    @if($edit_result['editCarModel'] == 'Y')
                        <table class="tbl">
                            <tr>
                                <td><p class="tac fs14"><b>项目</b></p></td>
                                <td><p class="tac fs14"><b>原约定</b></p></td>
                                <td><p class="tac fs14"><b>现改为</b></p></td>
                            </tr>
    @if($edit_result['editcarinfo'])
    <?php $info = $edit_result['editcarinfo'];?>
    @if($info['body']['old_body_color'] != $info['body']['body_color'] && $info['body']['body_color'])
                            <tr>
                                <td><p class="tac fs14">车身颜色</p></td>
                                <td><p class="tac fs14">{{$info['body']['old_body_color']}}</p></td>
                                <td><p class="tac fs14">{{$info['body']['body_color']}}</p></td>
                            </tr>
    @endif
    @if($info['inter']['old_interior_color'] != $info['inter']['interior_color'] && $info['inter']['interior_color'])
                            <tr>
                                <td><p class="tac fs14">内饰颜色</p></td>
                                <td><p class="tac fs14">{{$info['inter']['old_interior_color']}}</p></td>
                                <td><p class="tac fs14">{{$info['inter']['interior_color']}}</p></td>
                            </tr>
    @endif
    @if($info['licheng']['old_licheng'] != $info['licheng']['licheng'] && $info['licheng']['licheng'])
                            <tr>
                                <td><p class="tac fs14">行驶里程</p></td>
                                <td><p class="tac fs14">{{$info['licheng']['old_licheng']}}</p></td>
                                <td><p class="tac fs14">{{$info['licheng']['licheng']}}</p></td>
                            </tr>
    @endif
    @if($info['jiaoche']['old_jiaoche_at'] != $info['jiaoche']['jiaoche_at'] && $info['jiaoche']['jiaoche_at'])
                            <tr>
                                <td><p class="tac fs14">交车时限</p></td>
                                <td><p class="tac fs14">{{$info['jiaoche']['old_jiaoche_at']}}</p></td>
                                <td><p class="tac fs14">{{$info['jiaoche']['jiaoche_at']}}</p></td>
                            </tr>
    @endif
    @if(($info['year']['old_year'] != $info['year']['year'] || $info['month']['old_month'] != $info['month']['month']) && ($info['year']['year'] || $info['month']['month']))
                            <tr>
                                <td><p class="tac fs14">出厂年月</p></td>
                                <td><p class="tac fs14">
                                {{$info['year']['old_year'].'年'.$info['month']['old_month'].'月'}}
                                </p></td>
                                <td><p class="tac fs14">
                                  {{$info['year']['year'].$info['month']['month']}}
                                </p></td>
                            </tr>
    @endif
@endif
    @if($edit_result['editxzj'])
                @foreach($edit_result['editxzj'] as $xzj)
                    @if($xzj['old_num'] != $xzj['num'])
                                <tr>
                                <td><p class="tac fs14">{{$xzj['xzj_title']}}</p></td>
                                <td><p class="tac fs14">{{$xzj['old_num']}}</p></td>
                                <td><p class="tac fs14">{{$xzj['num']}}</p></td>
                                </tr>
                     @endif
                @endforeach
    @endif
        @if($edit_result['editzengpin'])
                @foreach($edit_result['editzengpin'] as $zengpin)
                    @if($zengpin['old_num'] != $zengpin['num'])
                                <tr>
                                <td><p class="tac fs14">{{$zengpin['zp_title']}}</p></td>
                                <td><p class="tac fs14">{{$zengpin['old_num']}}</p></td>
                                <td><p class="tac fs14">{{$zengpin['num']}}</p></td>
                                </tr>
                     @endif
                @endforeach
        @endif
                        </table>
@endif