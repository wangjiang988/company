<hr style="border-top:1px solid #0000C2"/>
<table class="table order-intro" >
    <tr>
        <td>工单编号：</td>
        <td><?=$output['conciliation']['id'];?></td>
        <td>工单时间：</td>
        <td><?=$output['conciliation']['created_at'];?></td>
        <td>工单提交人：</td>
        <td><?=$output['departments'][$output['conciliation_submitter']['dept_id']].$output['conciliation_submitter']['admin_name'];?></td>
    </tr>
    <tr>
        <td>工单对象：</td>
        <td><?=$output['conciliation']['target']==1?'平台':($output['conciliation']['target']==2?'客户':'售方');?></td>
        <td>处理部门：</td>
        <td><?=$output['departments'][$output['conciliation']['follow_depid']];?></td>
        <td>状态：</td>
        <td><?=$output['conciliation_status_list'][$output['conciliation']['status']];?></td>
    </tr>
    <tr>
        <td>主题：</td>
        <td colspan="5"><?=$output['conciliation']['subject'];?></td>
    </tr>
    <tr>
        <td>问题描述：</td>
        <td colspan="5"><?=$output['conciliation']['content'];?></td>
    </tr>
    <tr>
        <td>证据：</td>
        <?php if($output['conciliation_evidence']):?>
            <?php foreach($output['conciliation_evidence'] as $ev):?>
            <td><a href="<?=UPLOAD_SITE_URL.'/'.$ev['evidence_path'];?>" target="_blank"><?=$ev['evidence'];?></a></td>
            <?php endforeach;;?>
        <?php endif;?>
    </tr>
</table>
<h4 style="color:#0000C2;font-size:14px">处理记录</h4>
<hr/>
<table class="table table-bordered">
    <tr class="info">
        <td>记录时间</td>
        <td  width="50%">记录内容</td>
        <td width="30%">证据</td>
        <td>记录人</td>
        <td>跟进处理部门</td>
    </tr>
    <?php if($output['conciliation_receive']):?>
        <?php foreach($output['conciliation_receive'] as $re):?>
        <tr>
        <td><?=$re['created_at'];?></td>
        <td  width="50%"><?=$re['content'];?></td>
        <td>
            <?php if($re['evidence']):?>
                <?php foreach($re['evidence'] as $e):?>
                <a href="<?=UPLOAD_SITE_URL.'/'.$e['evidence_path'];?>" target="_blank"><?=$e['evidence'];?></a>
                <?php endforeach;?>
            <?php endif;?>
        </td>
        <td><?=$re['admin_name'];?></td>
        <td><?=$output['departments'][$re['follow_depid']];?></td>
        </tr>
        <?php endforeach;?>
    <?php else:;?>
    <tr>
        <td colspan="5">没有处理记录</td>
    </tr>
    <?php endif;?>
</table>
<?php if($output['arbitrate'] && !in_array($_GET['op'] ,['edit_judge_conciliation', 'end_judge_conciliation','judge_conciliation'])):?>
<h4 style="color:#0000C2;font-size:14px">平台裁判</h4>
<hr/>
    <dl class="dl-horizontal">
    <dt>判定结论：</dt><dd><?=$output['arbitrate']['arbitrate_result']==1?'裁判客户违约':($output['arbitrate']['arbitrate_result']==2?'裁判售方违约':'客户支付买车担保金违约');;?></dd></dt>
    <?php if($output['arbitrate']['arbitrate_result']==3):?>
        <dt>客户加信宝 ：</dt><dd>诚意金赔偿（售方）￥299.00</dd></dt>
        <dd>诚意金赔偿（平台）￥200.00</dd>
        <dd>退还可用余额<?=$output['arbitrate']['return_user_available_deposit_from_userjxb'];?></dd>
        <dt>售方加信宝：</dt><dd>退还可提现余额￥499.00</dd></dt>
    <?php else:?>
        <dt>客户加信宝 ：</dt><dd>买车担保金赔偿（售方）￥<?=$output['arbitrate']['seller_deposit_from_userjxb'];?></dd></dt>
        <dd>买车担保金赔偿（平台）￥<?=$output['arbitrate']['hwache_deposit_from_userjxb'];?></dd>
        <dd>退还可用余额￥<?=$output['arbitrate']['return_user_available_deposit_from_userjxb'];?></dd>
        <dd>转付华车服务费￥<?=$output['arbitrate']['transfer_hwache_service_charge_from_userjxb'];?> （含售方服务费￥<?=$output['arbitrate']['transfer_seller_service_charge_from_userjxb'];?>）</dd>
        <dt>售方加信宝：</dt><dd>歉意金N赔偿￥<?=$output['arbitrate']['apology_money_from_sellerjxb'];?></dd></dt>
        <dd>客户买车担保金利息N赔偿￥<?=$output['arbitrate']['user_deposit_interest_from_sellerjxb'];?></dd>
        <dd>退还可提现余额￥<?=$output['arbitrate']['return_user_avaiable_from_sellerjxb'];?></dd>
        <dt>售方可提现余额：</dt><dd>客户买车其他损失￥<?=$output['arbitrate']['user_damage'];?></dd></dt>
        <dd>华车平台损失赔偿￥<?=$output['arbitrate']['hwache_damage'];?></dd>
    </dl>
    <?php endif;?>
<?php endif;?>
