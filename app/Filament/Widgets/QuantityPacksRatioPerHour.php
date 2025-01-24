<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Support\RawJs;
use Illuminate\Support\Str;
use App\Models\PackingPerformance;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\DatePicker;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class QuantityPacksRatioPerHour extends ApexChartWidget
{
    protected static ?int $sort = 7;
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'quantityPacksRatioPerHour';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Ratio Kuantitas Setiap Pack/Jam';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $dataPerHour = PackingPerformance::select(
                            DB::raw('TIME(timestamp) as hour'),
                            DB::raw('SUM(qty_pack_a_0_2kg) as total_pack_a'),
                            DB::raw('SUM(qty_pack_b_0_3kg) as total_pack_b'),
                            DB::raw('SUM(qty_pack_c_0_4kg) as total_pack_c'),
                            DB::raw('SUM(qty_pack_a_0_2kg + qty_pack_b_0_3kg + qty_pack_c_0_4kg) as total_packs')
                        )
                        ->whereDate('timestamp', '=', Carbon::parse($this->filterFormData['tanggal'])->toDateString())
                        ->groupBy(DB::raw('TIME(timestamp)'))
                        ->orderBy('hour')
                        ->get()
                        ->map(function ($row) {
                            return [
                                'hour' => $row->hour,
                                'ratio_pack_a' => round(($row->total_pack_a / $row->total_packs) * 100),
                                'ratio_pack_b' => round(($row->total_pack_b / $row->total_packs) * 100),
                                'ratio_pack_c' => round(($row->total_pack_c / $row->total_packs) * 100)
                            ];
                        });
        $timestamps = PackingPerformance::select('timestamp')->whereDate('timestamp', '=', Carbon::parse($this->filterFormData['tanggal'])->toDateString())->orderBy('timestamp')->distinct()->get();
        $chartDataPerHour = [
            [
                'name' => 'Pack A (0.2kg)',
                'data' => $dataPerHour->pluck('ratio_pack_a')->toArray(),
            ],
            [
                'name' => 'Pack B (0.3kg)',
                'data' => $dataPerHour->pluck('ratio_pack_b')->toArray(),
            ],
            [
                'name' => 'Pack C (0.4kg)',
                'data' => $dataPerHour->pluck('ratio_pack_c')->toArray(),
            ],
        ];
        // dd($chartDataPerHour);
        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
                'distributed' => true,
            ],
            'series' =>$chartDataPerHour,
            'xaxis' => [
                'categories' => $timestamps->map(fn($value) => $value->timestamp->format('H:i:s'))->toArray(),
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'stepSize' => 10,
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
                            return val + '%';
                        }
                    }
                },
            }
        JS);
    }
}
