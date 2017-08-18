<?php

namespace App\Http\Controllers;

use App\Models\HcUserAccount;
//use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Exception;

use App\Repositories\Contracts\HcUserRechargeRepositoryInterface;
use App\Repositories\Contracts\HcUserAccountLogRepositoryInterface;
use App\Repositories\Contracts\HcUserAccountRepositoryInterface;
use App\Repositories\Contracts\HcUserConsumeRepositoryInterface;
use App\Http\Requests\XinHaoRequest as Request;

use Facades\App\Com\Hwache\Jiaxinbao\Account as Jxb;
use Facades\App\Com\Hwache\Vouchers\DJQ;
use Facades\App\Repositories\Contracts\HcAccountLogRepositoryInterface as SysAccount;

class HomeController extends Controller
{
    protected $recharge,$consume,$log,$account,$jxb,$djq;
    public function __construct(HcUserRechargeRepositoryInterface $userRechargeRepository,
                                HcUserConsumeRepositoryInterface $hcUserConsumeRepository,
                                HcUserAccountLogRepositoryInterface $userAccountLogRepository,
                                HcUserAccountRepositoryInterface $userAccountRepository
    )

    {
        $this->recharge = $userRechargeRepository;
        $this->consume  = $hcUserConsumeRepository;
        $this->log      = $userAccountLogRepository;
        $this->account  = $userAccountRepository;
    }
    public function showTest(Request $request,$type)
    {
        Jxb::addUserJxb(748, 499 - $money, $voucher, 5);


        //Jxb::addUserJxb(591);
        /*$list  = DJQ::getReleaseFind(100000);
        dd(Account::getOrderInfo(13)->toArray());*/
        /*$ShowType = 'show'.$type;
        $this->$ShowType($request);*/
        return view('welcome');
    }

    public function showXinhao(Request $request)
    {
        $request->createDbBingData();
    }
    /**
     * 测试Carbon 日期时间处理api
     */
    private function showCarbon()
    {
        //addYear,addMonth,addHour,addMinute,addSecond (sub....)
        Carbon::now()->addHour();
        Carbon::parse()->addDay()->toDateTimeString();//addDay 增加一天的时间
        Carbon::parse()->subDay()->toDateString();//减少一天的时间
        Carbon::now();//现在的时间
        Carbon::today()->toDateString();//今天的时间
        Carbon::now()->format('Y-m-d');//格式话输出
        Carbon::yesterday();//上一天的时间
        Carbon::tomorrow();//下一天的时间
        dd(Carbon::now()->format('Y-m-').'01');
    }

    /**
     * 测试队列
     * @param Request $request
     */
    private function showJobQueue(Request $request)
    {
        //测试队列
        dispatch(new \App\Jobs\SendReminderEmail($request->user(),'imcheney70@sina.com'));
    }

