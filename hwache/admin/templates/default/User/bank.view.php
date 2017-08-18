<style type="text/css">
    .tabBank{width:100%; margin-top: 15px;}
    .tabBank tr{ height: 30px; padding:5px; }
    .tabBank td{height: 30px; padding-left:15px; text-align: left; border-bottom: 1px #ccc solid; }
</style>
<table class="tabBank">
    <tr>
        <td>银行卡号：</td>
        <td><?=$output['bank']['bank_code']?></td>
    </tr>
    <tr>
        <td>开户行：</td>
        <td>(<?=$output['bank']['province'].$output['bank']['city']?>)<?=$output['bank']['bank_address']?></td>
    </tr>
    <tr>
        <td>户名：</td>
        <td><?=$output['find']['last_name'].$output['find']['first_name']?></td>
    </tr>
    <tr>
        <td>银行卡图片：</td>
        <td><a href="<?=getImgidToImgurl($output['bank']['bank_img'])?>" target="_blank" >查 看</a> </td>
    </tr>
</table>
