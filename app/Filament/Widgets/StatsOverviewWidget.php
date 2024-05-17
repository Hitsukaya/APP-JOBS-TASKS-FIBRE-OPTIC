<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;
use App\Model\User;
use App\Model\Zeroloss\Engineer;


class StatsOverviewWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 0;

    protected function getStats(): array
    {

        $startDate = ! is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) :
            null;

        $endDate = ! is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) :
            now();

        $isBusinessEngineersOnly = $this->filters['businessEngineersOnly'] ?? null;
        $businessEngineerMultiplier = match (true) {
            boolval($isBusinessEngineersOnly) => 2 / 3,
            blank($isBusinessEngineersOnly) => 1,
            default => 1 / 3,
        };

        $diffInDays = $startDate ? $startDate->diffInDays($endDate) : 0;

        //$newStatus = (int) (($startDate ? ($diffInDays * 137) : 192100) * $businessEngineerMultiplier);
        $newEngineers = (int) (($startDate ? ($diffInDays * 7) : 1340) * $businessEngineerMultiplier);
        $newJobs = (int) (($startDate ? ($diffInDays * 13) : 3543) * $businessEngineerMultiplier);

        $formatNumber = function (int $number): string {
            if ($number < 1000) {
                return (string) Number::format($number, 0);
            }

            if ($number < 1000000) {
                return Number::format($number / 1000, 2) . 'k';
            }

            return Number::format($number / 1000000, 2) . 'm';
        };

        return [
            // Stat::make('Open Assign Task', '' . $formatNumber($newStatus))
            //     ->description('32k increase')
            //     ->descriptionIcon('heroicon-m-arrow-trending-up')
            //     ->chart([7, 2, 10, 3, 15, 4, 17])
            //     ->color('success'),
            // Stat::make('New Engineers', $formatNumber($newEngineers))
            //     ->description('3% decrease')
            //     ->descriptionIcon('heroicon-m-arrow-trending-down')
            //     ->chart([17, 16, 14, 15, 14, 13, 12])
            //     ->color('danger'),
            // Stat::make('New Assign Task', $formatNumber($newJobs))
            //     ->description('7% increase')
            //     ->descriptionIcon('heroicon-m-arrow-trending-up')
            //     ->chart([15, 4, 10, 2, 12, 4, 12])
            //     ->color('success'),
        ];
    }
}
