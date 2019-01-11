<?php

namespace App\Console\Commands;

use app\Support\Jenner\Zebra\ArrayGroupBy;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class FaceCountOneDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yqDataClean:face_count_oneDay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '清洗某一天的count数据';

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
        $date = $this->ask("输入清洗时间：");
        if ($date < '2017-04-21' || $date >= Carbon::now()->toDateString()) {
            return $this->error('时间输入有误！');
        }

        $this->lookTimes($date);
        if ($date >= '2018-06-13') {
            $this->activePlayer($date);
        }
        if ($date >= '2018-07-24') {
            $this->activePlayTimes($date);
        }
        if ($date >= '2018-07-19') {
            $this->omo($date);
        }
        $this->phone($date);
        if ($date >= '2018-11-15') {
            $this->couponTimes($date);
            $this->verifyTimes($date);
        }
        $this->merge($date);

    }

    private function lookTimes($date)
    {
        $startClientDate = strtotime($date . ' 00:00:00') * 1000;
        $endClientDate = strtotime($date . ' 23:59:59') * 1000;

        $data = DB::connection('ar')->table('face_collect as fc')
            ->join('avr_official as ao', 'ao.oid', '=', 'fc.oid')
            ->selectRaw("fc.oid as oid,belong,count(*) as looktimes")
            ->whereRaw("fc.clientdate between '$startClientDate' and '$endClientDate' and fpid > 0 and fc.type = 'play' ")
            ->groupBy(DB::raw("fc.oid,belong"))
            ->get();

        $allData = DB::connection('ar')->table('face_collect as fc')
            ->join('avr_official as ao', 'ao.oid', '=', 'fc.oid')
            ->selectRaw("fc.oid as oid,belong,count(*) as looktimes")
            ->whereRaw("fc.clientdate between '$startClientDate' and '$endClientDate' and fpid > 0 and fc.type = 'play' ")
            ->groupBy(DB::raw("fc.oid"))
            ->get();
        $count = [];
        foreach ($data as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => $item->belong,
                'looktimes' => $item->looktimes,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }

        foreach ($allData as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => 'all',
                'looktimes' => $item->looktimes,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }

        DB::connection('ar')->table('xs_face_look_times')->insert($count);
    }

    private function activePlayer($date)
    {
        $startClientDate = strtotime($date . ' 00:00:00') * 1000;
        $endClientDate = strtotime($date . ' 23:59:59') * 1000;

        $timeArray = [7, 15, 21];

        //按所有人去重 belong='all'
        $sql1 = [];
        for ($i = 0; $i < count($timeArray); $i++) {
            $sql = DB::connection('ar')->table('face_people_time as fpt')
                ->join('avr_official as ao', 'ao.oid', '=', 'fpt.oid')
                ->whereRaw("fpt.clientdate between '$startClientDate' and '$endClientDate' and fpid>0 and playtime>='$timeArray[$i]000'")
                ->selectRaw("fpt.oid as oid");
            if ($date <= '2018-07-01') {
                $sql = $sql->groupBy(DB::raw('fpid*100+fpt.oid'));
            } else {
                $sql = $sql->groupBy(DB::raw('fpid*10000+fpt.oid'));
            }

            $sql1[] = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a" . $timeArray[$i]))
                ->groupBy('oid' . $timeArray[$i])
                ->selectRaw("oid as oid" . $timeArray[$i] . ",count(*) as playernum" . $timeArray[$i]);
        }
        $query = DB::connection('ar')->table(DB::raw("({$sql1[0]->toSql()}) as b"));
        for ($i = 1; $i < count($timeArray); $i++) {
            $query = $query->join(DB::raw("({$sql1[$i]->toSql()}) as b" . $i), function ($join) use ($i, $timeArray) {
                $join->on('b.oid' . $timeArray[0], '=', 'b' . $i . '.oid' . $timeArray[$i]);
            }, null, null, 'left');
        }
        $query->selectRaw("oid" . $timeArray[0] . " as oid");
        for ($i = 0; $i < count($timeArray); $i++) {
            $query->selectRaw("playernum" . $timeArray[$i]);
        }
        $allData = $query->get();

        //按节目去重
        $sql2 = [];
        for ($i = 0; $i < count($timeArray); $i++) {

            $sql = DB::connection('ar')->table('face_people_time as fpt')
                ->join('avr_official as ao', 'ao.oid', '=', 'fpt.oid')
                ->whereRaw("fpt.clientdate between '$startClientDate' and '$endClientDate' and fpid>0 and playtime>='$timeArray[$i]000'")
                ->selectRaw("fpt.oid as oid,belong");
            if ($date <= '2018-07-01') {
                $sql = $sql->groupBy(DB::raw('fpid*100+fpt.oid,belong'));
            } else {
                $sql = $sql->groupBy(DB::raw('fpid*10000+fpt.oid,belong'));
            }
            $sql2[] = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a" . $timeArray[$i]))
                ->groupBy(DB::raw("oid" . $timeArray[$i] . ",belong" . $timeArray[$i]))
                ->selectRaw("oid as oid" . $timeArray[$i] . ",belong as belong" . $timeArray[$i] . ",count(*) as playernum" . $timeArray[$i]);
        }
        $query = DB::connection('ar')->table(DB::raw("({$sql2[0]->toSql()}) as b"));
        for ($i = 1; $i < count($timeArray); $i++) {
            $query = $query->join(DB::raw("({$sql2[$i]->toSql()}) as b" . $i), function ($join) use ($i, $timeArray) {
                $join->on('b.oid' . $timeArray[0], '=', 'b' . $i . '.oid' . $timeArray[$i])
                    ->on('b.belong' . $timeArray[0], '=', 'b' . $i . '.belong' . $timeArray[$i]);
            }, null, null, 'left');
        }
        $query = $query->selectRaw("oid" . $timeArray[0] . " as oid,belong" . $timeArray[0] . " as belong");
        for ($i = 0; $i < count($timeArray); $i++) {
            $query->selectRaw("playernum" . $timeArray[$i]);
        }
        $data = $query->get();


        $count = [];
        foreach ($allData as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => 'all',
                'playernum7' => $item->playernum7,
                'playernum15' => $item->playernum15,
                'playernum21' => $item->playernum21,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }
        foreach ($data as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => $item->belong,
                'playernum7' => $item->playernum7,
                'playernum15' => $item->playernum15,
                'playernum21' => $item->playernum21,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }
        DB::connection('ar')->table('xs_face_active_player')
            ->insert($count);
    }

    private function activePlayTimes($date)
    {
        $startClientDate = strtotime($date . ' 00:00:00') * 1000;
        $endClientDate = strtotime($date . ' 23:59:59') * 1000;

        $sql = DB::connection('ar')->table("face_collect as fc")
            ->join('avr_official as ao', 'fc.oid', '=', 'ao.oid')
            ->join('face_people_time as fpt', function ($join) {
                $join->on('fc.oid', '=', 'fpt.oid');
                $join->on('fc.belong', '=', 'fpt.belong');
                $join->on('fc.fpid', '=', 'fpt.fpid');
            }, null, null, 'left')
            ->whereRaw("fc.clientdate between '$startClientDate' and '$endClientDate' and fpt.clientdate between '$startClientDate' AND '$endClientDate' and fc.fpid>0")
            ->selectRaw("fc.oid as oid ,fc.belong as belong,fc.fpid as fpid,fc.clientdate as clientdate,playtime");

        $data = $sql->get();
        $count = [];
        foreach ($data as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => $item->belong,
                'fpid' => $item->fpid,
                'clientdate' => $item->clientdate,
                'playtime' => $item->playtime,
            ];
        }
        $group_by_fields = [
            'oid' => function ($value) {
                return $value;
            },
            'belong' => function ($value) {
                return $value;
            },
            'fpid' => function ($value) {
                return $value;
            },

        ];
        $group_by_value = [
            'oid' => [
                'callback' => function ($value_array) {
                    return $value_array[0]['oid'];
                },
                'as' => 'oid'
            ],
            'belong' => [
                'callback' => function ($value_array) {
                    return $value_array[0]['belong'];
                },
                'as' => 'belong'
            ],
            'fpid' => [
                'callback' => function ($value_array) {
                    return $value_array[0]['fpid'];
                },
                'as' => 'fpid'
            ],
            'clientdate' => [
                'callback' => function ($data) {
                    return join(',', array_column($data, 'clientdate'));
                },
                'as' => 'clientdate'
            ],
            'playtime' => [
                'callback' => function ($value_array) {
                    return $value_array[0]['playtime'];
                },
                'as' => 'playtime'
            ],
        ];
        $data = ArrayGroupBy::groupBy($count, $group_by_fields, $group_by_value);
        $count = [];
        foreach ($data as $item) {
            $clientDates = explode(',', $item['clientdate']);
            sort($clientDates);
            $m = 0;
            $n = 1;
            $num7 = dateRecursion($m, $n, $clientDates, 7000) + 1;
            $num15 = dateRecursion($m, $n, $clientDates, 15000) + 1;
            $num21 = dateRecursion($m, $n, $clientDates, 21000) + 1;

            $count[] = [
                'oid' => $item['oid'],
                'belong' => $item['belong'],
                'fpid' => $item['fpid'],
                'playtimes7' => ($num7 > floor($item['playtime'] / 7000)) ? floor($item['playtime'] / 7000) : $num7,
                'playtimes15' => ($num15 > floor($item['playtime'] / 15000)) ? floor($item['playtime'] / 15000) : $num15,
                'playtimes21' => ($num21 > floor($item['playtime'] / 21000)) ? floor($item['playtime'] / 21000) : $num21,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }
        $group_by_fields = [
            'oid' => function ($value) {
                return $value;
            },
            'belong' => function ($value) {
                return $value;
            }
        ];
        $group_by_value = [
            'oid' => [
                'callback' => function ($value_array) {
                    return $value_array[0]['oid'];
                },
                'as' => 'oid'
            ],
            'belong' => [
                'callback' => function ($value_array) {
                    return $value_array[0]['belong'];
                },
                'as' => 'belong'
            ],
            'playtimes7' => function ($data) {
                return array_sum(array_column($data, 'playtimes7'));
            },
            'playtimes15' => function ($data) {
                return array_sum(array_column($data, 'playtimes15'));
            },
            'playtimes21' => function ($data) {
                return array_sum(array_column($data, 'playtimes21'));
            },
            'date' => [
                'callback' => function ($value_array) {
                    return $value_array[0]['date'];
                },
                'as' => 'date'
            ],
            'clientdate' => [
                'callback' => function ($value_array) {
                    return $value_array[0]['clientdate'];
                },
                'as' => 'clientdate'
            ]
        ];
        $data = ArrayGroupBy::groupBy($count, $group_by_fields, $group_by_value);

        $group_by_fields = [
            'oid' => function ($value) {
                return $value;
            }
        ];
        $group_by_value = [
            'oid' => [
                'callback' => function ($value_array) {
                    return $value_array[0]['oid'];
                },
                'as' => 'oid'
            ],
            'belong' => [
                'callback' => function ($value_array) {
                    return 'all';
                },
                'as' => 'belong'
            ],
            'playtimes7' => function ($data) {
                return array_sum(array_column($data, 'playtimes7'));
            },
            'playtimes15' => function ($data) {
                return array_sum(array_column($data, 'playtimes15'));
            },
            'playtimes21' => function ($data) {
                return array_sum(array_column($data, 'playtimes21'));
            },
            'date' => [
                'callback' => function ($value_array) {
                    return $value_array[0]['date'];
                },
                'as' => 'date'
            ],
            'clientdate' => [
                'callback' => function ($value_array) {
                    return $value_array[0]['clientdate'];
                },
                'as' => 'clientdate'
            ]
        ];
        $allData = ArrayGroupBy::groupBy($data, $group_by_fields, $group_by_value);
        DB::connection('ar')->table('xs_face_active_playtimes')->insert(array_merge($data, $allData));
    }

    private function omo($date)
    {
        $sql = DB::table('short_url_records')
            ->whereRaw("date_format(created_at,'%Y-%m-%d')='$date' and face_id <> '' and utm_source <> '' ")
            ->groupBy(DB::raw(" oid, belong, utm_term "))
            ->selectRaw("utm_source as oid,utm_campaign as belong,utm_term,count(*) as num");

        $scanAndShareSql = DB::table(DB::raw("({$sql->toSql()}) as a"))
            ->groupBy(DB::raw("oid,belong"))
            ->selectRaw("oid,belong,sum(if(utm_term = '', num, 0)) as omo_scannum,sum(if(utm_term = 'wechat_share', num, 0)) as omo_sharenum");

        $sql1 = DB::table('short_url_records')
            ->whereRaw(" date_format(created_at,'%Y-%m-%d')='$date' and face_id <> '' and utm_source <> '' ")
            ->groupBy(DB::raw(" oid, belong, face_id "))
            ->selectRaw("utm_source as oid,utm_campaign as belong,face_id");

        $outSql = DB::table(DB::raw("({$sql1->toSql()}) as b"))
            ->groupBy(DB::raw("oid,belong"))
            ->selectRaw("oid,belong,count(*) as omo_outnum");

        $data = DB::table(DB::raw("({$scanAndShareSql->toSql()}) as a1"))
            ->join(DB::raw("({$outSql->toSql()}) as b1"), function ($join) {
                $join->on('a1.oid', '=', 'b1.oid');
                $join->on('a1.belong', '=', 'b1.belong');
            })
            ->selectRaw("a1.oid as oid,a1.belong as belong ,b1.omo_outnum as omo_outnum,a1.omo_scannum as omo_scannum ,a1.omo_sharenum as omo_sharenum")
            ->get();
        $count = [];
        foreach ($data as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => $item->belong,
                'omo_outnum' => $item->omo_outnum,
                'omo_scannum' => $item->omo_scannum,
                'omo_sharenum' => $item->omo_sharenum,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }
        DB::connection('ar')->table('xs_face_omo')->insert($count);

        $clientDate = strtotime($date) * 1000;
        $allData = DB::connection('ar')->table('xs_face_omo')
            ->whereRaw("clientdate='$clientDate'")
            ->groupBy('oid')
            ->selectRaw(" oid,sum(omo_outnum) as omo_outnum,sum(omo_scannum) as omo_scannum,sum(omo_sharenum) as omo_sharenum,date,clientdate")
            ->get();
        $count = [];
        foreach ($allData as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => 'all',
                'omo_outnum' => $item->omo_outnum,
                'omo_scannum' => $item->omo_scannum,
                'omo_sharenum' => $item->omo_sharenum,
                'date' => $item->date,
                'clientdate' => $item->clientdate,
            ];
        }
        DB::connection('ar')->table('xs_face_omo')->insert($count);
    }

    private function phone($date)
    {
        $startClientDate = strtotime($date . ' 00:00:00') * 1000;
        $endClientDate = strtotime($date . ' 23:59:59') * 1000;

        //belong='all'
        $sql = DB::connection('ar')->table('face_ad_log as fal')
            ->join('avr_official as ao', 'fal.oid', '=', 'ao.oid')
            ->whereRaw("fal.clientdate between '$startClientDate' and '$endClientDate' and fpid <> 0")
            ->groupBy(DB::raw("oid,unionid"))
            ->selectRaw("fal.oid as oid,unionid");

        $allData = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a"))
            ->selectRaw("oid,count(length(unionid)=11 or null) as phonenum,count(length(unionid)<>11 or null) as oanum")
            ->groupBy("oid")
            ->get();

        //按节目去重
        $sql1 = DB::connection('ar')->table('face_ad_log as fal')
            ->join('avr_official as ao', 'fal.oid', '=', 'ao.oid')
            ->whereRaw("fal.clientdate between '$startClientDate' and '$endClientDate' and fpid <> 0")
            ->groupBy(DB::raw("oid,belong,unionid"))
            ->selectRaw("fal.oid as oid,belong,unionid");

        $data = DB::connection('ar')->table(DB::raw("({$sql1->toSql()}) as a"))
            ->selectRaw("oid,belong,count(length(unionid)=11 or null) as phonenum,count(length(unionid)<>11 or null) as oanum")
            ->groupBy(DB::raw("oid,belong"))
            ->get();
        $count = [];
        foreach ($allData as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => 'all',
                'phonenum' => $item->phonenum,
                'oanum' => $item->oanum,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }
        foreach ($data as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => $item->belong,
                'phonenum' => $item->phonenum,
                'oanum' => $item->oanum,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }
        DB::connection('ar')->table('xs_face_phone')
            ->insert($count);


        //不去重
        $times = DB::connection('ar')->table('face_ad_log as fal')
            ->join('avr_official as ao', 'fal.oid', '=', 'ao.oid')
            ->whereRaw("fal.clientdate between '$startClientDate' and '$endClientDate'")
            ->groupBy(DB::raw("fal.oid,belong"))
            ->selectRaw("fal.oid as oid,belong,count(length(unionid)=11 or null) as phonetimes,count(length(unionid)<>11 or null) as oatimes")
            ->get();
        $allTimes = DB::connection('ar')->table('face_ad_log as fal')
            ->join('avr_official as ao', 'fal.oid', '=', 'ao.oid')
            ->whereRaw("fal.clientdate between '$startClientDate' and '$endClientDate'")
            ->groupBy('fal.oid')
            ->selectRaw("fal.oid as oid,count(length(unionid)=11 or null) as phonetimes,count(length(unionid)<>11 or null) as oatimes")
            ->get();
        $count = [];
        foreach ($times as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => $item->belong,
                'phonetimes' => $item->phonetimes,
                'oatimes' => $item->oatimes,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }
        foreach ($allTimes as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => 'all',
                'phonetimes' => $item->phonetimes,
                'oatimes' => $item->oatimes,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }
        DB::connection('ar')->table('xs_face_phone_times')->insert($count);
    }

    private function couponTimes($date)
    {
        $data = DB::table('coupons')
            ->selectRaw("oid,belong,count(*) as coupontimes")
            ->whereRaw("date_format(created_at,'%Y-%m-%d')='$date' and oid>0 ")
            ->groupBy(DB::raw("oid,belong"))
            ->get();

        $allData = DB::table('coupons')
            ->selectRaw("oid,belong,count(*) as coupontimes")
            ->whereRaw("date_format(created_at,'%Y-%m-%d')='$date' and oid>0 ")
            ->groupBy('oid')
            ->get();
        $count = [];
        foreach ($data as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => $item->belong,
                'coupontimes' => $item->coupontimes,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }

        foreach ($allData as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => 'all',
                'coupontimes' => $item->coupontimes,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }

        DB::connection('ar')->table('xs_face_coupon_times')->insert($count);
    }

    private function verifyTimes($date)
    {
        $data = DB::table('coupons')
            ->selectRaw("oid,belong,count(*) as verifytimes")
            ->whereRaw("date_format(updated_at,'%Y-%m-%d')='$date' and status=1 and oid>0 ")
            ->groupBy(DB::raw("oid,belong"))
            ->get();

        $allData = DB::table('coupons')
            ->selectRaw("oid,belong,count(*) as verifytimes")
            ->whereRaw("date_format(updated_at,'%Y-%m-%d')='$date' and status=1 and oid>0 ")
            ->groupBy('oid')
            ->get();
        $count = [];
        foreach ($data as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => $item->belong,
                'verifytimes' => $item->verifytimes,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }

        foreach ($allData as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => 'all',
                'verifytimes' => $item->verifytimes,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }

        DB::connection('ar')->table('xs_face_verify_times')->insert($count);
    }

    private function merge($date)
    {
        $sql1 = DB::connection('ar')->table('face_count_log as fcl')
            ->whereRaw("date_format(fcl.date,'%Y-%m-%d')='$date'")
            ->selectRaw("fcl.oid,fcl.belong,looknum,playernum,outnum,scannum,lovenum")
            ->groupBy(DB::raw("fcl.oid,fcl.belong"));

        $clientDate = strtotime($date . ' 00:00:00') * 1000;
        $sql2 = DB::connection('ar')->table('xs_face_active_player')
            ->whereRaw("clientdate='$clientDate'")
            ->selectRaw("oid,belong,playernum7,playernum15,playernum21");

        $sql3 = DB::connection('ar')->table('xs_face_omo')
            ->whereRaw("clientdate='$clientDate'")
            ->selectRaw("oid,belong,omo_outnum,omo_scannum,omo_sharenum");

        $sql4 = DB::connection('ar')->table('xs_face_phone')
            ->whereRaw("clientdate='$clientDate'")
            ->selectRaw("oid,belong,phonenum,oanum");

        $sql5 = DB::connection('ar')->table('xs_face_phone_times')
            ->whereRaw("clientdate='$clientDate'")
            ->selectRaw("oid,belong,phonetimes,oatimes");

        $sql6 = DB::connection('ar')->table('xs_face_active_playtimes')
            ->whereRaw("clientdate='$clientDate'")
            ->selectRaw("oid,belong,playtimes7,playtimes15,playtimes21");

        $sql7 = DB::connection('ar')->table('xs_face_verify_times')
            ->whereRaw("clientdate='$clientDate'")
            ->selectRaw("oid,belong,verifytimes");

        $sql8 = DB::connection('ar')->table('xs_face_coupon_times')
            ->whereRaw("clientdate='$clientDate'")
            ->selectRaw("oid,belong,coupontimes");

        $sql = DB::connection('ar')->table('xs_face_look_times')
            ->whereRaw("clientdate='$clientDate'")
            ->selectRaw("oid,belong,looktimes");

        $data = DB::connection('ar')->table(DB::raw("({$sql->toSql()}) as a"))
            ->join(DB::raw("({$sql1->toSql()}) as b"), function ($join) {
                $join->on('a.oid', '=', 'b.oid');
                $join->on('a.belong', '=', 'b.belong');
            }, null, null, 'left')
            ->join(DB::raw("({$sql2->toSql()}) as c"), function ($join) {
                $join->on('a.oid', '=', 'c.oid');
                $join->on('a.belong', '=', 'c.belong');
            }, null, null, 'left')
            ->join(DB::raw("({$sql3->toSql()}) as d"), function ($join) {
                $join->on('a.oid', '=', 'd.oid');
                $join->on('a.belong', '=', 'd.belong');
            }, null, null, 'left')
            ->join(DB::raw("({$sql4->toSql()}) as e"), function ($join) {
                $join->on('a.oid', '=', 'e.oid');
                $join->on('a.belong', '=', 'e.belong');
            }, null, null, 'left')
            ->join(DB::raw("({$sql5->toSql()}) as f"), function ($join) {
                $join->on('a.oid', '=', 'f.oid');
                $join->on('a.belong', '=', 'f.belong');
            }, null, null, 'left')
            ->join(DB::raw("({$sql6->toSql()}) as g"), function ($join) {
                $join->on('a.oid', '=', 'g.oid');
                $join->on('a.belong', '=', 'g.belong');
            }, null, null, 'left')
            ->join(DB::raw("({$sql7->toSql()}) as h"), function ($join) {
                $join->on('a.oid', '=', 'h.oid');
                $join->on('a.belong', '=', 'h.belong');
            }, null, null, 'left')
            ->join(DB::raw("({$sql8->toSql()}) as i"), function ($join) {
                $join->on('a.oid', '=', 'i.oid');
                $join->on('a.belong', '=', 'i.belong');
            }, null, null, 'left')
            ->selectRaw("a.oid as oid,a.belong as belong,looknum,playernum7,playernum15,playernum21,playernum,outnum,scannum,omo_outnum,omo_scannum,omo_sharenum,lovenum,phonenum,oanum,phonetimes,oatimes,playtimes7,playtimes15,playtimes21,looktimes,verifytimes,coupontimes")
            ->get();
        $count = [];
        foreach ($data as $item) {
            $count[] = [
                'oid' => $item->oid,
                'belong' => $item->belong,
                'looknum' => $item->looknum,
                'playernum7' => $item->playernum7 ? $item->playernum7 : 0,
                'playernum15' => $item->playernum15 ? $item->playernum15 : 0,
                'playernum21' => $item->playernum21 ? $item->playernum21 : 0,
                'playernum' => $item->playernum,
                'outnum' => $item->outnum,
                'scannum' => $item->scannum,
                'omo_outnum' => $item->omo_outnum ? $item->omo_outnum : 0,
                'omo_scannum' => $item->omo_scannum ? $item->omo_scannum : 0,
                'omo_sharenum' => $item->omo_sharenum ? $item->omo_sharenum : 0,
                'lovenum' => $item->lovenum,
                'phonenum' => $item->phonenum,
                'oanum' => $item->oanum,
                'phonetimes' => $item->phonetimes,
                'oatimes' => $item->oatimes,
                'playtimes7' => $item->playtimes7,
                'playtimes15' => $item->playtimes15,
                'playtimes21' => $item->playtimes21,
                'looktimes' => $item->looktimes,
                'lovetimes' => $item->oatimes + $item->phonetimes,
                'verifytimes' => $item->verifytimes,
                'coupontimes' => $item->coupontimes,
                'date' => $date,
                'clientdate' => strtotime($date) * 1000
            ];
        }
        DB::connection('ar')->table('xs_face_count_log')->insert($count);
    }
}
