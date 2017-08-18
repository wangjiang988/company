define(function (require, exports, module) {

    var app = new Vue({
        el: '.slide',
        data: {

        },
        mounted:function(){
            setTimeout(function(){
                app.slideLeft()
                //app.initLeftHeight()
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
                    $(this).find("i").toggleClass("fa-sort-up")
                    return false
                })
            },
            initLeftHeight:function(){
                $(".slide").css("height",$(".content").height())
            }
        }
        ,
        watch:{

            '':function(){

            }

        }

    })


    module.exports = {

        init : function(obj){

        }
    }
})
