<?php defined('InHG') or exit('Access Invalid!');?>

<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><a href="index.php?act=admin_finance&op=home">平台财务</a></h3>
            <ul class="tab-base">
                <li><a  href="index.php?act=admin_finance&op=user_withdraw_limit"><span>客户提现银行手续费设定</span></a></li>
                <li><a class="current"><span>添加模板</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <div class="container">
        <div class="big_title2">
            添加模板
        </div>

        <div class="ml50 content">
            <div class="pure-g line">
                <div class="pure-u-3-24">
                    <span class="required"></span>
                    模板编号
                </div>
                <div class="pure-u-9-24">
                   3
                </div>
            </div>

            <div class="pure-g line">
                <div class="pure-u-3-24">
                  <span class="required">*</span>
                  模板名称
                </div>
                <div class="pure-u-9-24">
                    <input class="form-control" type="text"/>
                </div>
            </div>

            <div class="pure-g line">
                <div class="pure-u-3-24">
                    <span class="required">*</span>
                    模板内容
                </div>
                <div class="pure-u-9-24">
                   //TODO
                </div>
            </div>


        </div>

        <div class="info footer">
            <a href="javascript:void(0);" onclick="confirm_form();" class="button confirm">确定</a>
            <a href="javascript:history.go(-1);" class="button  ml-20">返回</a>
        </div>

    </div>

</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript">
    $(function(){

    });

    //表单提交
    function confirm_form() {
        
    }
</script>
