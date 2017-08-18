
define(function (require,exports,module) {

     require("module/common/time.jquery")

     $(".sj").hover(function(){
        $(this).next().toggle()
     })

     $.each($(".custom-info-tbl").find("tr"),function(idx,item){
        if (idx!=0) {
            var _item        = $(item).find(".countdown")
            var _start       = _item.attr("start")
            var _end         = _item.attr("end")
            var _timekeeping = _item.hasClass('red') ? "timeout" : "countdown"
            _item.CountDown({
                  startTime:_start,
                  endTime :_end,
                  timekeeping:_timekeeping
            })
        }
     })
     


     
    module.exports = {
        

    }
   

})

 



