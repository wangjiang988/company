<!-- 代理商左侧边导航栏 -->
<div class="portlet-body">
    <div class="panel-group accordion" id="accordion1">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle icon-user" data-toggle="collapse" data-parent="#accordion1" href="#collapse_1"> 我的信息 </a>
                </h4>
                <i class="fa fa-sort-up"></i>
            </div>
            <div id="collapse_1" class="panel-collapse">
                <div class="panel-body">
                    <a href="/dealer/member_info" <?php if($flag=='memberInfo'){echo 'class="menu-select"';}?>>基本信息</a>
                    <a href="/dealer/modify_password" <?php if($flag=='modifyPassword'){echo 'class="menu-select"';}?>>修改密码</a>
                </div>
            </div>
        </div>
        <div class="panel panel-default ">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle icon-set" data-toggle="collapse" data-parent="#accordion1" href="#collapse_2"> 常用管理  </a>
                </h4>
                <i class="fa fa-sort-up"></i>
            </div>
            @if(isset($daili_dealer_id))
                <div id="collapse_2" class=" panel-collapse
                                <?php
                                    if($flag=='editDealer' || $flag=='carmodel'.$daili_dealer_id || $flag=='information'.$daili_dealer_id || $flag=='worktime'.$daili_dealer_id || $flag=='custorfile'.$daili_dealer_id || $flag=='surance'.$daili_dealer_id)
                                        {echo '';}
                                    else{ echo 'collapse';
                                    }?>   ">
                    @else
                        <div id="collapse_2" class="panel-collapse
                                <?php if($flag=='editDealer' || $flag=='carmodel' || $flag=='information' || $flag=='worktime' || $flag=='custorfile' || $flag=='surance'){echo 'in';}else{ }?>">
                            @endif
                            <div class="panel-body" >
                                @foreach ($view['common_info'] as $comm)
                                    <a style="background: url({{'http://upload.hwache.cn/'.$comm['detail_img']}}) 15px 50%  no-repeat;background-size:27px" data-toggle="collapse" data-parent="#collapse_2" href="#collapse_x{{$comm['id']}}"
                                       @if(isset($daili_dealer_id) && $comm['id'] == $daili_dealer_id)
                                       class="sort collapse panel-left psr"
                                       @else class="sort psr collapsed  panel-left"
                                            @endif >{{$comm['d_shortname']}}<i class="fa fa-sort-down"></i></a>
                                    <div id="collapse_x{{$comm['id']}}"
                                         @if(isset($daili_dealer_id) && $comm['id'] == $daili_dealer_id)
                                         class="panel-collapse  collapse in"
                                         @else
                                         class="panel-collapse  collapse"
                                            @endif>

                                        @if($comm['dl_status'] == 4 )
                                            <a  href="{{route('dealer.editdealer',['type'=>'edit','id'=>$comm['id']])}}" <?php if($flag=='information'.$comm['id']){echo 'class="review-return"';}?>>审核退回</a>
                                        @else
                                            <a href="{{route('dealer.editdealer',['type'=>'edit','id'=>$comm['id']])}}" <?php if($flag=='information'.$comm['id']){echo 'class="menu-select"';}?>>基本信息</a>
                                        @endif
                                        @if($comm['dl_status'] == 2)
                                            <a href="/dealer/carmodel/{{$comm['d_id']}}" <?php if($flag=='carmodel'.$comm['id']){echo 'class="menu-select"';}?>>常用车型</a>
                                            <a href="{{route('dealer.custorfile',[$comm['d_id']])}}" <?php if($flag=='custorfile'.$comm['id']){echo 'class="menu-select"';} ?> >客户文件</a>
                                            <a href="{{route('dealer.worktime',[$comm['d_id']])}}" <?php if($flag == 'worktime'.$comm['id']){echo 'class="menu-select"';} ?> >工作时段</a>
                                        @endif
                                    </div>

                                @endforeach

                                <a href="/dealer/editdealer/add/0" <?php if($flag=='editDealer'){echo 'class="menu-select"';}?>>增加经销商</a>
                            </div>
                        </div>
                </div>
                <div class="panel panel-default ">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle icon-price" data-toggle="collapse" data-parent="#accordion1" href="#collapse_3"> 报价管理  </a>
                        </h4>
                        <i class="fa fa-sort-up"></i>
                    </div>
                    <div id="collapse_3" class="panel-collapse">
                        <div class="panel-body" >
                            <a href="/dealer/baojia/edit/0/1" class="<?php if($flag=='baojia-new'){echo 'menu-select';}?>">开始新建</a>
                            <a href="/dealer/baojialist/unfinished" class="<?php if($flag=='baojia-unfinished'){echo 'menu-select';}?>">新建未完({{$view['baojiaCount']['unfinished']}})</a>
                            <a href="{{route('dealer.baojialist','effective')}}" class="<?php if($flag=='baojia-to-be-effective'){echo 'menu-select';}?>">等待生效({{$view['baojiaCount']['effective']}})</a>
                            <a href="{{route('dealer.baojialist','online')}}" class="<?php if($flag=='baojia-online'){echo 'menu-select';}?>">正在报价({{$view['baojiaCount']['online']}})</a>
                            <a href="{{route('dealer.baojialist','suspensive')}}" class="<?php if($flag=='baojia-suspensive'){echo 'menu-select';}?>">暂时下架({{$view['baojiaCount']['suspensive']}})</a>
                            <a href="{{route('dealer.baojialist','useless')}}" class="<?php if($flag=='baojia-useless'){echo 'menu-select';}?>">失效报价({{$view['baojiaCount']['useless']}})</a>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle icon-order" data-toggle="collapse" data-parent="#accordion1" href="#collapse_4"> 订单管理  </a>
                        </h4>
                        <i class="fa fa-sort-up"></i>
                    </div>
                    <div id="collapse_4" class="panel-collapse">
                        <div class="panel-body" >
                            <a href="{{route('dealer.orderlist',['type'=>'actives'])}}" class="<?php if($flag=='order_list_actives'){echo 'menu-select';}?>">执行中({{$view['orders'][0]->actives_sum}})</a>
                            <a href="{{route('dealer.orderlist',['type'=>'finishs'])}}" class="<?php if($flag=='order_list_finishs'){echo 'menu-select';}?>">已结束({{$view['orders'][0]->finishs_sum}})</a>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default last">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle icon-money" data-toggle="collapse" data-parent="#accordion1" href="#collapse_5"> 资金管理  </a>
                        </h4>
                        <i class="fa fa-sort-up"></i>
                    </div>
                    <div id="collapse_5" class="panel-collapse">
                        <div class="panel-body" >
                            <a href="{{ route('dealer.funds','') }}" <?php if($flag=='myPrices'){echo 'class="menu-select"';}?>>资金总览</a>
                            <a href="{{ route('dealer.funds','capitalpool') }}" <?php if($flag=='capitalPool'){echo 'class="menu-select"';}?>>资金池</a>
                            <a href="{{ route('dealer.funds','pay') }}" <?php if($flag=='hwachePay'){echo 'class="menu-select"';}?>>加信宝</a>
                            <a href="{{ route('dealer.funds','recharge') }}" <?php if($flag=='myRecharge'){echo 'class="menu-select"';}?>>充值</a>
                            <a href="{{ route('dealer.funds','withdrawal') }}" <?php if($flag=='myWithdrawal'){echo 'class="menu-select"';}?>>提现</a>
                            <a href="{{ route('dealer.funds','settlement') }}" <?php if($flag=='settlement'){echo 'class="menu-select"';}?>>结算</a>
                        </div>
                    </div>
                </div>
        </div>
    </div>
