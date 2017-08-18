<?php defined('InHG') or exit('Access Invalid!');?>

<div class="tabmenu">
  <ul class="tab pngFix">
    <li class="active"><a href="javascript:;">选择文件</a></li>
  </ul>
</div>
<form method="get" action="index.php">
  <table class="search-form">
    <input type="hidden" name="act" value="store_files" />
    <input type="hidden" name="op" value="add_file" />
    <tr>
      <td>&nbsp;</td>
      <th><?php echo $lang['store_goods_index_store_goods_class'];?></th>
      
      <th>
        <select name="cate_id">
          <?php foreach ($output['cate'] as $key => $value): ?>
            <option value="<?php echo $value['cate_id'] ?>" <?php if($value['cate_id']==$_GET['cate_id']) echo "selected"; ?> ><?php echo $value['cate'] ?></option>
          <?php endforeach ?>
          <option value="">全部</option>
          
        </select>
      </th>
      
      <td class="tc w70"><label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['nc_search'];?>" /></label></td>
    </tr>
  </table>
</form>
<table class="ncsc-table-style">
  <thead>
    <tr nc_type="table_header">
      <th>文件名称</th>
      <th class="w200">场合</th>
      <th class="w100">操作</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['file_list'])) { ?>
    <?php foreach ($output['file_list'] as $val) { ?>
    
    <tr>
      <td><span><?php echo $val['title']; ?></span></td>
      <td><span><?php echo $val['cate']; ?></span></td>
      <td class="nscs-table-handle">
        <span><a href="index.php?act=store_files&op=select_file&id=<?php echo $val['file_id'];?>&isself=1" class="btn-blue"><i class="icon-edit"></i><p>本人资料</p></a></span> <span><a href="index.php?act=store_files&op=select_file&id=<?php echo $val['file_id'];?>&isself=0" class="btn-blue"><i class="icon-edit"></i><p>非本人资料</p></a></span>
      </td>
    </tr>
    
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php  if (!empty($output['file_list'])) { ?>
    <tr>
      <td colspan="20"><div class="pagination"> <?php echo $output['page']; ?> </div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>