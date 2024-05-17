<?php

namespace App\Filament\Resources;

use App\Enums\TaskStatus;
use App\Filament\Clusters\Resources\JobResource;
use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Filament\Resources\TaskResource\Widgets\TaskStats;
use App\Forms\Components\AddressForm;
use App\Models\Zeroloss\Task;
//use App\Models\Zeroloss\TaskItem;
use App\Models\Zeroloss\Job;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Carbon;
use Filament\Forms\Components\FileUpload;



class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $slug = 'deployment/assign-task';

    protected static ?string $recordTitleAttribute = 'number';

    protected static ?string $navigationGroup = 'Deployment';

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    protected static ?string $label ='Task';

    protected static ?int $navigationSort = 4;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema(static::getDetailsFormSchema())
                            ->columns(2),

                        Forms\Components\Section::make('Assign items')
                              ->headerActions([
                                //   Action::make('reset')
                                //       ->modalHeading('Are you sure?')
                                //       ->modalDescription('All existing items will be removed from the task.')
                                //       ->requiresConfirmation()
                                //       ->color('danger')
                                //       ->action(fn (Forms\Set $set) => $set('items', [])),
                              ])
                              ->schema([
                                  static::getItemsRepeater(),
                              ]),
                    ])
                    ->columnSpan(['lg' => fn (?Task $record) => $record === null ? 3 : 2]),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn (Task $record): ?string => $record->created_at?->diffForHumans()),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn (Task $record): ?string => $record->updated_at?->diffForHumans()),
                        // Forms\Components\Placeholder::make('zeroloss_user_id')
                        // ->label('Engineer modified')
                        //->content(fn (User $record): ?string => $record->updated_at?->diffForHumans()),
                        

                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?Task $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('Engineers')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                // Tables\Columns\TextColumn::make('status')
                //     ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Assign Task Date')
                    ->date()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->placeholder(fn ($state): string => 'Dec 18, ' . now()->subYear()->format('Y')),
                        Forms\Components\DatePicker::make('created_until')
                            ->placeholder(fn ($state): string => now()->format('M d, Y')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'] ?? null,
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators['created_from'] = 'Task from ' . Carbon::parse($data['created_from'])->toFormattedDateString();
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators['created_until'] = 'Task until ' . Carbon::parse($data['created_until'])->toFormattedDateString();
                        }

                        return $indicators;
                    }),
            ])
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
            ])
            ->groups([
                Tables\Grouping\Group::make('created_at')
                    ->label('Assign Task Date')
                    ->date()
                    ->collapsible(),
            ]);
    }

    public static function getWidgets(): array
    {
        return [
            TaskStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }

    /** @return Builder<Task> */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScope(SoftDeletingScope::class);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['number', 'engineer.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /** @var Task $record */

        return [
            'Engineer' => optional($record->engineer)->name,
        ];
    }

    /** @return Builder<Task> */
    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['engineer', 'items']);

        //return parent::getGlobalSearchEloquentQuery()->with(['users', 'items']);
    }

    // public static function getNavigationBadge(): ?string
    // {
    //     /** @var class-string<Model> $modelClass */
    //     $modelClass = static::$model;

    //     return (string) $modelClass::where('status', 'readyforservice')->count();

    //     return (string) $modelClass::where('status', 'design')->count();
    // }

    /** @return Forms\Components\Component[] */
    public static function getDetailsFormSchema(): array
    {
        return [
                    Forms\Components\TextInput::make('number')
                        ->default('TS-' . random_int(100000, 999999))
                        ->disabled()
                        ->dehydrated()
                        ->required()
                        ->maxLength(32)
                        ->unique(Task::class, 'number', ignoreRecord: true),

             //Migrate for use field user 
                    //Forms\Components\Select::make('zeroloss_engineer_id')->label('Engineers')
                    Forms\Components\Select::make('user_id')->label('Engineer')
                        //->relationship('engineer', 'name')
                        ->relationship('user', 'name')
                        //->searchable()                       
                        ->required()
                        ->createOptionForm([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('email')
                        ->label('Email address')
                        ->required()
                        ->email()
                        ->maxLength(255)
                        ->unique(),

                    Forms\Components\TextInput::make('phone')
                        ->maxLength(255),

                    Forms\Components\Select::make('gender')
                        ->placeholder('Select gender')
                        ->options([
                            'male' => 'Male',
                            'female' => 'Female',
                        ])
                        ->required()
                        ->native(false),
                ])
                ->createOptionAction(function (Action $action) {
                    return $action
                        ->modalHeading('Create Engineers')
                        ->modalSubmitActionLabel('Create Engineers')
                        ->modalWidth('lg');
                }),

            // Forms\Components\ToggleButtons::make('status')
            //     ->inline()
            //     ->options(TaskStatus::class)
            //     ->required(),

            //Migration for create column 'zeroloss_job_id'
            // Forms\Components\Select::make('zeroloss_job_id')
            //      ->label('Job')
            //      ->options(Job::query()->pluck('name', 'id'))
            //      ->required()
            //      ->reactive()
            //      ->distinct()
            //      ->searchable(),               

            //Migration for create document column 'zeroloss_task_id'
            // Forms\Components\FileUpload::make('document')->label('Document PDF')
            //     ->acceptedFileTypes(['application/pdf']),
            //Migrate for create imagine column 'zeroloss_task_id'    
            // Forms\Components\FileUpload::make('image')
            //      ->image()  
            //      ->acceptedFileTypes(['application/jpg,png,jpeg,bmp']),   


            // AddressForm::make('address')
            //     ->columnSpan('full'),

             Forms\Components\MarkdownEditor::make('notes')
                 ->columnSpan('full'),

        ];
    }

     public static function getItemsRepeater(): Repeater
     {
         return Repeater::make('items')
             ->relationship()
             ->schema([
                Forms\Components\Select::make('zeroloss_job_id')
                     ->label('Job')
                     ->options(Job::query()->pluck('name', 'id'))
                     ->required()
                     ->reactive()
                     ->distinct()
                     ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                     ->columnSpan([
                         'md' => 5,
                     ])
                     ->searchable(),

                Forms\Components\ToggleButtons::make('status')
                     ->inline()
                     ->options(TaskStatus::class)
                     ->columnSpan([ 'md' => 5, ])
                     ->required(),  
   
             ])
             ->extraItemActions([
                 Action::make('openTask')
                     ->tooltip('Open Assign Task')
                     ->icon('heroicon-m-arrow-top-right-on-square')
                     ->url(function (array $arguments, Repeater $component): ?string {
                         $itemData = $component->getRawItemState($arguments['item']);

                         $job = Job::find($itemData['zeroloss_job_id']);

                         if (! $job) {
                             return null;
                         }

                         return JobResource::getUrl('edit', ['record' => $job]);
                     }, shouldOpenInNewTab: true)
                     ->hidden(fn (array $arguments, Repeater $component): bool => blank($component->getRawItemState($arguments['item'])['zeroloss_job_id'])),
             ])
             ->orderColumn('sort')
             ->defaultItems(1)
             ->hiddenLabel()
             ->columns([
                 'md' => 10,
             ])
             ->required();
     }
}
