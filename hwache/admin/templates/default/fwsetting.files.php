<?php defined('InHG') or exit('Access Invalid!');?>
<?php extract($output)?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>文件设置</h3>
      <ul class="tab-base">
          <li><a class="current"><span>交车需要的文件设置</span></a></li>
          <li><a href="index.php?act=fwsetting&op=fileadd"><span>添加</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  
  <form method="get" action="index.php" name="formSearch" id="formSearch">
        <input type="hidden" name="act" value="fwsetting" />
        <input type="hidden" name="op" value="files" />
        <input type="hidden" name="is_search" value="1" />
        <input type="hidden" name="curpage" id="cur_page" value="<?php echo $_GET['curpage']?>" />


      <div class="pure-g line">
          <div class="pure-u-1-4">
              车辆用途:
              <select name="car_use_type" id="car_use_type">
                  <option value="">全部</option>
                  <option value="0"
                    <?php if($_GET['car_use_type'] === '0') echo "selected"; ?>
                  >
                      非营业个人客车
                  </option>
                  <option value="1"
                      <?php if($_GET['car_use_type'] === '1') echo "selected"; ?>
                  >
                      非营业企业客车
                  </option>
                  <option value="2"
                      <?php if($_GET['car_use_type'] === '2') echo "selected"; ?>
                  >
                      其他
                  </option>
              </select>
          </div>
          <div class="pure-u-1-2">
              上牌（注册登记）车主身份类别 :
              <select name="identity_id" id="identity_id" style="min-width: 100px;">
                  <option value="">全部</option>
              </select>
          </div>
          <div class="pure-u-1-4">
          </div>
      </div>

        <div class="pure-g line">
            <div class="pure-u-1-4">
                使用场合:
                <select name="cate_id" id="cate_id">
                    <option value="">全部</option>
<!--                    --><?php //if($cate_list){?>
<!--                        --><?php //foreach($cate_list as $item){ ?>
<!--                            <option value="--><?//=$item['cate_id']?><!--"-->
<!--                                    --><?php //if($_GET['cate_id']==$item['cate_id'] ) echo "selected";?>
<!--                            >--><?//=$item['cate']?><!--</option>-->
<!--                        --><?php //}?>
<!--                    --><?php //}?>
                </select>
            </div>
            <div class="pure-u-1-4">
                文件名称:
                <input type="text" class="text" name="title" value="<?php echo trim($_GET['title']); ?>" />
            </div>
             <div class="pure-u-1-2">
              <div class="">
                    <a href="JavaScript:void(0);" class="button" id="sub_btn"><span>查找</span></a>
                  <a href="index.php?act=fwsetting&op=files" class="button" id="sub_btn"><span>重置</span></a>
                </div>
            </div>
        </div>
       
    </form>

    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th>车辆用途</th>
          <th>上牌（注册登记）车主身份类别</th>
          <th>使用场合</th>
          <th>文件名称</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
        <?php foreach($output['list'] as $k => $v){ ?>
        <tr class="hover">
          <td><?php
              if($v['car_use_type'] ==1) echo '非营业企业客车';
              elseif($v['car_use_type'] ==2) echo '其他';
              elseif($v['car_use_type']==0)  echo   '非营业个人客车';
          ?></td>
          <td><?php echo $v['identity_name']; ?></td>
          <td><?php echo $v['cate']?></td>
          <td><?php echo $v['title']?></td>
          <td class="align-center">
              <a href="index.php?act=fwsetting&op=fileedit&id=<?php echo $v['file_id']?>">编辑</a>
              <a href="javascript:confirm_del(<?php echo $v['file_id']?>)">删除</a>
          </td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td colspan="5"><?php echo $lang['nc_no_record'];?></td>
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
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.serializejson.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript">
    $(function(){
        $("#sub_btn").click(function(){
            $("#cur_page").val(1);
            document.formSearch.submit();
        });
        $("#car_use_type").change(function(){
            let car_use_type = $(this).val();
            get_cate(car_use_type)
            get_identity(car_use_type);
        });
        var car_use_type = $("#car_use_type").val();
        get_identity(car_use_type);
        get_cate(car_use_type)
    });

    function confirm_del(id)
    {
        parent.layer.confirm('确定删除么？？', {
            btn: ['确认','取消'] //按钮
        }, function(){
            location.href='index.php?act=fwsetting&op=filedel&id='+id;
            closeLayer();
        }, function(){
            closeLayer();
        });
    }

    //获取身份列表
    function get_identity(car_use_type)
    {
        if(car_use_type == 2)
        {
            $('#identity_id').html('<option value="">全部</option>');
            return false;
        }
        post('index.php?act=fwsetting&op=get_identity_by_carusetype',{'car_use_type':car_use_type})
            .then(function(res){
                if(res.data.code=200)
                {
                    let identity_id = <?=$_GET['identity_id']?$_GET['identity_id']:'0'?>;
                    html = '<option value="">全部</option>';
                    data = res.data.data;
                    if(data){
                        for (x in data)
                        {
                            if(data[x].id === ''+identity_id ){
                                html += '<option  value="'+data[x].id+'" selected>'+data[x].identity_name+'</option>'
                            }else{
                                html += '<option  value="'+data[x].id+'">'+data[x].identity_name+'</option>'
                            }
                        }
                        $('#identity_id').html(html);
                    }else{
                        $('#identity_id').html('<option value="">全部</option>');
                    }


                }else{
                    alert(res.data.msg);
                }
            })
    }

    //获取场合列表
    function get_cate(car_use_type)
    {
        post('index.php?act=fwsetting&op=get_cate_by_carusetype',{'car_use_type':car_use_type})
            .then(function(res){
                if(res.data.code=200)
                {
                    let cate_id = <?=$_GET['cate_id']?$_GET['cate_id']:'0'?>;
                    html = '<option value="">全部</option>';
                    data = res.data.data;
                    if(data){
                        for (x in data)
                        {
                            if(data[x].cate_id  === ''+cate_id ){
                                html += '<option  value="'+data[x].cate_id+'" selected>'+data[x].cate+'</option>'
                            }else{
                                html += '<option  value="'+data[x].cate_id+'">'+data[x].cate+'</option>'
                            }
                        }
                        $('#cate_id').html(html);
                    }else{
                        $('#cate_id').html('<option value="">全部</option>');
                    }


                }else{
                    alert(res.data.msg);
                }
            })
    }


</script>