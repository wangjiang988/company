
define(function (require) {

    require("jq");//
    require("module/common/time.jquery")
    var vm = avalon.define({
        $id: 'item',
        total:0,
        isSunshixian:true,//机动车损失险
        iszerenxian:true,//车上人员责任险
        isdisan:true,//第三者责任保险
        init: function () {
            vm.zerenxian.z = parseInt($("input[name='baoxian[renyuan][sj][compensate]']:checked").val())
            vm.zerenxian.x = parseInt($("input[name='baoxian[renyuan][ck][compensate]']:checked").val())
            vm.zerenxian.y = parseInt($("input[name='baoxian[renyuan][seat]']:checked").val())
            $(".tongji-ry").show()
            
            vm.tongji()

            //console.log($("iframe").contents().find(".WinvalidDay").length)
           
        }

        ,
        zerenxian:{x:0,y:0,z:0}
        ,
        toggleContent: function () {
           
        }
        ,
        rili:function(){  
            require("vendor/DatePicker/WdatePicker")
            /*//console.log(111111111111)
            $(this).date_input();*/
            $(this).prev().focus()
        }
        ,
        selectTime:function(){
            $(this).parent().find("li").removeClass("active").end().end().addClass("active").parent().find("input[type='hidden']").val($(this).text()).parent().prev().find(".dropdown-label span").text($(this).text())
        }
        ,
        SolicitationTime:function(){
            var _this = $(this)
            var _disable ="disable"
            if (_this.hasClass(_disable)) {
                return false
            }
            var _index = _this.index()
            var _className = "cur-select"
            var _nextClassName = ["select-next","select-prev"]
            _this.addClass(_className).siblings().removeClass(_className).removeClass(_nextClassName[0]).removeClass(_nextClassName[1])
            if (_index == 1) {
                _this.next().addClass(_nextClassName[0])
            }else{
                _this.next().addClass(_nextClassName[0]).end().next().addClass(_nextClassName[0])
            } 
            _this.parent().find("input[type='hidden']").val(_this.text())
        }
        ,
        tiCheMethod:function(){
            var _this = $(this)
            var _className = "cur-select"
            var _nextClassName = ["select-next","select-prev"]
            var _index = _this.index()
            _this.addClass(_className).siblings().removeClass(_className).removeClass(_nextClassName[0]).removeClass(_nextClassName[1])
            if (_index == 1) {
                _this.next().addClass(_nextClassName[0])
                _this.parent().next("input").removeClass("hide")
            }else{
                _this.next().addClass(_nextClassName[0]).end().next().addClass(_nextClassName[0])
                _this.parent().next("input").addClass("hide")
            } 
            _this.parent().prev("input[type='hidden']").val(_this.text())

        }
        ,
        huji:function(){
            var _this = $(this)
            var _className = "cur-select"
            _this.siblings().removeClass(_className).end().addClass(_className).parent().find("input[type='hidden']").val(_this.text()).parent().prev().prev().prop("checked","checked")
        }
        ,
        displayTm: function () {
            $(this).next().fadeIn(300)
        }
        ,
        hideTm: function () {
            $(this).next().hide()
        }
        ,
        sunshixian:function(){
           vm.isSunshixian = !vm.isSunshixian 
        }
        ,
        zerenxianevent:function(){
           vm.iszerenxian = !vm.iszerenxian 
        }
        ,
        disanevent:function(){
           vm.isdisan = !vm.isdisan 
        }
        ,
        baojia:function(){
            var _this = $(this) 
            _this.parents(".baoxian").show().next().hide()
        }
        ,
        baojia2:function(){
            var _this = $(this) 
            _this.parents(".baoxian").hide().next().show().find("td").slice(1).find("input").attr("disabled","disabled")
           // _this.removeAttr("checked")
        }
        ,
        baojia3:function(){
            var _this = $(this) 
            _this.parents(".baoxian").hide().prev().show()
            _this.removeAttr("checked")
        }
        ,
        baojia4:function(){
           
        }
        ,
        bxself:function(val){

           if (isNaN(val)) {
              if (!$(this).prop("checked")) {

                 ////console.log($(this).parent().next().find("input:checked").attr("id"))
                 zuhe2.removeByValue($(this).parent().next().find("input:checked").attr("id"))

                 $(this).parent().next().find("input").removeAttr("checked").end().next()
                 .find("p").show().next().hide().text("").end().end()
                 .next().html("")
                 
              }else{
                
              }
           }else{
              //val = val + " \u5143" 
              if (!$(this).prop("checked")) {
                 val = ""
                 $(this).next().find(".pdi-tip").hide()
                 ////console.log(1111111)
                 //zuhe2.removeByValue($(this).parent().next().find("input").attr("id"))

              }else{

                if ($(this).hasClass("fjx")) {

                    var _obj = $("."+$(this).attr("for"))
                    var _flag = true
                    var _flag2 = true 
                    if (_obj.prop("checked")) {
                        _flag = false
                    }  
                    $.each(_obj.parents("td").next().find("input"),function(index,item){
                        
                        if ($(item).prop("checked")) {
                            _flag2 = false 
                            return
                        }
                        _flag = true
                    }) 
                    if (_flag && _flag2) {
                        $(this).next().find(".pdi-tip").show()
                    }
                    
                }

              }
              var _td = $(this).parents("td").next().next().next()
              if (_td.find(".tongji-ry")[0]) {
                _td.find(".tongji-ry").show()
              }else{
                //_td.html(val)
                _td.html($(this).val())
              }
              
              //tongji-ry
              
           }
           vm.panduan($(this))
           vm.tongji()
        }
        ,
        bxself2:function(val){
           if (isNaN(val)) {
              if (!$(this).prop("checked")) {
                 //console.log($(this).parent().next().find("input:checked"))
                 $(this).parent().next().find("input:checked").each(function(index,item){
                    zuhe2.removeByValue($(item).attr("id"))
                 })
                 

                 $(this).parent().next().find("input").removeAttr("checked").end().next()
                 .find("p").show().nextAll().hide().text("")
                 $(this).parent().parent().find(".cts").text("")
              }
           }
           vm.zerenxian.x = 0
           vm.zerenxian.y = 0
           vm.zerenxian.z = 0
           vm.panduan($(this))
           vm.tongji()
           
        }
        ,
        setSelectVal:function(obj){
            $(obj).parent().find(".hfdiv input[type='hidden']").val($.trim($(obj).next().text()))

        }
        ,
        selectBX:function(){
          var _this = $(this)
          //var _val = $(this).attr("data-bind") + " \u5143" 
          var _val = $(this).attr("data-bind")
          _this.parent().prev().find("input[type='checkbox']").prop("checked","checked")
          zuhe2.push(_this.parent().prev().find("input[type='checkbox']").attr("id"))

          _this.parent().next().find("p").hide().next().show().text(_val).end().end()
          .next().html($(this).val())
          //.next().html(_val)
          vm.panduan($(this))
          vm.setSelectVal($(this))
          vm.tongji()
        }
        ,
        selectBX2:function(){
          var _this = $(this)
          //var _val = $(this).attr("data-bind") + " \u5143" 
          var _val = $(this).attr("data-bind")
          _this.parents("td").next().find("table tr:eq(0) td").find("p").hide().next().show().text(_val)
          _this.parents("td").next().next().find("table tr:eq(0) td").html(_val)
          vm.zerenxian.z = parseInt($(this).val())
          vm.panduan($(this))
          vm.tongji()
          vm.setSelectVal($(this))
        }
        ,
        selectBXBoth:function(){
          var _this = $(this)
          var _v1,_v2,_vx

          if ($(this).attr("data-bind-type") == "1") {
             _v1 = $(this).attr("data-bind") 
             vm.zerenxian.x = parseInt($(this).val())
          }
          else if ($(this).attr("data-bind-type") == "2") {
            _v2 = "*" + $(this).attr("data-bind") 
            vm.zerenxian.y = parseInt($(this).val())
          }
          _vx = vm.zerenxian.x * vm.zerenxian.y 
          _this.parents("td").next().find("tr:eq(1) td p").hide().next().show().text(_v1).next().show().text(_v2)
          _this.parents("td").next().next().find("tr:eq(1) td ").text(
            _vx == 0 ? "" : _vx
           )
          if (vm.zerenxian.x != 0 && vm.zerenxian.y != 0) {
              var _ck = _this.parents("td").prev().find("input[type='checkbox']")
              zuhe2.push(
                  _ck.attr("id") 
                )
              _ck.prop("checked","checked")
          }
          vm.panduan($(this))
          vm.tongji()
          vm.setSelectVal($(this))
          
        }
        ,
        
        panduan:function(_this){
          var _id  = _this.attr("id")
          var _tmpid = _id.replace(/\d/g,"")//获取纯字母
          //console.log(_id)
          //console.log(_tmpid)
          var _index = 0 //原先的选择index
          var _isdel = false


          $.each(zuhe2,function(index,item){

              //console.log("indexOf:"+item.indexOf(''+_tmpid+''))
              if (item.indexOf(''+_tmpid+'')  >= 0) {
                 
                 //console.log($("#"+item).attr("name"))
                 //console.log(_this.attr("name"))
                 if ($.trim($("#"+item).attr("name")) == $.trim(_this.attr("name")) ) {
                    _index = index
                    _isdel = true
                     //console.log("_isdel:"+_isdel)
                    return false
                 }

                 
              }
          })


          //console.log("_index:"+_index)
          //console.log("原来的："+zuhe2)
          //移除原有的相关选项值
          if(_isdel)
          {
            zuhe2.remove(_index)
          }
          //单独的玻璃险
          if (_id == "bldgc") {
            zuhe2.removeByValue("bldjk")
          }
          else if (_id == "bldjk") {
            zuhe2.removeByValue("bldgc")
          }

          //添加新值
          if (_this.prop("checked")) {
            zuhe2.push(_id)
          }
          //去重复项
          zuhe2.unique()
          //做对比
          var _flag = zuhe.unique().toString() == zuhe2.unique().toString()
          var _input = $("input[name='baoxian']")
          if (_flag) {
            _input.eq(1).prop("checked","checked")
          }else{
            _input.eq(0).prop("checked","checked")
          }
          //console.log( zuhe2.unique())
          ////console.log(_flag)
           //zuhe2.push(_id)
           //zuhe2 = vm.unique(zuhe2)
           ////console.log( zuhe2.unique())
        }
        ,
        tongji:function(){
           var _total = 0
           var _span = 0
           $(".tongji").each(function(index,item){
              var _thisval = $(item).text()
              ////console.log("_thisval:"+_thisval)
              if ($(item).find("span")[0]) {
                 if (_span == 0) {
                    _thisval = vm.zerenxian.x * vm.zerenxian.y
                 }
                 else if (_span == 1) {
                    _thisval = vm.zerenxian.z + vm.zerenxian.x * vm.zerenxian.y
                 }
                 _span++
              }
              if (!isNaN(_thisval) && _thisval!="" ) {
                 _total += parseFloat(_thisval)
              }
           })
            _total = _total.toString()
           if (_total.indexOf(".") > 0) {
              var _tlength = _total.substring(_total.indexOf(".") + 1).length
              if (_tlength < 2) {
                _total = _total+"0"
              }
              else if (_tlength > 2) {
                _total = _total.substring(0,_total.indexOf(".") + 3)
              }
              
              var _tmp = _total.substring(0,_total.indexOf("."))
              if (_tmp.length > 3) {
                 var _prev = _tmp.length - 3
                 _total = _total.slice(0,_prev) +"," +_total.slice(_prev)
              } 
           }else{
              if (_total.length > 3) {
                 var _prev = _total.length - 3
                 _total = _total.slice(0,_prev) +"," +_total.slice(_prev)
              } 
           }
           vm.total = _total
        }
        ,
        shangpai:function(){
           $("table.sp").hide().eq($(this).parent().index()).show()
        }
        ,
        initDP:function(min,cur,max){
           var $c = $("iframe").contents()
           var $this = $c.find(".Wselday")
           var $length = 15
           var $col = 6
           var $lx = 0 //余下的数量
           var $cindex = $this.index()


           console.log(
              $c.find(".Wselday").index()
            )
           // $("iframe").contents().find(".WinvalidDay").attr("style","color:#ff0000")
        }
        ,
        send_yuyue:function(){
         var _ck = $("input[name='agree-check']")
         var _mydate = $("input[name=mydate]").val()
         var _mydate1 = $("input[name=jiaoche_date]").val()
         if(_mydate1 == '' && _mydate == ''){
           alert('您没有选择交车时间，请选择代理提交的日期或者自选一个日期');
           return false;
         }
         if( _mydate1 != '' && _mydate != ''){
          $("input[name=mydate]").val('');
          
          $("input[name=jiaoche_date]").val('');
          $(".sure.cur-select").removeClass('cur-select')
          $(".sure.select-next").removeClass('select-next')
          alert('代理提交的日期或者自选日期,只能二选一,请重新选择');
          return false;
         }
         
            if (_ck.prop("checked")) {
                $("form[name='item-form']").submit()
            }else{
                alert("请勾选'我已阅读并同意上述通知内容',才能进行提交")
            } 
        }
        
    });

    vm.init();

    
    $(window).scroll(function(){
      ////console.log($(window).scrollTop())
      if ($(window).scrollTop() > 4713  ) {
         $(".btn-save").removeClass("btn-save")
      }else{
          $(".btn-fl").addClass("btn-save")
      }
    })

    Array.prototype.unique = function()
    {
      this.sort();
      var re=[this[0]];
      for(var i = 1; i < this.length; i++)
      {
        if( this[i] !== re[re.length-1])
        {
          re.push(this[i]);
        }
      }
      return re;
    }
    Array.prototype.remove = function(dx)
  　{

  　　if(isNaN(dx)||dx>this.length){return false;}
  　　for(var i=0,n=0;i<this.length;i++)
  　　{
  　　　　if(this[i]!=this[dx])
  　　　　{
  　　　　　　this[n++]=this[i]
  　　　　}
  　　}
  　　this.length-=1
      ////console.log(this)
  　}
    Array.prototype.removeByValue= function(value)  
    {  
        
        for (var i = 0; i < this.length; i++)  
        {  
            if (this[i] == value)  
            {  
                this.remove(i)
                break;  
            }  
        }  
        
    }  

});