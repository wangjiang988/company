
<?php defined('InHG') or exit('Access Invalid!');?>
<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>客户财务</h3>
            <ul class="tab-base">
                <li><a href="index.php?act=finance&op=index"><span>客户财务</span></a>▷</li>
                <li><a class="current"><span>特别事项</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>

    <form method="get" action="index.php" name="ajax_form" id="ajax_form">
        <input type="hidden" name="act" value="finance" />
        <input type="hidden" name="op" value="with_draw_limit" />
        <input type="hidden" name="uid" value="<?php echo $output['user']['id'] ;?>" />
        <input type="hidden" name="is_search" value="1" />
        <div class="info">
            <span class="label">客户会员号：</span>
            <span class="val"><?php echo $output['user']['id'] ;?></span>
            <span class="label">客户姓名：</span>
            <span class="val"><?php echo $output['user']['last_name'].$output['user']['first_name'] ;?></span>
            <span class="label">客户手机：</span>
            <span class="val"><?php echo $output['user']['phone'] ;?></span>
            <span class="val pull-right">
                <a class="button" href="index.php?act=finance&op=special_add&uid=<?=$output['user']['id']?>">发起申请</a>
            </span>
        </div>
        <div class="info">
            <span class="label">客户可用余额：</span>
            <span class="val">￥ <?php echo $output['user']['account']['avaliable_deposit']?> </span>
            <span class="label">平台冻结可用余额：</span>
            <span class="val">￥ <?php echo $output['user']['account']['temp_deposit']?> </span>
            <span class="val pull-right">
                状态：
                <select name="status" id="status">
                    <option value="">全部</option>
                    <option value="0"
                    <?php if($output['status'] ==="0")  echo "selected";?>
                    >待批准</option>
                    <option value="1"
                        <?php if($output['status'] ==1)  echo "selected";?>
                    >已通过</option>
                    <option value="2"
                        <?php if($output['status'] ==2)  echo "selected";?>
                    >未通过</option>
                </select>
            </span>
        </div>

    </form>
    <table class="table tb-type2">
        <thead>
        <tr class="thead blue">
            <th >工单编号</th>
            <th >工单时间</th>
            <th >申请项目与金额</th>
            <th >原因</th>
            <th >申请人</th>
            <th >状态</th>
            <th >操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <?php foreach($output['list'] as $k => $v){ ?>
                <tr class="hover">
                    <td> <?=$v['id']?></td>
                    <td><?=$v['created_at']?></td>
                    <td><?=$v['name'].'， ￥'.$v['money']?></td>
                    <td><?= $v['reason'] ?></td>
                    <td><?=$v['apply_admin_name'] ?></td>
                    <td><?=show_special_status($v['status']) ?></td>
                    <td>
                        <a href="index.php?act=finance&op=user_special_detail&uid=<?=$output['user']['id']?>&id=<?=$v['id']?>">查看</a>
                    </td>
                </tr>
            <?php } ?>
        <?php }else { ?>
            <tr class="no_data">
                <td colspan="9"><?php echo $lang['nc_no_record'];?></td>
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
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>

<script type="text/javascript">
$(function(){
    $('#status').change(function () {
         let status =$(this).val();
         if(status=="")
         {
             location.href = "index.php?act=finance&op=user_special_index&uid=<?=$_GET['uid']?>";
         }else{
             location.href = "index.php?act=finance&op=user_special_index&uid=<?=$_GET['uid']?>&status="+status;
         }

    });
});
</script>
