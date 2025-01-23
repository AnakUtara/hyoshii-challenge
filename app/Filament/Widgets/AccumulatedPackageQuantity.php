<?php

namespace App\Filament\Widgets;

use App\Models\PackingPerformance;
use App\Models\PersonInCharge;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Enums\MaxWidth;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class AccumulatedPackageQuantity extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'accumulatedPackageQuantity';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Total Akumulasi Paket/Jam';

    protected static ?string $subheading = 'Paket yang diselesaikan per jam oleh PIC dengan kategorisasi tipe paket serta keseluruhan paket.';

    protected int | string | array $columnSpan = 'full';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $pics = PersonInCharge::all();
        $sumAll = [];
        $sumPackA = [];
        $sumPackB = [];
        $sumPackC = [];
        foreach($pics as $pic) {
            $where = PackingPerformance::where([
                ['person_in_charge_id','=', $pic->id],
                ['timestamp', '>', Carbon::parse($this->filterFormData['tanggal'])],
            ]);
            $packA = $where->sum('qty_pack_a_0_2kg');
            $packB = $where->sum('qty_pack_b_0_3kg');
            $packC = $where->sum('qty_pack_c_0_4kg');
            $sumPackA = $where == null ? [] : [...$sumPackA, $packA];
            $sumPackB = $where == null ? [] : [...$sumPackB, $packB];
            $sumPackC = $where == null ? [] : [...$sumPackC, $packC];
            $sumAll = $where == null ? [] : [...$sumAll, $packA + $packB + $packC];
        }
        return [
            'chart' => [
                'stacked' => true,
                'type' => 'bar',
                'height' => 400,
            ],
            'series' => [
                [
                    'name' => 'Akumulasi Pack C (0.4kg)/Jam',
                    'group' => 'Per Pack',
                    'data' => $sumPackC,
                ],
                [
                    'name' => 'Akumulasi Pack B (0.3kg)/Jam',
                    'group' => 'Per Pack',
                    'data' => $sumPackB,
                ],
                [
                    'name' => 'Akumulasi Pack A (0.2kg)/Jam',
                    'group' => 'Per Pack',
                    'data' => $sumPackA,

                ],
                [
                    'name' => 'Akumulasi Paket Selesai/Jam',
                    'group' => 'Per PIC',
                    'data' => $sumAll,
                ],
            ],
            'colors' => ['#1d4948', '#fda800', '#ff8000', '#ff0000'],
            'xaxis' => [
                'categories' => $pics->pluck('name'),
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                        'fontWeight' => 'bold'
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'legend' => [
                'position' => 'right',
                'horizontalAlign' => 'left',
            ]
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('tanggal')
                ->default(today())
        ];
    }
}
