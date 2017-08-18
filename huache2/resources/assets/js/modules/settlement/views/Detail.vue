<template>
    <div>
        <div class="yue-content psr">
            <span class="blue weight pl10 fl">结算明细</span>
            <div class="clear"></div>
            <hr class="dashed">
            <div class="m-t-10"></div>
        </div>
        <div class="one-line">
            <div class="col-sm-3">
                <span>结算年月：</span>
                <span>{{settlement.year_month}}</span>
            </div>
            <div class="col-sm-3">
                <span>结算订单数：</span>
                <span>{{settlement.order_num}}</span>
            </div>
            <div class="col-sm-3">
                <span>结算总金额：</span>
                <span>￥{{settlement.money}}</span>
            </div>
        </div>
        <div class="input-one-line mb20">
            <div class="ml15 fl">
                订单号：
            </div>
            <div class="col-sm-4">
                <input type="text" v-model="search_order_id"  class="form-control">
            </div>
            <div class="col-sm-2">
                <a href="javascript:void(0);" @click="search()" class="btn btn-s-md btn-danger bt w90">查找</a>
            </div>
        </div>


        <table class="tbl tbl-time tbl-gray tac">
            <tr>
                <th width="100" class="fs16">订单号</th>
                <th width="100" class="fs16">结算金额</th>
                <th width="100" class="fs16">项目明细及金额</th>
            </tr>
            <tr v-for='item in list'>
                <td>{{item.order_id}}</td>
                <td>{{item.money}}</td>
                <td>{{item.description}}</td>
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
                settlement:{ },
                list : [],
                currentPage: 1,
                pageNo     : 1,
                file_count : 0,
                //PulseLoader props
                loading_a :false,
                size    :"15px",
                color   :'#ff994c',
                //search
                search_order_id: '',
            }
        },
        created(){
            Settlement.initialize()
            this.settlement = Settlement.state.settlement
            this.getList(1)
        },
        methods: {
                getList(page,order_id) {
                    this.loading_a  = true
                    let _json = {
                        settlement_id: this.settlement.id
                    }
                    if(order_id){
                        _json ={
                            settlement_id: this.settlement.id,
                            order_id : order_id
                        }
                    }
                    let url = '/dealer/settlement/get_settlement_detail'
                    if(page>1)
                    {
                        url = '/dealer/settlement/get_settlement_detail?page='+page
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
                search() {
                    this.search_order_id =  this.search_order_id.replace(/,/g,"")
                    if(this.search_order_id == "") this.getList(this.currentPage);
                    else this.getList(this.currentPage,this.search_order_id);
                }

        },

    }
</script>