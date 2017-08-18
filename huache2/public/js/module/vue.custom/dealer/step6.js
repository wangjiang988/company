define(function (require,exports,module) {
    require("/webhtml/common/js/module/head")
    var step6 = new Vue({
        el: '#vue',
        delimiters : ['${', '}'],
        unsafeDelimiters : ['{--', '--}'],//don't work --! with v-html repeat
        init:function(){
             
        }
        ,
        data: { 
            formValite:{
                
            },
            empty:true,         //是否无数据
            nothing:true,
            isAdd:false,        //是否点击添加按钮
            isSelect:false,     //是否选中了下拉框 
            switchs:[
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:0,selectId:0} 
            ]
            , 
            elm:{
                value:"",
                html:"",
                id:"",
                count:1,
                countHtml:""
            }
            ,
            delOption:{
                id:0,
                dealer:0,
                model:null
            }
            ,
            eidtOption:{
                id:0,
                dealer:0,
                model:null
            }
            ,
            switched:true
            ,
            apply:{
                name:"",
                isName:false
            }
        },
        methods: {
            initSwitchs:function(index,isSw,value,id,count){
                this.switchs[index].selectEventSwitch  = isSw
                this.switchs[index].value              = value //真实提交的数据
                this.switchs[index].valueTmp           = value //默认值数据
                this.switchs[index].valueDisp          = value //表面展示的数据
                this.switchs[index].id                 = id
                this.switchs[index].count              = count
                this.switchs[index].countTmp           = count
                this.switchs[index].countDisp          = count
            }
            , 
            selectElmEvent:function(value,html,id){
                //this.switchs = false
                step6.setElm([value,html,id])
                this.isSelect = true
            }
            ,
            setElm:function(obj){
                this.elm.value = obj[0]
                this.elm.html  = obj[0]
                this.elm.id    = obj[2]
            }
            ,
            clearElm:function(obj){
                this.elm.value = ""
                //this.elm.html  = ""
                this.elm.id    = ""
            }
            ,
            servicelAdd: function (id,type) {
                this.empty   = false
                this.nothing = true
                this.isAdd   = true
                for (var i = 0; i < this.switchs.length; i++) {
                    this.cancel(i)
                }
            }
            ,
            killFocus:function(event){
               //事件本身传递event事件 但是定义了别的参数 event就传递不了 待解决 
               event.target.blur() 
               return false
            }
            ,
            minusEvent:function(event){
                this.elm.count--
            }
            ,
            addEvent:function(event){
                this.elm.count++
            }
            ,
            save:function(dealer_id,daili_dealer_id){

                var _this = this
                //console.log(_this.elm.id,dealer_id,_this.elm.count,daili_dealer_id)
                $.ajax({
                     type: "post",
                     url: "/dealer/ajaxsubmitdealer/addzengpin/",
                     data: {
                        dl_zp_id:_this.elm.id,//选中的赠品id
                        dealer_id:dealer_id,
                        num:_this.elm.count,
                        daili_dealer_id:daili_dealer_id,
                        _token:$("meta[name=csrf-token]").attr('content'),
                     },
                     dataType: "json",
                     success: function(data){
                         //console.log(data) 
                         if(data.error_code==0){
                             require("module/common/hc.popup.jquery") 
                             $("#tip-error").hcPopup({content:''+data.error_msg+''})
                         }else{
                            window.location.reload()
                             /*_this.switchs = false
                             _this.clearElm()*/
                         }
                     }
                 })
            }
            ,
            edit:function(index){
                if (this.isAdd) {
                    this.isAdd = false
                    this.elm.value      = ""
                    this.elm.valueDisp  = ""
                    this.elm.count      = ""
                    this.elm.countDisp  = ""
                    this.elm.html       = ""
                }
                ///////////////***********//////////////////
                //取消多个修改同时存在的情况，只允许有一个可以被修改(你能同时修改多个么，表扯淡！！)
                this.switchs[this.eidtOption.id].selectEventSwitch = false 
                ///////////////***********//////////////////
                this.switchs[index].selectEventSwitch              = true
                this.eidtOption.id                                 = index
                //this.switchs[index].selectId          = index
            }
            ,
            selectEditElm:function(id,title){
                this.switchs[this.eidtOption.id].value     = title
                this.switchs[this.eidtOption.id].valueDisp = title
                this.switchs[this.eidtOption.id].id        = id
                //this.eidtOption.id = 0
            }
            ,
            modify:function(index,dl_zp_id,dealer_id){
                var _this = this
                if (_this.switchs[index].valueDisp == _this.switchs[index].valueTmp && _this.switchs[index].countDisp == _this.switchs[index].countTmp) {
                    this.cancel(index)
                    return
                }
                $.ajax({
                     type: "post",
                     url: "/dealer/ajaxsubmitdealer/editzengpin/",
                     data: {
                        dl_zp_id:dl_zp_id,
                        zp_id:_this.switchs[index].id,
                        dealer_id:dealer_id,
                        num:_this.switchs[index].countDisp,
                        _token:$("meta[name=csrf-token]").attr('content')
                     },
                     dataType: "json",
                     success: function(data){
                         //console.log(data)
                         if (data.error_code==0) {
                            require("module/common/hc.popup.jquery")
                            $("#tip-error").hcPopup({content:''+data.error_msg+''})
                         
                         } else {
                             _this.switchs[index].count             = _this.switchs[index].countDisp
                             _this.switchs[index].countTmp          = _this.switchs[index].countDisp
                             _this.switchs[index].selectEventSwitch = false
                             _this.switchs[index].value             = _this.switchs[index].valueDisp
                             _this.switchs[index].valueTmp          = _this.switchs[index].valueDisp
                             //console.log(_this.switchs[index].value ,_this.switchs[index].valueTmp ,_this.switchs[index].valueDisp )
                         }             
                     }

                  })
            }
            ,
            cancel:function(index){
                this.switchs[index].selectEventSwitch = false
                this.eidtOption.id                    = 0
                this.switchs[index].valueDisp         = this.switchs[index].valueTmp
                this.switchs[index].countDisp         = this.switchs[index].countTmp
            }
            ,
            fade:function(){
                this.isAdd = false
                this.isSelect = false
                if ($(".def-temp").length == 0) {
                    this.empty   = true
                    this.nothing = false
                }
                this.elm.value      = ""
                this.elm.valueDisp  = ""
                this.elm.count      = ""
                this.elm.countDisp  = ""
                this.elm.html       = ""
            }
            ,
            del:function(event){
                require("module/common/hc.popup.jquery")
                $("#delControlModel").hcPopup({'width':'450'})
                var _del = $(event.target)
                this.delOption.model  = _del
                this.delOption.id     = _del.attr("data-id")
                this.delOption.dealer = _del.attr("dealer")
                //console.log(this.delOption)
            }
            ,
            doDel:function(dl_zp_id,dealer_id){
                var _this = this
                $.ajax({
                         type: "post",
                         url: "/dealer/ajaxsubmitdealer/del-zengpin/",
                         data: {
                            dealer_id:_this.delOption.dealer,
                            dl_zp_id:_this.delOption.id,
                            _token:$("meta[name=csrf-token]").attr('content'), 
                         },
                         dataType: "json",
                         success: function(data){
                            if (data.error_code == 0) {
                                $("#tip-error").hcPopup({content: '抱歉！删除失败，请重新尝试~'})
                            } else {
                                $("#delControlModel").hide()
                                var _tr  = _this.delOption.model.parents("tr")
                                var _tbl = _tr.parents("table")
                                _tr.fadeOut(300, function() {
                                    _tr.remove()
                                    _tbl.find("tr.no-tr").remove()
                                    _this.delOption.model  = null
                                    _this.delOption.id     = 0
                                    _this.delOption.dealer = 0
                                    if (_tbl.find("tr.def-temp").length == 0) {
                                        //添加状态下不要显示
                                        if (!_this.isAdd)  
                                            _this.nothing      = false
                                    }else{
                                        _this.nothing          = true
                                    }
                                    //console.log(_this.nothing)
                                })
                            }
                         }
                    
                    
                 })
            }
            ,
            minusEventEdit:function(index){
                if (this.switchs[index].countDisp <=1) 
                    this.switchs[index].countDisp = 1
                else
                    this.switchs[index].countDisp--
            }
            ,
            addEventEdit:function(index){
                this.switchs[index].countDisp++
            }
            ,
            applyNew:function(){
                require("module/common/hc.popup.jquery")
                $("#applyControlModel").hcPopup({'width':'450'})
            }
            ,
            doApplyNew:function(){
                var _form  = $("form[name='addServiceSpecialistForm']")
                var _this  = this
                if (_this.apply.name.trim() == "") {
                    _this.apply.isName = true
                }else{
                    require("vendor/jquery.form")
                    var options = {
                        type: 'post',
                        success: function(data) {
                             if (data.error_code == 0) {
                                 $("#tip-error").hcPopup({content:'抱歉！提交失败，请重新提交~'}) 
                             }else{
                                $("#tip-info-success").hcPopup() 
                                _this.apply.name = ""
                                $("#applyControlModel").css('display','none');
                             }
                        }
                    }
                    _form.ajaxSubmit(options)
                }
            }
            ,
            nextAction:function(step){
                require("vendor/jquery.form")
                var options = {
                    type: 'post',
                    success: function(data) {
                        if (data.type == 'check') {
                            window.location.href = window.location.href.replace("step5","step6")
                        } else {
                            window.location.reload()
                        }
                    }
                } 
                var _form  = $("form[name='next-form']")
                if(step==3){
                    _form.find('input[name="bx_type"]').val($("input[name=baoxian]:checked").val())
                    _form.ajaxForm(options).ajaxSubmit(options)
                }else if(step == 2 || step == 7 || step == 6) {
                    _form.ajaxForm(options).ajaxSubmit(options)
                    return false
                }
              
            }
        }
        ,
        watch:{
            'elm.count':function(){
                this.elm.count = this.elm.count.toString().replace(/\D/g,'')
                if (this.elm.count <=1) {
                    this.elm.count = 1
                }
            }
            ,
            'apply.name':function(){
                this.apply.isName = false
            }
        }

    })

    

    /*var trChild = React.createClass({
          displayName: 'tr',
          render: function() {
            return React.createElement("tr", null, "Hello ", this.props.name);
          }
    })*/

    /*var Hello = React.createClass({
          displayName: 'Hello',
          render: function() {
            return React.createElement("div", null, "Hello ", this.props.name);
          }
    });

    ReactDOM.render(
          React.createElement(Hello, {name: "World"}),
          document.getElementById('example')
    );*/

    
 
    module.exports = {
        init:function(index,isSw,value,id,count){
             step6.initSwitchs(index,isSw,value,id,count)
        }
    }

})