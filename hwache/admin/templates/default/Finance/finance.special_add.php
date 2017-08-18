
<?php defined('InHG') or exit('Access Invalid!');?>
<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>客户财务</h3>
            <ul class="tab-base">
                <li><a href="index.php?act=finance&op=index"><span>客户财务</span></a>▷</li>
                <li><a href="index.php?act=finance&op=user_special_index&uid=<?=$output['user']['id']?>"><span>特别事项</span></a>▷</li>
                <li><a class="current"><span>提交申请</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>

    <form method="get" action="index.php" name="formSearch" id="formSearch">
        <input type="hidden" name="act" value="finance" />
        <input type="hidden" name="op" value="with_draw_limit" />
        <input type="hidden" name="uid" value="<?php echo $output['user']['id'] ;?>" />
        <input type="hidden" name="is_search" value="1" />
        <div class="info">
            <span class="label">客户会员号：</span>
            <span class="val"><?php echo $output['user']['id'] ;?></span>
            <span class="label">客户姓名：</span>
            <span class="val"><?php echo $output['user']['name'] ;?></span>
            <span class="label">客户手机：</span>
            <span class="val"><?php echo $output['user']['phone'] ;?></span>
        </div>
        <div class="info">
            <span class="label">客户可用余额：</span>
            <span class="val">￥ <?php echo $output['user']['account']['avaliable_deposit']?> </span>
            <span class="label">平台冻结可用余额：</span>
            <span class="val">￥ <?php echo $output['user']['account']['temp_deposit']?> </span>
        </div>

    </form>

    <div class="clear"></div>
    <div class="big_title">
        申请内容
    </div>
    <div class="clear"></div>
    <form method="get"  id="ajax_form">
        <input type="hidden" name="act" value="finance">
        <input type="hidden" name="op" value="special_add">
        <input type="hidden" name="uid" value="<?php echo $output['user']['id']?>">
        <input type="hidden" name="form_submit" value="ok">
        <input type="hidden" name="in_ajax" value="1">
        <div class="info">
            <div class="title">
                <span class="icon" style="color: red;">*</span>
                申请项目与金额
            </div>
            <div class="info" style="padding-left: 50px; ">
                <div class=" ">
                    <span class="title span2">
                    <input type="radio" id="special_type_1" class="special_type" name="special_type" value="1" checked> <label for="special_type_1">冻结</label>
                     </span>
                    <span class="val span3">
                        ￥ <input type="text" data-range="<?=$output['user']['account']['avaliable_deposit'];?>" class="special_type_val" name="special_type_1_val" id="special_type_1_val"
                                 placeholder="0~<?=$output['user']['account']['avaliable_deposit'];?>"
                        >
                    </span>
                    <span class="tips span4">
                    </span>
                </div>

                <div class="">
                    <span class="title span2">
                     <input type="radio" id="special_type_2" class="special_type" name="special_type" value="2"> <label for="special_type_2">解冻</label>
                     </span>

                    <span class="val span3">
                        ￥ <input type="text" data-range="<?=$output['user']['account']['temp_deposit'];?>" class="special_type_val"
                                 placeholder="0~<?=$output['user']['account']['temp_deposit'];?>"
                                 name="special_type_2_val" id="special_type_2_val" readonly="readonly">
                    </span>
                    <span class="tips span4">
                    </span>
                </div>

                <div class="">
                    <span class="title span2">
                     <input type="radio" id="special_type_3" class="special_type" name="special_type" value="3"> <label for="special_type_3">转出</label>
                     </span>
                    <span class="val span3">
                        ￥ <input type="text" data-range="<?=$output['user']['account']['avaliable_deposit'];?>"
                                 placeholder="0~<?=$output['user']['account']['avaliable_deposit'];?>"
                                 class="special_type_val" name="special_type_3_val" id="special_type_3_val" readonly="readonly">
                    </span>
                    <span class="tips span4">
                    </span>
                </div>

                <div class=" ">
                    <span class="title span2">
                     <input type="radio" id="special_type_4" class="special_type" name="special_type" value="4"> <label for="special_type_4">转入</label>
                     </span>
                    <span class="val span3">
                        ￥ <input type="text" data-range="<?=$output['max_transfer_to_user_account']?>"
                                 placeholder="0~<?=$output['max_transfer_to_user_account'];?>"
                                 class="special_type_val" name="special_type_4_val" id="special_type_4_val" readonly="readonly">
                    </span>
                    <span class="tips span4">
                    </span>
                </div>

                <div class=" ">
                    <span class="title span2">
                     <input type="radio" id="special_type_5" class="special_type" name="special_type" value="5"> <label for="special_type_5">返还已得</label>
                     </span>
                    <span class="span2">
                        <select name="order_id_1"  id="order_id_1">
                            <option value="">请选择订单号</option>
                            <?php if(!empty($output['order_list']) && is_array($output['order_list'])){ ?>
                            <?php foreach($output['order_list'] as $k => $v){ ?>
                                <option value="<?=$v['id']?>"><?=$v['id']?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                    </span>
                    <span class="wt160" >
                       售方获得：
                    </span>
                    <span class="val "><!--TODO  这里需要取最大值。未定-->
                        ￥ <input type="text" data-range="1000"
                                 placeholder="0~1000"
                                 class="special_type_val" name="special_type_5_val" id="special_type_5_val" readonly="readonly">
                    </span>
                    <span class="tips span4">
                    </span>
                </div>

                <div>
                    <span class="title span2">
                        <input type="radio" id="special_type_6"   class="special_type" name="special_type" value="6"> <label for="special_type_6">获得返还</label>
                     </span>
                    <span class="span2">
                        <select name="order_id_2" id="order_id_2">
                            <option value="">请选择订单号</option>
                            <?php if(!empty($output['order_list']) && is_array($output['order_list'])){ ?>
                                <?php foreach($output['order_list'] as $k => $v){ ?>
                                    <option value="<?=$v['id']?>"><?=$v['id']?></option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </span>
                    <span class="wt160">
                        售方返还 &nbsp;&nbsp;&nbsp;&nbsp;   1.从可提现余额
                    </span>
                    <span class="val">
                        ￥ <input type="text" data-range="<?=$output['user']['account']['avaliable_deposit'];?>"
                                 placeholder="0~1000"
                                 class="special_type_val" name="special_type_6_val" id="special_type_6_val" readonly="readonly">
                    </span>

                </div>
                <div  >
                    <span class="title span2">&nbsp;
                     </span>
                    <span class="span2">&nbsp;
                    </span>
                    <span class="wt160">
                        平台返还 &nbsp;&nbsp;&nbsp;&nbsp;   1.从平台余额
                    </span>
                    <span class="val">
                        ￥ <input type="text" class="special_type_val"
                                 placeholder="0~1000"
                                 name="special_type_7_val" id="special_type_7_val" readonly="readonly">
                    </span>
                    <span class="wt150">
                         2.从待申报收入
                    </span>
                    <span class="val">
                        ￥ <input type="text" class="special_type_val"
                                 placeholder="0~1000"
                                 name="special_type_8_val" id="special_type_8_val" readonly="readonly">
                    </span>
                </div>
                <div class="info">
                    <span class="title span2"> &nbsp;
                     </span>
                    <span class="span2"> &nbsp;
                    </span>
                    <span class='wt160'>
                         &nbsp;
                    </span>
                    <span  style="width:234px;">
                        &nbsp;
                    </span>
                    <span class='wt150'>
                         售方待结算金额相应扣减
                    </span>
                    <span class="val">
                        ￥ <input type="text" class="special_type_val" name="special_type_9_val"
                                 placeholder="0~1000"
                                 id="special_type_9_val" readonly="readonly">
                    </span>

                </div>

                <div class="info">
                  <span class="title span2">
                       <span class="icon" style="color: red;">*</span>原因
                  </span>
                    <span class="val span6">
                     <input type="text" class="wt400" name="reason" id="reason"/>
                </span>
                </div>
                <div class="info">
                  <span class="title span2">
                      备注
                  </span>
                    <span class="val span6">
                     <textarea class="wt400" style="min-height: 120px;" name="remark" value="" id="remark" rows="10"></textarea>
                </span>
                </div>
            </div>
        </div>

        <div class="info footer">
            <a href="javascript:void(0);" class="button confirm" style="margin-right: 100px;" onclick="confirm_form();">提交</a>
            <a href="javascript:history.go(-1);" class="button">返回</a>
        </div>
    </form>

</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.serializejson.min.js" charset="utf-8"></script>

<script type="text/javascript">
    $(function(){
        //TODO  order这部分暂时不写
        $('#order_id_1').change(function(){
            var order_id = $(this).val();
            get_order_info(order_id,"order_id_1");
        });

        //加上监控事件
        $(".special_type").change(function(){
            _this = $(this);
            checked  =  _this.prop('checked');
            if(checked){
                val  = _this.val();
                if(val < 6 ){
                    //前五个只要有一个input
                    readonlyAll();
                    $('#special_type_'+val+"_val").removeAttr('readonly');
                }
            }
        });

        //input  不能超过值上限
         $(".special_type_val").blur(function(){
             _this  =  $(this);
             value  =  parseFloat(_this.val());
             range  =  parseFloat(_this.data("range"));
             console.log(range);
             if(value > range){
                 alert("值超出范围");
                 _this.val("");
//                 _this.focus();
             }
         });

    });


    function readonlyAll()
    {
        $(".special_type_val").val("");
        $(".special_type_val").prop('readonly','readonly');
    }


    function get_order_info(order_id, element_name)
    {
        //order_id
        post('index.php?act=finance&op=ajax_get_orderinfo', {"id":order_id}).then(function(res){
            if(res.data.code == 200)
            {
//                alert(res.data.msg);
//                $("#"+element_name).val();
            }else
            {
                alert(res.data.msg);
            }
        }).catch(function(err){
            console.log(err);
        });
    }

    /**
     * 确认按钮
     */
    function confirm_form(){
        let json= $('#ajax_form').serializeJSON();
        if(json.special_type < 5){
            let ele_id = 'special_type_'+json.special_type+'_val';
            let val  =  $("#"+ele_id).val();
            let range =  $("#"+ele_id).data('range');
            if(!valid(ele_id, /^[0-9]+(.[0-9]+)?$/)){
                alert("请输入合适的值（数字）");
                return false;
            }  //验证两位小数
            //验证是否超出最高 值
            val   = parseFloat(val);
            range   = parseFloat(range);

            if(val > range){
                alert('您填写的值超出最高范围！');
                return false;
            }
        }

        if(json.reason==''){
            alert("请填写原因");
            return false;
        }

        parent.layer.confirm('确定提交该特别事项申请吗？？', {
            btn: ['确认','取消'] //按钮
        }, function(){
            submit_form(json);
            closeLayer();
        }, function(){
            closeLayer();
        });
    }

    function submit_form(json){
        post('index.php?act=finance&op=special_add', json).then(function(res){
            if(res.data.code == 200)
            {
                alert(res.data.msg);
//                refresh_workspace();
                closeLayer();
                history.go(-1);
            }else
            {
                alert(res.data.msg);
            }
        }).catch(function(err){
            console.log(err);
        });
    }

</script>
