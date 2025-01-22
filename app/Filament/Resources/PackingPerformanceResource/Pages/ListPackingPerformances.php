<?php

namespace App\Filament\Resources\PackingPerformanceResource\Pages;

use App\Filament\Resources\PackingPerformanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPackingPerformances extends ListRecords
{
    protected static string $resource = PackingPerformanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
