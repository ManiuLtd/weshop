<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ShopUser;
use EasyWeChat;
use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Cache;
use Log;

class WeChatAuthController extends Controller
{
    public function oauth(Request $request)
    {                
        if (Cache::add("oauth_code", $request->code, 5)) {
            $openid = $this->getOpenid($request->code);
        } else {
            $openid = Cache::get("openid");
        }
        
        try {
            $shopUser = ShopUser::where("openid", "=", $openid)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            $shopUser = new ShopUser();
            $shopUser->openid = $openid;
            $shopUser->rec_code = "x"; // 这个值在监听事件中自动修改
            $shopUser->save();
        }

        $data = Cache::get($request->oauth_token);
        /**
         * 调用 Cache::forget() 后 $data["target"] 为 null,
         * 不知道什么鬼
         */
        $token = str_random(40);
        $query = array_merge(["oauth_token" => $token], $data["query"]);
        Cache::put($token, $openid, 2);
        $url = $data["url"] . "?" . http_build_query($query);
        return redirect($url);
    }

    private function getOpenid($code)
    {
        $appid = env("WECHAT_OFFICIAL_ACCOUNT_APPID");
        $secret = env("WECHAT_OFFICIAL_ACCOUNT_SECRET");
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?" .
             "appid={$appid}&secret={$secret}" .
             "&code={$code}" .
             "&grant_type=authorization_code";
        $obj = json_decode(file_get_contents($url));
        if (property_exists($obj, "openid")) {
            Cache::put("openid", $obj->openid, 5);
            return $obj->openid;
        } else {
            Log::error("{$obj->errcode}: {$obj->errmsg}");
            throw new \Exception("获取openid失败: {$obj->errmsg}");
        }
    }
}
