<?php defined('InHG') or exit('Access Invalid!');?>
<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>客户财务</h3>
            <ul class="tab-base">
                <li><a href="index.php?act=finance&op=index"><span>客户财务</span></a>▷</li>
                <li><a href="index.php?act=finance&op=with_draw_line&uid=<?=$_GET['uid']?>"><span>提现路线</span></a>▷</li>
                <li><a class="current"><span>操作记录</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>

        <div class="info">
            <span class="label">收款方账号户名：</span>
            <span class="val">
                <select name="" id="selector" onchange="pick_withdraw_line();">
                    <?php if($output['withdraw_line_list']){ ?>
                         <?php foreach ($output['withdraw_line_list'] as $v){?>
                             <?php if($v['bank_id']>0){ ?>
                                 <option value="<?php echo $v['uwl_id']?>"
                                 <?php if($output['current_withdraw_line']['uwl_id'] ==$v['uwl_id'] ) echo "selected"?>
                                 ><?php echo $v['bank']['bank_code'].'/'.$v['bank']['bank_register_name'] ?></option>
                            <?php }else{ ?>
                                <option value="<?php echo $v['uwl_id']?>"
                                    <?php if($output['current_withdraw_line']['uwl_id'] ==$v['uwl_id'] ) echo "selected"?>
                                ><?php echo $v['account_code'] ?></option>
                            <?php } ?>
                         <?php } ?>
                    <?php }else{ ?>
                        <option value="">已删除</option>
                    <?php } ?>

                </select>
            </span>
        </div>


    <table class="table tb-type2">
        <thead>
        <tr class="thead blue">
            <th >收款方账号户名</th>
            <th >收款路线</th>
            <th >状态</th>
            <th >修改人</th>
            <th >操作</th>
            <th >备注</th>
            <th >时间</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($output['log_list']) && is_array($output['log_list'])){ ?>
            <?php foreach($output['log_list'] as $k => $vv){ ?>
                <tr class="hover ">
                    <td>
                        <?php if($output['current_withdraw_line']['bank_id']>0){ ?>
                            <?php echo $output['current_withdraw_line']['bank']['bank_code'].'/'.$output['current_withdraw_line']['bank']['bank_register_name']?>
                        <?php }else{ ?>
                            <?php echo $output['current_withdraw_line']['account_code']?>
                        <?php } ?>
                    </td>
                    <td> <?php echo $output['current_withdraw_line']['account_name']?></td>
                    <td><?php echo show_withdraw_line_status( $output['current_withdraw_line']['status'],$output['current_withdraw_line']['line_type']); ?></td>
                    <td><?php echo $vv['user']['admin_name'] ?></td>
                    <td>
                        <?php echo $vv['operation'];
                        if($vv['details']){
                            foreach ($vv['details'] as $detail){
                               echo  show_change_detail($detail);
                            }
                        }
                        ?>
                    </td>
                    <td>
                        <?php echo $vv['remark'] ?>
                    </td>
                    <td>
                        <?php echo $vv['created_at'] ?>
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

    //选择提现线路
    function pick_withdraw_line(){
        var uwl_id  = $("#selector").find("option:selected").val();
        console.log(uwl_id);
        location.href="index.php?act=finance&op=withdraw_line_operation&uid=<?php echo  $_GET['uid']?>&uwl_id="+uwl_id;
    }


</script>
