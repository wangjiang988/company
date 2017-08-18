<?php defined('InHG') or exit('Access Invalid!');?>
    <div class="page finance">
        <div class="fixed-bar">
            <div class="item-title">
                <h3>客户财务</h3>
                <ul class="tab-base">
                    <li><a href="index.php?act=finance&op=index"><span>客户财务</span></a></li>
                    <li><a href="index.php?act=finance&op=withdraw_index"><span>提现</span></a>▷</li>
                    <li><a href="javascript:void(0);" class="current"><span>
                                                            <?php if($_GET['recharge_method'] ==1 ) echo '线上支付';else echo "银行转账" ?>
                            </span></a></li>
                </ul>
            </div>
        </div>
        <div class="fixed-empty"></div>
        <div class="info">
            <div class="span4">
                <span class="label">提现编号：</span>
                <span class="val"><?php echo $output['withdraw']['uw_id'] ;?></span>
            </div>
            <div class="span4">
                <span class="label">申请时间：</span>
                <span class="val"><?php echo $output['withdraw']['created_at'] ;?></span>
            </div>
            <div class="span4">
                <span class="label">状态：</span>
                <span class="val"><?php echo show_withdraw_status($output['withdraw']['status']) ;?>
                     </span>
            </div>
        </div>
        <div class="info">
            <div class="span4">
                <span class="label">客户会员号：</span>
                <span class="val"><?php echo $output['withdraw']['user_id'] ;?></span>
            </div>
            <div class="span4">
                <span class="label">客户姓名（称呼）：</span>
                <span class="val"><?php echo $output['withdraw']['user']['last_name'].$output['withdraw']['user']['first_name']."(".show_sex($output['withdraw']['user']['sex']).")" ;?></span>
            </div>
            <div class="span4">
                <span class="label">客户手机号：</span>
                <span class="val"><?php echo $output['withdraw']['user']['phone'] ;?></span>
            </div>

        </div>
        <div class="info">
            <div class="span4">
                <span class="label">提现方式：</span>
                <span class="val"><?php
                    if($output['withdraw']['ur_recharge_type'] !=2){
                        echo "线上支付";
                    }else{
                        echo "银行转账";
                    }
                    ?>
                </span>
            </div>
            <?php if($output['withdraw']['ur_recharge_type'] == 2){?>
                <div class="span4">
                    <span class="label">提现金额：</span>
                    <span class="val"> ￥ <?php echo $output['withdraw']['money'];?></span>
                </div>
            <?php }?>
            <div class="span4">
                <span class="label">提现批准：</span>
                <span class="val"><?= $output['step_log_list']['0']['user_name']."(".$output['step_log_list']['0']['created_at'].")"?> </span>
            </div>
        </div>

        <div class="clear"></div>
        <div class="big_title">
            提现路线金额
        </div>
        <div class="clear"></div>
        <?php if($output['withdraw']['uwl_line_type'] ==2){?>
        <div class="info">
            <div class="span12">
                    <div class="info">
                        <div class="span4">
                            <span class="label ">提现付款金额：</span>
                            <span class="val ">
                                ￥  <?php echo $output['withdraw']['money']-$output['withdraw']['fee'] ;?>
                             </span>
                        </div>
                        <div class="span8">
                            <span class="label ">收款路线&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;：</span>
                            <span class="val ">
                                  <?php
                                   if($output['withdraw']['uwl_line_type'] == 2 ){
                                       echo $output['withdraw']['bank']['province'].$output['withdraw']['bank']['city'].$output['withdraw']['bank']['bank_address'].'('.$output['withdraw']['bank']['bank_name'].")";
                                   }else{
                                       echo $output['withdraw']['uwl_account_name'];
                                   }
                                  ?>
                            </span>
                        </div>
                        <div class="span4">
                            <span class="label">收款方账号&nbsp;&nbsp;：</span>
                            <span class="val ">
                                   <?php echo $output['withdraw']['uwl_account_code'] ;?>
                            </span>
                        </div>
                        <?php if($output['withdraw']['uwl_line_type'] == 2 ){?>
                        <div class="span4">
                            <span class="label">收款方户名&nbsp;&nbsp;：</span>
                            <span class="val  " >
                                    <?php echo $output['withdraw']['bank']['bank_register_name'].'('.show_withdraw_line_status($output['withdraw']['uwl_status']).')'; ?>
                            </span>
                        </div>
                        <?php }?>
                        <div class="span4">
                            <span class="label">转账日期：</span>
                            <span class="val ">
                                <?= $output['withdraw']['transfer_at'] ?>
                                </span>
                        </div>
                        <div class="span4">
                            <span class="label">转账银行&nbsp;&nbsp;：</span>
                            <span class="val ">
                                   <?php echo $output['withdraw']['transfer_bank_name'] ;?>
                            </span>
                        </div>
                        <div class="span4">
                            <span class="label">银行凭证号：</span>
                            <span class="val ">
                                   <?php echo $output['withdraw']['transfer_trade_no'] ;?>
                            </span>
                        </div>
                        <div class="span4">
                            <span class="label">记账凭证号：</span>
                            <span class="val ">
                                   <?php echo $output['withdraw']['transfer_record_no'] ;?>
                            </span>
                        </div>
                        <div class="span4">
                            <span class="label">上传凭证：</span>
                            <span class="val ">
                               <a href="<?php echo $output['withdraw']['transfer_voucher'] ;?>">凭证地址</a>
                            </span>
                        </div>
                        <div class="info">
                            <?php if(!empty($output['log_list']) && is_array($output['log_list'])){ ?>
                                <?php foreach($output['log_list'] as $kk => $vv){ ?>
                                    <div class="span12">
                                        <span class="label span2">转账<?php echo show_operation_step($vv['step'],13)?>人</span>
                                        <span class="val tb-type1" ><?php echo $vv['user_name']?></span>
                                        <span class="label span2">转账<?php echo show_operation_step($vv['step'],13)?>时间：</span>
                                        <span class="val tb-type1 " ><?php echo $vv['created_at']?></span>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>

                    </div>
            </div>
        </div>
        <?php }else{?>
            <div class="span4">
                <span class="label ">收款路线&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;：</span>
                <span class="val ">
                           <?= $output['withdraw']['uwl_account_name'];?>
                     </span>
            </div>
            <div class="span4">
                <span class="label">收款方账号&nbsp;&nbsp;：</span>
                <span class="val ">
                                       <?php echo $output['withdraw']['uwl_account_code'] ;?>
                                </span>
            </div>
            <div class="span4">
                <span class="label">转账日期：</span>
                <span class="val ">
                                <?= $output['withdraw']['transfer_at'] ?>
                                </span>
            </div>
            <table class="table tb-type2">
                <thead>
                <tr class="thead blue">
                    <th >对应入账流水号</th>
                    <th >提现额度有效时限</th>
                    <th >转账操作时限</th>
                    <th >提现总额度</th>
                    <th >提现金额</th>
                    <th >提现付款金额</th>
                </tr>
                </thead>
                <tbody>
                <tr class="hover">
                    <td><?= $output['withdraw']['ur_trade_no']?></td>
                    <td><?= $output['withdraw']['consume']['wichdraw_end_at']?></td>
                    <td>//TODO</td> <!--//TODO 转账操作时限，是根据支付机构提供的可退款周期，在平台后台设定的办理线上支付提现时限，一到该时限未提交转账自动作为“未成功”处理，进入 未成功页面-->
                    <td>￥ <?= $output['withdraw']['money']?></td>
                    <td>￥ <?= $output['withdraw']['money']-$output['withdraw']['fee']; ?></td>
                    <td>￥ <?= $output['withdraw']['money']-$output['withdraw']['fee']; ?></td>
                </tr>
                </tbody>
            </table>
          <div class="info">
            <div class="span4">
                <span class="label">转账流水号：</span>
                <span class="val ">
                                   <?php echo $output['withdraw']['transfer_trade_no'] ;?>
                            </span>
            </div>
            <div class="span4">
                <span class="label">记账凭证号：</span>
                <span class="val ">
                                   <?php echo $output['withdraw']['transfer_record_no'] ;?>
                            </span>
            </div>
            <div class="span4">
                <span class="label">上传凭证：</span>
                <span class="val ">
                        <?php if($output['withdraw']['transfer_voucher']){?>
                               <a href="<?php echo $output['withdraw']['transfer_voucher'] ;?>">凭证地址</a>
                        <?php }?>
                            </span>
            </div>
          </div>
            <div class="info">
                <?php if(!empty($output['log_list']) && is_array($output['log_list'])){ ?>
                    <?php foreach($output['log_list'] as $kk => $vv){ ?>
                        <div class="span4">
                            <span class="label">转账<?php echo show_operation_step($vv['step'],13)?>人:</span>
                            <span class="val span4" ><?php echo $vv['user_name']?></span>
                        </div>
                        <div class="span4">
                            <span class="label">转账<?php echo show_operation_step($vv['step'],13)?>时间：</span>
                            <span class="val" ><?php echo $vv['created_at']?></span>
                        </div>
                        <div class="span4"></div>
                    <?php } ?>
                <?php } ?>
            </div>

        <?php }?>

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
                        <a href="javascript:void(0);" onclick="show_add_dialog(<?php echo $_GET['uw_id'];?>);" class="button pull-left">添加</a>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>

        <div class="info footer">
            <a href="javascript:void(0);" onclick="show_unsuccess_dialog();">转账未成功？</a>
            <a href="javascript:history.go(-1);" class="button">返回</a>
        </div>
    </div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.serializejson.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js"></script>

<script type="text/javascript">
    $(function(){
    });


    //添加备注弹框
    function show_add_dialog(ur_id){
        parent.layer.open({
            type: 2,
            title:"添加备注",
            skin: 'layui-layer-rim', //加上边框
            area: ['500px', '400px'], //宽高
            content: '/index.php?act=finance&op=ajax_common_add_comment&operation_type=<?php echo $output['operation_type']?>&id='+ur_id
        });
    }
    //转账未成功
    function show_unsuccess_dialog() {
        parent.layer.confirm('请选择本次转账被退回的原因：', {
            btn: ['我方原因','客户原因',"取消"] //按钮
        }, function(){//我方原因  //进去到更正页面
                closeLayer();
                location.href="index.php?act=finance&op=withdraw_detail&uw_id=<?=$output['withdraw']['uw_id'];?>&reset=1";
        }, function(){//客户原因
                parent.layer.confirm('确定为客户原因么？', {
                    btn: ['确认','取消'] //按钮
                },function () {
                    //设置为未成功
                    ajax_form();
//                    location.href="index.php?act=finance&op=withdraw_detail&uw_id=<?//=$output['withdraw']['uw_id'];?>//";
                    closeLayer();
                },function () {
                    closeLayer();
                });

        });
    }

    //删除备注弹框
    function show_del_dialog(comment_id){
        parent.layer.confirm('您确认删除该条备注消息么？', {
            btn: ['确认','取消'] //按钮
        }, function(){
            post('index.php?act=finance&op=ajax_del_comment&current_status=<?php echo $output['withdraw']['status']?>&id='+comment_id).then(function(res){
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


    function ajax_form(){
        let json= {'in_ajax':1,'reject':1,'reject_status':42,'remark':'提现付款金额已重新转入可用余额','uw_id':<?=$output['withdraw']['uw_id']?>};

        post('index.php?act=finance&op=withdraw_detail', json).then(function(res){
            if(res.data.code == 200)
            {
                alert(res.data.msg);
//                refresh_workspace();
                location.href="index.php?act=finance&op=withdraw_detail&uw_id=<?=$output['withdraw']['uw_id'];?>";
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
