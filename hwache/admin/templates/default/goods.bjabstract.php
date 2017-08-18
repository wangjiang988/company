<?php defined('InHG') or exit('Access Invalid!');?>
<link href="<?php echo ADMIN_TEMPLATES_URL;?>/css/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />

<table class="table">
    <tr>
        <td><?=$lang['goods_system_bj_number'];?></td>
        <td><?=$output['baojia']['bj_serial'];?><a href="/index.php?act=goods&op=show_bj_info&bj_id=<?=$output['baojia']['bj_id'];?>"><?=$lang['goods_bj_show'];?></a></td>
        <td><?=$lang['goods_dealer_bj_number'];?></td>
        <td><?=$output['baojia']['bj_serial'];?></td>
        <td><?=$lang['goods_bj_status'];?></td>
        <td>正常</td>
    </tr>
    <tr>
        <td><?=$lang['common_name'];?></td>
        <td><?=$output['member'];?>&nbsp;&nbsp;<a href="#"><?=$lang['goods_dealer_show'];?></a></td>
        <td><?=$lang['common_dealer_name'];?></td>
        <td><?=$output['baojia']['dealer_name']; ?></td>
        <td><?=$lang['common_area'];?></td>
        <td>江苏省苏州市</td>
    </tr>
    <tr>
        <td><?=$lang['goods_bj_car_model'];?></td>
        <td><?=$output['baojia']['gc_name']; ?></td>
        <td></td>
        <td></td>
        <td><?=$lang['goods_is_xianche'];?></td>
        <td><?=$output['baojia']['bj_is_xianche']?'现车':'非现车';?></td>
    </tr>
    <tr>
        <td><?=$lang['goods_guide_price'];?></td>
        <td><?=$output['zhidaojia']; ?></td>
        <td><?=$lang['goods_sale_price'];?></td>
        <td><?=$output['price']['bj_lckp_price']; ?> </td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td><?=$lang['goods_service_fee'];?></td>
        <td><?=$output['price']['bj_my_service_price']; ?></td>
        <td><?=$lang['goods_doposit_price'];?></td>
        <td><?=$output['price']['bj_earnest_price'];?></td>
        <td><?=$lang['goods_car_license_fee'];?></td>
        <td><?=$output['price']['bj_shangpai_price'];?></td>
    </tr>
</table>

<div class="text-center">
    <button type="button" class="btn btn-warning"><?=$lang['goods_return_button'];?></button>
</div>