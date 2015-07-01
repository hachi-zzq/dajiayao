<?php namespace Dajiayao\Http\Middleware;

use Closure;
use Dajiayao\User;
use Illuminate\Contracts\Auth\Guard;

class AdminAuthenticate {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard  $auth
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if ($this->auth->guest() or $this->auth->user()->role!=User::ROLE_ADMIN)
		{
			if ($request->ajax())
			{
				return response('Unauthorized.', 401);
			}
			else
			{
				return redirect('/admin')->with('error_tips', '没有权限操作')->withInput();
			}
		}

		return $next($request);
	}

}
