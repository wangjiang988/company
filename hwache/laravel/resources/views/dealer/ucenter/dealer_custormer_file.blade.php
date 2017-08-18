@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')                     
                        <div class="content-wapper ">
                          
                           <div class="m-t-10"></div>
                           <table class="custom-info-tbl ml-10" width="100%" cellpadding="0" cellspacing="0">
                              
                               <tr>
                                   <td class="right" width="135">
                                       <label>车辆用途：</label>
                                   </td>
                                   <td align="left">
                                        <div class="btn-group btn-jquery-Passenger">
                                            <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle dropdown-custom" >
                                                <span class="dropdown-label"><span>请选择车辆用途</span></span>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-vehicular-applications">
                                                <li data-id="0"><a><span>非营业个人客车</span></a></li>
                                                <li data-id="1"><a><span>非营业企业客车</span></a></li>
                                            </ul>
                                            <input type="hidden" name="">
                                        </div>
                                        <span class="error-div"><label>*请选择车辆用途~</label></span>
                                   </td>
                               </tr>
                               <tr>
                                   <td class="right" >
                                     <p class="nomargin"><label class="nomargin">上牌（注册登记）</label></p>
                                     <p class="nomargin"><label class="nomargin">车主身份类别：</label></p>
                                   </td>
                                   <td align="left">
                                        <div class="btn-group m-r pdi-drop pdi-drop-warp btn-jquery-Passenger">
                                            <button data-toggle="dropdown" class="btn btn-default dropdown-toggle area-drop-btn" >
                                                <span class="dropdown-label"><span>请选择上牌车主身份类别</span></span>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-categories">
                                                <li data-id="0" class="categories-def"><a><span>上牌地本市户籍居民</span></a></li>
                                                <li data-id="1" class="categories-def"><a><span>国内其他非限牌城市户籍居民</span></a></li>
                                                <li data-id="2" class="categories-def"><a><span>国内限牌城市（上海）户籍居民</span></a></li>
                                                <li data-id="3" class="categories-def"><a><span>国内限牌城市（北京）户籍居民</span></a></li>
                                                <li data-id="4" class="categories-def"><a><span>国内限牌城市（广州）户籍居民</span></a></li>
                                                <li data-id="5" class="categories-def"><a><span>国内限牌城市（天津）户籍居民</span></a></li>
                                                <li data-id="6" class="categories-def"><a><span>国内限牌城市（杭州）户籍居民</span></a></li>
                                                <li data-id="7" class="categories-def"><a><span>国内限牌城市（贵阳）户籍居民</span></a></li>
                                                <li data-id="8" class="categories-def"><a><span>国内限牌城市（深圳）户籍居民</span></a></li>
                                                <li data-id="9" class="categories-def"><a><span>中国军人</span></a></li>
                                                <li data-id="10" class="categories-def"><a><span>非中国大陆人士（外籍人士）</span></a></li>
                                                <li data-id="11" class="categories-def"><a><span>非中国大陆人士（台胞）</span></a></li>
                                                <li data-id="12" class="categories-def"><a><span>非中国大陆人士（港澳人士）</span></a></li>
                                                <li data-id="13" class="categories-def"><a><span>非中国大陆人士（持绿卡华侨）</span></a></li>
                                                <li data-id="0" type-id="1" class="categories-advance none"><a><span>上牌地本市注册企业（增值税一般纳税人）</span></a></li>
                                                <li data-id="1" type-id="1" class="categories-advance none"><a><span>上牌地本市注册企业（小规模纳税人）</span></a></li>
                                            </ul>
                                            <input type="hidden" name="">
                                        </div>
                                        <span class="error-div"><label>*请选择上牌车主身份类别</label></span>
                                   </td>
                               </tr> 
                           </table> 

                           <hr class="dashed" />
                           <div class="tbl-file-wrapper">
                             <table class="tbl custom-info-tbl tbl-file" >
                               <tbody>
                                 <tr>
                                     <td width="197" class="tac nobottomborder"><span>使用场合</span></td>
                                     <td width="138" class="tac nobottomborder"><span>文件资料</span></td>
                                     <td width="68"  class="tac nobottomborder"><span>数量</span></td>
                                     <td width="200" class="tac nobottomborder"><span>文件格式</span></td>
                                     <td width="132" class="tac nobottomborder"><span>操作</span></td>
                                 </tr>
                               </tbody>
                             </table>

                             <table class="tbl custom-info-tbl tbl-file" >
                               <tbody id="one"> 
                                 <tr class="file-tr">
                                     <td width="197" class="tac nobottomborder" rowspan="99">
                                        <p class="nomargin use-occasions-title">提车人身份验证</p>
                                        <p class="nomargin use-occasions-title">（提车人非车主本人）</p> 
                                     </td>
                                 </tr> 
                                 <tr>
                                     <td width="550" class="text-left nobottomborder use-occasions-add-file-wrapper" colspan="4">
                                        <a href="javascript:;" class="juhuang tdu ml30 use-occasions-add-file" data-id=1>添加文件</a>
                                     </td>
                                 </tr>
                                
                               </tbody>
                             </table>

                             <table class="tbl custom-info-tbl tbl-file" >
                               <tbody id="two">
                                 <tr class="file-tr">
                                     <td width="197" class="tac nobottomborder" rowspan="99">
                                        <p class="nomargin use-occasions-title">投保车辆首年商业保险</p>
                                     </td>
                                 </tr> 
                                 <tr>
                                     <td width="545" class="text-left nobottomborder use-occasions-add-file-wrapper" colspan="4">
                                        <a href="javascript:;" class="juhuang tdu ml30 use-occasions-add-file" data-id=2>添加文件</a>
                                     </td>
                                 </tr>
                                 
                               </tbody>
                             </table>

                             <table class="tbl custom-info-tbl tbl-file" >
                               <tbody id="three">
                                 <tr class="file-tr">
                                     <td width="197" class="tac nobottomborder" rowspan="99">
                                        <p class="nomargin use-occasions-title">代办上牌手续</p>
                                     </td>
                                 </tr> 
                                 <tr>
                                     <td width="545" class="text-left nobottomborder use-occasions-add-file-wrapper" colspan="4">
                                        <a href="javascript:;" class="juhuang tdu ml30 use-occasions-add-file" data-id=3>添加文件</a>
                                     </td>
                                 </tr>
                               </tbody>
                             </table>

                             <table class="tbl custom-info-tbl tbl-file" >
                               <tbody id="four">
                                 <tr class="file-tr">
                                     <td width="197" class="tac nobottomborder" rowspan="99">
                                        <p class="nomargin use-occasions-title">代办车辆临时牌照手续</p>
                                     </td>
                                 </tr> 
                                 <tr>
                                     <td width="545" class="text-left nobottomborder use-occasions-add-file-wrapper" colspan="4">
                                        <a href="javascript:;" class="juhuang tdu ml30 use-occasions-add-file" data-id=4>添加文件</a>
                                     </td>
                                 </tr>
                               </tbody>
                             </table>

                             <table class="tbl custom-info-tbl tbl-file" >
                               <tbody id="five">
                                 <tr class="file-tr">
                                     <td width="197" class="tac nobottomborder" rowspan="99">
                                      <p class="nomargin use-occasions-title">刷卡付款</p>
                                        <p class="nomargin use-occasions-title">(刷卡人非银行卡卡主本人)</p> 
                                        <p class="nomargin use-occasions-title"></p>
                                     </td>
                                 </tr> 
                                 <tr>
                                     <td width="545" class="text-left nobottomborder use-occasions-add-file-wrapper" colspan="4">
                                        <a href="javascript:;" class="juhuang tdu ml30 use-occasions-add-file" data-id=5>添加文件</a>
                                     </td>
                                 </tr>
                                 

                               </tbody>
                             </table>
                               
                             
                             <div class="use-occasions-add-wrapper notopborder nopadding">
                                <input type="hidden" name="dealer_id" value="{{$dealer_id}}">
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
                        

                     


                      <div id="addUseOccasionsFile" class="popupbox">
                          <div class="popup-title">新增/修改文件</div>
                          <div class="popup-wrapper">
                              <div class="popup-content">
                                  
                                  <form action="/dealer/ajaxCustfile/add_comment" name="addUseOccasionsFileForm" enctype="multipart/form-data" method="post">
                                  <input type="hidden" name="type_id" value="">
                                  <input type="hidden" name="shenfen_id" value="">
                                  <input type="hidden" name="dealer_id" value="{{$dealer_id}}">
                                  <input type="hidden" name="type" value="">
                                  <input type='hidden' name='_token' value="{{csrf_token()}}">
                                    <table class="custom-form-tbl no-padding-tbl ml20">
                                        <tr>
                                            <td align="right" width="">
                                                <span>使用场合：</span>
                                            </td>
                                            <td>
                                                <span class="add-file-title"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                          <td align="right" width="">
                                                <span>文件资料：</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-file">
                                                    <button data-toggle="dropdown" class="btn btn-select btn-select-normal btn-default dropdown-toggle ">
                                                        <span class="dropdown-label"><span>客户文件</span></span>
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-file dropdown-overhide">
                                                        <input type="hidden" name="" /> 
                                                        <input type="hidden" name="file_id">
                                                        <li data-id="10000"><a><span>请选择车主身份类别</span></a></li>
                                                    </ul>
                                                </div> 
                                                <div class="error-div"><label>*请选择文件资料</label></div>
                                            </td>
                                        </tr>
                                    </table>

                                    <table class="custom-form-tbl no-padding-tbl ml20">
                                        <tr>
                                            <td align="right"  width="180" valign="top">
                                                <p class="nomargin">是否需要原件及数量：</p>
                                                <p class="nomargin">（如原件与复印件都要，</p>
                                                <p class="nomargin">请分两次操作）</p>
                                            </td>
                                            <td width="300">
                                                <p class="nomargin">
                                                    <label class="ml20">
                                                      <input type="radio" name="use-occasions-file-radio" id="" value="1">
                                                      <span>原件</span>
                                                    </label>
                                                </p>
                                                <div class="nomargin">
                                                    <label class="ml20">
                                                      <input type="radio" name="use-occasions-file-radio" id="" value="0">
                                                      <span>复印件</span> 
                                                      <div class="btn-group inline-block btn-jquery-event">
                                                          <button data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle" >
                                                              <span class="dropdown-label"><span>1</span></span>
                                                              <span class="caret"></span>
                                                          </button>
                                                          <ul class="dropdown-menu dropdown-bt dropdown-overhide">
                                                              <li><a><span>1</span></a></li>
                                                              <li><a><span>2</span></a></li>
                                                              <li><a><span>3</span></a></li>
                                                              <li><a><span>4</span></a></li>
                                                              <li><a><span>5</span></a></li>
                                                              <li><a><span>6</span></a></li>
                                                              <li><a><span>7</span></a></li>
                                                              <li><a><span>8</span></a></li>
                                                              <li><a><span>9</span></a></li>
                                                              <li><a><span>10</span></a></li>
                                                          </ul>
                                                          <input type="hidden" name="num">
                                                      </div>
                                                      <span class="ml10">份</span>
                                                    </label>
                                                </div>
                                                <div class="error-div"><label>*请选择原件还是复印件</label></div>
                                            </td>
                                        </tr>
                                        <tr>
                                          <td colspan="2" class="text-left">
                                             <p class="ml30">专用格式请上传（没有可不上传）  <a href="javascript:;" class="juhuang tdu file-upload">点击上传</a></p>
                                             <div id="add_upload">
                                                <span class="blue ml20"><span class="file-prev ml10 use-occasions-add-file-name"></span></span>
                                                <input type="file" name="pic" id="hfUpload" class="hide" value="">
                                                <input type="hidden" name="" id="hfFile">
                                             </div>
                                          </td>
                                        </tr>
                                    
                                         
                                    </table>
                                  </form>
                              </div>
                              <div class="popup-control">
                                  <a  href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 do">提交</a>
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                  <div class="clear"></div>
                                  <div class="error-div"><label class="red">*此使用场合的同一文件资料已添加，请重新选择~</label></div>
                              </div>
                          </div>
                      </div>

                      <div id="editUseOccasionsFile" class="popupbox">
                          <div class="popup-title">新增/修改文件</div>
                          <div class="popup-wrapper">
                              <div class="popup-content">
                                  
                                  <form action="/dealer/ajaxCustfile/edit" name="editUseOccasionsFileForm" method="post" enctype="multipart/form-data">
                                  <input type='hidden' name='_token' value="{{csrf_token()}}">
                                  <input type="hidden" name="dealer_id" value="{{$dealer_id}}">
                                  <input type="hidden" name="id" value="">
                                    <table class="custom-form-tbl no-padding-tbl ml20">
                                        <tr>
                                            <td align="right" width="">
                                                <span>使用场合：</span>
                                            </td>
                                            <td>
                                                <span class="add-file-title"></span>
                                            </td>
                                        </tr>
                                        <tr>
                                          <td align="right" width="">
                                                <span>文件资料：</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-jquery-event">
                                                    <button data-toggle="dropdown" disabled class="btn btn-select btn-select-normal btn-default dropdown-toggle ">
                                                        <span class="dropdown-label"><span>客户文件</span></span>
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-file dropdown-overhide">
                                                        <input type="hidden" name="" /> 
                                                        <li data-id="10000"><a><span>请选择车主身份类别</span></a></li>
                                                    </ul>
                                                </div> 
                                                <div class="error-div"><label>*请选择文件资料</label></div>
                                            </td>
                                        </tr>
                                    </table>

                                    <table class="custom-form-tbl no-padding-tbl ml20">
                                        <tr>
                                            <td align="right"  width="180">
                                                <p class="nomargin">是否需要原件及数量：</p>
                                                <p class="nomargin">（如原件与复印件都要，</p>
                                                <p class="nomargin">请分两次操作）</p>
                                            </td>
                                            <td width="300">
                                                <p class="nomargin">
                                                    <label class="ml20">
                                                      <input disabled type="radio" name="use-occasions-file-radio" id="">
                                                      <span>原件</span>
                                                    </label>
                                                </p>
                                                <div class="nomargin">
                                                    <label class="ml20">
                                                      <input disabled type="radio" name="use-occasions-file-radio" id="">
                                                      <span>复印件</span> 
                                                      <div class="btn-group inline-block btn-jquery-event btn-edit">
                                                          <button id="btn-p" data-toggle="dropdown" class="btn btn-sm btn-default dropdown-toggle" >
                                                              <span class="dropdown-label"><span>1</span></span>
                                                              <span class="caret"></span>
                                                          </button>
                                                          <ul class="dropdown-menu dropdown-bt dropdown-overhide">
                                                              <li><a><span>1</span></a></li>
                                                              <li><a><span>2</span></a></li>
                                                              <li><a><span>3</span></a></li>
                                                              <li><a><span>4</span></a></li>
                                                              <li><a><span>5</span></a></li>
                                                              <li><a><span>6</span></a></li>
                                                              <li><a><span>7</span></a></li>
                                                              <li><a><span>8</span></a></li>
                                                              <li><a><span>9</span></a></li>
                                                              <li><a><span>10</span></a></li>
                                                          </ul>
                                                          <input type="hidden" name="num">
                                                      </div>
                                                      <span class="ml10">份</span>
                                                    </label>
                                                </div>
                                                <div class="error-div"><label>*请选择原件还是复印件</label></div>
                                            </td>
                                        </tr>
                                        <tr>
                                          <td colspan="2" class="text-left">
                                             <p class="ml30">专用格式请上传（没有可不上传）  <a href="javascript:;" class="juhuang tdu file-upload">点击上传</a></p>
                                             <div id="upload-file">
                                                <span class="blue ml20"><span class="file-prev ml10 use-occasions-edit-file-name"></span></span>
                                                <input type="file" name="pic" id="hfUpload" class="hide" value="">
                                                <input type="hidden" name="" id="hfFile">
                                             </div>
                                          </td>
                                        </tr>
                                    
                                         
                                    </table>
                                  </form>
                              </div>
                              <div class="popup-control">
                                  <a  href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 do">提交</a>
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                  <div class="clear"></div>
                                  <div class="error-div"><label class="red">*此使用场合的同一文件资料已添加，请重新选择~</label></div>
                              </div>
                          </div>
                      </div>
                
                      <div id="UseOccasionsFile" class="popupbox">
                          <div class="popup-title">温馨提示</div>
                          <div class="popup-wrapper">
                              <div class="popup-content">
                                   <div class="m-t-10"></div>
                                   <p class="fs14 pd  tac">确定要删除此文件吗？</p>
                                   <div class="m-t-10"></div>
                              </div>
                              <div class="popup-control">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 do">确认</a>
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                  <div class="clear"></div>
                              </div>
                          </div>
                      </div>

                      <div id="addOccasions" class="popupbox">
                          <div class="popup-title">新增使用场合</div>
                          <div class="popup-wrapper">
                              <div class="popup-content">
                                    <form action="addOccasions" name="addOccasionsForm">
                                        <div class="m-t-10"></div>
                                        <div class="m-t-10"></div>
                                        <table class="custom-form-tbl no-padding-tbl ml20">
                                            <tr>
                                                <td align="right" width="">
                                                    <span>使用场合：</span>
                                                </td>
                                                <td>
                                                    <input placeholder="" type="text" name="cate" class="form-control custom-control" value="">
                                                    <div class="error-div"><label>*请填写使用场合</label></div>
                                                    <div class="error-div"><label>*该使用场合已存在，请重新输入</label></div>
                                                </td>
                                            </tr>
                                        </table>
                                        <div class="m-t-10"></div>
                                        <div class="m-t-10"></div>
                                    </form>
                              </div>
                              <div class="popup-control">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 do">确认</a>
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                  <div class="clear"></div>
                              </div>
                          </div>
                      </div>

                      <div id="delUseOccasionsFile" class="popupbox">
                          <div class="popup-title">温馨提示</div>
                          <div class="popup-wrapper">
                              <div class="popup-content">
                                    <form action="delUseOccasionsFile" name="delUseOccasionsFileForm">
                                        <div class="m-t-10"></div>
                                        <p class="fs14 pd  tac">确定要删除此文件吗？</p>
                                        <div class="m-t-10"></div>
                                    </form>
                              </div>
                              <div class="popup-control">
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 w100 do">确认</a>
                                  <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                  <div class="clear"></div>
                              </div>
                          </div>
                      </div>
 
                   
                </div>
                <div class="clear"></div>
            </div>
        </div>


