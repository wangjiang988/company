@extends('_layout.base')
@section('css')
<link href="{{asset('themes/search.css')}}" rel="stylesheet" />
<link href="{{asset('themes/duibi.css')}}" rel="stylesheet" />
@endsection
@section('nav')
<nav class="navbar navbar-inverse navbar-fixed-top" >
    <div class="container">
        <div id="navbar" class="collapse navbar-collapse">
            <div class="navbar-header pos-rlt">
                <a class="navbar-brand logo" href="/">华车网</a>
            </div>
            <ul class="nav navbar-nav">
                <li><a href="#maiche">买车流程</a></li>
                <li><a href="#baozhang">诚信保障</a></li>
                <li><a href="#services">服务中心</a></li>
            </ul>
            <ul class="nav navbar-nav control">
            @if(isset($_SESSION['member_name']))
                <li class="loginout">
                    <label>欢迎您：<a href="{{ route('user.ucenter') }}"><span>{{ $_SESSION['member_name'] }}</span></a> </label>
                    <em>|</em>
                    <a href="{{ route('logout') }}"><span>[</span>退出<span>]</span></a>
                </li>
            @else
                <li class="loginout">
                    <a ms-click="login" href="javascript:;">快速登陆</a><em>|</em>
                    <a href="{{ $_ENV['_CONF']['config']['www_site_url'] }}/regbyphone">快捷注册</a>
                </li>
            @endif
            </ul>
        </div>

    </div>
</nav>
@endsection
@section('content')

