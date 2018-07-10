<?php

namespace App\Console\Commands;

use App\Http\Controllers\Admin\Face\V1\Models\FaceMauRecord;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class FaceMau extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xingstation:mau';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '月活玩家清洗';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = DB::table('face_mau_records')->max('date');
        $currentDate = Carbon::now()->toDateString();
        while ((new Carbon($date))->format('Y-m') < (new Carbon($currentDate))->format('Y-m')) {
            $startDate = $date;
            $endDate = (new Carbon($date))->endOfMonth()->toDateString();
            $startClientDate = strtotime($startDate . ' 00:00:00') * 1000;
            $endClientDate = strtotime($endDate . ' 23:59:59') * 1000;

            $sql = DB::connection('ar')->table('face_people_time')
                ->whereRaw("clientdate between '$startClientDate' and '$endClientDate' and playtime >= 7000 and oid not in(16, 19, 30, 31, 335, 334, 329, 328, 327)")
                ->groupBy(DB::raw('fpid * 10000 + oid'))
                ->selectRaw(" * ");
            $data = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a"))
                ->selectRaw("count(*) as playernum")
                ->first();
            $count = [
                'active_player' => $data->playernum,
                'date' => $date
            ];
            DB::connection('ar')->table('xs_face_mau')
                ->insert($count);
            $date = (new Carbon($date))->addMonth(1)->toDateString();
        }
        DB::table('face_mau_records')->insert(['date' => $date]);
    }
}
