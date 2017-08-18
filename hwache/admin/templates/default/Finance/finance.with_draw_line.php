<?php defined('InHG') or exit('Access Invalid!');?>
<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>客户财务</h3>
            <ul class="tab-base">
                <li><a href="index.php?act=finance&op=index"><span>客户财务</span></a>▷</li>
                <li><a class="current"><span>提现路线</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>

    <form method="get" action="index.php" name="formSearch" id="formSearch">
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
            <span class="val">
                <a href="index.php?act=finance&op=with_draw_limit&uid=<?php echo $output['user']['id'] ;?>">提现额度</a>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <a href="index.php?act=finance&op=withdraw_line_operation&uid=<?php echo $output['user']['id'] ;?>">操作记录</a>
            </span>
        </div>


    </form>
    <table class="table tb-type2">
        <thead>
        <tr class="thead blue">
            <th >提现方式</th>
            <th >收款路线</th>
            <th >收款方账号户名</th>
            <th >状态</th>
            <th >操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <?php foreach($output['list'] as $k => $v){ ?>
                <tr class="hover <?php if($v['status'] == 0 ) echo "gray"; ?>">
                    <td><?php echo show_line_type($v['line_type'])?></td>
                    <td><?php echo $v['account_name'] ?></td>
                    <td><?php echo $v['account_info']?></td>
                    <td><?php echo show_withdraw_line_status($v['status'],$v['line_type'])?></td>
                    <td><?php if($v['can_operate'] ) { ?>
                             <?php if($v['status'] != 0 ) { ?>
                               <a href="javascript:void(0);" onclick="show_dialog(<?php echo $output['user']['id'];  ?>,<?php echo $v['uwl_id']?>,0);">设为失效 </a>
                                <?php if(!in_array($v['line_type'],[1,3])) { ?>
                                    <a href="javascript:void(0);" onclick="show__edit_dialog(<?php echo $v['uwl_id']?>)">修改</a>
                                <?php }?>
                            <?php }else{ ?>
                                <a href="javascript:void(0);" onclick="show_dialog(<?php echo $output['user']['id'];  ?>,<?php echo $v['uwl_id']?>,1);">恢复有效 </a>
                                <a href="javascript:void(0);" onclick="show__del_dialog(<?php echo $v['uwl_id']?>);">删除</a>
                            <?php }?>

                       <?php } ?>
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
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>

<script type="text/javascript">
    $(function(){

    });
    //设置是否有效
    function show_dialog(uid,uwl_id,status){
        parent.layer.open({
            type: 2,
            title:"设置状态",
            skin: 'layui-layer-rim', //加上边框
            area: ['500px', '260px'], //宽高
            content: '/index.php?act=finance&op=ajax_set_status&uid='+uid+'&uwl_id='+uwl_id+'&status='+status
        });
    }

    //修改
    function show__edit_dialog(uwl_id){
        parent.layer.open({
            type: 2,
            title:"修改提现线路",
            skin: 'layui-layer-rim', //加上边框
            area: ['500px', '300px'], //宽高
            content: '/index.php?act=finance&op=withdraw_line_edit&uwl_id='+uwl_id
        });
    }
    //删除
    function show__del_dialog(uwl_id){
        parent.layer.open({
            type: 2,
            title:"删除提现路线",
            skin: 'layui-layer-rim', //加上边框
            area: ['500px', '300px'], //宽高
            content: '/index.php?act=finance&op=withdraw_line_del&uwl_id='+uwl_id
        });
    }


</script>
