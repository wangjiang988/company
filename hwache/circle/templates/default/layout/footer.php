<?php defined('InHG') or exit('Access Invalid!');?>

<div class="clear">&nbsp;</div>
<div id="tbox">
<a id="gotop" href="JavaScript:void(0);" title="<?php echo $lang['go_top'];?>" style="display:none;"></a>
</div>
<div id="footer">
  <p><a href="<?php echo SHOP_SITE_URL;?>"><?php echo $lang['nc_index'];?></a>
    <?php if(!empty($output['nav_list']) && is_array($output['nav_list'])){?>
    <?php foreach($output['nav_list'] as $nav){?>
    <?php if($nav['nav_location'] == '2'){?>
    | <a  <?php if($nav['nav_new_open']){?>target="_blank" <?php }?>href="<?php switch($nav['nav_type']){
    	case '0':echo $nav['nav_url'];break;
    	case '1':echo urlShop('search', 'index',array('cate_id'=>$nav['item_id']));break;
    	case '2':echo urlShop('article', 'article',array('ac_id'=>$nav['item_id']));break;
    	case '3':echo urlShop('activity', 'index',array('activity_id'=>$nav['item_id']));break;
    }?>"><?php echo $nav['nav_title'];?></a>
    <?php }?>
    <?php }?>
    <?php }?>
  </p>
 <center><div style=line-height:21px>��Դ�ṩ��<a href=http://www.haoid.cn target=_blank><font color=red>��վ����Դ</font></a>
<br>&nbsp;
<a href=http://www.haoid.cn target=_blank>��վ����Դ</a> | <a href=http://www.haoid.cn target=_blank>��Ʒ��ҵԴ��</a> | <a href=http://zhuji.haoid.cn target=_blank>��վ����Դ�ռ䡢����</a> | <a href=http://zhuji.haoid.cn/ target=_blank>90G����������ҵģ��</a> | <a href=http://www.haoid.cn/ target=_blank>վ��������</a>
<br>
���ྫƷ��ҵ��Դ������<a href=http://www.haoid.cn target=_blank>��վ����Դ</a></font>
</div></center>
  <?php echo html_entity_decode($GLOBALS['setting_config']['statistics_code'],ENT_QUOTES); ?> </div>
<?php if (C('debug') == 1){?>
<div id="think_page_trace" class="trace">
  <fieldset id="querybox">
    <legend><?php echo $lang['nc_debug_trace_title'];?></legend>
    <div>
      <?php print_r(Tpl::showTrace());?>
    </div>
  </fieldset>
</div>
<?php }?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.cookie.js"></script>
</body>
</html>
