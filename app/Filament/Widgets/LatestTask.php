<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\TaskResource;
use App\Models\Zeroloss\Task;
use App\Models\Zeroloss\Job;
use App\Models\User;
use App\Models\Zeroloss\TaskItem;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestTask extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(TaskResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Task Date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('number')->label('No. Task')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('Engineers')
                    ->searchable()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('status')
                //     ->badge(),
            ])
            ->actions([
                Tables\Actions\Action::make('open')
                    ->url(fn (Task $record): string => TaskResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
