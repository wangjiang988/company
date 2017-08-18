<?php
/**
 * @author wangjiang
 * @time 20170605
 * @location admin.123.com
 * @module  交易
 * 财务管理
 * --平台财务
 */
defined('InHG') or exit('Access Invalid!');

import('.control.action.AdminFinance.user');
import('.control.action.AdminFinance.seller');
import('.control.action.AdminFinance.account');

class admin_financeControl extends SystemControl
{
    public function __construct()
    {
        parent::__construct();
        Tpl::setDir('AdminFinance');
    }

    //首页
    public function indexOp()
    {
        //分发url
        $op = $_GET['op'];
        if($op === 'index'){
            $this->home();
        }
        if(method_exists($this, $op)){
            $this->$op();
        }else{
            $tmp  =  explode('_', $op);
            if(in_array($tmp[0],['user','seller','account'])  )
            {
                $action   =  new $tmp[0]();
            }else{
                $action = $this;
            }
            $method       =  $op;

            if(method_exists($action, $method)){
                $action->$method();
            }else{
                $this->home();
            }
        }

    }


    public function home(){
        Tpl::showpage('home');
    }

}