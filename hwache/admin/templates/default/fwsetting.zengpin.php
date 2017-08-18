<?php defined('InHG') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>赠品设置</h3>
      <ul class="tab-base"><li><a class="current"><span>赠品设置</span></a></li><li><a href="index.php?act=fwsetting&op=zengpinadd"><span>添加</span></a></li></ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
    <form method="get" action="index.php" name="formSearch" id="formSearch">
        <input type="hidden" name="act" value="fwsetting" />
        <input type="hidden" name="op" value="zengpin" />
        <input type="hidden" name="is_search" value="1" />
        <input type="hidden" name="curpage" id="cur_page" value="<?php echo $_GET['curpage']?>" />
        <div class="pure-g line">
            <div class="pure-u-1-4">
                赠品名称:
                <input type="text" class="text" name="title" value="<?php echo trim($_GET['title']); ?>" />
            </div>
            <div class="pure-u-3-4">
                <div class="">
                    <a href="JavaScript:void(0);" class="button" id="sub_btn"><span>查找</span></a>
                </div>
            </div>
        </div>

    </form>
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th class="w270">赠品名称</th>
          <!--<th class="w270">价值</th>
          <th>所属车型</th>-->
<!--          <th>备注</th>-->
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
        <?php foreach($output['list'] as $k => $v){ ?>
        <tr class="hover">
          <td><?php echo $v['title']; ?></td>
          <!--<td><?php //echo $v['price'];?></td>
          <td><?php //echo $v['gc_name']?></td>-->
<!--          <td>--><?php ////echo $v['beizhu']?><!--</td>-->
          <td class="align-center">
              <a href="index.php?act=fwsetting&op=zengpinedit&id=<?php echo $v['id']?>">编辑</a>
<!--              <a href="index.php?act=fwsetting&op=zengpindel&id=--><?php //echo $v['id']?><!--">删除</a>-->
              <a href="javascript:confirm_del(<?php echo $v['id']?>)">删除</a>
          </td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td colspan="5"><?php echo $lang['nc_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
        <tr class="tfoot">
          <td colspan="20"><div class="pagination"> <?php echo $output['page'];?> </div></td>
        </tr>
        <?php } ?>
      </tfoot>
    </table>
  <div class="clear"></div>
</div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.serializejson.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>

<script type="text/javascript">

    $(function(){
        $("#sub_btn").click(function(){
            $("#cur_page").val(1);
            document.formSearch.submit();
        });
    });

    function confirm_del(id)
    {
        parent.layer.confirm('确定删除么？？', {
            btn: ['确认','取消'] //按钮
        }, function(){
            location.href='index.php?act=fwsetting&op=zengpindel&id='+id;
            closeLayer();
        }, function(){
            closeLayer();
        });
    }
    

</script>