<?php defined('InHG') or exit('Access Invalid!');?>
<?php extract($output)?>
 <div class="content">
     <table class="table tb-type2">
         <thead>
         <tr class="thead blue">
             <th >收入凭证号</th>
             <th >提交人</th>
             <th >时间</th>
         </tr>
         </thead>
         <tbody id="datatable">
         <?php foreach($output['income_list'] as $k => $v){ ?>
             <tr class="hover">
                 <td><?= $v['now_val'] ?></td>
                 <td><?= $v['operation']['user_name'] ?></td>
                 <td><?= $v['created_at'] ?></td>
             </tr>
         <?php } ?>
         </tbody>
         <tfoot>
         <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
             <tr class="tfoot">
                 <td colspan="3"><div class="pagination"> <?php echo $output['page'];?> </div></td>
             </tr>
         <?php } ?>
         </tfoot>
     </table>

     <div class="mt-50"></div>
     
     <table class="table tb-type2">
         <thead>
         <tr class="thead blue">
             <th >成本凭证号</th>
             <th >提交人</th>
             <th >时间</th>
         </tr>
         </thead>
         <tbody id="datatable">
         <?php foreach($output['firstcost_list'] as $k => $v){ ?>
             <tr class="hover">
                 <td><?= $v['now_val'] ?></td>
                 <td><?= $v['operation']['user_name'] ?></td>
                 <td><?= $v['created_at'] ?></td>
             </tr>
         <?php } ?>
         </tbody>
         <tfoot>
         <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
             <tr class="tfoot">
                 <td colspan="3"><div class="pagination"> <?php echo $output['page'];?> </div></td>
             </tr>
         <?php } ?>
         </tfoot>
     </table>
 </div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.serializejson.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>

<script type="text/javascript">



</script>