<?php defined('InHG') or exit('Access Invalid!');?>

<style type="text/css">
	table{

   border-collapse:collapse;
}
#two {
	margin: 0 auto;
	width: 98%
}

   #two td{
	border:1px solid gray;
   }
   #two thead{
   	margin:20px;
   }

   ul{
   	margin-left: 60px;
   }

   ul li{
   	float:left;
   	padding:13px;
   	font-weight: bolder;
   }
   th{
   	width: 0px;
   }
   #distance{
    margin-left: 20px;
    font-size:13px;
   }
   #distance span {
    padding:0 15px;
   }
   #area{
    width: 420px;
    background: white;
    display: none;
    border:1px solid RGB(120,120,120);
   }
   #area select{
    margin:10px;
   }
   #area b{
    margin-left:13px;
   }
   #area input{
    padding:5px 20px;
    margin-top: 5px;
    margin-left:90px;
   }
   .btn{
    width: 70px;
    height: 30px;
    border-radius: 5px;
    border: 1px solid #ccc;
    padding: 5px 0px;
    text-align: center;
}
.btnCal{
    background-color: white;
}
.btnSub{
    background-color: #CFEEF0;
}
.btnSub:active{
    background-color: white;
}
.btnCal:active{
    color: white;
    background-color: #CFEEF0;
}


