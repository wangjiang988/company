<?php defined('InHG') or exit('Access Invalid!');?>
<style type="text/css">
 .tb-type1 td select{
         margin: 10px;
}
 #dealer{
 	width: 250px;
 }
 #brand{
 	margin-left: 50px;
 	width: 140px;
 }
</style>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['commons'];?></h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['manage'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" action="index.php" name="formSearch" id="formSearch">
    <input type="hidden" name="act" value="commons_manage" />
    <input type="hidden" name="op" value="common_manage" />
    <input type="hidden" value="1" name="query">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
        <th><label><?php echo $lang['common_name'];?></label></th>
         <td><input class="txt1" type="text" name="user" value="<?php echo $_GET['user'];?>" /></td>
         <th><label><?php echo $lang['common_username'];?></label></th>
         <td><input class="txt1" type="text" name="name" value="<?php echo $_GET['name'];?>" /></td>
         <th><label><?php echo $lang['common_phone'];?></label></th>
         <td><input class="txt1" type="text" name="mobile" value="<?php echo $_GET['mobile'];?>" /></td>
          <td colspan="4">
          <select name="order_state" class="querySelect">
              <option value=""><?php echo $lang['common_status'];?></option>
              <option value="1"<?php if($_GET['order_state'] == 1){?>selected<?php }?>><?php echo '待审核';?></option>
              <option value="2"<?php if($_GET['order_state'] == 2){?>selected<?php }?>><?php echo '审核通过';?></option>
              <option value="4"<?php if($_GET['order_state'] == 4){?>selected<?php }?>><?php echo '审核不通过';?></option>
            </select>
            </td>

          <th><label for="query_start_time"><?php echo $lang['common_time'];?></label></th>
          <td><input class="txt date" type="text" value="<?php echo $_GET['query_start_time'];?>" id="query_start_time" name="query_start_time">
            <label for="query_start_time">~</label>
            <input class="txt date" type="text" value="<?php echo date('Y-m-d', time());?>" id="query_end_time" name="query_end_time"/></td>
         </tr>

         <tr>
         <td colspan="2">
         <select name="brand" class="querySelect" id="brand">
              <option value=""><?php echo $lang['common_brand'];?></option>
               <?php foreach($output['brand_list'] as $brand_list) { ?>
              <option value="<?php echo $brand_list['dl_brand_id'];?>"><?php echo $brand_list['gc_name'];?></option>
              <?php } ?>
            </select>
         </td>
        <td colspan="2">
              <select name="dealer" class="querySelect" id="dealer">
              <option value=""><?php echo $lang['common_dealer'];?></option>
              
            </select>
         </td>

         <th><label><?php echo $lang['common_area'];?></label></th>
         <td colspan="2">
              <select name="common_area" class="querySelect">
              <option value="">
              <?php if(empty($_GET['common_area'])) { echo $lang['nc_please_choose']; } else {
                echo $_GET['common_area'];}?>                
              </option>
              <?php foreach($output['region'] as $region) { ?>
              <option value="<?php echo $region['d_areainfo'];?>"><?php echo $region['d_areainfo'];?></option>
              <?php } ?>
            </select>
         </td>
            <td><a href="javascript:viod(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
            </td>


        </tr>
      </tbody>
    </table>
  </form>
<div id="content"> </div>
    <?php require_once("commons_manage_table.php");?>

</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
$(function(){
     $('#query_start_time').datepicker({  maxDate: 0 });
    // $('#query_end_time').datepicker({dateFormat: 'yy-mm-dd'});

    $('#query_start_time').datepicker({
    dateFormat:'yy-mm-dd',
    onSelect: function( startDate ) {
        var $startDate = $( "#query_start_time" );
        var $endDate = $('#query_end_time');
        var endDate = $endDate.datepicker( 'getDate' );
        if(endDate < startDate){
            $endDate.datepicker('setDate', startDate - 3600*1000*24);
        }
        $endDate.datepicker( "option", "minDate", startDate );
    }
});
$('#query_end_time').datepicker({
    dateFormat:'yy-mm-dd',
    onSelect: function( endDate ) {
        var $startDate = $( "#query_start_time" );
        var $endDate = $('#query_end_time');
        var startDate = $startDate.datepicker( "getDate" );
        if(endDate < startDate){
            $startDate.datepicker('setDate', startDate + 3600*1000*24);
        }
        $startDate.datepicker( "option", "maxDate", endDate );
    }
});
    // $('#ncsubmit').click(function(){
    //    $('#formSearch').submit();
    // });


    var page=1;
    $("#ncsubmit").click(function(){
        getDealerTable(page);
    })

    //分页时传参
    $(document).on('click', '.pagination a', function (e) {
        var page = $(this).attr('href').split('curpage=')[1];
        getDealerTable(page);
        e.preventDefault();
    });

    $("#brand").change(function(){
      var aa = $("#brand").val();
      var _html='';
     $.ajax({
            url: 'index.php', 
            type: "get",
            dataType: "json",
            data: {
              act:'commons_manage',
              op:'ajaxquery',
              brand_id:aa
            },
            success: function (data) {
             $.each(data,function(item,index){
              _html += "<option value="+index.d_name+">"+index.d_name+"</option>";
             })
             $("#dealer").find('option').slice(1).remove();
             $("#dealer").append(_html);
            }
          })
    })

    //查询列表
  function getDealerTable(page)
  {
      $.ajax({
          url: "/index.php?"+$("#formSearch").serialize()+ "&curpage=" + page,
          datatype:'json',
      }).done(function (result) {
          $(".table").remove();
          $(".pagination").remove();
          $("#content").after(result);
      })
  }

});
</script>
