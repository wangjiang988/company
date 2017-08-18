<?php defined('InHG') or exit('Access Invalid!');?>
<?php extract($output)?>
 <div class="content">
     <div class="pure-g line">
         <div class="pure-u-1-4">
             售方用户名:<?=$settlement['member_name']?>
         </div>
         <div class="pure-u-1-4">
             姓名:<?=$settlement['member_truename']?>
         </div>
         <div class="pure-u-1-4">
             身份证号:<?=$settlement['seller_card_num']?>
         </div>
         <div class="pure-u-1-4">
             结算年月:<?=$settlement['year'].'-'.$settlement['month']?>
         </div>
     </div>
     <table class="table tb-type2">
         <thead>
         <tr class="thead blue">
             <th >订单号</th>
             <th >成本序列号</th>
             <th >结算金额</th>
             <th >项目明细及金额</th>
         </tr>
         </thead>
         <tbody id="datatable">
         <?php $num=0;?>
         <?php foreach($output['list'] as $k => $v){ ?>
            <?php $num+=(float)$v['money'] ?>
             <tr class="hover">
                 <td><?= $v['order_id'] ?></td>
                 <td><?= $v['firstcost_series_number'] ?></td>
                 <td><?= $v['money'] ?></td>
                 <td><?= $v['description'] ?></td>
             </tr>
         <?php } ?>
         </tbody>
         <tfoot>
         <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
             <tr class="tfoot">
                 <td colspan="4"><div class="pagination"> <?php echo $output['page'];?> </div></td>
             </tr>
         <?php } ?>
         </tfoot>
     </table>
    <div class="center font-14">结算总金额：￥<?=$num?></div>

     <div class="panel_footer" style="margin-top:50px;padding: 10px;text-align: center;">
            <span class="label">
                <a  href="javascript:void(0);"  onclick="do_export();"  class="button">导出</a>
                <a href="javascript:void(0);" onclick="closeLayer();" class="button">取消</a>
            </span>
     </div>
 </div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.serializejson.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>

<script type="text/javascript">
function do_export(){
       location.href = location.href+'&export=1';
}
</script>