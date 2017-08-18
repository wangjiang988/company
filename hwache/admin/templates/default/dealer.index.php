<?php defined('InHG') or exit('Access Invalid!');?>
<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>经销商管理</h3>
      <ul class="tab-base">
        <li><a href="javascript:void(0);" class="current"><span>管理</span></a></li>
        <li><a href="index.php?act=dealer&op=dealer_add" ><span>新增</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch" class="form">
    <input type="hidden" value="dealer" name="act">
    <input type="hidden" value="index" name="op">
    <input type="hidden" value="1" name="query">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <td>销售品牌</td>
          <td><select class="form-control" name="brand_id">
              <option value="">不限</option>
              <?php foreach($output['brand_list'] as $brand):?>
                <option value="<?=$brand['gc_id'];?>"><?=$brand['gc_name'];?></option>
              <?php endforeach;?>
            </select>
          </td>
          <td>归属地区</td>
          <td><select class="form-control" name="dealer_area">
              <option value="">不限</option>
              <?php foreach($output['area_list'] as $area):?>
                <option value="<?=$area['d_areainfo'];?>"><?=str_replace("	",'',$area['d_areainfo']);?></option>
              <?php endforeach;?>
            </select>
          </td>
          <td>显示状态</td>
          <td><select class="form-control" name="is_show" >
              <option value="">不限</option>
              <option value="1">显示</option>
              <option value="0">始终不显示</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>经销商名称</td>
          <td>
              <div class="form-group">
                  <input type="search" class="form-control" name="dealer_name" value="">
              </div>
          </td>
          <td>经销商编号</td>
          <td colspan="2">
              <div class="form-group">
                  <input type="search" class="form-control" name="dealer_id" value="">
              </div>
          </td>
            <td>
                <div class="form-group">
                    <button type="button" class="btn btn-warning" id="search">查找</button>
                    <button type="button" class="btn btn-default" id="reset">重置</button>
                </div>
            </td>

<!--          <td><a href="javascript:viod(0);" id="ncsubmit" class="btn-search " title="查找">&nbsp;</a></td>-->
        </tr>
      </tbody>
    </table>
  </form>
  <?php require_once("dealer.index.table.php"); ?>

<script>
  $(function(){
    //reset表单
    $("#reset").click(function(){
      window.location.reload();
    })

    //查询
    var page=1;
    $("#search").click(function(){
      //todo 校验参数
      getDealerTable(page);
    })

    //分页时传参
    $(document).on('click', '.pagination a', function (e) {
      var page = $(this).attr('href').split('curpage=')[1];
      getDealerTable(page);
      e.preventDefault();
    });
  });

  $(window).on('hashchange', function() {
    if (window.location.hash) {
      var page = window.location.hash.replace('#', '');
      if (page == Number.NaN || page <= 0) {
        return false;
      } else {
        getDealerTable(page);
      }
    }
  });


  //查询报价
  function getDealerTable(page)
  {
    $.ajax({
      url: "/index.php?"+$("form").serialize()+ "&curpage=" + page,
      datatype:'json',
    }).done(function (result) {
      $(".table").remove();
      $(".pagination").remove();
      $("#formSearch").after(result)
    })
  }
</script>
