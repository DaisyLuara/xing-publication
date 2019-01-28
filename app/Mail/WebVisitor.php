<?php

namespace App\Mail;

use App\Http\Controllers\Admin\Common\V1\Models\WebsiteVisitor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WebVisitor extends Mailable
{
    use Queueable, SerializesModels;

    protected $typeMapping = [
        0 => '无',
        1 => '我是商业综合体&商户',
        2 => '我是品牌客户&线上平台',
        3 => '我想城市加盟代理合作'
    ];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(WebsiteVisitor $visitor)
    {
        $this->visitor = $visitor;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.website')
            ->with([
                'name' => $this->visitor->name,
                'contact' => $this->visitor->phone ? $this->visitor->phone : $this->visitor->email,
                'remark' => $this->visitor->remark,
                'type' => $this->typeMapping[$this->visitor->type]
            ]);
    }
}
