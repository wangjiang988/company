<?php defined('InHG') or exit('Access Invalid!');?>
<?php extract($output);?>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
           <h3>地区管理</h3>
            <ul class="tab-base">
                <li><a href="<?=url('area_manage','index')?>"><span>列表</span></a></li>
                <li><a href="JavaScript:void(0);" class="current"><span>新增</span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form id="area_form" action="<?=url('area_manage','add')?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="form_submit" value="ok" />
        <div class="pure-g line">
             <div class="pure-u-1-4" >
                <div id='province_div'>
                    省级: 
                <select name="province" id="province">
                    <option value="0">省级</option>
                    <?php if($province_list){?>
                        <?php foreach($province_list as $item){?>
                                <option value="<?=$item['area_id']?>"><?=$item['area_name']?></option>
                        <?php }?>
                    <?php }?>
                </select>

                 <select name="city" id="city">
                    <option value="0">市级</option>
                 </select>
                </div>
            </div>
        </div>
        <div class="pure-g line">
            <div class="pure-u-1-4">
                名称:
                  <input type="text" class="text" name="area_name" value="<?=$data['area_name'];?>" />
            </div>
        </div>
        <div class="pure-g line">
            <div class="pure-u-1-4">
                是否非大陆地区:
                  <input type="checkbox" name="not_mainland" value="1">
            </div>
        </div>
        <div class="pure-g line">
            <div class="pure-u-1-2">
                 <a href="JavaScript:void(0);" class="button" id="sub_btn"><span>新增</span></a>
               <a href="JavaScript:history.go(-1);" class="button" id="sub_btn"><span>返回</span></a>
            </div>
        </div>

    </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.serializejson.min.js" charset="utf-8"></script>
<script>
    $(function(){
          $("#sub_btn").click(function(){
                $("#area_form").submit();
          });

          $("#province").change(function(){
              let val  = $(this).val();

              if(val!="0"){
                  post('index.php?act=area_manage&op=getCityByProvince',{'id':val})
                  .then(function(res){
                        if(res.data.code == 200)
                        {
                             $("#city").html("<option value='0'>市级</option>");
                            let list = res.data.data;
                            for(var i= 0; i <list.length; i++)
                            {
                                 $("#city").append("<option value='"+list[i].area_id+"'>"+list[i].area_name+"</option>");
                            }
                        }else
                        {
                            // alert(res.data.msg);
                        }
                  })
              }else{
                             $("#city").html("<option value='0'>市级</option>");
              }
          });
    })
</script>