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
  <div class="container m-t-86 pos-rlt">
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
                    <small>即将交车</small>
                    <i></i>
                    <small class="juhuang">正在交车</small>
                    <i></i>
                    <small>交车完毕</small>
                </div>
            </div>
        </div>
    </div>

    <div class="container pos-rlt content r-pdi" ms-controller="item">

        <div class="wapper has-min-step" style="overflow: visible;">
            <h1>尊敬的客户：</h1>
            <h1 class="ti">2015年10月20日，掐指一算，提车吉日已到，粮草都备足了吗？</h1>
            <h1 class="ti">检查一遍吧！最后！  </h1>
            <br>
              
    
         
            <!--可能发生的其他主要费用温馨提示-->
            <div class="box">
                
                <h2 class="title noborder">已同经销商约定的交车各环节费用汇总：</h2>
                <div class="box-inner box-inner-def">
                    <div style="width: 90%;margin:0 auto">
                        <table class="tbl">
                            <tbody><tr>
                                <th class="tal"><label class="fs14">序号</label></th>
                                <th class="tal"><label class="fs14">名称</label></th>
                                <th class="tal"><label class="fs14">金额</label></th>
                                <th class="tal"><label class="fs14">备注</label></th>
                            </tr>
                            <tr>
                                <td><p class="tal fs14">1</p></td>
                                <td><p class="tal fs14">裸车开票价格</p></td>
                                <td><p class="tal fs14"></p></td>
                                <td><p class="tal fs14">先验车后支付！</p></td>
                            </tr>
                            <tr>
                                <td><p class="tal fs14">2</p></td>
                                <td><p class="tal fs14">选装精品合计</p></td>
                                <td><p class="tal fs14"></p></td>
                                <td><p class="tal fs14">在经销商处当场支付</p></td>
                            </tr>
                            <tr>
                                <td><p class="tal fs14">3</p></td>
                                <td><p class="tal fs14">首年车辆商业保险保费合计</p></td>
                                <td><p class="tal fs14"></p></td>
                                <td><p class="tal fs14">投保时支付</p></td>
                            </tr>
                            <tr>
                                <td><p class="tal fs14">4</p></td>
                                <td><p class="tal fs14">上牌服务费</p></td>
                                <td><p class="tal fs14"></p></td>
                                <td><p class="tal fs14">在经销商处当场支付</p></td>
                            </tr>
                            <tr>
                                <td><p class="tal fs14">5</p></td>
                                <td><p class="tal fs14">上临时牌照服务费</p></td>
                                <td><p class="tal fs14"></p></td>
                                <td><p class="tal fs14">在经销商处当场支付</p></td>
                            </tr>
                            <tr>
                                <td><p class="tal fs14">6</p></td>
                                <td><p class="tal fs14">其他杂费合计</p></td>
                                <td><p class="tal fs14"></p></td>
                                <td><p class="tal fs14">在经销商处当场支付</p></td>
                            </tr>
                            <tr>
                                <td><p class="tal fs14">7</p></td>
                                <td><p class="tal fs14">代驾送车</p></td>
                                <td><p class="tal fs14"></p></td>
                                <td><p class="tal fs14">车送到目的地后当场支付</p></td>
                            </tr>
                            <tr>
                                <td><p class="tal fs14">8</p></td>
                                <td><p class="tal fs14">板车运输送车</p></td>
                                <td><p class="tal fs14"></p></td>
                                <td><p class="tal fs14">提车时先支付后送车</p></td>
                            </tr>
                            <tr>
                                <td><p class="tal fs14">9</p></td>
                                <td><p class="tal fs14">特别约定之证明办理费用</p></td>
                                <td><p class="tal fs14"></p></td>
                                <td><p class="tal fs14">在经销商处当场支付</p></td>
                            </tr>
                            <tr>
                                <td><p class="tal fs14">10</p></td>
                                <td><p class="tal fs14">超期提车补偿金额</p></td>
                                <td><p class="tal fs14"></p></td>
                                <td><p class="tal fs14">在经销商处当场支付</p></td>
                            </tr>
                            <tr>
                                <td><p class="tal fs14">11</p></td>
                                <td><p class="tal fs14">国家节能补贴</p></td>
                                <td><p class="tal fs14">— 人民币   元</p></td>
                                <td><p class="tal fs14">补贴</p></td>
                            </tr>
                        </tbody></table>
                    </div>

                    <h2 class="title noborder">可能发生的其他主要费用温馨提示：</h2>
                    <table class="tbl">
                        <tbody>
                            <tr>
                                <th class="tal"><label class="fs14">序号</label></th>
                                <th class="tal"><label class="fs14">名称</label></th>
                                <th class="tal"><label class="fs14">参考金额或参考计算标准</label></th>
                                <th class="tal"><label class="fs14">备注</label></th>
                            </tr>
                            <tr>
                                <td><p class="tal fs14">1</p></td>
                                <td><p class="tal fs14">交强险</p></td>
                                <td><p class="tal fs14">人民币<input type="text" class="baoxianinput juhuang shot" name="money" value=""> 元</p></td>
                                <td><p class="tal fs14">投保时支付</p></td>
                            </tr>
                            <tr>
                                <td><p class="tal fs14">2</p></td>
                                <td><p class="tal fs14">车船使用税</p></td>
                                <td><p class="tal fs14">人民币<input type="text" class="baoxianinput juhuang shot" name="money" value=""> 元</p></td>
                                <td><p class="tal fs14">保险公司代收或办税服务厅缴纳</p></td>
                            </tr>
                            <tr>
                                <td><p class="tal fs14">3</p></td>
                                <td><p class="tal fs14">购置税</p></td>
                                <td><p class="tal fs14">不含增值税的购车总价款10%<input type="text" class="baoxianinput juhuang shot" name="money" value=""> 元</p></td>
                                <td><p class="tal fs14">上牌地购置税办税服务厅缴纳</p></td>
                            </tr>
                            <tr>
                                <td><p class="tal fs14">4</p></td>
                                <td><p class="tal fs14">首年车辆商业保险保费  </p></td>
                                <td><p class="tal fs14"></p></td>
                                <td><p class="tal fs14">投保时支付</p></td>
                            </tr>
                            <tr>
                                <td><p class="tal fs14">5</p></td>
                                <td><p class="tal fs14">上牌费用  </p></td>
                                <td><p class="tal fs14"></p></td>
                                <td><p class="tal fs14">按实际发生</p></td>
                            </tr>
                            <tr>
                                <td><p class="tal fs14">6</p></td>
                                <td><p class="tal fs14">交通费  </p></td>
                                <td><p class="tal fs14"></p></td>
                                <td><p class="tal fs14">按实际发生</p></td>
                            </tr>
                            <tr>
                                <td><p class="tal fs14">7</p></td>
                                <td><p class="tal fs14">住宿费  </p></td>
                                <td><p class="tal fs14"></p></td>
                                <td><p class="tal fs14">按实际发生</p></td>
                            </tr>
                            <tr>
                                <td><p class="tal fs14">8</p></td>
                                <td><p class="tal fs14">  </p></td>
                                <td><p class="tal fs14"></p></td>
                                <td><p class="tal fs14"></p></td>
                            </tr>
                        </tbody>
                    </table>

                    <p class="tar">
                        <a href="javascript:;" data-grounp="" class="btn btn-s-md btn-danger ok oksure">已确认</a>
                        <a href="javascript:;" data-grounp="" class="btn btn-s-md btn-danger ok">OK</a>
                    </p>
                    
                     <h2 class="title noborder">您须提供的各环节文件资料汇总：</h2>
                     <div style="width: 80%;margin:0 auto">
                        <table class="tbl">
                            <tbody>
                                <tr>
                                    <th class="tal"><label class="fs14">文件资料</label></th>
                                    <th class="tal"><label class="fs14">数量</label></th>
                                    <th class="tal"><label class="fs14">使用场合</label></th>
                                </tr>
                                <tr>
                                    <td><p class="tal fs14">身份证正本</p></td>
                                    <td><p class="tal fs14">1</p></td>
                                    <td><p class="tal fs14">提车人身份验证、代办上牌手续</p></td>
                                </tr> 
                                <tr>
                                    <td><p class="tal fs14">&nbsp;</p></td>
                                    <td>
                                      <p class="tal fs14">&nbsp;</p>
                                    </td>
                                    <td>
                                       <p class="tal fs14">&nbsp;</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td><p class="tal fs14">&nbsp;</p></td>
                                    <td>
                                      <p class="tal fs14">&nbsp;</p>
                                    </td>
                                    <td>
                                       <p class="tal fs14">&nbsp;</p>
                                    </td>
                                </tr> 
                                <tr>
                                    <td><p class="tal fs14">身份证复印件</p></td>
                                    <td><p class="tal fs14">3</p></td>
                                    <td><p class="tal fs14"></p></td>
                                </tr>
                              
                                <tr>
                                    <td><p class="tal fs14">&nbsp;</p></td>
                                    <td>
                                      <p class="tal fs14">&nbsp;</p>
                                    </td>
                                    <td>
                                       <p class="tal fs14">&nbsp;</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td><p class="tal fs14">&nbsp;</p></td>
                                    <td>
                                      <p class="tal fs14">&nbsp;</p>
                                    </td>
                                    <td>
                                       <p class="tal fs14">&nbsp;</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                     </div>
                     <p class="tar">
                        <a href="javascript:;" data-grounp="" class="btn btn-s-md btn-danger ok oksure">已确认</a>
                        <a href="javascript:;" data-grounp="" class="btn btn-s-md btn-danger ok">OK</a>
                     </p>

                     <p class="ml150">
                        <a href="#" class="tdu juhuang ml150">下载交车确认书</a>
                        <a href="#" class="tdu juhuang ml150">下载交车宝典</a>
                     </p>
                     <p class="center">
                        <a href="javascript:;" data-grounp="" class="btn btn-s-md btn-danger payment">准备好了，可以交车了</a>
                    </p>

                </div>
            </div>

        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/item/item-payment-while", "module/common/common", "bt"]);
    </script>
@endsection
