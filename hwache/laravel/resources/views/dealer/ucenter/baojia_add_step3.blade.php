@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
                
                


                    <div class="custom-offer-step-wrapper">
                        <ul>
                          <li>车型价格</li>
                          <li class="prev">车况说明</li>
                          <li class="cur">选装精品</li>
                          <li>首年保险</li>
                          <li>收费标准</li>
                          <li class="last">其他事项</li>
                          <div class="clear"></div>
                        </ul>
                    </div> 
                    
                    
                    <form action="/dealer/baojia/edit/{{$baojia['bj_id']}}/3" name="baojia-submit-form" method="post">
                    	<input type='hidden' name='_token' value="{{csrf_token()}}">
                        <div class="content-wapper ">
                           
                            <p><b>选装精品</b></p>
                            <p>
                             <span>原厂选装精品折扣率：</span>     
                             <input value="100" placeholder="" type="text" name="bj_xzj_zhekou" class="form-control custom-control custom-control-min tac abs">   
                             <span>%</span>
                             <span class="error-div red">请输入0-100的数字</span>
                            </p>
                            @if($baojia['bj_is_xianche']==0)
                            <p><b>选装精品(出厂前装)</b></p>
                            
                            <table class="tbl tbl-blue wp100" id="tpl-is-front">
                              <tbody>
                                <tr>
                                  <th class="tac"><span>选择</span></th>
                                  <th class="tac"><span>名称</span></th>
                                  <th class="tac"><span>型号/说明</span></th>
                                  <th class="tac"><span>厂商编号</span></th>
                                  <th class="tac"><span>厂商指导价</span></th>
                                  <th class="tac"><span>折后价</span></th>
                                </tr>
                               @forelse($xzj['yc'] as $k=>$v)
                                <tr>
                                  <td class="tac select-checked"><input  type="checkbox" name="xzj[]" value="{{$v->xzj_id}}" id=""></td>
                                  <td class="tac"><span>{{$v->xzj_title}}</span></td>
                                  <td class="tac"><span>{{$v->xzj_model}}</span></td>
                                  <td class="tac"><span>{{$v->xzj_cs_serial}}</span></td>
                                  <td class="tac">￥<span>{{number_format($v->xzj_guide_price,2)}}</span></td>
                                  <td class="tac">￥<span>{{number_format(round($v->xzj_guide_price+$v->xzj_fee),2)}}</span></td>
                                  <!--//\\<td class="tac">￥<span>{{number_format($v->xzj_guide_price,2)}}</span></td>
                                  <td class="tac">￥<span>{{round($v->xzj_guide_price+$v->xzj_fee)}}</span></td>-->
                                </tr>
                            @empty
                                <tr><td colspan="6"><p class="tac mt10">暂无</p></td></tr>
                            @endforelse
                              </tbody>
                            </table>
                            <div class="m-t-10"></div>
                            <div class="m-t-10"></div>
                            @endif
                            
                            <p><b>选装精品(出厂后装)</b></p>
                            <table class="tbl tbl-blue wp100" id="tpl-not-front">
                              <tbody>
                                <tr>
                                  <th class="tac"><span>选择</span></th>
                                  <th class="tac"><span>名称</span></th>
                                  <th class="tac"><span>型号/说明</span></th>
                                  <th class="tac"><span>厂商编号</span></th>
                                  <th class="tac"><span>厂商指导价</span></th>
                                  <th class="tac"><span>安装费</span></th>
                                  <th class="tac">
                                     <div class="psr">
                                        <span>含安装费折</span><br><span>后总单价</span>
                                        <div class="th-tip">
                                            <i></i>
                                            计算方式：厂商指导价x折扣率+安装费
                                        </div>
                                     </div>
                                  </th>
                                  <th class="tac"><span>可供件数</span></th>
                                </tr>
                               @forelse($xzj['fyc'] as $k=>$v)
                                <tr>
                                  <td class="tac select-checked"><input  type="checkbox" name="xzj[]"  value="{{$v->xzj_id}}" id=""></td>
                                  <td class="tac"><span>{{$v->xzj_title}}</span></td>
                                  <td class="tac"><span>{{$v->xzj_model}}</span></td>
                                  <td class="tac"><span>{{$v->xzj_cs_serial}}</span></td>
                                  <td class="tac">￥<span>{{number_format(round($v->xzj_guide_price,2),2)}}</span></td>
                                  <td class="tac">￥<span>{{number_format(round($v->xzj_fee,2),2)}}</span></td>
                                  <td class="tac">￥<span>{{number_format(round($v->xzj_guide_price+$v->xzj_fee,2),2)}}</span></td>
                                  <td class="tac"><span><?=($v->xzj_has_num==0)?'不限':$v->xzj_has_num;?></span></td>
                                </tr>
                             @empty
                                <tr><td colspan="8"><p class="tac mt10">暂无</p></td></tr>
                              @endforelse
                              </tbody>
                            </table>
                            
                            
                            

                            
                            <div class="m-t-10"></div>
                            <div class="m-t-10"></div>
                            <p class="tac">                           		
                           		<a href="/dealer/baojia/edit/{{$baojia['bj_id']}}/2" class="btn btn-danger sure fs18 ml20">返回上一步</a>
                                <a href="javascript:;" class="btn btn-danger fs18 ml20 baojia-submit-button" data-step='3' data-type='1'>下一步</a>
                                <a href="javascript:;" class="btn btn-danger sure fs18 ml20 baojia-submit-button" data-step='3' data-type='2'>保存并退出</a>
                           	  	<a href="javascript:;" class="btn btn-danger sure fs18 ml20 reset-form">重置</a>
                            </p> 



 


                            
                        </div>
                        
                    </form>
                    <div class="m-t-200"></div> 


                   
                </div>
@endsection                
    
@section('js')
	
	 <script type="text/javascript">
seajs.use(["module/custom/custom_admin","module/custom/custom.admin.common.jquery","module/custom/custom.admin.goods.jquery", "module/common/common", "bt"],function(a,b,c){
    
});
	</script>
@endsection