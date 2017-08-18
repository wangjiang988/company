<?php defined('InHG') or exit('Access Invalid!');?>
<?php extract($output);?>
<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><a href="index.php?act=admin_finance&op=home">平台财务</a></h3>
            <ul class="tab-base">
                <li><a  href="index.php?act=admin_finance&op=user_online_pay_limit"><span>客户线上支付退款周期设定</span></a></li>
                <li><a class="current"><span>修改</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <div class="container">
        <div class="big_title2">
            修改退款周期
        </div>
            <div class="ml50 content">
                <table class="table tb-type2">
                    <thead>
                    <tr class="thead blue">
                        <th >线上支付渠道</th>
                        <th >客户申请退款有效周期</th>
                        <th >平台办理退款有效周期</th>
                        <th >操作</th>
                    </tr>
                    </thead>
                    <tbody id="datatable">
                    <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
                        <?php foreach($output['list'] as $k => $v){ ?>
                            <tr class="hover">
                                <td><?php echo $v['name']?></td>
                                <td><?php echo $v['user_range'].'日'?></td>
                                <td><?php echo $v['admin_range'].'日'?></td>
                                <td><a href="javascript:void(0);" onclick="show_update_dialog(<?=$v['id']?>);">修改</a></td>
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
            </div>

        <div class="info footer">
            <a href="javascript:history.go(-1);" class="button  ml-20">返回</a>
            <a href="index.php?act=admin_finance&op=user_update_online_pay_limit_history" class="ml-20 font-14">查看修改记录</a>
        </div>

    </div>

</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.serializejson.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>


<script type="text/javascript">
    $(function(){

    });

    //添加修改弹框
    function show_update_dialog(id){
//        show_dialog('修改退款周期','#html','500px','300px');
//        $('#object_id').val(id);
        parent.layer.open({
            type: 2,
            title:"修改退款周期",
            skin: 'layui-layer-rim', //加上边框
            area: ['500px', '300px'], //宽高
            content: '/index.php?act=admin_finance&op=user_ajax_update_form&template_id='+id
        });
    }


</script>
