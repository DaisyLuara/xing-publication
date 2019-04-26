<?php

use Illuminate\Database\Seeder;
use GuzzleHttp\Client;

class GeoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = new Client();
        $uri = "http://api.map.baidu.com/geocoder/v2/?output=json&ak=GGkofmc3GyYkjPhKRptlyiRSbAW5IZup";

        $address = DB::connection('ar')->table('avr_official')
            ->leftJoin('avr_official_area', 'avr_official_area.areaid', '=', 'avr_official.areaid')
            ->leftJoin('avr_official_market', 'avr_official_market.marketid', '=', 'avr_official.marketid')
            ->whereNotIn('avr_official.oid', [0, 30, 31])
            ->get(['avr_official.oid', 'avr_official_area.name as area_name', 'avr_official_market.name as market_name', 'avr_official.name as official_name']);

        $address->each(function ($item) use ($uri, $client) {
            $official_name_arr = explode('-', $item->official_name);
            array_pop($official_name_arr);
            $region = $item->area_name;

            $address = $region . $item->market_name;

            $uri = $uri . "&address=$address";
            $res = $client->request('GET', $uri)->getBody()->getContents();
            $res = json_decode($res, true);
            Log::info('address>>' . $address, ['res' => $res]);

            if ($res['status'] == 0) {
                $update_data = $res['result']['location'];
                DB::connection('ar')->table('avr_official')->where('oid', '=', $item->oid)->update($update_data);
            }

        });

    }

}
