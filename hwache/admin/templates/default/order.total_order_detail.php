<?php defined('InHG') or exit('Access Invalid!');?>
<!-- 新 Bootstrap 核心 CSS 文件 -->
<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
<!-- 最新的 Bootstrap 核心 JavaScript 文件 -->
<!--<script src="http://cdn.bootcss.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>-->
<link href="<?php echo ADMIN_TEMPLATES_URL;?>/css/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
<link rel="stylesheet" href="<?php echo ADMIN_TEMPLATES_URL;?>/css/font/font-awesome/css/font-awesome-ie7.min.css">
<![endif]-->
<link rel="stylesheet" href="<?php echo RESOURCE_SITE_URL;?>/js/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
    <link href="resource/themes/bootstrap.css" rel="stylesheet" />
    <link href="resource/themes/user.css" rel="stylesheet" />
<style>
  body{
    padding:15px;
  }
  .box-border {
    border-top:1px solid #ddd;
  }
  a,a:hover{
    text-decoration: none;
  }
 .cur-step a{
     color: white;
 }
  #one{
    display:inline-table;
  }
  #one td {
    line-height: 28px;
    text-align: center;
    font-size: 15px;
  }
   .two,.other_table {
    margin-left: 10px;
   }
    .two td,.other_table td {
    line-height: 28px;
    text-align: center;
    font-size: 15px;
  }
  .two tr:nth-child(1),.other_table tr:nth-child(1) {
    background-color: rgb(188,188,188);
  }
  #one tr td:nth-child(1) {
    background-color: rgb(188,188,188);
  }
  .table-center{
    vertical-align:middle;
    text-align: center
  }
  .small_title{
    margin: 30px 0;
  }
  .small_title span{
    background-color: RGB(255,137,49);
    color: #fff;
    display: inline-block;
    padding: 6px 40px;
  }
