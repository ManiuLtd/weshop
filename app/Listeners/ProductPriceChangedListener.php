<?php

namespace App\Listeners;

use App\Events\ProductPriceChangedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;
use EasyWeChat;
use App\Models\User;
use App\WeChat\SpreadQR;
use App\Models\ProductVariable;

class ProductPriceChangedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  ProductPriceChangedEvent  $event
     * @return void
     */
    public function handle(ProductPriceChangedEvent $event)
    {
//        $Variable = ProductVariable::where(['product_id' => $event->product->id])
//            ->orderBy("id", "desc")
//            ->first();
//        $app = EasyWeChat::officialAccount();
//        //群发消息
//        $myuser = User::pluck('openid');
//        $users = $app->user->select($myuser);
//        Log::error($users);
        //通过模板消息发送降价信息
//        $app->template_message->send([
//            'touser' => 'obOoJwWpxqeAPWmN5UNjQOgZZlJM',
//            'template_id' => 'HPp3ZBtebtk99VZYOGpLRqU7whRKqTlToI7Rq9bLP0Q',
//            'url' => route("wechat.product.show",$event->product->id),
//            'data' => [
//                'first' => '',
//                'keyword1' => $event->product->name."--".$event->product->model,
//                'keyword2' => '',
//                'keyword3' => $Variable->unit_price,
//                'keyword4' => $event->product->updated_at,
//                'remark' => '',
//            ],
//        ]);
    }

}
