<?php defined('InHG') or exit('Access Invalid!');?>
    <div class="page finance">
        <div class="fixed-bar">
            <div class="item-title">
                <h3>客户财务</h3>
                <ul class="tab-base">
                    <li><a href="index.php?act=finance&op=index"><span>客户财务</span></a></li>
                    <li><a href="index.php?act=finance&op=recharge_index" ><span>转入-银行转账</span></a>▷</li>
                    <li><a href="javascript:void(0);" class="current"><span><?php echo show_recharge_status($output['recharge']['status']) ;?></span></a></li>
                </ul>
            </div>
        </div>
        <div class="fixed-empty"></div>
        <div class="info">
            <div class="span4">
                <span class="label">转入编号：</span>
                <span class="val"><?php echo $output['recharge']['ur_id'] ;?></span>
            </div>
            <div class="span4">
                <span class="label">提交时间：</span>
                <span class="val"><?php echo $output['recharge']['created_at'] ;?></span>
            </div>
            <div class="span4">
                <span class="label">状态：</span>
                <span class="val"><?php echo show_recharge_status($output['recharge']['status']) ;?>
                     </span>
            </div>
        </div>
        <div class="info">
            <div class="span4">
                <span class="label">客户会员号：</span>
                <span class="val"><?php echo $output['recharge']['user_id'] ;?></span>
            </div>
            <div class="span4">
                <span class="label">客户姓名（称呼）：</span>
                <span class="val"><?php echo $output['recharge']['user']['last_name'].$output['recharge']['user']['first_name']."(".show_sex($output['recharge']['user']['sex']).")" ;?></span>
            </div>
            <div class="span4">
                <span class="label">客户手机号：</span>
                <span class="val"><?php echo $output['recharge']['user']['phone'] ;?></span>
            </div>

        </div>
        <div class="info">
            <div class="span4">
                <span class="label">提交金额：</span>
                <span class="val"><?php echo '￥'.$output['recharge']['money'] ;?></span>
            </div>
            <div class="span4">
                <span class="label">提交凭证：</span>
                <span class="val">
                    <img src="<?php echo RESOURCE_SITE_URL.$output['voucher'];?>" alt=""></span>
            </div>
            <div class="span4">
                <span class="label">提交用途：</span>
                <span class="val"><?php echo show_recharge_use_type($output['recharge']['use_type']);
                    if($output['recharge']['use_type']>0) echo '(订单号:'.$output['recharge']['order']['id'].')'
                    ?></span>
            </div>
        </div>
        <div class="info <?php if($output['recharge']['status'] == 0) echo 'highlight'?>">
            <div class="span4 ">
                <span class="label">汇款银行：</span>
                <span class="val"><?php echo $output['recharge']['bank_name'] ;?></span>
            </div>
            <div class="span4">
                <span class="label">汇款人账号：</span>
                <span class="val"><?php echo format_bank_code($output['recharge']['bank_account']);?></span>
            </div>
            <div class="span4">
                <span class="label">汇款人户名：</span>
                <span class="val"><?php echo $output['recharge']['user_bank_name'];?></span>
            </div>
        </div>
        <div class="clear"></div>
        <div class="big_title">
            入账
        </div>
        <div class="clear"></div>

        <?php if( $output['recharge']['status'] <2){ ?>
            <form method="get"  id="ajax_form">
                <input type="hidden" name="act" value="finance">
                <input type="hidden" name="op" value="recharge_detail">
                <input type="hidden" name="ur_id" value="<?php echo $output['recharge']['ur_id']?>">
                <input type="hidden" name="form_submit" value="ok">
                <input type="hidden" name="in_ajax" value="1">

                <div class="info">
                    <input type="hidden" id="order_money" value="<?php
                    if($output['recharge']['use_type'] ==1){
                        echo $output['recharge']['order']['sponsion_price'];
                    }else if(['use_type'] ==2){
                        echo  $output['recharge']['order']['earnest_price'];
                    }else{
                        echo "0";
                    }
                    ?>">
                    <div class="span8">
                        <input type='checkbox' name="recharge_operation_yes" id="recharge_operation_yes" value="1" />已入账
                        <?php if($output['recharge']['use_type']>0) { ?>
                            <div class="info" style="font-size:14px;color: #0f0f0f; margin-top: 20px;">
                                <?php if($output['recharge']['use_type'] ==1){
                                    echo  '当前订单买车担保金未入账金额: ￥'.$output['recharge']['order']['sponsion_price'];
                                }else{
                                    echo  '当前订单买车诚意金未入账金额: ￥'.$output['recharge']['order']['earnest_price'];
                                }?>
                            </div>
                            <div class="info">
                                <div class="span12">
                                    <span class="label span3">银行到账金额：</span>
                                    <span class="val">
                                       ￥ <input type="text" class="wt200" name="recharge_money" id="recharge_money" value=" <?php
                                        if($output['recharge']['status'] ==1){
                                            echo $output['recharge']['recharge_money'];
                                        }else{
                                            echo $output['recharge']['money'] ;
                                        }
                                        ?>">
                                    </span>
                                    <span id="recharge_money_upper"></span>
                                </div>
                                <div class="span12">
                                    <span class="label span3">订单入账金额：</span>
                                    <span class="val">￥ <input type="text"  class="disabled wt200"  id="transfer_to_order" name="transfer_to_order" value="0"></span>
                                    <span id="transfer_to_order_upper"></span>
                                </div>
                                <div class="span12">
                                    <span class="label span3">转入客户可用余额（充值）：</span>
                                    <span class="val">￥ <input type="text" class="disabled wt200" id="transfer_to_account" name="transfer_to_account" value="0"></span>
                                    <span id="transfer_to_account_upper"></span>
                                </div>
                                <div class="span12">
                                    <span class="label span3">银行到账日期：</span>
                                    <span class="val tb-type1" >&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" class="text  date wt150" id="recharge_confirm_at" name="recharge_confirm_at" value=" <?php echo get_now(); ?>"></span>
                                </div>
                            </div>
                        <?php }else{ ?>
                            <div class="info">
                                <div class="span12">
                                    <span class="label span3">银行到账金额：</span>
                                    <span class="val">
                                   ￥ <input type="text" id="recharge_money"  class=" wt200"  name="recharge_money" value="<?php
                                        if($output['recharge']['status'] ==1){
                                            echo $output['recharge']['recharge_money'];
                                        }else{
                                            echo $output['recharge']['money'] ;
                                        }
                                        ?>">
                                    </span>
                                    <span id="recharge_money_upper"></span>
                                </div>
                                <div class="span12 ">
                                    <span class="label span3">转入客户可用余额（充值）：</span>
                                    <span class="val">
                                        ￥ <input type="text" id="transfer_to_account"  class="disabled wt200"  name="transfer_to_account" value=" <?php echo $output['recharge']['money'] ;?>">
                                    </span>
                                    <span id="transfer_to_account_upper"></span>
                                </div>
                                <div class="span12 ">
                                    <span class="label span3">银行到账日期：</span>
                                    <span class="val tb-type1">&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" class="text date wt150" id="recharge_confirm_at" name="recharge_confirm_at" value=" <?php echo get_now2(); ?>"></span>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="span4 pull-right">
                        <input type='checkbox' name="recharge_operation_no" id="recharge_operation_no" value="1" />无此款项
                        <div class="info">
                            <?php if($output['recharge']['use_type']>0){?>
                        <span class="span12" style="padding-left: 20px;">
                            订单入账金额：￥0
                        </span>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </form>

        <?php }elseif($output['recharge']['status'] <4){?>
            <div class="info">
                <div class="span4 ">
                    <span class="label">转入方银行：</span>
                    <span class="val"><?php echo $output['recharge']['bank_name'] ;?></span>
                </div>
                <div class="span4">
                    <span class="label">转入方账号：</span>
                    <span class="val"><?php echo format_bank_code($output['recharge']['bank_account']);?></span>
                </div>
                <div class="span4">
                    <span class="label">转入方户名：</span>
                    <span class="val"><?php echo $output['recharge']['user_bank_name'];?></span>
                </div>
            </div>
            <div class="info">
                <div class="span4">
                    <span class="label">银行到账金额：</span>
                    <span class="val">  <?php echo '￥'.$output['recharge']['recharge_money'] ;?></span>
                </div>
                <div class="span4">
                    <span class="label">入账金额：</span> <!--转入订单金额或者充值金额-->
                    <?php if($output['recharge']['use_type']>0){?>
                        <span class="val">  <?php echo '￥'.$output['recharge']['transfer_to_order'] ;?></span>
                    <?php }else{ ?>
                        <span class="val">  <?php echo '￥'.$output['recharge']['transfer_to_account'] ;?></span>
                    <?php }?>
                </div>
                <div class="span4">
                    <span class="label">转入用途：</span>
                    <span class="val"><?php echo show_recharge_use_type($output['recharge']['use_type']);
                        if($output['recharge']['use_type']>0) echo '(订单号:'.$output['recharge']['order']['id'].')'
                        ?></span>
                </div>
            </div>
            <div class="info">
                <div class="span4">
                    <span class="label  ">银行到账日期：  <?php echo $output['recharge']['recharge_confirm_at']?></span>
                </div>
                <?php if($output['recharge']['status'] ==3){ ?>

                    <div class="span4">
                        <span class="label  ">收款银行：  <?php echo $output['recharge']['transfer_bank_name']?></span>
                    </div>
                    <div class="span4">
                        <span class="label  ">银行凭证号：  <?php echo $output['recharge']['trade_no']?></span>
                    </div>
                    <div class="span4">
                        <span class="label  ">记账凭证号：  <?php echo $output['recharge']['accounting_voucher']?></span>
                    </div>
                <?php }?>

                <?php if(!empty($output['log_list']) && is_array($output['log_list'])){ ?>
                    <?php foreach($output['log_list'] as $kk => $vv){ ?>
                        <div class="span12">
                            <span class="label span2">入账<?php echo show_operation_step($vv['step'])?>人</span>
                            <span class="val tb-type1 span3" ><?php echo $vv['user_name']?></span>
                            <span class="label span2">入账<?php echo show_operation_step($vv['step'])?>时间：</span>
                            <span class="val tb-type1 span3" ><?php echo $vv['created_at']?></span>
                        </div>
                    <?php } ?>
                <?php } ?>

            </div>
        <?php } else{ ?>
            <div class="info">
                <div class="span4">
                    <span class="label">银行到账金额：</span>
                    <span class="val">  <?php echo '￥'.$output['recharge']['recharge_money'] ;?></span>
                </div>


            </div>
            <div class="info">
                <?php if(!empty($output['log_list']) && is_array($output['log_list'])){ ?>
                    <?php foreach($output['log_list'] as $kk => $vv){ ?>
                        <div class="span12">
                            <span class="label span2">入账<?php echo show_operation_step($vv['step'])?>人</span>
                            <span class="val tb-type1 span3" ><?php echo $vv['user_name']?></span>
                            <span class="label span2">入账<?php echo show_operation_step($vv['step'])?>时间：</span>
                            <span class="val tb-type1 span3" ><?php echo $vv['created_at']?></span>
                        </div>
                    <?php } ?>
                <?php } ?>

            </div>
        <?php } ?>

        <div class="clear"></div>
        <div class="big_title">
            备注
        </div>
        <div class="clear"></div>
        <div class="info">
            <table class="table tb-type2">
                <thead>
                <tr class="thead blue">
                    <th >编号</th>
                    <th >内容</th>
                    <th >证据</th>
                    <th >备注人</th>
                    <th >备注时间</th>
                    <th >操作</th>
                </tr>
                </thead>
                <tbody>
                <?php if(!empty($output['comment_list']) && is_array($output['comment_list'])){ $i=0; ?>
                    <?php foreach($output['comment_list'] as $k => $v){ ?>
                        <tr class="hover">
                            <td><?php $i++; echo $i?></td>
                            <td><?php echo $v['remark'] ?></td>
                            <td>
                                 <?php foreach($v['file_list'] as $kk => $vv){ ?>
                                      <a href="<?php echo UPLOAD_SITE_URL.$vv['file_path']?>"><?php echo $vv['file_name'] ?></a><br/>
                                  <?php }?>
                            </td>
                            <td><?php echo $v['user_name']?></td>
                            <td><?php echo $v['created_at']?></td>
                            <td><a href="javascript:void(0);" onclick="show_del_dialog(<?php echo $v['id']; ?>)">删除</a></td>
                        </tr>
                    <?php } ?>
                <?php }else { ?>
                    <tr class="no_data" id="no_data" data-has="1">
                        <td colspan="8"><?php echo $lang['nc_no_record'];?></td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                <tr class="tfoot">
                    <td colspan="20">
                        <a href="javascript:void(0);" onclick="show_add_dialog(<?php echo $_GET['ur_id'];?>);" class="button pull-left">添加</a>
                    </td>
                </tr>
              <!--  <?php /*if(!empty($output['comment_list']) && is_array($output['comment_list'])){ */?>
                    <tr class="tfoot">
                        <td colspan="20"><div class="pagination"> <?php /*echo $output['page'];*/?> </div></td>
                    </tr>
                --><?php /*} */?>
                </tfoot>
            </table>
        </div>

        <?php if($output['recharge']['status'] ==2 ){?>
        <div class="big_title">
            补充入账信息
        </div>
        <form method="get"  id="ajax_form">
            <input type="hidden" name="act" value="finance">
            <input type="hidden" name="op" value="recharge_detail">
            <input type="hidden" name="ur_id" value="<?php echo $output['recharge']['ur_id']?>">
            <input type="hidden" name="recharge_operation_yes" value="1">
            <input type="hidden" name="form_submit" value="ok">
            <input type="hidden" name="in_ajax" value="1">
            <input type="hidden" id="save_input" name="save" value="0">
            <div class="info">
                <div class="span12">
                    <span class="label span3">收款银行</span>
                    <span class="val">
                          <select name="transfer_bank_name" id="transfer_bank_name" class="wt200">
                                 <option value="泰隆银行"
                                     <?php if($output['recharge']['transfer_bank_name'] == '泰隆银行') echo 'selected'?>
                                 >泰隆银行</option>
                                 <option value="招商银行"
                                     <?php if($output['recharge']['transfer_bank_name'] == '招商银行') echo 'selected'?>
                                 >招商银行</option>
                        </select>
                    </span>
                </div>
                <div class="span12">
                    <span class="label span3">银行凭证号：</span>
                    <span class="val"> <input type="text"  class=" wt200"  id="trade_no_input"   name="trade_no" value="<?php echo $output['recharge']['trade_no']?>"></span>
                </div>
                <div class="span12">
                    <span class="label span3">记账凭证号：</span>
                    <span class="val"><input type="text" class="  wt200"   id="accounting_voucher_input"  name="accounting_voucher" value="<?php echo $output['recharge']['accounting_voucher']?>"></span>
                </div>
            </div>
        </form>
        <?php } ?>

        <?php if($output['recharge']['status'] == 1 ){ ?>
            <?php if(!empty($output['log_list']) && is_array($output['log_list'])){ ?>
                <?php foreach($output['log_list'] as $kk => $vv){ ?>
                    <div class="span12">
                        <span class="label span2">入账<?php echo show_operation_step($vv['step'])?>人</span>
                        <span class="val tb-type1 span3" ><?php echo $vv['user_name']?></span>
                        <span class="label span2">入账<?php echo show_operation_step($vv['step'])?>时间：</span>
                        <span class="val tb-type1 span3" ><?php echo $vv['created_at']?></span>
                    </div>
                <?php } ?>
            <?php } ?>
        <?php }?>

        <div class="info footer">
            <?php if( $output['recharge']['status'] ==2){?>
                <a href="javascript:void(0);" class="button" onclick="save_form()">保存</a>
            <?php }?>
            <?php if( $output['recharge']['status'] <3){?>
            <a href="javascript:void(0);" class="button confirm" onclick="confirm_form()"><?php echo show_recharge_confirm_name($output['recharge']['status']);?></a>
            <?php }?>
            <a href="javascript:history.go(-1);" class="button">返回</a>
        </div>
    </div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.serializejson.min.js" charset="utf-8"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/layui/layui.js" charset="utf-8"></script>

<script type="text/javascript">

layui.use('laydate', function(){
  var laydate = layui.laydate;
  
  var start = {
    istime: true,
    format: 'YYYY-MM-DD hh:mm:ss'//日期格式
  };
  
  
  document.getElementById('recharge_confirm_at').onclick = function(){
    start.elem = this;
    laydate(start);
  }
});

    $(function(){
        // $('#recharge_confirm_at').datepicker({dateFormat: 'yy-mm-dd',timeFormat: 'hh:mm:00'});
        // $('#recharge_confirm_at').datepicker({dateFormat: 'yy-mm-dd',timeFormat: 'hh:mm:00'});
        //计算订单余额,z转入用户余额
        calc_transfer_to_order();
        disable_ruzhang();
        $('#recharge_operation_yes').change(function(){
            let val = $(this).prop('checked');
            console.log(val);
            if(val)
            {
                $('#recharge_operation_no').prop('checked',false);
                enable_ruzhang();
            }else
            {
                disable_ruzhang();
            }
        });
        $('#recharge_operation_no').change(function(){
            let val = $(this).prop('checked');
            console.log(val);
            if(val)
            {
                $('#recharge_operation_yes').prop('checked',false);
                disable_ruzhang();
            }
        });

        //银行到账金额发生变化
        $("#recharge_money").blur(function(){
            calc_transfer_to_order();
        })
    });

    //已入账表单未勾选
    function disable_ruzhang()
    {
        $('#transfer_to_account').attr('readonly','readonly');
        $('#recharge_money').attr('readonly','readonly');
        $('#transfer_to_order').attr('readonly','readonly');
        $('#recharge_confirm_at').attr('readonly','readonly');
    }

    //已入账勾选
    function enable_ruzhang() {
//        $('#transfer_to_account').removeAttr('disabled');
        $('#recharge_money').removeAttr('readonly');
//        $('#transfer_to_order').removeAttr('disabled');
        $('#recharge_confirm_at').removeAttr('readonly');
    }

    //计算订单入账金额
    function calc_transfer_to_order()
    {
        let order_money    = $('#order_money').val();
        let recharge_money = $('#recharge_money').val();
        order_money    = parseFloat(order_money);
        recharge_money = parseFloat(recharge_money);
        up_recharge_money = digitUppercase(recharge_money);
        //大写
        $("#recharge_money_upper").text(up_recharge_money);
        $("#transfer_to_order_upper").text( digitUppercase(order_money));


        if(order_money >0 )  //=0 说明是充值，不用计算订单
        {
            if(recharge_money >= order_money){
                $("#transfer_to_order").val(order_money);
                let account = recharge_money-order_money;
                $("#transfer_to_account").val(account);
                $("#transfer_to_account_upper").text( digitUppercase(account));
             }else{
                $("#transfer_to_order").val(recharge_money);
                let account =0;
                $("#transfer_to_account").val(account);
                $("#transfer_to_account_upper").text( digitUppercase(account));
             }

        }else{
                $("#transfer_to_account").val(recharge_money);
                $("#transfer_to_account_upper").text( digitUppercase(recharge_money));

        }
    }

    //添加备注弹框
    function show_add_dialog(ur_id){
        parent.layer.open({
            type: 2,
            title:"添加备注",
            skin: 'layui-layer-rim', //加上边框
            area: ['500px', '400px'], //宽高
            content: '/index.php?act=finance&op=ajax_add_comment&ur_id='+ur_id
        });
    }

    //添加备注弹框
    function show_del_dialog(comment_id){
        parent.layer.confirm('您确认删除该条备注消息么？', {
            btn: ['确认','取消'] //按钮
        }, function(){
            post('index.php?act=finance&op=ajax_del_comment&current_status=<?=$output['recharge']['status'];?>&id='+comment_id).then(function(res){
                if(res.data.code == 200)
                {
                    alert(res.data.msg);
                    refresh_workspace();
                    closeLayer();
                }else
                {
                    parent.layer.msg(res.data.msg, {
                        time: 20000, //20s后自动关闭
                        btn: ['明白了']
                    });
                }
            }).catch(function(err){
                console.log(err);
            });

        }, function(){
            closeLayer();
        });
    }
    /**
     * 保存按钮
     */
    function save_form()
    {
        if($('#accounting_voucher_input').val()=='' && $('#trade_no_input').val()==''){
            alert("记账凭证号和银行凭证号请至少填写一样");
            return false;
        }

        $('#save_input').val(1);
        ajax_form();
    }

    /**
     * 确认按钮
     */
    function confirm_form(){
        let status = <?=$output['recharge']['status'];?>;
        if(!valid_form(status))
            return false;

        parent.layer.confirm('确定提交么？', {
            btn: ['确认','取消'] //按钮
        }, function(){
            submit_form();
            closeLayer();
        }, function(){
            closeLayer();
        });
    }
    //提交表单
    function submit_form()
    {
        $('#save_input').val(0);
        ajax_form();
    }

    //表单验证
    function  valid_form(status)
    {
        if(status>1){
            if($('#accounting_voucher_input').val()==''){
                alert("请填写记账凭证号");
                return false;
            }

            if($('#trade_no_input').val()==''){
                alert("请填写银行凭证号");
                return false;
            }
        }else{
            //检查已入账和无此款项是否有选中
            if(!$('#recharge_operation_yes').attr('checked') && !$('#recharge_operation_no').attr('checked'))
            {
                alert('请先选择入账类型');
                return false;
            }
        }

        return true;

    }



    function ajax_form(){
        //已入账，或者无此款项，必须选一个
        let status  = <?php echo $output['recharge']['status']?>;
        if(status<2){
            if(!$("#recharge_operation_yes").prop('checked') && !$("#recharge_operation_no").prop('checked'))
            {
                alert("请完善入账选项");
                return false;
            }
        }

        let json= $('#ajax_form').serializeJSON();

        post('index.php?act=finance&op=recharge_detail', json).then(function(res){
            if(res.data.code == 200)
            {
                alert(res.data.msg);
                refresh_workspace();
                closeLayer();
            }else
            {
                alert(res.data.msg);
            }
        }).catch(function(err){
            console.log(err);
        });
    }



</script>
