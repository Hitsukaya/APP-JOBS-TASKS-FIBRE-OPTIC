<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum TaskStatus: string implements HasColor, HasIcon, HasLabel
{
    case readyforservice = 'readyforservice';

    case design = 'design';

    case trrcomplete = 'trrcomplete';

    case a55 = 'a55';

    case subducted = 'subducted';

    case cabled = 'cabled';

    public function getLabel(): string
    {
        return match ($this) {
            self::readyforservice => 'Ready for service',
            self::design => 'Design',
            self::trrcomplete => 'TRR Complete',
            self::a55 => 'A 55',
            self::subducted => 'Subducted',
            self::cabled => 'Cabled',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::readyforservice => 'info',
            self::design => 'warning',
            self::trrcomplete => 'success',
            self::a55 => 'success',
            self::subducted => 'danger',
            self::cabled => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::readyforservice => 'heroicon-m-sparkles',
            self::design => 'heroicon-m-arrow-path',
            self::trrcomplete => 'heroicon-m-truck',
            self::a55 => 'heroicon-m-check-badge',
            self::subducted => 'heroicon-m-x-circle',
            self::cabled => 'heroicon-m-x-circle',
        };
    }
}
