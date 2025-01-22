<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackingPerformanceResource\Pages;
use App\Filament\Resources\PackingPerformanceResource\RelationManagers;
use App\Models\PackingPerformance;
use App\Models\PersonInCharge;
use Carbon\Carbon;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PackingPerformanceResource extends Resource
{
    protected static ?string $model = PackingPerformance::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DateTimePicker::make('timestamp')->required()->displayFormat('Y/m/d H:i:s')->timezone(env('APP_TIMEZONE'))->default(today())->minDate(function(Get $get, string $operation, PackingPerformance $packingPerformance) {
                    $details = $get('packing_performance_details');
                    if($operation === 'create'){
                        $isExists = null;
                        foreach($details as $detail){
                            $pic = $detail["person_in_charge_id"];
                            $isExists = $packingPerformance->where('person_in_charge_id', $pic)->first();
                        }
                        return $isExists ? $isExists->timestamp->addHours(1) : today();
                    } else {
                        return today();
                    }
                })->label('Timestamp'),
                Repeater::make('packing_performance_details')->label('Packing Details')->schema([
                    Grid::make(3)->schema([
                        Select::make('person_in_charge_id')->label('Person In Charge')->relationship('pics', 'name')->required()
                        ->searchable()->preload()->createOptionForm([
                            TextInput::make('name')->required()->maxLength(255)->unique(),
                        ])->distinct(),
                        TextInput::make('gross_weight_kg')->label('Berat Kotor (Kg)')->required()->numeric()->inputMode('decimal'),
                        TextInput::make('reject_kg')->label('Reject (Kg)')->required()->numeric()->inputMode('decimal'),
                    ]),
                    Section::make('Kuantitas Pak')->description('Jumlah kuantitas yang berhasil dikerjakan untuk masing-masing tipe pak.')->schema([
                        TextInput::make('qty_pack_a_0_2kg')->label('Quantity Pack A (0.2kg)')->required()->numeric(),
                        TextInput::make('qty_pack_b_0_3kg')->label('Quantity Pack B (0.3kg)')->required()->numeric(),
                        TextInput::make('qty_pack_c_0_4kg')->label('Quantity Pack C (0.4kg)')->required()->numeric(),
                    ])->columns(3)->compact()
                ])->columnSpanFull()->addActionAlignment(Alignment::Start)->addActionLabel('Add Packing Details')
                ->minItems(1)->maxItems(PersonInCharge::all()->count())->visibleOn('create')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPackingPerformances::route('/'),
            'create' => Pages\CreatePackingPerformance::route('/create'),
            'edit' => Pages\EditPackingPerformance::route('/{record}/edit'),
        ];
    }
}
