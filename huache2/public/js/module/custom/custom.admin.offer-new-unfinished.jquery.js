define(function (require,exports,module) { 

	$(document).delegate(".del","click",function(){
		var _this = $(this)
		var _tr   = _this.parents("tr")
		var _win  = $("#DelWin")
		require("module/common/hc.popup.jquery")
        _win.hcPopup({'width':'450'}).find(".do").unbind('click').click(function(event) {
        	baojiaExcute('delete',_this.attr('data-value'),function(){
                var _tr = _this.parents("tr")
                _tr.fadeOut('400', function() {
                    _tr.remove();
                    var _length = $(".tbl.tbl-blue").find('tr').length
                    if (_length == 1) {
                        window.location.reload()
                    }

                })
            })
        }) 

	})

    $(document).delegate(".stop","click",function(){
        var _this = $(this)
        var _tr   = _this.parents("tr")
        var _win  = $("#StopWin")
        require("module/common/hc.popup.jquery")
        var _id = _tr.find('td').eq(0).text()
        var _sid = _this.parent().prev().find("span").text()
        if (_id !== '') { 
            _win.find("#msg").text("确定要直接将报价"+_id+"暂停吗？");
        }
        // _win.find("#msg").text(_win.find("#msg").text().replace("{0}",_id))
        _win.hcPopup({'width':'460'}).find(".do").unbind('click').click(function(event) {
            baojiaExcute('stop',_this.attr('data-id'),function(data){
                // console.log(data);
                if(data.data.ids)
                {
                    _win.find(".do").text('是，一起暂时下架').removeClass('w100')
                    _win.find(".sure").text('否，不暂时下架').removeClass('w100')
                    var _select = `<select name="" id="" multiple="" style="width: 350px;height: 40px;margin-bottom:10px;">
                                     {0}
                                  </select>`
                    var _option = ""          
                    var _array  =   data.data.series.split(',')
                    for (var i = 0; i < _array.length; i++) {
                         _option += "<option>"+_array[i]+"</option>"
                     } 
                    _select = _select.replace("{0}",_option)
                    _win.find("#msg").html("<p>"+_sid+"已经暂时下架，同车源其他报价:</p>"+_select+'<p>也需要暂时下架么？</p>');
                    _win.hcPopup({'width':'460'}).find(".do").unbind('click').click(function(event) {
                        baojiaExcute('stop_all', data.data.ids);
                    }).end().find('.sure').unbind('click').click(function(){
                        window.location.reload()
                    })

                }else
                {
                    window.location.reload()
                }
            });
        }) 

    })

    $(document).delegate(".susp","click",function(){

        var _this = $(this)
        var _tr   = _this.parents("tr")
        var _win  = $("#DelWin")
        require("module/common/hc.popup.jquery")
        var _id = _tr.find('td').eq(0).text()
        var _sid = _this.parent().prev().find("span").text()
        if (_id !== '') {
            _win.find("#msg").text("确定要直接将报价"+_id+"终止吗？");
        }
        _win.hcPopup({'width':'460'}).find(".do").unbind('click').click(function(event) {
            baojiaExcute('suspensive',_this.attr('data-id'),function(data){
                //console.log(data)
                if(data.data.ids)
                {
                    _win.find(".do").text('是，一起下架').removeClass('w100')
                    _win.find(".sure").text('否，不下架').removeClass('w100')
                    var _select = `<select name="" id="" multiple="" style="width: 350px;height: 40px;margin-bottom:10px;">
                                     {0}
                                  </select>`
                    var _option = ""          
                    var _array  =   data.data.series.split(',')
                    for (var i = 0; i < _array.length; i++) {
                         _option += "<option>"+_array[i]+"</option>"
                     } 
                    _select = _select.replace("{0}",_option)
                    _win.find("#msg").html("<p>"+_sid+"已经下架，同车源其他报价:</p>"+_select+'<p>也需要下架么？</p>');
                    _win.hcPopup({'width':'460'}).find(".do").unbind('click').click(function(event) {
                        baojiaExcute('suspensive_all', data.data.ids);
                    }).end().find('.sure').unbind('click').click(function(){
                        window.location.reload()
                    })
                }else
                {
                    window.location.reload()
                }

            });
        })

    })
    //一键恢复所有
    $(document).delegate("#shelves-all","click",function(){

        var _this = $(this)
        var _tr   = _this.parents("tr")
        var _win  = $("#ShelvesAllWin")
        require("module/common/hc.popup.jquery")
        var _id = _tr.find('td').eq(0).text()
        _win.find("#msg").text("确定要直接将报价"+_id+"终止吗？");
        _win.hcPopup({'width':'450'}).find(".do").unbind('click').click(function(event) {
        baojiaExcute('shelves-all',_this.attr('data-id'));
    }) 

    })


    //一键暂停所有
    $(document).delegate("#Cease-all","click",function(){

        var _this = $(this)
        var _tr   = _this.parents("tr")
        var _win  = $("#CeaseAllWin")
        require("module/common/hc.popup.jquery")
        var _id = _tr.find('td').eq(0).text()
        _win.find("#msg").text("确定要直接将报价"+_id+"终止吗？");
        _win.hcPopup({'width':'450'}).find(".do").unbind('click').click(function(event) {
        baojiaExcute('ceaseves-all',_this.attr('data-id'));
    }) 

    })

    $(document).delegate(".renew","click",function(){

        var _this = $(this)
        var _tr   = _this.parents("tr")
        var _win  = $("#RenewWin")
        require("module/common/hc.popup.jquery")
        var _id = _tr.find('td').eq(0).text()
        if (_id !== '') {
            _win.find("#msg").text("确定要直接将报价"+_id+"恢复吗？");
        }
        _win.hcPopup({'width':'450'}).find(".do").unbind('click').click(function(event) {
        baojiaExcute('renew',_this.attr('data-id')
            );
    }) 

    })


	$(".reset").click(function(){
		window.location.reload();
	})

	//$(document).delegate(".find-cars","click",function() {
	  $(".find-cars").click(function(){
		var _html = '';
		$.ajax({
            url: '/dealer/baojia/ajax-get-data/relation',
            type: "get",
            dataType: "json",
            data: {
            	bj_type:$(this).attr('data-type'),
            	brand_id:$(this).attr('data-id')
            },
            success: function (data) {
            	$.each(data,function(index,item){
            		_html += "<li class='find-standard' data-id="+item.gc_id+"><a><span>"+item.gc_name+"</span></a></li>"
            	})
                $('.place').find('li').remove();
            	$('.place').append(_html);
            },
        });
	});

	  $(document).delegate(".find-standard","click",function(){
	  	var _html = '';
		$.ajax({
            url: '/dealer/baojia/ajax-get-data/specifica',
            type: "get",
            dataType: "json",
            data: {
                bj_type:$('.types').val(),
            	parent_id:$(this).attr('data-id')
            },
            success: function (data) {
            	$.each(data,function(index,item){
            		_html += "<li class='find-price' data-id="+item.gc_id+"><a><span>"+item.gc_name+"</span></a></li>"
            	})
                $('.standard').find('li').remove();
            	$('.standard').append(_html);
            },
        });
	  })

    $(document).delegate(".find-price","click",function() {
    	var bj_id = $(this).attr("data-id")
		$.ajax({
            url: '/dealer/baojia/ajaxsubmit/find-price',
            type: "post",
            dataType: "json",
            data: {
            	_token:$("meta[name=csrf-token]").attr('content'),
            	bj_id:bj_id,
            },
            success: function (data) {
                $('.guide-price').addClass("guidelines");
            	$(".guide-price").html('厂商指导价:'+parseFloat(data.zhidaojia).formatMoney());
            	$(".brand").val(bj_id);
            },
        });
	});

    $(".btn-price").delegate('li', 'click', function(event) {
         var _this   = $(this)
         var _parant = _this.parents(".btn-group")
        _parant.find("input[name=pid]").val(_this.attr('data-id'))
    })

    $(".btn-other-event").delegate('li', 'click', function(event) {
         var _this   = $(this)
         var _parant = _this.parents(".btn-group")
          _this.addClass('active').siblings().removeClass("active")
        _parant.find("input[type='hidden']").val(_this.attr('data-id'))
        _parant.find(".dropdown-label span").text(_this.text())
    })

    function unix_to_datetime(unix) {
        var now = new Date(parseInt(unix) * 1000);
        return now.toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ");
    }


    var type = $("input[name=bj_type]").val();
    var brand=cars=standard=dealer='';
    $(window).on('hashchange', function() {
        var brand=cars=standard=dealer='';
        if (window.location.hash) {
            var page = window.location.hash.replace('#', '');
            if (page == Number.NaN || page <= 0) {
                return false;
            } else {
                getUselessBj(brand,cars,standard,dealer,page,type);
            }
        }
    });



    $(document).ready(function() {
      $(".submit-search").click(function(){
            var brand = $("input[name=brand]").val();
            var cars =  $("input[name=cars]").val();
            var standard = $("input[name=standard]").val();
            var dealer = $("input[name=dealer]").val();
            var type = $("input[name=bj_type]").val();
            var page=1;
            if (type == "online" || type=="suspensive") {
                var xianche = $("input[name=xianche]").val();
                var state = $("input[name=state]").val()
                getUselessBjia(brand,cars,standard,dealer,page,type,xianche,state);
            } else if (type == "effective") {
                var start_time = $("input[name=start_time]").val();
                var end_time = $("input[name=end_time]").val();
                getUselessBaoj(brand,cars,standard,dealer,page,start_time,end_time);
            } else {
                getUselessBj(brand,cars,standard,dealer,page,type);
            }
            
       })

        $(document).on('click', '.pagination a', function (e) {
            var brand = $("input[name=brand]").val();
            var cars =  $("input[name=cars]").val();
            var standard = $("input[name=standard]").val();
            var dealer = $("input[name=dealer]").val();
            var page = $(this).attr('href').split('page=')[1];
            if (type == "online" || type=="suspensive") {
                var xianche = $("input[name=xianche]").val();
                var state = $("input[name=state]").val()
                getUselessBjia(brand,cars,standard,dealer,page,type,xianche,state);
            } else if (type == "effective") {
                var start_time = $("input[name=start_time]").val();
                var end_time = $("input[name=end_time]").val();
                getUselessBaoj(brand,cars,standard,dealer,page,start_time,end_time);
            } else {
                getUselessBj(brand,cars,standard,dealer,page,type);
            }
            e.preventDefault();
        });
    });   

    function getUselessBj(brand,cars,standard,dealer,page,type) {
    $.ajax({
        url: "/dealer/baojialist/"+ type +"?brand=" + brand
                                        + "&cars=" + cars
                                        + "&standard=" + standard
                                        + "&dealer=" + dealer
                                        + "&page=" + page,
        datatype:'json',
    }).done(function (result) {
        $("table:last").remove();
        $(".tac").remove();
        $("table").after(result);
    })
    }

    function getUselessBjia(brand,cars,standard,dealer,page,type,xianche,state) {
    $.ajax({
        url: "/dealer/baojialist/"+ type +"?brand=" + brand
                                        + "&cars=" + cars
                                        + "&standard=" + standard
                                        + "&dealer=" + dealer
                                        + "&xianche=" + xianche
                                        + "&state=" + state
                                        + "&page=" + page,
        datatype:'json',
    }).done(function (result) {
        $("table:last").remove();
        $(".tac").remove();
        console.log(result)
        $("table").after(result);
    })
    }

    function getUselessBaoj(brand,cars,standard,dealer,page,start_time,end_time) {
    $.ajax({
        url: "/dealer/baojialist/effective?brand=" + brand
                                        + "&cars=" + cars
                                        + "&standard=" + standard
                                        + "&dealer=" + dealer
                                        + "&start_time=" + start_time
                                        + "&end_time=" + end_time
                                        + "&page=" + page,
        datatype:'json',
    }).done(function (result) {
        $("table:last").remove();
        $(".tac").remove();
        $("table").after(result);
    })
    }

    
})