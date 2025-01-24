<?php

namespace App\Filament\Widgets;

use App\Models\PersonInCharge;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class RejectAndGrossWeightRatioPerDay extends ApexChartWidget
{
    protected static ?int $sort = 5;
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'rejectAndGrossWeightRatioPerDay';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Reject Ratio/Hari';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $dataPerDay = PersonInCharge::with(['packingPerformance' => function ($query) {
            $query->select([
                'person_in_charge_id',
                DB::raw('(sum(reject_kg)/sum(gross_weight_kg)) * 100  as reject_ratio'),
                DB::raw('DATE("timestamp") as days'),
            ])
            ->where('days', '=', Carbon::parse($this->filterFormData['tanggal'])->toDateString())
            ->groupBy('person_in_charge_id');
        }])->get();

        $labels = $dataPerDay->map(fn($value) => $value->name)->toArray();
        $packingPerformance = $dataPerDay->flatMap(fn($value) => $value->packingPerformance);
        $series = $packingPerformance->map(fn($value) => round($value->reject_ratio))->toArray();
        return [
            'chart' => [
                'type' => 'donut',
                'height' => 300,
            ],
            'labels' => $labels,
            'series' => $series,
            'legend' => [
                'labels' => [
                    'fontFamily' => 'inherit',
                ],
            ],
        ];
    }


    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('tanggal')->default(today()),
        ];
    }
}
