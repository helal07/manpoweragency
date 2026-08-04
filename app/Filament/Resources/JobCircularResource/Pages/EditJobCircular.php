<?php

namespace App\Filament\Resources\JobCircularResource\Pages;

use App\Filament\Resources\JobCircularResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditJobCircular extends EditRecord
{
    protected static string $resource = JobCircularResource::class;

    public function getMaxContentWidth(): Width | string | null
    {
        return Width::Full;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Load existing pivot data into the repeater when editing.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['customFieldRequirements'] = $this->record
            ->customFields()
            ->get()
            ->map(fn ($field) => [
                'custom_field_id' => $field->id,
                'is_required' => (bool) $field->pivot->is_required,
            ])
            ->toArray();

        return $data;
    }

    protected function afterSave(): void
    {
        $this->syncCustomFields();
    }

    protected function syncCustomFields(): void
    {
        $requirements = $this->data['customFieldRequirements'] ?? [];

        $syncData = [];
        $order = 1;

        foreach ($requirements as $item) {
            $fieldId = $item['custom_field_id'] ?? null;
            if ($fieldId) {
                $syncData[$fieldId] = [
                    'is_required' => (bool) ($item['is_required'] ?? false),
                    'sort_order' => $order++,
                ];
            }
        }

        $this->record->customFields()->sync($syncData);
    }
}
