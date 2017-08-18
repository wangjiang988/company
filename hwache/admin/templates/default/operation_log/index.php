<?php defined('InHG') or exit('Access Invalid!');?>
<?php extract($output)?>
<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>审批操作日志</h3>
            <ul class="tab-base">
                <li><a class="current"><span>操作日志</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>

    <form method="get" action="index.php" name="formSearch" id="formSearch">
        <input type="hidden" name="act" value="operation_log" />
        <input type="hidden" name="op" value="index" />
        <input type="hidden" name="is_search" value="1" />
        <input type="hidden" name="curpage" id="cur_page" value="<?php echo $_GET['curpage']?>" />
        <input type="hidden" name="export" id="export" value="0" />
        <div class="pure-g line">
            <div class="pure-u-1-4">
                类型:
                <select name="type" >
                    <option value="">全部</option>
                    <?php if($op_types){?>
                        <?php foreach ($op_types as $item){?>
                        <option value="<?=$item['type']?>"
                            <?php if($_GET['type'] == $item['type']) echo 'selected'; ?>
                        ><?=$item['description']?></option>
                        <?php  } ?>
                    <?php  } ?>
                </select>
            </div>
            <div class="pure-u-1-4">
                关联表: <input type="text" value="<?=$_GET['related']?>" name="related" class="form-control">
            </div>
        </div>

        <div class="pure-g line">
            <div class="pure-u-3-4">
            </div>
            <div class="pure-u-1-4">
                <div class="pull-right">
                    <a href="JavaScript:void(0);" class="button" id="sub_btn"><span>查找</span></a>
                    <a href="index.php?act=operation_log&op=index" class="button" ><span>重置</span></a>
                </div>
            </div>
        </div>
    </form>
    <table class="table tb-type2">
        <thead>
        <tr class="thead blue">
            <th >操作人ID</th>
            <th >操作人</th>
            <th >类型</th>
            <th >操作</th>
            <th >备注</th>
            <th >关联表和对应id</th>
            <th >操作时间</th>
            <th class="align-center">操作</th>
        </tr>
        </thead>
        <tbody id="datatable">
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <?php foreach($output['list'] as $k => $v){ ?>
                <tr class="hover">
                    <td><?php echo $v['user_id']?></td>
                    <td><?php echo $v['user_name'] ?></td>
                    <td><?php echo show_operation_type($v['type']);?></td>
                    <td><?php echo $v['operation']?></td>
                    <td><?php echo $v['remark']?></td>
                    <td>  <?php echo $v['related']?>
                    </td>
                    <td>
                        <?php echo $v['created_at']; ?>
                    </td>
                    <td>
                        <a href="index.php?act=operation_log&op=detail&id=<?php echo $v['id'] ?>">查看</a>
                    </td>
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

        $("#sub_btn").click(function(){
            $("#cur_page").val(1);
            $("#export").val(0);
            document.formSearch.submit();
        });
    });
</script>
