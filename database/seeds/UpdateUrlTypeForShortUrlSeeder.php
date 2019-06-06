<?php

use Illuminate\Database\Seeder;
use App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrl;

class UpdateUrlTypeForShortUrlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $urls = ShortUrl::query()->get();
        foreach ($urls as $url) {
            if (!strpos($url->target_url, 'xingstation')) {
                $url->update(['url_type' => 1]);
            }
        }
    }
}
