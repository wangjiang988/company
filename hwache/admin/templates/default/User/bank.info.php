<?php defined('InHG') or exit('Access Invalid!');?>
<style type="text/css">
    .table .required{width: 100px; margin: 0; padding: 0;}
    .tab {width: 100%;border: 1px #ccc solid;}
    .tab td{border: 1px #ccc solid;}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><?=$lang['index_title'];?></h3>
            <ul class="tab-base">
                <li><a href="<?=url('new_user','list')?>"><span>管理</span></a></li>
                <li><a href="JavaScript:void(0);" class="current"><span>银行账户认证</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>

        <table class="table tb-type2">
            <tbody>
            <tr>
                <td width="required">
                    <label for="status">开户行:</label>
                </td>
                <td class="vatop rowform">
                    (<?=$output['bank']['province'].$output['bank']['city']?>)<?=$output['bank']['bank_address']?>
                </td>
            </tr>

            <tr class="noborder">
                <td width="required">
                    <label for="real_name">账号:</label>
                </td>
                <td class="vatop rowform">
                    <?=$output['bank']['bank_code']?>
                </td>
            </tr>

            <tr class="noborder">
                <td width="required">
                    <label>户名:</label>
                </td>
                <td class="vatop rowform">
                    <?=$output['find']['last_name'].$output['find']['first_name']?>
                </td>
            </tr>

            <tr>
                <td class="required">
                    <label for="passwrod">银行卡图片:</label>
                </td>
                <td class="vatop rowform">
                    <a href="<?=getImgidToImgurl($output['bank']['bank_img'])?>" target="_blank">
                        <img src="<?=getImgidToImgurl($output['bank']['bank_img'])?>" width="200" height="150" />
                    </a>
                    <?php if(!empty($output['bank']['sc_bank_img'])){ ?>
                    <a href="<?=getImgidToImgurl($output['bank']['sc_bank_img'])?>" target="_blank">
                        <img src="<?=getImgidToImgurl($output['bank']['sc_bank_img'])?>" width="200" height="150" />
                    </a>
                    <?php } ?>
                </td>
            </tr>
            </tbody>
            <tfoot>
            <tr class="tfoot">
                <td colspan="2" >
                    <a href="<?=url('new_user','list')?>" class="btn"><span>返回</span></a>
                </td>
            </tr>
            </tfoot>
        </table>
</div>