.fileBox{left-margin:50px;}
.fileInputP{display:inline-block;width:200px;height:30px;border-radius:5px;overflow:hidden;position:relative;}
.fileInputP i{display:inline-block;width:200px;height:30px;color:#fff;background:#7d8f33;text-align:center;line-height:30px;}
#fileInput{position:absolute;left:0;top:0;right:0;bottom:0;opacity:0;}
#fileSpan{display:inline-block;width:300px;height:150px;border:2px dashed #ccc;text-align:center;line-height:150px;}

.fileList_parent{margin:5px 5px 5px 0px;display:none;}
.fileList_parent th{background:#dadada;font-weight:bold;}
.fileList_parent th,.fileList_parent td{padding:1px 2px;}
.fileList tr:nth-of-type(2n){background:#dadada;}

.progressParent{width:200px;height:10px;border-radius:5px;background:#ccc;overflow:hidden;position:relative;}
.progress{width:0%;height:20px;background:#7d8f33;}
.progressNum{display:inline-block;width:100%;height:20px;text-align:center;line-height:20px;color:#fff;position:absolute;left:0;top:0;}

#fileBtn{margin-left:50px;}


</style>

<script>
$(function(){

  //元素
  var oFileBox = $(".fileBox");         //选择文件父级盒子
  var oFileInput = $("#fileInput");       //选择文件按钮
  var oFileSpan = $("#fileSpan");         //选择文件框

  var oFileList_parent = $(".fileList_parent"); //表格
  var oFileList = $(".fileList");         //表格tbody
  var oFileBtn = $("#fileBtn");         //上传按钮

  var flieList = [];                //数据，为一个复合数组
  var sizeObj = [];               //存放每个文件大小的数组，用来比较去重


  //拖拽外部文件，进入目标元素触发
  oFileSpan.on("dragenter",function(){
    $(this).text("可以释放鼠标了！").css("background","#ccc");
  });

  //拖拽外部文件，进入目标、离开目标之间，连续触发
  oFileSpan.on("dragover",function(){
    return false;
  });

  //拖拽外部文件，离开目标元素触发
  oFileSpan.on("dragleave",function(){
    $(this).text("或者将文件拖到此处").css("background","none");
  });

  //拖拽外部文件，在目标元素上释放鼠标触发
  oFileSpan.on("drop",function(ev){
    var fs = ev.originalEvent.dataTransfer.files;
    analysisList(fs);   //解析列表函数
    $(this).text("或者将文件拖到此处").css("background","none");
    return false;
  });

  //点击选择文件按钮选文件
  oFileInput.on("change",function(){
    analysisList(this.files);
  })

  //解析列表函数
  function analysisList(obj){
    //如果没有文件
    if( obj.length<1 ){
      return false;
    }

    for( var i=0;i<obj.length;i++ ){

      var fileObj = obj[i];   //单个文件
      var name = fileObj.name;  //文件名
      var size = fileObj.size;  //文件大小
      var type = fileType(name);  //文件类型，获取的是文件的后缀
      //文件大于100M，就不上传
      if( size > 1024*1024*1024 || size == 0 ){
        alert('“'+ name +'”超过了100M，不能上传');
        continue;
      }

      //文件类型不为这三种，就不上传
      if( ("jpg/png/jpeg/mp3").indexOf(type) == -1 ){
        alert('“'+ name +'”文件类型不对，不能上传');
        continue;
      }

      //把文件大小放到一个数组中，然后再去比较，如果有比较上的，就认为重复了，不能上传
      if( sizeObj.indexOf(size) != -1 ){
        alert('“'+ name +'”已经选择，不能重复上传');
        continue;
      }

      //给json对象添加内容，得到选择的文件的数据
      var itemArr = [fileObj,name,size,type]; //文件，文件名，文件大小，文件类型
      flieList.push(itemArr);

      //把这个文件的大小放进数组中
      sizeObj.push(size);

    }

    //console.log(flieList)
    //console.log(sizeObj)
    createList()        //生成列表
    oFileList_parent.show();  //表格显示
   // oFileBtn.show();      //上传按钮显示
  };


  //生成列表
  function createList(){
    oFileList.empty();          //先清空元素内容
    for( var i=0;i<flieList.length;i++ ){

      if (i == 5) {
        alert('一次操作不超过5个文件!!');
        return false;
      }

      var fileData = flieList[i];   //flieList数组中的某一个
      var objData = fileData[0];    //文件
      var name = fileData[1];     //文件名
      var size = fileData[2];     //文件大小
      var type = fileData[3];     //文件类型
      var volume = bytesToSize(size); //文件大小格式化


      var oTr = $("<tr></tr>");
      var str = '<td><cite title="'+ name +'">'+ name +'</cite></td>';
      str += '<td>'+ type +'</td>';
      str += '<td>'+ volume +'</td>';
      str += '<td>';
      str += '<div class="progressParent">';
      str += '<p class="progress"></p>';
      str += '<span class="progressNum">0%</span>';
      str += '</div>';
      str += '</td>';
      str += '<td><a href="javascript:;" class="operation">删除</a></td>';

      oTr.html(str);
      oTr.appendTo( oFileList );
    }
  }

  //删除表格行
  oFileList.on("click","a.operation",function(){
    var oTr = $(this).parents("tr");
    var index = oTr.index();
    oTr.remove();   //删除这一行
    flieList.splice(index,1); //删除数据
    sizeObj.splice(index,1);  //删除文件大小数组中的项

    //console.log(flieList);
    //console.log(sizeObj);

  });


  //上传
  oFileBtn.on("click",function(){
    if($('#contents').val() == ''){
       $("#errors").css('display','block');
       return false;
    }
    oFileBtn.off();
    var tr = oFileList.find("tr");    //获取所有tr列表
    var successNum = 0;         //已上传成功的数目
    oFileList.off();          //取消删除事件
    oFileBox.slideUp();         //隐藏输入框
    oFileList.find("a.operation").text("等待上传");

    if (tr.length == 0) {
      var dealer = $('input[name=dealer_id]').val();
      var content = $('#contents').val();
      $.ajax({
        url: 'index.php?act=commons_manage&op=add_upload',
        type: 'POST',
        dataType: 'json',
        data: {
          dealer_id: dealer,
          contents: content,
          sign : $('input[name=sign]').val(),
        },
      })
      .done(function() {
        location.reload();
      });

      }



    for( var i=0;i<tr.length;i++ ){
      uploadFn(tr.eq(i),i);   //参数为当前项，下标
    }


    function uploadFn(obj,i){
      var formData = new FormData();
      var arrNow = flieList[i];           //获取数据数组的当前项

      // 从当前项中获取上传文件，放到 formData对象里面，formData参数以key name的方式
      var result = arrNow[0];             //数据
      formData.append("imageFile" , result);

      var name = arrNow[1];             //文件名
      formData.append("name" , name);

      formData.append('dealer_id',$('input[name=dealer_id]').val()); //带上经销商id

      formData.append('num',i+1);

      //标识符产生
      var sign = $('input[name=sign]').val();
      formData.append('sign',sign);

      if (i == 0) {
        formData.append('contents',$('#contents').val());
      }

      var progress = obj.find(".progress");     //上传进度背景元素
      var progressNum = obj.find(".progressNum");   //上传进度元素文字
      var oOperation = obj.find("a.operation");   //按钮

      oOperation.text("正在上传");              //改变操作按钮
      oOperation.off();
      var request = $.ajax({
        type: "POST",
        //url: "../more/cModifyImageAction.go",
        url: "index.php?act=commons_manage&op=add_upload",
        data: formData,     //这里上传的数据使用了formData 对象
        processData : false,  //必须false才会自动加上正确的Content-Type
        contentType : false,
        async: false,

        //这里我们先拿到jQuery产生的XMLHttpRequest对象，为其增加 progress 事件绑定，然后再返回交给ajax使用
        xhr: function(){
          var xhr = $.ajaxSettings.xhr();
          if(onprogress && xhr.upload) {
            xhr.upload.addEventListener("progress" , onprogress, false);　
            return xhr;
          }
        },

        //上传成功后回调
        success: function(data){
          oOperation.text("成功");
          successNum++;
         // console.log(successNum);
          if(successNum == tr.length){
            location.reload();
            //open("http://www.baidu.com","_self"); //如果全部传成功了，跳转
          }
        },

        //上传失败后回调
        error: function(){
          oOperation.text("重传");
          oOperation.on("click",function(){
            request.abort();    //终止本次
            uploadFn(obj,i);
          });
        }

      });


      //侦查附件上传情况 ,这个方法大概0.05-0.1秒执行一次
      function onprogress(evt){
        var loaded = evt.loaded;  //已经上传大小情况
        var tot = evt.total;    //附件总大小
        var per = Math.floor(100*loaded/tot);  //已经上传的百分比
        progressNum.html( per +"%" );
        progress.css("width" , per +"%");
      }

    }


  });


})


//字节大小转换，参数为b
function bytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB'];
    if (bytes == 0) return 'n/a';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
};

//通过文件名，返回文件的后缀名
function fileType(name){
  var nameArr = name.split(".");
  return nameArr[nameArr.length-1].toLowerCase();
}


</script>





<ul id="fiexd">
	<li><a href="" style="padding:6px;background:gray;color:white;">主要基本资料</a></li>
</ul>
<table class="table tb-type2" style="margin:15px;">
  <tbody>
  <tr>
    <td>UBS编号：</td>
    <td><?php echo $output['dealer']['id']; ?></td>
    <td>审核状态：</td>
    <td>
    <?php
    if($output['dealer']['dl_status'] == 1) {
            echo "待审核";
          }
    if($output['dealer']['dl_status'] == 2) {
             echo "审核通过";
          }
    if($output['dealer']['dl_status'] == 4) {
       echo "审核不通过";
    }
    ?></td>
    <td></td>
    <td></td>

  </tr>
  <tr>
  	<td align="left" style="text-align:left;">用户名：</td>
	<td align="left" style="text-align:left"><?php echo $output['users']['member_name']; ?></td>
	<td align="left" style="text-align:left">用户姓名/手机号：</td>
	<td align="left" style="text-align:left"><?php echo $output['users']['member_truename'].'/'.$output['users']['member_mobile']; ?></td>
  </tr>
	<tr>
  	<td align="left" style="text-align:left;">品牌：</td>
	<td align="left" style="text-align:left"><?php echo $output['dealer']['gc_name']; ?></td>
	<td align="left" style="text-align:left">归属地区：</td>
	<td align="left" style="text-align:left"><?php echo $output['dealers']['d_areainfo']; ?></td>
	<td align="left" style="text-align:left">经销商名称：</td>
	<td align="left" style="text-align:left"><?php echo $output['dealers']['d_name'] ?></td>
	</tr>
	<tr>
  	<td align="left" style="text-align:left;">经销商编号：</td>
	<td align="left" style="text-align:left"><?php echo $output['dealer']['d_id']; ?></td>
	<td align="left" style="text-align:left">类别：</td>
	<td align="left" style="text-align:left">4S</td>
	<td align="left" style="text-align:left">经销商简称：</td>
	<td align="left" style="text-align:left"><?php echo $output['dealer']['d_shortname']; ?></td>
	</tr>
	<tr>
  	<td align="left" style="text-align:left;">营业地点：</td>
	<td align="left" style="text-align:left"><?php echo $output['dealers']['d_yy_place']; ?></td>
	<td align="left" style="text-align:left">交车地点：</td>
	<td align="left" style="text-align:left"><?php echo $output['dealers']['d_jc_place']; ?></td>
	</tr>
	<tr>
  	<td align="left" style="text-align:left;">开户行：</td>
	<td align="left" style="text-align:left"><?php echo $output['dealer']['dl_bank_addr']; ?></td>
	<td align="left" style="text-align:left">账号：</td>
	<td align="left" style="text-align:left"><?php echo $output['dealer']['dl_bank_account']; ?> </td>
	<td align="left" style="text-align:left">统一社会信用代码</td>
	<td align="left" style="text-align:left"><?php echo $output['dealer']['dl_code'];?></td>
	</tr>
  </tbody>
</table>
<h3 style="color:gray; margin:15px;">竞争分析:</h3>
<table class="table tb-type2" id="two">
  <tbody>
  <tr>
  	<td width="25%">地区</td>
  	<td width="35%">经销商名称</td>
  	<td width="40%">营业地点</td>
  </tr>
  <?php foreach($output['contend'] as $contend) { ?>
	<tr>
  	 <td><?php echo $contend['d_areainfo'];?></td>
  	<td><?php echo $contend['d_name'];?></td>
  	<td><?php echo $contend['d_yy_place'];?></td>
	</tr>
  <?php } ?>

  </tbody>
</table>
<h3 style="color:gray; margin:15px;">平台审核:</h3>
<div id="distance"><b>车源范围设定:</b>
<?php if(count($output['carscop']) == 0) { ?>
<span><a id="add_area_add">添加</a></span>
<?php } else {?>
<span id="verify">
<?php echo $output['carscop']['province1_name'].$output['carscop']['area1_name'].','.
           $output['carscop']['province2_name'].$output['carscop']['area2_name'].','.
           $output['carscop']['province3_name'].$output['carscop']['area3_name'];
        ?>
</span>
<span><a id="add_area">修改</a></span>
<?php } ?>
</div>
<table class="table tb-type2" id="two" style="margin:16px;width:60%">
  <tr>
    <td align="center">编号</td>
    <td align="center">车辆范围设定记录</td>
    <td align="center">备注人</td>
    <td align="center">备注时间</td>
  </tr>
  <?php if (count($output['scop_log'])>0) { ?>
    <?php foreach($output['scop_log'] as $key=>$scop_log) { ?>
    <tr>
    <td><?php echo $key+1;?></td>
    <td>
      <?php echo $scop_log['province1_name'].$scop_log['area1_name'].','.
           $scop_log['province2_name'].$scop_log['area2_name'].','.
           $scop_log['province3_name'].$scop_log['area3_name'];
        ?>
    </td>
    <td><?php echo $scop_log['admin_name'];?></td>
    <td><?php echo date('Y-m-d H:i:s',$scop_log['add_time']);?></td>
  </tr>
  <?php }}else { ?>
    <tr>
      <td colspan="4">无</td>
    </tr>
  <?php }?>
</table>
<table class="table tb-type2" id="two" style="margin:16px;width:90%">
  <tr>
    <td width="5%" align="center">编号</td>
    <td width="25%" align="center">备注内容</td>
    <td width="35%" align="center">证据</td>
    <td width="15%" align="center">备注人</td>
    <td width="15%" align="center">备注时间</td>
  </tr>
  <?php if (count($output['evidence_list'])>0) {?>
  <input type="hidden" name="sign" value="<?php echo count($output['evidence_list'])+1;?>"/>
  <?php } else { ?>
    <input type="hidden" name="sign" value="1"/>
  <?php } ?>
  <?php if(count($output['evidence_list'])>0) {?>
  <?php foreach($output['evidence_list'] as $evidence_list) { ?>
  <tr>
    <td align="center"><?php echo $evidence_list['id'];?></td>
    <td><?php echo $evidence_list['contents'];?></td>
    <td align="center">
    <p><a href="<?php echo BASE_EVIDENCE_PATH.'/'.$evidence_list['file_1'];?>" target="_bank"><?php echo $evidence_list['file_1_name'];?></a></p>
    <p><a href="<?php echo BASE_EVIDENCE_PATH.'/'.$evidence_list['file_2'];?>" target="_bank"><?php echo $evidence_list['file_2_name'];?></a></p>
    <p><a href="<?php echo BASE_EVIDENCE_PATH.'/'.$evidence_list['file_3'];?>" target="_bank"><?php echo $evidence_list['file_3_name'];?></a></p>
    <p><a href="<?php echo BASE_EVIDENCE_PATH.'/'.$evidence_list['file_4'];?>" target="_bank"><?php echo $evidence_list['file_4_name'];?></a></p>
    <p><a href="<?php echo BASE_EVIDENCE_PATH.'/'.$evidence_list['file_5'];?>" target="_bank"><?php echo $evidence_list['file_5_name'];?></a></p>
    </td align="center">
    <td align="center"><?php echo $evidence_list['admin_name'];?></td>
    <td align="center"><?php echo date('Y-m-d H:i:s',$evidence_list['add_time']);?></td>
  </tr>
  <?php } }else {?>
  <tr>
    <td colspan="5">无</td>
  </tr>
  <?php }?>
  <tr>
    <td colspan="5"><a id="add_upload">添加备注</a></td>
  </tr>
</table>
<?php if(count($output['verify_log'])>0) {?>
<table class="table tb-type2" id="two" style="margin:16px;width:90%">
  <tr>
    <td align="center">编号</td>
    <td align="center">提交时间</td>
    <td align="center">审核结果</td>
    <td align="center">审核人</td>
    <td align="center">审核时间</td>
  </tr>
  <?php foreach($output['verify_log'] as $verify=>$verify_log) {?>
  <tr>
    <td align="center"><?php echo $verify+1;?></td>
    <td align="center"><?php echo date('Y-m-d H:i:s',$verify_log['dl_add_time']);?></td>
    <td align="center"><?php echo $verify_log['verify_content'];?></td>
    <td align="center"><?php echo $verify_log['admin_name'];?></td>
    <td align="center"><?php echo date('Y-m-d H:i:s',$verify_log['verify_time']);?></td>
  </tr>
  <?php } ?>
</table>
<?php }?>
<div style="margin:20px auto;width:60%">
  <?php if(!in_array($output['dealer']['dl_status'], [2,4])) { ?>
  <button class="btn btnSub" style="width:200px;" id="backs"><b>不通过,退回</b></button>
  <button class="btn btnSub" style="margin:0 40px;width:200px;" id="pass"><b>通过</b></button>
  <?php }?>
  <button class="btn btnSub" style="width:200px" onclick="history.go(-1)"><b>返回</b></button>
  <div style="margin:5px 260px;display:none" id="error_msg"><strong style="color:red;">车源设定不得为空!!</strong></div>
</div>



<div id="area">
<form method="Get" action="index.php" id="form">
   <input type="hidden" name="act" value="commons_manage">
   <input type="hidden" name="op" value="addscope">
<h5 style="color:white;background:RGB(21,160,240);padding:10px;">车源范围</h5>
<?php for($i=1;$i<=3;$i++) {?>
<p>
<b>车源范围<?php echo $i;?>:</b>
<select id="province" name="city_name[<?php echo $i;?>]">
      <?php if($output['carscop']['province'.$i.'_name']){?>
        <option value="<?php echo $output['carscop']['province'.$i.'_name'];?>"><?php echo $output['carscop']['province'.$i.'_name'];?></option>
        <?php }else {?>
      <option value="">----请选择省份----</option>
      <?php }?>
       <?php foreach($output['area'] as $area){?>
       <option value="<?php echo $area['area_name'];?>"><?php echo $area['area_name'];?></option>
       <?php }?>
   </select>
   <select class="city" name="city_id_name[<?php echo $i;?>]">
   <?php if($output['carscop']['area'.$i.'_name']){?>
        <option value="<?php echo $output['carscop']['area'.$i.'_name'];?>"><?php echo $output['carscop']['area'.$i.'_name'];?></option>
        <?php }else {?>
      <option value="">----请选择城市----</option>
      <?php }?>
   </select>
   </p>
   <?php }?>
   <input type="hidden" name="dealer_id" value="<?php echo $output['dealer']['id'];?>">
   <input type="submit" name="" id="submit" value="保存">
   <input type="button" name="" id="close" style="margin-bottom:5px" value="取消">
   <p id="error" style="color:red; margin-left:130px;padding:10px; display:none;"><b>请至少选择一个车源范围</b></p>
</form>
</div>




<div style="border: 1px solid #ccc;width: 600px;min-height: 200px;;margin: auto;background: white; display:none;" id="uplode">
    <div style="background-color: #CFEEF0;padding: 10px 15px;"><span>温馨提示</span></div>
    <div style="padding: 20px 30px;">
        <div>
            <span>请输入备注内容:</span>&nbsp<span>(请选jpg,png,jpeg文件和小于100M的mp3文件)</span>
        </div>
        <div style="padding: 10px 0px;">
            <textarea rows="4" style="width: 100%;" id="contents" name="contents" value=""></textarea>
        </div>
        <div class="fileBox">
    <p class="fileInputP vm">
      <i>上传证据,请选择文件</i>
      <input type="file" multiple id="fileInput" />
    </p>
   <!--  <span id="fileSpan" class="vm">或者将文件拖到此处</span> -->
    <div class="mask"></div>
</div>

<table width="100%" border="1" class="fileList_parent">
  <thead>
    <tr>
      <th>文件名</th>
            <th>类型</th>
            <th>大小</th>
      <th>进度</th>
      <th>操作</th>
    </tr>
  </thead>

  <tbody class="fileList">
    </tbody>

</table>
 <div style="text-align: center;padding: 20px 0px 10px 0px;">
            <button class="btn btnSub" id="fileBtn">提交</button>
            <button class="btn btnCal" style="margin-left: 50px;" id="close">返回</button>
        </div>
        <div style="text-align: center;display:none;" id="errors">
            <span style="font-size: 12px;color: red;"><b>请输入备注内容</b></span>
        </div>
    </div>
</div>

<div style="border: 1px solid #ccc;width: 500px;min-height: 200px;display:none;" id="check">
    <div style="background-color: #CFEEF0;padding: 10px 15px;"><span>温馨提示</span></div>
    <div style="padding: 60px 30px;background:white;">
    <h5 style="color:black;margin-left:170px">确定审核通过吗?</h5>
        <div style="text-align: center;padding: 20px 0px 10px 0px;">
            <button class="btn btnSub" id="audit">确定</button>
            <button class="btn btnCal" style="margin-left: 50px;" id="close" >返回</button>
        </div>
        </div>
</div>

<div style="border: 1px solid #ccc;width: 500px;min-height: 200px;display:none;" id="back">
    <div style="background-color: #CFEEF0;padding: 10px 15px;"><span>温馨提示</span></div>
    <div style="padding: 60px 30px;background:white;">
    <h5 style="color:black;margin-left:170px">确定把该组信息退回吗?</h5>
        <div style="text-align: center;padding: 20px 0px 10px 0px;">
            <button class="btn btnSub" id="click_back">确定</button>
            <button class="btn btnCal" style="margin-left: 50px;" id="close" >返回</button>
        </div>
        </div>
</div>

<div style="border: 1px solid #ccc;width: 500px;min-height: 200px;display:none;" id="car_range">
    <div style="background-color: #CFEEF0;padding: 10px 15px;"><span>温馨提示</span></div>
    <div style="padding: 60px 30px;background:white;">
    <h5 style="color:black;margin-left:170px">确定修改车源范围吗?</h5>
        <div style="text-align: center;padding: 20px 0px 10px 0px;">
            <button class="btn btnSub" id="range_ensure">确定</button>
            <button class="btn btnCal" style="margin-left: 50px;" id="close" >返回</button>
        </div>
        </div>
</div>


<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/easydialog.js" charset="utf-8"></script>
<script type="text/javascript">
$(document).ready(function(){
   $(document).delegate("#province","click",function(){
    var _this = $(this);
    var area_name = _this.find("option:selected").val();
    var _html = '';
     $.ajax({
       url: 'index.php',
       type: 'GET',
       dataType: 'json',
       data: {
               act :　'commons_manage',
               op : 'ajaxarea',
               area_name : area_name
              },
     })
     .done(function(data) {
      $.each(data,function(index, el) {
        _html += "<option value="+el.area_name+">"+el.area_name+"</option>";
      })
      _this.next().find('option').slice(0).remove();
      _this.next().append(_html);
     })
   })

 $(document).delegate("#add_area","click",function(){
   easyDialog.open({
      container : 'car_range'
    });
   });

 $(document).delegate('#add_area_add', 'click', function(event) {
   easyDialog.open({
      container : 'area'
    });
 });


   $(document).delegate("#range_ensure","click",function(){
    easyDialog.open({
      container : 'area'
    });
   });


   $("#add_upload").click(function(event) {
    easyDialog.open({
      container : 'uplode',
      follow : 'fiexd',
      followX : 265,
      followY : 300
    });
   });

  $("#pass").click(function(event) {
    if($("#verify").html()== undefined) {
      $('#error_msg').css('display','block');
      return false;
    }
    easyDialog.open({
      container : 'check'
    });
   });

  $(document).delegate("#summbb","click",function(){
  easyDialog.open({
    container : 'uploads'
  });
 });

   $("#backs").click(function(event) {
    easyDialog.open({
      container : 'back'
    });
   });

   $("#quite").click(function(event) {

   });


 $(document).delegate("#close","click",function(){
    easyDialog.close();
 });


     var frm = $('#form');
        frm.submit(function (ev) {
          var in1 = $("select[name='city_name[1]']").val();
          var in2 = $("select[name='city_name[2]']").val();
          var in3 = $("select[name='city_name[3]']").val();
          if ((in1 || in2 || in3) == '') {
            $("#error").css('display','block');
            return false;
          }
            $.ajax({
                type: frm.attr('method'),
                url: frm.attr('action'),
                data: frm.serialize(),
                dataType: "json",
                success: function (data) {
                  if (data.error_code == 1) {
                    location.reload();
                  } else {
                    alert('操作失败,请重新登录操作!');
                    }
                 }
            });

            ev.preventDefault();
        });

$(document).delegate('#audit', 'click', function(event) {
  $.ajax({
    url: 'index.php?act=commons_manage&op=audit_dealer',
    type: 'POST',
    dataType: 'json',
    data: {dealer_id: $('input[name=dealer_id]').val()},
  })
  .done(function(data) {
    if (data.error_code == 1) {
        location.reload();
      } else {
        alert('操作失败,请重新登录操作!');
        }
  })

});

$(document).delegate('#click_back', 'click', function(event) {
  $.ajax({
    url: 'index.php?act=commons_manage&op=pass_dealer',
    type: 'POST',
    dataType: 'json',
    data: {dealer_id: $('input[name=dealer_id]').val()},
  })
  .done(function(data) {
    if (data.error_code == 1) {
        location.reload();
      } else {
        alert('操作失败,请重新登录操作!');
        }
  })

});

  });
</script>
