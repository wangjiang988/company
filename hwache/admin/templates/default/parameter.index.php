<?php defined('InHG') or exit('Access Invalid!');?>
<link href="<?php echo ADMIN_TEMPLATES_URL;?>/css/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
<link rel="stylesheet" href="<?php echo ADMIN_TEMPLATES_URL;?>/css/font/font-awesome/css/font-awesome-ie7.min.css">
<![endif]-->
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><?php echo $output['title'];?></h3>
            <ul class="tab-base">
                <li><a href="JavaScript:void(0);" class="current"><span>列表</span></a></li>
                <li><a href="<?php echo urlAdmin('parameter', 'add');?>"><span>新增</span></a></li>
            </ul>
        </div>
    </div>

    <div class="fixed-empty"></div>

    <table class="table tb-type2" id="app">
        <thead>
        <tr class="thead">
            <th class="w24">编号</th>
            <th>审核项目名称</th>
            <th>关联字段</th>
            <th>参数内容</th>
            <th>是否启用</th>
            <th class="w120">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php
            if (!empty($output['data']['list'])) :
            foreach ($output['data']['list'] as $k => $v) :
        ?>
            <tr>
                <td><?php echo $v['id'];?></td>
                <td><?php echo $v['title'];?></td>
                <td><?php echo $output['data']['fields'][$v['field']];?></td>
                <td><?php echo $v['operator'];?>
                    <?php
                        if (empty($v['relation_field'])) {
                            echo '常量：'.$v['const'];
                        } else {
                            echo '关联字段：'.$output['data']['fields'][$v['relation_field']].' * '.$v['multiple'];
                        }
                    ?>
                </td>
                <td><?php echo $v['open'] ? '开启' : '未开启';?></td>
                <td><?php echo $v['open'] ? '<a class="btn" href="javascript:void(0);" v-on:click="close('.$v['id'].')"> <span>关闭规则</span></a>' : '<a class="btn" href="javascript:void(0);" v-on:click="open('.$v['id'].')"> <span>开启</span></a>';?></td>
            </tr>
        <?php
            endforeach;
            else:
        ?>
            <td colspan="6" class="align-center">无</td>
        <?php
            endif;
        ?>
        </tbody>
    </table>
</div>

<script src="<?php echo RESOURCE_SITE_URL;?>/js/vue.min.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/vue-resource.min.js"></script>
<script>
    var app = new Vue({
        el : '#app',
        methods : {
            open: function(id) {
                if (confirm('确定要开启么？')) {
                    this.$http.post(
                        '<?php echo urlAdmin('parameter', 'postopen');?>',
                        {'id' : id},
                        {emulateJSON : true}
                    ).then((response) => {
                        var data = JSON.parse(response.body)
                        if (data.success) {
                            window.location.reload()
                        } else {
                            alert(data.msg);
                        }
                    });
                }
            },
            close: function(id) {
                if (confirm('确定要关闭该规则么？')) {
                    this.$http.post(
                        '<?php echo urlAdmin('parameter', 'postclose');?>',
                        {'id' : id},
                        {emulateJSON : true}
                    ).then((response) => {
                        var data = JSON.parse(response.body)
                        if (data.success) {
                        window.location.reload()
                    } else {
                        alert(data.msg);
                    }
                });
                }
            }
        }
    });
</script>