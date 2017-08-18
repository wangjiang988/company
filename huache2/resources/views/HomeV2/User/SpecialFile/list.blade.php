@extends('HomeV2._layout.user_base')
@section('css','')
@section('nav')
    @include('HomeV2._layout.nav_user')
@endsection

@section('content')
    <div class="user-content user-content-with-margin marign-top-tab psr">
        @include('HomeV2._layout.myfile_nav')
        <div class="mt10"></div>
        <div class="content-wapper">
            {!! Form::model($search,['url'=>route('special.list'),'role'=>'form','method'=>'get']) !!}
                <table class="custom-info-tbl noborder wp100">
                    <tbody><tr>
                        <td class="tar" width="90">文件名称：</td>
                        <td class="tal">
                            {!! Form::text('keyword', null, ['class' => 'form-control ml5 w300 inline-block','placeholder'=>"请输入"]) !!}
                            <button type="submit" class="btn btn-danger fs14 next ml20 btn-blue">查 询</button>
                            <a href="{{ route('special.add') }}" class="btn btn-danger fs14 next ml20 btn-orange">添 加</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            {!! Form::close() !!}
            <br>
            @if(isset($list))
            <table class="tbl">
                <tr>
                    <th>文件名称</th>
                    <th>上牌地区</th>
                    <th>提交时间</th>
                    <th>文件状态</th>
                    <th>操作</th>
                </tr>
                    @foreach($list as $key=>$item)
                <tr>
                    <td>
                        <p class="fs14 p">{{$item['file_name']}}</p>
                    </td>
                    <td>
                        <p class="fs14 p">{{getProvinceCityNames($item['area_id'])}}</p>
                    </td>
                    <td>
                        <p class="fs14 p">{{$item['created_at']}}</p>
                    </td>
                    <td>
                        <p class="fs14 p">
                        @if($item['status'] ==0)
                            待审核
                        @elseif($item['status'] ==1)
                            审核通过
                        @else
                            审核驳回
                        @endif
                        </p>
                    </td>
                    <td>
                        <a href="{{ route('special.view',['id'=>$item['id']]) }}" class="juhuang tdu">查看</a>
                    </td>
                </tr>
                    @endforeach
            </table>
            @else
            <h1 class="success-title">
                <table class="mauto">
                    <tr>
                        <td><span class="icon-large icon-error-large"></span></td>
                        <td><span class="inline-block ml20">暂未提交特殊文件~</span></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <br><br><br>
                            <a href="{{ route('special.add') }}" class="btn btn-s-md btn-danger fs16 sure ml50">去提交</a>
                        </td>
                    </tr>
                </table>
            </h1>
            @endif
            <div class="m-t-10" v-for="i in 12"></div>

            <div id="delWin" class="popupbox">
                <div class="popup-title">温馨提示</div>
                <div class="popup-wrapper">
                    <div class="popup-content">
                        <div class="m-t-10"></div>
                        <div class="set-authentication-wrapper">
                            <p class="gray">确定要删除此文件信息吗？</p>
                            <br>
                        </div>
                        <div class="m-t-10"></div>
                        <input type="hidden" name="token" value="">
                    </div>
                    <div class="popup-control">
                        <a href="javascript:;" @click.stop="doDelFile" class="btn btn-s-md btn-danger fs14 do btn-auto ">确定</a>
                        <a href="javascript:;" class="btn btn-s-md btn-danger fs14 sure btn-auto ml20 inline-block ">取消</a>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('login','')
@section('js')
    <script type="text/javascript">
        seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-special-files", "/webhtml/user/js/module/common/common"],function(v,u,c){


        })
    </script>
@endsection