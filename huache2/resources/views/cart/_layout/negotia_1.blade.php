       <div class="container m-t-86 psr">
        <div class="step pos-rlt">
             <p class="order-head-status">
                 <a href="{{route('buy.show',['id'=>$order->id])}}" class="blue fs18">打造个性座驾</a>
                 <span class="ml5 blue fs18">></span>
                 <a href="{{route('parts.list',['id'=>$order->id])}}" class="ml5 blue fs18">已订购的精品</a>
                 <span class="ml5 blue fs18">></span>
                 <span class="ml5 blue fs18">协商减少订购</span>
             </p>

        </div>
    </div>
    <div class="container pos-rlt content">
        <p class="mt20 ml50 red"><span>由于售方已为您备货，减少数量请求须征得售方同意方能达成。如售方无法满足亦请您谅解～</span></p>
         <div class="xs-hd wp90">
            <ul class="ml30">
                <li>1.发起协商</li>
                <li class="cur">2.协商中</li>
                <li>3.完成协商</li>
                <div class="clear"></div>
            </ul>
        </div>

        <div class="clear"></div>
        <div class="wapper has-min-step">
            <div class="clear m-t-10"></div>
            <form id="main" action="/user/modifyxzj">
                <table  class="tbl tbl-blue tbl-xzj">
                    <tr>
                        <th width="130"><b>品牌</b></th>
                        <th width="150"><b>名称</b></th>
                        <th width="350"><b>型号/说明</b></th>
                        <th width="120"><b>已订件数</b></th>
                        <th width="130"><b>希望件数减少为</b></th>
                    </tr>
                    @foreach($datas->sortByDesc('xzj_type') as $data)
                    <tr>
                @if($data->xzj_type)
                    <td><p class="tac">原厂</p></td>
                @else
                    <td><p class="tac">{{$data->xzj_brand}}</p></td>
                @endif
                        <td><p class="tac">{{$data->xzj_title}}</p></td>
                        <td><p class="tal">{{$data->xzj_model}}</p></td>
                        <td><p class="tac">{{$data->same_num}}</p></td>
                        <td><p class="tac">{{$data->edit_num}}</p></td>
                    </tr>
                    @endforeach
                </table>
            </form>
            <div class="clear"></div>
            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
            <table>
                <tr>
                    <td width="80" valign="top"><span class="red">温馨提示：</span></td>
                    <td>
                        <p class="fs14">1.售方将在{{date('Y年m月d日 H:i:s',strtotime($order->xzjp_updated_at))}}前向您回复。</p>
                        <p class="fs14">2.建议您可与售方代表（{{$order->orderMember->member_truename}}：{{$order->orderMember->member_mobile}}）直接沟通情况，以便尽早达成谅解。</p>
                    </td>
                </tr>
            </table>

            <div class=" tac psr center mt50" >
                <a href="{{route('buy.show',['id'=>$order->id])}}" class="btn btn-s-md btn-danger fs18 sure ml50">返回</a>
            </div>





            <div class="m-t-10"></div>
            <div class="m-t-10"></div>
        </div>
    </div>