</style>
                    <h2 class="title">
                        <span class="juhuang fs18">订单总详情</span>
                    </h2>
                    <table class="fs14" width="1200px;">
                      <tr>
                      <td width="50%">
                        <p class="ml10">
                          <span class="inline-block w200 "><b>订单号：</b><?=$output['order']['order_sn']?></span>
                        </p>
                      </td>
                        <td width="50%">
                           <p>
                              <span class="inline-block ml100"><b>订单时间：</b><?=$output['order']['created_at']?></span>
                           </p>

                        </td>
                      </tr>
                      <tr>
                        <td>
                          <p class="ml10">
                              <span class="inline-block w200 "><b>订单类别：</b><?php echo ($output['order']['is_xianche'] ==1)? '现车':'非现车'?></span>

                           </p>
                        </td>
                        <td>
                          <p>
                            <span class="inline-block ml100"><b>查看时间：</b><?=date('Y-m-d H:i:s',time())?></span>
                          </p>
                        </td>
                      </tr>
                      <tr>
                      <td style="height: 100px;">
                        <div style="border: 1px solid rgb(121,121,121);padding: 10px;">
                          <p><span style="font-size: 20px;">客户状态：</span><span style="color: blue;font-size: 15px"><?=$output['order_status']['user_progress']?></span><span style="margin-left: 15px;color: rgb(153,153,153);"><?=$output['order_status']['member_remark']?></span></p>
                          <?php $arr = [2011,2012,2021,2022,2031,2032];?>
                          <?php if(in_array($output['order']['order_state'], $arr)):?>
                          <p><strong style="margin-right: 10px;">等待售方反馈时长:</strong><span style="font-size: 15px;font-weight: bold;"><?=$output['order']['rockon_time']?></span></p>
                         <?php endif;?>
                        </div>
                        </td>
                        <td>
                        <div style="border: 1px solid rgb(121,121,121);padding: 10px;margin-left: 100px;" class="inline-block ml100">
                         <p><span style="font-size: 20px;">售方状态：</span><span style="color: blue;font-size: 15px"><?=$output['order_status']['seller_progress']?></span><span style="margin-left: 15px;color: rgb(153,153,153);"><?=$output['order_status']['seller_remark']?></span></p>
                         <?php if(in_array($output['order']['order_state'],[2011,2012,2031,2032])):?>
                          <p><strong style="margin-right: 10px;">确认售方修改内容时限:</strong><span style="font-size: 15px;font-weight: bold;"><?=$output['order']['rockon_time']?></span></p>
                        <?php elseif(in_array($output['order']['order_state'], [301,303,302])):?>
                          <p><strong style="margin-right: 10px;">交车邀请发出时限:</strong><span style="font-size: 15px;font-weight: bold;">
                          <?php if($output['order']['is_xianche']):?>
                            <?php echo date('Y-m-d',strtotime($output['info']['car_astrict']) - 8*26*3600);?> 24:00:00
                          <?php else:?>
                          <?php echo date('Y-m-d',strtotime($output['info']['car_astrict']) - 7*26*3600);?> 24:00:00
                        <?php endif;?>
                          </span></p>
                        <?php endif;?>
                        </div>
                        </td>
                      </tr>
                    </table>

                    <div class="box-border fs14 p10" style="margin-top: 20px;">
                      <p><b class="juhuang">商品信息</b></p>
                      <p><b>品牌车系车型规格：</b><?=$output['order']['gc_name']?></p>
                      <table>
                        <tr>
                          <td width="575">
                              <p><b>整车型号： </b><?=$output['car_info']['vehicle_model'];?></p>
                          </td>
                          <td>
                              <p><b>厂商指导价： </b>￥<?=number_format(unserialize($output['car_info']['value']),2);?></p>
                          </td>
                        </tr>
                        <tr>
                          <td>
                              <p><b>基本配置： </b><a href="<?=$output['car_info']['detail_img'];?>"><span class="blue">查看</span></a></p>
                          </td>
                          <td>
                              <p><b>生产国别：</b>国产</p>
                          </td>
                        </tr>
                        <tr>
                          <td>
                              <p><b>座位数： </b><?=$output['info']['car_seating']?>座</p>
                          </td>
                          <td>
                              <p><b>车辆类别：</b>全新中规车整车</p>
                          </td>
                        </tr>
                        <tr>
                          <td>
                              <p><b>排放标准： </b><?=$output['info']['car_paifang']?></p>
                          </td>
                          <td>
                              <p><b>数量：</b>1台</p>
                          </td>
                        </tr>
                      </table>
                      <p><b>车身颜色：</b><?=$output['info']['body_color']?></p>
                      <p><b>内饰颜色：</b><?=$output['info']['interior_color']?></p>
                    </div>
                    <div class="m-t-10"></div>
                    <?php $tab = $_GET['tab'];?>
                    <ul class="detail-step-wrapper fs14">
                       <li <?php if($tab == 1) :?> class="cur-step" <?php endif;?>><a href="index.php?act=order&op=order_detail&order_id=<?=$_GET['order_id']?>&tab=1">订单前序</li>
                       <li <?php if($tab == 2) :?> class="cur-step" <?php endif;?>><a href="index.php?act=order&op=order_detail&order_id=<?=$_GET['order_id']?>&tab=2">交易约定</a></li>
                       <li <?php if($tab == 3) :?> class="cur-step" <?php endif;?>><a href="index.php?act=order&op=order_detail&order_id=<?=$_GET['order_id']?>&tab=3">交易提示</a></li>
                       <li <?php if($tab == 4) :?> class="cur-step" <?php endif;?>><a href="index.php?act=order&op=order_detail&order_id=<?=$_GET['order_id']?>&tab=4">订单后记</a></li>
                       <li <?php if($tab == 5) :?> class="cur-step" <?php endif;?>><a href="index.php?act=order&op=order_detail&order_id=<?=$_GET['order_id']?>&tab=5">担保结算</a></li>
                       <li <?php if($tab == 6) :?> class="cur-step" <?php endif;?>><a href="index.php?act=order&op=order_detail&order_id=<?=$_GET['order_id']?>&tab=6">进程记录</a></li>
                       <li <?php if($tab == 7) :?> class="cur-step" <?php endif;?>><a href="index.php?act=order&op=order_detail&order_id=<?=$_GET['order_id']?>&tab=7">特殊进程</a></li>
                       <div class="clear"></div>
                    </ul>
                    <?php if($tab==1) :?>
                    <div class="clear"></div>
                    <div class="box-border p10 fs14">
                    <p class="small_title"><span style="margin-left: 60px"><strong>客户</strong></span> <span style="margin-left: 40%"><strong>售方</strong></span></p>
                    <table border="1px" width="40%" style="margin-left: 60px" id="one">
                      <tr>
                        <td width="265px">华车车价</td>
                        <td><?=$output['order']['hwache_price']?></td>
                      </tr>
                      <tr>
                        <td>买车担保金约定</td>
                        <td><?=$output['order']['sponsion_price']?></td>
                      </tr>
                      <tr>
                        <td>交车地点范围约定</td>
                        <td><?=$output['areas']?></td>
                      </tr>
                      <tr>
                        <td>上牌服务约定</td>
                        <td>自行办理</td>
                      </tr>
                       <?php if($output['info']['shangpai_status'] != 1):?>
                      <tr>
                        <td>代办上牌服务费参考金额</td>
                        <td><?=$output['order']['agent_numberplate_price']?></td>
                      </tr>
                       <?php endif;?>
                      <tr>
                        <td>上临牌约定</td>
                        <td>自行办理</td>
                      </tr>
                      <tr>
                        <td>代办临牌（每次）服务费参考金额:</td>
                        <td><?=$output['order']['agent_temp_numberplate_price']?></td>
                      </tr>
                       <tr>
                        <td>计划上牌地区</td>
                        <td><?=$output['area']['area_name']?></td>
                      </tr>
                      <tr>
                        <td>行驶里程</td>
                        <?php if($output['order']['is_xianche']):?>
                        <td>不高于<?=$output['info']['mileage']?></td>
                        <?php else:?>
                          <td>不高于20公里</td>
                        <?php endif;?>
                      </tr>
                      <tr>
                        <td>出厂年月</td>
                        <?php if($output['order']['is_xianche']):?>
                        <td>不早于<?=$output['info']['year_month']?></td>
                        <?php else:?>
                          <td>不早于<?=date('Y-m',strtotime($output['order']['created_at']))?></td>
                        <?php endif;?>
                      </tr>
                      <?php if($output['order']['is_xianche']):?>
                     <tr>
                        <td>交车周期</td>
                        <td><?=$output['info']['cycle']?></td>
                      </tr>
                    <?php endif;?>
                      <tr>
                        <td>商业车险投保约定</td>
                        <td>自行办理</td>
                      </tr>
                      <tr>
                        <td>原厂选装精品折扣率</td>
                        <td><?=$output['order']['bj_xzj_zhekou']?>%</td>
                      </tr>
                    </table>


                    <table border="1px" width="40%" style="margin-left: 120px" id="one">
                      <tr>
                        <td width="271px">经销商所属地区</td>
                        <td><?=$output['dealer']['d_areainfo'];?></td>
                      </tr>
                      <tr>
                        <td>报价编号</td>
                        <td><?=$output['order']['bj_serial']?></td>
                      </tr>
                      <tr>
                        <td>客户买车定金</td>
                        <td><?=$output['order']['client_hand_price']?></td>
                      </tr>
                      <tr>
                        <td>售方服务费</td>
                        <td><?=$output['order']['agent_service_price']?></td>
                      </tr>
                      <tr>
                        <td>报价上牌条件</td>
                        <td>自行办理</td>
                      </tr>
                      <?php if($output['info']['shangpai_status'] != 1):?>
                      <tr>
                        <td>代办上牌服务费</td>
                        <td><?=$output['order']['agent_numberplate_price']?></td>
                      </tr>
                      <?php endif;?>
                      <tr>
                        <td>报价临牌条件</td>
                        <td>自行办理</td>
                      </tr>
                       <tr>
                        <td>代办临牌（每次）服务费</td>
                        <td><?=$output['order']['agent_temp_numberplate_price']?></td>
                      </tr>
                      <tr>
                        <td>内部车辆编号</td>
                        <td><?=$output['order']['bj_dealer_internal_id']?></td>
                      </tr>
                      <tr>
                        <td>计划上牌地区</td>
                        <td><?=$output['area']['area_name']?></td>
                      </tr>
                     <tr>
                        <td>行驶里程</td>
                         <?php if($output['order']['is_xianche']):?>
                        <td>不高于<?=$output['info']['mileage']?></td>
                        <?php else:?>
                          <td>不高于20公里</td>
                        <?php endif;?>
                      </tr>
                      <tr>
                        <td>出厂年月</td>
                        <?php if($output['order']['is_xianche']):?>
                        <td>不早于<?=$output['info']['year_month']?></td>
                        <?php else:?>
                          <td>不早于<?=date('Y-m',strtotime($output['order']['created_at']))?></td>
                        <?php endif;?>
                      </tr>
                       <?php if($output['order']['is_xianche']):?>
                      <tr>
                        <td>交车周期</td>
                        <td><?=$output['info']['cycle']?></td>
                      </tr>
                      <?php endif;?>
                      <tr>
                        <td>商业车险投保约定</td>
                        <td>自行办理</td>
                      </tr>
                      <tr>
                        <td>原厂选装精品折扣率</td>
                        <td><?=$output['order']['bj_xzj_zhekou']?>%</td>
                      </tr>
                    </table>

                    </div>
                    <?php elseif($tab==2) :?>
                      <div style="text-align: center;width:950px;margin: 30px auto">
                    	<table id="one" width="875px" border="1px">
                      <tr>
                        <td width="225px">车辆开票价</td>
                        <td><?=number_format($output['order']['car_price'],2)?></td>
                      </tr>
                       <tr>
                        <td>华车服务费</td>
                        <td><?=number_format($output['order']['hwache_service_price'],2)?></td>
                      </tr>
                      <tr>
                        <td>付款方式</td>
                        <td>全款</td>
                      </tr>
                      <tr>
                        <td>经销商</td>
                        <td><?=$output['dealer']['d_name']?></td>
                      </tr>
                      <tr>
                        <td>营业地点</td>
                        <td><?=$output['dealer']['d_yy_place']?></td>
                      </tr>
                      <tr>
                        <td>交车地点</td>
                        <td><?=$output['dealer']['d_jc_place']?></td>
                      </tr>
                      <tr>
                        <td>约定交车时间</td>
                        <?php $days = [1=>'全天', 2=>'上午',3=>'下午'];?>
                       <td><?=$output['info']['car_jiaoche_at']?><?php echo $days[$output['info']['car_jiaoche_day']];?></td>
                      </tr>
                      </table>

                    <?php if(array_filter(array_column($output['other_price'], 'other_price'))):?>
                   <p style="margin: 50px 0 30px 10px;text-align: left;" class="small_title"><span>其他收费</span></p>
                   <table border="1px" width="355px" id="two" class="two">
                     <tr>
                       <td width="218px">费用名称</td>
                       <td>金额</td>
                     </tr>
                     <?php foreach($output['other_price'] as $other):?>
                     <tr>
                       <td><?=$other['other_name'];?></td>
                       <td data-price=<?=$other['other_price'];?>><?=number_format($other['other_price'],2);?></td>
                     </tr>
                     <tr>
                       <td colspan="2" style="text-align: right;"></td>
                     </tr>
                   <?php endforeach;?>
                   </table>
                 <?php endif;?>

                 <?php if(array_filter(array_column($output['edit_info']['xzj'], 'num'))):?>
                   <p style="margin: 50px 0 30px 10px;text-align: left;" class="small_title"><span>已装原厂选装精品：：</span></p>
                   <table width="900px" border="1" class="two">
                     <tr>
                       <td>名称</td>
                       <td>型号/说明</td>
                       <td>厂商指导价</td>
                       <td>数量</td>
                       <td>附加价值</td>
                     </tr>
                     <?php foreach($output['edit_info']['xzj'] as $xzj):?>
                      <?php if($xzj['num'] != 0):?>
                     <tr>
                       <td><?=$xzj['xzj_title']?></td>
                       <td><?=$xzj['xzj_model']?></td>
                       <td>￥<?=number_format($xzj['xzj_guide_price'],2)?></td>
                       <td><?=$xzj['num']?></td>
                       <td data-price=<?=$xzj['xzj_guide_price']*$xzj['num']?>>￥<?=(number_format($xzj['xzj_guide_price']*$xzj['num'],2))?></td>
                     </tr>
                   <?php endif;?>
                   <?php endforeach;?>
                   <tr>
                      <td colspan="5" style="text-align: right;"></td>
                   </tr>
                   </table>
                   <?php endif;?>

                   <?php if(array_filter(array_column($output['edit_info']['zp'], 'num'))):?>
                    <p style="margin: 50px 0 30px 10px;text-align: left;" class="small_title"><span>免费礼品或服务：</span></p>
                    <table width="900px" border="1" class="other_table">
                      <tr>
                        <td>名称</td>
                        <td>数量</td>
                        <td>状态</td>
                      </tr>
                      <?php foreach($output['edit_info']['zp'] as $zp):?>
                        <?php if($zp['num'] != 0):?>
                      <tr>
                        <td><?=$zp['zp_title']?></td>
                        <td><?=$zp['num']?></td>
                        <td>
                        <?php $status = isset($zp['zp_status']) ? $zp['zp_status']: $zp['is_install'];
                        echo ($status) ? '已安装' : '未安装';?>
                        </td>
                      </tr>
                     <?php endif;?>
                    <?php endforeach;?>
                    </table>
                   <?php endif;?>

                  <?php if(array_filter(array_column($output['arr_xzjs']['yc'], 'xzj_num'))):?>
                   <p style="margin: 50px 0 30px 10px;text-align: left;" class="small_title"><span>客户已订购原厂选装精品：</span></p>
                   <table width="900" class="two" border="1">
                     <tr>
                       <td>名称</td>
                       <td>型号/说明</td>
                       <td>厂商编号</td>
                       <td>厂商指导价</td>
                       <td>安装费</td>
                       <td>含安装费折后总单价</td>
                       <td>已定件数</td>
                       <td>订购时间</td>
                       <td>金额</td>
                     </tr>
                    <?php foreach($output['arr_xzjs']['yc'] as $yc):?>
                     <tr>
                       <td><?=$yc['xzj_title']?></td>
                       <td><?=$yc['xzj_model']?></td>
                       <td><?=$yc['xzj_title']?></td>
                       <td><?=$yc['xzj_guide_price']?></td>
                       <td><?=$yc['xzj_fee']?></td>
                       <td><?=number_format($yc['xzj_guide_price'] * $output['order']['bj_xzj_zhekou']/100+$yc['xzj_fee'],2)?></td>
                       <td><?=$yc['xzj_num']?></td>
                       <td><?=$yc['created_at']?></td>
                       <td data-price="<?=($yc['xzj_guide_price'] * $output['order']['bj_xzj_zhekou']/100+$yc['xzj_fee']) * $yc['xzj_num']?>"><?=number_format(($yc['xzj_guide_price'] * $output['order']['bj_xzj_zhekou']/100+$yc['xzj_fee']) * $yc['xzj_num'],2)?></td>
                     </tr>
                   <?php endforeach;?>
                   <tr>
                       <td colspan="9" style="text-align: right;"></td>
                    </tr>
                   </table>
                 <?php endif;?>

                  <?php if(array_filter(array_column($output['arr_xzjs']['fyc'], 'xzj_num'))):?>
                   <p style="margin: 50px 0 30px 10px;text-align: left;" class="small_title"><span>客户已订购非原厂选装精品：</span></p>
                   <table width="900" class="two" border="1">
                     <tr>
                       <td>品牌</td>
                       <td>名称</td>
                       <td>型号/说明</td>
                       <td>厂商编号</td>
                       <td>含安装费折后总单价</td>
                       <td>已订件数</td>
                       <td>订购时间</td>
                       <td>金额</td>
                     </tr>
                    <?php foreach($output['arr_xzjs']['fyc'] as $yc):?>
                     <tr>
                       <td>原厂</td>
                       <td><?=$yc['xzj_title']?></td>
                       <td><?=$yc['xzj_model']?></td>
                       <td><?=$yc['xzj_title']?></td>
                       <td><?=$yc['xzj_fee']?></td>
                       <td><?=$yc['xzj_num']?></td>
                       <td><?=$yc['created_at']?></td>
                       <td data-price=<?=($yc['xzj_fee']*$yc['xzj_num'])?>><?=($yc['xzj_fee']*$yc['xzj_num'])?></td>
                     </tr>
                   <?php endforeach;?>
                      <tr>
                       <td colspan="8" style="text-align: right;"></td>
                     </tr>
                   </table>
                 <?php endif;?>

                      </div>
                    <?php elseif($tab==3) :?>
                      <p style="margin-top: 50px; margin-left: 260px;" class="small_title"><span>客户有关</span></p>
                      <div style="text-align: center;">
                    	<table width="875px" border="1px" id="one" style="margin:30px auto">
                       <tr>
                          <td width="320px">客户会员号</td>
                          <td><?=$output['users']['id']?></td>
                        </tr>
                        <?php if($output['users']['is_id_verify'] == 1): ?>
                        <tr>
                          <td>客户姓名</td>
                          <td><?=$output['users']['last_name']?><?=$output['users']['first_name']?></td>
                        </tr>
                        <?php endif;?>

                        <?php if($output['users']['call']):?>
                        <tr>
                          <td>客户称呼</td>
                          <td><?=$output['users']['call']?></td>
                        </tr>
                      <?php endif;?>
                        <tr>
                          <td>客户手机号</td>
                          <td><?=$output['users']['phone']?></td>
                        </tr>
                        <?php if($output['order']['order_state'] >= 401 && $output['order_state'] != 403 && $output['order']['order_status'] >= 4):?>
                        <tr>
                          <td>提车人姓名</td>
                          <td><?=$output['info']['extract_name']?></td>
                        </tr>
                        <tr>
                          <td>提车人电话</td>
                          <td><?=$output['info']['extract_phone']?></td>
                        </tr>
                        <tr>
                          <td>计划上牌车主名称</td>
                          <td><?=$output['info']['owner_name']?></td>
                        </tr>
                        <tr>
                          <td>上牌车主名称与提车人姓名是否一致</td>
                          <td><?php echo ($output['info']['extract_name'] == $output['info']['owner_name']) ? '是':'否'?></td>
                        </tr>
                       <?php endif;?>
                       <?php if($output['area']['area_xianpai']): ?>
                        <tr>
                          <td>上牌车主取得牌照指标的方式</td>
                          <td><?php echo ($output['info']['license_tag'])?'已取得牌照指标':'在订车或提车后自行办理牌照指标'?></td>
                        </tr>
                      <?php endif;?>
                        <tr>
                          <td>客户前往提车的方式</td>
                          <td>本人安排</td>
                        </tr>
                        <tr>
                          <td>客户车辆的回程方式</td>
                          <td>本人安排</td>
                        </tr>
                    <?php if($output['order']['order_state'] >= 302 && $output['order']['order_status'] >= 3):?>
                        <tr>
                          <td>车主车辆用途</td>
                          <?php $use =['无','非营业个人客车(私家车)','企业客车'];?>
                          <td><?=$use[$output['info']['car_purpose']]?></td>
                        </tr>
                        <tr>
                          <td>车主身份类别</td>
                          <td><?=$output['user_type']['identity_name']?></td>
                        </tr>
                      <?php endif;?>
                      </table>

                      <p style="margin-right: 760px;" class="small_title"><span>售方有关</span></p>
                      <table id="one" width="875px" border="1px">
                        <tr>
                          <td width="320px">售方用户名</td>
                          <td><?=$output['dealers']['member_name']?></td>
                        </tr>
                        <tr>
                          <td>售方姓名</td>
                          <td><?=$output['dealers']['member_truename']?></td>
                        </tr>
                        <tr>
                          <td>售方手机号</td>
                          <td><?=$output['dealers']['member_mobile']?></td>
                        </tr>
                        <tr>
                          <td>经销商类别</td>
                          <td>授权经销商</td>
                        </tr>
                      <?php if($output['order']['order_state'] > 402 && $output['order']['order_status'] >= 4):?>
                        <tr>
                          <td>服务专员姓名</td>
                          <td><?=$output['waiter']['name']?></td>
                        </tr>
                        <tr>
                          <td>服务专员手机号</td>
                          <td><?=$output['waiter']['mobile']?></td>
                        </tr>
                        <?php if($output['waiter']['tel']):?>
                        <tr>
                          <td>服务专员备用电话</td>
                          <td><?=$output['waiter']['tel']?></td>
                        </tr>
                        <?php endif;?>
                      <?php endif;?>
                        <tr>
                          <td>单车付款刷信用卡收费标准</td>
                          <?php if($output['order']['xyk_status']):?>
                          <td>免费次数：<?=$output['order']['xyk_number']?>次，超出次数收费：刷卡金额的<?=$output['order']['xyk_per_num']?>%（百分之）<?php if($xyk_yuan_num > 0):?>每次<?php $output['order']['xyk_yuan_num']?>元（封顶）<?php endif;?></td>
                        <?php else:?>
                          <td>单车付款刷信用卡免费次数：不限</td>
                        <?php endif;?>
                        </tr>
                        <tr>
                          <td>单车付款刷借记卡收费标准</td>
                          <?php if($output['order']['jjk_status']):?>
                          <td>免费次数：<?=$output['order']['jjk_number']?>次，超出次数收费：刷卡金额的<?=$output['order']['jjk_per_num']?>%（百分之）<?php if($jjk_yuan_num > 0):?>每次<?php $output['order']['jjk_yuan_num']?>元（封顶）<?php endif;?></td>
                        <?php else:?>
                          <td>单车付款刷借记卡收费标准：不限</td>
                        <?php endif;?>
                        </tr>
                        <?php if($output['order']['order_status'] >=3 && $output['order']['order_state'] != 300):?>
                        <tr>
                          <td>交车邀请发出时限</td>
                          <?php if($output['order']['is_xianche']):?>
                            <td><?php echo date('Y-m-d',strtotime($output['info']['car_astrict']) - 8*26*3600);?> 24:00:00</td>
                          <?php else:?>
                          <td><?php echo date('Y-m-d',strtotime($output['info']['car_astrict']) - 7*26*3600);?> 24:00:00</td>
                        <?php endif;?>
                        </tr>
                        <tr>
                          <td>交车时限</td>
                          <td><?php echo date('Y-m-d',strtotime($output['info']['car_astrict']));?> 24:00:00</td>
                        </tr>
                      <?php endif;?>
                        <tr>
                          <td>向客户当场移交的文件资料</td>
                          <td><?php echo (!empty($output['suiche']['files']))?implode(',',$output['suiche']['files']) :'';?></td>
                        </tr>
                        <tr>
                          <td>向客户当场移交的随车工具</td>
                          <td><?php echo (!empty($output['suiche']['tools']))?implode(',',$output['suiche']['tools']) :'';?></td>
                        </tr>
                      </table>
                    </div>
                    <?php elseif($tab==4) :?>
                     <div class="clear"></div>
                    <div class="box-border fs14 detail-wrapper">
                       <p><span class="tip-title">评价</span></p>
                       <div class="ml100">
                          <table class="fs14">
                              <tr>
                                  <td valign="middle">
                                      华车服务：
                                  </td>
                                  <td>
                                      <div class="form-item" id="cs">
                                        <div class="formItemDiff formItemDiffFirst psr"></div>
                                        <div class="formItemDiff"></div>
                                        <div class="formItemDiff"></div>
                                        <div class="formItemDiff"></div>
                                        <div class="formItemDiff"></div>
                                      </div>
                                  </td>
                              </tr>
                              <tr>
                                  <td valign="middle">
                                      <div class="m-t-10"></div>
                                      售方服务：
                                  </td>
                                  <td>
                                      <div class="m-t-10"></div>
                                      <div class="form-item" id="hs">
                                        <div class="formItemDiff formItemDiffFirst psr"></div>
                                        <div class="formItemDiff"></div>
                                        <div class="formItemDiff"></div>
                                        <div class="formItemDiff"></div>
                                        <div class="formItemDiff"></div>
                                      </div>
                                  </td>
                              </tr>
                              <tr>
                                  <td valign="middle">
                                      <div class="m-t-10"></div>
                                      评价内容：
                                  </td>
                                  <td>
                                      <div class="m-t-10"></div>
                                      <p class="nomargin"><?=$output['info']['evaluate']?></p>
                                  </td>
                              </tr>

                          </table>



                       </div>
                       <div class="m-t-10"></div>




                       <p><span class="tip-title">发票</span></p>
                       <p class="ml100">已开票 </p>
                       <div class="m-t-10"></div>
                    </div>
                    <?php elseif($tab==5) :?>
                    	5
                    <?php elseif($tab==6) :?>
                     <div class="box-border fs14 detail-wrapper">
                      <div class="m-t-10" style="text-align: center;"></div>
                      <table class="tbl tbl-gray tac wp50 mauto" id="one" style="margin: 0 auto;display: table;">
                         <?php foreach ($output['logs'] as $value):?>
                          <tr>
                              <td class="prev-title"><?=$value['msg']?></td>
                              <td class="fs14"><?=$value['created_at']?></td>
                          </tr>
                        <?php endforeach;?>
                     </table>
                     <div class="m-t-10"></div>

                    </div>
                    <?php else :?>
                    	<div>
                   <?php if(!empty($output['files'])):?>
                   <p style="margin: 50px 0 30px 10px;text-align: left;" class="small_title"><span>特殊文件：</span></p>
                   <table width="900px" border="1" id="one">
                     <tr>
                       <td>特需内容</td>
                       <td colspan="3"><?=$output['info']['file_comment']?></td>
                     </tr>
                     <tr>
                       <td>反馈内容</td>
                       <td>
                       <?php $coun = count($output);?>
                         <?php foreach($output['files'] as $key=>$file):?>
                          <?=$file['title']?>
                          <?php echo ($file['ok'] == "Y")? '可以办理': '恕无法办理';?>
                          <php echo ($coun == $key) ? '':','?>
                          </br>
                         <?php endforeach;?>
                       </td>
                       <td>
                         办理费用: 人民币 <?php echo array_sum(array_column($output['files'],'fee'));?>元
                       </td>
                     </tr>
                     <tr>
                     <td>
                       确认结果:
                     </td>
                     <td colspan="2">
                       <?php echo ($output['info']['new_file_comment']) ? '办理': '不办理';?>
                     </td>
                     </tr>
                   </table>
                 <?php endif;?>

                 <?php if($output['order']['order_state'] >= 402 && $output['order']['order_status'] > 4):?>
                  <p style="margin: 50px 0 30px 10px;text-align: left;" class="small_title"><span>预约交车：</span></p>
                   <table width="900px" border="1" id="one">
                   <?php $day = ['全天','上午','下午'];?>
                     <tr>
                       <td width="215px">售方邀请内容</td>
                       <td id="comment"></td>
                     </tr>
                     <?php if(intval($output['info']['system_data'])):?>
                     <tr>
                       <td>售方反馈内容</td>
                       <td>希望 <?=$output['info']['seller_data']?> <?=$day[$output['info']['seller_day']]?></td>
                     </tr>
                     <tr>
                       <td>售方回复反馈</td>
                       <td><?php echo $output['info']['system_data'].$day[$output['info']['system_day']]?>
                       <?php echo (intval($output['info']['system_out_price'])) ?'超期费 ￥'.$output['info']['system_out_price'] : '' ;?></td>
                     </tr>
                     <tr>
                       <td>客户确认结果</td>
                       <td>同意(<?php echo $output['info']['system_data'].$day[$output['info']['system_day']]?>
                       <?php echo (intval($output['info']['system_out_price'])) ?'超期费 ￥'.$output['info']['system_out_price'] : '' ;?>)</td>
                     </tr>
                   <?php else:?>
                   <?php if(intval($output['info']['seller_data'])):?>
                    <tr>
                      <td>客户反馈内容</td>
                      <td>希望<?php echo $output['info']['member_data'].$day[$output['info']['member_day']];?></td>
                    </tr>
                    <tr>
                      <td>售方回复反馈</td>
                      <td><?php echo $output['info']['seller_data'].$day[$output['info']['seller_day']]?>
                       <?php echo (intval($output['info']['out_price'])) ?'超期费 ￥'.$output['info']['out_price'] : '' ;?></td>
                    </tr>
                    <tr>
                      <td>客户确认结果</td>
                      <td>同意(<?php echo $output['info']['seller_data'].$day[$output['info']['seller_day']]?>
                       <?php echo (intval($output['info']['out_price'])) ?'超期费 ￥'.$output['info']['out_price'] : '' ;?>)</td>
                    </tr>
                   <?php elseif($output['info']['is_feeback']):?>
                   <tr>
                     <td>售方反馈内容</td>
                     <td>希望<?php echo $output['info']['member_data'].$day[$output['info']['member_day']];?></td>
                   </tr>
                 <?php else:?>
                  <tr>
                    <td>客户反馈内容</td>
                    <td>希望（<?php echo $output['info']['member_data'].$day[$output['info']['member_day']];?>）</td>
                  </tr>
                  <tr>
                    <td>售方反馈内容</td>
                    <td>同意(<?php echo $output['info']['member_data'].$day[$output['info']['member_day']];?>)</td>
                  </tr>
                <?php endif;?>
                   <?php endif;?>
                   <tr>
                      <td colspan="5" style="text-align: right;"></td>
                   </tr>
                   </table>
            <?php endif;?>

                   <p style="margin: 50px 0 30px 10px;text-align: left;" class="small_title"><span>售方修改：</span></p>
                   <table width="900px" border="1" class="two">
                     <tr>
                       <td>名称</td>
                       <td>型号/说明</td>
                       <td>厂商指导价</td>
                       <td>数量</td>
                       <td>附加价值</td>
                     </tr>
                     <?php foreach($output['edit_info']['xzj'] as $xzj):?>
                      <?php if($xzj['num'] != 0):?>
                     <tr>
                       <td><?=$xzj['xzj_title']?></td>
                       <td><?=$xzj['xzj_model']?></td>
                       <td>￥<?=number_format($xzj['xzj_guide_price'],2)?></td>
                       <td><?=$xzj['num']?></td>
                       <td data-price=<?=$xzj['xzj_guide_price']*$xzj['num']?>>￥<?=(number_format($xzj['xzj_guide_price']*$xzj['num'],2))?></td>
                     </tr>
                   <?php endif;?>
                   <?php endforeach;?>
                   <tr>
                   </tr>
                   </table>

                 <?php if(count($output['consults'])): ?>
                    <p style="margin: 50px 0 30px 10px;text-align: left;" class="small_title"><span>精品协商：</span></p>
                   <table width="900px" border="1" class="two">
                     <tr>
                       <td>客户发起时间</td>
                       <td>品牌</td>
                       <td>名称</td>
                       <td>型号/说明</td>
                       <td>厂商编号</td>
                       <td>含安装费折后总价</td>
                       <td>希望件数减少为</td>
                       <td>协商结果</td>
                       <td>客户确认时间</td>
                     </tr>
                     <?php foreach($output['consults'] as $consult):?>
                     <tr>
                       <td><?=$consult['created_at']?></td>
                       <td><?php echo ($consult['xzj_yc']) ? '原厂' : $consult['xzj_brand'];?></td>
                       <td><?=$consult['xzj_title']?></td>
                       <td><?=$consult['xzj_model']?></td>
                       <td><?=$consult['cs_serial']?></td>
                       <?php if($consult['xzj_yc']):?>
                       <td><?php echo ($consult['xzj_guide_price'] * $output['order']['bj_xzj_zhekou']/100 +$consult['fee']);?></td>
                     <?php else:?>
                      <td><?=$consult['xzj_guide_price']?></td>
                     <?php endif;?>
                       <td><?=$consult['edit_num']?></td>
                       <td><?php if($consult['is_install'] == 1):?>
                        同意
                      <?php elseif($consult['is_install'] == 2):?>
                        不同意
                        <?php else:?>
                          待反馈
                        <?php endif;?></td>
                       <td><?=$consult['updated_at']?></td>
                     </tr>
                   <?php endforeach;?>
                   <tr>

                   </tr>
                   </table>
                 <?php endif;?>





                      </div>

                    <?php endif;?>
                    <div class="m-t-10"></div>

