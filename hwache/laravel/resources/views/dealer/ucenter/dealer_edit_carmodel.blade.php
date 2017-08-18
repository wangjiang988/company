@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
                        <div class="content-wapper ">
                           <h2 class="title weighttitle">修改常用车型</h2>
                           <div class="m-t-10"></div>
                           <table class="custom-info-tbl ml-10" width="100%" cellpadding="0" cellspacing="0">
                               <tr>
                                   <td class="right" width="100">
                                       <span>品 牌：</span>
                                   </td>
                                   <td align="left">
                                       <span>{{$car_brand['gc_name']}}</span>
                                   </td>
                               </tr>
                               <tr>
                                   <td class="right" >
                                       <span>车 系：</span>
                                   </td>
                                   <td align="left">
                                        <span>{{$data['gc_name']}}</span>
                                        <div class="error-div"><label>请选择车系</label></div>
                                   </td>
                               </tr>
                               <tr>
                                   <td class="right" >
                                       <span>车型规格：</span>
                                   </td>
                                   <td align="left">
                                      <span>{{$data['staple_name']}}</span>
                                        <div class="error-div"><label>车型规格</label></div>
                                   </td>
                               </tr>
                               <tr>
                                   <td class="right" >
                                       <span>整车型号：</span>
                                   </td>
                                   <td align="left">
                                       <span>{{$data['vehicle_model']}}</span>
                                       <div class="error-div"><label>整车型号</label></div>
                                   </td>
                               </tr>
                               <tr>
                                   <td class="right">
                                       <span>厂商指导价：</span>
                                   </td>
                                   <td align="left">
                                      <span>￥{{unserialize($data['value'])}}</span>
                                   </td>
                               </tr>

                           </table>
                           <p><b>原厂选装精品：</b>（可对厂商编号、安装费、可供件数进行编辑）</p>
                           <p>出厂前装：</p>
                            <form action="/dealer/editcarmodel/{{$dealer_id}}" method="post" id="forms">
                               <input type="hidden" name="_token" value="{{csrf_token()}}">
                               <input type="hidden" name="staple_id" value="{{$data['staple_id']}}">
                               <input type="hidden" name="car_brand" value="{{$data['gc_id_3']}}">
                           <table class="tbl custom-info-tbl tbl-front">
                             <tbody>
                               <tr>
                                   <th class="tac">名称</th>
                                   <th class="tac">型号/说明</th>
                                   <th class="tac">厂商编号(选填)</th>
                                   <th class="tac">厂商指导价</th>
                                   <th class="last">单车可装件数</th>
                               </tr>
                               @if(count($yc)>0)
                                 @foreach ($yc as $y)
                               <tr>
                                   <td class="tac"><span>{{$y->xzj_title}}</span></td>
                                   <td class="tac"><span>{{$y->xzj_model}}</span></td>
                                   <td class="tac">
                                     <input class="text-center card-input card-txt-price" type="text" name="qz_cs_serial[{{$y->id}}]" id="" value="{{$y->xzj_cs_serial}}">
                                       <div class="error-div"><label>请输入厂商编号</label></div>
                                       </td>
                                   <td class="tac"><span>￥{{$y->xzj_guide_price}}</span></td>
                                   <td class="tac"><span>{{$y->xzj_max_num}}</span></td>
                               </tr>
                               @endforeach
                               @else
                               <tr>
                                 <td colspan="8" class="tac">
                                   暂无原厂选装件提供~
                                 </td>
                               </tr>
                               @endif

                             </tbody>
                           </table>
                                

                           <hr class="dashed" />
                           <p>后装（现车加装）：</p>
                           <table class="tbl custom-info-tbl tbl-after">
                             <tbody>
                               <tr>
                                   <th class="tac">名称</th>
                                   <th class="tac">型号/说明</th>
                                   <th class="tac">厂商编号(选填)</th>
                                   <th class="tac">厂商指导价</th>
                                   <th class="tac">安装费</th>
                                   <th class="tac">单车可装件数</th>
                                   <th class="last">可供件数</th>
                               </tr>
                               @if(count($fyc)>0)                             
                                @foreach ($fyc as $f)
                               <tr>
                                   <td class="tac"><span>{{$f->xzj_title}}</span></td>
                                   <td class="tac"><span>{{$f->xzj_model}}</span></td>
                                   <td class="tac">
                                       <input class="text-center card-input card-txt-price" type="text" name="hz_cs_serial[{{$f->id}}]" id="" value="{{$f->xzj_cs_serial}}">
                                       <div class="error-div"><label>请输入厂商编号</label></div>
                                   </td>
                                   <td class="tac"><span>￥{{$f->xzj_guide_price}}</span></td>
                                   <td class="tac">
                                       ￥<input class="text-center card-input card-txt-price valite money-valite" type="text" name="xzj_fee[{{$f->id}}]" id="" 
                                       @if($f->xzj_fee == 0.00) 
                                       value="0">
                                       @else
                                       value="{{$f->xzj_fee}}">
                                       @endif
                                       <div class="error-div"><label>请正确输入安装费</label></div>
                                   </td>
                                   <td class="tac"><span>{{$f->xzj_max_num}}</span></td>
                                   <td class="tac">
                                        <div class="btn-group ">
                                            <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle" >
                                                <span class="dropdown-label"><span>
                                                @if($f->xzj_has_num == 0)
                                                不设限
                                                @else
                                                {{$f->xzj_has_num}}
                                                @endif
                                                </span></span>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-bt dropdown-overhide">
                                                <li><a><span>不设限</span></a></li>
                                            </ul>
                                            <input type="hidden" name="xzj_has_num[{{$f->id}}]" value="
                                            @if(isset($f->xzj_has_num))
                                                {{$f->xzj_has_num}}
                                                @else
                                                @endif">
                                        </div> 
                                   </td>
                               </tr>
                                  @endforeach
                                  @else
                                  <tr>
                                 <td colspan="8" class="tac">
                                   暂无原厂选装件提供~
                                 </td>
                               </tr>
                                  @endif            
                             </tbody>
                           </table>
                            </form>   

                           <hr class="dashed" />
                             <div class="row">
                             <div class="col-sm-6 col-xs-6">
                               <p>非原厂选装精品：<a href="javascript:;" class="juhuang tdu addNoOriginalGoods">添加</a></p>
                             </div>
                             <div class="col-sm-6 col-xs-6 text-right">
                                <p>
                                  <a href="javascript:;" class="juhuang tdu">下载非原厂选装精品模板</a>
                                  <a href="javascript:;" class="juhuang tdu ml10">导入数据</a>
                                </p>
                             </div>
                             <div class="clear"></div>
                           </div>
                           <table class="tbl custom-info-tbl tbl-goods">
                             <tbody>
                               <tr>
                                   <th class="tac">品牌</th>
                                   <th class="tac">名称</th>
                                   <th class="tac">型号/说明</th>
                                   <th class="tac">厂商编号</th>
                                   <th class="tac">含安装费<br>折后单价</th>
                                   <th class="tac">单车可<br>装件数</th>
                                   <th class="tac">可供件数</th>
                                   <th class="last">操作</th>
                               </tr>
                               @if(count($xzjp)>0) 
                                @foreach ($xzjp as $xz)
                               <tr class="def-temp">
                                   <td class="tac"><span>{{$xz->xzj_brand}}</span></td>
                                   <td class="tac"><span>{{$xz->xzj_title}}</span></td>
                                   <td class="tac"><span>{{$xz->xzj_model}}</span></td>
                                   <td class="tac"><span>{{$xz->cs_serial}}</span></td>
                                   <td class="tac"><span>￥{{$xz->xzj_guide_price}}</span></td>
                                   <td class="tac"><span>{{$xz->xzj_max_num}}</span></td>
                                    @if($xz->xzj_has_num == 0)
                                   <td class="tac"><span>不设限</span></td>
                                     @else
                                     <td class="tac"><span>{{$xz->xzj_has_num}}</span></td>
                                     @endif
                                   <td class="tac">
                                         <a data-id="{{$xz->xzj_list_id}}" dealer-id="{{$dealer_id}}" href="javascript:;" class="juhuang tdu edit-original-goods">修改</a>
                                         <a data-id="{{$xz->xzj_list_id}}" dealer-id="{{$dealer_id}}" href="javascript:;" class="juhuang tdu del-original-goods">删除</a>
                                   </td>
                               </tr>
                            @endforeach
                            <tr id="remod" class="none">
                               <td colspan="8" class="tac">
                                 暂无,可<a href="javascript:;" class="juhuang tdu addNoOriginalGoods">添加</a>~
                               </td>
                             </tr>
                            @else
                            <tr id="remod">
                               <td colspan="8" class="tac">
                                 暂无,可<a href="javascript:;" class="juhuang tdu addNoOriginalGoods">添加</a>~
                               </td>
                             </tr>
                            @endif
                            <input type="hidden" name="gc-id-3" value="{{$data['gc_id_3']}}"> 
                             </tbody>
                           </table>

                           <hr class="dashed" />
                           <div class="present-tool">
                               <div class="present-tool-content">
                                  <p><b>随车工具：</b>
                                   @foreach ($general as $gen)
                                   @if ($gen['type'] =='随车工具'){{$gen['title']}}、@endif
                                   @endforeach
                                   </p>
                                   <p><b>随车移交文件：</b>
                                   @foreach ($general as $gene)
                                   @if ($gene['type'] =='文件资料'){{$gene['title']}}、@endif
                                   @endforeach
                                   </p>
                               </div>
                               <p><b>基本配置：</b><a href="#" class="juhuang tdu">（查看）</a></p>
                           </div>
                           <p class="mt10">以上如有疑问，请<a href="#" class="juhuang tdu">联系我们</a></p>
                          
                        </div>
                        <p class="tac mt10">
                           <a href="javascript:;" class="btn btn-danger fs16 btn-new-offerings">修改</a>
                        </p>
                    

                    




                      <div id="addNoOriginalGoods" class="popupbox">
                          <div class="popup-title">添加非原厂选装精品</div>
                          <div class="popup-wrapper">
                              <div class="popup-content">
                                  
                                  <form action="/dealer/ajaxcarmodel/del" name="addNoOriginalGoodsForm" method="post">
                                    <table class="custom-form-tbl no-padding-tbl">
                                        <tr>
                                            <td align="right" width="">
                                                <span>品 牌：</span>
                                            </td>
                                            <td>
                                                <input placeholder="请输入" type="text" name="brand" class="form-control custom-control-normal">
                                                <div class="error-div"><label>*请输入品牌</label></div>
                                            </td>
                                            <td align="right" width="">
                                                <span>名 称：</span>
                                            </td>
                                            <td>
                                                <input placeholder="请输入" type="text" name="title" class="form-control custom-control-normal">
                                                <div class="error-div"><label>*请输入名称</label></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right"  width="70">
                                                <span>型号/说明：</span>
                                            </td>
                                            <td width="155">
                                                <input placeholder="请输入" type="text" name="notice" class="form-control custom-control-normal">
                                                <div class="error-div"><label>*请输入型号/说明</label></div>
                                            </td>
                                            <td align="right" width="100">
                                                <span>厂商编号(选填)：</span>
                                            </td>
                                            <td width="155">
                                                <input placeholder="请输入" type="text" name="model" class="form-control custom-control-normal" >
                                                <div class="error-div"><label>*请输入折后单价</label></div>
                                                <div class="error-div"><label>*请重新输入纯数字格式，例如：60.23</label></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            
                                            <td align="right" width="70">
                                                <p class="nm text-right ">含安装费&nbsp;&nbsp;</p>
                                                <p class="nm text-right">折后单价：</p>
                                            </td>
                                            <td width="155">
                                                <input placeholder="请输入" type="text" name="price" class="form-control custom-control-normal valite-money" >
                                                <div class="error-div"><label>*请输入折后单价</label></div>
                                                <div class="error-div"><label>*请重新输入纯数字格式，例如：60.23</label></div>
                                            </td>
                                            <td align="right" width="">
                                                <span>单车可装件数：</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-jquery-event">
                                                    <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle ">
                                                        <span class="dropdown-label"><span>0</span></span>
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu new-offerings-drop dropdown-overhide limited">                                                      
                                                    </ul>
                                                </div> 

                                            </td>
                                        </tr>
                                        <tr>
                                            
                                            <td align="right" width="">
                                                <span>可供件数：</span>
                                            </td>
                                            <td>
                                                 <div class="btn-group btn-jquery-event">
                                                    <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle ">
                                                        <span class="dropdown-label"><span>不设限</span></span>
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu new-offerings-drop dropdown-overhide unlimited">
                                                        <input type="hidden" name="" /> 
                                                        <li><a>不设限</a></li>
                                                    </ul>
                                                </div> 
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                         
                                    </table>
                                    <input type='hidden' name='_token' value="{{csrf_token()}}">
                                    <input id="d_id" type="hidden" name="dealer_id" value="{{$dealer_id}}" /> 
                                  </form>
                              </div>
                              <div class="popup-control">
                                  <a  href="javascript:;"  class="btn btn-s-md btn-danger fs14 w100 do">提交</a>
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                  <div class="clear"></div>
                              </div>
                          </div>
                      </div>

                      <div id="editNoOriginalGoods" class="popupbox">
                          <div class="popup-title">修改非原厂选装精品</div>
                          <div class="popup-wrapper">
                              <div class="popup-content">
                                  
                                  <form action="/doAddNoOriginalGoods" name="editNoOriginalGoodsForm" method="post">
                                    <table class="custom-form-tbl no-padding-tbl">
                                        <tr>
                                            <td align="right" width="">
                                                <span>品 牌：</span>
                                            </td>
                                            <td>
                                                <input placeholder="请输入" type="text" name="brand" class="form-control custom-control-normal">
                                                <div class="error-div"><label>*请输入品牌</label></div>
                                            </td>
                                            <td align="right" width="">
                                                <span>名 称：</span>
                                            </td>
                                            <td>
                                                <input placeholder="请输入" type="text" name="title" class="form-control custom-control-normal">
                                                <div class="error-div"><label>*请输入名称</label></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="right"  width="70">
                                                <span>型号/说明：</span>
                                            </td>
                                            <td width="155">
                                                <input placeholder="请输入" type="text" name="notice" class="form-control custom-control-normal">
                                                <div class="error-div"><label>*请输入型号/说明</label></div>
                                            </td>
                                            <td align="right" width="100">
                                                <span>厂商编号(选填)：</span>
                                            </td>
                                            <td width="155">
                                                <input placeholder="请输入" type="text" name="model" class="form-control custom-control-normal" >
                                                <div class="error-div"><label>*请输入厂商编号</label></div>
                                                <div class="error-div"><label>*请重新输入纯数字格式，例如：60.23</label></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            
                                            <td align="right" width="70">
                                                <p class="nm text-right ">含安装费&nbsp;&nbsp;</p>
                                                <p class="nm text-right">折后单价：</p>
                                            </td>
                                            <td width="155">
                                                <input placeholder="请输入" type="text" name="price" class="form-control custom-control-normal valite-money" >
                                                <div class="error-div"><label>*请输入折后单价</label></div>
                                                <div class="error-div"><label>*请重新输入纯数字格式，例如：60.23</label></div>
                                            </td>
                                            <td align="right" width="">
                                                <span>单车可装件数：</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-jquery-event">
                                                    <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle ">
                                                        <span class="dropdown-label"><span>                     
                                                        </span></span>
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu new-offerings-drop dropdown-overhide limited">                                                      
                                                    </ul>
                                                </div> 

                                            </td>
                                        </tr>
                                        <tr>
                                            
                                            <td align="right" width="">
                                                <span>可供件数：</span>
                                            </td>
                                            <td>
                                                 <div class="btn-group btn-jquery-event">
                                                    <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle ">
                                                        <span class="dropdown-label"><span>
                                                        </span></span>
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu new-offerings-drop dropdown-overhide unlimited">
                                                        <input type="hidden" name="" /> 
                                                        <li><a>不设限</a></li>
                                                    </ul>
                                                </div> 
                                            </td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                         





                                    </table>
                                  </form>
                              </div>
                              <div class="popup-control">
                                  <a  href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 do">提交</a>
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                  <div class="clear"></div>
                              </div>
                          </div>
                      </div> 
                
                      <div id="delNoOriginalGoods" class="popupbox">
                          <div class="popup-title">温馨提示</div>
                          <div class="popup-wrapper">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <p class="fs14 pd  tac">确定要删除该常用车型吗？</p>
                                   <div class="m-t-10"></div>
                              </div>
                              <div class="popup-control">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 do">确认</a>
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                  <div class="clear"></div>
                              </div>
                          </div>
                      </div>

                      <div id="tip-succeed" class="popupbox">
                          <div class="popup-title">温馨提示</div>
                          <div class="popup-wrapper">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <div class="fs14 pd tac succeed auto">
                                     <center>
                                       <span class="tip-tag"></span>
                                       <span class="tip-text">操作成功!</span>
                                     </center>
                                  </div>
                              </div>
                              <div class="popup-control">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 sure skillsure">确认</a>
                                  <div class="clear"></div>
                              </div>
                          </div>
                      </div>

                      <div id="tip-error" class="popupbox">
                          <div class="popup-title">温馨提示</div>
                          <div class="popup-wrapper">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <div class="fs14 pd tac error auto">
                                     <center>
                                       <span class="tip-tag"></span>
                                       <span class="tip-text">操作失败!</span>
                                     </center>
                                  </div>
                              </div>
                              <div class="popup-control">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 sure skillsure">确认</a>
                                  <div class="clear"></div>
                              </div>
                          </div>
                      </div>




                   
                </div>
                <div class="clear"></div>
            </div>
        </div>


    <div class="box" ms-include-src="footernew"></div>

@endsection

@section('js')
    <script type="text/template" id="GoodsLoading">
        <tr class="def-temp">
           <td class="tac"><span>{0}</span></td>
           <td class="tac"><span>{1}</span></td>
           <td class="tac"><span>{2}</span></td>
           <td class="tac"><span>{3}</span></td>
           <td class="tac"><span>￥{4}</span></td>
           <td class="tac"><span>{5}</span></td>
           <td class="tac"><span>{6}</span></td>
           <td class="tac">
               <a data-id="{id}" href="javascript:;" class="juhuang tdu edit-original-goods">修改</a>
               <a data-id="{id}" href="javascript:;" class="juhuang tdu del-original-goods">删除</a>
           </td>
       </tr>
  </script>
  <script type="text/javascript">
        seajs.use(["module/custom/custom_admin", "module/custom/custom.admin.jquery","module/common/common", "bt"],function(a,b,c){
           b.initCount("dropdown-bt",100) //后装
           b.initCount("unlimited",100)   //添加非原厂选装精品 单车可装件数
           b.initCount("limited",10)      //添加非原厂选装精品  可供件数           
        });
  </script>
@endsection


