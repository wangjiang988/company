<table class="noborder-tbl wp100 ml100">
    <tr>
        <td width="250" align="right" valign="middle">
            <p class="mt10 weight">您心仪座驾的生产国别：</p>
        </td>
        <td width="500" >
            @if($find->country_car==0)国产@else进口@endif
        </td>
    </tr>
    <tr>
        <td width="" align="right" valign="middle">
            <p class="mt10 weight">文件名称：</p>
        </td>
        <td width="" >
            {{$find->file_name}}
        </td>
    </tr>
    <tr>
        <td width="" align="right" valign="middle">
            <p class="mt10 weight">上牌地区：</p>
        </td>
        <td width="" >
            {{getProvinceCityNames($find->area_id)}}
        </td>
    </tr>
    <tr>
        <td width="150" align="right" valign="middle">
            <p class="mt10 weight">车辆用途：</p>
        </td>
        <td width="" >
            {{$find->use_car}}
        </td>
    </tr>
    <tr>
        <td width="" align="right" valign="middle">
            <p class="mt10 weight">上牌（注册登记）车主身份类别：</p>
        </td>
        <td width="" >
            @if($find->licence_user_type ==2)
                上牌地本市户籍居民
            @else
                {{$find->licence_other}}
            @endif
        </td>
    </tr>
    
        <tr>
            <td width="" align="right" valign="middle">
                <p class="mt10 weight">文件式样：</p>
            </td>
            <td width="" >
                @if($find->file_url >0)
                <a href="{{getImgidToImgurl($find->file_url)}}" target="_blank" class="blue tdu">图片</a>
                @else 无
                @endif
            </td>
        </tr>
    
    <tr>
        <td colspan="2">
            <p class="mt10"><br>
                <a href="{{ route('special.list') }}" class="btn btn-s-md btn-danger fs14 sure ml200">返 回</a>
            </p>
        </td>
    </tr>
</table>