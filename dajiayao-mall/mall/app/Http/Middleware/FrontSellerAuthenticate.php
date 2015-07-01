<?php namespace Dajiayao\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use \Config;


class FrontSellerAuthenticate {

    const SNSAPI_BASE = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_base&state=%s#wechat_redirect";


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if(env('APP_ENV') == 'local'){
            //如果是本地，关闭微信身份验证，写死身份
            Session::put('seller_id',1);

        }else{
            $openid = Session::get('openid');
            if( ! $openid){
                \Log::info("not found openid in session");
                $appid = Config::get("weixin.seller.appid");

                Session::put('request_url',$request->getUri());
                $authUrl = route("wxSellerAuth");
                \Log::info($authUrl);
                $snsapi_base = sprintf(self::SNSAPI_BASE,$appid,urlencode($authUrl),'dajiayao123456');
                return redirect($snsapi_base);
            }
            \Log::info("success found openid in session");
        }
        return $next($request);
    }

}
