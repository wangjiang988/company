<style type="text/css">
    .tab-base em{ padding: 0 10px;}
    .search input.txt{width: 120px;}
    .search input.small{width: 63px;}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>客户实名认证管理</h3>
            <ul class="tab-base">
                <li><a href="JavaScript:void(0);" class="current"><span>管理</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form name="formSearch" id="searchFrom" action="<?=url('approve','idcart')?>" method="get">
        <input type="hidden" value="approve" name="act">
        <input type="hidden" value="idcart" name="op">
        <table class="tb-type1 noborder search" style="width:100%;">
            <tbody>
            <tr>
                <th><label for="search_title">工单号</label></th>
                <td>
                    <input type="text" value="<?=$output['search']['id'];?>" name="id" class="txt">                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <label for="seller_name">会员号</label>
                    <input type="text" value="<?=$output['search']['user_id'];?>" name="user_id" class="txt">
                    <label for="name_phone">认证姓名</label>
                    <input type="text" value="<?=$output['search']['user_name'];?>" name="user_name" class="txt">
                    <label for="start_deposit">手机号</label>
                    <input type="text" value="<?=$output['search']['phone'];?>" name="phone" class="txt">
                    <label for="start_credit">身份证号</label>
                    <input type="text" value="<?=$output['search']['id_cart'];?>" name="id_cart" class="txt">
                </td>
            </tr>
            <tr>
                <th><label for="search_jxb">提交时间</label></th>
                <td>
                    <input type="text" value="<?=$output['search']['created_at'];?>" name="created_at" class="date">

                    <label for="seller_jiaxb">审核时间</label>
                    <input type="text" value="<?=$output['search']['review_time'];?>" name="review_time" class="date">

                    <label for="seller_jiaxb">认证结果</label>
                    <select name="status">
                        <option value="0">  ----全部----  </option>
                        <option value="1" <?=isSelected([$output['search']['status'],'==',1],'select')?> >已申请</option>
                        <option value="2" <?=isSelected([$output['search']['status'],'==',2],'select')?>>已通过</option>
                        <option value="3" <?=isSelected([$output['search']['status'],'==',3],'select')?>>未通过</option>
                    </select>
                </td>
            </tr>
            <tr><th></th>
                <td>
                    <div style="float:right; text-align:left; width:auto;">
                        <button type="submit" class="button button-primary button-small" style="margin-right: 10px;">搜索</button>
                        <a href="<?=url('seller_finance','index')?>" class="button button-primary button-small" style="margin-right: 10px;">重置</a>
                        <a href="<?=url('seller_finance','exportFile',$output['uri'])?>" class="button button-primary button-small">导出</a>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </form>

    <table class="table tb-type2">
        <thead>
        <tr class="thead">
            <th class="w24">工单号</th>
            <th class="w60">会员号</th>
            <th class="w48">认证姓名</th>
            <th class="align-center">手机号码</th>
            <th class="align-center">身份证号</th>
            <th class="align-center">提交时间</th>
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
                    <td><?=($v['status']!=1) ? '' :$v['real_name']; ?></td>
                    <td class="align-center"><?=$v['phone'];?></td>
                    <td class="align-center"><?=($v['status']!=1) ? '' :$v['id_cart'];?></td>
                    <td class="align-center"><?=$v['created_at'];?></td>
                    <td class="nowrap align-center">
                    <?=empty($v['status']) ? '' :$v['review_time'];?>
                    </td>
                    <td class="nowrap align-center">
                    <?php
                    $status = ['已申请','已通过','未通过'];
                    echo $status[$v['status']];
                    ?>
                    </td>
                    <td class="align-center">
                        <a href="<?=url('approve','idcart_detail',['id'=>$v['id']]); ?>">
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