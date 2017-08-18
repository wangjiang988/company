<div class="container">
  @foreach ($moneyList as $v)
    @if($v->pay_status == 0)
    <div><a href="{{ route('user.money.topup')."/{$v->serial_id}" }}">{{ $v->money }}</a></div>
    @elseif($v->pay_status == 1)
    <div>{{ $v->money }}, 已完成</div>
    @elseif($v->pay_status == 2)
      <div>{{ $v->money }}, 已取消</div>
    @endif
  @endforeach
</div>

{!! $moneyList->render() !!}
