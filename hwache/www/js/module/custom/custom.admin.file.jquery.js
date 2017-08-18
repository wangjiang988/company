define(function(require, exports, module) {
  //车辆用途 选择事件 特别设置
  $(".dropdown-vehicular-applications li").click(function() {
      var _index     = $(this).index()
      var _dropdown  = $(".dropdown-categories")
      var _showclass = "",
          _hideclass = ""
      if (_index == 0) {
        _showclass = ".categories-def"
        _hideclass = ".categories-advance"
      } else if (_index == 1) {
        _showclass = ".categories-advance"
        _hideclass = ".categories-def"
      }
      $(".display-transformation").remove()
      _dropdown.parents("td").find(".error-div").hide()
      var _firsttext = _dropdown.find(_showclass).show().eq(0).text()
      _dropdown.find(_hideclass).hide()/*.parent().next().val(_firsttext).end().prev().find("span:eq(0) span").text(_firsttext)
      $(".dropdown-categories li:eq(0)").click()*/ //自动加载文件资料 需要的话请解开注释
      _dropdown.prev().find(".dropdown-label span").text("请选择上牌车主身份类别")
      _dropdown.next().val("")
  })

  function formatTmp(tmp,item){
      return tmp.replace("{id}",   item.id)
                .replace("{0}" ,   item.isself == 1 ? (item.title.indexOf("原件") == -1 ? item.title+'原件' : item.title) : item.title+'复印件' )
                .replace("{1}" ,   item.file_url)
                .replace("{1}" ,   item.file_url)
                .replace("{2}" ,   item.isself == 1 ? "√" : item.num )
                .replace("{id}",   item.id)
                .replace("{id}",   item.id)
                .replace("{class}",item.file_url == null ? "hide" : "")
  }

  //上牌（注册登记）车主身份类别 选择事件 特别设置 
  $(".dropdown-categories li").click(function() {
    var _this = $(this)
    $("#addUseOccasionsFile").find("input[name=type_id]").val(_this.attr("type-id"));
    $("#addUseOccasionsFile").find("input[name=shenfen_id]").val(_this.attr("data-id"));
    _this.parents("td").find(".error-div").hide()
    $.ajax({
      type: "post",
      url: "/dealer/ajaxCustfile/filelist",
      data: {
        type_id: _this.attr("type-id"),
        shenfen_id: _this.attr("data-id"),
        //dealer_id:$(".use-occasions-add-wrapper").find("input[name='dealer_id']").val(),
        dealer_id: $('#addUseOccasionsFile').find("input[name='dealer_id']").val(),
        _token: $("meta[name=csrf-token]").attr('content')
      },
      dataType: "json",
      success: function(data) {
        //do
        $(".display-transformation").remove()
        var _file = ""
        var _one      = "",
            _fronttpl = $("#AnotherFileTmp").html()
        var _ones     = "",
            _fronttp0 = $("#FirstFileTmp").html()
        var _two      = "",
            _three    = "",
            _four     = "",
            _five     = "",
            _twos     = "",
            _threes   = "",
            _fours    = "",
            _fives    = "";
        $.each(data.file, function(index, item) {
          if (index == 1) {
            $.each(item, function(index, items) {
              if (index == 0) {
                _ones += formatTmp(_fronttp0,items)
              } else {
                _one += formatTmp(_fronttpl,items)
              }
            })

          } else if (index == 2) {
            $.each(item, function(index, items) {
              if (index == 0) {
                _twos += formatTmp(_fronttp0,items)
              } else {
                _two += formatTmp(_fronttpl,items)
              }


            })

          } else if (index == 3) {
            $.each(item, function(index, items) {
              if (index == 0) {
                _threes += formatTmp(_fronttp0,items)
              } else {
                _three += formatTmp(_fronttpl,items)
              }

            })

          } else if (index == 4) {
            $.each(item, function(index, items) {
              //console.log(index)
              if (index == 0) {
                _fours += formatTmp(_fronttp0,items)
              } else {
                _four += formatTmp(_fronttpl,items)
              } 

            })

          } else if (index == 5) {
            $.each(item, function(index, items) {
              if (index == 0) {
                _fives += formatTmp(_fronttp0,items)
              } else {
                _five += formatTmp(_fronttpl,items)
              }

            })
          }
        })

        if (!_one) {
          var _tbl_front = $("#one").find("tr:eq(1)")
          _tbl_front.find('td').slice(1).remove()
        }
        var _tbl_front = $("#one")
        _tbl_front.find("tr:eq(0) td").slice(1).remove()
        _tbl_front.find("tr.file-tr").slice(1).remove()
        //console.log("has run?")
        _tbl_front.find("td").after(_ones)
        _tbl_front.find("tr:first").after(_one)

        if (!_two) {
          var _tbl_front1 = $("#two").find("tr:eq(1)")
          _tbl_front1.find('td').slice(1).remove()
        }

        var _tbl_front1 = $("#two")
        _tbl_front1.find("tr:eq(0) td").slice(1).remove()
        _tbl_front1.find("tr.file-tr").slice(1).remove()
        _tbl_front1.find("td").after(_twos)
        _tbl_front1.find("tr:first").after(_two)

        if (!_three) {
          var _tbl_front2 = $("#three").find("tr:eq(1)")
          _tbl_front2.find('td').slice(1).remove()
        }

        var _tbl_front2 = $("#three")
        _tbl_front2.find("tr:eq(0) td").slice(1).remove()
        _tbl_front2.find("tr.file-tr").slice(1).remove()
        _tbl_front2.find("td").after(_threes)
        _tbl_front2.find("tr:first").after(_three)

        if (!_four) {
          var _tbl_front3 = $("#four").find("tr:eq(1)")
          _tbl_front3.find('td').slice(1).remove()
        }

        var _tbl_front3 = $("#four")
        _tbl_front3.find("tr:eq(0) td").slice(1).remove()
        _tbl_front3.find("tr.file-tr").slice(1).remove()
        _tbl_front3.find("td").after(_fours)
        _tbl_front3.find("tr:first").after(_four)

        if (!_five) {
          var _tbl_front4 = $("#five").find("tr:eq(1)")
          _tbl_front4.find('td').slice(1).remove()
        }

        var _tbl_front4 = $("#five")
        _tbl_front4.find("tr:eq(0) td").slice(1).remove()
        _tbl_front4.find("tr.file-tr").slice(1).remove()
        _tbl_front4.find("td").after(_fives)
        _tbl_front4.find("tr:first").after(_five)
      }
    })
  })


  $(document).delegate(".use-occasions-edit", "click", function(event) {
      useOccasionsEdit()
  })
  $(document).delegate(".use-occasions-del", "click", function(event) {
      useOccasionsDel()
  })
    //客户文件
    //添加文件
  $(function() {
    $(document).delegate('a.use-occasions-add-file', 'click', function(event) {
      var _passenger  = $(".btn-jquery-Passenger")
      var _selectFlag = true
      if (_passenger.eq(0).find("input[type='hidden']").val().trim() == "") {
          _selectFlag = false
          _passenger.eq(0).next().show()
      } 
      else if (_passenger.eq(1).find("input[type='hidden']").val().trim() == "") {
          _selectFlag = false
          _passenger.eq(1).next().show()
      } 
      else{
          _passenger.eq(0).next().hide()
          _passenger.eq(1).next().hide()
      }
      if (!_selectFlag) {
          return
      }

      require("module/common/hc.popup.jquery")
      var _this = $(this)
      var _tr = _this.parents("tr").eq(0)
      var _source =_this.parents("table").find(".file-tr").eq(0)
      var _title = _source.find("td").eq(0).find(".use-occasions-title").text()
      $("#addUseOccasionsFile").find("input[name=type]").val(_this.attr('data-id'));
      var _win = $("#addUseOccasionsFile")
      _win.hcPopup({'width': '500'})
      $(".add-file-title").text(_title)
      //修改功能逻辑，发送ajax
      $.ajax({
          type: "post",
          url: "/dealer/ajaxCustfile/occasions",
          data: {
            occasions : _this.attr('data-id'),
            _token: $("meta[name=csrf-token]").attr('content')
          },
          dataType: "json",
          success: function(data) {
            var _file = ""
            var _def  = '资料内容' //资料名称
            $.each(data, function(index, item) {
              _file += "<li data-id='" + $(item)[0].file_id + "'><a><span>" + $(item)[0].title + "</span></a></li>"
            })

            $(".dropdown-file").find("li").remove().end().append(_file).find("input[type='hidden']").val(_def).end().prev().find('.dropdown-label span').text(_def)
           }
       })


      _win.find(".do").unbind('click').bind("click", function() {
        var _sub        = $(this)
        var _form       = _win.find("form")
        var _flag       = true
        var _radio      = _form.find("input[name='use-occasions-file-radio']")
        var _radiocheck = _form.find("input[name='use-occasions-file-radio']:checked")
        var _fileid = _form.find("input[name='file_id']")
        _fileid.parents(".btn-file").next().removeAttr("style")
        _radio.parents("td").find(".error-div").hide()
        if (isNaN(_fileid.val())) {
          _flag = false
          _fileid.parents(".btn-file").next().css("display","inline-block")
          //errorshowhide()
        }
        else if (!_radiocheck[0]) {
          _flag = false
          _radio.parents("td").find(".error-div").show()
          //errorshowhide(_radio.parents("td").find(".error-div"))
        }

        if (_flag) {
          //判断选中的是原件还是复印件
          var _index     = _radio.eq(0).prop("checked") ? 0 : 1
          
          var _filecount = ""
          var _filename  = _win.find(".dropdown-label:eq(0) span").text()
          var _tmp       = null //
          var _fileup    = "" //上传的文件
          var _filetr    = _this.parents("table").eq(0).find(".file-tr")
          //判断文件资料是否存在 
          var options = {
            success: function(data) {

              var _fileinput = _win.find("input[type='file']")
              if (data.error_code == 0) {
                if (_index == 0) {
                  _tmp = $("#FirstFileTmp").html()
                  _filename += "原件"
                    //是否第一次添加 
                  if (!_filetr.find(".display-transformation")[0]) {
                    _tmp = $("#FirstFileTmp").html()
                  } else {
                    _tmp = $("#AnotherFileTmp").html()
                  }
                  _filecount = "√"
                } else if (_index == 1) {
                  //是否第一次添加 
                  if (!_filetr.find(".display-transformation")[0]) {
                    _tmp = $("#FirstFileTmp").html()

                  } else {
                    _tmp = $("#AnotherFileTmp").html()
                      //_filecount = "√"
                  }
                  //_tmp = $("#AnotherFileTmp").html()
                  _filecount = _win.find(".dropdown-label:eq(1) span").text()
                  _filename += "复印件"
                }
                
                if (!isempty(_fileinput)) {
                  _fileup = _fileinput.val().substring(_fileinput.val().lastIndexOf('\u005c') + 1)
                }
                
                _tmp = _tmp
                          .replace("{0}" ,   _filename)
                          .replace("{1}" ,   data.pic_path)
                          .replace("{1}" ,   data.pic_path)
                          .replace("{2}" ,   _filecount == 0 ? "√" : _filecount )
                          .replace("{id}",   data.id)
                          .replace("{id}",   data.id)
                          .replace("{id}",   data.id)
                          .replace("{class}",data.pic_path == "www" ? "hide" : "")

                if (!_filetr.find(".display-transformation")[0]) {
                  _filetr.eq(0).find("td:last").after(_tmp)
                } else {
                  _filetr.last().after(_tmp)
                }
                 
                _win.hide()

                _fileinput.val("")
                $(".use-occasions-add-file-name").text("")
                $(".use-occasions-edit").unbind('click').bind("click", function(event) {
                  useOccasionsEdit()
                })
                $(".use-occasions-del").unbind('click').bind("click", function(event) {
                  useOccasionsDel()
                })

                //qingkong
                _fileid.val("")
                _radiocheck.prop("checked",false)
                _win.find(".dropdown-label:eq(1) span").text('1')

              } else {
                errorshowhide(_sub.next().next().next())
              }
              
            },
            beforeSubmit: function() {

            }
            ,
            clearForm: false
          }
          // ajaxForm 
          _form.ajaxForm(options)
            // 表单提交
          _form.ajaxSubmit(options) 
        }

      })

      _win.find(".file-upload").unbind('click').bind("click", function() {
        $(this).parent().next().find("input[type='file']").click().unbind('change').bind("change", function() {
          $(".use-occasions-add-file-name").html($(this).val())
          $("#add_upload").find("#hfFile").val($(this).val())
        })
      })

    })
  })

  function useOccasionsEdit() {
    event = arguments.callee.caller.arguments[0] || window.event
    require("module/common/hc.popup.jquery")
      //tbl elem
    var _this = $(event.target)
    var _source = $(".file-tr").eq(0)
    var _title = _source.find("td").eq(0).find(".use-occasions-title").text()
    var _curtd = _this.parent() //修改删除的td
    var _td2 = _curtd.prev() //文件格式td
    var _td1 = _td2.prev() //数量td
    var _td0 = _td1.prev() //文件资料td
    var _win = $("#editUseOccasionsFile")
      //popup elem
    var _filedrop = _win.find(".dropdown-file") //文件资料下拉框
    var _countdrop = _win.find(".dropdown-bt") //复印件数量下拉框
    var _radio = _win.find("input[type='radio']") //原件、复印件单选
    var _filename = _win.find("input[type='file']").prev() //上传的文件名
      //popup
    _win.hcPopup({
      'width': '500'
    })
    $(".add-file-title").text(_title)
    $("#btn-p").attr("disabled",false)
      //set value
    /*
     * 设置原件复印件
     * 判断是否是身份证 
     */  
    var _idx1 , idx2 , idx3 , _td0text , 
        _sou = "\u539f\u4ef6" ,_copy = "\u590d\u5370\u4ef6" , 
        _numid = "\u8eab\u4efd\u8bc1";
    _idx1 = _td0.text().indexOf(_sou)
    _idx2 = _td0.text().indexOf(_copy)
    _idx3 = _td0.text().indexOf(_numid)
    if (_idx1 != -1 && _idx2 != -1) {
       if (_idx1 > _idx2) {
          _td0text = _td0.text().replace(_sou, "")
          _radio.eq(0).prop("checked", "checked")
          $("#btn-p").attr("disabled",true)
       }else{
          _td0text = _td0.text().replace(_copy, "")
          _radio.eq(1).prop("checked", "checked")
       }
    }else{
       if (_idx3 != -1) {
           _td0text = _td0.text()
       }else{
           _td0text = _td0.text().replace(_sou, "").replace(_copy, "")
       }
       if (_idx1 > 0) {
          _radio.eq(0).prop("checked", "checked")
          $("#btn-p").attr("disabled",true)
       } else {
          _radio.eq(1).prop("checked", "checked")
       }
    }
    //_td0text = _td0.text().replace("\u539f\u4ef6", "").replace("\u590d\u5370\u4ef6", "")
    _filedrop.parent().find(".dropdown-label span").text(_td0text).end()
                      .find("input[type='hidden']").val(_td0text)
    

    var _count = _td1.text() == "√" ? '0' : _td1.text()
    _countdrop.parent().find(".dropdown-label span").text(_count).end()
                       .find("input[type='hidden']").val(_count)
    var _txt = _td2.text().trim()     
    _filename.find("span").text((_txt == "null" || _txt == "www") ? "" : _td2.text())
    if (_filename.find("span").text() != "") {
      _win.find(".file-upload").text("\u91cd\u65b0\u4e0a\u4f20")
    }else{
      _win.find(".file-upload").text("\u70b9\u51fb\u4e0a\u4f20")
    }

    //bind event
    _win.find(".do").unbind('click').bind("click", function() {
      var _sub = $(this)
      var _form = _win.find("form")
      var _filecount = _countdrop.next().val()
      var _fileup = "" //上传的文件
      var _fileinput = _win.find("input[type='file']")
      if (!isempty(_fileinput)) {
        _fileup = _fileinput.val().substring(_fileinput.val().lastIndexOf('\u005c') + 1)
      }
      var id = _this.parent().find('.use-occasions-edit').attr('data-id');
      $("#editUseOccasionsFile").find("input[name=id]").val(id);

      var options = {
        success: function(data) {
          console.log(data)
          if (data.pic_path != 'www') {
            _td2.find(".file-prev").html("<a href='/upload/"+data.pic_path+"' target='_bank'>"+data.pic_path+"</a>")
          } else {
            _td2.find(".file-prev").text(_td2.text())
          }
          _td1.text(_filecount == "0" ? "√" : _filecount)
          _win.hide()

        },
        beforeSubmit: function() {},
        error: function() {
          _td1.text(_filecount)
          _td2.find(".file-prev").text(_fileup)
          _win.hide()

        },
        clearForm: true
      }

      // ajaxForm 
      _form.ajaxForm(options)
        // 表单提交
      _form.ajaxSubmit(options)



    })

    _win.find(".file-upload").unbind('click').bind("click", function() {
      $(this).parent().next().find("input[type='file']").click().unbind('change').bind("change", function() {
        $(".use-occasions-edit-file-name").text($(this).val())
      })
    })
  }

  function useOccasionsDel() {
    require("module/common/hc.popup.jquery")
    var _win = $("#delUseOccasionsFile")
    _win.hcPopup({
      'width': '400'
    })
    event       = arguments.callee.caller.arguments[0] || window.event
    var _this   = $(event.target)
    var _delobj = null
    var _tr     = _this.parents("tr").eq(0)
    var _tmp    = null
    if (!_tr.attr("id")) {
      _delobj = _tr.find("td").slice(1)
    } else {
      _delobj = _tr
    }
    _tmp = _tr.next()
    _win.find(".do").unbind('click').bind("click", function() {
      var id = _this.parent().find('.use-occasions-del').attr('data-id');
      
      $.ajax({
        type: "post",
        url: "/dealer/ajaxCustfile/del",
        data: {
          id: id,
          dealer_id: $('.use-occasions-add-wrapper').find("input[name=dealer_id]").val(),
          _token: $("meta[name=csrf-token]").attr('content')
        },
        dataType: "json",
        success: function(data) {
          if (data.error_code == 1) {
            _delobj.remove()
            if (_tmp.attr("id")) {
              _tr.append(_tmp.html())
              if (!_tr.attr("id")){
                _tmp.remove()
              }
            }else{
              _tmp.find("td").slice(1).remove()
            }
            //$("#tip-success").hcPopup({content:data.message})
            //alert('删除成功');
          } else {
            $("#tip-error").hcPopup({
              content: data.error_msg
            })
          } 
          _win.hide()

        }
      })//ajax

    })//bind


  }


  $(".add-use-occasions").click(function() {
    require("module/common/hc.popup.jquery")
    var _win = $("#addOccasions")
    _win.hcPopup({
      'width': '400'
    })
    var _form = _win.find("form")
    _win.find(".do").unbind('click').bind("click", function() {
      var file = $("#add_upload").find(".use-occasions-add-file-name").text();
      $("#add_upload").find("#hfFile").val(file);
      var _input = _win.find("input[type='text']")
      var _flag = true
      var _occasions = ""
      var options = {
        success: function(data) {

        },
        beforeSubmit: function() {},
        error: function() {
          var _addOccasionsTpl = $("#OccasionTmp").html()
          _occasions = _addOccasionsTpl.replace("{0}", _input.val())
          $(".use-occasions-add-wrapper").before(_occasions)
          _win.hide()
        },
        clearForm: true
      }


      if (isempty(_input)) {
        errorshowhide(_input.next())
        _flag = false
      } else {

      }
      if (_flag) {
        // ajaxForm 
        _form.ajaxForm(options)
          // 表单提交
        _form.ajaxSubmit(options)
      }

    })


  })

$(document).delegate('input[name="use-occasions-file-radio"]:eq(0)', 'click', function(event) {
   //$('input[name="use-occasions-file-radio"]:eq(1)').val(0)
})

$(document).delegate('input[name="use-occasions-file-radio"]:eq(1)', 'click', function(event) {
   var _parent = $(this).parents("label")
   var _span = _parent.find(".dropdown-label span")
   var _select = _parent.find(".dropdown-menu li")
   _select.eq(parseInt(_span.text())-1).click()
 
})
//

  //
})