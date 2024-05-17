<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Resources\UserResource\Widgets\UserStatsWidgets;
use App\Filament\Widgets\EngineersWidgets;


class Dashboard extends BaseDashboard
{
    use BaseDashboard\Concerns\HasFiltersForm;

    // public function filtersForm(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Section::make()
    //                 ->schema([
    //                     Select::make('businessEngineersOnly')
    //                         ->boolean(),
    //                     DatePicker::make('startDate')
    //                         ->maxDate(fn (Get $get) => $get('endDate') ?: now()),
    //                     DatePicker::make('endDate')
    //                         ->minDate(fn (Get $get) => $get('startDate') ?: now())
    //                         ->maxDate(now()),
    //                 ])
    //                 ->columns(3),
    //         ]);
    // }

    protected function getHeaderWidgets(): array
    {
        return [
            UserStatsWidgets::class,
            //EngineersWidgets::class
        ];
    }

}
