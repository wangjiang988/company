<template>
    <div >
        <div class="yue-content psr">
            <span class="blue weight pl10 fl">结算文件寄送</span>
            <div class="clear"></div>
            <hr class="dashed">
            <div class="m-t-10"></div>
        </div>

        <div class="yue-content psr">
            <div class="col-sm-offset-2 col-sm-10">
                <div class="mb10">根据税务规定，您的收入提取需要使用结算文件正本，您的建议操作步骤如下：</div>
                <div>1.下载 ——打印——签名（一次可准备多份）；
                    <a href="" class="juhuang">>  去下载</a>
                </div>
                <div>2.用快件寄给华车（注明您的用户名和姓名）；</div>
                <div>3.提交寄件信息通知华车查收。</div>
            </div>

        </div>
        <div class="clear"></div>
        <p class="pre-fix"><span class="weight mt10  pl10">收件人信息</span></p>
        <div class="yue-content psr">
            <div class="col-sm-offset-1 col-sm-11">
                <div class="one-line">
                    <div class="col-sm-3 text-right">邮寄地址：</div>
                    <div class="col-sm-9 text-left">江苏省苏州高新区竹园路209号苏州创业园2号楼2205室</div>
                </div>
                <div class="one-line">
                    <div class="col-sm-3 text-right">邮编：</div>
                    <div class="col-sm-9 text-left">215011</div>
                </div>
                <div class="one-line">
                    <div class="col-sm-3 text-right">公司名称：</div>
                    <div class="col-sm-9 text-left">苏州华车网络科技有限公司</div>
                </div>
                <div class="one-line">
                    <div class="col-sm-3 text-right">收件人：</div>
                    <div class="col-sm-9 text-left">王小姐</div>
                </div>
                <div class="one-line">
                    <div class="col-sm-3 text-right">电话：</div>
                    <div class="col-sm-9 text-left">18112552176</div>
                </div>

            </div>
        </div>
        <div class="clear"></div>

        <p class="pre-fix"><span class="weight mt10  pl10">提交寄件信息</span></p>
        <form class="form" @submit.prevent="sub()">
        <div class="yue-content psr">
            <div class="col-sm-offset-1 col-sm-11">
                <div class="input-one-line">
                    <div class="col-sm-3 text-right">文件数量：</div>
                    <div class="col-sm-6 text-left">
                        <input type="number"  :class="error.file_number?'form-control error_bg': 'form-control' "
                               @blur="check_file_number"
                               v-model="former.file_number"  placeholder="请输入整数">
                    </div>
                    <div class="col-sm-3 text-left error"  v-if="error.file_number">输入内容格式有误～</div>
                </div>
                <div class="input-one-line">
                    <div class="col-sm-3 text-right">快递名称：</div>
                    <div class="col-sm-6 text-left">
                        <input type="text" :class="error.delivery_company_name?'form-control error_bg': 'form-control' "
                               @blur="check_delivery_company_name"
                               v-model="former.delivery_company_name" placeholder="请输入">
                    </div>
                    <div class="col-sm-3 text-left error"  v-if="error.delivery_company_name">输入内容不能为空～</div>
                </div>
                <div class="input-one-line">
                    <div class="col-sm-3 text-right">运单号：</div>
                    <div class="col-sm-6 text-left">
                        <input type="text"  :class="error.delivery_number?'form-control error_bg': 'form-control' "
                               @blur="check_delivery_number"
                               v-model="former.delivery_number" placeholder="请输入">
                    </div>
                    <div class="col-sm-3 text-left error" v-if="error.delivery_number">输入内容不能为空～</div>
                </div>

            </div>
        </div>


        <div class="clear"></div>
        <div style="margin-top: 50px;"></div>
        <div class="tac">
            <button  type="submit" class="btn btn-danger fs16 baojia-submit-button">提交</button>
            <router-link to="/dealer/prices/settlement/"  class="btn btn-danger sure fs18 ml20 baojia-submit-button">返回</router-link>
        </div>
        </form>


        <!--//提交窗口-->
        <div id="subWin" :class="popup.confirm? 'popupbox popupbox-show':'popupbox'">
            <div class="popup-title"><span>寄件信息确认</span></div>
            <div class="popup-wrapper">
                <div class="popup-content">
                    <div class="m-t-10"></div>
                    <p class="fs14 pd tac" >
                        <span class="tip-tag bp0"></span>
                        <span class="tip-text mt10">您确定用{{former.delivery_company_name}}（{{former.delivery_number}}）寄了{{former.file_number}}份结算文件给华车吗？？ </span>
                    <div class="clear"></div>
                    <br>
                    <div class="m-t-10"></div>
                </div>
                <div class="popup-control">
                    <a href="javascript:;" :disabled="isProcessing"  @click="dosend" class="btn btn-s-md btn-danger fs14 do w100">确定</a>
                    <a href="javascript:;" @click="popupbox_hide" class="btn btn-s-md btn-danger fs14 do w100 sure ml50">取消</a>
                    <div class="clear"></div>
                    <div class="m-t-10"></div>
                </div>
            </div>
        </div>
        <!--成功按钮-->
        <div id="successWin" :class="popup.success? 'popupbox popupbox-show':'popupbox'">
            <div class="popup-title"><span>提交成功</span></div>
            <div class="popup-wrapper">
                <div class="popup-content">
                    <div class="m-t-10"></div>
                    <p class="fs14 pd tac" >
                        <span class="tip-tag bp0"></span>
                        <span class="tip-text mt10">恭喜您成功提交</span>
                    </p>
                </div>
                <div class="popup-control">
                    <a href="javascript:;"  @click="popupbox_hide" style="margin-bottom:10px; " class="btn btn-s-md btn-danger fs14 bt2 do w100 sure">关闭</a>
                    <p><span class="red">{{countDownNum}}</span>秒后自动关闭</p>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    <!--失败按钮-->
        <div id="errorWin" :class="popup.error? 'popupbox popupbox-show':'popupbox'">
            <div class="popup-title"><span>提交未成功</span></div>
            <div class="popup-wrapper">
                <div class="popup-content">
                    <div class="m-t-10"></div>
                    <p class="fs14 pd tac" >
                        <span class="tip-tag bp0"></span>
                        <span class="tip-text mt10">很遗憾，本次提交未成功，请重新提交！</span>
                    <div class="clear"></div>
                    <br>
                    <div class="m-t-10"></div>
                </div>
                <div class="popup-control">
                    <a href="javascript:;" @click="popupbox_hide" class="sure btn btn-s-md btn-danger fs14 do w100">确定</a>
                    <div class="clear"></div>
                    <div class="m-t-10"></div>
                </div>
            </div>
        </div>

    </div>
