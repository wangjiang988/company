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
                <li><a href="JavaScript:void(0);" class="current"><span>实名认证</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form id="idcart_form" action="<?=url('new_user','post')?>" method="post" >
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="user_id" id="user_id" value="<?=$output['find']['id'];?>" />
        <input type="hidden" name="ref_url" value="<?php echo getReferer();?>" />
        <table class="table tb-type2">
            <tbody>

            <tr class="noborder">
                <td width="required">
                    <label for="real_name">姓名:</label>
                </td>
                <td class="vatop rowform" colspan="3">
                    <?=$output['find']['last_name'];?>&nbsp;<?=$output['find']['first_name'];?>
                </td>
            </tr>

            <tr class="noborder">
                <td width="required">
                    <label for="id_cart">身份证号:</label>
                </td>
                <td class="vatop rowform"  colspan="3">
                    <?=$output['find']['id_cart'];?>
                </td>
            </tr>

            <tr>
                <td class="required">
                    <label for="passwrod">身份证图片:</label>
                </td>
                <td class="vatop rowform" colspan="3">
                    <a href="<?=getImgidToImgurl($output['find']['sc_id_cart_img'])?>" target="_blank"><img src="<?=getImgidToImgurl($output['find']['sc_id_cart_img'])?>" width="200" height="150" /></a>
                    <a href="<?=getImgidToImgurl($output['find']['id_facade_img'])?>" target="_blank"><img src="<?=getImgidToImgurl($output['find']['id_facade_img'])?>" width="200" height="150" /></a>
                    <a href="<?=getImgidToImgurl($output['find']['id_behind_img'])?>" target="_blank"><img src="<?=getImgidToImgurl($output['find']['id_behind_img'])?>" width="200" height="150" /></a>
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
    </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/diy_validate.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/User/edit.js" charset="utf-8"></script>