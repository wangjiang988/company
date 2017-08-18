/***********************************
**************2016-4-14**************
**************llm*******************
**************v1.0.0.0**************
***********************************/

define(function (require, exports, module) {
    
    $.fn.hcPopup = function(_options){

        
        var defaults = {
           width:'400', 
           height:'auto',
           zindex:99990,
           min:false,
           top:0,
           minbottom:0,
           content:'',
           minleft:0,
           callback:function(){}
        }
        var options = $.extend(defaults, _options)
        
        return this.each(function(){
            var _this = $(this)
            var _minobj = $("."+_this.attr("for"))
            var _height = $(window).height() / 2
            var _thisheight = $(this).height() / 2
            if (_height - _thisheight < 0) {
                _height  = _height + 200
            }
            var _top = options.top || _height - _thisheight - 43
            //console.log(_height,_thisheight)
            _this.css({
                "width":options.width,
                "display":"block",
                "visibility":"visible",
                "position":"fixed",
                "margin":"0 auto",
                "left":"0",
                "top":_top,
                "right":"0",
                //"bottom":"0",
                "z-index":options.zindex

            }).find(".sure").bind("click",function(){
                _this.hide()
                options.top = _top
                options.callback()
            })
            _minobj.hide().removeClass('showtag').removeAttr('style')

            if (options.min) {
                var _min = $("<div class='min'>-</div>")
                var _bottom = $(window).height()  
                var _width = $(".container").width() 
                _min.appendTo(_this.find(".popup-title")).bind("click",function(){
                    if (options.minbottom == 0) {
                        var _fixtop = 30
                        if ($(".showtag").length > 0) {
                            _fixtop += 60
                        }
                        options.minbottom = _bottom - _min.height() - _fixtop 
                    }
                    if (options.minleft == 0) {
                        options.minleft = _width - _min.width() + 100
                    }
                    _bottom = options.minbottom 
                    _width = options.minleft
                    _this.animate({/*'top':_bottom,left:_width,width:0,height:0*/}, 500,function(){
                        _this.hide()//.css({top:_top,left:0,rihgt:0})
                        _minobj.css({
                            'top':_bottom,left:_width
                        }).addClass('showtag').fadeIn(200).attr("for","#"+_this.attr("id")).
                        find("a").bind("click",function(){
                            $(_minobj.hide().removeClass('showtag').removeAttr('style').attr("for")).css({
                                /*top:_top,left:0,rihgt:0,
                                width:options.width*/
                            }).show()
                        })
                    })
                })

            }
            if (options.content){
                _this.find(".tip-text").html(options.content)
            }

            //this.propter
             
        })
    }
    
});