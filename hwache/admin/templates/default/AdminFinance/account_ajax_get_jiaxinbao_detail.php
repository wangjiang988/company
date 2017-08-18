<?php defined('InHG') or exit('Access Invalid!');?>
<?php extract($output)?>
 <div class="content">
    <div class="pure-g line">
        <div class="pure-u-1-3">
            订单号: <?=$_GET['order_id']?>
        </div>
    </div>
     <?php if($_GET['role']==1){ ?>
     <table class="table tb-type2">
         <thead>
         <tr class="thead blue">
             <th >发生时间</th>
             <th >冻结/解冻</th>
             <th >冻结来源</th>
             <th >解冻去向</th>
             <th>冻结增减金额</th>
         </tr>
         </thead>
         <tbody id="datatable">
         <?php foreach($output['list'] as $k => $v){ ?>
             <tr class="hover">
                 <td><?=$v['created_at'] ?></td>
                 <td><?=$v['type']==10?"冻结":"解冻" ?></td>
                 <td><?=$v['type']==10? $v['item']:''?></td>
                 <td><?=$v['type']==20? $v['item']:''?></td>
                 <td><?=$v['type']==10?"+ ￥".$v['money']:"- ￥".$v['money'] ?></td>
             </tr>
         <?php } ?>
         </tbody>

     </table>
     <?php }else{ ?>
         <table class="table tb-type2">
             <thead>
             <tr class="thead blue">
                 <th >冻结/解冻</th>
                 <th >项目</th>
                 <th >说明</th>
                 <th>冻结增减金额</th>
             </tr>
             </thead>
             <tbody id="datatable">
             <?php foreach($output['list'] as $k => $v){ ?>
                 <tr class="hover">
                     <td><?=$v['type']==10?"冻结":"解冻"; ?></td>
                     <td><?=$v['item']?></td>
                     <td><?=$v['description'] ?></td>
                     <td><?=$v['type']==10?"+ ￥".$v['money']:"- ￥".$v['money'] ?></td>
                 </tr>
             <?php } ?>
             </tbody>
         </table>

     <?php } ?>
     <div class="pure-g line">
         <div class="pure-u-2-3">

         </div>
         <div class="pure-u-1-3">
             冻结金额: <?=$sum?>
         </div>
     </div>
     <div class="panel_footer" style="margin-top:100px;padding: 10px;text-align: center;">
            <span class="label">
                <a href="javascript:void(0);" onclick="closeLayer();" class="button">取消</a>
            </span>
     </div>
 </div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.serializejson.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>

<script type="text/javascript">



</script>