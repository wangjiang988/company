@extends('_layout.base_usercenter')
@section('css')

@endsection

@section('nav')
@endsection
@section('content')
<div class="box">
                         
                        <h2 class="title psr">
                            <span>我提交的特殊文件</span>
                        </h2>
                        <div class="content-wapper">

                            @if($excute =='view')
                            <br><br>
                            <div class="box ml20 box-view ">
                                <div class="form-txt">
                                    <label class="">您心仪座驾生产国别：</label><span><?=$file['guobie']==0?"国产":"进口"?></span>
                                </div>
                                <div class="form-txt">
                                    <label class="">文件名称：</label><span>{{$file['title']}}</span>
                                </div>
                                <div class="form-txt">
                                    <label class="">上牌地区：</label><span>{{$file['area']}}</span>
                                </div>
                                <div class="form-txt">
                                    <label class="">车辆用途：</label><span>{{$file['use']}}</span>
                                </div>
                                <div class="form-txt">
                                    <label class="">上牌（注册登记）车主身份类别：</label><span>{{$file['shenfen']}}</span>
                                </div>
                                @if($file['status'] ==0 && $file['reason']!='')
                                <div class="form-txt audit-error">
                                    <label class="">审核失败原因：</label><span>经了解，当地上牌不需要此文件</span>
                                </div>
                                @endif
                                <!--//
                                <p class="tac mt20">
                                    <a href="javascript:;" class="btn btn-danger fs16 w150" data-title="已提交，正在审核" data-def="确定" id="btnFileView">确定</a>
                                </p>
                                -->
                                <br><br>
                                <p class="tac haserror">
                                    有问题，<a href="#" class="juhuang">联系我们</a>
                                </p>
                            </div>
                            @elseif($excute == 'add' || $excute == 'edit')
                            <form action=""  method="post" name="kpform">
                                
                                <div class="form-txt">
                                    <label class="">您心仪座驾生产国别：</label>
                                    <label class=""><input type="radio" name="guobie" class="radio" value='0' <?php if($file['guobie']==0){echo 'checked';}?>><span>国产</span></label>
                                    <label class="ml20"><input type="radio" name="guobie" class="radio" value='1' <?php if($file['guobie']==1){echo 'checked';}?>><span>进口</span></label>
                                    <p class="inputerror hide ml100" >请选择生产国别</p>
                                </div>
                                <div class="mt10"></div>
                                <div class="form-txt">
                                    <label class="txt-length">文件名称：</label>
                                    <div class="edit-wp edit-long ml5">
                                        <input type="text" name="filename" value="{{$file['title']}}">
                                        <span class="edit"></span>
                                    </div>
                                    <p class="clear mt10 inputerror hide ml100">请输入文件名称</p>
                                </div>

                                <div class="mt10"></div>
                                <div class="form-txt">
                                    <label class="txt-length">上牌地区：</label>
                                    <div class="btn-group m-r pdi-drop pdi-drop-warp">
                                        <div ms-on-click="initProvince" class="btn btn-sm btn-default dropdown-toggle area-drop-btn">
                                            <span class="dropdown-label"><span>{{$file['area']}}&nbsp;</span></span>
                                            <span class="caret"></span>
                                        </div>
                                        <div class="dropdown-menu dropdown-select area-tab-div">
                    
                                            <input type="hidden" name="province" value="{{$memberArea['province']}}"/>
                                            <input type="hidden" name="city" value="{{$memberArea['city']}}"/>
                                            <p class="area-tab"></p>
                                            <dl class="dl">
                                                @foreach($topArea as $k=>$v)
			                                        <dd ms-on-click="selectProvince({{$v['area_id']}})">{{$v['area_name']}}</dd>
			                                    @endforeach
                                              <div class="clear"></div>
                                            </dl>
                                            <dl class="dl" style="display: none;">
                                              <dd ms-repeat-city="citylist" ms-on-click="selectCity"><!--city.name--></dd>
                                              <div class="clear"></div>
                                            </dl>
                                        </div>
                                    </div> 

                                    <p class="inputerror hide ml100" >请选择上牌地区</p>
                                </div>


                                <div class="mt10"></div>
                                <div class="form-txt">
                                    <label class="txt-length">车辆用途：</label>
                                    <div class="btn-group m-r pdi-drop pdi-drop-warp ib" >
                                          <div ms-on-click="initOrderTime" class="btn btn-sm btn-default dropdown-toggle area-drop-btn">
                                              <span class="dropdown-label"><div>{{$file['use']}}&nbsp;</div></span>
                                              <span class="caret"></span>
                                          </div>
                                          <div class="dropdown-menu dropdown-select area-tab-div " style="display: none;">
                                              <p class="area-tab"></p>
                                              <dl class="dl fp" style="display: none;">
                                                  <dd class="block" ms-on-click="selectDumpList2('非营业企业客车')" ms-on-click-2="selectCarMethod(2)" class="select-order-time">非营业企业客车</dd>
                                                  <dd class="block" ms-on-click="selectDumpList2('非营业个人客车')" ms-on-click-2="selectCarMethod(1)" class="select-order-time">非营业个人客车</dd>
                                                  <div class="clear"></div>
                                              </dl>
                                          </div>
                                          <input type="hidden" name="caryongtu" value="{{$file['use']}}">
                                    </div>
                                    <p class="inputerror hide ml100" >请选择车辆用途</p>
                                </div>
                                


                                <div class="box  box-select <?php if($file['use']!='非营业个人客车'){echo 'hide';}?>"> 
                                    <div class="mt10"></div>
                                    <div class="form-txt">
                                        <label class="">上牌（注册登记）车主身份类别：</label>
                                    </div> 
                                    <div class="form-txt">
                                        <label class=""><input type="checkbox" ms-on-click="selectIdendityCate(0)" data-index="0" name="typetype" class="radio fl" value='local' <?php if($file['shenfen']=='上牌地本市户籍居民'){echo 'checked';}?>><span class="fl">上牌地本市户籍居民</span><span class="clear"></span></label>
                                    </div> 
                                    <div class="form-txt ifl psr wp100">
                                        <p class="m0"><label class=""><input ms-on-click="selectIdendityCate(1)" data-index="1" type="checkbox" name="typetype" class="radio fl" value='other' <?php if(in_array($file['shenfen'],array('国内其他非限牌城市户籍居民','国内其他限牌城市户籍居民','中国军人','非中国大陆人士'))){echo 'checked';}?>><span class="fl">其他</span><span class="clear"></span></label></p>
                                        <p class="ml20-2">
                                            <label class=""><input ms-on-click="selectIdendityCateOther(0)" type="radio" name="othertype" class="radio fl" value='国内其他非限牌城市户籍居民' <?php if($file['shenfen']=='国内其他非限牌城市户籍居民'){echo 'checked';}?>><span class="fl">国内其他非限牌城市户籍居民</span><span class="clear"></span></label>
                                        </p>
                                        <p class="ml20-2">
                                            <label class=""><input ms-on-click="selectIdendityCateOther(1)" id="xianpaicity" type="radio" name="othertype" class="radio fl" value='国内其他限牌城市户籍居民' <?php if($file['shenfen']=='国内其他限牌城市户籍居民'){echo 'checked';}?>><span class="fl">国内其他限牌城市户籍居民</span><span class="clear"></span></label>
                                        </p>
                                        <p class="ml20-2">
                                            <label class=""><input ms-on-click="selectIdendityCateOther(0)" type="radio" name="othertype" class="radio fl" value='中国军人' <?php if($file['shenfen']=='中国军人'){echo 'checked';}?>><span class="fl" >中国军人</span><span class="clear"></span></label>
                                        </p>
                                        <p class="ml20-2">
                                            <label class=""><input ms-on-click="selectIdendityCateOther(2)" id="notchina" type="radio" name="othertype" class="radio fl" value='非中国大陆人士' <?php if($file['shenfen']=='非中国大陆人士'){echo 'checked';}?>><span class="fl">非中国大陆人士</span><span class="clear"></span></label>
                                        </p>

                                        <dl class="city-select" data-for="xianpaicity">
                                            <dd ms-on-click="SelectCityType"><span>北京</span><i></i></dd>
                                            <dd ms-on-click="SelectCityType"><span>上海</span><i></i></dd>
                                            <dd ms-on-click="SelectCityType"><span>广州</span><i></i></dd>
                                            <dd ms-on-click="SelectCityType"><span>天津</span><i></i></dd>
                                            <dd ms-on-click="SelectCityType"><span>杭州</span><i></i></dd>
                                            <dd ms-on-click="SelectCityType"><span>贵阳</span><i></i></dd>
                                            <dd ms-on-click="SelectCityType"><span>深圳</span><i></i></dd>
                                            <dd class="hide"><span class="inputerror ">请选择</span><i></i></dd>
                                            <input type="hidden" name="hujicity">
                                        </dl>
                                        <dl class="city-select address-area" data-for="notchina">
                                            <dd ms-on-click="SelectCityType"><span>外籍人士</span><i></i></dd>
                                            <dd ms-on-click="SelectCityType"><span>台胞</span><i></i></dd>
                                            <dd ms-on-click="SelectCityType"><span>港澳人士</span><i></i></dd>
                                            <dd ms-on-click="SelectCityType"><span>持绿卡华侨</span><i></i></dd>
                                            <dd class="hide"><span class="inputerror ">请选择</span><i></i></dd>
                                            <input type="hidden" name="addressarea">
                                        </dl>
                                    </div>
                                    <div class="clear"></div>
                                </div>

                                <div class="box <?php if($file['use']!='非营业企业客车'){echo 'hide';}?>  box-select">
                                    <div class="mt10"></div>
                                    <div class="form-txt">
                                        <label class="">上牌（注册登记）车主身份类别：</label>
                                    </div> 
                                    <div class="form-txt">
                                        <label class=""><input type="radio" name="typeqiye" class="radio fl" value='上牌地本市注册企业（增值税一般纳税人）' <?php if($file['shenfen']=='上牌地本市注册企业（增值税一般纳税人）'){echo 'checked';}?>><span class="fl">上牌地本市注册企业（增值税一般纳税人）</span><span class="clear"></span></label>
                                    </div> 
                                    <div class="form-txt">
                                        <label class=""><input type="radio" name="typeqiye" class="radio fl" value='上牌地本市注册企业（小规模纳税人）' <?php if($file['shenfen']=='上牌地本市注册企业（小规模纳税人）'){echo 'checked';}?>><span class="fl">上牌地本市注册企业（小规模纳税人）</span><span class="clear"></span></label>
                                    </div> 
                                </div>

                                <p class="inputerror hide ml20" id="identityCate" >请选择身份类别</p>



                                <p class="tac mt20">
                                    <a href="javascript:;" ms-on-click="SubmiteFile" class="btn btn-danger fs16 w150">提交</a>
                                </p> 
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
                         		<input type="hidden" name="act_form" value='{{$excute}}'>
                         		@if($excute == 'edit')
                         		<input type="hidden" name="id" value="{{$file['id']}}">
                         		@endif
                            </form>  
                                                       
                            @endif
                            
                            
							

                            
                         </div>  
                    </div>
@endsection           

@section('js')
	 <script type="text/javascript">
        seajs.use(["module/user/user", "module/common/common", "bt"]);
	</script>
@endsection