@extends('_layout.base_usercenter')
@section('css')

@endsection

@section('nav')
@endsection
@section('content')


				@if(empty($invoice))
					<div class="box">
					<?php //$order['updated_at'] = date("Y-m-d H:i:s",time()-86400*100);?>
						@if((intval(strtotime($order['updated_at']))+86400*90) > time() || $order['overtime_invoice_status'] == 1)
                        <div class="content-wapper billing-wapper">
                            <ul class="order-sing-detail">
                                <li class="tal"><span>订单号：<?php if(isset($order_num)){echo $order_num;}?></span></li>
                                <li class="tac"><span>可开发票金额：￥{{$order_money}}</span></li>
                                <li class="tar"><a href="{{url('orderoverview')}}/{{$order_num}}" class="juhuang tdu">查看订单总详情</a></li>
                            </ul> 
                        </div> 
                        <h2 class="title psr">
                            <span>申请开票</span>
                        </h2>
                        <div class="content-wapper">
                            <form action="" method="post" name="billingform">
                                <p class="form-txt">
                                    <label class="txt-length">申请开票金额：</label>
                                    <input type="text" value="{{$order_money}}" name="price" readonly="" disabled="">
                                </p>
                                <div class="form-txt">
                                    <label class="txt-length">申请开票抬头：</label>
                                    <div class="btn-group m-r pdi-drop pdi-drop-warp ib" >
                                          <div ms-on-click="initOrderTime" class="btn btn-sm btn-default dropdown-toggle area-drop-btn">
                                              <span class="dropdown-label"><span>&nbsp;</span></span>
                                              <span class="caret"></span>
                                          </div>
                                          <div class="dropdown-menu dropdown-select area-tab-div " style="display: none;">
                                              <input type="hidden" name="OrderTime" value="">
                                              <p class="area-tab"></p>
                                              <dl class="dl fp" style="display: none;">
                                              @foreach($inv_titleArray as $k=>$v)
                                              	@if($v == '其它')
                                                  	<dd class="block" ms-on-click-1="selectOrderTime('{{$v}}')" ms-on-click-2="showTaitou">{{$v}}</dd>
                                              	@else
                                              		<dd class="block" ms-on-click-1="selectOrderTime('{{$v}}')" ms-on-click-2="hideTaitou">{{$v}}</dd>
                                              	@endif
                                              @endforeach
                                                <div class="clear"></div>
                                              </dl>
                                          </div>
                                          <input type="hidden" name="inv_title">
                                          <div class="edit-wp edit-long hide">
                                             <input type="text" name="taitou">
                                             <span class="edit"></span>
                                          </div>
                                    </div>
                                    <p class="inputerror hide ml100" >请选择开票抬头</p>
                                    <p class="inputerror hide ml100">请输入开票抬头</p>
                                </div>
                                <div class="mt10"></div>
                                <p class="form-txt">
                                    <label class="txt-length">发票类型：</label>
                                    <span ms-on-click="selectInvoice(1)" class="fptype fptype-cur">
                                        增值税普通发票
                                        <i></i>
                                    </span>
                                   @if($yongtu == '非营业企业客车')
                                    <span ms-on-click="selectInvoice(2)" class="fptype">
                                        增值税专用发票
                                        <i></i>
                                    </span>
                                    @endif
                                    <span class="inputerror hide">请选择发票类型</span>
                                    <input type="hidden" name="invoice_type" value="1">
                                </p>
                                <div class="form-txt">
                                    <label class="txt-length">邮寄地址：</label>
                                    <textarea name="address" id=""></textarea>
                                    <p class="inputerror hide ml100">请输入邮寄地址</p>
                                </div>
                                <div class="form-txt">
                                    <label class="txt-length">收 件 人：</label>
                                    <input type="text" name="receiver">
                                    <p class="inputerror hide ml100">请输入收件人名称</p>
                                    <input type="hidden" name="nameIsValite" value="0">
                                </div>
                                <div class="mt10"></div>
                                <div class="form-txt">
                                    <label class="txt-length">电     话：</label>
                                    <input type="text" name="phone">
                                    <p class="inputerror hide ml100">请输入您的电话</p>
                                </div>
								<div class="add-wrapper">
                                    <h2 class="title psr">
                                        <span>补充开票资料信息</span>
                                    </h2>
                                    <div class="mt10"></div>
                                    <div class="form-txt">
                                        <label class="txt-length">社会信用代码：</label>
                                        <input type="text" name="addsn">
                                        <p class="inputerror hide ml100">请输入纳税人识别号</p>
                                    </div>
                                    <div class="mt10"></div>
                                    <div class="form-txt">
                                        <label class="txt-length">地 址：</label>
                                        <input type="text" name="addaddress">
                                        <p class="inputerror hide ml100">请输入地址</p>
                                    </div>
                                    <div class="mt10"></div>
                                    <div class="form-txt">
                                        <label class="txt-length">电 话：</label>
                                        <input type="text" name="addphone">
                                        <p class="inputerror hide ml100">请输入电话</p>
                                    </div>
                                    <div class="mt10"></div>
                                    <div class="form-txt">
                                        <label class="txt-length">开户行：</label>
                                        <input type="text" name="addbank">
                                        <p class="inputerror hide ml100">请输入开户行</p>
                                    </div>
                                    <div class="mt10"></div>
                                    <div class="form-txt">
                                        <label class="txt-length">账 号：</label>
                                        <input type="text" name="addaccount">
                                        <p class="inputerror hide ml100">请输入账号</p>
                                    </div>
                                </div>
                                <p class="tac">
                                    <a href="javascript:;" ms-on-click="billing" class="btn btn-danger fs16 w150">申请开票</a>
                                    <a href="javascript:;" class="btn btn-danger fs16 w150 ml50 sure">返回</a>
                                </p> 
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                         		<input type="hidden" name="act_form" value='sub'>
                            </form>  
                         </div>
                         <!-- //超过三个月时限不能开票 -->
                         @else
                         <h2 class="title psr">
                            <span>超时发票</span>
                        </h2>
                        <div class="content-wapper">
                        	<p class="form-txt">
                                <label class="txt-length">订单号：</label>
                                <span>{{$order_num}} &nbsp;&nbsp;
                                <a href="{{url('orderoverview')}}/{{$order_num}}" class="tdu juhuang" target="_blank">查看订单总详情</a>
                                </span>
                            </p>
                            <p class="form-txt">
                                <label class="txt-length">结算完成时间：</label>
                                <span>{{$order['updated_at']}}</span>
                            </p>
                            <p class="form-txt">
                                <label class="txt-length">原可开票金额：</label>
                                <span>￥{{$order_money}}</span>
                            </p>
                            <p class="form-txt">
                                <label class="txt-length">现可开票金额：</label>
                                <span>￥0</span>
                            </p>
                            <p class="mt10"></p>
                            <p class="form-txt">
                                <label class="txt-length">说明：</label>
                            	<span>已经超过三个月开票时限，不可开票</span>
                            </p>
                            
                         </div>
                         
                         
                         
                         @endif  
                    </div>
                    <div id="billing-tip" class="popupbox">
                        <div class="popup-title">温馨提示</div>
                        <div class="popup-wrapper">
                            <div class="popup-content">
                                <p class="fs18 pd ti tac">       
                                确定申请开票吗？
                                </p>
                            </div>
                            <div class="popup-control">
                                <a ms-on-click="submitBilling" href="javascript:;" class="btn btn-s-md btn-danger fs14 w100">确定</a>
                                <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                <div class="clear"></div>
                                <p class="fs14 tac mt10">*请仔细核对您填写的信息，保证信息的准确性</p>
                            </div>
                        </div>
                    </div>
                    
                    
					@elseif($invoice['invoice_status'] == 1)
					
					<!-- //退回充填  可编辑状态  start-->
					@if($invoice['invoice_re_edit']==0)
						<div class="content-wapper billing-wapper">
                            <ul class="order-sing-detail">
                                <li class="tal"><span>订单号：<?php if(isset($order_num)){echo $order_num;}?></span></li>
                                <li class="tac"><span>可开发票金额：￥{{$invoice['inv_money']}}</span></li>
                                <li class="tar"><a href="{{url('orderoverview')}}/{{$order_num}}" class="juhuang tdu">查看订单总详情</a></li>
                            </ul> 
                        </div> 
                        <h2 class="title psr">
                            <span>申请开票</span>
                        </h2>
                        <div class="content-wapper">
                            <form action="" method="post" name="billingform">
                                <p class="form-txt">
                                    <label class="txt-length">申请开票金额：</label>
                                    <input type="text" value="{{$invoice['inv_money']}}" name="price" readonly="" disabled="">
                                </p>
                                <div class="form-txt">
                                    <label class="txt-length">申请开票抬头：</label>
                                    <div class="btn-group m-r pdi-drop pdi-drop-warp ib" >
                                          <div ms-on-click="initOrderTime" class="btn btn-sm btn-default dropdown-toggle area-drop-btn">
                                              <span class="dropdown-label"><span>&nbsp;</span></span>
                                              <span class="caret"></span>
                                          </div>
                                          <div class="dropdown-menu dropdown-select area-tab-div " style="display: none;">
                                              <input type="hidden" name="OrderTime" value="">
                                              <p class="area-tab"></p>
                                              <dl class="dl fp" style="display: none;">
                                              @foreach($inv_titleArray as $k=>$v)
                                              	@if($v == '其它')
                                                  	<dd class="block" ms-on-click-1="selectOrderTime('{{$v}}')" ms-on-click-2="showTaitou">{{$v}}</dd>
                                              	@else
                                              		<dd class="block" ms-on-click-1="selectOrderTime('{{$v}}')" ms-on-click-2="hideTaitou">{{$v}}</dd>
                                              	@endif
                                              @endforeach
                                                <div class="clear"></div>
                                              </dl>
                                          </div>
                                          <input type="hidden" name="inv_title">
                                          <div class="edit-wp edit-long hide">
                                             <input type="text" name="taitou">
                                             <span class="edit"></span>
                                          </div>
                                    </div>
                                    <p class="inputerror hide ml100" >请选择开票抬头</p>
                                    <p class="inputerror hide ml100">请输入开票抬头</p>
                                </div>
                                <div class="mt10"></div>
                                <p class="form-txt">
                                    <label class="txt-length">发票类型：</label>
                                    <span ms-on-click="selectInvoice(1)" class="fptype fptype-cur">
                                        增值税普通发票
                                        <i></i>
                                    </span>
                                   @if($yongtu == '非营业企业客车')
                                    <span ms-on-click="selectInvoice(2)" class="fptype">
                                        增值税专用发票
                                        <i></i>
                                    </span>
                                    @endif
                                    <span class="inputerror hide">请选择发票类型</span>
                                    <input type="hidden" name="invoice_type" value="1">
                                </p>
                                <div class="form-txt">
                                    <label class="txt-length">邮寄地址：</label>
                                    <textarea name="address" id="" >{{$invoice['inv_goto_addr']}}</textarea>
                                    <p class="inputerror hide ml100">请输入邮寄地址</p>
                                </div>
                                <div class="form-txt">
                                    <label class="txt-length">收 件 人：</label>
                                    <input type="text" name="receiver" value="{{$invoice['inv_rec_name']}}">
                                    <p class="inputerror hide ml100">请输入收件人名称</p>
                                    <input type="hidden" name="nameIsValite" value="0">
                                </div>
                                <div class="mt10"></div>
                                <div class="form-txt">
                                    <label class="txt-length">电     话：</label>
                                    <input type="text" name="phone" value="{{$invoice['inv_rec_mobphone']}}">
                                    <p class="inputerror hide ml100">请输入您的电话</p>
                                </div>
								<div class="add-wrapper">
                                    <h2 class="title psr">
                                        <span>补充开票资料信息</span>
                                    </h2>
                                    <div class="mt10"></div>
                                    <div class="form-txt">
                                        <label class="txt-length">社会信用代码：</label>
                                        <input type="text" name="addsn" value="{{$invoice['inv_code']}}">
                                        <p class="inputerror hide ml100">请输入纳税人识别号</p>
                                    </div>
                                    <div class="mt10"></div>
                                    <div class="form-txt">
                                        <label class="txt-length">地 址：</label>
                                        <input type="text" name="addaddress" value="{{$invoice['inv_reg_addr']}}">
                                        <p class="inputerror hide ml100">请输入地址</p>
                                    </div>
                                    <div class="mt10"></div>
                                    <div class="form-txt">
                                        <label class="txt-length">电 话：</label>
                                        <input type="text" name="addphone" value="{{$invoice['inv_reg_phone']}}">
                                        <p class="inputerror hide ml100">请输入电话</p>
                                    </div>
                                    <div class="mt10"></div>
                                    <div class="form-txt">
                                        <label class="txt-length">开户行：</label>
                                        <input type="text" name="addbank" value="{{$invoice['inv_reg_bname']}}">
                                        <p class="inputerror hide ml100">请输入开户行</p>
                                    </div>
                                    <div class="mt10"></div>
                                    <div class="form-txt">
                                        <label class="txt-length">账 号：</label>
                                        <input type="text" name="addaccount" value="{{$invoice['inv_reg_baccount']}}">
                                        <p class="inputerror hide ml100">请输入账号</p>
                                    </div>
                                </div>
                                <p class="tac">
                                    <a href="javascript:;" ms-on-click="billing" class="btn btn-danger fs16 w150">申请开票</a>
                                    <a href="javascript:;" class="btn btn-danger fs16 w150 ml50 sure">返回</a>
                                </p> 
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                         		<input type="hidden" name="act_form" value='sub_invoice_re_edit'>
                         		<input type="hidden" name="inv_id" value='{{$invoice["inv_id"]}}'>
                            </form>  
                         </div>
						 <div id="billing-tip" class="popupbox">
                        <div class="popup-title">温馨提示</div>
                        <div class="popup-wrapper">
                            <div class="popup-content">
                                <p class="fs18 pd ti tac">       
                                确定申请开票吗？
                                </p>
                            </div>
                            <div class="popup-control">
                                <a ms-on-click="submitBilling" href="javascript:;" class="btn btn-s-md btn-danger fs14 w100">确定</a>
                                <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                <div class="clear"></div>
                                <p class="fs14 tac mt10">*请仔细核对您填写的信息，保证信息的准确性</p>
                            </div>
                        </div>
                    </div>
					<!-- //退回充填  可编辑状态 end -->
					@else					
                    <div class="box">
                        <div class="content-wapper billing-wapper">
                            <ul class="order-sing-detail">
                                <li class="tal"><span>订单号：{{$order_num}}</span></li>
                                <li class="tac"><span>可开发票金额：￥{{$invoice['inv_money']}}</span></li>
                                <li class="tar"><a href="#" class="juhuang tdu">查看订单总详情</a></li>
                            </ul> 
                        </div> 
                        <h2 class="title psr">
                            <span>申请开票</span>
                        </h2>
                        <div class="content-wapper">
                                <p class="form-txt">
                                    <label class="txt-length">申请开票金额：</label>
                                    <input type="text" value="￥{{$invoice['inv_money']}}" name="price" readonly="" disabled="">
                                </p>
                                <div class="form-txt">
                                    <label class="txt-length">申请开票抬头：</label>
                                    <span>{{$invoice['inv_title']}}</span>
                                </div>
                                <div class="mt10"></div>
                                <p class="form-txt">
                                    <label class="txt-length">发票类型：</label>
                                    <span class="fptype <?php if($invoice['inv_state']==1){echo 'fptype-cur';}?>">
                                        普通发票
                                        <i></i>
                                    </span>
                                    <span  class="fptype <?php if($invoice['inv_state']==2){echo 'fptype-cur';}?>">
                                        增值税专用发票
                                        <i></i>
                                    </span>
                                </p>
                                <div class="form-txt">
                                    <label class="txt-length">邮寄地址：</label>
                                    <span>{{$invoice['inv_goto_addr']}}</span>
                                </div>
                                <div class="form-txt">
                                    <label class="txt-length">收 件 人：</label>
                                    <span>{{$invoice['inv_rec_name']}}</span>
                                </div>
                                <div class="form-txt">
                                    <label class="txt-length">电     话：</label>
                                    <span>{{$invoice['inv_rec_mobphone']}}</span>
                                </div>
							@if($invoice['inv_state']==2)
                                    <div class="mt10"></div>
                                    <div class="form-txt">
                                        <label class="txt-length">社会信用代码：</label>
                                        <span>{{$invoice['inv_code']}}</span>
                                    </div>
                                    <div class="mt10"></div>
                                    <div class="form-txt">
                                        <label class="txt-length">地 址：</label>
                                        <span>{{$invoice['inv_reg_addr']}}</span>
                                    </div>
                                    <div class="mt10"></div>
                                    <div class="form-txt">
                                        <label class="txt-length">电 话：</label>
                                        <span>{{$invoice['inv_reg_phone']}}</span>
                                    </div>
                                    <div class="mt10"></div>
                                    <div class="form-txt">
                                        <label class="txt-length">开户行：</label>
                                        <span>{{$invoice['inv_reg_bname']}}</span>
                                    </div>
                                    <div class="mt10"></div>
                                    <div class="form-txt">
                                        <label class="txt-length">账 号：</label>
                                        <span>{{$invoice['inv_reg_baccount']}}</span>
                                    </div>
                                @endif   
                                <p class="tac">
                                    <a href="javascript:;" class="btn btn-danger fs16 w150 oksure">已申请</a>
                                    {{--<a href="javascript:;" class="btn btn-danger fs16 w150 ml50 sure">返回</a>--}}

                                </p> 
                                <p class="tac">
                                    <label>申请开票发起时间：</label><span>{{$invoice['inv_apply_date']}}</span>
                                </p>
                                <p>
                                    <label>预计开票时间：    </label>
                                    <span>{{$invoice['inv_estimate_date']}}</span>
                                </p>
                         </div>  
                    </div>
                    @endif
                    
					@elseif(($invoice['invoice_status'] == 2 || $invoice['invoice_status']==3 || $invoice['invoice_status']==4) && empty($_REQUEST['redo']))

                    <div class="box">
                        <div class="content-wapper billing-wapper">
                            <ul class="order-sing-detail">
                                <li class="tal"><span>订单号：{{$order_num}}</span></li>
                                <li class="tac"><span>可开发票金额：￥{{$invoice['inv_money']}}</span></li>
                                <li class="tar"><a href="#" class="juhuang tdu">查看订单总详情</a></li>
                            </ul> 
                        </div>  

                         <h2 class="title psr">
                            <span>实际开票</span>
                        </h2>
                        <div class="content-wapper">
                            <p class="form-txt">
                                <label class="txt-length">申请开票金额：</label>
                                <span>￥{{$invoice['inv_money']}}</span>
                            </p>
                            <p class="form-txt">
                                <label class="txt-length">申请开票抬头：</label>
                                <span>{{$invoice['inv_title']}}</span>
                            </p>
                            <p class="mt10"></p>
                            <p class="form-txt">
                                <label class="txt-length">发票类型：</label>
                                
                                <span>
                                <?php if($invoice['inv_state']==2){echo '增值税专用发票';}else{echo '增值税普通发票';}?>	
                                </span>
                            </p>
                            <p class="form-txt">
                                <label class="txt-length">发票编号：</label>
                                <span>{{$invoice['inv_number']}}</span>
                            </p>
	                            @if($invoice['invoice_status']==3 || $invoice['invoice_status']==4)
	                            <p class="form-txt">
	                                <label class="txt-length">寄送时间：</label>
	                                <span>{{$invoice['inv_deliver_date']}}</span>
	                            </p>
	                            <p class="form-txt">
	                                <label class="txt-length">快递名称：</label>
	                                <span>{{$invoice['inv_deliver']}}</span>
	                            </p>
	                            <p class="form-txt">
	                                <label class="txt-length">运 单 号： </label>
	                                <span>{{$invoice['inv_deliver_number']}}</span>
	                            </p>
	                            @endif

                            <p class="tac">
                            	@if($invoice['invoice_status']==4)
                            	<a  href="javascript:;" class="btn btn-danger fs16 w150 ">已确定收到</a>
                            	@else
                            	<a ms-on-click="confirmReceipt('{{$order_num}}','ok')" href="javascript:;" class="btn btn-danger fs16 w150 ">确定收到</a>
                            	@endif
                                
                                <a href="javascript:;" class="btn btn-danger fs16 w150 ml50 sure">返回</a>

                            </p> 
                            <p class="tac juhuang fs16">
                                有问题，<a href="#" class="tdu juhuang">联系我们</a>
                            </p>
                                
                        </div>  
                  <!--//发票填写重开信息  -->       
				@elseif($invoice['invoice_status']==4 && $_REQUEST['redo']=='y')
                        <h2 class="title psr">
                            <span>申请开票</span>
                        </h2>
                        <div class="content-wapper">
                            <form action="" method="post" name="rebillingform">

                                <div class="form-txt">
                                    <label class="txt-length">重开发票原因：</label>
                                    <div class="btn-group m-r pdi-drop pdi-drop-warp ib" >
                                          <div ms-on-click="initOrderTime" class="btn btn-sm btn-default dropdown-toggle area-drop-btn">
                                              <span class="dropdown-label"><span>发票内容开错</span></span>
                                              <span class="caret"></span>
                                          </div>
                                          <div class="dropdown-menu dropdown-select area-tab-div " style="display: none;">
                                              <p class="area-tab"></p>
                                              <dl class="dl fp" style="display: none;">
                                                  <dd class="block"  ms-on-click="selectDumpList('发票内容开错')">发票内容开错</dd>
                                                  <dd ms-on-click="selectDumpList('变更发票抬头')">变更发票抬头</dd>
                                                  <dd ms-on-click="selectDumpList('其他')">其他</dd>
                                                <div class="clear"></div>
                                              </dl>
                                              
                                          </div>
                                          <input type="hidden" name="reason" value="发票内容开错">
                                          
                                    </div>
                                    <p class="inputerror hide ml100" >请选择重开发票原因</p>
                                </div>
                                <div class="mt10"></div>
                                <div class="form-txt">
                                    <label class="">原发票寄回快递：</label>
                                    <input type="text" value="" name="kuaidi" >
                                    <p class="inputerror hide ml100" >请填写原发票寄回快递</p>
                                </div>
                                <div class="mt10"></div>
                                <div class="form-txt">
                                    <label class="">原发票寄回快递运单号：</label>
                                    <input type="text" value="" name="kuaidisn" >
                                    <p class="inputerror hide ml100" >请填写原发票寄回快递运单号</p>
                                </div>
                                <div class="mt10"></div>
                                <p class="form-txt">
                                    <label class="txt-length">申请开票金额：</label>
                                    <input type="text" value="￥2000.00" name="price" readonly="" disabled="">
                                </p>

                                <div class="form-txt">
                                    <label class="txt-length">申请开票抬头：</label>
                                    <div class="btn-group m-r pdi-drop pdi-drop-warp ib" >
                                          <div ms-on-click="initOrderTime" class="btn btn-sm btn-default dropdown-toggle area-drop-btn">
                                              <span class="dropdown-label"><span>&nbsp;</span></span>
                                              <span class="caret"></span>
                                          </div>
                                          <div class="dropdown-menu dropdown-select area-tab-div " style="display: none;">
                                              <input type="hidden" name="OrderTime" value="">
                                              <p class="area-tab"></p>
                                              <dl class="dl fp" style="display: none;">
                                              @foreach($inv_titleArray as $k=>$v)
                                                  <dd class="block" ms-on-click-1="selectOrderTime('{{$v}}')" ms-on-click-2="hideTaitou">{{$v}}</dd>
                                              @endforeach
                                                <div class="clear"></div>
                                              </dl>
                                          </div>
                                          <input type="hidden" name="inv_title">
                                          <div class="edit-wp edit-long hide">
                                             <input type="text" name="taitou">
                                             <span class="edit"></span>
                                          </div> 
                                    </div>
                                    <p class="inputerror hide ml100" >请选择开票抬头</p>
                                    <p class="inputerror hide ml100">请输入开票抬头</p>
                                </div>
                                <div class="mt10"></div>
                                <p class="form-txt">
                                    <label class="txt-length">发票类型：</label>
                                    <span ms-on-click="selectInvoice(1)" class="fptype fptype-cur">
                                        增值税普通发票
                                        <i></i>
                                    </span>
                                   @if($yongtu == '非营业企业客车')
                                    <span ms-on-click="selectInvoice(2)" class="fptype">
                                        增值税专用发票
                                        <i></i>
                                    </span>
                                    @endif
                                    <span class="inputerror hide">请选择发票类型</span>
                                    <input type="hidden" name="invoice_type" value="1">
                                </p>
                                <div class="form-txt">
                                    <label class="txt-length">邮寄地址：</label>
                                    <textarea name="address" id=""></textarea>
                                    <p class="inputerror hide ml100">请输入邮寄地址</p>
                                </div>
                                <div class="form-txt">
                                    <label class="txt-length">收 件 人：</label>
                                    <input type="text" name="receiver">
                                    <p class="inputerror hide ml100">请输入收件人名称</p>
                                    <input type="hidden" name="nameIsValite" value="0">
                                </div>
                                <div class="mt10"></div>
                                <div class="form-txt">
                                    <label class="txt-length">电     话：</label>
                                    <input type="text" name="phone">
                                    <p class="inputerror hide ml100">请输入您的电话</p>
                                </div>
								
								<div class="add-wrapper">
                                    <h2 class="title psr">
                                        <span>补充开票资料信息</span>
                                    </h2>
                                    <div class="mt10"></div>
                                    <div class="form-txt">
                                        <label class="txt-length">社会信用代码：</label>
                                        <input type="text" name="addsn">
                                        <p class="inputerror hide ml100">请输入纳税人识别号</p>
                                    </div>
                                    <div class="mt10"></div>
                                    <div class="form-txt">
                                        <label class="txt-length">地 址：</label>
                                        <input type="text" name="addaddress">
                                        <p class="inputerror hide ml100">请输入地址</p>
                                    </div>
                                    <div class="mt10"></div>
                                    <div class="form-txt">
                                        <label class="txt-length">电 话：</label>
                                        <input type="text" name="addphone">
                                        <p class="inputerror hide ml100">请输入电话</p>
                                    </div>
                                    <div class="mt10"></div>
                                    <div class="form-txt">
                                        <label class="txt-length">开户行：</label>
                                        <input type="text" name="addbank">
                                        <p class="inputerror hide ml100">请输入开户行</p>
                                    </div>
                                    <div class="mt10"></div>
                                    <div class="form-txt">
                                        <label class="txt-length">账 号：</label>
                                        <input type="text" name="addaccount">
                                        <p class="inputerror hide ml100">请输入账号</p>
                                    </div>
                                </div>
								<p class="tac">
                                    <a href="javascript:;" ms-on-click="rebilling" class="btn btn-danger fs16 w150">申请开票</a>
                                    <a href="javascript:;" class="btn btn-danger fs16 w150 ml50 sure">返回</a>
                                </p> 
                                {{--
                                <p class="tac">
                                    <a href="javascript:;" class="btn btn-danger fs16 w150 oksure">已申请重开</a>
                                    <a href="javascript:;" class="btn btn-danger fs16 w150 ml50 sure">返回</a>
                                </p> 
                                <p class="tac fs16">申请重开票发起时间：2015-12-07 10：10：17</p>
                                --}}
								
								<div class="mt10"></div>
                                <div class="form-txt">
                                    <label class="txt-length fl">温馨提示：</label>
                                    <span class="fl w600" >
                                        请保持发票完整，并随附纸条注明订单号、您的姓名、重开发票原因等，安全包装后寄往下方的收件地址，不支持任何到付，谢谢合作～
                                    </span>
                                    <div class="clear"></div>
                                </div>
                                <div class="mt10"></div>
                                <div class="form-txt">
                                    <label class="txt-length fl noweight">邮寄地址：</label>
                                    <span class="fl w600" >
                                        江苏省苏州市高新区竹园路209号苏州创业园2号楼2205室
                                    </span>
                                    <div class="clear"></div>
                                </div>
                                <div class="mt10"></div>
                                <div class="form-txt">
                                    <label class="txt-length fl noweight">公司名称：</label>
                                    <span class="fl w600" >
                                        苏州华车网络科技有限公司
                                    </span>
                                    <div class="clear"></div>
                                </div>
                                <div class="mt10"></div>
                                <div class="form-txt">
                                    <label class="txt-length fl noweight">收件人：</label>
                                    <span class="fl " >
                                        黄女士           
                                    </span>
                                    <label class="txt-length fl noweight">TEL:</label>
                                    <span class="fl " >
                                        18112552176
                                    </span>
                                    <label class="txt-length fl noweight">邮编：</label>
                                    <span class="fl " >
                                       215011
                                    </span>

                                    <div class="clear"></div>
                                </div>
								
                                
                            	<input type="hidden" name="_token" value="{{ csrf_token() }}">
                         		<input type="hidden" name="act_form" value='sub_redo'>
                            </form>  
                        </div> 
                      <div id="billing-retip" class="popupbox">
                        <div class="popup-title">温馨提示</div>
                        <div class="popup-wrapper">
                            <div class="popup-content">
                                <p class="fs18 pd ti tac">       
                                确定申请重新开票吗？
                                </p>
                            </div>
                            <div class="popup-control">
                                <a ms-on-click="submitreBilling" href="javascript:;" class="btn btn-s-md btn-danger fs14 w100">确定</a>
                                <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                <div class="clear"></div>
                                <p class="fs14 tac mt10">*请仔细核对您填写的信息，保证信息的准确性</p>
                            </div>
                        </div>
                    </div>
                        <!--//发票已经填写重开  -->
              @elseif($invoice['invoice_status']==5)
              	@if($re_invoice['invoice_re_edit'] ==0)
              		<h2 class="title psr">
                            <span>申请开票</span>
                        </h2>
                        <div class="content-wapper">
                            <form action="" method="post" name="rebillingform">

                                <div class="form-txt">
                                    <label class="txt-length">重开发票原因：</label>
                                    <div class="btn-group m-r pdi-drop pdi-drop-warp ib" >
                                          <div ms-on-click="initOrderTime" class="btn btn-sm btn-default dropdown-toggle area-drop-btn">
                                              <span class="dropdown-label"><span>发票内容开错</span></span>
                                              <span class="caret"></span>
                                          </div>
                                          <div class="dropdown-menu dropdown-select area-tab-div " style="display: none;">
                                              <p class="area-tab"></p>
                                              <dl class="dl fp" style="display: none;">
                                                  <dd class="block"  ms-on-click="selectDumpList('发票内容开错')">发票内容开错</dd>
                                                  <dd ms-on-click="selectDumpList('变更发票抬头')">变更发票抬头</dd>
                                                  <dd ms-on-click="selectDumpList('其他')">其他</dd>
                                                <div class="clear"></div>
                                              </dl>
                                              
                                          </div>
                                          <input type="hidden" name="reason" value="发票内容开错">
                                          
                                    </div>
                                    <p class="inputerror hide ml100" >请选择重开发票原因</p>
                                </div>
                                <div class="mt10"></div>
                                <div class="form-txt">
                                    <label class="">原发票寄回快递：</label>
                                    <input type="text" name="kuaidi" value="{{$re_invoice['return_deliver']}}">
                                    <p class="inputerror hide ml100" >请填写原发票寄回快递</p>
                                </div>
                                <div class="mt10"></div>
                                <div class="form-txt">
                                    <label class="">原发票寄回快递运单号：</label>
                                    <input type="text"  name="kuaidisn" value="{{$re_invoice['return_deliver_num']}}" >
                                    <p class="inputerror hide ml100" >请填写原发票寄回快递运单号</p>
                                </div>
                                <div class="mt10"></div>
                                <p class="form-txt">
                                    <label class="txt-length">申请开票金额：</label>
                                    <input type="text" value="{{$re_invoice['inv_money']}}" name="price" readonly="" disabled="">
                                </p>

                                <div class="form-txt">
                                    <label class="txt-length">申请开票抬头：</label>
                                    <div class="btn-group m-r pdi-drop pdi-drop-warp ib" >
                                          <div ms-on-click="initOrderTime" class="btn btn-sm btn-default dropdown-toggle area-drop-btn">
                                              <span class="dropdown-label"><span>&nbsp;</span></span>
                                              <span class="caret"></span>
                                          </div>
                                          <div class="dropdown-menu dropdown-select area-tab-div " style="display: none;">
                                              <input type="hidden" name="OrderTime" value="">
                                              <p class="area-tab"></p>
                                              <dl class="dl fp" style="display: none;">
                                              @foreach($inv_titleArray as $k=>$v)
                                                  <dd class="block" ms-on-click-1="selectOrderTime('{{$v}}')" ms-on-click-2="hideTaitou">{{$v}}</dd>
                                              @endforeach
                                                <div class="clear"></div>
                                              </dl>
                                          </div>
                                          <input type="hidden" name="inv_title">
                                          <div class="edit-wp edit-long hide">
                                             <input type="text" name="taitou">
                                             <span class="edit"></span>
                                          </div> 
                                    </div>
                                    <p class="inputerror hide ml100" >请选择开票抬头</p>
                                    <p class="inputerror hide ml100">请输入开票抬头</p>
                                </div>
                                <div class="mt10"></div>
                                <p class="form-txt">
                                    <label class="txt-length">发票类型：</label>
                                    <span ms-on-click="selectInvoice(1)" class="fptype fptype-cur">
                                        增值税普通发票
                                        <i></i>
                                    </span>
                                   @if($yongtu == '非营业企业客车')
                                    <span ms-on-click="selectInvoice(2)" class="fptype">
                                        增值税专用发票
                                        <i></i>
                                    </span>
                                    @endif
                                    <span class="inputerror hide">请选择发票类型</span>
                                    <input type="hidden" name="invoice_type" value="1">
                                </p>
                                <div class="form-txt">
                                    <label class="txt-length">邮寄地址：</label>
                                    <textarea name="address" id="">{{$re_invoice['inv_goto_addr']}}</textarea>
                                    <p class="inputerror hide ml100">请输入邮寄地址</p>
                                </div>
                                <div class="form-txt">
                                    <label class="txt-length">收 件 人：</label>
                                    <input type="text" name="receiver" value="{{$re_invoice['inv_rec_name']}}">
                                    <p class="inputerror hide ml100">请输入收件人名称</p>
                                    <input type="hidden" name="nameIsValite" value="0">
                                </div>
                                <div class="mt10"></div>
                                <div class="form-txt">
                                    <label class="txt-length">电     话：</label>
                                    <input type="text" name="phone" value="{{$re_invoice['inv_rec_mobphone']}}">
                                    <p class="inputerror hide ml100">请输入您的电话</p>
                                </div>
								
								<div class="add-wrapper">
                                    <h2 class="title psr">
                                        <span>补充开票资料信息</span>
                                    </h2>
                                    <div class="mt10"></div>
                                    <div class="form-txt">
                                        <label class="txt-length">社会信用代码：</label>
                                        <input type="text" name="addsn">
                                        <p class="inputerror hide ml100">请输入纳税人识别号</p>
                                    </div>
                                    <div class="mt10"></div>
                                    <div class="form-txt">
                                        <label class="txt-length">地 址：</label>
                                        <input type="text" name="addaddress">
                                        <p class="inputerror hide ml100">请输入地址</p>
                                    </div>
                                    <div class="mt10"></div>
                                    <div class="form-txt">
                                        <label class="txt-length">电 话：</label>
                                        <input type="text" name="addphone">
                                        <p class="inputerror hide ml100">请输入电话</p>
                                    </div>
                                    <div class="mt10"></div>
                                    <div class="form-txt">
                                        <label class="txt-length">开户行：</label>
                                        <input type="text" name="addbank">
                                        <p class="inputerror hide ml100">请输入开户行</p>
                                    </div>
                                    <div class="mt10"></div>
                                    <div class="form-txt">
                                        <label class="txt-length">账 号：</label>
                                        <input type="text" name="addaccount">
                                        <p class="inputerror hide ml100">请输入账号</p>
                                    </div>
                                </div>
								<p class="tac">
                                    <a href="javascript:;" ms-on-click="rebilling" class="btn btn-danger fs16 w150">申请开票</a>
                                    <a href="javascript:;" class="btn btn-danger fs16 w150 ml50 sure">返回</a>
                                </p> 
                                {{--
                                <p class="tac">
                                    <a href="javascript:;" class="btn btn-danger fs16 w150 oksure">已申请重开</a>
                                    <a href="javascript:;" class="btn btn-danger fs16 w150 ml50 sure">返回</a>
                                </p> 
                                <p class="tac fs16">申请重开票发起时间：2015-12-07 10：10：17</p>
                                --}}
								
								<div class="mt10"></div>
                                <div class="form-txt">
                                    <label class="txt-length fl">温馨提示：</label>
                                    <span class="fl w600" >
                                        请保持发票完整，并随附纸条注明订单号、您的姓名、重开发票原因等，安全包装后寄往下方的收件地址，不支持任何到付，谢谢合作～
                                    </span>
                                    <div class="clear"></div>
                                </div>
                                <div class="mt10"></div>
                                <div class="form-txt">
                                    <label class="txt-length fl noweight">邮寄地址：</label>
                                    <span class="fl w600" >
                                        江苏省苏州市高新区竹园路209号苏州创业园2号楼2205室
                                    </span>
                                    <div class="clear"></div>
                                </div>
                                <div class="mt10"></div>
                                <div class="form-txt">
                                    <label class="txt-length fl noweight">公司名称：</label>
                                    <span class="fl w600" >
                                        苏州华车网络科技有限公司
                                    </span>
                                    <div class="clear"></div>
                                </div>
                                <div class="mt10"></div>
                                <div class="form-txt">
                                    <label class="txt-length fl noweight">收件人：</label>
                                    <span class="fl " >
                                        黄女士           
                                    </span>
                                    <label class="txt-length fl noweight">TEL:</label>
                                    <span class="fl " >
                                        18112552176
                                    </span>
                                    <label class="txt-length fl noweight">邮编：</label>
                                    <span class="fl " >
                                       215011
                                    </span>

                                    <div class="clear"></div>
                                </div>
								
                                
                            	<input type="hidden" name="_token" value="{{ csrf_token() }}">
                         		<input type="hidden" name="act_form" value='sub_redo_re_edit'>
                            </form>  
                        </div> 
                      <div id="billing-retip" class="popupbox">
                        <div class="popup-title">温馨提示</div>
                        <div class="popup-wrapper">
                            <div class="popup-content">
                                <p class="fs18 pd ti tac">       
                                确定申请重新开票吗？
                                </p>
                            </div>
                            <div class="popup-control">
                                <a ms-on-click="submitreBilling" href="javascript:;" class="btn btn-s-md btn-danger fs14 w100">确定</a>
                                <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                <div class="clear"></div>
                                <p class="fs14 tac mt10">*请仔细核对您填写的信息，保证信息的准确性</p>
                            </div>
                        </div>
                    </div>
              	@else
                        <h2 class="title psr">
                            <span>重开信息</span>
                        </h2>
                        <div class="content-wapper">
                            <p class="form-txt">
                                <label class="">退回发票收到时间：</label>
                                <span>{{$re_invoice['return_rec_date'] or '发票尚未收到'}}</span>
                            </p>
                            <p class="form-txt">
                                <label class="">重开发票类型：</label>
                                <span>
                                	
                                <?php if($re_invoice['inv_state']==2){echo '增值税专用发票';}else{echo '增值税普通发票';}?>	
                                </span>
                            </p>
                            <p class="form-txt">
                                <label class="">重开发票抬头：</label>
                                <span>{{$re_invoice['inv_title']}}</span>
                            </p>
                            <p class="form-txt">
                                <label class="">重开发票收件人：</label>
                                <span>{{$re_invoice['inv_rec_name']}}</span>
                            </p>
                            <p class="form-txt">
                                <label class="">重开发票收件人联系号码：</label>
                                <span>{{$re_invoice['inv_rec_mobphone']}}</span>
                            </p>
                            <p class="form-txt">
                                <label class="">重开发票收件人地址：</label>
                                <span>{{$re_invoice['inv_goto_addr']}}</span>
                            </p>
                            <p class="form-txt">
                                <label class="">重开发票编号：</label>
                                <span>{{$re_invoice['inv_number'] or '发票尚未开出'}}</span>
                            </p>
                            <p class="form-txt">
                                <label class="">重开发票寄送时间：</label>
                                <span>{{$re_invoice['inv_deliver_date'] or '发票尚未开出'}}</span>
                            </p>
                            <p class="form-txt">
                                <label class="">快递名称：</label>
                                <span>{{$re_invoice['inv_deliver'] or '发票尚未寄出'}}</span>
                            </p>
                            <p class="form-txt">
                                <label class="">运 单 号：</label>
                                <span>{{$re_invoice['inv_deliver_number'] or '发票尚未寄出'}}</span>
                            </p>
                            <p class="tac">
                            	<!--//原始发票显示 已申请重开  新发票显示已经收到  -->
                            	@if($re_invoice['invoice_status']==4)
                            	<a href="javascript:;"  class="btn btn-danger fs16 w150">已经收到</a>
                            	@elseif($re_invoice['invoice_status']==3)
                                <a href="javascript:;" ms-on-click="confirmReceipt('{{$order_num}}','reok')" class="btn btn-danger fs16 w150">确定收到</a>
                                @endif
                                {{--<a href="javascript:;" class="btn btn-danger fs16 w150 ml50 sure">返回</a>--}}
                            </p> 
                            <p class="tac fs16 juhuang">
                                有问题，<a href="#" class="juhuang tdu">联系我们</a>
                            </p>
                        </div>

                    </div>
                	@endif    
                @endif
@endsection                
@section('js')
	 <script type="text/javascript">
        seajs.use(["module/user/user", "module/common/common", "bt"]);
	</script>
@endsection    