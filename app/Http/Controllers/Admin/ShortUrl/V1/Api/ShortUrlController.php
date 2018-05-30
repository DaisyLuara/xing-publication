<?php

namespace App\Http\Controllers\Admin\ShortUrl\V1\Api;

use App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrlRecords;
use App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrl;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hashids\Hashids;

class ShortUrlController extends Controller
{
    public function index(Request $request, ShortUrl $push)
    {
    }

    public function store(Request $request)
    {
        $shortUrl = ShortUrl::create(['target_url' => $request->get('url')]);
        $hashIds = new Hashids();
        $uri = $hashIds->encode($shortUrl->id);
        return response()->json(['short_url' => env('APP_URL') . "/api/s/" . $uri]);

    }

    public function redirect(string $short_path)
    {
        $hashIds = new Hashids();
        $shortUrl = ShortUrl::findOrFail($hashIds->decode($short_path)[0]);

        return redirect($shortUrl['target_url']);
    }

}
