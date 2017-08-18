define(function (require,exports,module) {    
    //车辆用途 选择事件 特别设置
    $(".dropdown-vehicular-applications li").click(function(){
    	var _index    = $(this).index()
    	var _dropdown = $(".dropdown-categories")
    	var _showclass = "" , _hideclass = ""
    	if (_index == 0) {
    		_showclass = ".categories-def"
    		_hideclass = ".categories-advance"
    	}
    	else if (_index == 1) {
    		_showclass = ".categories-advance"
    		_hideclass = ".categories-def"
    	}
    	var _firsttext = _dropdown.find(_showclass).show().eq(0).text()
    	_dropdown.find(_hideclass).hide().parent().next().val(_firsttext).end().prev().find("span:eq(0) span").text(_firsttext)
    })

    //上牌（注册登记）车主身份类别 选择事件 特别设置 
    $(".dropdown-categories li").click(function(){
    	var _this = $(this)
    	$.ajax({
             type: "GET",
             url: "getById",
             data: {
                id:_this.attr("data-id") 
             },
             dataType: "json",
             success: function(data){
                 //do
                 var _file = ""
             	 var _def  = _data[0].资料名称 //默认值
             	 $.each(_data,function(index,item){
             		_file += "<li data-id='"+$(item)[0].资料id+"'><a><span>"+$(item)[0].资料名称+"</span></a></li>"
                 })
                 $(".dropdown-file").find("li").remove().end().append(_file).find("input[type='hidden']").val(_def).end().prev().find('.dropdown-label span').text(_def)
 
             },
             error:function(){
             	 //真实环境 请把error方法删除 别忘了error前面的那个逗号
             	 var _file = ""
             	 var _data = [{"资料名称":"xxx文件资料","id":"x123"},{"资料名称":"yyy文件资料","id":"x456"}]
             	 var _def  = _data[0].资料名称 //默认值
             	 $.each(_data,function(index,item){
             		_file += "<li data-id='"+$(item)[0].id+"'><a><span>"+$(item)[0].资料名称+"</span></a></li>"
                 })
                 $(".dropdown-file").find("li").remove().end().append(_file).find("input[type='hidden']").val(_def).end().prev().find('.dropdown-label span').text(_def)
             }
        })
    })

    //客户文件
    //添加文件
    $(function(){
        $(document).delegate('a.use-occasions-add-file', 'click', function(event) {
        	//这个事件里面挺绕的 不懂的话 随时召唤我
            //console.log(898)
        	require("module/common/hc.popup.jquery")
        	var _this   = $(this)
        	var _tr     = _this.parents("tr").eq(0)
        	var _source = $(".file-tr").eq(0)
        	//var _copy   = _tr.prev()
        	var _title  = _source.find("td").eq(0).find(".use-occasions-title").text()
        	
        	var _win   = $("#addUseOccasionsFile")
        	_win.hcPopup({'width':'500'})
            $(".add-file-title").text(_title)
        	_win.find(".do").unbind('click').bind("click",function(){
                 var _sub        = $(this)
                 var _flag       = true
                 var _radio      = $("input[name='use-occasions-file-radio']")
                 var _radiocheck = $("input[name='use-occasions-file-radio']:checked")
                 if (!_radiocheck[0]) {
                    _flag = false
                    errorshowhide(_radio.parents("td").find(".error-div"))
                 }

                 if (_flag) {
                     //判断选中的是原件还是复印件
                     var _index = _radio.eq(0).prop("checked") ? 0 : 1
                     //用于判断当前文件资料是否存在
                     var _id    = $(".dropdown-file").find("li.active").attr("data-id") || $(".dropdown-file").find("li:eq(0)").attr("data-id")
                     var _form  = _win.find("form") 
                     var _filecount = ""
                     var _filename  = _win.find(".dropdown-label:eq(0) span").text()
                     var _tmp       = null //
                     var _fileup    = "" //上传的文件
                     var _filetr = _this.parents("table").eq(0).find(".file-tr")
                     if (_index == 0) {
                        _id  += "-source"
                        _tmp = $("#FirstFileTmp").html()
                        _filename += "原件"
                        //是否第一次添加 
                        if (!_filetr.find(".display-transformation")[0]) {
                             _tmp = $("#FirstFileTmp").html()
                        }else{
                             _tmp = $("#AnotherFileTmp").html()
                        }
                        _filecount = "√"
                     }else if(_index == 1) {
                        _id  += "-copy"
                        //是否第一次添加 
                        if (!_filetr.find(".display-transformation")[0]) {
                             _tmp = $("#FirstFileTmp").html()

                        }else{
                             _tmp = $("#AnotherFileTmp").html()
                             //_filecount = "√"
                        }
                        //_tmp = $("#AnotherFileTmp").html()
                        _filecount  = _win.find(".dropdown-label:eq(1) span").text()
                        _filename += "复印件"
                     }
                     var _fileinput = _win.find("input[type='file']")
                     if (!isempty(_fileinput)) {
                        _fileup = _fileinput.val().substring(_fileinput.val().lastIndexOf('\u005c') + 1)
                     }
                     _tmp = _tmp.replace("{0}" ,_filename)
                                .replace("{1}" ,_fileup)
                                .replace("{2}" ,_filecount)
                                .replace("{id}",_id)
                     //判断文件资料是否存在           
                     var _some = $("#"+_id)
                     if (typeof(_some[0]) == 'undefined') {
                         if (!_filetr.find(".display-transformation")[0]) {
                             _filetr.eq(0).find("td:last").after(_tmp)
                         }else{
                             _filetr.last().after(_tmp)
                         }
                         var options = {
                            success: function (data) {
                               _win.hide()
                               _fileinput.val("") 
                               $(".use-occasions-add-file-name").text("")
                               $(".use-occasions-edit").unbind('click').bind("click",function(event) {
                                   useOccasionsEdit()
                               }) 
                               $(".use-occasions-del").unbind('click').bind("click",function(event) {
                                   useOccasionsDel()
                               }) 
                            }
                            ,
                            beforeSubmit:function(){}
                            ,
                            error:function(){
                               _win.hide()  
                               _fileinput.val("") 
                               $(".use-occasions-add-file-name").text("") 
                               /*var ele = $._data($(".use-occasions-edit")[0], "event")
                               if (ele && ele["click"]) {}*/ 

                               $(".use-occasions-edit").unbind('click').bind("click",function(event) {
                                   useOccasionsEdit()
                               }) 
                               $(".use-occasions-del").unbind('click').bind("click",function(event) {
                                   useOccasionsDel()
                               }) 
                              
                            
                            }
                            ,
                            clearForm:true
                         }
                         
                         // ajaxForm 
                         _form.ajaxForm(options) 
                         // 表单提交
                         _form.ajaxSubmit(options) 

                     }else{
                        //存在 显示错误
                        errorshowhide(_sub.next().next().next())
                     } 
                	  
                }
            }) 
            _win.find(".file-upload").unbind('click').bind("click",function(){
                $(this).parent().next().find("input[type='file']").click().unbind('change').bind("change",function(){
                    $(".use-occasions-add-file-name").html($(this).val())
                })
            })
        })
    })

    function useOccasionsEdit(){ 
        event = arguments.callee.caller.arguments[0] || window.event 
        require("module/common/hc.popup.jquery")
        //tbl elem
        var _this      = $(event.target)
        var _source    = $(".file-tr").eq(0)
        var _title     = _source.find("td").eq(0).find(".use-occasions-title").text()
        var _curtd     = _this.parent()                         //修改删除的td
        var _td2       =  _curtd.prev()                         //文件格式td
        var _td1       =  _td2.prev()                           //数量td
        var _td0       =  _td1.prev()                           //文件资料td
        var _win       = $("#editUseOccasionsFile")
        //popup elem
        var _filedrop  = _win.find(".dropdown-file")            //文件资料下拉框
        var _countdrop = _win.find(".dropdown-bt")              //复印件数量下拉框
        var _radio     = _win.find("input[type='radio']")       //原件、复印件单选
        var _filename  = _win.find("input[type='file']").prev() //上传的文件名
        //popup
        _win.hcPopup({'width':'500'})
        $(".add-file-title").text(_title)
        //set value
        var _td0text  = _td0.text().replace("\u539f\u4ef6","").replace("\u590d\u5370\u4ef6","")
        _filedrop.parent().find(".dropdown-label span").text(_td0text).end()
                 .find("input[type='hidden']").val(_td0text).end()
        if (_td0.text().indexOf("\u539f\u4ef6") > 0) {
            _radio.eq(0).prop("checked","checked")
        }    
        else{
            _radio.eq(1).prop("checked","checked")
        }   
        var _count = _td1.text() == "√" ? '0' : _td1.text()
        _countdrop.parent().find(".dropdown-label span").text(_count).end()
                 .find("input[type='hidden']").val(_count).end() 
        _filename.find("span").text(_td2.text())      
        if (_td2.text().trim()!="") {
            _win.find(".file-upload").text("\u91cd\u65b0\u4e0a\u4f20")
        }           

        //bind event
        _win.find(".do").unbind('click').bind("click",function(){
             var _sub        = $(this) 
             var _form  = _win.find("form") 
             var _filecount = _countdrop.next().val()
             var _fileup    = "" //上传的文件
             var _fileinput = _win.find("input[type='file']")
             if (!isempty(_fileinput)) {
                _fileup = _fileinput.val().substring(_fileinput.val().lastIndexOf('\u005c') + 1)
             }

             var options = {
                success: function (data) {
                   _td1.text(_filecount)
                   _td2.find(".file-prev").text(_fileup) 
                   _win.hide()
                }
                ,
                beforeSubmit:function(){}
                ,
                error:function(){
                   _td1.text(_filecount)
                   _td2.find(".file-prev").text(_fileup) 
                   _win.hide()
                
                }
                ,
                clearForm:true
             }
             
             // ajaxForm 
             _form.ajaxForm(options) 
             // 表单提交
             _form.ajaxSubmit(options)  
          
        }) 
        _win.find(".file-upload").unbind('click').bind("click",function(){
            $(this).parent().next().find("input[type='file']").click().unbind('change').bind("change",function(){
                $(".use-occasions-edit-file-name").text($(this).val())
            })
        })
    }
    function useOccasionsDel(){
        require("module/common/hc.popup.jquery")
        var _win    = $("#delUseOccasionsFile")
        _win.hcPopup({'width':'400'})
        event       = arguments.callee.caller.arguments[0] || window.event 
        var _this   = $(event.target)
        var _delobj = null
        var _tr     = _this.parents("tr").eq(0)
        if (!_tr.attr("id")) {
            _delobj = _tr.find("td").slice(1)
        }else{
            _delobj = _tr
        } 
        _win.find(".do").unbind('click').bind("click",function(){
             
            $.ajax({
                 type: "GET",
                 url: "delById",
                 data: {
                    id:"" 
                 },
                 dataType: "json",
                 success: function(data){
                    _win.hide()
                    _delobj.fadeOut(300,function(){
                        _delobj.remove()
                    })
     
                 },
                 error:function(){
                    //真实环境 请把error方法删除 别忘了error前面的那个逗号
                    _win.hide()
                    _delobj.fadeOut(300,function(){
                        _delobj.remove()
                    })
                 }
            })

        })
    }

    $(".add-use-occasions").click(function(){
        require("module/common/hc.popup.jquery")
        var _win           = $("#addOccasions")
        _win.hcPopup({'width':'400'})
        var _form          = _win.find("form") 
        _win.find(".do").unbind('click').bind("click",function(){
            var _input     = _win.find("input[type='text']")
            var _flag      = true 
            var _occasions = ""          
            var options    = {
                success: function (data) {
                    
                }
                ,
                beforeSubmit:function(){}
                ,
                error:function(){
                    var _addOccasionsTpl = $("#OccasionTmp").html()
                    _occasions = _addOccasionsTpl.replace("{0}",_input.val())
                    $(".use-occasions-add-wrapper").before(_occasions)
                    _win.hide()
                }
                ,
                clearForm:true
             }

             
             if (isempty(_input)) {
                errorshowhide(_input.next())
                _flag = false
             }
             else{

                $.ajax({
                     type: "GET",
                     url: "",
                     data: {
                        id:""
                     },
                     dataType: "json",
                     success: function(data){
                        if (data.error_code == 0) {
                            errorshowhide(_input.next().next())
                            _flag = false
                        }
                     },
                     error:function(){
                        //真实环境 请把error方法删除 别忘了error前面的那个逗号
                        var data = {'error_code':"0",'error_msg':"该使用场合已存在"}
                        if (data.error_code == 0) {
                            errorshowhide(_input.next().next())
                            _flag = false
                        }
                     }
                })

             }
             if (_flag) {
                 // ajaxForm 
                 _form.ajaxForm(options) 
                 // 表单提交
                 _form.ajaxSubmit(options) 
             }  
     
        })


    })
    


//
})    