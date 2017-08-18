@extends('_layout.base')
@section('css')
  <link rel="stylesheet" href="{{ asset('css/ui-dialog.css') }}"/>
  <link href="{{asset('themes/item-fix.css')}}" rel="stylesheet" />
@endsection
@section('nav')
<nav class="navbar navbar-inverse navbar-fixed-top" >
    <div class="container">
        <div id="navbar" class="collapse navbar-collapse">
            <div class="navbar-header pos-rlt">
                <a class="navbar-brand logo" href="/">华车网</a>
            </div>
            <ul class="nav navbar-nav">
                <li><a href="#maiche">买车流程</a></li>
                <li><a href="#baozhang">诚信保障</a></li>
                <li><a href="#services">服务中心</a></li>
            </ul>
            <ul class="nav navbar-nav control">
            @if(isset($_SESSION['member_name']))
                <li class="loginout">
                    <label>欢迎您：<a href="{{ $_ENV['_CONF']['config']['shop_site_url'] }}"><span>{{ $_SESSION['member_name'] }}</span> </a></label>
                    <em>|</em>
                    <a href="{{ route('logout') }}"><span>[</span>退出<span>]</span></a>
                </li>
            @else
                <li class="loginout">
                    <a ms-click="login" href="javascript:;">快速登陆</a><em>|</em>
                    <a href="{{ $_ENV['_CONF']['config']['www_site_url'] }}/regbyphone">快捷注册</a>
                </li>
            @endif
            </ul>
        </div>

    </div>
