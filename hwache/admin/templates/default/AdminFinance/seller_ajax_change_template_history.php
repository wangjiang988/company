<?php defined('InHG') or exit('Access Invalid!');?>
<?php extract($output);?>
<div class="page finance">

    <div class="container">
        <div class="content">
            <table class="table tb-type2">
                <thead>
                <?php if($template['content']['type'] == 1){?>
                <tr class="thead blue">
                    <th >每月免费次数</th>
                    <th >超出每次收费</th>
                    <th >修改人</th>
                    <th >修改时间</th>
                </tr>
                <?php }?>
                </thead>
                <tbody id="datatable">
                <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
                    <?php foreach($output['list'] as $k => $v){ ?>
                        <tr class="hover">
                            <td><?php echo $v['detail']['now_val']['freetime']?></td>
                            <td><?php echo $v['detail']['now_val']['fee']?></td>
                            <td><?php echo $v['user_name']?></td>
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
        </div>

        <div class="info footer">
            <a href="javascript:closeLayer();" class="button">确定</a>
        </div>

    </div>

</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript">
    $(function(){

    });
</script>
