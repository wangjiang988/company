<?php defined('InHG') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>自定义字段管理</h3>
      <ul class="tab-base">
        <li><a href="javascript:void(0);" class="current"><span>管理</span></a></li>
        <li><a href="index.php?act=fields&op=add" ><span>新增</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
    <form method="get" action="index.php" name="formSearch" id="formSearch">
        <input type="hidden" name="act" value="fields" />
        <input type="hidden" name="op" value="index" />
        <input type="hidden" name="is_search" value="1" />
        <input type="hidden" name="curpage" id="cur_page" value="<?php echo $_GET['curpage']?>" />
        <div class="pure-g line">
            <div class="pure-u-1-4">
                关联模块:
                <select name="model" id="model">
                    <option value="">全部</option>
                    <?php foreach ($output['carmodel'] as $k => $v) { ?>
                        <option value="<?php echo $k; ?>"
                        <?php if($k==$_GET['model']) echo 'selected';?>
                        ><?php echo $v; ?></option>
                    <?php }?>
                </select>
            </div>
            <div class="pure-u-1-4">
                显示标题:
                <input type="text" class="text" name="name" value="<?php echo trim($_GET['name']); ?>" />
            </div>
            <div class="pure-u-1-2">
                    <a href="JavaScript:void(0);" class="button" id="sub_btn"><span>查找</span></a>
                    <a href="index.php?act=fields&op=index" class="button" ><span>重置</span></a>
            </div>
        </div>
    </form>



  <table class="table tb-type2 nobdb">
    <thead>
      <tr class="thead">
        <th class="align-center">ID</th>
        <th class="align-center">关联模块</th>
        <th class="align-center">字段名</th>
        <th class="align-center">显示标题</th>
        <th class="align-center">字段类型</th>
        <th class="align-center">设置的值</th>
        <th class="align-center">字段说明</th>
        <th class="align-center">排序</th>
        <th class="align-center">搜索</th>
        <th class="align-center">添加显示</th>
        <th class="align-center">列表显示</th>
        <th class="align-center">操作</th>
      </tr>
    <tbody>
      <?php if(!empty($output['fields_list']) && is_array($output['fields_list'])){ ?>
      <?php foreach($output['fields_list'] as $k => $v){ ?>
      <tr class="hover member">
        <td class="align-center"><?php echo $v['id']; ?></td>
        <td class="align-center"><?php echo $output['carmodel'][$v['model']]; ?></td>
        <td class="align-center"><?php echo $v['name']; ?></td>
        <td class="align-center"><?php echo $v['title']; ?></td>
        <td class="align-center"><?php echo $output['type'][$v['type']]; ?></td>
        <td class="align-center"><?php $tmps = unserialize($v['setting']);if(!is_array($tmps)){echo $tmps;}else{echo implode(',', $tmps);} ?></td>
        <td class="align-center"><?php echo $v['desc']; ?></td>
        <td class="align-center"><?php echo $v['sort']; ?></td>
        <td class="align-center"><?php if($v['is_search']){echo '是';}else{echo '否';} ?></td>
        <td class="align-center"><?php if($v['is_add']){echo '是';}else{echo '否';} ?></td>
        <td class="align-center"><?php if($v['is_index']){echo '是';}else{echo '否';} ?></td>
        <td class="align-center">
        <?php
          switch ($v['operating']) {
            case 0:
              echo '<a href="index.php?act=fields&op=edit&id='.$v['id'].'">编辑</a>';
              echo '<br />';
              echo '<a href="javascript:confirm_del('.$v['id'].')">删除</a>';
              break;
            case 1:
              echo '<a href="index.php?act=fields&op=edit&id='.$v['id'].'">编辑</a>';
              break;
            case 2:
                echo '<a href="javascript:confirm_del('.$v['id'].')">删除</a>';
              break;
            default:
              echo '系统固有字段';
              break;
          }
        ?>
        </td>
      </tr>
      <?php } ?>
      <?php }else { ?>
      <tr class="no_data">
        <td colspan="12"><?php echo $lang['nc_no_record']?></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot class="tfoot">
      <?php if(!empty($output['fields_list']) && is_array($output['fields_list'])){ ?>
      <tr>
        <td colspan="12">
          <div class="pagination"> <?php echo $output['page'];?> </div></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>

<script type="text/javascript">

    $(function(){

        $("#sub_btn").click(function(){
            $("#cur_page").val(1);
            document.formSearch.submit();
        });
    });


    function confirm_del(id)
    {
        parent.layer.confirm('确定删除么？？', {
            btn: ['确认','取消'] //按钮
        }, function(){
            location.href='index.php?act=fields&op=del&id='+id;
            closeLayer();
        }, function(){
            closeLayer();
        });
    }
</script>