    /**
     * 测试集合
     */
    private function showCollection()
    {
        $collection = collect([1, 2, 3]);
        // 返回该集合所代表的底层数组：
        $collection->all();
        // 返回集合中所有项目的平均值：
        $collection->avg();
        // 将集合拆成多个给定大小的较小集合：
        $collection->chunk(4);
        // 将多个数组组成的集合折成单一数组集合：
        $collection->collapse();
        // 用来判断该集合是否含有指定的项目：
        $collection->contains('New York');
        // 返回该集合内的项目总数：
        $collection->count();
        // 遍历集合中的项目，并将之传入给定的回调函数：
        $collection = $collection->each(function ($item, $key) {
        });
        // 会创建一个包含第 n 个元素的新集合：
        $collection->every(4);
        // 传递偏移值作为第二个参数：
        $collection->every(4, 1);
        // 返回集合中排除指定键的所有项目：
        $collection->except(['price', 'discount']);
        // 以给定的回调函数筛选集合，只留下那些通过判断测试的项目：
        $filtered = $collection->filter(function ($item) {
            return $item > 2;
        });
        // 返回集合中，第一个通过给定测试的元素：
        collect([1, 2, 3, 4])->first(function ($key, $value) {
            return $value > 2;
        });
        // 将多维集合转为一维集合：
        $flattened = $collection->flatten();
        // 将集合中的键和对应的数值进行互换：
        $flipped = $collection->flip();
        // 以键自集合移除掉一个项目：
        $collection->forget('name');
        // 返回含有可以用来在给定页码显示项目的新集合：
        $chunk = $collection->forPage(2, 3);
        // 返回给定键的项目。如果该键不存在，则返回 null：
        $value = $collection->get('name');
        // 根据给定的键替集合内的项目分组：
        $grouped = $collection->groupBy('account_id');
        // 用来确认集合中是否含有给定的键：
        $collection->has('email');
        // 用来连接集合中的项目
        $collection->implode('product', ', ');
        // 移除任何给定数组或集合内所没有的数值：
        $intersect = $collection->intersect(['Desk', 'Chair', 'Bookcase']);
        // 假如集合是空的，isEmpty 方法会返回 true：
        collect([])->isEmpty();
        // 以给定键的值作为集合项目的键：
        $keyed = $collection->keyBy('product_id');
        // 传入回调函数，该函数会返回集合的键的值：
        $keyed = $collection->keyBy(function ($item) {
            return strtoupper($item['product_id']);
        });
        // 返回该集合所有的键：
        $keys = $collection->keys();
        // 返回集合中，最后一个通过给定测试的元素：
        $collection->last();
        // 遍历整个集合并将每一个数值传入给定的回调函数：
        $multiplied = $collection->map(function ($item, $key) {
            return $item * 2;
        });
        // 返回给定键的最大值：
        $max = collect([['foo' => 10], ['foo' => 20]])->max('foo');
        $max = collect([1, 2, 3, 4, 5])->max();
        // 将给定的数组合并进集合：
        $merged = $collection->merge(['price' => 100, 'discount' => false]);
        // 返回给定键的最小值：
        $min = collect([['foo' => 10], ['foo' => 20]])->min('foo');
        $min = collect([1, 2, 3, 4, 5])->min();
        // 返回集合中指定键的所有项目：
        $filtered = $collection->only(['product_id', 'name']);
        // 获取所有集合中给定键的值：
        $plucked = $collection->pluck('name');
        // 移除并返回集合最后一个项目：
        $collection->pop();
        // 在集合前面增加一个项目：
        $collection->prepend(0);
        // 传递第二个参数来设置前置项目的键：
        $collection->prepend(0, 'zero');
        // 以键从集合中移除并返回一个项目：
        $collection->pull('name');
        // 附加一个项目到集合后面：
        $collection->push(5);
        // put 在集合内设置一个给定键和数值：
        $collection->put('price', 100);
        // 从集合中随机返回一个项目：
        $collection->random();
        // 传入一个整数到 random。如果该整数大于 1，则会返回一个集合：
        $random = $collection->random(3);
        // 会将每次迭代的结果传入到下一次迭代：
        $total = $collection->reduce(function ($carry, $item) {
            return $carry + $item;
        });
        // 以给定的回调函数筛选集合：
        $filtered = $collection->reject(function ($item) {
            return $item > 2;
        });
        // 反转集合内项目的顺序：
        $reversed = $collection->reverse();
        // 在集合内搜索给定的数值并返回找到的键：
        $collection->search(4);
        // 移除并返回集合的第一个项目：
        $collection->shift();
        // 随机排序集合的项目：
        $shuffled = $collection->shuffle();
        // 返回集合从给定索引开始的一部分切片：
        $slice = $collection->slice(4);
        // 对集合排序：
        $sorted = $collection->sort();
        // 以给定的键排序集合：
        $sorted = $collection->sortBy('price');
        // 移除并返回从指定的索引开始的一小切片项目：
        $chunk = $collection->splice(2);
        // 返回集合内所有项目的总和：
        collect([1, 2, 3, 4, 5])->sum();
        // 返回有着指定数量项目的集合：
        $chunk = $collection->take(3);
        // 将集合转换成纯 PHP 数组：
        $collection->toArray();
        // 将集合转换成 JSON：
        $collection->toJson();
        // 遍历集合并对集合内每一个项目调用给定的回调函数：
        $collection->transform(function ($item, $key) {
            return $item * 2;
        });
        // 返回集合中所有唯一的项目：
        $unique = $collection->unique();
        // 返回键重设为连续整数的的新集合：
        $values = $collection->values();
        // 以一对给定的键／数值筛选集合：
        $filtered = $collection->where('price', 100);
        // 将集合与给定数组同样索引的值合并在一起：
        $zipped = $collection->zip([100, 200]);
    }
    /**
     * 测试数据库锁
     */
    private function showLock()
    {
        //悲观锁
        $userList = User::find(3)->UserOrder()->lockForUpdate()->get();
        dd($userList);
        DB::beginTransaction();
        try{
            $res = DB::table('users')->where('id',3)->update(['login_num'=>8]);
            $res = DB::table('users')->where('id',1)->update(['login_num'=>8]);
            if($res >= 0){
                throw new Exception('数据错误！！！');
            }
            /* 不提交事务是不能更新数据的
            DB::commit();*/
        }catch (Exception $e){
            /*DB::rollBack();
            echo $e->getMessage().'<br />';*/
        }
        //未提交事务查询结果实际上并未更新
        $userList = DB::table('users')->where('id', 1)->orderBy('id')->first();
        dd($userList->login_num);
    }
}
