<div class="slide">
    <div class="portlet-body">
        <div class="panel-group accordion">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a class="accordion-toggle"  href="javascript:;">我的华车 </a>
                    </h4>
                    <i class="fa fa-sort-up"></i>
                </div>
                <div class="panel-collapse ">
                    <div class="panel-body">
                        <a href="{{ route('user.info') }}" {{ userLeftSelected($nav,'info') }}>个人资料</a>
                        <a href="{{ route('user.safe') }}" {{ userLeftSelected($nav,'safe') }}>安全设置</a>
                        <!-- <a href="{{ route('user.bank') }}" {{ userLeftSelected($nav,'bank') }}>银行账户管理</a> -->
                    </div>
                </div>
            </div>
            <div class="panel panel-default ">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a class="accordion-toggle" href="javascript:;"> 我的订单  </a>
                    </h4>
                    <i class="fa fa-sort-up"></i>
                </div>
                <div class="panel-collapse">
                    <div class="panel-body" >
                        <a href="{{ route('my.order') }}" {{ userLeftSelected($nav,'myOrder') }}>我的订单</a>
                    </div>
                </div>
            </div>
            <div class="panel panel-default ">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a class="accordion-toggle" href="javascript:;"> 我的财务  </a>
                    </h4>
                    <i class="fa fa-sort-up"></i>
                </div>
                <div class="panel-collapse">
                    <div class="panel-body" >
                        <a href="{{ route('my.myBalance') }}" {{ userLeftSelected($nav,'myBalance') }}>我的余额</a>
                        <a href="{{ route('my.RecordedAt') }}" {{ userLeftSelected($nav,'RecordedAt') }}>我的转入</a>
                        <a href="{{ route('my.Withdrawal') }}" {{ userLeftSelected($nav,'Withdrawal') }}>我的提现</a>
                        <a href="{{ route('my.Welfare') }}" {{ userLeftSelected($nav,'Welfare') }}>我的福利</a>
                        <a href="{{ route('my.Receipt') }}" {{ userLeftSelected($nav,'Receipt') }}>我的发票</a>
                    </div>
                </div>
            </div>
            <div class="panel panel-default last">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a class="accordion-toggle" href="javascript:;"> 我的文件  </a>
                    </h4>
                    <i class="fa fa-sort-up"></i>
                </div>
                <div class="panel-collapse ">
                    <div class="panel-body" >
                        <a href="{{ route('auth.showIdCart') }}" {{ userLeftSelected($nav,'myFile') }}>我的提交</a>
                        <a href="{{ route('special.download') }}" {{ userLeftSelected($nav,'myDownload') }}>我的下载</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>