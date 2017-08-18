define(function (require, exports, module) {
    //根目录{webhtml}自行配置
    //开发环境中请把每个$.ajax或ajaxForm中的error方法删除
    require("/webhtml/common/js/module/head")
    require("/webhtml/common/js/module/left") 
    require("/webhtml/common/js/vendor/jquery.form") 
    require("/webhtml/custom/js/module/custom/dropdown-components")
    require("./pagination-components")
   
    var app = new Vue({
        el: '.user-content',
        data: {
             vouchersStatusList:[
                 {id:1,name:"\u53ef\u7528\u4ee3\u91d1\u5377"},
                 {id:2,name:"\u5df2\u7528\u4ee3\u91d1\u5377"},
                 {id:3,name:"\u8fc7\u671f\u4ee3\u91d1\u5377"}
             ],
             volumeTypeList:[
                 {id:1,name:"\u901a\u7528\u5377"},
                 {id:2,name:"\u54c1\u7c7b\u5377"}  
             ],
             pageinfo:{
                current:1,
                showItem:5,
                allPage:10
             },
             vouchersStatus:1,
             vouchersStatusName:'',
             volumeTypeId:0,
             volumeTypeName:'',
             vouchersList:[]
        },
        mounted:function(){
            this.getVouchersList()
        },
        methods:{
            getVouchersList:function(){
                var _this = this
                $.ajax({
                     type: "POST",
                     url : "/member/vouchers",
                     data: {
                         vouchersStatus:_this.vouchersStatus,           //代金券类型
                         vouchersStatusName:_this.vouchersStatusName,   //代金券名称
                         volumeTypeId:_this.volumeTypeId,               //卷类型ID
                         volumeTypeName:_this.volumeTypeName,           //卷类型名称
                         current:_this.pageinfo.current,                //当前页数
                         token:$("input[name='token']").val()
                     },
                     dataType: "json",
                     success: function(data){
                         _this.vouchersList = data.list
                         _this.pageinfo.allPage = data.pageCount        //总页数
                     },
                     error: function(){
                         _this.vouchersList = [
                            {price:50,typeId:1,typeName:'通用卷',time:'2017.2.28-2017.3.30',category:'全品类',sn:"9276291015",ordersn:"HC2542522",usetime:'2017-3-29'},
                            {price:50,typeId:2,typeName:'品类卷',time:'2017.2.28-2017.3.30',category:'仅可购买宝马全系商品',sn:"9276295896",ordersn:"HC2542522",usetime:'2017-3-29'},
                            {price:50,typeId:2,typeName:'品类卷',time:'2017.2.28-2017.3.30',category:'仅可购买吉利＞帝豪GS＞2016款 优雅版 1.8L DCT领尚型商品',sn:"9276291589",ordersn:"HC2542522",usetime:'2017-3-29'},
                            {price:50,typeId:1,typeName:'通用卷',time:'2017.2.28-2017.3.30',category:'全品类',sn:"9276291789",ordersn:"HC2542522",usetime:'2017-3-29'},
                            {price:50,typeId:1,typeName:'通用卷',time:'2017.2.28-2017.3.30',category:'全品类',sn:"9276291136",ordersn:"HC2542522",usetime:'2017-3-29'},
                            {price:50,typeId:1,typeName:'通用卷',time:'2017.2.28-2017.3.30',category:'全品类',sn:"9276291985",ordersn:"HC2542522",usetime:'2017-3-29'}
                         ]
                         var _random = Math.random()
                         _this.pageinfo.allPage = Math.round(_random * 10)       
                     },
                }) 
            },
            getPageIndex:function(value){
                this.pageinfo.current = value
                this.getVouchersList()
            },
            getVouchers:function(id,value){
                this.vouchersStatus   = id
                this.vouchersStatusName = value
                this.getVouchersList()
            },
            getVolumeType:function(id,value){
                this.volumeTypeId   = id
                this.volumeTypeName = value
                this.getVouchersList()
            }
        },
        watch:{
            
        }

    }) 
     
  
    module.exports = {
        initVouchersStatusList:function(array){
            app.vouchersStatusList = array
        },
        initVolumeTypeList:function(array){
            app.volumeTypeList = array
        }
    }
})
