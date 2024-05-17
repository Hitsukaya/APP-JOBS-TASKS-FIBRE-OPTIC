<?php

namespace App\Filament\Clusters\Resources\BrandResource\RelationManagers;

use App\Filament\Clusters\Resources\JobResource;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class JobsRelationManagerSecond extends RelationManager
{
    protected static string $relationship = 'jobs';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return JobResource::form($form);
    }

    public function table(Table $table): Table
    {
        return JobResource::table($table)
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ])
            ->groupedBulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
