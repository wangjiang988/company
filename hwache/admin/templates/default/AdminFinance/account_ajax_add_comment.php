<?php defined('InHG') or exit('Access Invalid!');?>
<?php extract($output)?>
<?php if($list) {?>
    <div style="padding: 20px 50px;" id="list">
    <table class="table tb-type2">
        <thead>
        <tr class="thead blue">
            <th >备注内容</th>
            <th >备注人</th>
            <th >时间</th>
        </tr>
        </thead>
        <tbody id="datatable">
            <?php foreach($output['list'] as $k => $v){ ?>
                <tr class="hover">
                    <td><?= $v['comment'] ?></td>
                    <td><?= $v['user_name'] ?></td>
                    <td><?= $v['created_at'] ?></td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <tr class="tfoot">
                <td colspan="3"><div class="pagination"> <?php echo $output['page'];?> </div></td>
            </tr>
        <?php } ?>
        </tfoot>
    </table>
        <a href="javascript:show_add();">添加备注</a>
    </div>
<?php } ?>

<div class="comment_div"
<?php if($list) echo 'style="display:none;"'?>
>
    <form id="ajax_form" style="margin-top: 50px;">
        <input type="hidden" name="declare_id" value="<?=$_GET['declare_id']?>">
        <input type="hidden" name="in_ajax" value="1">
        <div class="pure-g line">
            <div class="pure-u-1-3">
                <div style="text-align: right;padding-right: 50px;font-size: 14px;height: 50px; line-height: 50px;">备注:</div>
            </div>
            <div class="pure-u-2-3">
                <textarea style="min-width:80%;min-height: 100px;" name="comment" id="comment" cols="30" rows="10"></textarea>
            </div>
        </div>
        <div class="panel_footer" style="margin-top:100px;padding: 10px;text-align: center;">
            <span class="label">
                <a href="javascript:void(0);"  onclick="addComment();" class="button">提交</a>
                <a href="javascript:void(0);" onclick="closeLayer();" class="button">取消</a>
            </span>
        </div>
    </form>
</div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.serializejson.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>

<script type="text/javascript">
    function show_add()
    {
        $("#list").hide();
        $(".comment_div").show();
    }
    function addComment(){

        let json= $('#ajax_form').serializeJSON();
        if(json.comment ==""){
            alert("备注内容不完整～");
            return false;
        }

        post('index.php?act=admin_finance&op=account_ajax_add_comment', json).then(function(res){
            if(res.data.code == 200)
            {
                alert(res.data.msg);
                refresh_workspace();
                closeLayer();
            }else
            {
                alert(res.data.msg);
            }
        }).catch(function(err){
            console.log(err);
        });
    }

</script>