</body>
</html>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script>
	           <?php if(!is_null($output['info']['buy_id'])):?>
             $("#hs .formItemDiff").slice(0, <?=$output['info']['seller_service']?>).addClass('sele')
             $("#cs .formItemDiff").slice(0, <?=$output['info']['hwache_service']?>).addClass('sele')
             <?php endif;?>

            try{
               $("tbody tr").hover(
                  function(){
                      $(this).css({'background-clor':rgb(188,188,188)} );
                  },
                  function(){
                      $(this).css({'background-clor':rgb(188,188,188)} );
                  }
                )

            }
           catch(error){
           }

         //货币格式
         Number.prototype.formatMoney = function(places, symbol, thousand, decimal) {
        places = !isNaN(places = Math.abs(places)) ? places : 2;
        symbol = symbol !== undefined ? symbol : "\uffe5";
        thousand = thousand || ",";
        decimal = decimal || ".";
        var number = this,
            negative = number < 0 ? "-" : "",
            i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
            j = (j = i.length) > 3 ? j % 3 : 0;
        return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
          }


          $(".two").each(function(index,table){
              var _sum = 0.00
              $(table).find("tr").slice(1, $(table).find("tr").length - 1).each(function(){
                var _td = $(this).find("td").last().attr("data-price")
                var _price = parseFloat(_td)
                if (isNaN(_price) ) _price = parseFloat($(this).find("td").eq(1).text())
                _sum+=_price
              })
              $(table).find("tr").last().find("td").text("合计:"+_sum.formatMoney())
          })

          var _json = <?=json_encode(unserialize($output['jiaoche']['jiaoche_times']))?>


          var _strarray = []
          _json.forEach(function(item,index){
            var _str =
                item.year + "-" +
                item.month + "-" +
                item.day + " "+
                (
                  item.select == 1
                  ? "上午/下午" :
                  (item.select == 2 ? "上午" : "下午")
                )
            _strarray.push(_str)
          })
          $("#comment").html(_strarray.join(","))


</script>
