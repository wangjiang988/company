@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
                    <div class="custom-offer-step-wrapper">
                        <ul>
                          <li>车型价格</li>
                          <li>车况说明</li>
                          <li class="prev">选装精品</li>
                          <li class="cur">首年保险</li>
                          <li>收费标准</li>
                          <li class="last">其他事项</li>
                          <div class="clear"></div>
                        </ul>
                    </div> 
                    
                    
                    <form action="/dealer/baojia/edit/{{$baojia['bj_id']}}/4" name="baojia-submit-form" method="post">
                        <input type='hidden' name='_token' value="{{csrf_token()}}">
                        <input type="hidden" name="seat_num" value="{{$seat_num}}">
                        <div class="content-wapper ">
                           
                            <p><b>车辆首年商业保险：</b></p>
                            <p class="ml20 nomargin">
                              <label class="noweight"><input type="radio" name="bj_baoxian" value="1" <?php if($baojia['bj_baoxian']==1){echo 'checked';}?>><span class="ml5">客户必须在经销商处投保（客户上牌地必须在保险公司理赔范围内）</span></label>
                            </p>
                            <p class="ml20  nomargin">
                              <label class="noweight"><input type="radio" name="bj_baoxian" value="0" <?php if($baojia['bj_baoxian']==0){echo 'checked';}?>><span class="ml5">客户自由投保</span></label>
                            </p>
                            <table class="custom-info-tbl noborder">
                              <tbody>
                                <tr>
                                   <td class="tar" style="padding-left:0;text-align:left;">
                                      <label>保险公司：</label>
                                   </td>
                                   <td class="tal">
                                      <div class="btn-group btn-group-auto btn-jquery-event-use-id">
                                          <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle dropdown-company">
                                              <span class="dropdown-label">
                                              	<span>
                                              	@if($baojia['bj_bx_select']>0)
                                              		{{@$baoxianList[$baojia['bj_bx_select']]['bx_title']}}
                                              	@else
                                              		请选择保险公司
                                              	@endif
                                              	 </span>
                                              </span>
                                              <span class="caret"></span>
                                          </button>
                                          <ul class="dropdown-menu dropdown-company">
                                              <input type="hidden" name="bj_bx_select" value="{{$baojia['bj_bx_select']}}">
                                              @if(count($baoxianList)>0)
                                              @foreach($baoxianList as $k=>$v)
                                              <li class="claims-scope {{$k==$baojia['bj_bx_select']?'active':''}}" data-bind="<?php if($v['bx_is_quanguo']==1){echo '1,1';}else{echo '1,0';}?>" data-value="{{$v['bx_id']}}"><a><span>{{$v['bx_title']}} </span></a></li>
                                              @endforeach
                                              @endif
                                          </ul>
                                      </div>
                                   </td>
                                </tr>

                                <tr>
                                   <td class="tar" style="padding-left:0;text-align:left;">
                                      <label>理赔范围：</label>
                                   </td>
                                   <td class="tal">
                                      <div class="checkbox-wrapper psr">
                                          <label class="mt"><input disabled="" checked=""  type="checkbox" name="scope-1" id=""><span>本地</span></label>
                                          <label class="ml mt" id="icon-disabled"><input  disabled="" type="checkbox" name="scope-1" id="" <?php if(@$baoxianList[$baojia['bj_bx_select']]['bx_is_quanguo']==1){echo 'checked';}?>><span>异地</span></label>
                                          <input type="hidden" name="">
                                          <div class="th-tip scope-tip">
                                              <i></i>
                                              该保险公司不支持全国通赔，不可选
                                          </div>
                                          <span class="icon-disabled <?php if(@$baoxianList[$baojia['bj_bx_select']]['bx_is_quanguo']==1){echo 'none';}?>" ></span>
                                          
                                      </div>
                                   </td>
                                </tr>



                               
                              </tbody>
                            </table>
                        
                            <p class="text-right">
                              <a href="/upload/保险费模板.xls" class="juhuang tdu">下载保险报价模板</a>
                              <a href="#" class="juhuang tdu ml20" id="importfile-btn">导入数据</a>
                              <input type="file" name='importfile' class="none" >
                            </p>
                            <table class="tbl w700 tbl-insurance">
                              <tbody>
                                 <tr>
                                   <td rowspan="2" width="40"><b class="fs14">类别</b></td>
                                   <td rowspan="2" width="158"><b class="fs14">险种</b></td>
                                   <td rowspan="2" width="258"><b class="fs14">赔付选项</b></td>
                                   <td colspan="2"><b class="fs14">保费折后价</b></td> 
                                 </tr>
                                 <tr>
                                   <td width="132"><b class="fs14">非营业个人客车</b></td>
                                   <td width="132"><b class="fs14">非营业企业客车</b></td>
                                 </tr>
                                 <!--机动车损失险-->
                                 <tr>
                                   <td rowspan="18" valign="middle">
                                      <b>主<br><br><br><br>险</b>
                                   </td>
                                   <td>机动车损失险</td>
                                   <td class="tal"><span class="ml10">按保险公司规定执行</span></td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="chesun[{{$baoxianType[0]}}]" id="" value="{{number_format($bx['chesun'][0]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="chesun[{{$baoxianType[1]}}]" id="" value="{{number_format($bx['chesun'][1]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <!--机动车盗抢险-->
                                 <tr>
                                   <td>机动车盗抢险</td>
                                   <td class="tal"><span class="ml10">按保险公司规定执行</span></td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="daoqiang[{{$baoxianType[0]}}]" id="" value="{{number_format($bx['daoqiang'][0]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="daoqiang[{{$baoxianType[1]}}]" id="" value="{{number_format($bx['daoqiang'][1]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <!--第三者责任险-->
                                 <tr>
                                   <td rowspan="6">第三者责任险</td>
                                   <td class="tal"><span class="ml10">赔付额度5万元</span></td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="sanzhe[{{$baoxianType[0]}}][5]" id="" value="{{number_format($bx['sanzhe'][0][5]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="sanzhe[{{$baoxianType[1]}}][5]" id="" value="{{number_format($bx['sanzhe'][1][5]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">赔付额度10万元</span></td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="sanzhe[{{$baoxianType[0]}}][10]" id="" value="{{number_format($bx['sanzhe'][0][10]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="sanzhe[{{$baoxianType[1]}}][10]" id="" value="{{number_format($bx['sanzhe'][1][10]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">赔付额度15万元</span></td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="sanzhe[{{$baoxianType[0]}}][15]" id="" value="{{number_format($bx['sanzhe'][0][15]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="sanzhe[{{$baoxianType[1]}}][15]" id="" value="{{number_format($bx['sanzhe'][1][15]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">赔付额度20万元</span></td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="sanzhe[{{$baoxianType[0]}}][20]" id="" value="{{number_format($bx['sanzhe'][0][20]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="sanzhe[{{$baoxianType[1]}}][20]" id="" value="{{number_format($bx['sanzhe'][1][20]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">赔付额度50万元</span></td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="sanzhe[{{$baoxianType[0]}}][50]" id="" value="{{number_format($bx['sanzhe'][0][50]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="sanzhe[{{$baoxianType[1]}}][50]" id="" value="{{number_format($bx['sanzhe'][1][50]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">赔付额度100万元</span></td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="sanzhe[{{$baoxianType[0]}}][100]" id="" value="{{number_format($bx['sanzhe'][0][100]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="sanzhe[{{$baoxianType[1]}}][100]" id="" value="{{number_format($bx['sanzhe'][1][100]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <!--车上人员责任险驾驶人每次事故责任限额-->
                                 <tr>
                                   <td rowspan="10">车上人员责任险</td>
                                   <td class="tal"><span class="ml10">驾驶人每次事故责任限额1万元</span></td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="renyuan[{{$baoxianType[0]}}][1][ck]" id="" value="{{number_format($bx['renyuan'][0]['sj'][1]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="renyuan[{{$baoxianType[1]}}][1][ck]" id="" value="{{number_format($bx['renyuan'][1]['sj'][1]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">驾驶人每次事故责任限额2万元</span></td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="renyuan[{{$baoxianType[0]}}][2][ck]" id="" value="{{number_format($bx['renyuan'][0]['sj'][2]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="renyuan[{{$baoxianType[1]}}][2][ck]" id="" value="{{number_format($bx['renyuan'][1]['sj'][2]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">驾驶人每次事故责任限额3万元</span></td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="renyuan[{{$baoxianType[0]}}][3][ck]" id="" value="{{number_format($bx['renyuan'][0]['sj'][3]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="renyuan[{{$baoxianType[1]}}][3][ck]" id="" value="{{number_format($bx['renyuan'][1]['sj'][3]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">驾驶人每次事故责任限额4万元</span></td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="renyuan[{{$baoxianType[0]}}][4][ck]" id="" value="{{number_format($bx['renyuan'][0]['sj'][4]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="renyuan[{{$baoxianType[1]}}][4][ck]" id="" value="{{number_format($bx['renyuan'][1]['sj'][4]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">驾驶人每次事故责任限额5万元</span></td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="renyuan[{{$baoxianType[0]}}][5][ck]" id="" value="{{number_format($bx['renyuan'][0]['sj'][5]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="renyuan[{{$baoxianType[1]}}][5][ck]" id="" value="{{number_format($bx['renyuan'][1]['sj'][5]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <!--车上人员责任险乘客每次事故每人责任限额-->
                                 <tr>
                                   <td class="tal"><span class="ml10">乘客每次事故每人责任限额1万元</span></td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="renyuan[{{$baoxianType[0]}}][1][sj]" id="" value="{{number_format($bx['renyuan'][0]['ck'][1]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="renyuan[{{$baoxianType[1]}}][1][sj]" id="" value="{{number_format($bx['renyuan'][1]['ck'][1]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">乘客每次事故每人责任限额2万元</span></td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="renyuan[{{$baoxianType[0]}}][2][sj]" id="" value="{{number_format($bx['renyuan'][0]['ck'][2]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="renyuan[{{$baoxianType[1]}}][2][sj]" id="" value="{{number_format($bx['renyuan'][1]['ck'][2]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">乘客每次事故每人责任限额3万元</span></td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="renyuan[{{$baoxianType[0]}}][3][sj]" id="" value="{{number_format($bx['renyuan'][0]['ck'][3]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="renyuan[{{$baoxianType[1]}}][3][sj]" id="" value="{{number_format($bx['renyuan'][1]['ck'][3]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">乘客每次事故每人责任限额4万元</span></td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="renyuan[{{$baoxianType[0]}}][4][sj]" id="" value="{{number_format($bx['renyuan'][0]['ck'][4]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="renyuan[{{$baoxianType[1]}}][4][sj]" id="" value="{{number_format($bx['renyuan'][1]['ck'][4]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">乘客每次事故每人责任限额5万元</span></td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="renyuan[{{$baoxianType[0]}}][5][sj]" id="" value="{{number_format($bx['renyuan'][0]['ck'][5]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="renyuan[{{$baoxianType[1]}}][5][sj]" id="" value="{{number_format($bx['renyuan'][1]['ck'][5]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <!--玻璃单独破碎险-->
                                 <tr>
                                   <td rowspan="11"><b>附<br><br><br><br>加<br><br><br><br>险</b></td>
                                   <td rowspan="2">玻璃单独破碎险</td>
                                   <td class="tal"><span class="ml10">进口玻璃</span></td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="boli[{{$baoxianType[0]}}][jk]" id="" value="{{number_format($bx['boli'][0]['jk']['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="boli[{{$baoxianType[1]}}][jk]" id="" value="{{number_format($bx['boli'][1]['jk']['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">国产玻璃</span></td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="boli[{{$baoxianType[0]}}][gc]" id="" value="{{number_format($bx['boli'][0]['gc']['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="boli[{{$baoxianType[1]}}][gc]" id="" value="{{number_format($bx['boli'][1]['gc']['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                 </tr> 
                                 <!--车身划痕损失险-->
                                 <tr>
                                   <td rowspan="4">车身划痕损失险</td>
                                   <td class="tal"><span class="ml10">赔付额度0.2万元</span></td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="huahen[{{$baoxianType[0]}}][2000]" id="" value="{{number_format($bx['huahen'][0][2000]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="huahen[{{$baoxianType[1]}}][2000]" id="" value="{{number_format($bx['huahen'][1][2000]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">赔付额度0.5万元</span></td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="huahen[{{$baoxianType[0]}}][5000]" id="" value="{{number_format($bx['huahen'][0][5000]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="huahen[{{$baoxianType[1]}}][5000]" id="" value="{{number_format($bx['huahen'][1][5000]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">赔付额度1万元</span></td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="huahen[{{$baoxianType[0]}}][10000]" id="" value="{{number_format($bx['huahen'][0][10000]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="huahen[{{$baoxianType[1]}}][10000]" id="" value="{{number_format($bx['huahen'][1][10000]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">赔付额度2万元</span></td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="huahen[{{$baoxianType[0]}}][20000]" id="" value="{{number_format($bx['huahen'][0][20000]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="huahen[{{$baoxianType[1]}}][20000]" id="" value="{{number_format($bx['huahen'][1][20000]['price'],2)}}">
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <!--不计免赔特约险-->
                                 <tr>
                                   <td rowspan="5">不计免赔特约险</td>
                                   <td class="tal"><span class="ml10">机动车损失险不计免赔</span></td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="bjmp[chesun][{{$baoxianType[0]}}]" id="" value="{{number_format($bx['chesun'][0]['bjm_price'],2)}}">
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="bjmp[chesun][{{$baoxianType[1]}}]" id="" value="{{number_format($bx['chesun'][1]['bjm_price'],2)}}">
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10">机动车盗抢险不计免赔</span></td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="bjmp[daoqiang][{{$baoxianType[0]}}]" id="" value="{{number_format($bx['daoqiang'][0]['bjm_price'],2)}}">
                                      <span>元</span>
                                   </td>
                                   <td>
                                      <input class="card-input card-txt-price abs" type="text" name="bjmp[daoqiang][{{$baoxianType[1]}}]" id="" value="{{number_format($bx['daoqiang'][1]['bjm_price'],2)}}">
                                      <span>元</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10 block">第三者责任险不计免赔，按赔付额度保费X费率</span></td>
                                   <td>
                                      <span>费率</span>      
                                      <input class="card-input card-txt-price card-txt-price-min abs" type="text" name="bjmp[sanzhe][{{$baoxianType[0]}}]" id="" value="{{$bx['sanzhe'][0][5]['bjm_percent']}}">
                                      <span>%计保费</span>
                                   </td>
                                   <td>
                                      <span>费率</span>      
                                      <input class="card-input card-txt-price card-txt-price-min abs" type="text" name="bjmp[sanzhe][{{$baoxianType[1]}}]" id="" value="{{$bx['sanzhe'][1][5]['bjm_percent']}}">
                                      <span>%计保费</span>
                                   </td>
                                 </tr>
                                 <tr>
                                   <td class="tal"><span class="ml10 block">车上人员责任险不计免赔，按限额和人数保费X费率</span></td>
                                   <td>
                                      <span>费率</span>      
                                      <input class="card-input card-txt-price card-txt-price-min abs" type="text" name="bjmp[renyuan][{{$baoxianType[0]}}]" id="" value="{{$bx['renyuan'][0]['sj'][1]['bjm_percent']}}">
                                      <span>%计保费</span>
                                   </td>
                                   <td>
                                      <span>费率</span>      
                                      <input class="card-input card-txt-price card-txt-price-min abs" type="text" name="bjmp[renyuan][{{$baoxianType[1]}}]" id="" value="{{$bx['renyuan'][1]['sj'][1]['bjm_percent']}}">
                                      <span>%计保费</span>
                                   </td>
                                 </tr>
                                  <tr>
                                   <td class="tal"><span class="ml10 block">车身划痕损失险不计免赔，按赔付额度保费X费率  </span></td>
                                   <td>
                                      <span>费率</span>      
                                      <input class="card-input card-txt-price card-txt-price-min abs" type="text" name="bjmp[huahen][{{$baoxianType[0]}}]" id="" value="{{$bx['huahen'][0][2000]['bjm_percent']}}">
                                      <span>%计保费</span>
                                   </td>
                                   <td>
                                      <span>费率</span>      
                                      <input  class="card-input card-txt-price card-txt-price-min abs" type="text" name="bjmp[huahen][{{$baoxianType[1]}}]" id="" value="{{$bx['huahen'][1][2000]['bjm_percent']}}">
                                      <span>%计保费</span>
                                   </td>
                                 </tr> 

                               
                              </tbody>
                            </table>

                            
                            <div class="m-t-10"></div>
                            <div class="m-t-10"></div>
                            <p class="tac">                           		
                           		<a href="/dealer/baojia/edit/{{$baojia['bj_id']}}/3" class="btn btn-danger sure fs18 ml20">返回上一步</a>
                                <a href="javascript:;" class="btn btn-danger fs18 ml20 baojia-submit-button" data-step='4' data-type='1'>下一步</a>
                                <a href="javascript:;" class="btn btn-danger sure fs18 ml20 baojia-submit-button" data-step='4' data-type='2'>保存并退出</a>
                           	  	<a href="javascript:;" class="btn btn-danger sure fs18 ml20 reset-form">重置</a>
                            </p>
                            <div class="error-div tac" id="inpurerror"><label>*请重新输入纯数字格式，费率部分请输入0-30的整数，例如：22</label></div>



 


                            
                        </div>
                        
                    </form>
                    <div id="tip-error" class="popupbox">
                          <div class="popup-title">温馨提示</div>
                          <div class="popup-wrapper">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <div class="fs14 pd tac error auto">
                                     <center>
                                       <span class="tip-tag"></span>
                                       <span class="tip-text">添加失败!</span>
                                     </center>
                                  </div>
                              </div>
                              <div class="popup-control">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 sure skillsure">确认</a>
                                  <div class="clear"></div>
                              </div>
                          </div>
                      </div>
                    <div class="m-t-200"></div> 



                
                  
                  

                  

                  
                   
                </div>                
                                
@endsection                
 
@section('js')
<script type="text/template" id="good-tpl">
        <tr class="def" data-ref-id="{id}">
          <td class="tac none">
              <input type="checkbox" name="" id="" value="">
          </td>
          <td class="tac"><span>{0}</span></td>
          <td class="tac"><span>{1}</span></td>
          <td class="tac"><span>{2}</span></td>
          <td class="tac"><span>{3}</span></td>
          <td class="tac count"><span>{4}</span></td>
          <td class="tac"><span>{5}</span></td>
        </tr>
    </script>	
<script type="text/javascript">
seajs.use(["module/custom/custom_admin",
           "module/custom/custom.admin.common.jquery",
           "module/custom/custom.admin.insurance.jquery", 
           "module/common/common", "bt"],function(a,b,c){
    
});

</script>
@endsection