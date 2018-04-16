<?php

use Illuminate\Database\Seeder;

class WxWarningFormatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('wx_warnings')->orderBy('id', 'desc')->chunk(100, function ($wx_warnings) {
            $wx_warnings->each(function ($wx_warning) {
                $wx_warning = json_decode(json_encode($wx_warning), true);
                $message = explode(PHP_EOL, $wx_warning['message']);
                $input = [];
                $length = count($message);
                if ($length > 0) {
                    $input = [
                        'address' => $message[0],
                        'reason' => $message[$length - 1],
                        'market_id' => $this->getMarketId($message[0]),
                        'product_id' => $this->getProductId($wx_warning['project']),
                    ];
                }

                DB::table('wx_warnings')->where('id', '=', $wx_warning['id'])->update($input);
            });
        });
    }

    private function getMarketId($address)
    {
        $address = explode('-', $address);
        $length = count($address);
        if ($length > 1) {
            $market_name = $address[1];
            $market = DB::connection('jingsaas')
                ->table('oc_avr_official_market')
                ->where('name', '=', $market_name)
                ->first();
            return $market ? $market->marketid : 0;
        }
        return 0;
    }

    private function getProductId($product_name)
    {
        $product = DB::connection('jingsaas')
            ->table('oc_ar_product_list')
            ->where('name', '=', $product_name)
            ->first();
        return $product ? $product->id : 0;
    }

}
