@extends('_layout.base_usercenter')
@section('css')

@endsection

@section('nav')
@endsection
@section('content')
<div class="box">
                         
                        <h2 class="title psr">
                            <span>我提交的特殊文件</span>
                            <a  class="title-right psa juhuang" href="/user/memberFile/add/">添加</a>
                        </h2>
                        <div class="content-wapper">
                          @if(count($fileList)>0)
                            <table class="tbl">
                                <tr>
                                    <th>文件名称</th>
                                    <th>承诺上牌地区</th>
                                    <th>提交时间</th>
                                    <th>文件状态</th>
                                    <th>操作</th>
                                </tr>
                                @foreach($fileList as $k =>$v)
                                <tr>
                                    <td>
                                        <p class="p fs14">{{$v['title']}}</p>
                                    </td>
                                    <td>
                                        <p class="p fs14">{{$v['area']}}</p>
                                    </td>
                                    <td>
                                        <p class="p fs14">{{$v['creattime']}}</p>
                                    </td>
                                    <td>
                                        <p class="p fs14">
                                        <?php 
                                        if($v['status']==0){
                                        	if(empty($v['reason'])){
                                        		echo "正在审核";
                                        	}else{
                                        		echo "审核失败";
                                        	}
                                        	
                                        }else{
                                        	echo "审核通过";
                                        }?></p>
                                    </td>
                                    <td>
                                        <a href="/user/memberFile/view/{{$v['id']}}" class="juhuang">查看</a>
                                        <a href="/user/memberFile/edit/{{$v['id']}}"  class="juhuang">修改</a>
                                        <a href="/user/memberFile/del/{{$v['id']}}"  class="juhuang">删除</a>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            @else
                            
                            <br><br><br><br><br>

                            <p class="tac">没有提交的特殊文件,显示</p>
                            <p class="tac">您尚未提交过特殊的文件！<a class="juhuang" href="/user/memberFile/add/">去添加>></a></p>
                            
                            @endif
                            
                            
							                            
                         </div>  
                    </div>
@endsection           

@section('js')
	 <script type="text/javascript">
        seajs.use(["module/user/user", "module/common/common", "bt"]);
	</script>
@endsection