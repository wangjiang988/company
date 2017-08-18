<?php defined('InHG') or exit('Access Invalid!');?>
<?php extract($output);?>
<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>地区管理</h3>
            <ul class="tab-base">
                <li><a href="JavaScript:void(0);" class="current"><span>列表</span></a></li>
                <li><a href="index.php?act=area_manage&op=add"><span>新增</span></a></li>
            </ul>

             <h3 style="margin-left:50px;"><a href="<?php echo WWW_SITE_URL.'/cache/area?admin=yeath';?>" style="color:purple;">更新前台地区缓存</a></h3>
             <h3><a href="<?php echo WWW_SITE_URL.'/cache/front-area?admin=yeath';?>" style="color:purple;">更新前台JS地区缓存</a></h3>
             <h3><a href="index.php?act=area_manage&op=cache_area" style="color:purple;">更新后台JS地区缓存</a></h3>
        </div>
    </div>
    <div class="fixed-empty"></div>
    

<form method="get" action="index.php" name="formSearch" id="formSearch">
        <input type="hidden" name="act" value="area_manage" />
        <input type="hidden" name="op" value="index" />
        <input type="hidden" name="is_search" value="1" />
        <input type="hidden" name="curpage" id="cur_page" value="<?php echo $_GET['curpage']?>" />
        <input type="hidden" name="export" id="export" value="0" />
        <div class="pure-g line">
            <div class="pure-u-1-2">
                省/直辖市/自治区:
                <input type="text" class="text" name="area_name" value="<?php echo trim($_GET['area_name']); ?>" />
            </div>
             <div class="pure-u-1-2">
                <div class="pull-right">
                    <a href="JavaScript:void(0);" class="button" id="sub_btn"><span>查找</span></a>
                    <a href="index.php?act=area_manage&op=index" class="button" ><span>重置</span></a>
                </div>
            </div>
        </div>
    </form>
    <table class="table tb-type1">
        <thead>
        <tr class="thead blue">
           <th></th>
            <th class="wt70">ID</th>
            <th >名称</th>
            <th class="align-center">操作</th>
        </tr>
        </thead>
        <tbody id="datatable">
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
            <?php foreach($output['list'] as $k => $v){ ?>
                <tr class="hover">
                   <td class="w48">
                    <?php if($v['child_count'] > 0){ ?>
                    <img fieldid="<?php echo $v['area_id'];?>" onclick="show_area(this);" status="open" nc_type="flex" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/tv-expandable.gif">
                    <?php }else{ ?>
                    <img fieldid="<?php echo $v['area_id'];?>"  onclick="show_area(this);" status="close" nc_type="flex" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/tv-item.gif">
                    <?php } ?>
                    </td>
                    <td>
                        <?php echo $v['area_id']; ?>
                    </td>
                    <td class="text-left">
                        <?php echo $v['area_name']; ?>
                    </td>
                    <td>
                        <a href="index.php?act=area_manage&op=detail&id=<?php echo $v['area_id'] ?>">编辑</a>
                        <a href="javascript:void(0);" onclick="del(<?=$v['area_id']?>)">删除</a>
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

function del(id){
    post('index.php?act=area_manage&op=del',{"id":id})
    .then(function(res){
        if(res.data.code == 200)
        {
             alert(res.data.msg);
             location.href="index.php?act=area_manage&op=index";
        }else{
            alert(res.data.msg);
        }
    })
}

function show_area(_this){
    var status = $(_this).attr('status');
    if(status == 'open'){
        var pr  = $(_this).parent('td').parent('tr');
        var id  = $(_this).attr('fieldid');
        var obj = $(_this);
        $(_this).attr('status','none');
        $.ajax({
            url: 'index.php?act=area_manage&op=index&ajax=1&area_id='+id,
            dataType: 'json',
            success: function(data){
                
                if(data.code == 200){
                    data  = data.data;
                    for(var i = 0; i < data.length; i++){
                        var src='';
                        src += "<tr class='"+pr.attr('class')+" row"+id+"'>";
                        left  = '|--';
                        if(data[i].area_deep==3)   left  = '|----';
                        //图片
                        src += "<td>";
                        if(data[i].child_count>0)
                            src +=  '<img fieldid="'+data[i].area_id+'" onclick="show_area(this);" status="open" nc_type="flex" src="'+ADMIN_TEMPLATES_URL+'/images/tv-expandable.gif">'
                        else
                            src +=  '<img fieldid="'+data[i].area_id+'" status="close" nc_type="flex" src="'+ADMIN_TEMPLATES_URL+'/images/tv-item.gif">'
                        src += "</td>";
                        src += "<td>";
                        //名称
                        src += data[i].area_id;
                        src += "</td>";
                        src += "<td class='text-left'>"+ left;
                        //名称
                        src += data[i].area_name;
                        src += "</td>";
                        //操作
                        src += "<td>";
                        src += ' <a href="index.php?act=area_manage&op=detail&id='+data[i].area_id+'">编辑</a>';
                        src += ' <a href="javascript:void(0);" onclick="del('+data[i].area_id+')">删除</a>';
                        src += "</td>";
                        src += "</tr>";
                        //插入
                        pr.after(src);
                    }
                    obj.attr('status','close');
                    obj.attr('src',obj.attr('src').replace("tv-expandable","tv-collapsable"));
                    $('img[nc_type="flex"]').unbind('click');
                    //重现初始化页面
                    //$.getScript(RESOURCE_SITE_URL+"/js/jquery.edit.js");
                    //$.getScript(RESOURCE_SITE_URL+"/js/jquery.goods_class.js");	
                }
            },
            error: function(){
                alert('获取信息失败');
            }
        });
    }

    if(status == 'close'){
        $(".row"+$(_this).attr('fieldid')).remove();
        $(_this).attr('src',$(_this).attr('src').replace("tv-collapsable","tv-expandable"));
        $(_this).attr('status','open');
    }

}


    
</script>