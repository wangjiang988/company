<?php defined('InHG') or exit('Access Invalid!');?>
<?php extract($output);?>
<style type="text/css">
    .table .required{width: 100px; margin: 0; padding: 0;}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>特殊文件</h3>
            <ul class="tab-base">
                <li><a href="index.php?act=special_file&op=index"><span>特殊文件</span></a></li>
                <li><a href="JavaScript:void(0);" class="current"><span>查看</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form id="ajax_form">
        <input type="hidden" name="in_ajax" value="1" />
        <input type="hidden" name="id" id="id" value="<?=$data['id'];?>" />
        <table class="table tb-type2">
            <tbody>
            <tr class="noborder">
                <td class="w84">
                    <label for="status">工单编号:</label>
                </td>
                <td class="vatop rowform">
                    <?=$data['id']?>
               </td>
            </tr>
            <tr class="noborder">
                <td>
                    <label for="identity">会员号:</label>
                </td>
                <td class="vatop rowform">
                    <?=$data['user_id']?>
                </td>
            </tr>
            <tr class="noborder">
                <td >
                    <label for="identity">手机号:</label>
                </td>
                <td class="vatop rowform">
                    <?=$data['user']['phone']?>
                </td>
            </tr>
            <tr class="noborder">
                <td >
                    <label for="identity">文件名称:</label>
                </td>
                <td class="vatop rowform">
                    <?=$data['file_name']?>
                </td>
            </tr>
            <tr class="noborder">
                <td >
                    <label for="identity">座驾生产国别:</label>
                </td>
                <td class="vatop rowform">
                    <?=$data['country_car']?'进口':'国产'?>
                </td>
            </tr>
            <tr class="noborder">
                <td >
                    <label for="identity">上牌地区:</label>
                </td>
                <td class="vatop rowform">
                    <?=$data['area_name']?>
                </td>
            </tr>
            <tr class="noborder">
                <td >
                    <label for="identity">车辆用途:</label>
                </td>
                <td class="vatop rowform">
                    <?=$data['use_car']?>
                </td>
            </tr>
            <tr class="noborder">
                <td >
                    <label for="identity">上牌（注册登记）车主身份类别:</label>
                </td>
                <td class="vatop rowform">
                    <?=$data['licence_user_type']==2?'上牌地本市户籍居民':$data['licence_other']?>
                </td>
            </tr>
            <tr class="noborder">
                <td >
                    <label for="identity">文件式样图片:</label>
                </td>
                <td class="vatop rowform">
                    <?php if($data['image_list']){?>
                        <?php foreach ($data['image_list'] as $k => $item){ ?>
                            <div class="pure-u-1-4 mt10">
                                <img src="<?=$item['img_url'].'?imageView2/1/w/100';?>">
                            </div>
                        <?php } ?>
                    <?php } ?>
                </td>
            </tr>

            <tr class="noborder">
                <td >
                    <label for="identity">提交时间:</label>
                </td>
                <td class="vatop rowform">
                    <?=$data['created_at']?>
                </td>
            </tr>

            <?php if($data['status']>0) {?>
            <tr class="noborder">
                <td >
                    <label for="identity">处理状态:</label>
                </td>
                <td class="vatop rowform">
                    <?=$data['status']==1?"已通过":'已驳回';?>
                </td>
            </tr>
            <tr class="noborder">
                <td >
                    <label for="identity">处理记录:</label>
                </td>
                <td class="vatop rowform">
                    <div class="mt10">处理人： <?=$data['operation']['user_name']?></div>
                    <div class="mt10">备注： <?=$data['operation']['remark']?></div>
                    <div class="mt10">处理时间： <?=$data['confirm_at']?></div>
                </td>
            </tr>
            <?php }?>
            </tbody>
            <tfoot>
            <?php if($data['status']>0) {?>
                <tr class="tfoot">
                    <td colspan="15" >
                        <a href="javascript:history.go(-1);" class="button">返回</a>
                    </td>
                </tr>
            <?php }?>
            </tfoot>
        </table>
        <?php if($data['status'] == 0) {?>
            <div class="big_title">
                处理信息
            </div>

            <div class="pure-g line">
                <div class="pure-u-1-3"> <span style="padding: 0 2px;color:red">*</span>处理结果:</div>
                <div class="pure-u-2-3">
                    <input type="radio" name="status" id="status_yes" value="1" checked><label for="status_yes">通过</label>
                    <input type="radio" name="status" style="margin-left: 20px;"  id="status_no" value="4"><label for="status_no">不通过</label>
                </div>
            </div>
            <div class="pure-g line">
                <div class="pure-u-1-3"> <span style="padding: 0 2px;color:red"></span>备注:</div>
                <div class="pure-u-2-3">
                    <textarea name="remark" style="min-width: 300px;min-height: 100px;"></textarea>
                </div>
            </div>
            <div class="info footer center">
                <a href="javascript:confirm_form()" class="button confirm ml-20" style="margin-right: 100px;">提交</a>
                <a href="javascript:history.go(-1);" class="button">返回</a>

            </div>
        <?php }?>



    </form>
</div>
<script>
</script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/region.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/diy_validate.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/Diy/Seller/edit.js" charset="utf-8"></script>

<?php if($data['status'] == 0) {?>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.serializejson.min.js" charset="utf-8"></script>
    <script type="text/javascript">

        //表单提交
        function confirm_form() {
            let json= $('#ajax_form').serializeJSON();
            console.log(json);
            parent.layer.confirm('确认要提交客户特殊文件处理结果吗？？', {
                btn: ['确认','取消'] //按钮
            }, function(){
                url  = 'index.php?act=special_file&op=detail&id=<?=$_GET['id']?>'
                submit_ajax_form(url,json,location.href);
                closeLayer();
            }, function(){
                closeLayer();
            });
        }

    </script>
<?php } ?>
