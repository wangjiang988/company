@if($timeOut)
    <div class="fs14 tac ml260"  >
        <span class="fs14 fl mt10 outtime">已超时：</span>
        <div class="time fl">
            <div class="jishi wp100 outtime" id="timeout">
                <span>0</span>
                <span>0</span>
                <span class="fuhao">:</span>
                <span>0</span>
                <span>0</span>
                <span class="fuhao">:</span>
                <span>0</span>
                <span>0</span>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="m-t-10 tac">
        <p class="hide error showerror">请选择支付方式</p>
        <a ms-on-click="subPayForm" href="javascript:;" class="btn fs16 btn-s-md btn-danger">立即支付</a>
        <a href="javascript:;" class="btn fs16 btn-s-md btn-danger ml20 sure">紧急联系客服</a>
    </div>
@else
    <div class="fs14 tac ml260"  >
        <span class="fs14 fl mt10">剩余时间：</span>
        <div class="time fl">
            <div class="jishi wp100" id="countdown">
                <span>0</span>
                <span>0</span>
                <span class="fuhao">:</span>
                <span>0</span>
                <span>0</span>
                <span class="fuhao">:</span>
                <span>0</span>
                <span>0</span>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="m-t-10 tac">
        <p class="hide error showerror">请选择支付方式</p>
        <a ms-on-click="subPayForm" href="javascript:;" class="btn fs16 btn-s-md btn-danger  ">确认并进行支付</a>
    </div>
    <div class="m-t-10"></div>
@endif
