<?php defined('InHG') or exit('Access Invalid!');?>

<div class="tabmenu">
  <ul class="tab pngFix">
    <li class="active"><a href="javascript:;">添加资料</a></li>
  </ul>
</div>

<div class="item-publish">
  <form method="get" action="index.php">
    <input type="hidden" name="form_submit" value="ok">
    <input type="hidden" name="act" value="yijiao" />
    <input type="hidden" name="op" value="updata" />
    <input type="hidden" name="class_id" id="class_id" value="" />
  
    <div class="ncsc-form-goods">
    选择车型：
    <div class="wrapper_search">
  <div class="wp_sort">
    <div id="dataLoading" class="wp_data_loading">
      <div class="data_loading"><?php echo $lang['store_goods_step1_loading'];?></div>
    </div>
    <div class="sort_selector">
      <div class="sort_title"><?php echo $lang['store_goods_step1_choose_common_category'];?>
        <div class="text" id="commSelect">
            <div><?php echo $lang['store_goods_step1_please_select'];?></div>
            <div class="select_list" id="commListArea">
              <ul>
                <?php if(is_array($output['staple_array']) && !empty($output['staple_array'])) {?>
                <?php foreach ($output['staple_array'] as $val) {?>
                <li  data-param="{stapleid:<?php echo $val['staple_id']?>}"><span nctype="staple_name"><?php echo $val['staple_name']?></span><a href="JavaScript:void(0);" nctype="del-comm-cate" title="<?php echo $lang['nc_delete'];?>">X</a></li>
                <?php }?>
                <?php }?>
                <li id="select_list_no" <?php if (!empty($output['staple_array'])) {?>style="display: none;"<?php }?>><span class="title"><?php echo $lang['store_goods_step1_no_common_category'];?></span></li>
              </ul>
            </div>
        </div>
        <i class="icon-angle-down"></i>
      </div>
    </div>
    <div id="class_div" class="wp_sort_block">
      <div class="sort_list">
        <div class="wp_category_list">
          <div id="class_div_1" class="category_list">
            <ul>
              <?php if(isset($output['goods_class']) && !empty($output['goods_class']) ) {?>
              <?php foreach ($output['goods_class'] as $val) {?>
              <li class="" nctype="selClass" data-param="{gcid:<?php echo $val['gc_id'];?>,deep:1,tid:<?php echo $val['type_id'];?>}"> <a class="" href="javascript:void(0)"><i class="icon-double-angle-right"></i><?php echo $val['gc_name'];?></a></li>
              <?php }?>
              <?php }?>
            </ul>
          </div>
        </div>
      </div>
      <div class="sort_list">
        <div class="wp_category_list blank">
          <div id="class_div_2" class="category_list">
            <ul>
            </ul>
          </div>
        </div>
      </div>
      <div class="sort_list sort_list_last">
        <div class="wp_category_list blank">
          <div id="class_div_3" class="category_list">
            <ul>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- <div class="alert">
    <dl class="hover_tips_cont">
      <dt id="commodityspan"><span style="color:#F00;"><?php echo $lang['store_goods_step1_please_choose_category'];?></span></dt>
      <dt id="commoditydt" style="display: none;" class="current_sort"><?php echo $lang['store_goods_step1_current_choose_category'];?><?php echo $lang['nc_colon'];?></dt>
      <dd id="commoditydd"></dd>
    </dl>
  </div> -->
</div>
      
      <dl>
        <dt><i class="required">*</i>名称：</dt>
        <dd><input type="text" class="text w300" name="title" value=""></dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>数量：</dt>
        <dd><input type="text" class="text w300" name="num" value=""></dd>
      </dl>

      <dl>
        <dt><i class="required">*</i>所属类型：</dt>
        <dd>
          <select name="type" id="">
         
            <option value="文件资料">文件资料</option>
          <option value="随车工具">随车工具</option>
          </select>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>备注：</dt>
        <dd>
          
          <textarea name="notice" id="" cols="30" rows="10"></textarea>
        </dd>
      </dl>
      
    </div>
    <div class="bottom tc hr32">
      <label class="submit-border">
        <input type="submit" class="submit" value="提交">
      </label>
    </div>
  </form>
</div>

<script src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script>
<script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/store_goods_add.step1.js"></script>
<script>
SEARCHKEY = '<?php echo $lang['store_goods_step1_search_input_text'];?>';
RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';
</script>
