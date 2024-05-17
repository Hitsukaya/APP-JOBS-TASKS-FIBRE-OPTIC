<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Zeroloss\Engineer;
use App\Models\User;
use App\Models\Zeroloss\Task;
use App\Models\Zeroloss\Job;
use App\Models\Zeroloss\Build;
use App\Models\Zeroloss\Brand;
use App\Models\Zeroloss\TaskItem;

class EngineersWidgets extends BaseWidget
{

    protected function getTablePage(): string
    {
        return ListTasks::class;
    }

    protected function getCards(): array
    {
        return [
            Card::make('Total Engineers', User::count())
            ->description('7% increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->chart([15, 4, 10, 2, 12, 4, 12])
            ->color('success'),
            Card::make('Engineer of Task', Task::whereDate('created_at', today())->count())
            ->description('7% increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->chart([15, 4, 10, 2, 12, 4, 12])
            ->color('success'),
            Card::make('Jobs', Job::whereDate('created_at', today())->count())
            ->description('7% increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->chart([15, 4, 10, 2, 12, 4, 12])
            ->color('success'),
            Card::make('Builds', Build::whereDate('created_at', today())->count())
            ->description('7% increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->chart([15, 4, 10, 2, 12, 4, 12])
            ->color('success'),
            Card::make('Brands', Brand::whereDate('created_at', today())->count())
            ->description('7% increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->chart([15, 4, 10, 2, 12, 4, 12])
            ->color('success'),
            Card::make('TaskItem', TaskItem::whereDate('created_at', today())->count())
            ->description('7% increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->chart([15, 4, 10, 2, 12, 4, 12])
            ->color('success'),
        ];
    }
}
