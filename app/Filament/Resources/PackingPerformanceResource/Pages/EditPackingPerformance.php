<?php

namespace App\Filament\Resources\PackingPerformanceResource\Pages;

use App\Filament\Resources\PackingPerformanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPackingPerformance extends EditRecord
{
    protected ?bool $hasDatabaseTransactions = true;
    protected static string $resource = PackingPerformanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
