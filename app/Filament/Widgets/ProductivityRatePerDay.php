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

    protected static ?int $sort = 4;
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
        $data = PersonInCharge::with(['packingPerformance' => function ($query) {
            $query->select([
                'person_in_charge_id',
                DB::raw('(sum(qty_pack_a_0_2kg) + sum(qty_pack_b_0_3kg) + sum(qty_pack_c_0_4kg)) / 600 as total_qty_packs_day'),
                'timestamp',
            ])
            ->whereDate('timestamp', '=', Carbon::parse($this->filterFormData['tanggal'])->toDateString())
            ->groupBy('person_in_charge_id');
        }])->get();

        $labels = [];
        $series = [];

        foreach ($data as $pic) {
            $picName = $pic->name;
            $totalQtyPacksDay = $pic->packingPerformance->first() == null ? 0 : round($pic->packingPerformance->first()->total_qty_packs_day);
            $labels[] = $picName;
            $series[] = $totalQtyPacksDay;
        }

        return [
            'chart' => [
                'type' => 'pie',
                'height' => 500,
                'distributed' => true
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

    protected function extraJsOptions(): ?RawJs
    {
        return RawJs::make(<<<'JS'
            {
                yaxis: {
                    labels: {
                        formatter: function (val) {
                            return '±' + val + ' paket/menit';
                        }
                    }
                },
            }
        JS);
    }
}
