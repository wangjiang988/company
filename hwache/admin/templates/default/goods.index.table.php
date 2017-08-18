<table class="table table-hover table-bordered">
    <tr class="info">
        <th>报价编号</th>
        <th>用户名</th>
        <th>经销商归属地区</th>
        <th>报价车型</th>
        <th>报价状态</th>
        <th>发布审核</th>
        <th>操作 </th>
    </tr>
    <?php if($output['goods_list']):?>
    <?php foreach ($output['goods_list'] as $key => $value): ?>
        <tr class="hover edit">
            <td><?php echo $value['bj_serial']; ?></td>
            <td><?php echo $value['member_name']; ?></td>
            <td><?php echo str_replace("	",'',$value['d_areainfo']); ?></td>
            <td><?php echo $value['gc_name']; ?></td>
            <td><?php echo $value['bj_status'];?> </td>
            <td><?php
                if($value['bj_status'] == '失效报价' ||$value['bj_status'] == '新建未完'){
                    $public_type = '无';
                }else{
                    $public_type = $value['bj_is_public']?($value['bj_is_public']==1?'正常':'异常'):'无';
                }
                echo $public_type;
                ?>
            </td>
            <td>
                <a href="index.php?act=goods&op=show&bj_id=<?php echo $value['bj_id']; ?>" >查看</a>
            </td>
        </tr>
    <?php endforeach ?>
    <?php else:?>
        <tr class="no_data">
            <td colspan="7"><?php echo $lang['nc_no_record']?></td>
        </tr>
    <?php endif;?>
</table>
<?php if($output['goods_list']):?>
<div class="pagination"> <?php echo $output['page'];?> </div>
<?php endif;?>