<?php defined('InHG') or exit('Access Invalid!');?>
<?php extract($output);?>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
           <h3>地区管理</h3>
            <ul class="tab-base">
                <li><a href="<?=url('area_manage','index')?>"><span>列表</span></a></li>
                <li><a href="JavaScript:void(0);" class="current"><span>详情</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form id="area_form" action="<?=url('area_manage','detail')?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="area_id" id="area_id" value="<?=$data['area_id'];?>" />
        <div class="pure-g line">
            <div class="pure-u-1-4">
                名称:
                  <input type="text" class="text" name="area_name" value="<?=$data['area_name'];?>" />
            </div>
            <div class="pure-u-1-4">
            </div>
        </div>
         <div class="pure-g line">
            <div class="pure-u-1-4">
                是否非大陆地区:
                  <input type="checkbox" name="not_mainland" value="1" <?php if($data['not_mainland']) echo "checked='checked'"; ?> >
            </div>
        </div>
        <div class="pure-g line">
            <div class="pure-u-1-2">
                 <a href="JavaScript:void(0);" class="button" id="sub_btn"><span>修改</span></a>
               <a href="JavaScript:history.go(-1);" class="button" id="sub_btn"><span>返回</span></a>
            </div>
        </div>

    </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script>
    $(function(){
          $("#sub_btn").click(function(){
                $("#area_form").submit();
          });
    })
</script>