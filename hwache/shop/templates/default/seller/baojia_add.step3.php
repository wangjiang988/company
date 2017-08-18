<?php defined('InHG') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui-dialog.css" rel="stylesheet" type="text/css">
<style>
  .ncsc-form-goods dl dt { width: 15%; }
  .ncsc-form-goods dl dd { width: 82%; }
  .spec_table tbody tr td p {margin-bottom: 10px;}
  .ui-dialog{width:auto;}
  .hgmsg{border: 1px solid #f00!important;}
</style>

<ul class="add-goods-step">
  <li><i class="icon icon-list-alt"></i>
    <h6>STIP.1</h6>
    <h2>选择车型</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li><i class="icon icon-edit"></i>
    <h6>STIP.2</h6>
    <h2>填写详情</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li class="current"><i class="icon icon-user-md"></i>
    <h6>STIP.3</h6>
    <h2>经销商信息</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li><i class="icon icon-wrench"></i>
    <h6>STIP.4</h6>
    <h2>选装件</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li><i class="icon icon-ok-circle"></i>
    <h6>STIP.5</h6>
    <h2>完成</h2>
  </li>
</ul>

<div class="item-publish">
  <form method="post" id="form" action="<?php echo urlShop('baojia_add', 'add_step_three');?>">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="bjid" value="<?php echo $output['bjid'];?>" />
    <div class="ncsc-form-goods">
      <h3>报价经销商选择和附加信息</h3>
      <dl>
        <dt><i class="required">*</i>经销商<?php echo $lang['nc_colon'];?></dt>
        <dd>
          <?php if(is_array($output['dealer_list']) && !empty($output['dealer_list'])) { ?>
          <input type="hidden" name="d_name" id="d_name" />
          <select name="d_id" id="d_id">
            <option value="0">请选择</option>
            <?php foreach($output['dealer_list'] as $k=>$v){ ?>
            <option value="<?php echo $v['d_id'];?>"><?php echo $v['d_name'];?></option>
            <?php } ?>
          </select>
          <?php } else { ?>
            <input hgngg="required" hg-type="i" type="hidden" name="d_id">
            <label><a href="index.php?act=store_setting&op=add_dealer">还没有添加经销商,点击添加</a></label>
          <?php } ?>
          <p class="hint">选择经销商</p>
        </dd>
      </dl>
      <div id="jxsdata" style="display:none;">
        <!-- <dl>
          <dt><i class="required">*</i>上级经销商集团名称<?php echo $lang['nc_colon'];?></dt>
          <dd>
            <input hgngg="required" name="topdealer" value="" type="text" class="text w400" />
            <p class="hint">上级经销商集团名称</p>
          </dd>
        </dl> -->
        <dl>
          <dt><i class="required">*</i>可售区域<?php echo $lang['nc_colon'];?></dt>
          <dd>
            <table border="0" cellpadding="0" cellspacing="0" class="spec_table">
              <thead>
                <tr>
                  <th class="w120">省</th>
                  <th>市</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="2"><label><input type="checkbox" name="quanguo" id="quanguo">可售全国</label></td>
                </tr>
              <?php foreach ($output['area'] as $k=>$v) :?>
                <tr>
                  <td><input type="checkbox" class="parent" id="p<?php echo $v['area_id'];?>" name="area[<?php echo $v['area_id'];?>][p]" value="<?php echo $v['area_id'];?>"><label for="p<?php echo $v['area_id'];?>"> <?php echo $v['area_name'];?></label></td>
                  <td>
                    <?php foreach ($v['child'] as $_k=>$_v) :?>
                      <input type="checkbox" class="son" id="s<?php echo $_v['area_id'];?>" data-pid="<?php echo $v['area_id'];?>" name="area[<?php echo $v['area_id'];?>][s][]" value="<?php echo $_v['area_id'];?>"><label for="s<?php echo $_v['area_id'];?>"> <?php echo $_v['area_name'];?> </label>
                    <?php endforeach;?>
                  </td>
                </tr>
              <?php endforeach;?>
              </tbody>
            </table>
          </dd>
        </dl>
        <dl>
          <dt>商业保险限定<?php echo $lang['nc_colon'];?></dt>
          <dd>
            <ul class="ncsc-form-radio-list">
              <li>
                <input type="checkbox" name="baoxian" id="baoxian"> <label for="baoxian">限定保险</label>
              </li>
              <li>
                <?php if(!empty($output['bx_setting'])) :?>
                <select name="baoxianselect" id="baoxianselect" class="bxzj">
                  <option value="0">请选择保险</option>
                  <?php foreach($output['bx_setting'] as $k=>$v) : ?>
                  <option value="<?php echo $v['id'];?>"><?php echo $v['title'];?></option>
                  <?php endforeach;?>
                </select>
                <?php endif;?>
              </li>
              <li>保险折扣 <input type="text" name="baoxian_discount" value="100"> %</li>
            </ul>
            <div id="tpl_bx"></div>
            <table border="0" cellpadding="0" cellspacing="0" class="spec_table">
              <thead>
                <tr>
                  <th colspan="2">委托销售商办理保险，客户须配合提供资料： </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="w120">个人投保</td>
                  <td>
                    <p>有效身份证件（本市户籍居民：身份证；持工作居住证居民：身份证、工作居住证；持暂住证居民：身份证、暂住证；军人：身份证、军官证；港澳台居民：通行证、港澳台居民身份证；外国人：护照）<br />
                      <label><input type="checkbox" name="grbx_sfz[yj]" class="bxzj" > 原件 </label>
                      <label><input type="checkbox" name="grbx_sfz[fyj]" class="bxzj" > 复印件 </label>
                      <input name="grbx_sfz[num]" hg-type="i" class="bxzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>驾驶证：
                      <label><input type="checkbox" name="grbx_jsz[yj]" class="bxzj" > 原件 </label>
                      <label><input type="checkbox" name="grbx_jsz[fyj]" class="bxzj" > 复印件 </label>
                      <input name="grbx_jsz[num]" hg-type="i" class="bxzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>代办人身份证：
                      <label><input type="checkbox" name="grbx_dbr[yj]" class="bxzj" > 原件 </label>
                      <label><input type="checkbox" name="grbx_dbr[fyj]" class="bxzj" > 复印件 </label>
                      <input name="grbx_dbr[num]" hg-type="i" class="bxzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>授权书：
                      <label><input type="checkbox" name="grbx_sqs[yj]" class="bxzj" > 原件 </label>
                      <label><input type="checkbox" name="grbx_sqs[fyj]" class="bxzj" > 复印件 </label>
                      <input name="grbx_sqs[num]" hg-type="i" class="bxzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>亲属关系证明（户口簿或结婚证）：
                      <label><input type="checkbox" name="grbx_qsgx[yj]" class="bxzj" > 原件 </label>
                      <label><input type="checkbox" name="grbx_qsgx[fyj]" class="bxzj" > 复印件 </label>
                      <input name="grbx_qsgx[num]" hg-type="i" class="bxzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                  </td>
                </tr>
                <tr>
                  <td class="w120">企业投保</td>
                  <td>
                    <p>营业执照：
                      <label><input type="checkbox" name="gsbx_yyzj[yj]" class="bxzj" > 原件 </label>
                      <label><input type="checkbox" name="gsbx_yyzj[fyj]" class="bxzj" > 复印件 </label>
                      <input name="gsbx_yyzj[num]" hg-type="i" class="bxzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>组织机构代码证：
                      <label><input type="checkbox" name="gsbx_zzjg[yj]" class="bxzj" > 原件 </label>
                      <label><input type="checkbox" name="gsbx_zzjg[fyj]" class="bxzj" > 复印件 </label>
                      <input name="gsbx_zzjg[num]" hg-type="i" class="bxzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>税务登记证：
                      <label><input type="checkbox" name="gsbx_swdj[yj]" class="bxzj" > 原件 </label>
                      <label><input type="checkbox" name="gsbx_swdj[fyj]" class="bxzj" > 复印件 </label>
                      <input name="gsbx_swdj[num]" hg-type="i" class="bxzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>法人身份证：
                      <label><input type="checkbox" name="gsbx_frsfz[yj]" class="bxzj" > 原件 </label>
                      <label><input type="checkbox" name="gsbx_frsfz[fyj]" class="bxzj" > 复印件 </label>
                      <input name="gsbx_frsfz[num]" hg-type="i" class="bxzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>委托书：
                      <label><input type="checkbox" name="gsbx_wts[yj]" class="bxzj" > 原件 </label>
                      <label><input type="checkbox" name="gsbx_wts[fyj]" class="bxzj" > 复印件 </label>
                      <input name="gsbx_wts[num]" hg-type="i" class="bxzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>代办人身份证：
                      <label><input type="checkbox" name="gsbx_dbr[yj]" class="bxzj" > 原件 </label>
                      <label><input type="checkbox" name="gsbx_dbr[fyj]" class="bxzj" > 复印件 </label>
                      <input name="gsbx_dbr[num]" hg-type="i" class="bxzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>公章：√</p>
                  </td>
                </tr>
              </tbody>
            </table>
            <p class="hint">是否要在经销商办理保险</p>
          </dd>
        </dl>
        <dl>
          <dt>上牌服务限定<?php echo $lang['nc_colon'];?></dt>
          <dd>
            <ul class="ncsc-form-radio-list">
              <li>
                <!--<input type="checkbox" name="shangpai" id="shangpai">-->
                  <select name="shangpai">
                    <option value="0">自助上牌</option>
                    <option value="1">指定上牌</option>
                    <option value="2">接受安排</option>
                  </select>
                 <label for="shangpai">限定上牌</label> 
              </li>
              <li>
                上牌费用<input hgngg="required" hg-type="i" name="shangpai_price" id="shangpai_price" value="0" type="text" class="text w80" /><em class="add-on"><i class="icon-renminbi"></i></em> <span></span>
              </li>
            </ul>
            <table border="0" cellpadding="0" cellspacing="0" class="spec_table">
              <thead>
                <tr>
                  <th colspan="2">委托销售商办理上牌，客户须配合提供资料： </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="w120">个人上牌</td>
                  <td>
                    <p>有效身份证件（本市户籍居民：身份证；持工作居住证居民：身份证、工作居住证；持暂住证居民：身份证、暂住证；军人：身份证、军官证；港澳台居民：通行证、港澳台居民身份证；外国人：护照）<br />
                      <label><input type="checkbox" name="grsp_sfz[yj]" class="spzj" > 原件 </label>
                      <label><input type="checkbox" name="grsp_sfz[fyj]" class="spzj" > 复印件 </label>
                      <input name="grsp_sfz[num]" hg-type="i" class="spzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>驾驶证：
                      <label><input type="checkbox" name="grsp_jsz[yj]" class="spzj" > 原件 </label>
                      <label><input type="checkbox" name="grsp_jsz[fyj]" class="spzj" > 复印件 </label>
                      <input name="grsp_jsz[num]" hg-type="i" class="spzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>代办人身份证：
                      <label><input type="checkbox" name="grsp_dbr[yj]" class="spzj" > 原件 </label>
                      <label><input type="checkbox" name="grsp_dbr[fyj]" class="spzj" > 复印件 </label>
                      <input name="grsp_dbr[num]" hg-type="i" class="spzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>授权书：
                      <label><input type="checkbox" name="grsp_sqs[yj]" class="spzj" > 原件 </label>
                      <label><input type="checkbox" name="grsp_sqs[fyj]" class="spzj" > 复印件 </label>
                      <input name="grsp_sqs[num]" hg-type="i" class="spzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>亲属关系证明（户口簿或结婚证）：
                      <label><input type="checkbox" name="grsp_qsgx[yj]" class="spzj" > 原件 </label>
                      <label><input type="checkbox" name="grsp_qsgx[fyj]" class="spzj" > 复印件 </label>
                      <input name="grsp_qsgx[num]" hg-type="i" class="spzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                  </td>
                </tr>
                <tr>
                  <td class="w120">企业上牌</td>
                  <td>
                    <p>营业执照：
                      <label><input type="checkbox" name="gssp_yyzj[yj]" class="spzj" > 原件 </label>
                      <label><input type="checkbox" name="gssp_yyzj[fyj]" class="spzj" > 复印件 </label>
                      <input name="gssp_yyzj[num]" hg-type="i" class="spzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>组织机构代码证：
                      <label><input type="checkbox" name="gssp_zzjg[yj]" class="spzj" > 原件 </label>
                      <label><input type="checkbox" name="gssp_zzjg[fyj]" class="spzj" > 复印件 </label>
                      <input name="gssp[num]" hg-type="i" class="spzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>税务登记证：
                      <label><input type="checkbox" name="gssp_swdj[yj]" class="spzj" > 原件 </label>
                      <label><input type="checkbox" name="gssp_swdj[fyj]" class="spzj" > 复印件 </label>
                      <input name="gssp_swdj[num]" hg-type="i" class="spzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>法人身份证：
                      <label><input type="checkbox" name="gssp_frsfz[yj]" class="spzj" > 原件 </label>
                      <label><input type="checkbox" name="gssp_frsfz[fyj]" class="spzj" > 复印件 </label>
                      <input name="gssp_frsfz[num]" hg-type="i" class="spzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>委托书：
                      <label><input type="checkbox" name="gssp_wts[yj]" class="spzj" > 原件 </label>
                      <label><input type="checkbox" name="gssp_wts[fyj]" class="spzj" > 复印件 </label>
                      <input name="gssp_wts[num]" hg-type="i" class="spzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>代办人身份证：
                      <label><input type="checkbox" name="gssp_dbr[yj]" class="spzj" > 原件 </label>
                      <label><input type="checkbox" name="gssp_dbr[fyj]" class="spzj" > 复印件 </label>
                      <input name="gssp_dbr[num]" hg-type="i" class="spzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>公章：√</p>
                  </td>
                </tr>
              </tbody>
            </table>
            <p class="hint">是否要在经销商处办理上牌</p>
          </dd>
        </dl>
        <dl>
          <dt>上临牌服务限定<?php echo $lang['nc_colon'];?></dt>
          <dd>
            <ul class="ncsc-form-radio-list">
              <li>
                <input type="checkbox" name="linpai" id="linpai"> <label for="linpai">限定上临牌</label>
              </li>
              <li>
                上临牌费用<input hgngg="required" hg-type="i" name="linpai_price" id="linpai_price" value="0" type="text" class="text w80" /><em class="add-on"><i class="icon-renminbi"></i></em> <span></span>
              </li>
            </ul>
            <table border="0" cellpadding="0" cellspacing="0" class="spec_table">
              <thead>
                <tr>
                  <th colspan="2">委托销售商办理上临牌，客户须配合提供资料： </th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="w120">个人上临牌</td>
                  <td>
                    <p>有效身份证件（本市户籍居民：身份证；持工作居住证居民：身份证、工作居住证；持暂住证居民：身份证、暂住证；军人：身份证、军官证；港澳台居民：通行证、港澳台居民身份证；外国人：护照）<br />
                      <label><input type="checkbox" name="grslp_sfz[yj]" class="slpzj" > 原件 </label>
                      <label><input type="checkbox" name="grslp_sfz[fyj]" class="slpzj" > 复印件 </label>
                      <input name="grslp_sfz[num]" hg-type="i" class="slpzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>驾驶证：
                      <label><input type="checkbox" name="grslp_jsz[yj]" class="slpzj" > 原件 </label>
                      <label><input type="checkbox" name="grslp_jsz[fyj]" class="slpzj" > 复印件 </label>
                      <input name="grslp_jsz[num]" hg-type="i" class="slpzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>代办人身份证：
                      <label><input type="checkbox" name="grslp_dbr[yj]" class="slpzj" > 原件 </label>
                      <label><input type="checkbox" name="grslp_dbr[fyj]" class="slpzj" > 复印件 </label>
                      <input name="grslp_dbr[num]" hg-type="i" class="slpzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>授权书：
                      <label><input type="checkbox" name="grslp_sqs[yj]" class="slpzj" > 原件 </label>
                      <label><input type="checkbox" name="grslp_sqs[fyj]" class="slpzj" > 复印件 </label>
                      <input name="grslp_sqs[num]" hg-type="i" class="slpzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>亲属关系证明（户口簿或结婚证）：
                      <label><input type="checkbox" name="grslp_qsgx[yj]" class="slpzj" > 原件 </label>
                      <label><input type="checkbox" name="grslp_qsgx[fyj]" class="slpzj" > 复印件 </label>
                      <input name="grslp_qsgx[num]" hg-type="i" class="slpzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                  </td>
                </tr>
                <tr>
                  <td class="w120">企业上临牌</td>
                  <td>
                    <p>营业执照：
                      <label><input type="checkbox" name="gsslp_yyzj[yj]" class="slpzj" > 原件 </label>
                      <label><input type="checkbox" name="gsslp_yyzj[fyj]" class="slpzj" > 复印件 </label>
                      <input name="gsslp_yyzj[num]" hg-type="i" class="slpzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>组织机构代码证：
                      <label><input type="checkbox" name="gsslp_zzjg[yj]" class="slpzj" > 原件 </label>
                      <label><input type="checkbox" name="gsslp_zzjg[fyj]" class="slpzj" > 复印件 </label>
                      <input name="gsslp[num]" hg-type="i" class="slpzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>税务登记证：
                      <label><input type="checkbox" name="gsslp_swdj[yj]" class="slpzj" > 原件 </label>
                      <label><input type="checkbox" name="gsslp_swdj[fyj]" class="slpzj" > 复印件 </label>
                      <input name="gsslp_swdj[num]" hg-type="i" class="slpzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>法人身份证：
                      <label><input type="checkbox" name="gsslp_frsfz[yj]" class="slpzj" > 原件 </label>
                      <label><input type="checkbox" name="gsslp_frsfz[fyj]" class="slpzj" > 复印件 </label>
                      <input name="gsslp_frsfz[num]" hg-type="i" class="slpzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>委托书：
                      <label><input type="checkbox" name="gsslp_wts[yj]" class="slpzj" > 原件 </label>
                      <label><input type="checkbox" name="gsslp_wts[fyj]" class="slpzj" > 复印件 </label>
                      <input name="gsslp_wts[num]" hg-type="i" class="slpzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>代办人身份证：
                      <label><input type="checkbox" name="gsslp_dbr[yj]" class="slpzj" > 原件 </label>
                      <label><input type="checkbox" name="gsslp_dbr[fyj]" class="slpzj" > 复印件 </label>
                      <input name="gsslp_dbr[num]" hg-type="i" class="slpzj text w40" value="0" type="text" /><em class="add-on">份</em> <span></span>
                    </p>
                    <p>公章：√</p>
                  </td>
                </tr>
              </tbody>
            </table>
            <p class="hint">是否要在经销商处办理上临牌</p>
          </dd>
        </dl>
        <dl>
          <dt>上牌地牌照费用<?php echo $lang['nc_colon'];?></dt>
          <dd>
            <input hg-type="i" name="paizhao_price" value="0" type="text" class="text w80" /><em class="add-on"><i class="icon-renminbi"></i></em> <span></span>
            <p class="hint">部分限牌城市需要填写此价格，大多数不需要牌照费用</p>
          </dd>
        </dl>
        <dl>
          <dt>赠品/附加服务<?php echo $lang['nc_colon'];?></dt>
          <dd>
            <table cellspacing="0" cellpadding="0" border="0" class="spec_table">
              <thead>
                <tr>
                  <th></th>
                  <th>赠品名称</th>
                  
                  <th>数量</th>
                  <th>是否已经安装</th>
                </tr>
              </thead>
              <?php foreach ($output['zengpin'] as $key => $value): ?>
                <tr>
                <td><input type="checkbox" name="zengpin[<?php echo $value['id']; ?>][id]" value="<?php echo $value['id']; ?>"></td>
                <td><?php echo $value['title']; ?>

                </td>
                
                <td><input type="text" name="zengpin[<?php echo $value['id']; ?>][num]" value="1"></td>
                <td><select name="zengpin[<?php echo $value['id']; ?>][is_install]">
                      <option value="1" selected="selected">是</option>
                      <option value="0">否</option>
                    </select></td>
              </tr>
              <?php endforeach ?>
            </table>
            <p class="hint">经销商在交车时的赠品或者附加的服务</p>
          </dd>
        </dl>
        <dl>
          <dt>刷信用卡次数<?php echo $lang['nc_colon'];?></dt>
          <dd>
          <p>
            <input type="radio" name="xinyongka" checked="checked" class="body_color" value="1" hgid="xyk1">免费不限次数
          </p>
          <p>
            <ul class="ncsc-form-radio-list">
              <li>
                <input type="radio" name="xinyongka" class="body_color" value="2" hgid="xyk1">免费 <input hg-type="i" type="text" class="w30" name="xykm2" value="1" id="xinyongka"><em class="add-on">次</em>
              </li>
              <li>超出次数的收
              费:</li>
              <li><label>刷卡金额的 </label>

              <input hg-type="i" class="text price w40" type="text" name="xykr2" id="xyk1" value="1" ><em class="add-on">%</em></li>

              <li><label>每次 </label>
              <input hg-type="i" class="text price w40" type="text" name="xykq2" id="xyk2" value="50" ><em class="add-on"><i class="icon-renminbi"></i></em>元(封顶)</li>
            </ul>
          </p>
            <p>
              <ul class="ncsc-form-radio-list">
              <li>
                <input type="radio" name="xinyongka" class="body_color" value="3" hgid="xyk1">免费 <input hg-type="i" type="text" class="w30" name="xykm3" value="1" id="xinyongka"><em class="add-on">次</em>
              </li>
              <li>超出次数的收
              费:</li>
              <li><label>刷卡金额的 </label>
              <input hg-type="i" class="text price w40" type="text" name="xykr3" id="xyk1" value="1" ><em class="add-on">%</em></li>
            </ul>

            </p>
            <p>
              <ul class="ncsc-form-radio-list">
              <li>
                <input type="radio" name="xinyongka" class="body_color" value="4" hgid="xyk1">免费 <input hg-type="i" type="text" class="w30" name="xykm4" value="1" id="xinyongka"><em class="add-on">次</em>
              </li>
              <li>超出次数的收
              费:</li>
              <li><label>每次 </label>
              <input hg-type="i" class="text price w40" type="text" name="xykq4" id="xyk2" value="50" ><em class="add-on"><i class="icon-renminbi"></i></em>元(封顶)</li>
            </ul>
            </p>
          </dd>
        </dl>
        <dl>
          <dt>刷借记卡次数<?php echo $lang['nc_colon'];?></dt>
          <dd>
          <p>
            <input type="radio" name="jiejika" checked="checked" class="body_color" value="1" hgid="xyk1">免费不限次数
          </p>
          <p>
            <ul class="ncsc-form-radio-list">
              <li>
                <input type="radio" name="jiejika" class="body_color" value="2" hgid="xyk1">免费 <input hg-type="i" type="text" class="w30" name="jjkm2" value="1" id="xinyongka"><em class="add-on">次</em>
              </li>
              <li>超出次数的收
              费:</li>
              <li><label>刷卡金额的 </label>

              <input hg-type="i" class="text price w40" type="text" name="jjkr2" id="xyk1" value="1" ><em class="add-on">%</em></li>

              <li><label>每次 </label>
              <input hg-type="i" class="text price w40" type="text" name="jjkq2" id="xyk2" value="50" ><em class="add-on"><i class="icon-renminbi"></i></em>元(封顶)</li>
            </ul>
          </p>
            <p>
              <ul class="ncsc-form-radio-list">
              <li>
                <input type="radio" name="jiejika" class="body_color" value="3" hgid="xyk1">免费 <input hg-type="i" type="text" class="w30" name='jjkm3' vlue="1"id="xinyongka"><em class="add-on">次</em>
              </li>
              <li>超出次数的收
              费:</li>
              <li><label>刷卡金额的 </label>
              <input hg-type="i" class="text price w40" type="text" name="jjkr3" id="xyk1" value="1" ><em class="add-on">%</em></li>
            </ul>

            </p>
            <p>
              <ul class="ncsc-form-radio-list">
              <li>
                <input type="radio" name="jiejika" class="body_color" value="4" hgid="xyk1">免费 <input hg-type="i" type="text" class="w30" name="jjkm4" value="1" id="xinyongka"><em class="add-on">次</em>
              </li>
              <li>超出次数的收
              费:</li>
              <li><label>每次 </label>
              <input hg-type="i" class="text price w40" type="text" name="jjkq4" id="xyk2" value="50" ><em class="add-on"><i class="icon-renminbi"></i></em>元(封顶)</li>
            </ul>
            </p>
          </dd>
        </dl>
        <?php if (!empty($output['butie'])) : //是否支持国家补贴 ?>
        <dl>
          <dt>国家节能补贴<?php echo $lang['nc_colon'];?></dt>
          <dd>
            <p class="butiep"><label><input type="radio" name="butieradio" value="1" checked="checked"> 交车时当场兑现，由销售商垫付，消费者上牌后补充所缺资料给销售商</label></p>
            <p class="butiep"><label><input type="radio" name="butieradio" hgid="butieday" value="2"> 上牌资料齐全后，由销售商立即垫付给消费者 </label><input hg-type="i" type="text" class="w30 butieinputtext" name="butieday" value="" id="butieday" disabled="disabled" style="background:#E7E7E7 none;">个工作日内</p>
            <p class="butiep"><label><input type="radio" name="butieradio" hgid="butiemonth" value="3"> 交车后，销售商将所有资料交给汽车厂商，厂商直接付给消费者，或者厂商付销售商再由销售商付消费者 </label><input hg-type="i" type="text" class="w30 butieinputtext" name="butiemonth" value="" id="butiemonth" disabled="disabled" style="background:#E7E7E7 none;">个月内</p>
            <p class="hint">补消费者可领取国家节能补贴流程与时限</p>
          </dd>
        </dl>
        <?php endif;?>
        <dl>
          <dt>
            其他费用：
          </dt>
          <dd>
            <table cellspacing="0" cellpadding="0" border="0" class="spec_table">
              <thead>
                <tr>
                  <th>名称</th>
                  <th>费用</th>
                </tr>
              </thead>
             <tbody>
             <?php 
              foreach ($output['other'] as $key => $value) {
                ?>
                <tr>
                <td><?php echo $value['title']; ?></td>
                <td><input name="other_price[<?php echo $value['name'] ;?>]" type="text" value=""></td>
              </tr>
              <?php
              }
              ?>
             
             </tbody>
             </table>

          </dd>
        </dl>
        <!--<dl>
          <dt>牌照指标代办<?php echo $lang['nc_colon'];?></dt>
          <dd>
            <ul class="ncsc-form-radio-list">
              <li>
                <input type="checkbox" name="zhibiao" id="zhibiao" value="1"> <label for="linpai">指标代办</label>
              </li>
              <li>
                代办费用<input  hg-type="i" name="zhibiao_price" id="zhibiao_price" value="0" type="text" class="text w80" /><em class="add-on"><i class="icon-renminbi"></i></em> <span></span>
              </li>
            </ul>
            
          </dd>
        </dl>-->
        <dl>
          <dt>
            交车时交付文件资料：
          </dt>
          <dd>
           <table cellspacing="0" cellpadding="0" border="0" class="spec_table">
              <thead>
                <tr>
                  <th></th>
                  <th>名称</th>
                  <th>数量</th>
                  <th>备注</th>
                </tr>
              </thead>
             <tbody>
             <?php 
              foreach ($output['suiche'] as $key => $value) {
                ?>
                <?php if ($value['type']!='文件资料') continue; ?>

                <tr>
                <td><input type="checkbox" name="wenjian[]" value="<?php echo $value['id']; ?>"></td>
                <td><?php echo $value['title']; ?></td>
                <td><?php echo $value['num']; ?></td>
                <td><?php echo $value['notice']; ?></td>
              </tr>
              <?php
              }
              ?>
             
             </tbody>
             </table>

          </dd>
        </dl>
        <dl>
          <dt>
            交车时交付随车工具：
          </dt>
          <dd>
            <table cellspacing="0" cellpadding="0" border="0" class="spec_table">
              <thead>
                <tr>
                  <th></th>
                  <th>名称</th>
                  <th>数量</th>
                  <th>备注</th>
                </tr>
              </thead>
             <tbody>
             <?php 
              foreach ($output['suiche'] as $key => $value) {
                ?>
                <?php if ($value['type']!='随车工具') continue; ?>
                <tr>
                <td><input type="checkbox" name="gongju[]" value="<?php echo $value['id']; ?>"></td>
                <td><?php echo $value['title']; ?></td>
                <td><?php echo $value['num']; ?></td>
                <td><?php echo $value['notice']; ?></td>
              </tr>
              <?php
              }
              ?>
             
             </tbody>
             </table>

          </dd>
        </dl>
      </div>
    </div>
    <div class="bottom tc hr32">
      <label class="submit-border">
        <input type="submit" class="submit" value="下一步" />
      </label>
    </div>
  </form>
</div>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/datetime/WdatePicker.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/template.js"></script>
<script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/dialog-min.js"></script>
<script id="tplbx" type="text/html">
  <table border="0" cellpadding="0" cellspacing="0" class="spec_table">
    <thead>
      <tr>
        <th nctype="spec_name_1">保险项目<input type="hidden" name="type" value="{{type}}" /></th>
        {{if type}}
        <th class="w200">个人非营运(6座以下)</th>
        <th class="w200">公司非营运(6座以下)</th>
        {{else}}
        <th class="w200">个人非营运(6~10座)</th>
        <th class="w200">公司非营运(6~10座)</th>
        {{/if}}
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>机动车损失险<?php echo $lang['nc_colon'];?></td>
        <td>
          <input hgngg="required" hg-type="i" class="text price w80" type="text" name="chesun[gr]" value="{{chesun.gr}}" ><em class="add-on">元</em>
        </td>
        <td>
          <input hgngg="required" hg-type="i" class="text price w80" type="text" name="chesun[gs]" value="{{chesun.gs}}" ><em class="add-on">元</em>
        </td>
      </tr>
      <tr>
        <td>机动车盗抢险<?php echo $lang['nc_colon'];?></td>
        <td>
          <input hgngg="required" hg-type="i" class="text price w80" type="text" name="daoqiang[gr]" value="{{daoqiang.gr}}" ><em class="add-on">元</em>
        </td>
        <td>
          <input hgngg="required" hg-type="i" class="text price w80" type="text" name="daoqiang[gs]" value="{{daoqiang.gs}}" ><em class="add-on">元</em>
        </td>
      </tr>
      {{each sanzhe as value index}}
      <tr>
        <td>第三者责任险{{index}}万元赔付额度<?php echo $lang['nc_colon'];?></td>
        <td>
          <input hgngg="required" hg-type="i" class="text price w80" type="text" name="sanzhe[{{index}}][gr]" value="{{value.gr}}" ><em class="add-on">元</em>
        </td>
        <td>
          <input hgngg="required" hg-type="i" class="text price w80" type="text" name="sanzhe[{{index}}][gs]" value="{{value.gs}}" ><em class="add-on">元</em>
        </td>
      </tr>
      {{/each}}
      {{each renyuan as value index}}
      {{each value as v k}}
      <tr>
        <td>车上人员({{if index == 'sj'}}司机{{else}}乘客单个座位{{/if}}{{k}}万事故责任限额)责任险<?php echo $lang['nc_colon'];?></td>
        <td>
          <input hgngg="required" hg-type="i" class="text price w80" type="text" name="renyuan[{{index}}][{{k}}][gr]" value="{{v.gr}}" ><em class="add-on">元</em>
        </td>
        <td>
          <input hgngg="required" hg-type="i" class="text price w80" type="text" name="renyuan[{{index}}][{{k}}][gs]" value="{{v.gs}}" ><em class="add-on">元</em>
        </td>
      </tr>
      {{/each}}
      {{/each}}
      {{each boli as value index}}
      <tr>
        <td>玻璃单独破碎险({{if index == 'jk'}}进口{{else}}国产{{/if}})<?php echo $lang['nc_colon'];?></td>
        <td>
          <input hgngg="required" hg-type="i" class="text price w80" type="text" name="boli[{{index}}][gr]" value="{{value.gr}}" ><em class="add-on">元</em>
        </td>
        <td>
          <input hgngg="required" hg-type="i" class="text price w80" type="text" name="boli[{{index}}][gs]" value="{{value.gs}}" ><em class="add-on">元</em>
        </td>
      </tr>
      {{/each}}
      <tr>
        <td>自燃损失险<?php echo $lang['nc_colon'];?></td>
        <td>
          <input hgngg="required" hg-type="i" class="text price w80" type="text" name="ziran[gr]" value="{{ziran.gr}}" ><em class="add-on">元</em>
        </td>
        <td>
          <input hgngg="required" hg-type="i" class="text price w80" type="text" name="ziran[gs]" value="{{ziran.gs}}" ><em class="add-on">元</em>
        </td>
      </tr>
      {{each huahen as value index}}
      <tr>
        <td>车身划痕险{{index}}元赔付额度<?php echo $lang['nc_colon'];?></td>
        <td>
          <input hgngg="required" hg-type="i" class="text price w80" type="text" name="huahen[{{index}}][gr]" value="{{value.gr}}" ><em class="add-on">元</em>
        </td>
        <td>
          <input hgngg="required" hg-type="i" class="text price w80" type="text" name="huahen[{{index}}][gs]" value="{{value.gs}}" ><em class="add-on">元</em>
        </td>
      </tr>
      {{/each}}
      <tr>
        <td>不计免赔特约(机动车损失)险<?php echo $lang['nc_colon'];?></td>
        <td>
          <input hgngg="required" hg-type="i" class="text price w80" type="text" name="bujimian[chesun][gr]" value="{{bujimian.chesun.gr}}" ><em class="add-on">元</em>
        </td>
        <td>
          <input hgngg="required" hg-type="i" class="text price w80" type="text" name="bujimian[chesun][gs]" value="{{bujimian.chesun.gs}}" ><em class="add-on">元</em>
        </td>
      </tr>
      <tr>
        <td>不计免赔特约(机动车盗抢)险<?php echo $lang['nc_colon'];?></td>
        <td>
          <input hgngg="required" hg-type="i" class="text price w80" type="text" name="bujimian[daoqiang][gr]" value="{{bujimian.daoqiang.gr}}" ><em class="add-on">元</em>
        </td>
        <td>
          <input hgngg="required" hg-type="i" class="text price w80" type="text" name="bujimian[daoqiang][gs]" value="{{bujimian.daoqiang.gs}}" ><em class="add-on">元</em>
        </td>
      </tr>
      {{each bujimian.sanzhe as value index}}
      <tr>
        <td>不计免赔特约(第三者责任{{index}}万元赔付额度)险<?php echo $lang['nc_colon'];?></td>
        <td>
          <input hgngg="required" hg-type="i" class="text price w80" type="text" name="bujimian[sanzhe][{{index}}][gr]" value="{{value.gr}}" ><em class="add-on">元</em>
        </td>
        <td>
          <input hgngg="required" hg-type="i" class="text price w80" type="text" name="bujimian[sanzhe][{{index}}][gs]" value="{{value.gs}}" ><em class="add-on">元</em>
        </td>
      </tr>
      {{/each}}
      {{each bujimian.renyuan as value index}}
      {{each value as v k}}
      <tr>
        <td>不计免赔特约(车上人员({{if index == 'sj'}}司机{{else}}乘客{{/if}}{{k}}万事故责任限额))险<?php echo $lang['nc_colon'];?></td>
        <td>
          <input hgngg="required" hg-type="i" class="text price w80" type="text" name="bujimian[renyuan][{{index}}][{{k}}][gr]" value="{{v.gr}}" ><em class="add-on">元</em>
        </td>
        <td>
          <input hgngg="required" hg-type="i" class="text price w80" type="text" name="bujimian[renyuan][{{index}}][{{k}}][gs]" value="{{v.gs}}" ><em class="add-on">元</em>
        </td>
      </tr>
      {{/each}}
      {{/each}}
      {{each bujimian.huahen as value index}}
      <tr>
        <td>不计免赔特约(车身划痕{{index}}元赔付额度)险<?php echo $lang['nc_colon'];?></td>
        <td>
          <input hgngg="required" hg-type="i" class="text price w80" type="text" name="bujimian[huahen][{{index}}][gr]" value="{{value.gr}}" ><em class="add-on">元</em>
        </td>
        <td>
          <input hgngg="required" hg-type="i" class="text price w80" type="text" name="bujimian[huahen][{{index}}][gs]" value="{{value.gs}}" ><em class="add-on">元</em>
        </td>
      </tr>
      {{/each}}
    </tbody>
  </table>
</script>
<script>
  $(function(){

    // 选择经销商后加载经销商的数据
    $('#d_id').change(function(){
      var v = parseInt($(this).val());
      if(v>0){
        // 经销商名称
        var d_n = $(this).find('option:checked').text();
        $("#d_name").val(d_n);
        $.getJSON('index.php?act=baojia_add&op=ajaxgetdealer',{id:v},function(d){
          $('#jxsdata').show();

          // 可售区域
          $(".parent,.son").removeAttr('checked');
          $("#s"+d.data.shi).attr('checked',true);

          // 保险限定
          if (d.data.baoxian==1) {
            $("#baoxian").attr('checked',true);
          } else {
            $("#baoxian").removeAttr('checked');
          }
          $("#baoxianselect").val(0);
          $("#tpl_bx").html('');
          // baoxian();

          // 上牌限定
          if (d.data.shangpai==1) {
            $("#shangpai").attr('checked',true);
          } else {
            $("#shangpai").removeAttr('checked');
          }
          // shangpai();

          // 上临牌限定
          if (d.data.linpai==1) {
            $("#linpai").attr('checked',true);
          } else {
            $("#linpai").removeAttr('checked');
          }
          // linpai();

        });
      } else {
        $("#d_name").val('');
        $('#jxsdata').hide();
      }
    });

    // 可售区域 父级选择子级
    $('.parent').click(function(){
      var that = $(this);
      // var that_val = that.val();
      var that_checked = that.attr('checked');
      if(that_checked=='checked') {
        that.parent().next().find('input').attr('checked', true);
      } else {
        that.parent().next().find('input').attr('checked', false);
      }
      qg(); // 全国
    });
    // 可售区域 子级选择父级
    $('.son').click(function(){
      var that = $(this);
      var pid = that.attr('data-pid');
      var parent = $('#p'+pid);
      var that_checked = that.attr('checked');
      if(that_checked=='checked') {
        var v = that.parent().find('input:not(:checked)');
        if(v.length==0){
          parent.attr('checked', true);
        } else {
          parent.attr('checked', false);
        }
      } else {
        parent.attr('checked', false);
      }
      qg();
    });
    // 可售全国
    $("#quanguo").click(function(){
      if($(this).attr('checked')=='checked') {
        $(".parent").attr('disabled', true).css('background','#E7E7E7 none');
        $(".son").attr('disabled', true).css('background','#E7E7E7 none');
      } else {
        $(".parent").removeAttr('disabled').css('background','');
        $(".son").removeAttr('disabled').css('background','');
      }
    });
    // 全国检测
    var qg = function(){
      var p = $(".parent:not(:checked)");
      var s = $(".son:not(:checked)");
      if(p.length==0 && s.length==0) {
        $("#quanguo").attr('checked', true);
      } else {
        $("#quanguo").attr('checked', false);
      }
    };

    // 保险检测
    // $("#baoxian").click(function(){
    //   baoxian();
    // });
    // var baoxian = function(){
    //   var bx = $("#baoxian").attr('checked');
    //   var bxzj = $(".bxzj"); // 保险证件
    //   if(bx=='checked') {
    //     bxzj.removeAttr('disabled').css('background','');
    //   } else {
    //     bxzj.attr('disabled', true).css('background','#E7E7E7 none');
    //   }
    // };

    // 上牌检测
    // $("#shangpai").click(function(){
    //   shangpai();
    // });
    // var shangpai = function(){
    //   var sp = $("#shangpai").attr('checked');
    //   var spzj = $(".spzj"); // 上牌证件
    //   var sp_p = $("#shangpai_price");
    //   if(sp=='checked') {
    //     spzj.removeAttr('disabled').css('background','');
    //     sp_p.removeAttr('disabled').css('background','');
    //   } else {
    //     spzj.attr('disabled', true).css('background','#E7E7E7 none');
    //     sp_p.attr('disabled', true).css('background','#E7E7E7 none');
    //   }
    // };

    // 上临牌检测
    // $("#linpai").click(function(){
    //   linpai();
    // });
    // var linpai = function(){
    //   var slp = $("#linpai").attr('checked');
    //   var slpzj = $(".slpzj"); // 上牌证件
    //   var slp_p = $("#linpai_price");
    //   if(slp=='checked') {
    //     slpzj.removeAttr('disabled').css('background','');
    //     slp_p.removeAttr('disabled').css('background','');
    //   } else {
    //     slpzj.attr('disabled', true).css('background','#E7E7E7 none');
    //     slp_p.attr('disabled', true).css('background','#E7E7E7 none');
    //   }
    // };

    // 保险报价下拉列表
    $('#baoxianselect').change(function(){
      var v = parseInt($(this).val());
      if(v>0){
        $.getJSON('index.php?act=baojia_add&op=ajaxgetbx',{bjid:<?php echo $output['bjid'];?>,bxid:v},function(d){
          var html = template('tplbx', d);
          $("#tpl_bx").html(html);
        });
      } else {
        $("#tpl_bx").html('');
      }
    });

    // 检测应填写数字类型的输入非数字
    $('input[hg-type="i"]').live('blur',function(){
      var fi = parseFloat($(this).val());
      if (isNaN(fi)) {
        $(this).val(0);
      } else {
        $(this).val(fi);
      }
    });
    // 单选项的选中和同名的其他非转中的效果
    $(":radio").click(function(){
      var t = $(this);
      var self = t.attr('hgid');
      var name = t.attr('name');
      disinput(name,self,true);
    });
    // 默认禁用没有选择的文本框
    $(":radio").each(function(){
      var t = $(this);
      if (t.attr('checked')=='checked') {
        var self = t.attr('hgid');
        var name = t.attr('name');
        disinput(name,self,false);
      };
    });
    // 共用函数
    function disinput(name,self,boolen) {
      $("input[name="+name+"]").each(function(){
        var id = $(this).attr('hgid');
        $("#"+id).attr('disabled', true).css('background','#E7E7E7 none');
      });
      if (boolen) {
        $("#"+self).removeAttr('disabled').css('background','').focus();
      } else {
        $("#"+self).removeAttr('disabled').css('background','');
      }
    }


    // 提交表单检测
    $("#form").submit(function(){
      var ok = false;
      var t = null;
      // 可售区域检查
      if($("#quanguo,.parent:checked,.son:checked").length==0){
        $("#quanguo").focus();
        var d = dialog({
          content: '请选择可售区域'
        }).show();
        setTimeout(function () {
          d.close().remove();
        }, 1000);
        return false;
      }
      // 保险限定
      if ($("#baoxian").attr('checked')=='checked') {
        if ($("#baoxianselect").val()==0) {
          $("#baoxianselect").focus();
          var d = dialog({
            content: '请选择保险'
          }).show();
          setTimeout(function () {
            d.close().remove();
          }, 1000);
          return false;
        };
      };
      $("input[hgngg='required']").each(function(){
        // console.log($(this).attr("value"));
        if ($(this).val().length==0 || $(this).attr("value")==0) {
          if ($(this).attr("disabled")!='disabled') {
            if (t==null) {t=$(this);}
            ok = false;
            $(this).addClass('hgmsg');
          };
        } else {
          if ($(this).attr("disabled")=='disabled') {
            $(this).removeClass('hgmsg');
          };
        }
      });
      if (!ok) {
        t.focus();
        var d = dialog({
          content: '不能为空或0'
        }).show();
        setTimeout(function () {
          d.close().remove();
        }, 1000);
        return false;
      };
    });


  });
</script>
