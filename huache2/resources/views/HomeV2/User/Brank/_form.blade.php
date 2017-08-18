@extends('HomeV2._layout.base')
@section('css')<link href="{{asset('themes/pwd.css')}}" rel="stylesheet" />@endsection
@section('nav')
    @section('nav_login') @include('HomeV2._layout.nav_temp') @endsection
    @include('HomeV2._layout.nav')
@endsection

@section('content')
<div class="box" ms-include-src="regheader"></div>
    <div class="container m-t-86 pos-rlt" id="vue">
        <div class="container m-t-86 pos-rlt" id="vue">
            <div class="wapper">
                <div class="hd reg-box">
                    <div class="title">银行卡管理</div>
                    <div class="form">

                    {!! Form::open(['url'=>route('brank.save'),'class'=>'form-horizontal','role'=>'form','enctype'=>'multipart/form-data']) !!}

                        <div class="form-group">
                            {!! Form::label('brank_code', '银行卡号:', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('brank_code', null, ['required','class' => 'form-control']) !!}
                                @if ($errors->has('brank_code'))
                                    <span class="help-block">
                                       <strong>{{ $errors->first('brank_code') }}</strong>
                                    </span>
                                @endif
                            </div>                            
                        </div>
                        <div class="form-group">
                            {!! Form::label('brank_name', '银行名称:', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('brank_name', null, ['class' => 'form-control']) !!}
                                @if ($errors->has('brank_name'))
                                    <span class="help-block">
                                       <strong>{{ $errors->first('brank_name') }}</strong>
                                 </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('province', '选择省:', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::select('province',$region,null, ['id'=>'province','class'=>'form-control','onchange'=>'showCity(this.value())']) !!}
                                @if ($errors->has('province'))
                                    <span class="help-block">
                                       <strong>{{ $errors->first('province') }}</strong>
                                    </span>
                                @endif
                            </div>                            
                        </div>
                        <div class="form-group">
                        {!! Form::label('city', '选择市/区:', ['id'=>'city','class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::select('city',[],'', ['class'=>'form-control']) !!}
                                @if ($errors->has('city'))
                                    <span class="help-block">
                                       <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                @endif
                            </div>                            
                        </div>
                        <div class="form-group">
                            {!! Form::label('district', '选择县/镇:', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::select('district',[],'', ['id'=>'district','class'=>'form-control']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('brank_address', '开户行地址:', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::text('brank_address', null, ['required','class' => 'form-control']) !!}
                            </div>
                            @if ($errors->has('brank_address'))
                                <span class="help-block">
                                       <strong>{{ $errors->first('brank_address') }}</strong>
                                 </span>
                            @endif
                        </div>
                        <div class="form-group">
                            {!! Form::label('sc_brank_img', '手持银行卡图片:', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::file('sc_brank_img', ['class'=>'form-control']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('brank_img', '银行卡正面图片:', ['class'=>'col-sm-2 control-label']) !!}
                            <div class="col-sm-10">
                                {!! Form::file('brank_img', ['class'=>'form-control']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-7 col-md-offset-3">
                                <button type="submit" class="btn btn-primary btn-md">
                                    {!! Form::hidden('id',$brank['id']) !!}
                                    <i class="fa fa-plus-circle">提交</i>
                                </button>
                            </div>
                        </div>
                    {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('login') @include('HomeV2._layout.login')@endsection
@section('js')
    <script type="text/javascript">
        seajs.use(["vendor/vue","module/common/common","bt"],function(a,b,c){
        })
    </script>
@endsection

