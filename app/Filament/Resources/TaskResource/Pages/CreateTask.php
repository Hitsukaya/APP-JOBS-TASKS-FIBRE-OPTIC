<?php

namespace App\Filament\Resources\TaskResource\Pages;

use App\Filament\Resources\TaskResource;
use App\Models\Zeroloss\Task;
use App\Models\User;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use App\Filament\Clusters\Resources\JobResource;

class CreateTask extends CreateRecord
{
    use HasWizard;

    protected static string $resource = TaskResource::class;

    public function form(Form $form): Form
    {
        return parent::form($form)
            ->schema([
                Wizard::make($this->getSteps())
                    ->startOnStep($this->getStartStep())
                    ->cancelAction($this->getCancelFormAction())
                    ->submitAction($this->getSubmitFormAction())
                    ->skippable($this->hasSkippableSteps())
                    ->contained(false),
            ])
            ->columns(null);
    }


    protected function afterCreate(): void
    {
        /** @var Task $task */
        $task = $this->record;

        /** @var User $user */
        $user = auth()->user();

        Notification::make()
            ->title('New Task')
            ->icon('heroicon-o-flag')
            ->body("**{$task->engineer?->name} ordered {$task->items->count()} tasks.**")
            ->actions([
                Action::make('View')
                    ->url(TaskResource::getUrl('edit', ['record' => $task])),
            ])
            ->sendToDatabase($user);
    }

    /** @return Step[] */
    protected function getSteps(): array
    {
        return [
            Step::make('Assign Task Details - Items')
                ->schema([
                     Section::make()->schema([
                        TaskResource::getItemsRepeater(),
                     ]),
                    Section::make()->schema(TaskResource::getDetailsFormSchema())->columns(),
                ]),
        ];
    }
}
