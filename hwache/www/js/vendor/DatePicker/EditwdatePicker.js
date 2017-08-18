var timeadd;
$(function() {
var g=$("#realInp").val();
var b=$("#realInp2").val();
// 获取当前时间，填充
//时间格式处理.没有0 的自己加一个
function stime(str) {
if (str < 10) {
return "0" + str.toString();
} else {
return str;
}
}
var dateTime = new Date();
var yy = dateTime.getFullYear();
var MM = dateTime.getMonth() + 1; // 因为1月这个方法返回为0，所以加1 15:54
var dd = dateTime.getDate();
var hh = dateTime.getHours();
var mm = dateTime.getMinutes();
$("#realInp")[0].value = yy + "-" + stime(MM) + "-" + stime(dd);
$("#realInp2")[0].value = yy + "-" + stime(MM) + "-" +stime(dd +1);
// $("#reTime")[0].value =stime(hh) + ":" +stime(mm)+"分";
$("#realInp").focus(function() {
WdatePicker({
dateFmt:"yyyy-MM-dd",
realDateFmt:"yyyy-MM-dd",
minDate: '%y-%M-%d' ,
onpicked:calA
});
});
$("#realInp2").focus(function() {
WdatePicker({
dateFmt:"yyyy-MM-dd",
realDateFmt:"yyyy-MM-dd",
minDate: timeadd,
onpicked:calB
});
});
function calA(){
if ( g< b) {
if (g == "" && g == null) {
alert("请选择取时间");
return;
} else if (b == "" && b == null) {
alert("请选择还时间");
return;
}
}
timeadd = new Date($("#realInp").val().replace(/-/g, "/"));
timeadd = new Date(timeadd.getTime() + 1 * 24 * 60 * 60 * 1000);
timeadd = timeadd.getFullYear() + "-" + stime((timeadd.getMonth() + 1)) + "-" + stime(timeadd.getDate());
$("#realInp2").val(timeadd);
}
function calB() {
if ( g< b) {
if (g == "" && g == null) {
alert("请选择取时间");
return;
} else if (b == "" && b == null) {
alert("请选择还时间");
return;
} }
if (g > b) {
alert("取时间不能大于还时间");
}}
});