@extends('HomeV2._layout.base')
@section('css')<link href="{{asset('themes/pwd.css')}}" rel="stylesheet" />@endsection
@section('nav')
    {{-- @section('nav_login') @include('HomeV2._layout.nav_temp') @endsection --}}
    @include('HomeV2._layout.nav')
@endsection

@section('content')
    
    <div class="container m-t-86 pos-rlt" id="vue">
        
        <div class="wapper">
            <div class="hd reg-box">
                <div class="title">用户登录</div>
                <div class="form">
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="{{ route('member.checkLogin') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">用户名</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control" name="name" value="{{ old('email') }}" placeholder="手机号或邮箱" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">密码</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> 记住我
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        登 录
                                    </button>

                                    <a href="{{ route('user.getReg') }}" target="_blank" class="btn btn-secondary">
                                        注 册
                                    </a>
                                    <br />
                                    <a class="btn btn-link" href="{{ route('pwd.showResetForm') }}">
                                        忘记密码?
                                    </a>
                                </div>

                            </div>
                        </form>
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
        seajs.use(["vendor/vue","module/common/common","bt"],function(a,b,c){
        })
    </script>
@endsection
