<?php

namespace App\Filament\Resources\TaskResource\Widgets;

use App\Filament\Resources\TaskResource\Pages\ListTasks;
use App\Models\Zeroloss\Task;
use App\Models\Zeroloss\Job;
//use App\Model\Zeroloss\TaskItem;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class TaskStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListTasks::class;
    }

    protected function getStats(): array
    {
        $orderData = Trend::model(Job::class)
            ->between(
                start: now()->subYear(),
                end: now(),
            )
            ->perMonth()
            ->count();

        return [
            Stat::make('Assign Task', $this->getPageTableQuery()->count())
                ->chart(
                    $orderData
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),
            //Stat::make('Open Assign Task', $this->getPageTableQuery()->whereIn('status', ['open', 'design'])->count()),
            //Stat::make('Problems', $this->getPageTableQuery()->whereIn('status', ['open', 'a55', 'subducted'])->count()),
        ];
    }
}
