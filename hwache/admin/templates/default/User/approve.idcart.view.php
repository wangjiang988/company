<?php defined('InHG') or exit('Access Invalid!'); extract($output); ?>
<style type="text/css">
    .table .required{width: 100px; margin: 0; padding: 0;}
    .tab {width: 100%;border: 1px #ccc solid;}
    .tab td{border: 1px #ccc solid;}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>客户实名认证管理</h3>
            <ul class="tab-base">
                <li><a href="<?=url('approve','idcart')?>"><span>管理</span></a></li>
                <li><a href="JavaScript:void(0);" class="current"><span>查看</span></a></li>
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
            <tr>
                <td width="required">
                    <label for="status">工单号:</label>
                </td>
                <td class="vatop rowform">
                    <?=$output['find']['id']?>
                </td>
                <td width="required">
                    <label for="status">提交时间:</label>
                </td>
                <td class="vatop rowform">
                    <?=$output['find']['created_at']?>
                </td>
            </tr>

            <tr>
                <td width="required">
                    <label for="status">会员号:</label>
                </td>
                <td class="vatop rowform" colspan="3">
                    <?=$output['find']['user_id']?>
                </td>
            </tr>

            <tr class="noborder">
                <td width="required">
                    <label for="real_name">真实姓名:</label>
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

            <tr>
                <td class="required" colspan="4" style="background:#8c8c8c; color: #ffffff; margin-left: 5px;"> </td>
            </tr>
            <tr class="noborder">
                <td class="required"><label class="validation">认证结果</label> </td>
                <td class="vatop rowform"><?php echo ($output['find']['status']==1) ? '已通过':'未通过';?></td>
                <td class="required">
                <label class="validation"><?=($find['status'] ==1)?'姓名':'原因';?></label>
                 </td>
                <td class="vatop rowform">
                <?php
                    if($find['status'] ==1)
                    echo $output['find']['last_name'].$output['find']['first_name'];
                    else
                    echo $output['find']['reason'];
                ?>
                </td>
            </tr>

            <tr class="noborder">
                <td class="required">审核留存图片：</td>
                <td colspan="3">

                    <?php if(isset($images)){ foreach($images as $image) {?>
                    <div class="pure-u-1-4">
                       <img class='shenpi_images' data-id='<?=$image["id"]?>' style='max-width:300px;' src="<?=UPLOAD_SITE_URL.$image['url']?>"/>
                    </div>
                    <?php } } ?>
                </td>
            </tr>

            <tr>
                <td class="required">备注:</td>
                <td colspan="3"><?=$output['find']['remark']?></td>
            </tr>
            <tr>
                <td class="required">审核人:</td>
                <td colspan="3"><?=$output['find']['admin_name']?></td>
            </tr>
            <tr>
                <td class="required">审核时间:</td>
                <td colspan="3"><?=$output['find']['review_time']?></td>
            </tr>

            </tbody>
            <tfoot>
            <tr class="tfoot">
                <td colspan="4" >
                    <a href="<?=url('approve','idcart')?>" class="btn"><span>返回</span></a>
                </td>
            </tr>
            </tfoot>
        </table>
    </form>
</div>