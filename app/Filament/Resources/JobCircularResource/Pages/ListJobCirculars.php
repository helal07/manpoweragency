<?php

namespace App\Filament\Resources\JobCircularResource\Pages;

use App\Filament\Resources\JobCircularResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJobCirculars extends ListRecords
{
    protected static string $resource = JobCircularResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
