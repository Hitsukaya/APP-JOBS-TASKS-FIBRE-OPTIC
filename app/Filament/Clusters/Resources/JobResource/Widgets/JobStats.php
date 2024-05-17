<?php

namespace App\Filament\Clusters\Resources\JobResource\Widgets;

use App\Filament\Clusters\Resources\JobResource\Pages\ListJobs;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Zeroloss\TaskItem;
use App\Models\Zeroloss\Job;


class JobStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListJobs::class;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Jobs', $this->getPageTableQuery()->count()),
            //Stat::make('STATUS - "DESIGN"', $this->getPageTableQuery()->whereIn('status', ['open', 'design'])->count()),
            //Task::make('Status' $this->getPageTableQuery()->whereIn('status', ['open', 'design'])->count()),
            //Stat::make('Total Jobs', $this->getPageTableQuery()->count()),
            //Stat::make('Total Jobs', $this->getPageTableQuery()->count()),
            
            Card::make('Jobs Today', Job::whereDate('created_at', today())->count())
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
