@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
                    <!--//账户信息-->
                    <h3 class="title">账户信息</h3>
                    <table class="tbl custom-info-tbl">
                         <tr>
                             <td width="380">用户名：{{$member['seller_name']}}</td>
                             <td width="292"></td>
                             <td width="85" class="nobottomborder">
                                 <div class="pic-wrapper">
                                     <a href="/dealer/member_info/modify_base" class="modify">修改个人信息</a>
                                     <div class="pic">
                                         <img width="84" class="block" src="/{{$member['seller_photo']}}" alt="" onerror="this.src='/themes/images/custom/custom-info-head.gif'">
                                         <p class="info-txt">上传头像</p>
                                     </div>
                                 </div>
                             </td>
                         </tr>
                         <tr>
                             <td width="380">类别：A(主)</td>
                             <td width="292">
                                 <dl class="star-dl">
                                     <dd>级别：</dd>
                                     <dd>
                                        <span class="star"></span>
                                        <span class="star"></span>
                                        <span class="star"></span>
                                     </dd>
                                     <div class="clear"></div>
                                 </dl>
                             </td>
                             <td width="85" class="notopborder nobottomborder"></td>
                         </tr>
                         <tr>
                             <td width="380">真实姓名：{{$member['member_truename']}}<small>（已认证，不可修改）</small></td>
                             <td width="292">性别：{{ ($member['seller_sex'] == 1) ? '男' : '女'}}
                             </td>
                             <td width="85" class="notopborder "></td>
                         </tr>
                    </table>
                    <!--//联络方式-->
                    <h3 class="title">联络方式</h3>
                    <table class="tbl custom-info-tbl">
                         <tr>
                             <td width="380">手机号码：{{$member['member_mobile']}} <a href="/dealer/changemobile/byphone" class="ml5"><small>修改</small></a></td>
                             <td width="378">固定电话：{{$member['seller_phone']}}</td>
                         </tr>
                         <tr>
                             <td width="380">电子邮箱：{{$member['seller_email']}}<a href="/dealer/changeemail/byphone" class="ml5"><small>修改</small></a></td>
                             <td width="378">联系地址：{{$member['seller_address']}}</td>
                         </tr>
                         <tr>
                             <td width="380">邮编：{{$member['seller_postcode']}}</td>
                             <td width="378">
                             	微信号<?php if($member['seller_weixin']==''){?>（<a>未绑定</a>）<?php }?>：
                             	{{$member['seller_weixin']}}
                             	</td>
                         </tr>
                         <tr>
                             <td colspan="2">其他联络方式：{{$other_contact_str}}</td>
                         </tr>
                    </table>

                    <!--//银行账户信息-->
                    <h3 class="title">银行账户信息</h3>
                    <table class="tbl custom-info-tbl">
                         <tr>
                             <td width="380" class="nobottomborder">
                                	开户行: {{$member['seller_bank_city_str'].' '.$member['seller_bank_addr']}}
                             </td>
                         </tr>
                         <tr>
                             <td width="380" class="notopborder">账号/卡号：{{$member['seller_bank_account']}}</td>
                         </tr>
                    </table>

                    <!--//附属账户-->
{{--                     <h3 class="title">附属账户</h3>
                    <table class="tbl custom-info-tbl">
                         <tr>
                             <th>用户名</th>
                             <th>用户姓名</th>
                             <th>手机号</th>
                             <th>用户权限</th>
                             <th class="last">
                                管理
                                <div class="pic-wrapper">
                                    <a href="javascript:;" ms-on-click="addaccount" class="modify modify-account normal">添加子账户</a>
                                </div>
                             </th>
                         </tr>
                         <tr>
                             <td>sql-xa001</td>
                             <td>王德军</td>
                             <td>18897262711</td>
                             <td>常用管理、报价管理、订单管理</td>
                             <td class="tac">
                                 <a href="javascript:;" ms-on-click="editaccount(1)" class="weight">修改</a>
                                 <a href="javascript:;" ms-on-click="delaccount(1)" class="ml10 weight">删除</a>
                             </td>
                         </tr>

                    </table>

					<!--//平台服务-->
                    <h3 class="title">平台服务</h3>
                    <table class="tbl custom-info-tbl">
                         <tr>
                             <td class="nobottomborder norightborder">专属客服：{{$kefu['name']}}</td>
                             <td class="nobottomborder noleftborder">电子邮箱：{{$kefu['email']}}</td>
                         </tr>
                         <tr>
                             <td class="notopborder norightborder">手机号码：{{$kefu['mobilephone']}}</td>
                             <td class="notopborder noleftborder">固定电话：{{$kefu['phone']}}</td>
                         </tr>
                    </table> --}}


                    <div class="content-wapper">
                         <div id="account-add" class="popupbox">
                            <div class="popup-title">添加子账户</div>
                            <div class="popup-wrapper">
                                <div class="popup-content">
                                    <form action="" name="addaccount">
                                        <table>
                                            <tr>
                                                <td align="right" width="196">用 户 名：</td>
                                                <td>
                                                    <!--//xql这个前缀是主体用户名-->
                                                    <span>xql-</span>
                                                    <input type="text" name="account-login-name" placeholder="" class="form-control custom-control custom-name-contrl">
                                                    <div class="error-div"><label>请填写用户名</label></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right">用户姓名：</td>
                                                <td>
                                                    <input type="text" name="account-name" placeholder="" class="form-control custom-control">
                                                    <div class="error-div"><label>请填写用户姓名</label></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right">手 机 号：</td>
                                                <td>
                                                    <input type="text" name="account-phone" placeholder="" class="form-control custom-control">
                                                    <div class="error-div"><label>请填写手机号</label></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right" valign="top">用户权限：</td>
                                                <td>
                                                    <label class="normal"><input type="checkbox" name="account-role" id="">常用管理</label>
                                                    <label class="normal ml20"><input type="checkbox" name="account-role" id="">报价管理</label>
                                                    <div class="clear"></div>
                                                    <label class="normal"><input type="checkbox" name="account-role" id="">订单管理</label>
                                                    <label class="normal ml20"><input type="checkbox" name="account-role" id="">资金管理</label>
                                                    <div class="error-div"><label>请至少选择一个用户权限</label></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right">设置子账户密码：</td>
                                                <td>
                                                    <input type="password" name="account-pwd" placeholder="" class="form-control custom-control">
                                                    <div class="error-div"><label>请填写子账户密码</label></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right">请再次输入密码：</td>
                                                <td>
                                                    <input type="password" name="account-pwd-2" placeholder="" class="form-control custom-control">
                                                    <div class="error-div"><label>两次密码输入不一致</label></div>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>

                                </div>
                                <div class="popup-control">
                                    <a ms-on-click="doaddaccount" href="javascript:;" class="btn btn-s-md btn-danger fs14 w100">提交</a>
                                    <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>

                        <div id="account-modify" class="popupbox">
                            <div class="popup-title">修改子账户</div>
                            <div class="popup-wrapper">
                                <div class="popup-content">
                                    <form action="" name="editaccount">
                                        <input type="hidden" name="accountid" ms-attr-value="editmodel.accountid">
                                        <table>
                                            <tr>
                                                <td align="right" width="196">用 户 名：</td>
                                                <td>
                                                    <span>xql-</span>
                                                    <input ms-attr-value="editmodel.accountloginname" type="text" name="account-login-name" placeholder="" class="form-control custom-control custom-name-contrl">
                                                    <div class="error-div"><label>请填写用户名</label></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right">用户姓名：</td>
                                                <td>
                                                    <input ms-attr-value="editmodel.accountname" type="text" name="account-name" placeholder="" class="form-control custom-control">
                                                    <div class="error-div"><label>请填写用户姓名</label></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right">手 机 号：</td>
                                                <td>
                                                    <input ms-attr-value="editmodel.accountphone" type="text" name="account-phone" placeholder="" class="form-control custom-control">
                                                    <div class="error-div"><label>请填写手机号</label></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right" valign="top">用户权限：</td>
                                                <td>
                                                    <label class="normal"><input ms-attr-checked="editmodel.accountrole[0]" type="checkbox" name="account-role" id="">常用管理</label>
                                                    <label class="normal ml20"><input ms-attr-checked="editmodel.accountrole[1]" type="checkbox" name="account-role" id="">报价管理</label>
                                                    <div class="clear"></div>
                                                    <label class="normal"><input ms-attr-checked="editmodel.accountrole[2]" type="checkbox" name="account-role" id="">订单管理</label>
                                                    <label class="normal ml20"><input ms-attr-checked="editmodel.accountrole[3]" type="checkbox" name="account-role" id="">资金管理</label>
                                                    <div class="error-div"><label>请至少选择一个用户权限</label></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right">设置子账户密码：</td>
                                                <td>
                                                    <input type="password" name="account-pwd" placeholder="" class="form-control custom-control">
                                                    <div class="error-div"><label>请填写子账户密码</label></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="right">请再次输入密码：</td>
                                                <td>
                                                    <input type="password" name="account-pwd-2" placeholder="" class="form-control custom-control">
                                                    <div class="error-div"><label>两次密码输入不一致</label></div>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                                <div class="popup-control">
                                    <a ms-on-click="doeditaccount" href="javascript:;" class="btn btn-s-md btn-danger fs14 w100">提交</a>
                                    <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>

                        <div id="account-del" class="popupbox">
                            <div class="popup-title">温馨提示</div>
                            <div class="popup-wrapper">
                                <div class="popup-content">
                                    <p class="fs14 pd ti">
                                        确定要删除该附属用户吗？
                                    </p>
                                </div>
                                <div class="popup-control">
                                    <a ms-on-click="dodelaccount" href="javascript:;" class="btn btn-s-md btn-danger fs14 w100">提交</a>
                                    <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure w100 ml20">返回</a>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                    </div>
@endsection

@section('js')
	 <script type="text/javascript">
        seajs.use(["module/custom/custom_admin", "module/common/common", "bt"]);
	</script>
@endsection