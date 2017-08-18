
define(function (require,exports,module) {

     require("/webhtml/common/js/vendor/time.jquery")
     function countdownAll(){
         $(".countdown").each(function(){
            var _this = $(this)
            _this.CountDown({
              startTime:_this.attr("data-time1"),
              endTime :_this.attr("data-time2"),
              timekeeping:'countdown',
              callback:function(){
                  window.location.reload()
              }
            })
         })

         $(".countdown-red").each(function(){
            var _this = $(this)
            _this.CountDown({
              startTime:_this.attr("data-time1"),
              endTime :_this.attr("data-time2"),
              timekeeping:'countdown',
              callback:function(){
                  window.location.reload()
              }
            })
         })
     }
     countdownAll()

      //货币格式
    Number.prototype.formatMoney = function (places, symbol, thousand, decimal) {
        places = !isNaN(places = Math.abs(places)) ? places : 2;
        symbol = symbol !== undefined ? symbol : "\uffe5";
        thousand = thousand || ",";
        decimal = decimal || ".";
        var number = this,
            negative = number < 0 ? "-" : "",
            i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
            j = (j = i.length) > 3 ? j % 3 : 0;
        return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
    }

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

     $(".brand,.chexi").on('click','li', function(event) {
        var _this = $(this)
        if (_this.index() == 1) {
            setTimeout(function(){
                _this.prev().val("")
            })
            if (_this.parents("ul").hasClass('brand'))
                $(".chexi").find("li").eq(0).click().nextAll("li").remove()
            $(".guige").find("li").eq(0).click().nextAll("li").remove()
            return
        }

        $.ajax({
            url: '/dealer/order/range',
            type: 'post',
            dataType: 'json',
            data: {
                name: $(this).text(),
                type: $(this).attr('data-type'),
                active: $("input[name='active']").val(),
                _token: $("input[name='_token']").val()
            },
        })
        .done(function(data) {

            var html = '';
            $.each(data.list,function(idx,item){
                html += "<li data-type='3'><a><span>"+item.gc_name+"</span></a></li>"
            })
            if (data.place == "1") {
                var _li = $(".chexi li")
                if (_li.length == 1) _li.after(html)
                else _li.slice(1).remove().end().after(html)
           }
           if (data.place == "2") {
               var htmls = '';
                $.each(data.list,function(idx,item){
                htmls += "<li data-id="+item.brand+"><a><span>"+item.gc_name+"</span></a></li>"
            })
                var _li = $(".guige li")
                if (_li.length == 1) _li.after(htmls)
                else _li.slice(1).remove().end().after(htmls)
           }
           $(".zhidaojia").hide()
        })
     });

    $(".guige").on('click','li', function(event) {
        var _this = $(this)
        if (_this.index() == 1) {
            setTimeout(function(){
                _this.prev().val("")
            })
            return
        }
        $.ajax({
            url: '/dealer/ajaxcarmodel/listAll',
            type: 'POST',
            dataType: 'json',
            data: {
                gc_id_3: $(this).attr('data-id'),
                _token: $("input[name=_token]").val()
            },
        })
        .done(function(data) {
            $(".zhidaojia").show().text("厂商指导价 :"+ parseFloat(data.price.value).formatMoney());
        });

    });

    $(".btn-custom-event").delegate('li', 'click', function(event) {
        var _this = $(this)
        var _parant = _this.parents(".btn-group")
        _this.addClass('active').siblings().removeClass("active")
        _parant.find("input[type='hidden']").val(_this.index() == 1 ? "" : _this.text())
        _parant.find(".dropdown-label span").text(_this.text())

    })

         var brand=cars=standard=dealer='';
            $(window).on('hashchange', function() {
                var brand=cars=standard=dealer='';
                if (window.location.hash) {
                    var page = window.location.hash.replace('#', '');
                    if (page == Number.NaN || page <= 0) {
                        return false;
                    } else {
                        getUselessBj(brand,cars,standard,dealer,page);
                    }
                }
            });

            $(".status").on('click', 'li', function(event) {
                $("input[name=status]").val($(this).attr('data-id'))
            });

            $(".order_state").on('click', 'li', function(event) {
                $("input[name=order_state]").val($(this).attr('data-state'))
            });

            $(".rend").on('click', 'li', function(event) {
                $("input[name=feedback]").val($(this).attr('data-type'))
            });

            $(document).ready(function() {
              $(".submit-search").click(function(){
                    var order_sn = $("input[name=order_sn]").val();
                    var keyword =  $("input[name=keyword]").val();
                    var phone = $("input[name=phone]").val();
                    var brand = $("input[name=brand]").val();
                    var series = $("input[name=series]").val()
                    var gc_name = $("input[name=guige]").val();
                    var dealer = $("input[name=dealer]").val();
                    var type = $("input[name=active]").val();
                    var order_state = ($("input[name=order_state]").val() != undefined) && $("input[name=order_state]").val()
                    var feedback = ($("input[name=feedback]").val() != undefined) && $("input[name=feedback]").val()
                    var page=1;
                    if (type == 'finishs') {
                        var str_time = $("input[name=str_time]").val();
                        var end_time = $("input[name=end_time]").val();
                        getUselessBj(type,order_sn,'','',brand,series,keyword,phone,gc_name,dealer,page,str_time,end_time);
                   }else{
                    getUselessBj(type,order_sn,order_state,feedback,brand,series,keyword,phone,gc_name,dealer,page);
                   }
               })

              $(document).on('click', '.pagination a', function (e) {
                    var order_sn = $("input[name=order_sn]").val();
                    var keyword =  $("input[name=keyword]").val();
                    var phone = $("input[name=phone]").val();
                    var gc_name = $("input[name=guige]").val();
                    var brand = $("input[name=brand]").val();
                    var series = $("input[name=series]").val()
                    var dealer = $("input[name=dealer]").val();
                    var type = $("input[name=active]").val();
                    var order_state = ($("input[name=order_state]").val() != undefined) && $("input[name=order_state]").val()
                    var feedback = ($("input[name=feedback]").val() != undefined) && $("input[name=feedback]").val()
                    var page = $(this).attr('href').split('page=')[1];
                    if (type == 'finishs') {
                        var str_time = $("input[name=str_time]").val();
                        var end_time = $("input[name=end_time]").val();
                        getUselessBj(type,order_sn,'','',brand,series,keyword,phone,gc_name,dealer,page,str_time,end_time);
                   }else{
                    getUselessBj(type,order_sn,order_state,feedback,brand,series,keyword,phone,gc_name,dealer,page);
                   }
                  e.preventDefault();
                });
            });

            function getUselessBj(type,order_sn,order_state,feedback,brand,series,keyword,phone,gc_name,dealer,page,str_time,end_time) {

                if (type == 'finishs') {
                    var url = "/dealer/order/orderlist/"+type+"?order_sn=" + order_sn
                                                + "&seller_remark=" + keyword
                                                + "&phone=" + phone
                                                + "&gc_name=" + gc_name
                                                + "&car_brand=" + brand
                                                + "&car_series=" + series
                                                + "&d_name=" + dealer
                                                + "&str_time=" + str_time
                                                + "&end_time=" + end_time
                                                + "&page="+ page


                }else{
                    var url = "/dealer/order/orderlist/"+type+"?order_sn=" + order_sn
                                                + "&seller_remark=" + keyword
                                                + "&order_state=" + order_state
                                                + "&feedback=" + feedback
                                                + "&car_brand=" + brand
                                                + "&car_series=" + series
                                                + "&phone=" + phone
                                                + "&gc_name=" + gc_name
                                                + "&d_name=" + dealer
                                                + "&page="+ page
                }
            $.ajax({
                url: url,
                datatype:'json',
            }).done(function (result) {
              //  console.log(result);
                $("table:last").remove();
                $(".fenye").remove();
                $("form").after(result);
                countdownAll()
            })
            }




    module.exports = {


    }


})





