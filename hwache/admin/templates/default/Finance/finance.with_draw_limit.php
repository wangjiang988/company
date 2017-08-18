<?php defined('InHG') or exit('Access Invalid!');?>
<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>客户财务</h3>
            <ul class="tab-base">
                <li><a href="index.php?act=finance&op=index"><span>客户财务</span></a>▷</li>
                <li><a class="current"><span>提现额度</span></a></li>
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
                <a href="index.php?act=finance&op=with_draw_line&uid=<?php echo $output['user']['id'] ;?>">提现路线</a>
            </span>
        </div>
        <div class="info span8">
            <span class="label">待用提现额度合计：</span>
            <span class="val">￥ <?php echo $output['user']['total_left_with_draw_limit']?> </span>
        </div>
        <div class="span2 pull-right">
            <input type="checkbox" id="order_by"/><label for="order_by">只看有排序 //TODO</label>
        </div>

    </form>
    <table class="table tb-type2">
        <thead>
        <tr class="thead blue">
            <th >转入时间</th>
            <th >转入方式</th>
            <th >转入方（提现收款方）</th>
            <th >转入凭证编号</th>
            <th >提现额度有效时限</th>
            <th >转入金额（提现总额度）</th>
            <th >已提现额度</th>
            <th >待用提现额度</th>
            <th >提现排序</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <?php foreach($output['list'] as $k => $v){ ?>
                <tr class="hover">
                    <td><?= $v['created_at']; ?></td>
                    <td><?= $v['recharge_type']==2? '银行转账':'线上支付' ?></td>
                    <td><?php if($v['recharge_type'] == 2) 
                                    echo $v['bank_account'].'/'.$v['bank_name'];
                             else 
                                echo  $v['recharge_type_name'].'/'.$v['alipay_user_name'];
                          ?></td>
                    <td><?php echo $v['trade_no'] ?></td>
                    <td><?php echo $v['consume']['wichdraw_end_at'] ?></td>
                    <td><?php echo '￥'.$v['recharge_money']?></td>
                    <td>
                        <?php
                        if($v['withdraw']['money'] <= 0) {
                            echo '￥'."0.00";
                        }else{ ?>
                         <a href='javascript:(0);' onclick='show_dialog("<?php echo $output['user']['id']?>","<?php echo $v['consume']['cid']?>")'>
                             <?php echo '￥'. $v['withdraw']['money']; ?>
                         </a>
                        <?php  }?>
                    </td>
                    <td><?php echo '￥'.((float)$v['recharge_money'] - (float)($v['withdraw']['money']))?></td>
                    <td><?php echo "TODO";?></td>
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
    //uid  用户id
    //cid  car_hc_user_consume cid
    function show_dialog(uid,cid){
        parent.layer.open({
            type: 2,
            title:"查看提现额度使用记录",
            skin: 'layui-layer-rim', //加上边框
            area: ['500px', 'auto'], //宽高
            content: '/index.php?act=finance&op=ajax_show_withdraws&uid='+uid+'&cid='+cid
        });
    }

</script>
