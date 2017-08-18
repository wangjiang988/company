@extends('HomeV2._layout.base2')
@section('css')
<?php $title = '404';?>
@endsection
@section('content')
<div class="container m-t-86 pos-rlt tac">
  <img src="/webhtml/common/images/404.png" alt="">
  <h2>哎呀，出错啦！</h2>
  <div class="m-t-10"></div>
  <div class="m-t-10"></div>	
  <p><span class="red" id="timeshow">5</span>秒后返回首页</p>
  <a href="/" class="juhuang tdu">返回首页</a>
  <a class="ml50 juhuang tdu" href="javascript:history.go(-1)">返回上一页</a>
 
</div>	

@endsection

@section('footer')
  @include('HomeV2._layout.footer')
@endsection

	


@section('js')
	<script src="/js/sea.js"></script>
    <script src="/js/config.js"></script>
    <script type="text/javascript">
        seajs.use(["vendor/vue","/webhtml/common/js/module/404", "module/common/common"],function(a,b){
            
        });
    </script>
@endsection