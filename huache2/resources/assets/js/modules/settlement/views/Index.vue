<template>
    <div class="">
        <div class="yue-content psr">
            <span class="juhuang weight pl10 fl pre-fix w300">结算记录</span>
            <span>     结算文件剩余数：<span  v-if="file_count>=0">{{file_count}}</span></span>
            <span class="w_warning" v-if="file_count>0 && file_count<3">（存货告急，请速补寄！）</span>
            <span class="w_error" v-if="file_count===0 ">（文件不足，无法结算！）</span>
                <router-link to="/dealer/prices/settlement/mail_history" class="fr btn btn-danger sure btn-auto">
                    寄送记录
                </router-link>
                <router-link to="/dealer/prices/settlement/mail_file" class="fr btn btn-danger sure btn-auto">
                   > 寄文件
                </router-link>
            <div class="clear"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
        </div>
        <table class="tbl tbl-time tbl-gray tac">
            <tr>
                <th width="100" class="fs16">结算年月</th>
                <th width="160" class="fs16">预计结算总金额</th>
                <th width="160" class="fs16">结算总金额</th>
                <th width="160" class="fs16">服务费收入</th>
                <th width="160" class="fs16">服务费收入时间</th>
                <th width="160" class="fs16">操作</th>
            </tr>
            <tr v-for='item in list'>
                <td>{{item.year_month}}</td>
                <td class="text-right">￥{{item.predict_money}}</td>
                <td class="text-right">￥{{item.money}}</td>
                <td class="text-right">￥{{item.service_income}}</td>
                <td>{{item.service_income_at}}</td>
                <td>
                    <a href="javascript:void(0);" @click="go_detail(item)" class="juhuang">查看结算明细</a>
                </td>
            </tr>
        </table>
        <pulse-loader :loading="loading_a" :color="color" :size="size" style="margin-left:40%;"></pulse-loader>
        <div class="pageinfo ml200">
            <pagination :page-no="pageNo" :current="currentPage" @page-change="pageChange"></pagination>
        </div>
        <div class="clear "></div>
        <div class="m-t-10" v-for="i in 5"></div>
        <table>
            <tr>
                <td width="80" valign="top">温馨提示：</td>
                <td>
                    <p>1.您可查询最近一年的结算明细记录。</p>
                    <p>2.每个自然月月底，对当月累计收入自动结算，税后收入在次月财务手续完成后自动进入可提现余额。</p>
                </td>
            </tr>
        </table>
    </div>
</template>

<script type="text/javascript">
    import { post, get } from '../../../helpers/api'
    import pagination from '../components/Pagination.vue'
    import PulseLoader from 'vue-spinner/src/PulseLoader.vue'
    import Settlement from '../store/settlement'

    export default {
        components: {
            pagination,
            PulseLoader
        },
        data(){
            return {
                list : [],
                currentPage: 1,
                pageNo     : 1,
                file_count : '0',
                //PulseLoader props
                loading_a :false,
                size    :"15px",
                color   :'#ff994c',
                //phone
                phone:''
            }
        },
        created(){
            this.getList(1)
            this.getFileCount()
        },
        methods: {
                getList(page) {
                    this.loading_a  = true
                    let _json = {}
                    let url = '/dealer/settlement/get_settlement'
                    if(page>1)
                    {
                        url = '/dealer/settlement/get_settlement?page='+page
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
                            if(res.data.user_phone)
                                this.phone = res.data.user_phone
                        })
                        .catch((err)=>{

                        })
                },
                getFileCount() {
                    post('/dealer/settlement/get_file_count',{})
                        .then((res)=>{
                            if(res.data){
                                this.file_count = res.data.file_number
                            }
                        })
                        .catch((err)=>{
                            if(err.response.status === 422) {
                                //错误处理
                                this.file_count = 0
                            }
                        })
                },
                pageChange(val) {
                    this.currentPage  = val
                    this.getList(val)
                },
                go_detail(item){
                    Settlement.set(item)
                    this.$router.push("/dealer/prices/settlement/detail/"+item.id);
                },
                sendSms(type){ //发送短信 TODO  短信模板未添加，发送不了
                    if(this.phone){
                        get("/member/sendSms?phone="+this.phone+"&template_code="+type)
                    }
                }

        },
        watch: {
            file_count(val)
            {
                if(val>0 && val <=2){
                  //发送短信
                    this.sendSms('78715080')
                }
                if(val===0){
                    this.sendSms('78545076')
                }
            }
        }

    }
</script>