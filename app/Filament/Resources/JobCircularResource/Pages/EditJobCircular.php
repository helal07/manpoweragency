<?php

namespace App\Filament\Resources\JobCircularResource\Pages;

use App\Filament\Resources\JobCircularResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJobCircular extends EditRecord
{
    protected static string $resource = JobCircularResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
