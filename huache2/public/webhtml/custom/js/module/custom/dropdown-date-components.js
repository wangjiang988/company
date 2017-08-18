define(function (require, exports, module) {

	Vue.component("drop-down-date", {
        props:["defValue","isHighLight","minDate","maxDate"],
        template: `
            <div class="btn-group fl" >
                <div class="btn btn-default dropdown-toggle btn-dropdown-default btn-dropdown-date">
                    <span class="dropdown-label" @click.stop.prevent="display" :class="{juhuang:isHighLight && isSelect && defValue != def}"><span>{{def}}</span></span>
                    <span class="caret"></span>
                </div>
            </div>
        `,
        data: function() {
            return { 
                show:!1,
                def:"",
                classList:[],
                isSelect:!1,
                datePicker:null
            }
        },
        computed: {
            
        },
        mounted:function(){
        	 this.def = this.defValue
           
        },
        methods: {
            display:function(event){
                //console.log(event.target)
                var _this = this
                if (this.datePicker) {
                  this.datePicker
                  this.datePicker
                }
                else{
                    this.datePicker = WdatePicker({el:event.target,$crossFrame:false,dateFmt:'yyyy年MM月dd日',realDateFmt:'yyyy年MM月dd日',minDate:this.minDate,maxDate:this.maxDate,startDate:this.startDate,errDealMode:2,onpicking:function(dp){
                        var _newdate = dp.cal.newdate
                        _this.def = _newdate.y + "年" + _newdate.M + "月" + _newdate.d + "日"
                        _this.isSelect = !0
                        _this.$emit('receive-params',_this.def)
                        //console.log(_this.isHighLight,_this.isSelect ,_this.def!=_this.defValue)
                    }})
                }
            },
            select:function(obj){
            	 /*this.def = obj.name 
               this.show = !0*/
               this.isSelect = !0
               this.$emit('receive-params',this.def)
            },
            
        },
        watch:{
            
        }

    })
})