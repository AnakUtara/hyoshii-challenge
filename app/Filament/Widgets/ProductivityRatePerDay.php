<?php

namespace App\Filament\Widgets;

use App\Models\PackingPerformance;
use App\Models\PersonInCharge;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Support\RawJs;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ProductivityRatePerDay extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'productivityRatePerDay';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Tingkat Produktivitas PIC/Hari';

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
                DB::raw('sum(qty_pack_a_0_2kg + qty_pack_b_0_3kg + qty_pack_c_0_4kg) as total_qty_packs_day'),
                'timestamp',
            ])
            ->whereBetween('timestamp', [Carbon::parse($this->filterFormData['mulai_dari'])->toDateString(), Carbon::parse($this->filterFormData['sampai_dengan'])->toDateString()])
            ->groupBy('person_in_charge_id');
        }])->get();
        $timestamps = PackingPerformance::select('timestamp')->distinct()->get();
        $chartDataPerDay = $dataPerDay->map(fn($value) => [
            'name' => $value->name,
            'data' => $value->packingPerformance->map(fn($value) =>
                ceil($value->total_qty_packs_day / 600),
            )
        ])->toArray();
        return [
            'chart' => [
                'type' => 'line',
                'height' => 400,
                'distributed' => true
            ],
            'series' => $chartDataPerDay,
            'xaxis' => [
                'categories' => $timestamps->map(fn($value) => $value->timestamp->toDateString())->toArray(),
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'stepSize' => 1,
                'min' => 0,
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'stroke' => [
                'curve' => 'smooth',
            ],
        ];
    }

        protected function getFormSchema(): array
    {
        return [
            DatePicker::make('mulai_dari')->default(today()),
            DatePicker::make('sampai_dengan')->default(today()->addDays(5)),
        ];
    }

    protected function extraJsOptions(): ?RawJs
    {
        return RawJs::make(<<<'JS'
            {
                yaxis: {
                    labels: {
                        formatter: function (val) {
                            return val + ' paket/jam';
                        }
                    }
                },
            }
        JS);
    }
}
