@extends('HomeV2._layout.base')
@section('css')<link href="{{asset('themes/pwd.css')}}" rel="stylesheet" />@endsection
@section('nav') @include('_layout.nav') @endsection
@section('content')
    <div class="box" ms-include-src="regheader"></div>
    <div class="container m-t-86 pos-rlt" id="vue">
        <div class="wapper">
            <div class="hd reg-box">
                <div class="title">身份认证</div>
                

                <div class="form">
                    <br>
                    {!! Form::open(['url'=>route('auth.postIdCart'),'class'=>'form-horizontal','role'=>'form','enctype'=>'multipart/form-data']) !!}
                    <div class="form-group">
                        {!! Form::label('name', '姓名:', ['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::text('name', null, ['class' => 'form-control']) !!}
                            @if ($errors->has('name'))
                                <span class="help-block">
                                   <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('id_cart', '身份证:', ['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::text('id_cart', null, ['class' => 'form-control']) !!}
                            @if ($errors->has('id_cart'))
                                <span class="help-block">
                                   <strong>{{ $errors->first('id_cart') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('sc_id_cart_img', '手持身份证:', ['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::file('sc_id_cart_img', ['class'=>'form-control']) !!}                            
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('id_facade_img', '身份证正面:', ['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::file('id_facade_img', ['class'=>'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('id_behind_img', '身份证反面:', ['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::file('id_behind_img', ['class'=>'form-control']) !!}
                        </div>
                    </div>
                    	<div class="form-group">
                    		<div class="col-md-7 col-md-offset-3">
                    			<button type="submit" class="btn btn-primary btn-md">
                    				<i class="fa fa-plus-circle"> 提 交 </i>
                    			</button>
                    		</div>
                    	</div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <br /><br /><br />
    </div>
@endsection

@section('footer')
    @include('HomeV2._layout.footer')
@endsection

@section('login')
    @include('HomeV2._layout.login')
@endsection

@section('js')
<script type="text/javascript">
    seajs.use(["/webhtml/common/js/vendor/vue.min","/webhtml/user/js/module/user/user-base", "/webhtml/user/js/module/common/common", "bt"],function(a,b,c,d){
    });
</script>
@endsection