</template>
<script type="text/javascript">
    import { post } from '../../../helpers/api'
    export default {
        data() {
            return {
                former: {
                    file_number:0,
                    delivery_company_name:'',
                    delivery_number:''
                },
                error: {
                    file_number:false,
                    delivery_company_name:false,
                    delivery_number:false,
                },
                popup : {
                    confirm: false,
                    success:false,
                    error   :false
                },
                isProcessing:false,
                countDownNum:5
            }
        },
        methods: {
            check_file_number() {
                if(this.former.file_number<1){
                    this.error.file_number = true
                }else{
                    this.error.file_number = false
                }
            },
            check_delivery_company_name() {
                this.former.delivery_company_name = this.former.delivery_company_name.replace(/,/g,"")
                if(this.former.delivery_company_name == ''){
                    this.error.delivery_company_name = true
                }else{
                    this.error.delivery_company_name = false
                }
            },
            check_delivery_number() {
                this.former.delivery_number = this.former.delivery_number.replace(/,/g,"")
                if(this.former.delivery_number == ''){
                    this.error.delivery_number = true
                }else{
                    this.error.delivery_number = false
                }
            },
            popupbox_hide() {
                this.popup.confirm = false
                this.popup.success = false
                this.popup.error = false
            },
            valid() {
                this.check_file_number()
                this.check_delivery_number()
                this.check_delivery_company_name()
                if(this.error.file_number || this.error.delivery_number || this.error.delivery_company_name){
                    return false
                }else{
                    return true
                }
            },
            sub() {
                if(!this.valid()){
                    return false;
                }
                this.popup.confirm = true
            },
            dosend() {
                this.isProcessing = true

                post('/dealer/settlement/mail_file',this.former)
                    .then((res)=>{
                        if(res.data.succcess) {
                            this.popupbox_hide()
                            this.popup.success = true;
                            var t = setInterval(()=>{
                                if(this.countDownNum>0) {
                                    this.countDownNum--
                                } else{
                                    clearInterval(t)
                                    this.popup.success = false;
                                    //跳转到列表也
                                    this.$router.push('/dealer/prices/settlement/mail_history')
                                }
                            },1000)


                        }else{
                            this.popupbox_hide()
                            this.popup.error = true;
                        }
                        this.isProcessing = false
                    })
                    .catch((err)=>{
                        if(err.response.status === 422) {
                            this.error = err.response.data
                        }
                        this.isProcessing = false
                    })
            }


        }
    }
</script>