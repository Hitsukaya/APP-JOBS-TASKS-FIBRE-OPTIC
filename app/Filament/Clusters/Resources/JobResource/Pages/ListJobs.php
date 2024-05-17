<?php

namespace App\Filament\Clusters\Resources\JobResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use App\Filament\Resources\TaskResource;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Clusters\Resources\JobResource;
use Filament\Pages\Concerns\ExposesTableToWidgets;

class ListJobs extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = JobResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return JobResource::getWidgets();
        //return TaskResource::getWidgets();
    }

    // public function getTabs(): array
    // {
    //     return [
    //         null => Tab::make('All'),
    //         'readyforservice' => Tab::make()->query(fn ($query) => $query->where('status', 'readyforservice'))->label('Ready For Service'),
    //         'design' => Tab::make()->query(fn ($query) => $query->where('status', 'design'))->label('Design'),
    //         'trrcomplete' => Tab::make()->query(fn ($query) => $query->where('status', 'trrcomplete'))->label('TRR Complete'),
    //         'a55' => Tab::make()->query(fn ($query) => $query->where('status', 'a-55'))->label('A 55'),
    //         'subducted' => Tab::make()->query(fn ($query) => $query->where('status', 'subducted'))->label('Subducted'),
    //         'cabled' => Tab::make()->query(fn ($query) => $query->where('status', 'cabled'))->label('Cabled'),
    //     ];
    // }

}
