//ajax 分页
var brand_id=car_body_color=timeout_reason=time_circle='';
$(window).on('hashchange', function() {
    if (window.location.hash) {
        var page = window.location.hash.replace('#', '');
        if (page == Number.NaN || page <= 0) {
            return false;
        } else {
            getUselessBj(brand_id,car_body_color,timeout_reason,time_circle,page);
        }
    }
});

$(document).ready(function() {
    //查询车系颜色
    $(".btn-jquery-event-car-color").click(function(event) {
        var _this = $(this)
        var _brand_id = $("input[name=hfbrand-chexing]").next().attr("data-brand-id");
        var _dealer_id = $("input[name='dealer_id']").val();
        $.getJSON("/dealer/baojia/ajax-get-data/get-car-color?brand_id=" + _brand_id + "&dealer_id=" + _dealer_id, function (data) {
            var  str='';
            $.each(data,function(item,index){
                str=str+'<li data-body-color="'+index+'"><a><span>'+index+'</span></a></li>';
            })
            $("input[name='car_body_color']").parent().find('li').remove();
            $("input[name='car_body_color']").after(str);
            if(str==''){
                $("#body-color").html('<font color="red">没有对应的颜色</font>');
            }

        });
    })

    //reset表单
    $(".reset").click(function(){
        var local= window.location.href;
        window.location.href = local.substr(0,local.indexOf('?'));
    })

    //ajax提交表单
    $(".submit").click(function(){
        var brand_id = $("input[name=hfbrand-chexing]").next().attr("data-brand-id");
        if(brand_id == undefined){
            brand_id='';
        }
        var car_body_color =  $("input[name=car_body_color]").val();
        if(car_body_color=='失效时间不限'){
            car_body_color = '';
        }
        var timeout_reason = $("input[name=timeout_reason]").val();
        if(timeout_reason == '失效原因不限'){
            timeout_reason = '';
        }
        var time_circle = $("input[name=time_circle]").val();
        var page=1;
        getUselessBj(brand_id,car_body_color,timeout_reason,time_circle,page);
    })

    //分页时传参
    $(document).on('click', '.pagination a', function (e) {
        var brand_id = $("input[name=hfbrand-chexing]").next().attr("data-brand-id");
        if(brand_id == undefined){
            brand_id='';
        }
        var car_body_color =  $("input[name=car_body_color]").val();
        if(car_body_color=='失效时间不限'){
            car_body_color = '';
        }
        var timeout_reason = $("input[name=timeout_reason]").val();
        if(timeout_reason == '失效原因不限'){
            timeout_reason = '';
        }
        var time_circle = $("input[name=time_circle]").val();
        var page = $(this).attr('href').split('page=')[1];
        getUselessBj(brand_id,car_body_color,timeout_reason,time_circle,page);
        e.preventDefault();
    });
});


function getUselessBj(brand_id,car_body_color,timeout_reason,time_circle,page)
{
    $.ajax({
        url: "/dealer/baojialist/useless?brand_id=" + brand_id
                                        + "&car_body_color=" + car_body_color
                                        + "&timeout_reason=" + timeout_reason
                                        + "&time_circle=" + time_circle
                                        + "&page=" + page,
        datatype:'json',
    }).done(function (result) {
        $("table:last").remove();
        $(".tac").remove();
        $("table").after(result);
    })
}