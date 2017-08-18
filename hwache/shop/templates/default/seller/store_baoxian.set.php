<?php
/**
 * 经销商代理
 * 报价保险添加模板
 */
defined('InHG') or exit('Access Invalid!');
?>

<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<style>
  .inline{display:inline-block;*display: inline;*zoom: 1;vertical-align: top;}
  .f_m{margin-top: 10px;}
  .f_m_l{}
  .feiyong_t{}
</style>
<ul class="add-goods-step">
  <li><i class="icon icon-list-alt"></i>
    <h6>STIP.1</h6>
    <h2>选择保险公司</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li class="current"><i class="icon icon-edit"></i>
    <h6>STIP.2</h6>
    <h2>填写保险费率</h2>
  </li>
</ul>
<div class="item-publish">
  <form method="post" id="add" action="<?php if ($output['edit_baoxian']) { echo urlShop('store_baoxian', 'editset');} else { echo urlShop('store_baoxian', 'set');}?>">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="baoxian_id" value="<?php echo $output['baoxian_id'];?>">
    <div class="ncsc-form-goods">
<?php
/**
 * 保险参数循环开始
 */
foreach ($output['baoxianType'] as $key => $value) :
?>
    <dl>
      <dt><i class="required">*</i><?php echo $value['title'];?><?php echo $lang['nc_colon'];?></dt>
      <dd>
        <p><?php echo $value['desc'];?></p>
        <table border="0" cellpadding="0" cellspacing="0" class="spec_table">
          <thead>
            <tr>
              <th nctype="spec_name_1">项目</th>
              <th class="w120">个人非营运(6座以下)</th>
              <th class="w120">个人非营运(6~10座)</th>
              <th class="w120">公司非营运(6座以下)</th>
              <th class="w120">公司非营运(6~10座)</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($value['fields'] as $k=>$v) :
            ?>
            <?php
            /**
             * 检测是否有foreach
             * 比如车上人员责任险,区分司机和乘客
             */
              if(isset($value['foreach'])){
                foreach($value['foreach'] as $keyk=>$val) :
                  /**
                   * 检测是否有value
                   * 比如车上人员责任险,司机和乘客都区分每座限额
                   */
                  if(isset($v['value'])) {
                    foreach($v['value'] as $_k=>$_v) :
            ?>
            <tr>
              <td>
                <?php echo $val.'_'.$_v.'_'.$v['title'];?>
              </td>
              <td>
                <input class="text price w80" type="text" name="<?php echo $value['name'];?>[<?php echo $keyk;?>][<?php echo $_k;?>][1][<?php echo $v['name'];?>]" ><em class="add-on"><?php echo $v['dw'];?></em>
              </td>
              <td>
                <input class="text price w80" type="text" name="<?php echo $value['name'];?>[<?php echo $keyk;?>][<?php echo $_k;?>][2][<?php echo $v['name'];?>]" ><em class="add-on"><?php echo $v['dw'];?></em>
              </td>
              <td>
                <input class="text price w80" type="text" name="<?php echo $value['name'];?>[<?php echo $keyk;?>][<?php echo $_k;?>][3][<?php echo $v['name'];?>]" ><em class="add-on"><?php echo $v['dw'];?></em>
              </td>
              <td>
                <input class="text price w80" type="text" name="<?php echo $value['name'];?>[<?php echo $keyk;?>][<?php echo $_k;?>][4][<?php echo $v['name'];?>]" ><em class="add-on"><?php echo $v['dw'];?></em>
              </td>
            </tr>
            <?php
                    endforeach;
                  } else {
                    // 没有value
            ?>
            <tr>
              <td>
                <?php echo $val.'_'.$v['title'];?>
              </td>
              <td>
                <input class="text price w80" type="text" name="<?php echo $value['name'];?>[<?php echo $keyk;?>][1][<?php echo $v['name'];?>]" ><em class="add-on"><?php echo $v['dw'];?></em>
              </td>
              <td>
                <input class="text price w80" type="text" name="<?php echo $value['name'];?>[<?php echo $keyk;?>][2][<?php echo $v['name'];?>]" ><em class="add-on"><?php echo $v['dw'];?></em>
              </td>
              <td>
                <input class="text price w80" type="text" name="<?php echo $value['name'];?>[<?php echo $keyk;?>][3][<?php echo $v['name'];?>]" ><em class="add-on"><?php echo $v['dw'];?></em>
              </td>
              <td>
                <input class="text price w80" type="text" name="<?php echo $value['name'];?>[<?php echo $keyk;?>][4][<?php echo $v['name'];?>]" ><em class="add-on"><?php echo $v['dw'];?></em>
              </td>
            </tr>
            <?php
                  }
                endforeach;
              } else {
                //没有foreach
                /**
                 * 检测是否有value
                 */
                if(isset($v['value'])) {
                  foreach($v['value'] as $_k=>$_v) :
            ?>
            <tr>
              <td>
                <input type="hidden" name=""  /><?php echo $_v.$v['title'];?>
              </td>
              <td>
                <input class="text price w80" type="text" name="<?php echo $value['name'];?>[<?php echo $_k;?>][1][<?php echo $v['name'];?>]" ><em class="add-on"><?php echo $v['dw'];?></em>
              </td>
              <td>
                <input class="text price w80" type="text" name="<?php echo $value['name'];?>[<?php echo $_k;?>][2][<?php echo $v['name'];?>]" ><em class="add-on"><?php echo $v['dw'];?></em>
              </td>
              <td>
                <input class="text price w80" type="text" name="<?php echo $value['name'];?>[<?php echo $_k;?>][3][<?php echo $v['name'];?>]" ><em class="add-on"><?php echo $v['dw'];?></em>
              </td>
              <td>
                <input class="text price w80" type="text" name="<?php echo $value['name'];?>[<?php echo $_k;?>][4][<?php echo $v['name'];?>]" ><em class="add-on"><?php echo $v['dw'];?></em>
              </td>
            </tr>
            <?php
                  endforeach;
                } else {
            ?>
            <tr>
              <td>
                <?php echo $v['title'];?>
              </td>
              <td>
                <input class="text price w80" type="text" name="<?php echo $value['name'];?>[1][<?php echo $v['name'];?>]" ><em class="add-on"><?php echo $v['dw'];?></em>
              </td>
              <td>
                <input class="text price w80" type="text" name="<?php echo $value['name'];?>[2][<?php echo $v['name'];?>]" ><em class="add-on"><?php echo $v['dw'];?></em>
              </td>
              <td>
                <input class="text price w80" type="text" name="<?php echo $value['name'];?>[3][<?php echo $v['name'];?>]" ><em class="add-on"><?php echo $v['dw'];?></em>
              </td>
              <td>
                <input class="text price w80" type="text" name="<?php echo $value['name'];?>[4][<?php echo $v['name'];?>]" ><em class="add-on"><?php echo $v['dw'];?></em>
              </td>
            </tr>
            <?php
                } // 检测是否有value
              } // 检测是否有foreach
            endforeach;
            ?>
          </tbody>
        </table>
      </dd>
    </dl>
    <dl>
<?php
endforeach;
/**
 * 保险参数循环结束
 */
?>
    </div>
    <div class="bottom tc hr32">
      <label class="submit-border">
        <input type="submit" class="submit" value="保存" />
      </label>
    </div>
  </form>
</div>