@endsection

@section('js')
    
    <script type="text/template" id="FirstFileTmp">
       <td width="138" id="{id}" class="tac display-transformation"><span class="source">{0}</span></td>
       <td width="68" class="tac display-transformation"><span class="source-count">{2}</span></td>
       <td width="200" class="tac display-transformation">
          <span class="blue"><span class="file-prev source-file {class}"><a href="/upload/{1}" target="_bank">{1}</a></span></span>
       </td>
       <td width="132" class="tac display-transformation">
          <a href="javascript:;" class="juhuang tdu use-occasions-edit" data-id="{id}">修改</a>
          <a href="javascript:;" class="juhuang tdu use-occasions-del" data-id="{id}">删除</a>
       </td>
    </script>
    <script type="text/template" id="AnotherFileTmp">
        <tr id="{id}" class="file-tr">
           <td width="138" class="tac display-transformation"><span class="copy">{0}</span></td>
           <td width="68" class="tac display-transformation"><span class="copy-count">{2}</span></td>
           <td width="200" class="tac display-transformation">
               <span class="blue"><span class="file-prev copy-file {class}"><a href="/upload/{1}" target="_bank">{1}</span></span>
           </td>
           <td width="132" class="tac display-transformation">
              <a href="javascript:;" class="juhuang tdu use-occasions-edit" data-id="{id}">修改</a>
              <a href="javascript:;" class="juhuang tdu use-occasions-del" data-id="{id}">删除</a>
           </td>
        </tr>
    </script>
    <script type="text/template" id="OccasionTmp">
        <table class="tbl custom-info-tbl tbl-file" >
           <tbody>
             <tr class="file-tr">
                 <td width="197" class="tac nobottomborder" rowspan="99">
                    <p class="nomargin use-occasions-title">{0}</p>
                 </td>
             </tr> 
             <tr>
                 <td width="545" class="text-left nobottomborder use-occasions-add-file-wrapper" colspan="4">
                    <a href="javascript:;" class="juhuang tdu ml30 use-occasions-add-file">添加文件</a>
                 </td>
             </tr>
           </tbody>
        </table>
    </script>
    <script type="text/javascript">
        seajs.use(["module/custom/custom_admin","module/custom/custom.admin.common.jquery","module/custom/custom.admin.file.jquery", "module/common/common", "bt"],function(a,b,c){
         
        });
    </script>
@endsection


