<table class="table tb-type2">
    <tbody>
    <tr class="noborder">
        <td width="required">
            <label>工单编号:</label>
        </td>
        <td class="vatop rowform">
            <?=$output['find']['id']?>
        </td>
    </tr>

    <tr class="noborder">
        <td width="required">
            <label>审核预存图片:</label>
        </td>
        <td class="vatop rowform">
            <img src="<?=getImgidToImgurl($output['find']['bank_img'])?>?imageView2/2/w/200/h/100" />
            <?php
            if($output['find']['sc_bank_img']){
            ?>
            <img src="<?=getImgidToImgurl($output['find']['sc_bank_img'])?>?imageView2/2/w/200/h/100" />
            <?php } ?>
        </td>
    <tr>
        <td class="required">
            <label for="passwrod">备注:</label>
        </td>
        <td class="vatop rowform"><?=$output['find']['desc']?></td>
    </tr>
    <tr class="noborder">
        <td class="required">审核人：</td>
        <td>
            <?=getDeptAdmin($output['find']['admin_id'],false)?>
        </td>
    </tr>
    <tr>
        <td class="required">审核时间:</td>
        <td><?=$output['find']['created_at']?></td>
    </tr>
    </tbody>
</table>