<?php defined('InHG') or exit('Access Invalid!');?>
<style type="text/css">
    .table .required{width: 100px; margin: 0; padding: 0;}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><?=$lang['index_title'];?></h3>
            <ul class="tab-base">
                <li><a href="<?=url('new_user','list')?>"><span>列表</span></a></li>
                <li><a href="JavaScript:void(0);" class="current"><span>查看</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <table class="table tb-type2">
        <tbody>
        <tr>
            <td class="required" colspan="2" style="background:#0A8CD2; color: #ffffff; margin-left: 5px;"> | <b>查看认证信息</b></td>
        </tr>

        <tr>
            <td width="required">
                <label for="seller_name">姓名:</label>
            </td>
            <td class="vatop rowform">
                <font class="red">（
                    <?php
                    switch($output['find']['is_id_verify']){
                        case 1:
                            echo '认证通过';
                            break;
                        case 2:
                            echo '认证驳回';
                            break;
                        case 0:
                            echo '未认证';
                            break;
                    }
                    ?>
                    ）</font>
            </td>
        </tr>
        <tr>
            <td width="required">
                <label for="member_truename">身份证号:</label>
            </td>
            <td class="vatop rowform">
                <?=$output['find']['id_cart'];?>
            </td>
        </tr>
        <tr>
            <td class="required"><label for="seller_bank_account">身份证图片:</label></td>
            <td class="vatop rowform">
                <img src="<?=getImgidToImgurl($output['find']['sc_id_cart_img'])?>" width="200" height="150" />
                <img src="<?=getImgidToImgurl($output['find']['id_facade_img'])?>" width="200" height="150" />
                <img src="<?=getImgidToImgurl($output['find']['id_behind_img'])?>" width="200" height="150" />
            </td>
        </tr>

        <tr>
            <td class="required" colspan="2">
                <a class="btn" href="<?=url('new_user','list')?>"><span>返 回</span></a>
            </td>
        </tr>
        </tbody>
    </table>
</div>