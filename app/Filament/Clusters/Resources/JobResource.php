<?php

namespace App\Filament\Clusters\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Enums\TaskStatus;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\Zeroloss\Job;
use App\Models\Zeroloss\Build;
use App\Filament\Clusters\Jobs;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Infolists\Components;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Clusters\Resources\JobResource\Widgets\JobStats;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\BooleanConstraint;
use App\Filament\Clusters\Resources\BrandResource\RelationManagers\JobsRelationManager;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;

class JobResource extends Resource
{
    protected static ?string $model = Job::class;

    protected static ?string $slug = '/jobs';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?string $navigationLabel = 'Jobs';

    protected static ?string $navigationGroup = 'Deployment';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                        if ($operation !== 'create') {
                                            return;
                                        }

                                        $set('slug', Str::slug($state));
                                    }),

                                Forms\Components\TextInput::make('slug')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(Job::class, 'slug', ignoreRecord: true),

                                Forms\Components\MarkdownEditor::make('description')
                                    ->columnSpan('full'),

                                FileUpload::make('document')
                                    ->acceptedFileTypes(['application/pdf']) 

                            ])
                            ->columns(2),

                            

                        Forms\Components\Section::make('Images')
                            ->schema([
                                
                                SpatieMediaLibraryFileUpload::make('media')->label('Imagine 1')
                                     ->collection('job-images')
                                     //->multiple()
                                     ->image(),
                                     //->preserveFilenames()
                                     //->visibility('public')
                                     //->maxFiles(5),
                                    //->hiddenLabel(),

                                SpatieMediaLibraryFileUpload::make('media1')->label('Imagine 2')
                                    ->collection('job-images')
                                    ->image()
                                    //->preserveFilenames()
                                    ->maxFiles(5),
                                   //->hiddenLabel(),

                                SpatieMediaLibraryFileUpload::make('media2')->label('Imagine 3')
                                   ->collection('job-images')
                                   ->image()
                                   //->preserveFilenames()
                                   ->maxFiles(5),
                                  //->hiddenLabel(),

                                SpatieMediaLibraryFileUpload::make('media3')->label('Imagine 4')
                                    ->collection('job-images')
                                    ->image()
                                    //->preserveFilenames()
                                    ->maxFiles(5),
                                 //->hiddenLabel(),   

                                SpatieMediaLibraryFileUpload::make('media4')->label('Imagine 5')
                                    ->collection('job-images')
                                    ->image()
                                    //->preserveFilenames()
                                    ->maxFiles(5),
                                //->hiddenLabel(),

                                SpatieMediaLibraryFileUpload::make('media5')->label('Imagine 5')
                                    ->collection('job-images')
                                    ->image()
                                    //->disk('s3')
                                    //->preserveFilenames()
                                    ->maxFiles(5),
                               //->hiddenLabel(),  

                        ])->columns(2)
                        ->collapsible(),

                     ])->columnSpan(['lg' => 2]),


                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Status')
                            ->schema([
                                Forms\Components\Toggle::make('is_visible')
                                    ->label('Visible')
                                    ->helperText('This job will be hidden from all sales channels.')
                                    ->default(true),

                                Forms\Components\DatePicker::make('published_at')
                                    ->label('Availability')
                                    ->default(now())
                                    ->required(),
                            ]),

                        Forms\Components\Section::make('Associations')
                            ->schema([
                                Forms\Components\Select::make('zeroloss_brand_id')
                                    ->relationship('brand', 'name'),
                                    //->searchable(),
                                    //->hiddenOn(JobsRelationManager::class),

                                Forms\Components\Select::make('builds')->label('Build')
                                    ->relationship('categories', 'name')->label('Build')
                                    ->multiple()
                                    ->required(),

                                // Forms\Components\ToggleButtons::make('status')
                                //     ->inline()
                                //     ->options(TaskStatus::class)
                                //     ->columnSpan([ 'md' => 5, ])
                                //     ->required(),      
                            ]),
                    ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\SpatieMediaLibraryImageColumn::make('job-image')
                //   ->label('Image')
                //   ->collection('job-images'),

               

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
               
                Tables\Columns\TextColumn::make('categories.name')->label('Build'),
               
                // Tables\Columns\TextColumn::make('status')
                //      ->badge(),

                Tables\Columns\TextColumn::make('brand.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_visible')
                    ->label('Visibility')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Publish Date')
                    ->date()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
            ])
            ->filters([
                QueryBuilder::make()
                    ->constraints([
                        TextConstraint::make('name'),
                        TextConstraint::make('slug'),
                        TextConstraint::make('description'),
                        BooleanConstraint::make('is_visible')
                            ->label('Visibility'),
                        BooleanConstraint::make('featured'),
                        BooleanConstraint::make('backorder'),
                        DateConstraint::make('published_at'),
                    ])
                    ->constraintPickerColumns(2),
            ], layout: Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->deferFilters()
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            Components\Section::make()->schema([
                Fieldset::make('Informations')
                ->schema([
                    Components\Grid::make(4)->schema([
                        TextEntry::make('name'),
                        TextEntry::make('description')->label('Description'),
                        TextEntry::make('categories.name')->label('Build'),
                        TextEntry::make('brand.name')->label('Brand'),
                    ])
                ])
                    ]),

            Components\Section::make()->schema([
                Fieldset::make('Images')
                ->schema([
                    Components\Grid::make(2)->schema([
                        TextEntry::make('document'),
                        //ImageEntry::make('media'),
                        SpatieMediaLibraryImageEntry::make('media') 
                            ->collection('job-images')
                            
                    ])
                ])
            ])
        ]);
}

    public static function getRelations(): array
    {
        return [
            \App\Filament\Clusters\Resources\JobResource\RelationManagers\CommentsRelationManager::class,
        ];
    }



    public static function getWidgets(): array
    {
        return [
            JobStats::class,
            //TaskStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Clusters\Resources\JobResource\Pages\ListJobs::route('/'),
            'create' => \App\Filament\Clusters\Resources\JobResource\Pages\CreateJob::route('/create'),
            'view' => \App\Filament\Clusters\Resources\JobResource\Pages\ViewJob::route('/{record}'),
            'edit' => \App\Filament\Clusters\Resources\JobResource\Pages\EditJob::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'brand.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /** @var Job $record */

        return [
            'Brand' => optional($record->brand)->name,
        ];
    }

    /** @return Builder<Job> */
    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['brand']);
    }

    public static function getNavigationBadge(): ?string
    {
        return Job::count();

        /** @var class-string<Model> $modelClass */
        $modelClass = static::$model;

        return (string) $modelClass::where('status', 'readyforservice')->count();

        return (string) $modelClass::where('status', 'design')->count();

        return (string) $modelClass::where('status', 'trrcomplete')->count();

        return (string) $modelClass::where('status', 'a55')->count();

        return (string) $modelClass::where('status', 'subducted')->count();

        return (string) $modelClass::where('status', 'cabled')->count();
        
    }

    //  /** @return Builder<Job> */
    //  public static function getEloquentQuery(): Builder
    //  {
    //      return parent::getEloquentQuery()->withoutGlobalScope(SoftDeletingScope::class);
    //  }   



}
