<?php

namespace App\Filament\Widgets;

use App\Models\PackingPerformance;
use App\Models\PersonInCharge;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Support\RawJs;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class RejectAndGrossWeightRatioPerHour extends ApexChartWidget
{
    protected static ?int $sort = 5;
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'rejectAndGrossWeightRatioPerHour';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Reject Ratio/Jam';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $dataPerHour = PersonInCharge::with(['packingPerformance' => function ($query) {
                            $query->select([
                                'person_in_charge_id',
                                DB::raw('(reject_kg/gross_weight_kg) * 100  as reject_ratio'),
                                'timestamp',
                            ])->whereDate('timestamp', '=', Carbon::parse($this->filterFormData['tanggal'])->toDateString())
                            ->orderBy('timestamp');
                        }])
                        ->get();
        $timestamps = PackingPerformance::select('timestamp')->whereDate('timestamp', '=', Carbon::parse($this->filterFormData['tanggal'])->toDateString())->orderBy('timestamp')->distinct()->get();
        $chartDataPerHour = $dataPerHour->map(fn($value) => [
                                'name' => $value->name,
                                'data' => $value->packingPerformance->map(fn($value) =>
                                    round($value->reject_ratio),
                                )
                            ])->toArray();
        return [
            'chart' => [
                'type' => 'area',
                'height' => 300,
                'distributed' => true
            ],
            'series' => $chartDataPerHour,
            'labels' => $timestamps->map(fn($value) => Carbon::parse($value->timestamp)->format('H:i'))->toArray(),
            'legend' => [
                'labels' => [
                    'fontWeight' => 600,
                ],
            ],
            'dataLabels' => [
                'enabled' => true,
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
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

    protected function extraJsOptions(): ?RawJs
    {
        return RawJs::make(<<<'JS'
            {
                yaxis: {
                    labels: {
                        formatter: function (val) {
                            return val + '%';
                        }
                    }
                },
            }
        JS);
    }
}
