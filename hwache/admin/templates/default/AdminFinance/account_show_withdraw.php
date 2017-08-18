<?php defined('InHG') or exit('Access Invalid!');?>
<?php extract($output);?>
<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><a href="index.php?act=admin_finance&op=home">平台财务</a></h3>
            <ul class="tab-base">
                <li><a  href="index.php?act=admin_finance&op=account_settlement"><span>收入支出申报</span></a></li>
                <li><a class="current"><span>提现手续费收入详情</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <div class="container">
        <div class="big_title">
            提现手续费收入详情
        </div>

        <div class="ml50 content">
            <div class="pure-g line">
                <div class="pure-u-1-3">
                    类别：<?= show_declare_type($declare['type'])?>
                </div>
                <div class="pure-u-1-3">
                    收入金额：￥<?= $declare['money']?>
                </div>
                <div class="pure-u-1-3">
                    支付方：<?php  if($declare['type'] == 20) echo "客户"; else echo "售方";?>
                </div>
            </div>
            <div class="pure-g line">
                <div class="pure-u-1-3">
                    业务申报年月：<?= $declare['year'].'-'.$declare['month'];?>
                </div>
                <div class="pure-u-1-3">
                    收入序列号：<?= $declare['income_series_number'];?>
                </div>
                <div class="pure-u-1-3">
                    收入凭证号： <?= $declare['income_voucher_number'];?>
                </div>
            </div>
            <?php if($declare['type'] == 20){?> <!--//客户-->
            <table class="table tb-type2">
                <thead>
                <tr class="thead blue">
                    <th >客户会员号</th>
                    <th >金额</th>
                    <th >时间</th>
                </tr>
                </thead>
                <tbody id="datatable">
                <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
                    <?php foreach($output['list'] as $k => $v){ ?>
                        <tr class="hover">
                            <td><?php echo $v['from']['phone']?></td>
                            <td><?php echo "￥ ".$v['money']?></td>
                            <td><?php echo $v['created_at']?></td>
                        </tr>
                    <?php } ?>
                <?php }else { ?>
                    <tr class="no_data" id="no_data" data-has="1">
                        <td colspan="8"><?php echo $lang['nc_no_record'];?></td>
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
            <?php }else{?>
                <table class="table tb-type2">
                    <thead>
                    <tr class="thead blue">
                        <th >售方用户名</th>
                        <th >金额</th>
                        <th >时间</th>
                    </tr>
                    </thead>
                    <tbody id="datatable">
                    <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
                        <?php foreach($output['list'] as $k => $v){ ?>
                            <tr class="hover">
                                <td><?php echo $v['from']['member_name']?></td>
                                <td><?php echo "￥ ".$v['money']?></td>
                                <td><?php echo $v['created_at']?></td>
                            </tr>
                        <?php } ?>
                    <?php }else { ?>
                        <tr class="no_data" id="no_data" data-has="1">
                            <td colspan="8"><?php echo $lang['nc_no_record'];?></td>
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
            <?php } ?>

        </div>

        <div class="info footer">

            <a href="javascript:void(0);" class="button" id="export_btn">导出</a>
            <a href="javascript:history.go(-1);" class="button">返回</a>
        </div>

    </div>

</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript">
    $(function(){
        $("#export_btn").click(function(){
            location.href =  location.href+'&export=1';
        })
    });
</script>
