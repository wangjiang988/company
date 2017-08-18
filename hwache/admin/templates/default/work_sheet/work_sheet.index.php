<?php defined('InHG') or exit('Access Invalid!');?>
<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>工单管理</h3>
            <ul class="tab-base">
                <li><a class="current"><span>工单管理</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>

    <form method="get" action="index.php" name="formSearch" id="formSearch">
        <input type="hidden" name="act" value="work_sheet" />
        <input type="hidden" name="op" value="index" />
        <input type="hidden" name="is_search" value="1" />
        <input type="hidden" name="curpage" id="cur_page" value="<?php echo $_GET['curpage']?>" />
        <input type="hidden" name="export" id="export" value="0" />
        <!-- <div class="pure-g line">
            <div class="pure-u-1-4">
                类别:
                <input type="text" class="text" name="id" value="<?php echo trim($_GET['id']); ?>" />
            </div>
            <div class="pure-u-1-4">
                客户姓名:
                <input type="text" class="text" name="name" value="<?php echo trim($_GET['name']); ?>" />
            </div>
            <div class="pure-u-1-4">
                客户手机:
                <input type="text" class="text" name="phone" value="<?php echo trim($_GET['phone']); ?>" />
            </div>
            <div class="pure-u-1-4">
                可用余额:
                   ￥ <input type="text" class="text wt80" name="s_avaliable_deposit" value="<?php echo trim($_GET['s_avaliable_deposit']); ?>" />  -
                    <input type="text" class="text wt80" name="e_avaliable_deposit" value="<?php echo trim($_GET['e_avaliable_deposit']); ?>" />
            </div>
        </div>
        <div class="pure-g line">
            <div class="pure-u-1-4">
                加信宝:
                <span class="icon">￥</span> <input type="text" class="text wt80" name="s_freeze_deposit" value="<?php echo trim($_GET['s_freeze_deposit']); ?>" />
                -
                <input type="text" class="text wt80" name="e_freeze_deposit" value="<?php echo trim($_GET['e_freeze_deposit']); ?>" />
            </div>
            <div class="pure-u-1-4">
                平台冻结:
                <span class="icon">￥</span> <input type="text" class="text wt80" name="order_num" value="<?php echo trim($_GET['order_num']); ?>" />
                -
                <input type="text" class="text wt80" name="order_num" value="<?php echo trim($_GET['order_num']); ?>" />
            </div>
            <div class="pure-u-2-4">
            </div>
        </div> -->
        <div class="pure-g line">
            <div class="pure-u-3-4">
                <div class="pull-left">
                    <a href="index.php?act=work_sheet&op=create" class="button"><span>新建内部工单</span></a>
                </div>
            </div>
            <div class="pure-u-1-4">
                <div class="pull-right">
                    <a href="JavaScript:void(0);" class="button" id="sub_btn"><span>查找</span></a>
                    <a href="index.php?act=finance&op=index" class="button" ><span>重置</span></a>
                    <a href="JavaScript:void(0);" class="button" id="export_btn"><span>导出</span></a>
                </div>
            </div>
        </div>
    </form>
    <table class="table tb-type2">
        <thead>
        <tr class="thead blue">
            <th class="wt70">编号</th>
            <th >时间</th>
            <th >客户手机</th>
            <th >售方用户名</th>
            <th >类别</th>
            <th >对象</th>
            <th >事项</th>
            <th >状态</th>
            <th >处理部门</th>
            <th class="align-center">操作</th>
        </tr>
        </thead>
        <tbody id="datatable">
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <?php foreach($output['list'] as $k => $v){ ?>
                <tr class="hover">
                    <td><?php echo $v['id']?></td>
                    <td><?php echo $v['created_at'] ?></td>
                    <td><?php echo $v['user_phone']?></td>
                    <td><?php echo $v['seller_name']?></td>
                    <td><?php echo '内部工单';?></td>
                    <td><?=show_target($v['target']);?></td>
                    <td></td>
                    <td></td>
                    <td>运营部</td>
                    <td>
                        <a href="index.php?act=work_sheet&op=view&id=<?php echo $v['id'] ?>">前往</a>
                    </td>
                </tr>
            <?php } ?>
        <?php }else { ?>
            <tr class="no_data" id="no_data" data-has="1">
                <td colspan="12"><?php echo $lang['nc_no_record'];?></td>
            </tr>
        <?php } ?>
        </tbody>
        <tfoot>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <tr class="tfoot">
                <td colspan="20"><div class="pagination"> <?php echo $output['page'];?> </div></td>
            </tr>
        <?php } ?>
        </tfoot>
    </table>
    <div class="clear"></div>
</div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript">
$(function(){
    //导出图表
    $("#export_btn").click(function(){
        let has  =  $('#no_data').data('has');
        if(has) alert('没有数据导出');
        else{
            $("#export").val(1);
            document.formSearch.submit();
        }
    });

});
</script>
