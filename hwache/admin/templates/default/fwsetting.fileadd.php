<?php defined('InHG') or exit('Access Invalid!');?>
<style>
  .tar{text-align: right;}
</style>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>文件设置</h3>
      <ul class="tab-base"><li><a href="index.php?act=fwsetting&op=files"><span>交车需要的文件设置</span></a></li><li><a class="current"><span>添加</span></a></li></ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <tbody>
      <tr>
          <td class="tar">车辆类型<?php echo $lang['nc_colon'];?></td>
          <td><select name="car_use_type" id="car_use_type">
                  <option value=""><?php echo $lang['nc_please_choose'];?>...</option>
                  <option value="0">非营业个人客车</option>
                  <option value="1">非营业企业客车</option>
                  <option value="2">其他</option>
              </select></td>
      </tr>
      <tr>
          <td class="tar">上牌（注册登记）车主身份类别<?php echo $lang['nc_colon'];?></td>
          <td>
              <select name="identity_id" id="identity_id">
                  <option value="">全部</option>
              </select>
          </td>
      </tr>
      <tr>
          <td class="tar">使用场合<?php echo $lang['nc_colon'];?></td>
          <td>
              <select name="cate_id" id="cate_id">
                  <option value="">全部</option>
              </select
          </td>
      </tr>
        <tr>
          <td class="tar">文件名称<?php echo $lang['nc_colon'];?></td>
          <td>
            <input type="text" class="w300" name="title" id="title_input" value="" />
          </td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td></td>
          <td>
            <a id="submitBtn" class="btn" href="javascript:void(0);"> <span><?php echo $lang['nc_submit'];?></span> </a>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script>
$(function(){
    $("#car_use_type").change(function(){
        let car_use_type = $(this).val();
        get_cate(car_use_type);
        get_identity(car_use_type);
    });
  // 表单验证
  $("#submitBtn").click(function(){
      let car_use_type = $("#car_use_type").val();
      let cate_id = $("#cate_id").val();
      let identity_id = $("#identity_id").val();
      let title = $("#title_input").val();
      if(car_use_type=='')
      {
          alert('请选择车辆类型');
          return false;
      }
      if(car_use_type!='' &&car_use_type!=2)
      {
          if(identity_id ==''){
              alert('请选择上牌（注册登记）车主身份类别');
              return false;
          }
      }
      if(cate_id==""){
          alert('请选择使用场合');
          return false;
      }
      if(title==""){
          alert('请输入文件名称');
          return false;
      }


    $("#form").submit();
  });


    //获取身份列表
    function get_identity(car_use_type)
    {
        if(car_use_type == 2)
        {
            $('#identity_id').html('<option value="">全部</option>');
            return false;
        }
        post('index.php?act=fwsetting&op=get_identity_by_carusetype',{'car_use_type':car_use_type})
            .then(function(res){
                if(res.data.code=200)
                {
                    let identity_id = <?=$_GET['identity_id']?$_GET['identity_id']:'0'?>;
                    html = '<option value="">全部</option>';
                    data = res.data.data;
                    if(data){
                        for (x in data)
                        {
                            if(data[x].id === ''+identity_id ){
                                html += '<option  value="'+data[x].id+'" selected>'+data[x].identity_name+'</option>'
                            }else{
                                html += '<option  value="'+data[x].id+'">'+data[x].identity_name+'</option>'
                            }
                        }
                        $('#identity_id').html(html);
                    }else{
                        $('#identity_id').html('<option value="">全部</option>');
                    }


                }else{
                    alert(res.data.msg);
                }
            })
    }

    //获取场合列表
    function get_cate(car_use_type)
    {
        post('index.php?act=fwsetting&op=get_cate_by_carusetype',{'car_use_type':car_use_type})
            .then(function(res){
                if(res.data.code=200)
                {
                    let cate_id = <?=$_GET['cate_id']?$_GET['cate_id']:'0'?>;
                    html = '<option value="">全部</option>';
                    data = res.data.data;
                    if(data){
                        for (x in data)
                        {
                            if(data[x].cate_id  === ''+cate_id ){
                                html += '<option  value="'+data[x].cate_id+'" selected>'+data[x].cate+'</option>'
                            }else{
                                html += '<option  value="'+data[x].cate_id+'">'+data[x].cate+'</option>'
                            }
                        }
                        $('#cate_id').html(html);
                    }else{
                        $('#cate_id').html('<option value="">全部</option>');
                    }


                }else{
                    alert(res.data.msg);
                }
            })
    }



});
</script>