<?php

namespace App\Filament\Widgets;

use Closure;
use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Zeroloss\Job;
use App\Models\Zeroloss\Task;
use App\Models\Zeroloss\Brand;
use App\Models\Zeroloss\Build;
use App\Filament\Clusters\Jobs;
use App\Models\Zeroloss\Engineer;
use App\Models\Zeroloss\TaskItem;
use App\Filament\Resources\JobResource;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\TaskResource;
use App\Filament\Resources\BrandResource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CategoryResource;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Filament\Clusters\Resources\BrandResource\RelationManagers\JobsRelationManager;
use App\Filament\Clusters\Resources\JobResource as ResourcesJobResource;
use Illuminate\Queue\Jobs\JobName;

class StatusTasks extends BaseWidget
{

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    protected function getTableQuery(): Builder
    {
        return TaskITem::query()->latest();
    }

    protected function  getEloquentQuery(): Builder
    {
        return TaskResource::getEloquentQuery();
        return Job::getEloquentQuery();
        return Build::getEloquentQuery();
        return Brand::getEloquentQuery();
    }

    // public static function getEloquentQuery(): Builder
    // {
    //    return parent::getEloquentQuery()->where('zeroloss_task_id',auth()->id());
    // }

    protected function getTableColumns(): array
    {
        return
            [           
                // TextColumn::make('id')
                //     ->label('No.'),
                TextColumn::make('created_at')
                    ->label('Task Date')->date()->sortable()->dateTime(),
                TextColumn::make('task.number')
                    ->label('No. Task'),
                TextColumn::make('job.name')
                    ->label('Jobs'),                   
                TextColumn::make('build.name') //Relationship not defined - create & migrate table or models create functions
                    ->label('Builds'),
                TextColumn::make('task.user.name')
                    ->label('Engineers'),
                TextColumn::make('brand.name')
                    ->label('Brand'),
                TextColumn::make('status')
                    ->label('Status')->badge(),

            ];
    }
}
