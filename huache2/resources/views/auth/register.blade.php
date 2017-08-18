@extends('HomeV2._layout.base')
@section('css')
    <link href="{{asset('themes/reg.adv.css')}}" rel="stylesheet" />
@endsection
@section('nav')
    @include('HomeV2._layout.not_login')
@endsection
@section('content')

    <div class="container m-t-86 pos-rlt" id="vue">
        <div class="wapper">
            <div class="hd reg-box">
                <div class="title">快捷注册</div>
                <div class="form"  v-cloak>
                    <table class="reg-form-tbl tac" style="width: 830px;margin:0 auto">
                        <tr>
                            <td width="203" align="right">手机号</td>
                            <td width="445" align="left">
                                <input id="txtPhone" maxlength="11" @blur.stop="checkPhone"  @focus="setPhoneStatus" v-model="form.phone" :value="form.phone" name="phone" type="text" placeholder="中国大陆手机为11位数字" :class="{'form-input':true,'form-input-def':true,'error-bg':checkPhoneCode > 1}" />
                            </td>
                            <td align="left" width="182">
                                <span v-cloak style="width:270px;top:96px;margin-left:-4px;" class="form-error ib psa" v-show=" checkPhoneCode == 4">
                                    <i class="reg-icon reg-icon-error fl"></i>
                                    <span class="fl mt7 fs14">格式有误，请重新输入~</span>
                                    <span class="clear"></span>
                                </span>
                                <span v-cloak class="reg-icon reg-icon-success" v-show="checkPhoneCode == 1"></span>
                                <span v-cloak style="width:270px;top:96px;margin-left:-4px;" class="form-error ib psa " v-show="checkPhoneCode == 2">
                                    <i class="reg-icon reg-icon-error fl"></i>
                                    <span class="fl mt7 fs14">不可注册！请更换其他号码注册～</span>
                                    <span class="clear"></span>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">设置密码</td>
                            <td align="left">
                                <div class="input-wrapper">
                                    <input v-cloak v-show="isOpenEye" @blur="checkPwd" @focus="setPwdStatus" maxlength="20" v-model="form.pwd" :value="form.pwd" placeholder="6-20位数字、符号或字母（区分大小写）组合" name="pwd" type="text" :class="{'form-input':true,'form-input-def':true,'error-bg':pwdInputStatus==2 || pwdInputStatus==3}" />
                                    <div v-show="!isOpenEye" class="psr">
                                        <input v-cloak v-model="form.pwd" @blur="checkPwd" @focus="setPwdStatus" maxlength="20" :value="form.pwd" name="pwd" type="password" :class="{'form-input':true,'form-input-def':true,'error-bg':pwdInputStatus==2 || pwdInputStatus==3}"/>
                                        <span v-show="!isPwdInput" class="pwd-see" @click="pwdSee">6-20位数字、符号或字母（区分大小写）组合</span>
                                    </div>
                                    <div class="icon-wrapper">
                                        <span v-cloak v-show="isOpenEye" @click="openEye" class="reg-icon reg-icon-eye-on pointer"></span>
                                        <span v-cloak v-show="!isOpenEye" @click="openEye" class="reg-icon reg-icon-eye-off pointer"></span>
                                    </div>
                                </div>
                                <div class="pwd-strong pos-fixed hide" :class="{show:form.pwd.length && pwdInputStatus != 1 && pwdInputStatus!=3}" v-show="pwdInputStatus == 0 || pwdInputStatus == 2">
                                    <label>密码强度：</label>
                                    <span :class="{'p-s-less':true, pwdcur:pwdStrongStatus==1}">低</span>
                                    <span :class="{'p-s-normal':true, pwdcur:pwdStrongStatus==2}">中</span>
                                    <span :class="{'p-s-max':true, pwdcur:pwdStrongStatus==3}">高</span>
                                </div>
                                
                            </td>
                            <td>
                                <span v-cloak class="form-error ib fl" v-show="pwdInputStatus==3">
                                    <i class="reg-icon reg-icon-error fl"></i>
                                    <span class="fl mt7 fs14">不足6位</span>
                                    <span class="clear"></span>
                                </span>
                                <span v-cloak class="reg-icon reg-icon-success fl" v-show="pwdInputStatus == 1"></span>
                            </td>
                        </tr>
                        <tr v-cloak v-show="!isOpenEye">
                            <td align="right">重复密码</td>
                            <td align="left">
                                <div class="psr ib">
                                    <input @blur="checkPwd2" @focus="setPwd2Status" v-model="form.pwd2" :value="form.pwd2" maxlength="20" name="pwd2" type="password" :class="{'form-input':true,'form-input-def':true,'error-bg':pwd2InputStatus==2 || pwd2InputStatus==3}"/>
                                    <span v-show="!isPwd2Input" class="pwd-see" @click="pwdSee">再输入一遍密码</span>
                                </div>
                                
                            </td>
                            <td>
                                <span v-cloak class="form-error ib fl" v-show="pwd2InputStatus==3">
                                    <i class="reg-icon reg-icon-error fl"></i>
                                    <span class="fl mt7 fs14">两次密码输入不一致</span>
                                    <span class="clear"></span>
                                </span>
                                <span v-cloak class="reg-icon reg-icon-success fl" v-show="pwd2InputStatus == 1"></span>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">验证码</td>
                            <td align="left">
                                <div class="psr">
                                    <input maxlength="6"  @blur.stop-1="checkCode" @focus="initCodeStatus" v-model="form.code" :value="form.code"  placeholder="6位数字" name="code" type="text" :class="{'form-input':true, 'form-input-code':true,'error-bg':checkCodeStatus == 3 || isCodeError}" />
                                    <button :disabled="!isCheckPass && !isSendCode" v-cloak @click="getCode" type="submit" class="btn btn-s-md btn-danger w120 ib ml60 btn-code">
                                        <span v-cloak class="red" v-show="isSendCode">@{{countDownTime}}s</span>@{{sendCodeTxt}}
                                    </button> 
                                </div>
                            </td>
                            <td>
                                
                            </td>
                        </tr>
                        
                    </table>

                    
                    
                    <div class="reg-error-psa">

                        <p class="text-center mt20" v-show="!isAgree && isReg">
                            <span v-cloak class="form-error ib" >
                                <i class="reg-icon reg-icon-error fl"></i>
                                <span class="fl mt5">成功注册请接受注册协议～</span>
                                <span class="clear"></span>
                            </span>
                        </p>

                        <div v-cloak class="text-center mt20 notice-count notice-count-long" v-show="isCodeError  && !isFreeze">
                            <span class="form-error ib">
                                <i v-cloak class="reg-icon reg-icon-warn fl"></i>
                                <span class="fl mt5"><span class="mt7 fs14 red ml5">验证码有误，请重新输入～</span>
                                <span class="clear"></span>
                                </span>
                            </span>
                        </div>
                        <div class="text-center mt20 notice-count notice-count-long" v-show="noticeCount == 4">
                            <div v-cloak class="form-error block" > 
                                <span class="fl mt5 ib notice-info-long gray">短信验证码已经发出，请注意查收短信，今日还可获取<span class="red">1次</span>验证码！</span>
                                <div class="clear"></div>
                            </div>
                        </div>

                        <div class="text-center mt20 notice-count notice-count-none" v-show="noticeCount == 5 && !isFreeze">
                            <div v-cloak class="form-error block" > 
                                <span class="fl mt5 ib notice-info-long gray">短信验证码已经发出，请注意查收短信，今日注册短信验证码发送次数已达上限！</span>
                                <div class="clear"></div>
                            </div>
                        </div>


                        <div class="text-center mt20 notice-count notice-count-none" v-show="isFreeze">
                            <span v-cloak class="form-error ib" >
                                <i class="reg-icon reg-icon-warn fl mt23"></i>
                                <span class="ml5 fl mt5 ib notice-info-long">对不起，今日申请而未使用的注册验证码次数过多，该手机号注册功能已被保护，请明日再试！或者您可改用其他手机继续注册。如有紧急需求可联系华车客服，谢谢～</span>
                                <span class="clear"></span>
                            </span>
                        </div>
                    </div>
                    <div class="clear"></div>

                    <div class="m-t-10"></div>

                    <p class="fs14 text-center mt20">
                        <input type="checkbox" v-model="agree" value="1" />
                        <span class="ml5">同意<a href="javascript:;" @click="service"  class="blue">《注册协议》</a></span>
                    </p>
                    <p class="tac mt-20">
                        <button @click="send" type="submit" class="btn btn-danger btn-reg">注 册</button>
                    </p>
                </div>
            </div>
        </div>
        <br><br><br>
        
        <input id="_token" type="hidden" value="{{ csrf_token() }}" />
        <popup v-cloak ref="popup">
            <header slot="title">华车平台用户注册协议</header>
            <h1 slot="header">华车平台用户注册协议</h1>
            <div class="service-main" slot="main">
                <p class="tar">版本生效时间：2017年07月</p>
                <p>本协议是用户（以下称为“用户”或简称为“您”）与华车平台网站（网址：www.hWache.com，以下简称为“本站”）所有者（苏州华车网络科技有限公司，以下简称为“华车平台”）之间就华车平台网站服务等相关事宜所订立的契约，请您仔细阅读本注册协议，若您勾选“同意《注册协议》”、并点击“注册”按钮，本协议即构成对双方有约束力的法律文件。</p>
                <p class="weight fs16">第1条 本协议条款的确认和接纳</p>
                <p>1.1本站的各项电子服务的所有权和运作权归华车平台所有。您同意所有注册协议条款并完成注册程序，才能成为本站的正式用户。您确认：本协议条款是处理双方权利义务的契约，始终有效（法律另有强制性规定或双方另有特别约定的，依其规定）。</p>
                <p>1.2您勾选同意本协议的，即视为您确认本人已具有享受本站服务相应的权利能力和行为能力，能够独立承担法律责任。</p>
                <p>1.3如果您在18周岁以下，只能在父母或监护人的监护指导下才能使用本站。</p>
                <p>1.4本站保留在中华人民共和国大陆地区施行之法律允许的范围内独自决定拒绝服务、清除或编辑内容、取消订单、关闭订单的权利。</p>

                <p class="weight fs16">第2条 服务前提</p>
                <p>2.1本站通过互联网依法为用户提供互联网信息等服务，您在完全同意本协议及本站规定的情况下，方有权使用本站的相关服务。</p>
                <p>2.2用户必须自行准备如下设备和承担如下开支：</p>
                <p>（1）上网设备，包括但不限于电脑或者其他上网终端、调制解调器及其他必备的上网装置；（2）上网开支，包括但不限于网络接入费、上网设备租用费、手机流量费等。</p>

                <p class="weight fs16">第3条 用户账户</p>
                <p>3.1您应自行诚信向本站提供注册资料，您同意所提供的注册资料真实、准确、完整、合法有效，注册资料如有变动的，您应及时更新注册资料。如果您提供的注册资料不合法、不真实、不准确、不详尽的，您需承担因此引起的相应责任及后果，并且华车平台保留终止您使用本站各项服务的权利。</p>
                <p>3.2您注册成功后，将可使用您的注册手机或电子邮箱、配合预设密码登录账户。您应谨慎合理地保存、使用密码，可以根据本人需要和本站规定改变您的密码。您若发现任何非法使用您的账户或存在安全漏洞的情况，请立即通知本站并向公安机关报案。</p>
                <p class="weight">3.3您不得将在本站注册获得的账户借给他人使用，否则您应承担由此产生的全部责任（包括与实际使用人一起承担的连带责任）。鉴于网络服务的特殊性，本站无义务审核是否是您本人使用的该账户和密码，仅审核该账户和密码是否与数据库中留存的一致，任何人只要输入的账户信息及密码与数据库中留存的一致，即可登录该账户并使用本站所提供的各类服务，并且相关使用行为视为您本人的操作行为，产生的结果和责任须由您承担。即使事后您认为您的账户登录及后续操作行为并非您本人所为，本站亦不承担与此有关的任何责任。</p>
                <p class="weight">3.4您同意，华车平台有权使用用户的注册信息、密码等信息，登录进入用户注册账户，进行证据保全，包括但不限于公证、见证等。</p>

                <p class="weight fs16">第4条 用户依法言行义务 </p>
                <p>本协议依据国家相关法律法规规章制定，您同意严格遵守以下义务： </p>
                <p>（1）不得传输或发表：煽动抗拒、破坏宪法和法律、行政法规实施的言论，煽动颠覆国家政权，推翻社会主义制度的言论，煽动分裂国家、破坏国家统一的的言论，煽动民族仇恨、民族歧视、破坏民族团结的言论； </p>
                <p>（2）从中国大陆向境外传输资料信息时必须符合中国有关法规； </p>
                <p>（3）不得利用本站从事洗钱、窃取商业秘密、窃取个人信息等违法犯罪活动； </p>
                <p>（4）不得干扰本站的正常运转，不得侵入本站及国家计算机信息系统； </p>
                <p>（5）不得传输或发表任何违法犯罪的、骚扰性的、中伤他人的、辱骂性的、恐吓性的、伤害性的、庸俗的，淫秽的、不文明的等信息资料； </p>
                <p>（6）不得传输或发表损害国家社会公共利益和涉及国家安全的信息资料或言论； </p>
                <p>（7）不得教唆他人从事本条所禁止的行为； </p>
                <p>（8）不得利用在本站注册的账户进行牟利性经营活动； </p>
                <p>（9）不得发布任何侵犯他人著作权、商标权等知识产权或合法权利的内容； </p>
                <p>您应不时关注并遵守本站不时公布或修改的各类合法规则规定。 </p>
                <p class="weight">本站保有删除站内各类不符合法律政策或不真实的信息内容而无须通知用户的权利。 若您未遵守以上规定的，本站有权作出独立判断并采取暂停或关闭用户账号等措施，违反法律法规的，由您承担相关法律责任。</p>

                <p class="weight fs16">第5条 商品信息 </p>
                <p>本站上包括车辆详情、车源范围、服务条件等在内的商品信息，在上架时段随时都有可能发生变动，本站不作特别通知。由于网站上商品信息的数量极其庞大，虽然本站会尽最大努力保证您所浏览商品信息的准确性，但由于厂商临时变更配置、以及众所周知的互联网技术因素等客观原因存在，本站网页显示的信息可能会有一定的滞后性或差错，对此情形您知悉并理解；华车平台欢迎纠错，并会视情况给予纠错者一定的奖励。 </p>

                <p class="weight fs16">第6条 交易说明</p>
                <p class="weight">6.1作为互联网信息服务提供者及第三方交易平台，本站将汽车品牌授权经销商的车辆销售信息汇集，所展示商品信息内容系授权经销商委托发布，其真实性、准确性均由委托销售方承担责任。</p>
                <p class="weight">6.2在本站所示的加信宝购车保障服务，由特定服务方提供并承担责任，其保障内容和范围，亦在商品信息详情该服务方的《服务协议》中详细告知，您需要同意相关《服务协议》方可完成下单。</p>
                <p class="weight">6.3您接受本协议表明，您不可撤销地授权本站根据上述特定服务方交易指令对您账户资金进行扣划、冻结或其他处置操作，无论该操作是否对您有利。并且您同意，即使您与销售方、服务方在交易确定和执行过程中发生任何异议、争议、纠纷或法律事件，都将免除华车平台承担连带责任。</p>

                <p class="weight fs16">第7条 订单和账户资金</p>
                <p>7.1 您在本站登录后可随时查询您的订单状态和资金状态。您理解并同意，您所查询到的任何信息仅作为参考，而不作为相关操作或交易的证据或依据，如该类信息与本站实际记录存在任何不一致，应以华车平台提供并确认过的书面记录为准。</p>
                <p>7.2 您可使用本站推荐的支付方式对账户资金进行转入或提现。您从银行、第三方支付机构等支付渠道向本站转入资金，到账时间和金额以本站实际收到确认结果为准。您的提现，根据银行和税务相关监管要求，原则上只能原路退还，据此本站形成用户提现规则，并在合法合规前提下向您推荐可操作的提现路线。<b>您同意，按照本站推荐的提现路线方案办理提现，不对提现路线提出任意变更要求</b>。银行渠道办理提现需要您配合提供开户行信息，如您的收款银行账户信息不全，可能无法办理或产生额外费用，相应后果须由您承担。此外，受部分支付渠道仅在工作日进行资金划转的现状所限，本站不对提现服务的资金到账时间做任何承诺，也不承担与此相关的责任，包括但不限于由此产生的费用、利息等损失。</p>
                <p class="weight">7.3您一旦提交转入资金、支付订单款项或申请提现的操作指令，即表示不可撤销地授权本站进行相关操作，且该操作是不可逆转的，您不能以任何理由要求取消。</p>
                <p class="weight">7.4您同意，对您账户中的资金本站无须向您支付利息。</p>
                <p class="weight">7.5您同意，如您交易的服务方就您的订单履约提出异议而向本站发出止付指令，本站保留收到撤销止付指令前暂缓为您办理账户资金实际操作的权利。</p>

                <p class="weight fs16">第8条 所有权及知识产权条款</p>
                <p class="weight">8.1您一旦接受本协议，即表明您主动将其在任何时间段在本站发表的任何形式的信息内容（包括但不限于客户评价、客户咨询、各类话题文章等信息内容）的财产性权利等任何可转让的权利，如著作权财产权（包括但不限于：复制权、发行权、出租权、展览权、表演权、放映权、广播权、信息网络传播权、摄制权、改编权、翻译权、汇编权以及应当由著作权人享有的其他可转让权利），全部独家且不可撤销地转让给华车平台所有，您同意华车平台有权就任何主体侵权而单独提起诉讼。</p>
                <p class="weight">8.2本协议已经构成《中华人民共和国著作权法》第二十五条（条文序号依照2011年版著作权法确定）及相关法律规定的著作财产权等权利转让书面协议，其效力及于用户在本站上发布的任何受著作权法保护的作品内容，无论该内容形成于本协议订立前还是本协议订立后。</p>
                <p class="weight">8.3您同意并已充分了解本协议的条款，承诺不将已发表于本站的信息，以任何形式发布或授权其他主体以任何方式使用（包括但不限于在各类网站、媒体上使用）。</p>
                <p class="weight">8.4华车平台是本站的制作者，拥有此网站内容及资源的著作权等合法权利，受国家法律保护，有权不时地对本协议及本站的内容进行修改，并在本站张贴，无须另行通知用户。在法律允许的最大限度范围内，华车平台对本协议及本站内容拥有解释权。</p>
                <p class="weight">8.5除法律另有强制性规定外，未经华车平台明确的特别书面许可，任何单位或个人不得以任何方式非法地全部或部分复制、转载、引用、链接、抓取或以其他方式使用本站的信息内容。否则，华车平台有权追究其法律责任。</p>
                <p>8.6本站所刊登的资料信息（诸如文字、图表、标识、按钮图标、图像、声音文件片段、数字下载、数据编辑和软件），均是华车平台或其内容提供者的财产，受中国和国际版权法的保护。本站上所有内容的汇编是华车平台的排他财产，受中国和国际版权法的保护。本站上所有软件都是华车平台或其关联公司或其软件供应商的财产，受中国和国际版权法的保护。</p>

                <p class="weight fs16">第9条 责任限制及不承诺担保 </p>
                <p class="weight">9.1除非另有明确的书面说明，本站及其所包含的或以其他方式通过本站提供给您的全部信息、内容、材料、产品（包括软件）和服务，均是在“按现状”和“按现有”的基础上提供的。本站对由本协议引起的任何间接、偶然、派生因素造成的损失均不承担责任。 </p>
                <p class="weight">9.2除非另有明确的书面说明，华车平台不对本站的运营及其包含在本网站上的信息、内容、材料、产品（包括软件）或服务作任何形式的、明示或默示的声明或担保（根据中华人民共和国法律另有规定的以外）。华车平台不担保本站所包含的或以其他方式通过本站提供给您的全部信息、内容、材料、产品（包括软件）和服务、其服务器或从本站发出的电子信件、信息没有病毒或其他有害成分。 </p>
                <p class="weight">9.3如因不可抗力或其他本站无法预测或控制的第三方原因使本站无法正常使用，导致网上交易无法完成或丢失有关的信息、记录等，华车平台并不承担违约责任，但会采取合理行动积极恢复服务正常、尽力减少给您造成的影响。不可抗力和第三方原因包括但不限于：</p>
                <p>（1）因自然灾害、罢工、暴乱、恐怖袭击、战争、政府行为、司法行政命令等不可抗力因素；</p>
                <p>（2）因电力供应故障、通讯网络故障、支付机构问题中断服务等公共服务因素或第三方因素；</p>
                <p>（3）在本站已尽善意管理的情况下，因常规或紧急的设备与系统维护、设备与系统故障、网络信息与数据安全等因素。</p>
                <p>9.4本站可能会提供与其他国际互联网网站或资源进行链接，对于前述网站或资源是否可以利用，华车平台不予担保。因使用或依赖上述网站或资源所产生的损失或损害，华车平台也不承担任何责任。</p>

                <p class="weight fs16">第10条 协议更新及用户关注义务 </p>
                <p>10.1根据国家法律法规变化及网站运营需要，华车平台有权对本协议条款不时地进行修改，修改后的协议一旦被张贴在本站上即生效，并代替原来的协议，您可随时登录查阅最新协议。</p>
                <p class="weight">10.2您有义务不时关注并阅读最新版的协议及本站公告。如您不同意更新后的协议，可以且应立即停止接受本站依据本协议提供的服务；如您继续使用本站所提供服务的，即视为同意更新后的协议。</p>
                <p>10.3本站有可能以温馨提示、特别提醒等形式，向您说明您在使用本站服务时应当履行的本协议所约定的义务之外的其他义务，您亦应当仔细阅读并全面履行。上述约定如果与本协议相互冲突或者矛盾的，以上述约定为准；上述约定未涉及的内容，仍适用本协议。</p>
                <p>10.4华车平台建议您在使用本站之前仔细阅读本协议及本站公告，如果本协议中任何一条被判断为废止、无效或因任何理由不可执行，则该条应视为可分的且并不影响任何其余条款的有效性和可执行性。</p>

                <p class="weight fs16">第11条 用户信息保护</p>
                <p>11.1您在本站进行浏览、购车等活动时，涉及真实姓名、银行账户、支付账号、通信地址、注册手机、联系电话、电子邮箱等个人信息和您保存在本站的非公开内容，本站将予以严格保密，除非有下列情况：</p>
                <p>（1）获得您的授权许可；</p>
                <p>（2）根据有关法律法规或主管部门的要求；</p>
                <p>（3）出于维护广大用户及社会公众根本利益的需要；</p>
                <p>（4）出于维护华车平台合法权益的需要；</p>
                <p>11.2在包括但不限于下列情况之下，您同意授权华车平台使用您的个人信息：</p>
                <p>（1）您在本站与服务方达成服务协议后，可以与有关服务方分享您的信息以便其及时为您服务；</p>
                <p>（2）可以将您的信息与第三方信息匹配（例如连通您的即时通讯工具），以便更加高效地为您服务；</p>
                <p>（3）为提升为您提供更好服务的能力，本站统计服务使用状况，与合作伙伴、广告商或其他关联第三方分享有关统计信息，但这些信息中不含关于真实身份、银行账户等隐私内容。</p>
                <p class="weight">11.3您同意，华车平台拥有通过邮件、短信、电话、用户端信息推送等形式，向在本站注册用户发送和交易有关的通知信息、或本站认为您可能感兴趣的其他信息的权利。您应确保相关联络方式有效可用，若因此造成信息传送不畅或延误给您带来损失，您须自行承担责任。</p>

                <p class="weight fs16">第12条 法律适用与管辖 </p>
                <p>12.1本协议的订立、生效、执行、修订、补充、解释、终止及争议解决均适用中华人民共和国大陆地区之有效法律（但不包括其冲突法规则）。如发生本协议与适用之法律相抵触，则这些条款将完全按法律规定重新解释，而其他有效条款继续有效。 </p>
                <p>12.2如缔约方就本协议内容或其执行发生任何争议，双方应尽力友好协商解决；协商不成时，任何一方均可向被告所在地人民法院提起诉讼。</p>

                <p class="weight fs16">第13条 服务的变更、中断或终止 </p>
                <p>13.1鉴于网络服务的特殊性和经营主体的自主性，华车平台有权随时变更、中断或终止部分或全部的网络服务而无需对用户或任何第三方承担责任，但本站将尽可能事先进行通知，并尽可能给您预留时间以便处理相关权益。</p>
                <p>13.2华车平台有权根据业务调整情况将本协议项下全部或部分权利义务转移给关联公司，此种情况将会提前30天以本站公告形式发布。</p>

                <p class="weight fs16">第14条 其他</p>
                <p>14.1华车平台网站所有者是指在政府部门依法许可或备案的华车平台网站经营主体。</p>
                <p>14.2华车平台尊重广大用户和消费者的合法权利，本协议及本站上发布的各类规则、声明等其他内容，均是为了更好、更便利地为用户和消费者提供服务。本站欢迎广大用户和社会各界提出意见和建议，华车平台将虚心接受并适时修改本协议及本站上的各类规则。</p>
                <p>14.3本协议内容中<b>加粗</b>方式显著标识的条款，请您务必着重阅读。</p>
                <p>14.4您勾选“同意《注册协议》”、并点击“注册”按钮，即视为您完全接受本协议并成为注册用户，在勾选和点击之前，请您再次确认已完全理解并同意本协议的全部内容。</p>
            </div>
        </popup>

    </div>

@endsection



@section('login')
  @include('HomeV2._layout.login')
@endsection

@section('js')
    <script type="text/javascript">
        seajs.use(["vendor/vue","module/reg/reg.adv"],function(a,b,c){
            b.init("{{ route('member.checkUser') }}","{{ route('member.sendSms') }}","{{ route('member.checkSms') }}","{{ route('user.postReg') }}","{{ route('reset.success',['type'=>'reg']) }}");
            b.initTime("{{ endToDayTime() }}");
        })
    </script>
@endsection