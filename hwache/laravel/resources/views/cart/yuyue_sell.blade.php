@extends('_layout.base')

@section('content')
  <div style="margin-top:100px;">
    <form action="{{ route('cart.yuyue.post') }}" method="post">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <p>交车时间:<input type="text" name="jc_time" /></p>
      <p>交车地点:<input type="text" name="jc_place" /></p>
      <p>车架号:<input type="text" name="jc_car_frame" /></p>
      <p>发动机号:<input type="text" name="jc_engine" /></p>
      <p>车辆内外照片:<input type="text" name="jc_img" /></p>
      <p>车身颜色:<input type="text" name="jc_body_color" /></p>
      <p>内饰颜色:<input type="text" name="jc_interior_color" /></p>
      <p>实车出厂年月:<input type="text" name="jc_producetime" /></p>
      <p>实车行驶里程:<input type="text" name="jc_licheng" /></p>
      <p>已安装原厂选装件:<input type="text" name="jc_xzj_installed" /></p>
      <p>已备齐原厂选装件:<input type="text" name="jc_xzj_installation" /></p>
      <p>交车文件:<input type="text" name="jc_other_file" /></p>
      <p>随车工具:<input type="text" name="jc_other_tool" /></p>
      <p><input type="submit" class="btn btn-default" value="保存提交" /></p>
    </form>
  </div>
@endsection

