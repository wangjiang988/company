﻿@extends('HomeV2._layout.base')
@section('css')
    <link href="{{asset('themes/reg.adv.css')}}" rel="stylesheet" />
@endsection
@section('nav')
    <div class="head-wrapper">
        <div class="head-container">
            <div class="head-logo-wrapper psr">
                <a class="logo" href="/">华车</a>
            </div>
            <div class="clear"></div>
        </div>
    </div>
@endsection

@section('content')
    <div class="box" ms-include-src="regheader"></div>

    <div class="container m-t-86 pos-rlt" id="vue">
        <div class="wapper">
            <div class="hd reg-box">
                <div class="title">快捷注册</div> 
              
               
                <div class="form">
                    <br>
                    <table class="reg-form-tbl w355">
                        <tr>
                            <td width="100" align="right" valign="top">
                                <span class="tag-success"></span>
                            </td>
                            <td class="text-gray">
                                <p class="weight juhuang fs18">恭喜您注册成功！</p>
                            </td>
                        </tr>   
                    </table>  
                    <table class="reg-form-tbl w780">
                        <tr>
                            <td width="150"></td>
                            <td class="text-gray">
                                <p class=" ">华车采用实时抢购模式，订购诚意金￥499从账户可用余额扣减，</p>
                                <p class=" ">所以顺利订购需保证可用余额不低于￥499，您需要立即给账户充值吗？</p>
                            </td>
                        </tr>   
                    </table>  


                    <br><br>
                    <p class="text-center">
                        {{--TODO 页面及功能未完成--}}
                        <a href="{{route('pay.online')}}" class="btn btn-s-md btn-danger w160 mt20 inline-block">是的，给账户充值</a>
                        <a href="/" class="btn btn-s-md btn-danger w160 mt20 btn-white inline-block ml120">不用，先看看车</a>
                    </p>
                    
                    
                </div>
                
            </div>
        </div>
        <br><br><br>
 

    </div>
    @endsection
    @section('footer')
        @include('HomeV2._layout.footer')
    @endsection

  
