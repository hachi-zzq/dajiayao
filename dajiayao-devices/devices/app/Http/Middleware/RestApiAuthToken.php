<?php namespace Dajiayao\Http\Middleware;

use Closure;
use Dajiayao\Model\App;
use Dajiayao\Model\RestLog;
use Dajiayao\Model\WeixinMp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class RestApiAuthToken {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{

        $token = $request->get('token');
        if(!$token or ! $this->verifyToken($token)){
            return $this->encodeResult(20000,"Permission denied,TOKEN ERROR");
        }

        $app = App::where('access_token',$token)->first();
        Session::put('appid',$app->id);
        $mp = WeixinMp::where('app_id',$app->id)->first();
        Session::put('wx_mp_id',$mp->id);
        Session::put('wx_appid',$mp->appid);
        Session::put('wx_appsecret',$mp->appsecret);
		return $next($request);
	}



    /**
     * 验证 Token 并使其登录
     * @author zhengqian.zhu@dajiayao.cc
     */
    protected function verifyToken($tokenStr)
    {
        // TODO: Redis 缓存 Token
        // 判断 Token 有效性
        $token = App::where('access_token', '=', $tokenStr)->where('expire_at','>',date("Y-m-d H:i:s"))->first();
        if (!$token) {
            return false;
        }
        // 延长 Token 的有效期
        $today = new \Datetime();
        $modifier = '+2 hours';
        $token->expire_at = $today->modify($modifier);
        $token->save();

        return $token->app_id;
    }


    /**
     * 统一返回格式
     * @param $msgcode
     * @param null $message
     * @param null $data
     * @return string
     */
    protected function encodeResult($msgcode, $message = NULL, $data = NULL)
    {
        if($data == null){
            $data = new \stdClass();
        }

        $log = new RestLog();
        $log->request = json_encode(Request::except('file'));
        $log->request_route = Route::currentRouteName();
        $log->response = json_encode($data);
        $log->msgcode = $msgcode;
        $log->message = $message;
        $log->client_ip = Request::getClientIp();
        $log->client_useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : NULL;

        if (Auth::check()) {
            $log->user_id = Auth::user()->user_id;
        }
        $log->save();

        $result = array(
            "rest_id"=>$log->id,
            'msgcode' => $msgcode,
            'message' => $message,
            'date' => $data,
            'version' => '1.0',
            'servertime' => time()
        );

        return \Response::json($result);
    }

}
