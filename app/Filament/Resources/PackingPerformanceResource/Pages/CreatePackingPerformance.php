<?php

namespace App\Filament\Resources\PackingPerformanceResource\Pages;

use App\Filament\Resources\PackingPerformanceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePackingPerformance extends CreateRecord
{
    protected ?bool $hasDatabaseTransactions = true;
    protected static string $resource = PackingPerformanceResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $details = $data['packing_performance_details'];
        $lastCreatedModel = [];
        if(collect($details)->count() > 0) {
            foreach($details as $detail) {
                $lastCreatedModel = static::getModel()::create([
                    ...$detail,
                    'timestamp' => $data['timestamp']
                ]);
            }
        }
        return $lastCreatedModel;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
