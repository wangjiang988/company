<style type="text/css">
    .tab-base em{ padding: 0 10px;}
    .search input.txt{width: 120px;}
    .search input.small{width: 63px;}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>客户银行账号认证管理</h3>
            <ul class="tab-base">
                <li><a href="JavaScript:void(0);" class="current"><span>管理</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form name="formSearch" id="searchFrom" action="<?=url('approve','bank')?>" method="get">
        <input type="hidden" value="approve" name="act">
        <input type="hidden" value="bank" name="op">

         <div class="pure-g line">
            <div class="pure-u-1-4">
                工单号:
                <input type="text" class="text" name="id" value="<?php echo trim($_GET['id']); ?>" />
            </div>
            <div class="pure-u-1-4">
                会员号:
                <input type="text" class="text" name="user_id" value="<?php echo trim($_GET['user_id']); ?>" />
            </div>
            <div class="pure-u-1-4">
                认证姓名:
                <input type="text" class="text" name="real_name" value="<?php echo trim($_GET['real_name']); ?>" />
            </div>
            <div class="pure-u-1-4">
                手机号码:
                <input type="text" class="text" name="phone" value="<?php echo trim($_GET['phone']); ?>" />
            </div>
        </div>
        <div class="pure-g line">
            <div class="pure-u-1-4">
                账号:
                <input type="text" class="text " name="bank_code" value="<?php echo trim($_GET['bank_code']); ?>" />
            </div>
            <div class="pure-u-1-4">
                提交时间:
                <input type="text" class="text date" name="created_at" value="<?php echo trim($_GET['created_at']); ?>" />
            </div>
           <div class="pure-u-1-4">
                审核时间:
                <input type="text" class="text date" name="review_time" value="<?php echo trim($_GET['review_time']); ?>" />
            </div>
            <div class="pure-u-1-4">
                认证结果:
                <select name="status">
                    <option value="">全部</option>
                    <option value="0"  <?php if($_GET['status'] === '0') echo "selected"?> >已申请</option>
                    <option value="1"  <?php if($_GET['status'] === '1') echo "selected"?> >已通过</option>
                    <option value="2" <?php if($_GET['status'] === '2') echo "selected"?> >未通过</option>
                </select>
            </div>
        </div>
        <div class="pure-g line">
            <div class="pure-u-3-4">
            </div>
            <div class="pure-u-1-4">
                <div class="pull-right">
                    <button href="JavaScript:void(0);" type="submit" class="button button-primary button-small" id="sub_btn"><span>查找</span></button>
                    <a href="index.php?act=approve&op=bank" class="button button-primary button-small" ><span>重置</span></a>
                </div>
            </div>
        </div>
    </form>

    <table class="table tb-type2">
        <thead>
        <tr class="thead">
            <th class="w24">工单号</th>
            <th class="w60">会员号</th>
            <th class="w48">认证姓名</th>
            <th class="align-center">手机号码</th>
            <th class="align-center">账号</th>
            <th class="align-center">提交时间</th>
            <th class="align-center">类别</th>
            <th class="align-center">审核时间</th>
            <th>认证结果</th>
            <th class="w120 align-center">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <?php foreach($output['list'] as $k => $v){ ?>
                <tr class="hover">
                    <td><?=$v['id']; ?></td>
                    <td><?=$v['user_id']; ?></td>
                    <td><?=$v['real_name'];?></td>
                    <td class="align-center"><?=$v['phone'];?></td>
                    <td class="align-center"><?=($v['status']!=1) ? '' :$v['bank_code'];?></td>
                    <td class="align-center"><?=$v['created_at'];?></td>
                    <td class="align-center">
                    <?php
                            $type = ['新增','更换'];
                            echo $type[$v['type']-1];
                    ?>
                    </td>
                    <td class="nowrap align-center"><?=in_array($v['status'],[0,3]) ? '' :$v['review_time']?></td>
                    <td class="nowrap align-center">
                    <?php
                            $status = ['已申请','已通过','未通过'];
                            echo $status[$v['status']];
                    ?>
                    </td>
                    <td class="align-center">
                        <a href="<?=url('approve','bank_detail',['id'=>$v['id']]); ?>">
                            <?php
                            echo empty($v['status']) ? '审核' : '查看';
                            ?>
                        </a>
                    </td>
                </tr>
            <?php } ?>
        <?php }else { ?>
            <tr class="no_data">
                <td colspan="12"><?php echo $lang['nc_no_record'];?></td>
            </tr>
        <?php } ?>
        </tbody>
        <tfoot>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <tr class="tfoot">
                <td>&nbsp;</td>
                <td colspan="12">
                    <div class="pagination"> <?php echo $output['page'];?></div>
                </td>
            </tr>
        <?php } ?>
        </tfoot>
    </table>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/buttons.css"  />

<script>
    $(function() {
        $('.date').datepicker({dateFormat: 'yy-mm-dd'});
    });
</script>