</nav>
@endsection
@section('content')
  <div class="container m-t-86 pos-rlt" >
        <div class="step pos-rlt">
            <ul>
                <li class="first">诚意预约<i></i></li>
                <li>付担保金<i></i></li>
                <li>预约交车<i></i></li>
                <li class="step-cur">付款提车<i></i></li>
                <li>退担保金<i></i></li>
                <li>完成评价<i></i></li>
                <div class="clear"></div>
            </ul>
            <div class="min-step">
                <div class="m-content payment">
                    <small class="juhuang">正在交车</small>
                    <i></i>
                    <small>核实信息</small>
                </div>
            </div>
        </div>
    </div>

    <div class="container pos-rlt content r-pdi"  ms-controller="item">
        <form action="{{ url('cart/saveticheinfo') }}" method="post" name="item-form">
        <div class="wapper has-min-step">
            <h1>尊敬的客户：</h1>
            <h1 class="ti">非常高兴您与经销商就交车中的波折达成谅解，祝您此后一切顺利！</h1>
            <h1 class="ti">请您将后续情况及时告诉我们。如遇到新的交车问题也可<a href="{{url('cart/zhengyi')}}/{{$order_num}}" class="juhuang tdu">点此</a>，让我们为您排忧解难。</h1>
            <br>
            <ul class="pdi-order-ul">
                <li class="pdi-sn">
                    <p class="fs14"><b>订单号：{{$order_num}}</b></p>
                </li>
                <li class="pdi-time"><p class="fs14"><b>订单时间：</b>{{ddate($order['created_at'])}}</p></li>
                <li class="pdi-more">
                    <div class="psr fs14">
                      <span class="sj"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                         <b>更多</b>
                      </span>
                      <p class="tm tm-long" style="display: none;">
                        @if(count($cart_log)>0)     
                        	@foreach($cart_log as $k =>$v )
						     <span>{{$v['msg_time']}}：{{$v['time']}}</span>
						     @endforeach
						 @endif
                      
                      </p>
                    </div>


                </li>
                <div class="clear"></div>
            </ul>
            <div class="clear"></div>
            <ul class="pdi-order-ul border">
                <li class="pdi-name">
                    <p class="fs14">{{$brand[0]}}</p>
                </li>
                <li class="pdi-type"><p class="fs14">{{$brand[1]}}</p></li>
                <li class="pdi-title"><p class="fs14">{{$brand[2]}}</p></li>
                <li class="pdi-color"><p class="fs14">{{$body_color}}（{{$interior_color}}）</p></li>
                <div class="clear"></div>
            </ul>     

            <p class="fs14 tac">
                <a href="#" class="juhuang tdu">下载交车确认书</a>
                <a href="#" class="juhuang tdu pl20">查看订单总详情</a>
                <a href="#" class="juhuang tdu pl20">下载交车宝典</a>
            </p>
            <hr class="dashed">


            <div class="box">
               
                <div class="box-inner  box-inner-def">
                  
                    <table class="tbl">
                        <tbody>
                            <tr>
                                <th><label class="fs14">项目</label></th>
                                <th><label class="fs14">约定</label></th>
                                <th><label class="fs14">实际</label></th>
                                <th><label class="fs14">备注</label></th>
                            </tr>
                            <tr>
                                <td><p class="tac fs14">生产国别</p></td>
                                <td><p class="tac fs14"></p></td>
                                <td class="cell">
                                    <p class="tac fs14">
                                        <input name="guobie" checked="" class="radio" type="radio"><span>相符</span>
                                        <input name="guobie" class="radio" type="radio"><span>不相符</span>
                                    </p>
                                </td>
                                <td width="400">
                                    <textarea name="guobie_notice" id="" cols="30" rows="1"></textarea>
                                    <span class="edit" ms-on-click="edit"></span>
                                </td>
                            </tr> 
                            <tr>
                                <td><p class="tac fs14">基本配置</p></td>
                                <td><p class="tac fs14"><a href="#" class="juhuang tdu">见附件一</a></p></td>
                                <td class="cell">
                                    <p class="tac fs14">
                                        <input name="peizhi" checked="" class="radio" type="radio"><span>相符</span>
                                        <input name="peizhi" class="radio" type="radio"><span>不相符</span>
                                    </p>
                                </td>
                                <td width="400">
                                    <textarea name="peizhi_notice" id="" cols="30" rows="1"></textarea>
                                    <span class="edit" ms-on-click="edit"></span>
                                </td>
                            </tr> 
                            <tr>
                                <td><p class="tac fs14">经销商名称</p></td>
                                <td><p class="tac fs14"></p></td>
                                <td class="cell">
                                    <p class="tac fs14">
                                        <input name="jxs" checked="" class="radio" type="radio"><span>相符</span>
                                        <input name="jxs" class="radio" type="radio"><span>不相符</span>
                                    </p>
                                </td>
                                <td width="400">
                                    <textarea name="jxs_notice" id="" cols="30" rows="1"></textarea>
                                    <span class="edit" ms-on-click="edit"></span>
                                </td>
                            </tr> 
                            <tr>
                                <td><p class="tac fs14">交车地点</p></td>
                                <td><p class="tac fs14"></p></td>
                                <td class="cell">
                                    <p class="tac fs14">
                                        <input name="address" checked="" class="radio" type="radio"><span>相符</span>
                                        <input name="address" class="radio" type="radio"><span>不相符</span>
                                    </p>
                                </td>
                                <td width="400">
                                    <textarea name="address_notice" id="" cols="30" rows="1"></textarea>
                                    <span class="edit" ms-on-click="edit"></span>
                                </td>
                            </tr> 
                            <tr>
                                <td><p class="tac fs14">交车时间</p></td>
                                <td><p class="tac fs14"></p></td>
                                <td class="cell">
                                    <p class="tac fs14">
                                        <input name="jiaoche" checked="" class="radio" type="radio"><span>相符</span>
                                        <input name="jiaoche" class="radio" type="radio"><span>不相符</span>
                                    </p>
                                </td>
                                <td width="400">
                                    <textarea name="jiaoche_notice" id="" cols="30" rows="1"></textarea>
                                    <span class="edit" ms-on-click="edit"></span>
                                </td>
                            </tr> 
                            <tr>
                                <td><p class="tac fs14">裸车开票价格</p></td>
                                <td><p class="tac fs14"></p></td>
                                <td class="cell">
                                    <p class="tac fs14">
                                        <input name="price" checked="" class="radio" type="radio"><span>相符</span>
                                        <input name="price" class="radio" type="radio"><span>不相符</span>
                                    </p>
                                </td>
                                <td width="400">
                                    <textarea name="price_notice" id="" cols="30" rows="1"></textarea>
                                    <span class="edit" ms-on-click="edit"></span>
                                </td>
                            </tr> 
                        </tbody>
                    </table>
                    <p class="center">
                        <a href="javascript:;" data-grounp="" class="btn btn-s-md btn-danger" name="bt_sure_1">确认</a>
                        <a href="javascript:;" data-grounp="" class="btn btn-s-md btn-danger oksure" style="display:none" name="bt_has_sure_1">已确认</a>
                        <a href="javascript:;" data-grounp="" class="juhuang tdu fs16" style="display:none" name="bt_modify_1">修改</a>
                    </p>
                    <hr class="dashed">


                    <table class="tbl">
                        <tbody>
                            <tr>
                                <th><label class="fs14">项目</label></th>
                                <th><label class="fs14">约定</label></th>
                                <th><label class="fs14">实际</label></th>
                                <th><label class="fs14">备注</label></th>
                            </tr>
                            <tr>
                                <td><p class="tac fs14">排放标准</p></td>
                                <td><p class="tac fs14"></p></td>
                                <td class="cell">
                                    <p class="tac fs14">
                                        <input name="biaozhun" checked="" class="radio" type="radio"><span>相符</span>
                                        <input name="biaozhun" class="radio" type="radio"><span>不相符</span>
                                    </p>
                                </td>
                                <td width="400">
                                    <textarea name="biaozhun_notice" id="" cols="30" rows="1"></textarea>
                                    <span class="edit" ms-on-click="edit"></span>
                                </td>
                            </tr> 
                            <tr>
                                <td><p class="tac fs14">车身颜色</p></td>
                                <td><p class="tac fs14"><a href="#" class="juhuang tdu">见附件一</a></p></td>
                                <td class="cell">
                                    <p class="tac fs14">
                                        <input name="csys" checked="" class="radio" type="radio"><span>相符</span>
                                        <input name="csys" class="radio" type="radio"><span>不相符</span>
                                    </p>
                                </td>
                                <td width="400">
                                    <textarea name="csys_notice" id="" cols="30" rows="1"></textarea>
                                    <span class="edit" ms-on-click="edit"></span>
                                </td>
                            </tr> 
                            <tr>
                                <td><p class="tac fs14">内饰颜色</p></td>
                                <td><p class="tac fs14"></p></td>
                                <td class="cell">
                                    <p class="tac fs14">
                                        <input name="nsys" checked="" class="radio" type="radio"><span>相符</span>
                                        <input name="nsys" class="radio" type="radio"><span>不相符</span>
                                    </p>
                                </td>
                                <td width="400">
                                    <textarea name="nsys_notice" id="" cols="30" rows="1"></textarea>
                                    <span class="edit" ms-on-click="edit"></span>
                                </td>
                            </tr> 
                            <tr>
                                <td><p class="tac fs14">行驶里程</p></td>
                                <td><p class="tac fs14"></p></td>
                                <td class="cell">
                                    <p class="tac fs14">
                                        <input name="licheng" checked="" class="radio" type="radio"><span>相符</span>
                                        <input name="licheng" class="radio" type="radio"><span>不相符</span>
                                    </p>
                                </td>
                                <td width="400">
                                    <textarea name="licheng_notice" id="" cols="30" rows="1"></textarea>
                                    <span class="edit" ms-on-click="edit"></span>
                                </td>
                            </tr> 
                            <tr>
                                <td><p class="tac fs14">出厂年月</p></td>
                                <td><p class="tac fs14"></p></td>
                                <td class="cell">
                                    <p class="tac fs14">
                                        <input name="chuchang" checked="" class="radio" type="radio"><span>相符</span>
                                        <input name="chuchang" class="radio" type="radio"><span>不相符</span>
                                    </p>
                                </td>
                                <td width="400">
                                    <textarea name="chuchang_notice" id="" cols="30" rows="1"></textarea>
                                    <span class="edit" ms-on-click="edit"></span>
                                </td>
                            </tr> 
                            <?php if ($userxzj): ?>
                                <tr>
                                <td><p class="tac fs14">已装原厂选装精品</p></td>
                                <td style="padding:0;">
                                    <table class="tbl2" width="100%">
                                        <tbody>
                                        <?php foreach ($userxzj as $key => $value): ?>
                                            <tr>
                                                <td class="bottomtborder">
                                                    <p class="tal fs14">{{$value['xzj_name']}}</p>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>

                                        </tbody>
                                    </table>
                                    
                                </td>
                                <td class="cell" style="padding:0;">
                                    <table class="tbl2" width="100%">
                                        <tbody>
                                        <?php foreach ($userxzj as $key => $value): ?>
                                            <tr>
                                                <td class="bottomtborder">
                                                    <p class="tac fs14">
                                                        <input name="ycxzj[{{$value['id']}}]" checked="" class="radio" value="1" type="radio"><span>相符</span>
                                                        <input name="ycxzj[{{$value['id']}}]" value="0" class="radio" type="radio"><span>不相符</span>
                                                    </p>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                            
                                            
                                        </tbody>
                                    </table>
                                    
                                </td>
                                <td width="400" <td style="padding:0;">
                                    <table class="tbl2" width="100%">
                                        <tbody>
                                        <?php foreach ($userxzj as $key => $value): ?>
                                            <tr>
                                                <td class="bottomtborder">
                                                    <textarea name="ycxzj_notice[{{$value['id']}}]" id="" cols="30" rows="1"></textarea>
                                                    <span class="edit" ms-on-click="edit"></span>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
 
                                        </tbody>
                                    </table>
                                    
                                </td>
                            </tr> 
                            <?php endif ?>
                            <?php if ($zengpin): ?>
                                <tr>
                                <td><p class="tac fs14">免费礼品和服务</p></td>
                                <td style="padding:0;">
                                    <table class="tbl2" width="100%">
                                        <tbody>
                                        <?php foreach ($zengpin as $key => $value): ?>
                                            <tr>
                                                <td class="bottomtborder">
                                                    <p class="tal fs14">{{$value['title']}}</p>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>

                                        </tbody>
                                    </table>
                                    
                                </td>
                                <td class="cell" style="padding:0;">
                                    <table class="tbl2" width="100%">
                                        <tbody>
                                        <?php foreach ($zengpin as $key => $value): ?>
                                            <tr>
                                                <td class="bottomtborder">
                                                    <p class="tac fs14">
                                                        <input name="zengpin[{{$value['id']}}]" checked="" class="radio" type="radio"><span>相符</span>
                                                        <input name="zengpin[{{$value['id']}}]" class="radio" type="radio"><span>不相符</span>
                                                    </p>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>

                                        </tbody>
                                    </table>
                                    
                                </td>
                                <td width="400" <td style="padding:0;">
                                    <table class="tbl2" width="100%">
                                        <tbody>
                                        <?php foreach ($zengpin as $key => $value): ?>
                                            <tr>
                                                <td class="bottomtborder">
                                                    <textarea name="zp_notice[{{$value['id']}}]" id="" cols="30" rows="1"></textarea>
                                                    <span class="edit" ms-on-click="edit"></span>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                            
                                        </tbody>
                                    </table>
                                    
                                </td>
                            </tr> 
                            <?php endif ?>
                            
                        </tbody>
                    </table>
                    <p class="center">
                        <a href="javascript:;" data-grounp="" class="btn btn-s-md btn-danger" name="bt_sure_2">确认</a>
                        <a href="javascript:;" data-grounp="" class="btn btn-s-md btn-danger oksure" style="display:none" name="bt_has_sure_2">已确认</a>
                        <a href="javascript:;" data-grounp="" class="juhuang tdu fs16" style="display:none" name="bt_modify_2">修改</a>
                    </p>
                    <hr class="dashed">


                    <table class="tbl">
                        <tbody>
                            <tr>
                                <th><label class="fs14">项目</label></th>
                                <th><label class="fs14">约定</label></th>
                                <th><label class="fs14">实际</label></th>
                                <th><label class="fs14">备注</label></th>
                            </tr>
                            <tr>
                                <td><p class="tac fs14">选装精品</p></td>
                                <td><p class="tac fs14"></p></td>
                                <td class="cell">
                                    <p class="tac fs14">
                                        <input name="xzj" checked="" class="radio" type="radio" value="1"><span>相符</span>
                                        <input name="xzj" class="radio" type="radio" value="0"><span>不相符</span>
                                    </p>
                                </td>
                                <td width="400">
                                    <textarea name="xzj_notice" id="" cols="30" rows="1"></textarea>
                                    <span class="edit" ms-on-click="edit"></span>
                                </td>
                            </tr> 
                            <tr>
                                <td><p class="tac fs14">车辆商业保险</p></td>
                                <td><p class="tac fs14"><a href="#" class="juhuang tdu">见附件一</a></p></td>
                                <td class="cell">
                                    <p class="tac fs14">
                                        <input name="baoxian" checked="" class="radio" type="radio" value="1"><span>相符</span>
                                        <input name="baoxian" class="radio" type="radio" value="0"><span>不相符</span>
                                    </p>
                                </td>
                                <td width="400">
                                    <textarea name="baoxian_notice" id="" cols="30" rows="1"></textarea>
                                    <span class="edit" ms-on-click="edit"></span>
                                </td>
                            </tr> 
                            <tr>
                                <td><p class="tac fs14">上牌服务</p></td>
                                <td><p class="tac fs14"><a href="#" class="juhuang tdu">见附件一</a></p></td>
                                <td class="cell">
                                    <p class="tac fs14">
                                        <input name="shangpai" checked="" value="1" class="radio" type="radio"><span>相符</span>
                                        <input name="shangpai" class="radio" type="radio" value="0"><span>不相符</span>
                                    </p>
                                </td>
                                <td width="400">
                                    <textarea name="shangpai_notice" id="" cols="30" rows="1"></textarea>
                                    <span class="edit" ms-on-click="edit"></span>
                                </td>
                            </tr> 
                            <tr>
                                <td><p class="tac fs14">上临时牌照</p></td>
                                <td><p class="tac fs14"></p></td>
                                <td class="cell">
                                    <p class="tac fs14">
                                        <input name="linpai" checked="" class="radio" type="radio" value="1"><span>相符</span>
                                        <input name="linpai" class="radio" type="radio" value="0"><span>不相符</span>
                                    </p>
                                </td>
                                <td width="400">
                                    <textarea name="linpai_notice" id="" cols="30" rows="1"></textarea>
                                    <span class="edit" ms-on-click="edit"></span>
                                </td>
                            </tr> 
                            <tr>
                                <td><p class="tac fs14">其他杂费收取</p></td>
                                <td><p class="tac fs14"></p></td>
                                <td class="cell">
                                    <p class="tac fs14">
                                        <input name="other" checked="" class="radio" type="radio" value="1"><span>相符</span>
                                        <input name="other" value="0" class="radio" type="radio"><span>不相符</span>
                                    </p>
                                </td>
                                <td width="400">
                                    <textarea name="other_notice" id="" cols="30" rows="1"></textarea>
                                    <span class="edit" ms-on-click="edit"></span>
                                </td>
                            </tr> 
                            <tr>
                                <td><p class="tac fs14">收到的文件资料</p></td>
                                <td><p class="tac fs14"></p></td>
                                <td class="cell">
                                    <p class="tac fs14">
                                        <input name="wenjian" checked="" class="radio" type="radio" value="1"><span>相符</span>
                                        <input name="wenjian" class="radio" value="0" type="radio"><span>不相符</span>
                                    </p>
                                </td>
                                <td width="400">
                                    <textarea name="wenjian_notice" id="" cols="30" rows="1"></textarea>
                                    <span class="edit" ms-on-click="edit"></span>
                                </td>
                            </tr> 
                            <tr>
                                <td><p class="tac fs14">收到的随车工具</p></td>
                                <td><p class="tac fs14"></p></td>
                                <td class="cell">
                                    <p class="tac fs14">
                                        <input name="gongju" checked="" value="1" class="radio" type="radio"><span>相符</span>
                                        <input name="gongju" value="0" class="radio" type="radio"><span>不相符</span>
                                    </p>
                                </td>
                                <td width="400">
                                    <textarea name="gongju_notice" id="" cols="30" rows="1"></textarea>
                                    <span class="edit" ms-on-click="edit"></span>
                                </td>
                            </tr> 
                            <tr>
                                <td><p class="tac fs14">车辆回程方式</p></td>
                                <td><p class="tac fs14"></p></td>
                                <td class="cell">
                                    <p class="tac fs14">
                                        <input name="huicheng" checked="" value="1" class="radio" type="radio"><span>相符</span>
                                        <input name="huicheng" class="radio" value="0" type="radio"><span>不相符</span>
                                    </p>
                                </td>
                                <td width="400">
                                    <textarea name="huicheng_notice" id="" cols="30" rows="1"></textarea>
                                    <span class="edit" ms-on-click="edit"></span>
                                </td>
                            </tr> 
                            <tr>
                                <td><p class="tac fs14">补贴</p></td>
                                <td><p class="tac fs14"></p></td>
                                <td class="cell">
                                    <p class="tac fs14">
                                        <input name="butie" checked="" value="1" class="radio" type="radio"><span>相符</span>
                                        <input name="butie" class="radio" value="0" type="radio"><span>不相符</span>
                                    </p>
                                </td>
                                <td width="400">
                                    <textarea name="butie_notice" id="" cols="30" rows="1"></textarea>
                                    <span class="edit" ms-on-click="edit"></span>
                                </td>
                            </tr> 
                            
                        </tbody>
                    </table>
                    <p class="center">
                        <a href="javascript:;" data-grounp="" class="btn btn-s-md btn-danger" name="bt_sure_3">确认</a>
                        <a href="javascript:;" data-grounp="" class="btn btn-s-md btn-danger oksure" style="display:none" name="bt_has_sure_3">已确认</a>
                        <a href="javascript:;" data-grounp="" class="juhuang tdu fs16" style="display:none" name="bt_modify_3">修改</a>
                    </p>
                    <hr class="dashed">
                    <?php if ($order['cartBase']['shangpai']): ?>
                    <div style="width: 70%;margin:0 auto">
                         <table class="tbl2">
                            <tbody>
                                <tr>
                                    <td class="p10 tar"><label class="fs14">车辆识别代号（VIN码）：</label></td>
                                    <td width="400" class="p10">
                                        <div class="btn-group m-r time-sl">
                                          <div class="form-group psr pdi-control">
                                            <input style="" name="vin" type="text" placeholder="" class="form-control pdi-control" value="{{$fin_car_info['vin']}}">
                                            <span class="edit"></span>
                                          </div>
                                        </div>
                                    </td>
                                </tr> 
                                <tr>
                                    <td class="p10 tar"><label class="fs14">发动机号：</label></td>
                                    <td width="400" class="p10">
                                        <div class="btn-group m-r time-sl">
                                          <div class="form-group psr pdi-control">
                                            <input style="" value="{{$fin_car_info['engine_no']}}" name="engine_no" type="text" placeholder="" class="form-control pdi-control">
                                            <span class="edit"></span>
                                          </div>
                                        </div>
                                    </td>
                                </tr> 
                                <tr>
                                  <th class="tar p10"><label class="fs14">上牌地区：</label></th>
                                  <th class="p10">
                                      <div class="btn-group m-r fl bts fn pdi-drop pdi-drop-warp">
                                        <button class="btn btn-sm btn-default dropdown-toggle area-drop-btn">
                                            <span class="dropdown-label"><span>{{$fin_car_info['shangpai_area']}}</span></span>
                                            <span class="caret"></span>
                                        </button>
                                        <div class="dropdown-menu dropdown-select area-tab-div">
                                            <input type="hidden" name="sheng" />
                                            <input type="hidden" name="shi" />
                                            <p class="area-tab"><span class="cur-tab">省份</span><span>城市</span></p>
                                            <dl class="dl">
                                                <?php foreach ($sheng as $key => $value): ?>
                                                      <dd ms-on-click="selectProvince({{$value['area_id']}})">{{$value['area_name']}}</dd>
                                                <?php endforeach ?>
                                                
                                              <div class="clear"></div>
                                            </dl>
                                            <dl class="dl" style="display: none;">
                                              <dd ms-repeat-city="citylist" ms-on-click="selectCity"><!--city.name--></dd>
                                              <div class="clear"></div>
                                            </dl>
                                        </div>
                                      </div>
                                  </th>
                              </tr>

                                <tr>
                                    <td class="p10 tar"><label class="fs14">车辆用途：</label></td>
                                    <td width="400" class="p10">
                                        <div class="btn-group m-r fl bts fn pdi-drop">
                                          <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                              <span class="dropdown-label"><span>{{$fin_car_info['yongtu']}}</span></span>
                                              <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-select">
                                              <input type="hidden" name="yongtu"  value="{{$fin_car_info['yongtu']}}" />
                                              <li ms-on-click="selectTime" class="active"><a><span>非营运个人客车</span></a></li>
                                              <li ms-on-click="selectTime"><a><span>非营运公司客车</span></a></li>
                                              
                                          </ul>
                                        </div>
                                    </td>
                                </tr>  
                                <tr>
                                    <td class="p10 tar"><label class="fs14">上牌（注册登记）车主名称：</label></td>
                                    <td width="400" class="p10">
                                        <div class="btn-group m-r time-sl">
                                          <div class="form-group psr pdi-control">
                                            <input style="" type="text" name="reg_name" placeholder="" value="{{$fin_car_info['reg_name']}}" class="form-control pdi-control">
                                            <span class="edit"></span>
                                          </div>
                                        </div>
                                    </td>
                                </tr> 
                                <tr>
                                    <td class="p10 tar"><label class="fs14">牌照号码：</label></td>
                                    <td width="400" class="p10">
                                        <!--苏-->
                                        <div class="btn-group m-r fl bts fn">
                                          <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                              <span class="dropdown-label"><span>{{$chepai[0]}}</span></span>
                                              <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-select chepai">
                                              <input type="hidden" name="chepai[]" value="{{$chepai[0]}}" />
                                              <li  ms-repeat-item="areaSn" ms-on-click="selectTime"  ms-class="<!--item == '{{$chepai[0]}}' ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                          </ul>
                                        </div>
                                        <!--E-->
                                        <div class="btn-group m-r fl bts fn">
                                          <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle">
                                              <span class="dropdown-label"><span>{{$chepai[1]}}</span></span>
                                              <span class="caret"></span>
                                          </button> 
                                          <ul class="dropdown-menu dropdown-select chepai">
                                              <input type="hidden" name="chepai[]" value="{{$chepai[1]}}"/>
                                              <li ms-repeat-item="en" ms-on-click="selectTime" ms-class="<!--item == '{{$chepai[1]}}' ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                          </ul> 
                                        </div>
                                        
                                        <div class="btn-group m-r fl bts fn">
                                          <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle ">
                                              <span class="dropdown-label"><span>{{$chepai[2]}}</span></span>
                                              <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-select chepai">
                                              <input type="hidden" name="chepai[]" value="{{$chepai[2]}}" />
                                              <li ms-repeat-item="fill" ms-on-click="selectTime" ms-class="<!--$index == {{$chepai[2]}} ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                          </ul>
                                        </div>

                                        <div class="btn-group m-r fl bts fn">
                                          <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle ">
                                              <span class="dropdown-label"><span>{{$chepai[3]}}</span></span>
                                              <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-select chepai">
                                              <input type="hidden" name="chepai[]" value="{{$chepai[3]}}" />
                                              <li ms-repeat-item="fill" ms-on-click="selectTime" ms-class="<!--$index == {{$chepai[3]}} ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                          </ul>
                                        </div>

                                        <div class="btn-group m-r fl bts fn">
                                          <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle ">
                                              <span class="dropdown-label"><span>{{$chepai[4]}}</span></span>
                                              <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-select chepai">
                                              <input type="hidden" name="chepai[]" value="{{$chepai[4]}}" />
                                              <li ms-repeat-item="fill" ms-on-click="selectTime" ms-class="<!--$index == {{$chepai[4]}} ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                          </ul>
                                        </div>

                                        <div class="btn-group m-r fl bts fn">
                                          <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle ">
                                              <span class="dropdown-label"><span>{{$chepai[5]}}</span></span>
                                              <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-select chepai">
                                              <input type="hidden" name="chepai[]" value="{{$chepai[5]}}" />
                                              <li ms-repeat-item="fill" ms-on-click="selectTime" ms-class="<!--$index == {{$chepai[5]}} ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                          </ul>
                                        </div>

                                        <div class="btn-group m-r fl bts fn">
                                          <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle ">
                                              <span class="dropdown-label"><span>{{$chepai[6]}}</span></span>
                                              <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-select chepai">
                                              <input type="hidden" name="chepai[]" value="{{$chepai[6]}}" />
                                              <li ms-repeat-item="fill" ms-on-click="selectTime" ms-class="<!--$index == {{$chepai[6]}} ? 'active' : ''-->"><a><span><!--item--></span></a></li>
                                          </ul>
                                        </div>

                                    </td>
                                </tr> 
                                <?php if ($order_attr['butie']): ?>
                                <tr>
                                    <td class="p10 tar"><label class="fs14">国家节能补贴发放约定时间：</label></td>
                                    <td width="300" class="p10">
                                        <div class="btn-group m-r time-sl">
                                          <div class="form-group psr pdi-control">
                                            <input style="" name="fafang_butie" type="text" placeholder="<?=!empty($fin_car_info['fafang_butie'])?$fin_car_info['fafang_butie']:""?>" class="form-control " onfocus="WdatePicker({minDate:'2015-12-2 00:00:00',startDate:'2015-12-19 00:00:00' });">
                                            <i class="rili"></i>
                                          </div>
                                        </div>
                                    </td>
                                </tr> 
                                <?php endif ?>
                            </tbody>
                        </table>
                    </div>
                    <p class="center">
                        <a href="javascript:;" data-grounp="" class="btn btn-s-md btn-danger" name="bt_sure_4">确认</a>
                        <a href="javascript:;" data-grounp="" class="btn btn-s-md btn-danger oksure" style="display:none" name="bt_has_sure_4">已确认</a>
                        <a href="javascript:;" data-grounp="" class="juhuang tdu fs16" style="display:none" name="bt_modify_4">修改</a>
                    </p>
                       <?php else: ?>                 
                    <br>    <br>    

                    <div style="width: 70%;margin:0 auto">
                         <table class="tbl2">
                            <tbody>
                                <tr>
                                    <td class="p10 tar"><label class="fs14">车辆识别代号（VIN码）：</label></td>
                                    <td width="400" class="p10">
                                        <div class="btn-group m-r time-sl">
                                          <div class="form-group psr pdi-control">
                                            <input style="" type="text" name="vin" value="{{$fin_car_info['vin']}}" placeholder="" class="form-control pdi-control">
                                            <span class="edit"></span>
                                          </div>
                                        </div>
                                    </td>
                                </tr> 
                                <tr>
                                    <td class="p10 tar"><label class="fs14">发动机号：</label></td>
                                    <td width="400" class="p10">
                                        <div class="btn-group m-r time-sl">
                                          <div class="form-group psr pdi-control">
                                            <input style="" value="{{$fin_car_info['engine_no']}}" name="engine_no" type="text" placeholder="" class="form-control pdi-control">
                                            <span class="edit"></span>
                                          </div>
                                        </div>
                                    </td>
                                </tr> 
                                <tr>
                                    <td class="p10 tar"><label class="fs14">预计上牌(注册登记)最晚日期：</label></td>
                                    <td width="300" class="p10">
                                        <div class="btn-group m-r time-sl">
                                          <div class="form-group psr pdi-control">
                                            <input style="" name="shangpai_time" type="text" placeholder="2015年10月10号" class="form-control " onfocus="WdatePicker({minDate:'2015-12-2 00:00:00',startDate:'2015-12-19 00:00:00' });">
                                            <i class="rili"></i>
                                          </div>
                                        </div>
                                    </td>
                                </tr> 
                                
                            </tbody>
                        </table>
                    </div>
                    <p class="center">
                        <a href="javascript:;" data-grounp="" class="btn btn-s-md btn-danger" name="bt_sure_5">确认</a>
                        <a href="javascript:;" data-grounp="" class="btn btn-s-md btn-danger oksure" style="display:none" name="bt_has_sure_5">已确认</a>
                        <a href="javascript:;" data-grounp="" class="juhuang tdu fs16" style="display:none" name="bt_modify_5">修改</a>
                    </p>
                <?php endif ?>
                <?php if ($order_attr['butie']): ?>
                    <p class="m-t-10"><small>温馨提示：如您本人上牌时间过长，可能因为国家补贴政策、厂商内部政策调整而导致原国家节能补贴发放条件发生变化。对交车（收到任一方交车信息日）后15个自
然日内提交正确上牌信息，且符合国家政策规定的客户，华车平台提供国家节能补贴发放担保。除此以外的客户，请自行与经销商协商约定发放和领取该补贴事宜。</small></p>
                <?php endif ?>
                    <hr class="dashed">
                    <p>
                       <input type="button" value="提交" class="btn btn-s-md btn-danger fl" ms-on-click="submit_form">
                       <a href="javascript:;" class="btn btn-s-md btn-danger fl oksure ml20">已提交</a>
                    </p>
                    <p class="fs14 clear m-t-10">
                        <input type="checkbox"><span class="fn">我已接受上述实际状况的车辆、附加品、文件、服务。</span>
                    </p>
                </div>
               
            </div>
        <input type="hidden" value="{{$order_num}}" name="order_num" >
        <input type="hidden" value="{{$id}}" name="id" >
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        </form>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/item/item-payment-while", "module/common/common", "bt"]);
    </script>
@endsection
