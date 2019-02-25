<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/2/19
 * Time: 下午5:35
 */

namespace App\Http\Controllers\Admin\Contract\V1\Api;


class ActionConfig
{
    const CONTRACT_STATUS_WAIT = 1;//待审批
    const CONTRACT_STATUS_ONGOING = 2;//审批中
    const CONTRACT_STATUS_AGREE = 3;//已审批
    const CONTRACT_STATUS_SPECIAL = 4;//特批
    const CONTRACT_STATUS_REJECT = 5;//驳回

    const CONTRACT_TYPE_RECEIVE = 0;//收款合同
    const CONTRACT_TYPE_PAY = 1;//付款合同
    const CONTRACT_TYPE_OTHER = 2;//其它合同

    const CONTRACT_KIND_SCREEN = 1;//铺屏
    const CONTRACT_KIND_SELL = 2;//销售
    const CONTRACT_KIND_LEASE = 3;//租赁
    const CONTRACT_KIND_SERVE = 4;//服务

    const CONTRACT_PRODUCT_STATUS_NOHARDWARE = 0;//无硬件
    const CONTRACT_PRODUCT_STATUS_NOTOUT = 1;//未出厂
    const CONTRACT_PRODUCT_STATUS_LEAVE = 2;//已出厂
}
