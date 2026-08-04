<?php

namespace App\Filament\Resources\JobCircularResource\Pages;

use App\Filament\Resources\JobCircularResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateJobCircular extends CreateRecord
{
    protected static string $resource = JobCircularResource::class;

    public function getMaxContentWidth(): Width | string | null
    {
        return Width::Full;
    }

    protected function afterCreate(): void
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
