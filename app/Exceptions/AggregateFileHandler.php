<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/4/30
 * Time: 上午10:06
 */

namespace App\Exceptions;


use Carbon\Carbon;
use Monolog\Handler\StreamHandler;

class AggregateFileHandler extends StreamHandler
{
    public function handleBatch(array $records): void
    {
        $dur = number_format(microtime(true) - LARAVEL_START, 3);
        $request = request();
        $format = 'Y-m-d H:i:s.u';
        // 这一行是我们这个处理器自己加上的日志，记录请求时间、响应时间、访客IP，请求方法、请求Url
        $log = sprintf(
            "[%s][%s]%s %s %s\n",
            Carbon::now()->format($format),
//            Carbon::createFromFormat('U.u', sprintf('%.6F', LARAVEL_START), config('app.timezone'))->format($format),
            $dur,
            $request->getClientIp(),
            $request->getMethod(),
            $request->getRequestUri()
        );
        // 然后将内存中的日志追加到$log这个变量里
        foreach ($records as $record) {
            if (!$this->isHandling($record)) {
                continue;
            }
            $record = $this->processRecord($record);
            $log .= $this->getFormatter()->format($record);
        }
        // 调用日志写入方法
        $this->write(['formatted' => $log]);
    }
}