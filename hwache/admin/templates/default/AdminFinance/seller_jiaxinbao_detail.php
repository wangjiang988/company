<?php defined('InHG') or exit('Access Invalid!');?>
<?php extract($output);?>
<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><a href="index.php?act=admin_finance&op=home">平台财务</a></h3>
            <ul class="tab-base">
                <li><a href="index.php?act=admin_finance&op=seller_jiaxinbao"><span>售方加信宝</span></a></li>
                <li><a class="current"><span>查看</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <div class="container">
       <!-- <div class="big_title">
            所有客户冻结余额总计：￥<span id="total_freeze_deposit"><?/*=$sum_freeze_deposit*/?></span>
        </div>-->
        <div class="mt-50 ml50 content border-bottom" >
            <div class="pure-g line">
                <div class="pure-u-1-3">
                    售方用户名：<?=$data['member_name']?>
                </div>
                <div class="pure-u-1-3">
                    售方手机：<?=$data['seller_phone']?>
                </div>
                <div class="pure-u-1-3">
                    冻结金额：￥ <?=$data['freeze_deposit']?$data['freeze_deposit']:0.00;?>
                </div>
            </div>
        </div>
        <form id="search_form">
            <input type="hidden" name="act"  value="admin_finance">
            <input type="hidden" name="op"  value="seller_jiaxinbao_detail">
            <input type="hidden" name="id"  value="<?=$_GET['id']?>">
            <input type="hidden" id="export" name="export" value="0">
            <input type="hidden" name="is_search" value="1" >

        <div class="ml50 content">

            <div class="pure-g line">
                <div class="pure-u-1-3">
                    订单号：<input type="text" name="order_id" value="<?=$_GET['order_id']?>">
                </div>
                <div class="pure-u-1-3">
                    冻结金额：￥ <input type="text" name="s_sum" value="<?=$_GET['s_sum']?>"> ~ <input type="text" name="e_sum" value="<?=$_GET['e_sum']?>">
                </div>
                <div class="pure-u-1-3">
                    <a href="javascript:$('#export').val(0);$('#search_form').submit();" class="button confirm">查找</a>
                    <a href="index.php?act=admin_finance&op=seller_jiaxinbao_detail&id=<?=$_GET['id']?>" class="button">重置</a>
                    <a href="javascript:void(0);" class="button" id="export_btn">导出</a>
                </div>
            </div>
         </div>
        </form>
            <table class="table tb-type2">
                <thead>
                <tr class="thead blue">
                    <th >订单号</th>
                    <th >订单时间</th>
                    <th >冻结金额</th>
                    <th >操作</th>
                </tr>
                </thead>
                <tbody id="datatable">
                <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
                    <?php foreach($output['list'] as $k => $v){ ?>
                        <tr class="hover">
                            <td><?php echo $v['id']?></td>
                            <td><?php echo $v['created_at']?></td>
                            <td>￥ <?php echo $v['sum']? $v['sum']:0.00; ?></td>
                            <td><a href="javascript:void(0);" onclick="show_detail(<?=$v['id'];?>)">查看</a></td>
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
</div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.serializejson.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>



<script type="text/javascript">
    $(function(){
        $("#export_btn").click(function () {
            $('#export').val(1);
            $('#search_form').submit();
        })


    });

    function show_detail(order_id){
        parent.layer.open({
            type: 2,
            title:"查看冻结余额明细",
            skin: 'layui-layer-rim', //加上边框
            area: ['600px', '500px'], //宽高
            content: "index.php?act=admin_finance&op=account_ajax_get_jiaxinbao_detail&role=2&order_id="+order_id
        });
    }

</script>