<form class="SearchPageForm" ms-controller="duibi" name="SearchPageForm">
        <div class="container m-t-86 pos-rlt">
            <h1>对比</h1>
            <div class="search-def-option">
                <ul>
                    <li>
                        <label>新车上牌地区</label>
                        <dl>
                            <dt class="s-area">
                                <p>{{ $city }}<span>
                                    @if($xianpai==1)
                                        (限牌)
                                        @endif
                                    
                                </span></p>
                            </dt>
                        </dl>
                    </li>

                    <li>
                        <label>品牌</label>
                        <dl>
                            <dt class="s-area">
                                <p>{{ $pinpai  }}</p>
                            </dt>
                        </dl>
                    </li>

                    <li>
                        <label>车系</label>
                        <dl>
                            <dt class="s-area">
                                <p>{{ $chexi }}</p>
                            </dt>
                            
                        </dl>
                    </li>

                    <li>
                        <label>车型规格</label>
                        <dl>
                            <dt class="s-chexing">
                                <p>{{ $chexing }}</p>
                            </dt>
                        </dl>
                    </li>

                    <li>
                        <label>厂家指导价</label>
                        <dl>
                            <dt class="s-chexing">
                                <p>￥{{ $carmodelInfo['zhidaojia'] }}</p>
                            </dt>
                            <dd>
                                <input type="hidden" name="price" value="" />
                            </dd>
                        </dl>
                    </li>
                    <li class="clear"></li>
                    <li>
                        <label>座位数</label>
                        <dl>
                            <dt class="s-chexing">
                                <p>{{ $carmodelInfo['seat_num'] }}</p>
                            </dt>
                        </dl>
                    </li>
                    <li>
                        <label>生产国别</label>
                        <dl>
                            <dt class="s-chexing">
                                @if($carmodelInfo['guobie']==1)
                                <p>进口</p>
                                    @else
                                <p>国产</p>
                                  @endif
                            </dt>
                        </dl>
                    </li>
                    <li>
                        <label>基本配置</label>
                        <dl>
                            <dt class="s-chexing">
                                <p>权威参数来自官网 <a href="{{ $barnd_info['official_url'] }}" target="_blank"><span>查看</span></a></p>
                            </dt>
                        </dl>
                    </li>
                    <li class="clear"></li>
                </ul>
            </div>
        </div>
		<?php 
		$num = count($bj);
		$widthPercent = floor(100/($num+1));
		$style="style=width:".$widthPercent."%";
		?>
        <div class="container  pos-rlt car-list">
            <table class="tbl">
                <tbody>
                    <tr>
                        <td class="prev-title" {{$style}}>车源位置</td>
                        
                        @foreach($bj as $key =>$value)
                        <td {{$style}}>
                            {{ $value['show_distance']}} 公里
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="prev-title">车价(含服务费）</td>
                        @foreach($bj as $key =>$value)
                        <td>
                            {{ $value['bj_lckp_price']}} 元
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="prev-title">车价对比指导价</td>
                         @foreach($bj as $key =>$value)
                        <td>
                            {{ $value['dbjpre'] }} 
                            @if(intval($value['dbjpre'])>=100)
                            ↑
                            @else
                            ↓
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="prev-title">付款方式</td>
                        @foreach($bj as $key =>$value)
                        <td>
                            @if($value['bj_pay_type']==1)
                            全款
                            @else
                            贷款
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td colspan="<?php echo $num+2;?>" class="t-footer">
                            <p ms-click="toggleContent"><b>车辆情况</b><i></i></p>
                        </td>
                    </tr>
                    <tr class="tr">
                        <td class="prev-title">车身颜色</td>
                        @foreach($bj as $key =>$value)
                        <td>{{ $value['body_color']}}</td>
                        @endforeach
                    </tr>
                    <tr class="tr">
                        <td class="prev-title">内饰颜色</td>
                        @foreach($bj as $key =>$value)
                        <td>{{ $value['interior_color']}}</td>
                        @endforeach
                    </tr>
                    <tr class="tr">
                        <td class="prev-title">行驶里程</td>
                        @foreach($bj as $key =>$value)
                        <td>{{ $value['bj_licheng']}} 公里</td>
                        @endforeach
                    </tr>
                    <tr class="tr">
                        <td class="prev-title">出厂年月或交车周期</td>
                        @foreach($bj as $key =>$value)
                        <td>{{ $value['bj_producetime']}}/{{ $value['bj_jc_period']}}</td>
                        @endforeach
                    <tr class="tr">
                        <td class="prev-title">排放标准</td>
                        @foreach($bj as $key =>$value)
                        <td>
                           @if($carmodelInfo['paifang']==0)
                                                            国5标准
                                                            @elseif($carmodelInfo['paifang']==1)
                                                            国4标准
                                                        @elseif($carmodelInfo['paifang']==2)
                                                            新能源
                                                            @endif 
                        </td>
                        @endforeach
                    </tr>
                    <tr class="tr">
                        <td class="prev-title">加装改装情况</td>
                        @foreach($bj as $key =>$value)
                        <td>
                            
                            <?php 
                                $title1='';
                                foreach ($value['xzj'] as $k =>$v) {
                                    $title1.=$v['xzj_title'].',';
                                }
                                echo $title1;
                             ?>
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td colspan="<?php echo $num+2;?>" class="t-footer">
                            <p ms-on-click="toggleContent"><b>附加条件</b><i></i></p>
                        </td>
                    </tr>
                    <tr class="tr2">
                        <td class="prev-title">车辆上牌用途</td>
                         @foreach($bj as $key =>$value)
                        <td><?php 
                                if ($carmodelInfo['seat_num']>6) {
                                    echo "非运营公司用车";
                                }else{
                                    echo "非运营个人用车";
                                }
                         ?></td>
                        @endforeach
                    </tr>
                    <tr class="tr2">
                        <td class="prev-title">商业保险限定</td>
                        @foreach($bj as $key =>$value)
                        <td>
                            @if($value['bj_baoxian']==1)
                            指定购买
                            @else
                            自由购买
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    <tr class="tr2">
                        <td class="prev-title">参照保费</td>
                        @foreach($bj as $key =>$value)
                        <td>
                           {{ $value['bxprice'] }} 元
                        </td>
                        @endforeach
                    </tr>
                    <tr class="tr2">
                        <td class="prev-title">上牌服务限定</td>
                        @foreach($bj as $key =>$value)
                        <td>
                            @if($value['bj_shangpai']==1)
                            指定上牌
                            @else
                            自由上牌
                            @endif
                        </td>
                        @endforeach
                    <tr class="tr2">
                        <td class="prev-title">上牌服务限定参考费用</td>
                        @foreach($bj as $key =>$value)
                        <td>
                           {{ $value['bj_shangpai_price'] }} 元
                        </td>
                        @endforeach
                    </tr>
                    
                    <tr class="tr2">
                        <td class="prev-title">上临时牌限定</td>
                        @foreach($bj as $key =>$value)
                        <td>
                            @if($value['bj_linpai']==1)
                            指定上牌
                            @else
                            自由上牌
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    <tr class="tr2">
                        <td class="prev-title">原厂选装件折扣率</td>
                        @foreach($bj as $key =>$value)
                        <td>
                           {{ $value['bj_xzj_zhekou'] }} %
                        </td>
                        @endforeach
                    </tr>
                    <tr class="tr2">
                        <td class="prev-title">其他收费项目和金额</td>
                        @foreach($bj as $key =>$value)
                        <td>
                           <?php 
                            $str='';
                                foreach ($value['other_price'] as $k => $v) {
                                    $str.=$k.' '.$v.'元 ,';
                                }
                                echo rtrim($str,',')
                            ?>
                        </td>
                        @endforeach
                    </tr>
                    <tr class="tr2">
                        <td class="prev-title">补贴种类和金额</td>
                        @foreach($bj as $key =>$value)
                        <td>国家补贴 
                           {{ $value['bj_butie'] }} 元
                        </td>
                        @endforeach
                    </tr>
                    <tr class="tr2">
                        <td class="prev-title">赠品或免费附加服务</td>
                        @foreach($bj as $key =>$value)
                        <td>
                            
                            <?php 
                                $title1='';
                                foreach($value['zengpin'] as $k =>$v){
                                    $title1.=$v['title'].',';

                                }
                                echo rtrim($title1,',');
                             ?>
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td colspan="<?php echo $num+2;?>" class="t-footer">
                            <p ms-on-click="toggleContent"><b>担保要求</b><i></i></p>
                        </td>
                    </tr>
                    <tr class="tr3">
                        <td class="prev-title">买车担保金</td>
                        @foreach($bj as $key =>$value)
                        <td>
                           {{ $value['bj_car_guarantee'] }} 元
                        </td>
                        @endforeach
                    </tr>
                    <tr class="tr3">
                        <td class="prev-title">诚意金</td>
                        @foreach($bj as $key =>$value)
                        <td>
                           499 元
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td class="nobordres"></td>
                        @foreach($bj as $key =>$value)
                        <td class="nobordres"><a href="{{ url('show/'.$value['bj_serial']).'/50/'.$buytype }}" data-grounp="照推荐组合投保" class="btn btn-s-md btn-danger w80p">抢订</a></td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </form>

@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["module/item/item-duibi", "module/common/common", "bt"]);
    </script>
@endsection