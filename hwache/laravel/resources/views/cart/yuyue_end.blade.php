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
                <li class="step-cur">开始交车<i></i></li>
                <li>付款提车<i></i></li>
                <li>退担保金<i></i></li>
                <li>完成评价<i></i></li>
                <div class="clear"></div>
            </ul>
            <div class="min-step">
                <div class="m-content pdi">
                    <small>开始预约</small>
                    <i></i>
                    <small>反馈确认</small>
                    <i></i>
                    <small class="juhuang">预约完毕</small>
                </div>
            </div>
        </div>
    </div>

    <div class="container pos-rlt content r-pdi" ms-controller="item">
        <form action="{{url('cart/gotiche')}}" method="post">
        <input type="hidden" name="order_num" value="{{ $order_num}}">
        <input type="hidden" name="id" value="{{ $id}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="wapper has-min-step" style="overflow: visible;">
            <h1>尊敬的客户：</h1>
            <h1 class="ti">根据您与经销商达成的买车交车最终约定，经销商即将向您移交尊驾，请作好必要准备。</h1>
            <br>
            
            <!--您与经销商线下交接车辆-->
            <div class="box">
                <div class="title long-title">
                    <label>一、您与经销商线下交接车辆，约定事项如下</label>
                    <em></em>
                    <code class="besure"></code>
                </div>
                <div class="box-inner  box-inner-def kuang">
                    <table width="100%">
                        <tbody>
                            <tr>
                                <td valign="top" > 

                                    <div class="psr fs14">
                                          <i class="yuan"></i><b>订单号：</b>{{ $order['cartBase']['order_num']}}
                                          <span class="ml20"><b>订单时间</b>：{{ $order['cartBase']['created_at']}}</span>
                                          <span class="sj"  ms-mouseover="displayTm()" ms-mouseout="hideTm()">
                                             <b>更多</b>
                                          </span>
                                          <p class="tm tm-long" style="left: 160px;">
                                             @if(count($cart_log)>0)     
												@foreach($cart_log as $k =>$v )
												<span>{{$v['msg_time']}}：{{$v['time']}}</span>
												@endforeach
											@endif
                                          </p>
                                    </div>
                                    <br>
                                    <p class="ifl"><i class="yuan"></i></p>
                                    <div class="ifl">
                                        <p><b>经销商名称：</b>{{$jxs['d_name']}}</p>
                                        <p><b>服务专员姓名： </b>{{$zhuanyuan['name']}}</p>
                                        <p><b> 电话：</b>{{$zhuanyuan['mobile']}}  <b>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        备用电话：</b>{{$zhuanyuan['tel']}}</p>
                                    </div>
                                    <div class="clear"></div>

                                        
                                    <p class="ifl"><i class="yuan"></i></p>
                                    <div class="ifl">
                                        <p><b>交车地点：</b>{{$jxs['d_jc_place']}}</p>
                                        <p><b>前往方式： </b>{{$take_way}}        </p>
                                    </div>
                                    <div class="clear"></div>
                                    <p><i class="yuan"></i><b>上牌名称：</b>张三</p>
                                    <p><i class="yuan"></i><b>提车人姓名：</b>{{$ticheren['username']}}
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <b>电话： </b>{{$ticheren['mobile']}}
                                    </p>
                                
                                </td>
                                <td align="center" valign="top" width="270">
                                    <p class="tal"><b class="tal">交车地点图示</b></p>
                                    <img  width="270" src="/themes/images/item/map.gif" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                </div>

                <p class="tac pt20">订单其他详细约定，请查看<a href="{{url('orderoverview')}}/{{$order_num}}" class="juhuang" target="_blank">订单总详情</a></p>

                <div class="box-inner  box-inner-def">

                    <h2 class="title">已同经销商约定的交车各环节费用汇总</h2>
                    <div style="width: 80%;margin:0 auto">
                        <table class="tbl">
                            <tr>
                                <th class="tal"><label  class="fs14">名称</label></th>
                                <th class="tal"><label  class="fs14">金额</label></th>
                                <th class="tal"><label  class="fs14">备注</label></th>
                            </tr>
                            <tr>
                                <td><p class="tal fs14">裸车开票价格</p></td>
                                <td><p class="tal fs14">{{ $bj['bj_lckp_price'] }}</p></td>
                                <td><p class="tal fs14">先验车后支付！</p></td>
                            </tr>
                            <?php if ($xzj_total>0): ?>
                                <tr>
                                <td><p class="tal fs14">选装精品合计</p></td>
                                <td><p class="tal fs14">{{$xzj_total}}</p></td>
                                <td><p class="tal fs14">在经销商处当场支付</p></td>
                            </tr>
                            <?php endif ?>
                            <?php if ($order['cartBase']['final_baoxian']): ?>
                                <tr>
                                <td><p class="tal fs14">首年车辆商业保险保费合计</p></td>
                                <td><p class="tal fs14"></p></td>
                                <td><p class="tal fs14">投保时支付</p></td>
                            </tr>
                            <?php endif ?>
                            

                            <?php if ($order['cartBase']['shangpai']): ?>
                                <tr>
                                    <td><p class="tal fs14">上牌服务费</p></td>
                                    <td><p class="tal fs14">{{$bj['bj_shangpai_price']}}</p></td>
                                    <td><p class="tal fs14">在经销商处当场支付</p></td>
                                </tr>
                            <?php endif ?>
                            <?php if ($order['cartBase']['linpai']): ?>
                                <tr>
                                <td><p class="tal fs14">上临时牌照服务费</p></td>
                                <td><p class="tal fs14">{{$bj['bj_linpai_price']}}</p></td>
                                <td><p class="tal fs14">在经销商处当场支付</p></td>
                            </tr>
                            <?php endif ?>
                            
                            <tr>
                                <td><p class="tal fs14">其他杂费合计</p></td>
                                <td><p class="tal fs14">{{$other_price_total}}</p></td>
                                <td><p class="tal fs14">在经销商处当场支付</p></td>
                            </tr>

                            <?php if (strpos($trip_way['fangshi'],'代驾')!==false): ?>
                              <tr>
                                    <td><p class="tal fs14">代驾送车服务费</p></td>
                                    <td><p class="tal fs14">{{$trip_way['price']}}</p></td>
                                    <td><p class="tal fs14">{{$trip_way['pay']}}</p></td>
                                </tr>
                            <?php endif ?>
                            <?php if (strpos($trip_way['fangshi'],'板车')!==false): ?>
                            <tr>
                                <td><p class="tal fs14">板车运输送车运费</p></td>
                                <td><p class="tal fs14">{{$trip_way['price']}}</p></td>
                                <td><p class="tal fs14">{{$trip_way['pay']}}</p></td>
                            </tr>
                            <tr>
                                <td><p class="tal fs14">板车运输送车运输险保费</p></td>
                                <td><p class="tal fs14">{{$trip_way['baoxian']}}</p></td>
                                <td><p class="tal fs14">{{$trip_way['pay']}}</p></td>
                            </tr>
                          <?php endif ?>
                            <?php if ($fl_price>0): ?>
                            <tr>
                                <td><p class="tal fs14">特别约定之证明办理费用</p></td>
                                <td><p class="tal fs14">{{$fl_price}}</p></td>
                                <td><p class="tal fs14">在经销商处当场支付</p></td>
                            </tr>
                          <?php endif ?>
                            <?php if ($order['cartBase']['overtime']): ?>
                                <tr>
                                    <td><p class="tal fs14">超期提车补偿金额</p></td>
                                    <td><p class="tal fs14">{{$jc_date['fee']}}</p></td>
                                    <td><p class="tal fs14">在经销商处当场支付</p></td>
                                </tr>
                            <?php endif ?>
                            <?php if ($order_attr['butie']): ?>
                                <tr>
                                    <td><p class="tal fs14">国家节能补贴</p></td>
                                    <td><p class="tal fs14">-{{$bj['bj_butie']}} </p></td>
                                    <td><p class="tal fs14">补贴</p></td>
                                </tr>
                            <?php endif ?>
                        </table>

                        
                    </div>
                    

                    <h2 class="title">您须提供的各环节文件资料汇总   </h2>
                    <div style="width: 70%;margin:0 auto">

                        <table class="tbl" ms-if="!view">
                              <tr>
                                  <td class="cell" colspan="3">
                                <b>查看方式：</b>
                                <input ms-attr-checked="!view" name="baoxian" ms-on-click="viewMethod(1)"  class="radio" type="radio"><span>按使用场合查看</span>
                                <input name="baoxian" ms-on-click="viewMethod(1)"  class="radio" type="radio"><span>按名称查看</span>
                            </td>
                              </tr>
                              <tr>
                                  <td width="160"><p class="tac fs14 weight">使用场合</p></td>
                                  <td width="198"><p class="tac fs14 weight">文件资料</p></td>
                                  <td width="150"><p class="tac fs14 weight">数量</p></td>
                              </tr> 
                              <?php foreach ($files1 as $key => $value): ?>
                                <tr>
                                  <td width="160"><p class="tac fs14 ">{{$key}}</p></td>
                                  <td colspan="2" class="nopadding ">
                                     <table class="tbl2" width="100%">
                                      <?php foreach ($value as $k => $v): 
                                        
                                      ?>
                                        <tr>
                                              <td width="197" class="bottomtborder ">
                                                  <p class="tac fs14">{{$v['title']}}</p>
                                              </td>
                                              <td width="150" class="bottomtborder norightborder">
                                                  <p class="tac fs14">{{$v['num']}}</p>
                                              </td>
                                              
                                          </tr>
                                      <?php endforeach ?>
                                          
                                          
                                      </table>
                                  </td>
                              </tr>
                              <?php endforeach ?>
                               

                            </table>
                        

                        <table class="tbl" ms-if="view">
                              <tr>
                                  <td class="cell" colspan="3">
                                <b>查看方式：</b>
                                <input name="baoxian2" ms-on-click="viewMethod(2)"  class="radio" type="radio"><span>按使用场合查看</span>
                                <input name="baoxian2" ms-attr-checked="view" ms-on-click="viewMethod(2)" checked="" class="radio" type="radio"><span>按名称查看</span>
                            </td>
                              </tr>
                              <tr>
                                  <td width="160"><p class="tac fs14 weight">文件资料</p></td>
                                  <td width="198"><p class="tac fs14 weight">数量</p></td>
                                  <td width="150"><p class="tac fs14 weight">使用场合</p></td>
                              </tr> 
                                <?php foreach ($files2 as $key => $value): ?>
                                  <tr>
                                    <td width="160"><p class="tac fs14 ">{{$value['title']}}</p></td>
                                    <td class="">
                                       <p class="tac fs14">{{$value['num']}}</p>
                                    </td>
                                    <td class="">
                                       <p class="tac fs14">{{$value['cate']}}</p>
                                    </td>
                                </tr>
                                <?php endforeach ?>
                               

                           
                            </table>

                    </div>

                    <p><b>经销商向您当场移交的文件资料：   </b>{{$gongju}}</p>
                    <p><b>经销商向您当场移交的随车工具： </b>{{$wenjian}}</p>
                    <p>为保障您的权益，请在交车现场正式交车前，与经销商签订《交车确认书》，并参照《交车宝典》接收尊驾。             </p>
                    <p>
                        <a href="#" class="tdu juhuang ml150">下载交车确认书</a>
                        <a href="#" class="tdu juhuang ml150">下载交车宝典</a>
                    </p>
                </div>
            </div>

            <!--您与华车平台约定事项如下-->
            <div class="box">
                <div class="title">
                    <label>二、您与华车平台约定事项如下</label>
                    <em></em>
                    <code class="besure"></code>
                </div>
                <div class="box-inner box-inner-def">
                    <p><i class="yuan"></i><b>买车担保金：</b>人民币<span class="baoxianinput juhuang shot"> {{$guarantee}} </span>元  </p>
                    <p class="pl20"><b>约定内容：</b>已冻结在华车平台加信宝，订单完成后扣除华车服务费和您的其他违约赔偿（如产生）后，多余金额将原路退还给您。     </p>
 
                    <p><i class="yuan"></i><b>华车服务费：</b>人民币<span class="baoxianinput juhuang shot"> {{$sysprice['hwacheServicePrice']}} </span>元  </p>
                    <p class="pl20"><b>约定内容：</b>订单完成后从买车担保金中扣除。</p>
                        <?php if ($bj['bj_license_plate_break_contract']): ?>
                            <p><i class="yuan"></i><b>客户本人上牌违约赔偿金额：</b>人民币 <span class="baoxianinput juhuang shot"> {{$bj['bj_license_plate_break_contract']}} </span>元  </p>
                    <p class="pl20"><b>约定内容：</b>（交车后）您提交的上牌信息审核通过后，该金额作为应退还买车担保金的一部分，将原路退还给您。</p>
                          <?php endif ?>      
                </div>
            </div>
            <!--与本次购车可能有关的其他主要费用 -->
            <div class="box">
                <div class="title long-title">
                    <label >三、温馨提示：与本次购车可能有关的其他主要费用 </label>
                    <em></em>
                    <code class="besure"></code>
                </div>
                <div class="box-inner box-inner-def">
                    <p>注：显示的项目和金额仅供参考，请以您本人实际办理为准。                  </p>
                 
                    <table class="tbl">
                        <tr>
                            
                            <th class="tal"><label class="fs14">名称</label></th>
                            <th class="tal"><label class="fs14">参考金额或参考计算标准</label></th>
                            <th class="tal"><label class="fs14">备注</label></th>
                        </tr>
                        <tr>
                            
                            <td><p class="tal fs14">交强险</p></td>
                            <td><p class="tal fs14">人民币<span class="baoxianinput juhuang shot"> {{$bj['barnd_info']['qiangxian']}} </span>元 </p></td>
                            <td><p class="tal fs14">投保时支付</p></td>
                        </tr>
                        <tr>
                            
                            <td><p class="tal fs14">车船使用税</p></td>
                            <td><p class="tal fs14">人民币<span class="baoxianinput juhuang shot"> {{$bj['barnd_info']['chechuan']}} </span>元</p></td>
                            <td><p class="tal fs14">保险公司代收或办税服务厅缴纳</p></td>
                        </tr>
                        <tr>
                            
                            <td><p class="tal fs14">车辆购置税</p></td>
                            <td><p class="tal fs14">不含增值税的购车总价款 <span class="baoxianinput juhuang shot"> {{$bj['barnd_info']['gouzhi']}} </span> %</p></td>
                            <td><p class="tal fs14">上牌地购置税办税服务厅缴纳</p></td>
                        </tr>
                        <?php if (!$order['cartBase']['final_baoxian']): ?>
                        <tr>
                            <td><p class="tal fs14">车辆首年商业保险保费  </p></td>
                            <td><p class="tal fs14"><span class="baoxianinput juhuang shot"> {{$bxprice}} 元</span></p></td>
                            <td><p class="tal fs14">投保时支付</p></td>
                        </tr>
                        <?php endif ?>
                        <?php if (!$order['cartBase']['shangpai']): ?>
                        <tr>
                            <td><p class="tal fs14">上牌费用</p></td>
                            <td><p class="tal fs14"><span class="baoxianinput juhuang shot"> {{$bj['bj_shangpai_price']}} 元</span></p></td>
                            <td><p class="tal fs14">按实际发生</p></td>
                        </tr>
                        <?php endif ?>
                        <tr>
                            <td><p class="tal fs14">交通费</p></td>
                            <td><p class="tal fs14"></p></td>
                            <td><p class="tal fs14">按实际发生</p></td>
                        </tr>
                        <tr>
                            <td><p class="tal fs14">住宿费</p></td>
                            <td><p class="tal fs14"></p></td>
                            <td><p class="tal fs14">按实际发生</p></td>
                        </tr>
                    </table>
                 
                   
                    <p class="center">
                        <a href="{{url('cart/tiche_info')}}/{{$order_num}}" data-grounp="" class="btn btn-s-md btn-danger ok">收车</a>
                        
                    </p>
                </div>
            </div>

        </div>
        </form>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/item/item-pdi-confirm", "module/common/common", "bt"]);
    </script>
@endsection