<?php

namespace App\Http\Middleware;

use Closure;
use App\Com\Hwache\Jiaxinbao\Account;

class UserJxb
{
    protected $jxb;
    public function __construct(Account $jxb)
    {
        $this->jxb = $jxb;
    }
    /**
     * @param $request
     * @param Closure $next
     * @param int $role
     * @return mixed
     */
    public function handle($request, Closure $next,$role=0)
    {
        /*$role = empty($role) ? 'member' : 'seller';
        $order_id = $request->id;
        $this->jxb ->callJxb($role,$order_id);*/
        return $next($request);
    }

    public function terminate($request, $response)
    {
        // 存储session数据...
    }
}
