<?php namespace App\Http\Controllers\Front;
/**
 * 首页控制器
 *
 * @author  Andy  <php360@qq.com>
 */

use App\Http\Controllers\Controller;

class WelcomeController extends Controller {

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function index()
    {
        return view('welcome');
    }

}
