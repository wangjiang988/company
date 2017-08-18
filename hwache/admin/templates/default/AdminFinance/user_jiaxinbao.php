<?php defined('InHG') or exit('Access Invalid!');?>
<?php extract($output);?>
<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><a href="index.php?act=admin_finance&op=home">平台财务</a></h3>
            <ul class="tab-base">
                <li><a class="current"><span>用户加信宝</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <div class="container">
        <div class="big_title">
            所有客户冻结余额总计：￥<span id="total_freeze_deposit"><?=$sum_freeze_deposit?></span>
        </div>
        <form id="search_form">
            <input type="hidden" name="act"  value="admin_finance">
            <input type="hidden" name="op"  value="user_jiaxinbao">
            <input type="hidden" id="export" name="export" value="0">
            <input type="hidden" name="is_search" value="1" >

        <div class="ml50 content">
            <div class="pure-g line">
                <div class="pure-u-1-3">
                    客户会员号：<input type="text" name="id" value="<?=$_GET['id']?>">
                </div>
                <div class="pure-u-1-3">
                    客户手机：<input type="text" name="phone" value="<?=$_GET['phone']?>">
                </div>
                <div class="pure-u-1-3">
                    冻结金额：￥ <input type="text" name="s_freeze_deposit" value="<?=$_GET['s_freeze_deposit']?>"> ~ <input type="text" name="e_freeze_deposit" value="<?=$_GET['e_freeze_deposit']?>">
                </div>
            </div>
            <div class="pure-g line">
                <div class="pure-u-1-3">
                    排列： <select name="order" id="order">
                                <option value="asc"
                                        <?php if($_GET['order']=='asc') echo "selected";?>
                                        >冻结余额从低到高</option>
                                <option value="desc"
                                    <?php if($_GET['order']=='desc') echo "selected";?>
                                >冻结余额从高到低</option>
                            </select>
                </div>
                <div class="pure-u-1-3">
                </div>
                <div class="pure-u-1-3">
                    <a href="javascript:$('#export').val(0);$('#search_form').submit();" class="button confirm">查找</a>
                    <a href="index.php?act=admin_finance&op=user_jiaxinbao" class="button">重置</a>
                    <a href="javascript:void(0);" class="button" id="export_btn">导出</a>
                </div>
            </div>
        </form>
            <table class="table tb-type2">
                <thead>
                <tr class="thead blue">
                    <th >客户会员号</th>
                    <th >客户手机号码</th>
                    <th >冻结金额</th>
                    <th >操作</th>
                </tr>
                </thead>
                <tbody id="datatable">
                <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
                    <?php foreach($output['list'] as $k => $v){ ?>
                        <tr class="hover">
                            <td><?php echo $v['id']?></td>
                            <td><?php echo $v['phone']?></td>
                            <td>￥ <?php echo $v['freeze_deposit']? $v['freeze_deposit']:0; ?></td>
                            <td><a href="javascript:void(0);" onclick="show_detail(<?=$v['id'];?>)">查看</a></td>
                        </tr>
                    <?php } ?>
                <?php }else { ?>
                    <tr class="no_data" id="no_data" data-has="1">
                        <td colspan="8"><?php echo $lang['nc_no_record'];?></td>
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


        </div>
    </div>

</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript">
    $(function(){
        $("#export_btn").click(function () {
            $('#export').val(1);
            $('#search_form').submit();
        })

        //排列
        $("#order").change(function(){
            $('#export').val(0);
            $('#search_form').submit();
        })


    });
    //查看
    function show_detail(id){
        location.href='index.php?act=admin_finance&op=user_jiaxinbao_detail&id='+id;
    }
</script>
