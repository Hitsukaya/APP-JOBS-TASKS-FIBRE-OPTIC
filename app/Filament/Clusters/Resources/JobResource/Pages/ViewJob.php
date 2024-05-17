<?php

namespace App\Filament\Clusters\Resources\JobResource\Pages;

use App\Filament\Clusters\Resources\JobResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewJob extends ViewRecord
{
    protected static string $resource = JobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
