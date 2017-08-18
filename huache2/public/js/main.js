
    //require("al");
    //require("jq");
    
     

avalon.define("model", function (vm) {
    console.log(1111111111);
    vm.info = "哎哟 我只是做个测试而已！！！";
    vm.show = function () {
        document.write("啊！！轻点！！");
    }

    vm.arr = ["llm", "jack", 25, "rose"]

    //el: 不一定叫这个名字，比如说ms-each-item，它就变成item了。默认为el。指向当前元素。
    //$first: 判定是否为监控数组的第一个元素
    //$last: 判定是否为监控数组的最后一个元素
    //$index: 得到当前元素的索引值
    //$outer: 得到外围循环的那个元素。
    //$remove：这是一个方法，用于移除此元素
    //可以通过data-repeat-rendered, data-each-rendered来指定这些元素都插入DOM被渲染了后执行的回调
    //当循环对象为二位数组的时候可以用ms-repeat-elem 做个双重循环
    //<tr ms-repeat-elem="double_">
    //    <td ms-repeat="elem">{{el}}</td>
    //</tr>
    vm.list = [
        { name: "llm", age: "28" },
        { name: "rose", age: "25" },
        {
            name: "zhenzhen", age: "1", family: {
                fname:"llm",fage:"51"
            }
        }
    ]
    var i = 0;
    //整个repeat执行完毕后回调一次
    //action 参数的值为 add, del, move, index,clear
    vm.callback = function (action) {
        //console.log(++i + "" + action);
    }
    
    $("div[ms-controller='model'] div:eq(0)").load("./include/left.html")

})

 
    
/*
console.log("xx47215冯绍峰21545斯蒂芬".slice(0,10));
var o = {}; // 创建一个新对象
Object.defineProperty(o, "a", {
    value: 37,
    writable: false
});

console.log(o.a); // 打印 37
o.a = 25; // 没有错误抛出（在严格模式下会抛出，即使之前已经有相同的值）
console.log(o.a); // 打印 37， 赋值不起作用。


var o = {};
Object.defineProperty(o, "a", { value: 1, enumerable: true });
Object.defineProperty(o, "b", { value: 2, enumerable: false });
Object.defineProperty(o, "c", { value: 3 }); // enumerable defaults to false
o.d = 4; // 如果使用直接赋值的方式创建对象的属性，则这个属性的enumerable为true

for (var i in o) {
    console.log(i);
}
// 打印 'a' 和 'd' (in undefined order)

console.log(Object.keys(o)); // ["a", "d"]

console.log(o.propertyIsEnumerable('a')); // true
console.log(o.propertyIsEnumerable('b')); // false
console.log(o.propertyIsEnumerable('c')); // false

*/