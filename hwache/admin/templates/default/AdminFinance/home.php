<?php defined('InHG') or exit('Access Invalid!');?>

<div class="page finance">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>平台财务</h3>
        </div>
    </div>
    <div class="fixed-empty"></div>

    <div class="home_container">
        <div class="pure-g line">
            <div class="pure-u-1-3">
                <div class="cell" data-href="index.php?act=admin_finance&op=user_withdraw_limit">客户提现手续费设定</div>
            </div>
            <div class="pure-u-1-3">
                <div class="cell" data-href="index.php?act=admin_finance&op=user_online_pay_limit">客户线上支付退款周期设定</div>
            </div>
            <div class="pure-u-1-3">
                <div class="cell" data-href="index.php?act=admin_finance&op=user_withdraw_filter">客户提现申请拦截条件设定</div>
            </div>
        </div>

        <div class="pure-g line">
            <div class="pure-u-1-3">
                <div class="cell" data-href="index.php?act=admin_finance&op=seller_withdraw_filter">售方提现申请拦截条件设定</div>
            </div>
            <div class="pure-u-1-3">
                <div class="cell" data-href="index.php?act=admin_finance&op=seller_withdraw_limit">售方提现银行手续费设定</div>
            </div>
            <div class="pure-u-1-3">
            </div>
        </div>

        <div class="pure-g line">
            <div class="pure-u-1-3">
                <div class="cell" data-href="index.php?act=admin_finance&op=account_log">账户资金流动</div>
            </div>
            <div class="pure-u-1-3">
                <div class="cell"  data-href="index.php?act=admin_finance&op=account_settlement">收入支出申报</div>
            </div>
            <div class="pure-u-1-3">
                <div class="cell" data-href="index.php?act=admin_finance&op=seller_settlement">售方收入开票入账</div>
            </div>
        </div>

        <div class="pure-g line">
            <div class="pure-u-1-3">
                <div class="cell" data-href="index.php?act=admin_finance&op=user_jiaxinbao">客户加信宝</div>
            </div>
            <div class="pure-u-1-3">
                <div class="cell" data-href="index.php?act=admin_finance&op=seller_jiaxinbao">售方加信宝</div>
            </div>
            <div class="pure-u-1-3">
            </div>
        </div>

    </div>

</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript">
    $(function(){
        $('.cell').click(function(){
            let  _this = $(this);
            let href = _this.data('href');
            location.href = href;
        });
    });
</script>
