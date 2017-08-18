define(function (require,exports,module) {
    var step3 = new Vue({
        el: '#vue',
        delimiters : ['${', '}'],
        devtools : true,
        debug : true,
        unsafeDelimiters : ['{--', '--}'],//don't work --! with v-html repeat
        data: { 
            formValite:{
                
            },
            radio:1,
            area:[],
            empty:true,         //是否无数据
            nothing:true,
            isAdd:false,        //是否点击添加按钮
            isSelect:false,     //是否选中了下拉框 
            isError:false,
            canAdd:false,
            switchs:[
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0},
                {selectEventSwitch:false,value:'',id:'',count:0,countTmp:0,valueTmp:"",valueDisp:"",countDisp:"",selectId:9,displayError:false,obj:[],selectDef:0} 
            ]
            , 
            elm:{
                value:"",
                html:"",
                id:"",
                count:null,
                countHtml:"",
                obj:[]
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
             
        },
        methods: {
            selectRadio:function(idx){
                this.radio = idx
            }
            ,
            initArea:function(obj,index){
                this.area   = ["local",obj[1] == 1 ? "all" : ""]
                this.canAdd = true
            }
            ,
            initModifyArea:function(obj,index){
                
                this.switchs[index].obj      = ['local',obj[1] == 1 ? "all" : ""]
                this.switchs[index].selectId = obj[1] //是否是全国
                /*console.log("obj:"+obj+"------index:"+index)
                console.log(this.switchs[index].selectId)
                console.log(this.switchs[index].selectDef)*/
            }
            ,
            initSwitchs:function(index,isSw,value,id,count){
                this.switchs[index].selectEventSwitch  = isSw
                this.switchs[index].value              = value //真实提交的数据
                this.switchs[index].valueTmp           = value //默认值数据
                this.switchs[index].valueDisp          = value //表面展示的数据
                this.switchs[index].id                 = id
                this.switchs[index].count              = count == 1 ? "\u672c\u5730\u3001\u5f02\u5730" : "\u672c\u5730"
                this.switchs[index].countTmp           = this.switchs[index].count
                this.switchs[index].countDisp          = this.switchs[index].count
                this.switchs[index].selectDef          = count
            }
            , 
            selectElmEvent:function(value,html,id){
                //this.switchs = false
                step3.setElm([value,html,id])
                this.isSelect = true
            }
            ,
            setElm:function(obj){
                this.elm.value = obj[0]
                this.elm.html  = obj[0]
                this.elm.obj   = obj[1]
                this.elm.id    = obj[2]
            }
            ,
            clearElm:function(obj){
                this.elm.value = ""
                //this.elm.html  = ""
                this.elm.id    = ""
            }
            ,
            addInsuranceCompany: function (id,type) {
                this.empty   = false
                this.nothing = true
                this.isAdd   = true
                this.isError = false
                //添加的时候取消编辑状态
                for (var i = 0; i < this.switchs.length; i++) {
                    this.cancel(i)
                }
            }
             
            ,
            save:function(dealer_id,daili_dealer_id){
                if (!this.canAdd ) {
                    this.isError = true
                    return
                }
                var _this = this
                //console.log(_this.elm.id,dealer_id,_this.elm.count,daili_dealer_id)
                //console.log(dealer_id,_this.elm.id,_this.elm.value,daili_dealer_id)
                
                $.ajax({
                     type: "post",
                     url: "/dealer/ajaxsubmitdealer/add-baoxian/",
                     data: {
                        id:0,
                        co_id:_this.elm.id,
                        title:_this.elm.value,
                        dealer_id:dealer_id,
                        daili_dealer_id:daili_dealer_id,
                        _token:$("meta[name=csrf-token]").attr('content')
                     },
                     beforeSend:function(){
                        _this.canAdd = false
                     }
                     , 
                     dataType: "json",
                     success: function(data){
                         console.log(data)
                         _this.canAdd = true
                         if(data.error_code==0){
                             require("module/common/hc.popup.jquery") 
                             //当error_code == 0的时候 有时候是不返回error_msg值的
                             //自定义了一个：'服务器繁忙，请稍后再试！'
                             $("#tip-error").hcPopup({content:''+(data.error_msg ? data.error_msg : '服务器繁忙，请稍后再试！')+''})
                         }else{
                            //解决新增保险公司刷新页面时丢失车辆首年商业保险选择项问题
                            var _href = window.location.href 
                            var _param = step3.request("radio")
                            if (_param != null) {
                                _href = _href.slice(0, _href.indexOf("?")) + "?radio=" + _this.radio
                            }else{
                                _href += "?radio="+ _this.radio
                            }
                            window.location.href = _href
                            
                         }
                     }
                })
            }
            ,
            edit:function(index,isLoacl){
                //修改的时候取消添加状态 选择值清零
                if (this.isAdd) {
                    this.isAdd          = false
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
                //保证理赔范围的准确性
                var _isLoacl = this.switchs[index].selectDef         
                this.switchs[index].obj = ['local',_isLoacl == 1 ? "all" : ""]
            }
            ,
            selectEditElm:function(id,title){
                this.switchs[this.eidtOption.id].value     = title
                this.switchs[this.eidtOption.id].valueDisp = title
                this.switchs[this.eidtOption.id].id        = id
                //this.eidtOption.id = 0
            }
            ,
            modify:function(index,id,dealer_id,daili_dealer_id){
                var _this = this
                //console.log(index,id,dealer_id,_this.switchs[index].id,_this.switchs[index].countDisp,_this.switchs[index].value)
                if (_this.switchs[index].valueDisp == _this.switchs[index].valueTmp ) {
                    this.cancel(index)
                    return
                }
                if (_this.switchs[index].displayError ) {
                    return
                }
                //console.log(dealer_id,_this.switchs[index].id,_this.switchs[index].value,dealer_id,daili_dealer_id)

                $.ajax({
                     type: "post",
                     url: "/dealer/ajaxsubmitdealer/add-baoxian/",
                     data: {
                        id:id,
                        co_id:_this.switchs[index].id,
                        title:_this.switchs[index].value,
                        dealer_id:dealer_id,
                        daili_dealer_id:daili_dealer_id,
                        _token:$("meta[name=csrf-token]").attr('content')
                     },
                     dataType: "json",
                     success: function(data){
                         //console.log(_this.switchs[index].id)
                         if (data.error_code == 0) {
                            require("module/common/hc.popup.jquery")
                            $("#tip-error").hcPopup({content:''+data.error_msg+''})
                         } else {
                             /*_this.switchs[index].count             = _this.switchs[index].countDisp
                             _this.switchs[index].countTmp          = _this.switchs[index].countDisp*/
                             _this.switchs[index].selectEventSwitch = false
                             _this.switchs[index].value             = _this.switchs[index].valueDisp
                             _this.switchs[index].valueTmp          = _this.switchs[index].valueDisp
                             //理赔范围
                             var _isLoacl                           = _this.switchs[index].selectId
                             _this.switchs[index].count             = _isLoacl == 1 ? "\u672c\u5730\u3001\u5f02\u5730" : "\u672c\u5730"
                             _this.switchs[index].countTmp          = _this.switchs[index].count
                             _this.switchs[index].countDisp         = _this.switchs[index].count
                             _this.switchs[index].obj   = ['local',_isLoacl == 1 ? "all" : ""]
                             _this.switchs[index].selectDef         = _this.switchs[index].selectId
                             //console.log(_this.switchs[index].value ,_this.switchs[index].valueTmp ,_this.switchs[index].valueDisp )
                             //同步删除ID
                             //这边用jq也是无奈之举
                             $("#tr-"+index).find(".del").attr("data-bx",_this.switchs[index].id)
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
                //this.switchs[index].selectId          = this.switchs[index].selectId == 0 ? 9 : 1
            }
            ,
            fade:function(){
                this.isAdd    = false
                this.isSelect = false
                if ($(".def-temp").length == 0) {
                    this.empty   = true
                    this.nothing = false
                }
                this.elm.value     = ""
                this.elm.count     = ""
                this.elm.countHtml = ""
            }
            ,
            del:function(event){
                require("module/common/hc.popup.jquery")
                $("#delInsuranceCompany").hcPopup({'width':'450'})
                var _del = $(event.target)
                this.delOption.model  = _del
                this.delOption.id     = _del.attr("data-bx")
                this.delOption.dealer = _del.attr("dealer")
                //console.log(this.delOption)
            }
            ,
            doDel:function(){

                var _this = this
                $.ajax({
                         type: "post",
                         url: "/dealer/ajaxsubmitdealer/del-baoxian/",
                         data: {
                            id:_this.delOption.id,
                            _token:$("meta[name=csrf-token]").attr('content')
                         },
                         dataType: "json",
                         success: function(data){
                            if (data.error_code == 0) {
                                $("#tip-error").hcPopup({content: '抱歉！删除失败，请重新尝试~'})
                            } else {
                                $("#delInsuranceCompany").hide()
                                var _tr  = _this.delOption.model.parents("tr")
                                var _tbl = _tr.parents("table")
                                _tr.fadeOut(300, function() {
                                    _tr.remove()
                                    _tbl.find("tr.no-tr").remove()
                                    _this.delOption.model  = null
                                    _this.delOption.id     = 0
                                    _this.delOption.dealer = 0
                                    if (_tbl.find("tr.def-temp").length == 0) {
                                        if (!_this.isAdd)
                                            _this.nothing      = false
                                    }else{
                                        _this.nothing          = true
                                    }
                                })
                            }
                         }
                    
                    
                 })
            }
            
            ,
            nextAction:function(step){
                require("vendor/jquery.form")
                var options = {
                    type: 'post',
                    success: function(data) {
                        //console.log(data.type)
                        if (data.type == 'check') {
                            var _href  = window.location.href
                            var _index = _href.indexOf("?")
                            window.location.href = _index == -1 ? _href.replace("step2","step3")
                                                                : _href.slice(0,_index).replace("step2","step3")
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
            ,
            request :function(name) {
                var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i")
                var r = window.location.search.substr(1).match(reg)
                if (r != null) return decodeURIComponent(r[2]) 
                return null
            }
            ,
            save_action:function(step){
                require("vendor/jquery.form")
                var options = {
                    type: 'post',
                    success: function(data) {
                       require("module/common/hc.popup.jquery")
                       if(data.error_code ==0){
                           $("#tip-error").hcPopup({content:'抱歉！修改失败，请重新尝试~'})
                           return false;
                       }else{
                           $("#tip-succeed").hcPopup({content:'恭喜，修改成功！',callback:function(){
                                //window.location.reload()
                           }})
                           
                       }
                    } 
            } 
            var _form  = $("form[name='save-form']")
             _form.ajaxForm(options).ajaxSubmit(options)
             console.log("x")
            
        } 
        
        }

    })

   
 
    module.exports = {
        init:function(index,isSw,value,id,count){
             step3.initSwitchs(index,isSw,value,id,count)
        } 
        ,
        initRadio:function(bx,tp){
            var _param = step3.request("radio")
            if (_param != null) {
                step3.radio = _param
            }else{
                if (bx == 1 || tp == "add") {
                    step3.radio = 1
                }
                else if (bx == 0 && tp == "check") {
                   step3.radio = 0
                }
                else if (bx == 0 && tp == "add") {
                   step3.radio = 1
                }
                else if (bx == 0 && tp == "edit") {
                   step3.radio = bx
                }
                
            }
            //console.log(bx,tp) 
        }
    }



})