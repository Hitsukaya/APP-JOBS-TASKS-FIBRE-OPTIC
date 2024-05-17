<?php

namespace App\Filament\Clusters\Resources;

//use App\Filament\Clusters\Zeroloss;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\Zeroloss\Job;
use App\Models\Zeroloss\Build;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\View;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;


class CategoryResource extends Resource
{
    protected static ?string $model = Build::class;

    protected static ?string $slug = '/builds';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Deployment';

    //protected static ?string $navigationParentItem = 'Build';


    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return Build::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                                Forms\Components\TextInput::make('slug')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(Build::class, 'slug', ignoreRecord: true),
                            ]),

                        Forms\Components\Select::make('parent_id')
                            ->label('Parent')
                            ->relationship('parent', 'name', fn (Builder $query) => $query->where('parent_id', null))
                            //->searchable()
                            ->placeholder('Select parent category'),

                        Forms\Components\Toggle::make('is_visible')
                            ->label('Visible to engineers.')
                            ->default(true),

                        Forms\Components\MarkdownEditor::make('description')
                            ->label('Description'),

                        FileUpload::make('document')
                            ->acceptedFileTypes(['application/pdf'])
                            ->openable()
                            ->downloadable()    
                    ])
                    ->columnSpan(['lg' => fn (?Build $record) => $record === null ? 3 : 2]),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn (Build $record): ?string => $record->created_at?->diffForHumans()),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn (Build $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?Build $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Parent')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_visible')
                    ->label('Visibility')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated Date')
                    ->date()
                    ->sortable(),
                
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->groupedBulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->action(function () {
                        Notification::make()
                            ->title('Now, now, don\'t be cheeky, leave some records for others to play with!')
                            ->warning()
                            ->send();
                    }),
            ]);
    }


    public static function infolist(Infolist $infolist): Infolist
{
    return $infolist
        ->schema([
            Section::make()->schema([
                Fieldset::make('Informations')
                ->schema([
                    Grid::make(4)->schema([
                        TextEntry::make('name'),
                        TextEntry::make('parent.name')->label('Sub_build'),
                        TextEntry::make('description')->label('Description'),
                        TextEntry::make('updated_at')->label('Updated Date'),
                        TextEntry::make('document')->label('Document'),
                        
                    ])
                ])
             ])
        ]);
}

    public static function getRelations(): array
    {
        return [
            \App\Filament\Clusters\Resources\CategoryResource\RelationManagers\JobsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Clusters\Resources\CategoryResource\Pages\ListCategories::route('/'),
            'create' => \App\Filament\Clusters\Resources\CategoryResource\Pages\CreateCategory::route('/create'),
            'view' => \App\Filament\Clusters\Resources\CategoryResource\Pages\ViewCategory::route('/{record}'),
            'edit' => \App\Filament\Clusters\Resources\CategoryResource\Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
