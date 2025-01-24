<?php

namespace App\Filament\Widgets;

use App\Models\PackingPerformance;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Support\RawJs;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class QuantityPacksRatioPerDay extends ApexChartWidget
{
    protected static ?int $sort = 8;
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'quantityPacksRatioPerDay';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Ratio Kuantitas Setiap Pack/Hari';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array

    {
        $data = PackingPerformance::select(
                    DB::raw('DATE(timestamp) as day'),
                    DB::raw('SUM(qty_pack_a_0_2kg) as total_pack_a'),
                    DB::raw('SUM(qty_pack_b_0_3kg) as total_pack_b'),
                    DB::raw('SUM(qty_pack_c_0_4kg) as total_pack_c'),
                    DB::raw('SUM(qty_pack_a_0_2kg + qty_pack_b_0_3kg + qty_pack_c_0_4kg) as total_packs')
                )
                ->where('day', Carbon::parse($this->filterFormData['tanggal'])->toDateString())
                ->groupBy('day')
                ->orderBy('day')
                ->get()
                ->flatMap(function ($row) {
                    $ratios = [
                            round(($row->total_pack_a / $row->total_packs) * 100, 2),
                            round(($row->total_pack_b / $row->total_packs) * 100, 2),
                            round(($row->total_pack_c / $row->total_packs) * 100, 2),
                    ];
                    return $ratios;
                });
        return [
            'chart' => [
                'type' => 'pie',
                'height' => 300,
                'distributed' => true
            ],
            'labels' => ['Pack A (0.2kg)', 'Pack B (0.3kg)', 'Pack C (0.4kg)'],
            'series' => $data,
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
