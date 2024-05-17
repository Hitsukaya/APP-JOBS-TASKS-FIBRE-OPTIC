<?php

namespace App\Filament\Resources\EngineerResource\Pages;

use App\Filament\Resources\EngineerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEngineers extends ListRecords
{
    protected static string $resource = EngineerResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
