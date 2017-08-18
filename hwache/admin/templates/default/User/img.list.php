<?php defined('InHG') or exit('Access Invalid!');?>
<style type="text/css">
    .img { float:left; width: 200px; height: 150px; padding:5px; margin: 5px; border: 1px #ccc solid;}
</style>
<div class="page">
    <table class="table tb-type2">
        <tbody>
        <tr>
            <td width="required">
                <?php if(!empty($output['list']) && is_array($output['list'])) {
                    foreach ($output['list'] as $k => $v) {
                        ?>
                        <a href="<?=$v['img_url']?>" target="_blank">
                            <div class="img"><img src="<?=$v['img_url']?>" width="200" height="150"></div>
                        </a>
                        <?
                    }
                }
                ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>