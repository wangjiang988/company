<?php defined('InHG') or exit('Access Invalid!');?>
<div class="page">
<div class="fixed-bar">
    <div class="item-title">
      <h3>交车管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=jiaoche&op=index&d_type=undone" <?php if($_GET['d_type']=='undone'){echo "class=current";}?>><span>待审核</span></a></li>
        <li><a href="index.php?act=jiaoche&op=index&d_type=done" <?php if($_GET['d_type']=='done'){echo "class=current";}?>><span>已审核</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  
  <style>
  .feedback{width:850px;}
  .feedback td{height:30px;line-height:30px};
  </style>
  
  <?php 
      $userxzj = $output['xzj'];
      $zengpin = $output['zengpin'];
      $verify = $output['verify'];
      //print_r($verify);
      ?>
  <table  class='feedback'>
    <tbody>
    
      <tr class="space">
        <th colspan="4">客户反馈交车情况</th>
      </tr>
      <tr>
        <td width='200'>项目</td><td width='250'>约定</td><td width='200'>实际</td><td width='200'>备注</td>
      </tr>
      <tr>
          <td><p class="tac fs14">生产国别</p></td>
          <td><p class="tac fs14"></p></td>
          <td class="cell">
              <p class="tac fs14">
                  <input name="guobie" <?php if($verify['guobie']['accord']==1){echo ' checked ';}?> class="radio" type="radio" value="1"><span>相符</span>
                  <input name="guobie" <?php if($verify['guobie']['accord']==0){echo 'checked';}?>class="radio" type="radio" value="0"><span>不相符</span>
              </p>
          </td>
          <td width="400">
              
          </td>
      </tr> 
      <tr>
          <td><p class="tac fs14">基本配置</p></td>
          <td><p class="tac fs14"><a href="#" class="juhuang tdu">见附件一</a></p></td>
          <td class="cell">
              <p class="tac fs14">
                  <input name="peizhi" <?php if($verify['peizhi']['accord']==1){echo ' checked ';}?>  value="1" class="radio" type="radio"><span>相符</span>
                  <input name="peizhi" <?php if($verify['peizhi']['accord']==0){echo ' checked ';}?> class="radio" value="0" type="radio"><span>不相符</span>
              </p>
          </td>
          <td width="400">
              
          </td>
      </tr> 
      <tr>
          <td><p class="tac fs14">经销商名称</p></td>
          <td><p class="tac fs14"></p></td>
          <td class="cell">
              <p class="tac fs14">
                  <input name="jxs" value="1" <?php if($verify['jxs']['accord']==1){echo ' checked ';}?>  class="radio" type="radio"><span>相符</span>
                  <input name="jxs" class="radio" <?php if($verify['jxs']['accord']==0){echo ' checked ';}?>  value="0" type="radio"><span>不相符</span>
              </p>
          </td>
          <td width="400">
              
          </td>
      </tr> 
      <tr>
          <td><p class="tac fs14">交车地点</p></td>
          <td><p class="tac fs14"></p></td>
          <td class="cell">
              <p class="tac fs14">
                  <input name="address" <?php if($verify['address']['accord']==1){echo ' checked ';}?> class="radio" type="radio" value="1"><span>相符</span>
                  <input name="address" <?php if($verify['address']['accord']==0){echo ' checked ';}?>class="radio" type="radio" value="0"><span>不相符</span>
              </p>
          </td>
          <td width="400">
              
          </td>
      </tr> 
      <tr>
          <td><p class="tac fs14">交车时间</p></td>
          <td><p class="tac fs14"></p></td>
          <td class="cell">
              <p class="tac fs14">
                  <input name="jiaoche" <?php if($verify['jiaoche']['accord']==1){echo ' checked ';}?> class="radio" type="radio" value="1"><span>相符</span>
                  <input name="jiaoche" <?php if($verify['jiaoche']['accord']==0){echo ' checked ';}?>class="radio" type="radio" value="0"><span>不相符</span>
              </p>
          </td>
          <td width="400">
              
          </td>
      </tr> 
      <tr>
          <td><p class="tac fs14">裸车开票价格</p></td>
          <td><p class="tac fs14"></p></td>
          <td class="cell">
              <p class="tac fs14">
                  <input name="price" <?php if($verify['price']['accord']==1){echo ' checked ';}?> class="radio" type="radio" value="1"><span>相符</span>
                  <input name="price" <?php if($verify['price']['accord']==0){echo ' checked ';}?>class="radio" type="radio" value="0"><span>不相符</span>
              </p>
          </td>
          <td width="400">
              
          </td>
      </tr> 
      <tr>
          <td><p class="tac fs14">排放标准</p></td>
          <td><p class="tac fs14"></p></td>
          <td class="cell">
              <p class="tac fs14">
                  <input name="biaozhun" <?php if($verify['biaozhun']['accord']==1){echo ' checked ';}?> class="radio" type="radio" value="1"><span>相符</span>
                  <input name="biaozhun" <?php if($verify['biaozhun']['accord']==0){echo ' checked ';}?>class="radio" type="radio" value="0"><span>不相符</span>
              </p>
          </td>
          <td width="400">
              
          </td>
      </tr> 
      <tr>
          <td><p class="tac fs14">车身颜色</p></td>
          <td><p class="tac fs14"><a href="#" class="juhuang tdu">见附件一</a></p></td>
          <td class="cell">
              <p class="tac fs14">
                  <input name="csys" <?php if($verify['csys']['accord']==1){echo ' checked ';}?> class="radio" type="radio" value="1"><span>相符</span>
                  <input name="csys" <?php if($verify['csys']['accord']==0){echo ' checked ';}?>class="radio" type="radio" value="0"><span>不相符</span>
              </p>
          </td>
          <td width="400">
              
          </td>
      </tr> 
      <tr>
          <td><p class="tac fs14">内饰颜色</p></td>
          <td><p class="tac fs14"></p></td>
          <td class="cell">
              <p class="tac fs14">
                  <input name="nsys" <?php if($verify['nsys']['accord']==1){echo ' checked ';}?> class="radio" type="radio" value="1"><span>相符</span>
                  <input name="nsys" <?php if($verify['nsys']['accord']==0){echo ' checked ';}?>class="radio" type="radio" value="0"><span>不相符</span>
              </p>
          </td>
          <td width="400">
              
          </td>
      </tr> 
      <tr>
          <td><p class="tac fs14">行驶里程</p></td>
          <td><p class="tac fs14"></p></td>
          <td class="cell">
              <p class="tac fs14">
                  <input name="licheng" <?php if($verify['licheng']['accord']==1){echo ' checked ';}?> class="radio" type="radio" value="1"><span>相符</span>
                  <input name="licheng" <?php if($verify['licheng']['accord']==0){echo ' checked ';}?>class="radio" type="radio" value="0"><span>不相符</span>
              </p>
          </td>
          <td width="400">
              
          </td>
      </tr> 
      <tr>
          <td><p class="tac fs14">出厂年月</p></td>
          <td><p class="tac fs14"></p></td>
          <td class="cell">
              <p class="tac fs14">
                  <input name="chuchang" <?php if($verify['chuchang']['accord']==1){echo ' checked ';}?> class="radio" type="radio" value="1"><span>相符</span>
                  <input name="chuchang" <?php if($verify['chuchang']['accord']==0){echo ' checked ';}?>class="radio" type="radio" value="0"><span>不相符</span>
              </p>
          </td>
          <td width="400">
              
          </td>
      </tr> 
      
      
      
      <tr>
          <td><p class="tac fs14">已装原厂选装精品</p></td>
          <td style="padding:0;">
              <table class="tbl2" width="100%">
                  <tbody>
                  <?php foreach ($userxzj as $key => $value): ?>
					<tr>
					    <td class="bottomtborder">
					        <p class="tal fs14"><?=$value['xzj_name']?></p>
					    </td>
					</tr>
                  <?php endforeach ?>

                  </tbody>
              </table>
              
          </td>
          <td class="cell" style="padding:0;">
              <table class="tbl2" width="100%">
                  <tbody>
                  <?php foreach ($userxzj as $key => $value): ?>
					<tr>
					    <td class="bottomtborder">
					        <p class="tac fs14">
					            <input name="ycxzj[<?=$value['id']?>]" checked="" class="radio" value="1" type="radio"><span>相符</span>
					            <input name="ycxzj[<?=$value['id']?>]" value="0" class="radio" type="radio"><span>不相符</span>
					        </p>
					    </td>
					</tr>
                  <?php endforeach ?>


                  </tbody>
              </table>
              
          </td>
          <td width="400" <td style="padding:0;">
              <table class="tbl2" width="100%">
                  <tbody>
                  <?php foreach ($userxzj as $key => $value): ?>
					<tr>
					    <td class="bottomtborder">
					        
					    </td>
					</tr>
                  <?php endforeach ?>
 
                  </tbody>
              </table>
              
          </td>
      </tr> 
      <tr>
          <td><p class="tac fs14">免费礼品和服务</p></td>
          <td style="padding:0;">
              <table class="tbl2" width="100%">
                  <tbody>
                  <?php foreach ($zengpin as $key => $value): ?>
					<tr>
					    <td class="bottomtborder">
					        <p class="tal fs14"><?=$value['title']?></p>
					    </td>
					</tr>
                  <?php endforeach ?>

                  </tbody>
              </table>
              
          </td>
          <td class="cell" style="padding:0;">
              <table class="tbl2" width="100%">
                  <tbody>
                  <?php foreach ($zengpin as $key => $value): ?>
					<tr>
					    <td class="bottomtborder">
					        <p class="tac fs14">
					            <input name="zengpin[<?=$value['id']?>]" checked="" class="radio" type="radio"><span>相符</span>
					            <input name="zengpin[<?=$value['id']?>]" class="radio" type="radio"><span>不相符</span>
					        </p>
					    </td>
					</tr>
                  <?php endforeach ?>

                  </tbody>
              </table>
              
          </td>
          <td width="400" <td style="padding:0;">
              <table class="tbl2" width="100%">
                  <tbody>
                  <?php foreach ($zengpin as $key => $value): ?>
					<tr>
					    <td class="bottomtborder">
					        
					    </td>
					</tr>
                  <?php endforeach ?>

                  </tbody>
              </table>
              
          </td>
      </tr> 
      
      
      
      
      <!-- 已装原厂选装精品 -->
      
      <!-- 免费礼品和服务 -->
      
      
      <tr>
          <td><p class="tac fs14">选装精品</p></td>
          <td><p class="tac fs14"></p></td>
          <td class="cell">
              <p class="tac fs14">
                  <input name="xzj" <?php if($verify['xzj']['accord']==1){echo ' checked ';}?> class="radio" type="radio" value="1"><span>相符</span>
                  <input name="xzj" <?php if($verify['xzj']['accord']==0){echo ' checked ';}?>class="radio" type="radio" value="0"><span>不相符</span>
              </p>
          </td>
          <td width="400">
              
          </td>
      </tr> 
      <tr>
          <td><p class="tac fs14">车辆商业保险</p></td>
          <td><p class="tac fs14"><a href="#" class="juhuang tdu">见附件一</a></p></td>
          <td class="cell">
              <p class="tac fs14">
                  <input name="baoxian" <?php if($verify['baoxian']['accord']==1){echo ' checked ';}?> class="radio" type="radio" value="1"><span>相符</span>
                  <input name="baoxian" <?php if($verify['baoxian']['accord']==0){echo ' checked ';}?>class="radio" type="radio" value="0"><span>不相符</span>
              </p>
          </td>
          <td width="400">
              
          </td>
      </tr> 
      <tr>
          <td><p class="tac fs14">上牌服务</p></td>
          <td><p class="tac fs14"><a href="#" class="juhuang tdu">见附件一</a></p></td>
          <td class="cell">
              <p class="tac fs14">
                  <input name="shangpai" <?php if($verify['shangpai']['accord']==1){echo ' checked ';}?> value="1" class="radio" type="radio"><span>相符</span>
                  <input name="shangpai" <?php if($verify['shangpai']['accord']==0){echo ' checked ';}?>class="radio" type="radio" value="0"><span>不相符</span>
              </p>
          </td>
          <td width="400">
              
          </td>
      </tr> 
      <tr>
          <td><p class="tac fs14">上临时牌照</p></td>
          <td><p class="tac fs14"></p></td>
          <td class="cell">
              <p class="tac fs14">
                  <input name="linpai" <?php if($verify['linpai']['accord']==1){echo ' checked ';}?> class="radio" type="radio" value="1"><span>相符</span>
                  <input name="linpai" <?php if($verify['linpai']['accord']==0){echo ' checked ';}?>class="radio" type="radio" value="0"><span>不相符</span>
              </p>
          </td>
          <td width="400">
              
          </td>
      </tr> 
      <tr>
          <td><p class="tac fs14">其他杂费收取</p></td>
          <td><p class="tac fs14"></p></td>
          <td class="cell">
              <p class="tac fs14">
                  <input name="other" <?php if($verify['other']['accord']==1){echo ' checked ';}?> class="radio" type="radio" value="1"><span>相符</span>
                  <input name="other" <?php if($verify['other']['accord']==0){echo ' checked ';}?>value="0" class="radio" type="radio"><span>不相符</span>
              </p>
          </td>
          <td width="400">
              
          </td>
      </tr> 
      <tr>
          <td><p class="tac fs14">收到的文件资料</p></td>
          <td><p class="tac fs14"></p></td>
          <td class="cell">
              <p class="tac fs14">
                  <input name="wenjian" <?php if($verify['wenjian']['accord']==1){echo ' checked ';}?> class="radio" type="radio" value="1"><span>相符</span>
                  <input name="wenjian" <?php if($verify['wenjian']['accord']==0){echo ' checked ';}?>class="radio" value="0" type="radio"><span>不相符</span>
              </p>
          </td>
          <td width="400">
              
          </td>
      </tr> 
      <tr>
          <td><p class="tac fs14">收到的随车工具</p></td>
          <td><p class="tac fs14"></p></td>
          <td class="cell">
              <p class="tac fs14">
                  <input name="gongju" <?php if($verify['gongju']['accord']==1){echo ' checked ';}?> value="1" class="radio" type="radio"><span>相符</span>
                  <input name="gongju" <?php if($verify['gongju']['accord']==0){echo ' checked ';}?>value="0" class="radio" type="radio"><span>不相符</span>
              </p>
          </td>
          <td width="400">
             
          </td>
      </tr> 
      <tr>
          <td><p class="tac fs14">车辆回程方式</p></td>
          <td><p class="tac fs14"></p></td>
          <td class="cell">
              <p class="tac fs14">
                  <input name="huicheng" <?php if($verify['huicheng']['accord']==1){echo ' checked ';}?> value="1" class="radio" type="radio"><span>相符</span>
                  <input name="huicheng" <?php if($verify['huicheng']['accord']==0){echo ' checked ';}?>class="radio" value="0" type="radio"><span>不相符</span>
              </p>
          </td>
          <td width="400">
              
          </td>
      </tr> 
      <tr>
          <td><p class="tac fs14">补贴</p></td>
          <td><p class="tac fs14"></p></td>
          <td class="cell">
              <p class="tac fs14">
                  <input name="butie" <?php if($verify['butie']['accord']==1){echo ' checked ';}?> value="1" class="radio" type="radio"><span>相符</span>
                  <input name="butie" <?php if($verify['butie']['accord']==0){echo ' checked ';}?>class="radio" value="0" type="radio"><span>不相符</span>
              </p>
          </td>
          <td width="400">
              
          </td>
      </tr> 
      
  </table>
</div>