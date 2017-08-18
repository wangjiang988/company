<template>
    <div>
        <div class="yue-content psr">
            <span class="juhuang weight pl10 fl pre-fix w300">寄送记录</span>
            <div class="clear"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
        </div>

        <table class="tbl tbl-gray tac" >
            <tr >
                <th width="160" class="fs16">提交时间</th>
                <th width="100" class="fs16">文件数量</th>
                <th width="100" class="fs16">快递名称</th>
                <th width="160" class="fs16">运单号</th>
                <th width="90" class="fs16">状态</th>
                <th width="160" class="fs16">收到文件数量</th>
                <th width="100" class="fs16">确认时间</th>
                <th width="100" class="fs16">操作</th>
            </tr>
            <tr v-for='item in list'>
                <td>{{item.created_at}}</td>
                <td>{{item.file_number}}</td>
                <td>{{item.delivery_company_name}}</td>
                <td>{{item.delivery_number}}</td>
                <td>{{item.status_name}}</td>
                <td>{{item.confirm_file_number}}</td>
                <td>{{item.confirm_at}}</td>
                <td><a class="juhuang" href="javascript:void(0);" @click="confirm(item.id)" v-if="item.status==10">撤销</a></td>
            </tr>
        </table>
        <pulse-loader :loading="loading_a" :color="color" :size="size" style="margin-left:40%;"></pulse-loader>
        <div class="pageinfo ml200">
            <pagination :page-no="pageNo" :current="currentPage" @page-change="pageChange"></pagination>
        </div>
        <div class="clear "></div>
        <div class="m-t-10" v-for="i in 5"></div>
        <div class="tac">
            <router-link to="/dealer/prices/settlement"  class="btn btn-danger sure fs18 ml20 baojia-submit-button">返回</router-link>
        </div>

        <!--//提交窗口-->
        <div id="subWin" :class="popup.confirm? 'popupbox popupbox-show':'popupbox'">
            <div class="popup-title"><span>撤销确认</span></div>
            <div class="popup-wrapper">
                <div class="popup-content">
                    <div class="m-t-10"></div>
                    <p class="fs14 pd tac" >
                        <span class="tip-tag bp0"></span>
                        <span class="tip-text mt10">确定撤销您提交的该条寄件记录吗？ </span>
                    <div class="clear"></div>
                    <br>
                    <div class="m-t-10"></div>
                </div>
                <div class="popup-control">
                    <a href="javascript:;" :disabled="isProcessing"  @click="dosend" class="btn btn-s-md btn-danger fs14 do w100">确定</a>
                    <a href="javascript:;" @click="confirm_hide" class="btn btn-s-md btn-danger fs14 do w100 sure ml50">取消</a>
                    <div class="clear"></div>
                    <div class="m-t-10"></div>
                </div>
            </div>
        </div>
        <!--成功按钮-->
        <div id="successWin" :class="popup.success? 'popupbox popupbox-show':'popupbox'">
            <div class="popup-title"><span>撤销成功</span></div>
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
            <div class="popup-title"><span>撤销未成功</span></div>
            <div class="popup-wrapper">
                <div class="popup-content">
                    <div class="m-t-10"></div>
                    <p class="fs14 pd tac" >
                        <span class="tip-tag bp0"></span>
                        <span class="tip-text mt10">因故撤销不成功，请重新尝试～</span>
                    </p>
                    <p class="center"><span class="red">{{countDownNum}}</span>秒后自动关闭</p>
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
    import { post, get } from '../../../helpers/api'
    import pagination from '../components/Pagination.vue'
    import PulseLoader from 'vue-spinner/src/PulseLoader.vue'
    export default {
        components: {
			pagination,
            PulseLoader
		},
        data() {
            return {
                list : [],
                currentPage: 1,
                pageNo     : 1,
                popup : {
                    confirm: false,
                    success:false,
                    error   :false
                },
                popupbox : false,
                isProcessing:false,
                countDownNum:5,
                delivery_id:0, //撤销的id
                //PulseLoader props
                loading_a :false,
                size    :"15px",
                color   :'#ff994c'
            }
        },
        created(){
            this.getList(1)
        },
        methods:{
            getList(page) {
                this.loading_a  = true
                let _json = {}
                let url = '/dealer/settlement/mail_history'
                if(page>1)
                {
                    url = '/dealer/settlement/mail_history?page='+page
                }
                post(url,_json)
                    .then((res)=>{
                        let  list   =  res.data.list
                        this.list   =  list.data
                        if(list.last_page>0){
                            this.currentPage  =  list.current_page
                            this.pageNo       =  list.last_page
                        }
                        this.loading_a  = false
                    })
                    .catch((err)=>{

                    })
            },
            pageChange(val) {
                this.currentPage  = val
                this.getList(val)
            },
            confirm(id){
                this.delivery_id = id
                this.popup.confirm = true
            },
            confirm_hide () {
                this.delivery_id = 0
                this.popup.confirm = false
            },
            popupbox_hide() {
                this.delivery_id = 0
                this.popup.confirm = false
                this.popup.success = false
                this.popup.error = false
                this.popupbox = false
            },
            dosend() {
                this.isProcessing = true
                post('/dealer/settlement/cancel_mail',{id: this.delivery_id })
                    .then((res)=>{
                        if(res.data.success) {
                            this.popupbox_hide()
                            this.popup.success = true
                            this.popupbox = true
                            var t = setInterval(()=>{
                                if(this.countDownNum>0) {
                                    this.countDownNum--
                                } else{
                                    clearInterval(t)
                                    this.popup.success = false
                                    this.popupbox = false
                                }
                            },1000)

                        }else{
                            this.popupbox_hide()
                            this.popup.error = true;
                            this.popupbox = true
                            var t = setInterval(()=>{
                                if(this.countDownNum>0) {
                                    this.countDownNum--
                                } else{
                                    clearInterval(t)
                                    this.popup.error = false;
                                    this.popupbox = false
                                }
                            },1000)
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
        },
        watch: {
            popupbox(val) {
                if(!val){
                    this.getList(this.currentPage)
                }else{
                    this.countDownNum = 5 //重置计数器
                }

            }
        }
    }
</script>