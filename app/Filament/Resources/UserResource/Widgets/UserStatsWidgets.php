<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\User;
use App\Models\Zeroloss\Task;



class UserStatsWidgets extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Users', User::count())
            ->description('7% increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->chart([15, 4, 10, 2, 12, 4, 12])
            ->color('success'),
            Card::make('Users Registered Today', User::whereDate('created_at', today())->count())
            ->description('7% increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->chart([15, 4, 10, 2, 12, 4, 12])
            ->color('success'),
            Card::make('Tasks Created Today', Task::whereDate('created_at', today())->count())
            ->description('7% increase')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->chart([15, 4, 10, 2, 12, 4, 12])
            ->color('success'),
        ];
    }

}
