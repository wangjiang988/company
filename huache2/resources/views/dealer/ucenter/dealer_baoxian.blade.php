@extends('_layout.base_dealercenter')
@section('css')

@endsection

@section('nav')

@endsection

@section('content')
              
 
    <form action="newOfferings">
        <div class="content-wapper ">
           <h4 class="title weighttitle"><span>保险公司</span></h4>
           <div class="m-t-10"></div>
          
           <table class="tbl custom-info-tbl" style="width: 60%;">
             <tbody>
               <tr>
                   <th class="tac">保险公司名称</th>
                   <th class="last">理赔范围</th>
               </tr> 
               @if(count($data)>0)
               @foreach ($data as $da)
               <tr>
                   <td class="tac"><span>{{$da->title}}</span></td>
                   <td class="tac"><span>
                   @if($da->bx_is_quanguo == 1)
                   全国
                   @elseif($da->bx_is_quanguo == 0)
                   本地
                   @endif
                   </span></td>
               </tr>
               @endforeach
               @endif
             </tbody>
           </table>
           
           <div class="m-t-200"></div>
                
        </div>
        
    </form>
 
    <div class="box" ms-include-src="footernew"></div>
@endsection

@section('js')
    
    <script type="text/javascript">
        seajs.use(["module/custom/custom_admin","module/custom/custom.admin.jquery", "module/common/common", "bt"],function(a,b,c){

        });
    </script>

@endsection


