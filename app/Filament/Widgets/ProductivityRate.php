<?php

namespace App\Filament\Widgets;

use App\Models\PackingPerformance;
use App\Models\PersonInCharge;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Support\RawJs;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ProductivityRate extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'productivityRate';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Tingkat Produktivitas PIC/Jam';

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
                                DB::raw('qty_pack_a_0_2kg + qty_pack_b_0_3kg + qty_pack_c_0_4kg as total_qty_packs'),
                                'timestamp',
                            ])->where('timestamp', '>=', Carbon::parse($this->filterFormData['tanggal'])->toDateString())->orderBy('timestamp');
                        }])
                        ->get();
        $timestamps = PackingPerformance::select('timestamp')->distinct()->get();
        $chartDataPerHour = $dataPerHour->map(fn($value) => [
                                'name' => $value->name,
                                'data' => $value->packingPerformance->map(fn($value) =>
                                    ceil($value->total_qty_packs / 60),
                                )
                            ])->toArray();
        return [
            'chart' => [
                'type' => 'line',
                'height' => 400,
                'distributed' => true,
            ],
            'series' => $chartDataPerHour,
            'xaxis' => [
                'categories' => $timestamps->map(fn($value) => $value->timestamp->format('H:i:s'))->toArray(),
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
                            return val + ' paket/menit';
                        }
                    }
                },
            }
        JS);
    }
}
