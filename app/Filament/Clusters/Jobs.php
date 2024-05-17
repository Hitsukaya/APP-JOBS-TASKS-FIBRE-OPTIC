<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Jobs extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?int $navigationSort = 0;

    protected static ?string $slug = 'deployment/build';

    protected static ?string $navigationGroup = 'Deployment';

    protected static ?string $title = 'Build';
}
