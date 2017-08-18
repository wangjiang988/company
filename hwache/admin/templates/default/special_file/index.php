<?php defined('InHG') or exit('Access Invalid!');?>
<?php extract($output)?>
<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>特殊文件</h3>
            <ul class="tab-base">
                <li><a class="current"><span>特殊文件</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form method="get" action="index.php" name="formSearch" id="formSearch">
        <input type="hidden" name="act" value="special_file" />
        <input type="hidden" name="op" value="index" />
        <input type="hidden" name="is_search" value="1" />
        <input type="hidden" name="curpage" id="cur_page" value="<?php echo $_GET['curpage']?>" />
        <div class="pure-g line">
            <div class="pure-u-1-4">
                处理状态:
                <select name="status" >
                    <option value="">全部</option>
                    <option value="0"
                        <?php if($_GET['status'] === '0') echo 'selected';?>
                    >未处理</option>
                    <option value="1"
                        <?php if($_GET['status'] === '1') echo 'selected';?>
                    >已通过</option>
                    <option value="4"
                        <?php if($_GET['status'] === '4') echo 'selected';?>
                    >未通过</option>
                </select>
            </div>
            <div class="pure-u-1-4">
                客户会员号:
                <input type="text" class="text" name="user_id" value="<?php echo trim($_GET['user_id']); ?>" />
            </div>
            <div class="pure-u-1-4">
                客户手机号:
                <input type="text" class="text" name="phone" value="<?php echo trim($_GET['phone']); ?>" />
            </div>
            <div class="pure-u-1-4">
                文件名:
                    <input type="text" class="text" name="file_name" value="<?php echo trim($_GET['file_name']); ?>" />
            </div>
        </div>
        <div class="pure-g line  tb-type1">
            <div class="pure-u-1-4">
                上牌地区:
                <select name="province" id="province">
                    <option value="">全部</option>
                    <?php if($province_list){ ?>
                      <?php foreach($province_list as $item){ ?>
                        <option value="<?=$item['area_id']?>"
                        <?php if($_GET['province']==$item['area_id'])echo 'selected';?>
                        ><?=$item['area_name']?></option>
                        <?php }?>
                    <?php }?>
                </select>
                <select name="area_id" id="city">
                    <option value="">全部</option>
                </select>
            </div>
            <div class="pure-u-1-4">
                提交时间:
               <input type="text" class="text wt80 date" name="s_created_at" id="s_created_at" value="<?php echo trim($_GET['s_created_at']); ?>" />
                -
                <input type="text" class="text wt80 date" name="e_created_at" id="e_created_at" value="<?php echo trim($_GET['e_created_at']); ?>" />
            </div>
            <div class="pure-u-1-4">
                处理时间:
                <input type="text" class="text wt80 date" name="s_confirm_at" id="s_confirm_at" value="<?php echo trim($_GET['s_confirm_at']); ?>" />
                -
                <input type="text" class="text wt80 date" name="e_confirm_at" id="e_confirm_at" value="<?php echo trim($_GET['e_confirm_at']); ?>" />
            </div>
            <div class="pure-u-1-4">
            </div>
        </div>
        <div class="pure-g line">
            <div class="pure-u-3-4">
            </div>
            <div class="pure-u-1-4">
                <div class="pull-right">
                    <a href="JavaScript:void(0);" class="button" id="sub_btn"><span>查找</span></a>
                    <a href="index.php?act=special_file&op=index" class="button" ><span>重置</span></a>
                </div>
            </div>
        </div>
    </form>
    <table class="table tb-type2">
        <thead>
        <tr class="thead blue">
            <th>工单编号</th>
            <th>会员号</th>
            <th>手机号</th>
            <th>文件名称</th>
            <th>上牌地区</th>
            <th>提交时间</th>
            <th>处理时间</th>
            <th>状态</th>
            <th class="align-center">操作</th>
        </tr>
        </thead>
        <tbody id="datatable">
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <?php foreach($output['list'] as $k => $v){ ?>
                <tr class="hover">
                    <td><?php echo $v['id']?></td>
                    <td><?php echo $v['user_id'] ?></td>
                    <td><?php echo $v['phone']?></td>
                    <td><?php echo $v['file_name'] ?></td>
                    <td><?php echo $v['area_name'] ?></td>
                    <td>
                        <?php echo $v['created_at']; ?>
                    </td>
                    <td>

                        <?php if($v['status']) echo $v['confirm_at']; ?>
                    </td>
                    <td>
                        <?php
                            switch ($v['status'])
                            {
                                case '0' : echo '未处理';break;
                                case '1' : echo '已通过';break;
                                case '4' : echo '未通过';break;
                            }
                        ?>
                    </td>
                    <td>
                        <?php if($v['status']){ ?>
                           <a href="index.php?act=special_file&op=detail&id=<?php echo $v['id'] ?>">查看</a>
                        <?php } else{ ?>
                            <a href="index.php?act=special_file&op=detail&id=<?php echo $v['id'] ?>">处理</a>
                         <?php }  ?>
                    </td>
                </tr>
            <?php } ?>
        <?php }else { ?>
            <tr class="no_data" id="no_data" data-has="1">
                <td colspan="10"><?php echo $lang['nc_no_record'];?></td>
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
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.serializejson.min.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />


<script type="text/javascript">

    $(function(){
        $('#s_created_at').datepicker({dateFormat: 'yy-mm-dd'});
        $('#e_created_at').datepicker({dateFormat: 'yy-mm-dd'});
        $('#s_confirm_at').datepicker({dateFormat: 'yy-mm-dd'});
        $('#e_confirm_at').datepicker({dateFormat: 'yy-mm-dd'});

        $("#sub_btn").click(function(){
            let json = $("#formSearch").serializeJSON();
            if(json.province){
                if(!json.area_id) {
                    alert('请选择对应的市');
                    return false;
                }
            }


            $("#cur_page").val(1);

            document.formSearch.submit();
        });

        $("#province").change(function () {
            let val = $(this).val();
            getCity(val)
        })
        var area_id  = $("#province").val();
        if(area_id)
        {
            getCity(area_id);
        }
    });

    function getCity(area_id)
    {
            if(area_id){
                post('index.php?act=special_file&op=getSubArea&area_id='+area_id)
                    .then(function(res){
                        if(res.data.code==200)
                        {
                            list = res.data.data;
                            html ='<option value="">全部</option>';
                            for (x in list)
                            {
                                if(<?=$_GET['area_id']?$_GET['area_id']:'0'?>==list[x].area_id)
                                html +='<option value="'+list[x].area_id+'" selected>'+list[x].area_name + "</option>";
                            else
                                html +='<option value="'+list[x].area_id+'">'+list[x].area_name + "</option>";
                            }
                            $("#city").html(html);
                        }
                    })
            }
    }
</script>
