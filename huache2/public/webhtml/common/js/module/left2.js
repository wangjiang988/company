var app1 = new Vue({
    el: '.slide',
    data: {

    },
    mounted:function(){
        setTimeout(function(){
            app1.slideLeft()
            //app1.initLeftHeight()
        },100)

    }
    ,
    methods:{
        slideLeft:function(){
            $(".panel-heading").click(function(){
                var _i    = $(this).find(".fa")
                var _next = $(this).next()
                if (_i.hasClass("fa-sort-down")) _i.removeClass('fa-sort-down').addClass("fa-sort-up")
                else _i.addClass('fa-sort-down').removeClass('fa-sort-up')
                if (_next.hasClass("collapse")) _next.removeClass('collapse')
                else _next.addClass('collapse')
                return false
            })

            $(".panel-left").click(function(){
                $(this).next().removeClass("hide").toggle()
                return false
            })
        },
        initLeftHeight:function(){
            $(".slide").css("height",$(".content").height())
        }
    }

})

