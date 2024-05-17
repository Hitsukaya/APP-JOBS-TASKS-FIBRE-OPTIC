<?php

namespace App\Filament\Resources\EngineerResource\Pages;

use App\Filament\Resources\EngineerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEngineer extends EditRecord
{
    protected static string $resource = EngineerResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\RestoreAction::make(),
            Actions\ForceDeleteAction::make(),
        ];
    }
}
