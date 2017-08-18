<?php defined('InHG') or exit('Access Invalid!');?>
<style type="text/css">
 table.search {
      margin: 10px 0px 30px;
}
 .search td select{
      margin-left: 10px;
      margin-right: 25px;
      width:200px;
      height: 30px;
 }
 .search td button{
      padding:3px 20px;
      background-color:rgb(255,137,49);
      border: 1px solid rgb(246,142,61);
      border-radius: 4px;
      color: white;
      height: 30px;
 }
</style>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['provinces_index'];?></h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['manage'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" action="index.php" name="formSearch" id="formSearch">
    <input type="hidden" name="act" value="provinces" />
    <input type="hidden" name="op" value="index" />
    <table class="search">
      <tbody>
      <tr>
         <td><label style="font-size:15px">省/直辖市:</label></td>
         <td>
           <select name="area_id">
          <?php if(count($output['province_list']) == 1) {?>
            <option value="<?php echo $output['province_list']['0']['area_id'];?>"><?php echo $output['province_list']['0']['area_name']; } else {?> </option>
            <option value="">不限</option>
            <?php }?>
            <?php foreach($output['province_lists'] as $provinces){?>
            <option value=<?php echo $provinces['area_id'];?>><?php echo $provinces['area_name'];}?></option>
           </select>
         </td>
         <td>
           <label>
             <button>查找</button>
           </label>
         </td>
      </tr>
      </tbody>
    </table>
  </form>
<div id="content"> </div>
    <table class="table tb-type2 nobdb">
    <thead>
      <tr class="thead" style="background-color:rgb(197,235,255);">
        <th class="align-center"><?php echo $lang['provinces_number'];?></th>
        <th class="align-center"><?php echo $lang['provinces_province'];?></th>
        <th class="align-center"><?php echo $lang['provinces_other']; ?></th>
        <th class="align-center"><?php echo $lang['common_operate']; ?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(count($output['province_list'])>0){?>
      <?php foreach($output['province_list'] as $province){?>
      <tr class="hover">
        <td class="align-center"><?php echo $province['area_id'];?></td>
        <td class="align-center"><?php echo $province['area_name'];?></td>
        <td class="align-center">
        <?php if ($output['citydata'][$province['area_id']]) {?>
          <?php foreach($output['citydata'][$province['area_id']] as $citydata) { ?>
            <?php echo $citydata['area_name'];?> 、
          <?php }?>
        <?php }?>
        </td>
        <td class="nowrap align-center">
          <a href="index.php?act=provinces&op=edit&area_id=<?php echo $province['area_id'];?>">编辑</a>
        </td>
      </tr>
      <?php }?>
      <?php }else{?>
      <tr class="no_data">
        <td colspan="17"><?php echo $lang['nc_no_record'];?></td>
      </tr>
      <?php }?>
    </tbody>
    <tfoot>
      <tr class="tfoot">
      </tr>
    </tfoot>
  </table>
  <div class="pagination"><?php echo $output['page'];?> </div>

</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

