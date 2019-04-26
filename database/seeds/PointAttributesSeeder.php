<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\Admin\Attribute\V1\Models\Attribute;
use App\Http\Controllers\Admin\Attribute\V1\Models\PointAttribute;

class PointAttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        #点位拆分
        DB::connection('ar')->table('xs_point_attributes')->truncate();

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load(base_path('database/seeds') . '/' . '点位拆分.xlsx');

        $cells = $spreadsheet->getSheet(0)->toArray();
        array_shift($cells);

        foreach ($cells as $cell) {
            $point = DB::connection('ar')->table('avr_official')
                ->join('avr_official_area', 'avr_official.areaid', '=', 'avr_official_area.areaid')
                ->join('avr_official_market', 'avr_official.marketid', '=', 'avr_official_market.marketid')
                ->where('avr_official_area.name', '=', $cell[0])
                ->where('avr_official_market.name', '=', $cell[1])
                ->where('avr_official.name', '=', $cell[2])
                ->first();

            $data = [$cell[7], $cell[8]];
            $attributes = Attribute::query()->whereIn('name', $data)->get(['id']);
            if ($point) {
                foreach ($attributes as $attribute) {
                    PointAttribute::create(['attribute_id' => $attribute->id, 'point_id' => $point->oid]);
                }
            } else {
                Log::info('point_not_found');
            }
        }
    }
}
