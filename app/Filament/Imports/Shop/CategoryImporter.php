<?php

namespace App\Filament\Imports\Zeroloss;

use App\Models\Zeroloss\Build;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class CategoryImporter extends Importer
{
    protected static ?string $model = Build::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->example('Build A'),
            ImportColumn::make('slug')
                ->requiredMapping()
                ->rules(['required', 'max:255'])
                ->example('build-a'),
            ImportColumn::make('parent')
                ->relationship(resolveUsing: ['name', 'slug'])
                ->example('Build B'),
            ImportColumn::make('description')
                ->example('This is the description for Build A.'),
            ImportColumn::make('position')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer'])
                ->example('1'),
            ImportColumn::make('is_visible')
                ->label('Visibility')
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean'])
                ->example('yes'),
            ImportColumn::make('seo_title')
                ->label('SEO title')
                ->rules(['max:60'])
                ->example('Awesome Build A'),
            ImportColumn::make('seo_description')
                ->label('SEO description')
                ->rules(['max:160'])
                ->example('Wow! It\'s just so amazing.'),
        ];
    }

    public function resolveRecord(): ?Build
    {
        return Build::firstOrNew([
            'slug' => $this->data['slug'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your zeroloss build  import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
