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
                <li><a href="<?php echo urlAdmin('parameter', 'index');?>"><span>列表</span></a></li>
                <li><a href="javaScript:void(0);" class="current"><span>新增</span></a></li>
            </ul>
        </div>
    </div>

    <div class="fixed-empty"></div>

    <table class="table tb-type2" id="app">
        <tbody>
        <tr>
            <td class="w120 tar">审核项目名称</td>
            <td>
                <input type="text" class="w300" name="title" v-model="formData.title" />
            </td>
        </tr>
        <tr>
            <td class="tar">关联字段</td>
            <td>
                <select name="field" v-model="formData.field">
                    <option v-for="(item, index) in fields" v-bind:value="index">{{item}}</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="tar">运算符</td>
            <td>
                <select name="operator" v-model="formData.operator">
                    <option v-for="(item, index) in operators" v-bind:value="index">{{item}}</option>
                </select>
            </td>
        </tr>
        <tr>
            <td class="tar" rowspan="2">参数类型</td>
            <td>
                <label><input type="radio" name="type" v-model.number="formData.type" value="1" />常数</label>
                <input type="number" min="1" class="w60" name="const" v-model.number="formData.const" v-if="formData.type" />
            </td>
        </tr>
        <tr>
            <td>
                <label><input type="radio" name="type" v-model.number="formData.type" value="0" />关联</label>
                <template v-if="!formData.type">
                 <select name="relation_field" v-model="formData.relation_field">
                    <option v-for="(item, index) in fields" v-bind:value="index">{{item}}</option>
                </select>
                原值 * <input type="number" min="0.1" class="w60" name="multiple" v-model.number="formData.multiple"/> 倍
                </template>
            </td>
        </tr>
        <tr>
            <td class="tar">是否启用</td>
            <td>
                <label><input type="radio" name="open" v-model.number="formData.open" value="1" />是</label>
                <label><input type="radio" name="open" v-model.number="formData.open" value="0" />否</label>
            </td>
        </tr>
        </tbody>
        <tfoot>
        <tr>
            <td></td>
            <td>
                <div v-if="submiting">正在提交中，请等等...</div>
                <a v-else class="btn" href="javascript:void(0);" v-on:click="submit"> <span>{{submitText}}</span> </a>
            </td>
        </tr>
        </tfoot>
    </table>
</div>

<script src="<?php echo RESOURCE_SITE_URL;?>/js/vue.min.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/vue-resource.min.js"></script>
<script>
    var app = new Vue({
        el : '#app',
        data : {
            fields : <?php echo $output['data']['fields']; ?>,
            operators : <?php echo $output['data']['operators']; ?>,
            formData : {
                title : '',
                field : 'agent_numberplate_price',
                operator : 1,
                type : 1,
                const : '',
                relation_field : 'agent_numberplate_price',
                multiple : '',
                open : 0
            },
            submitText : '<?php echo $lang['nc_submit'];?>',
            submiting : false
        },
        methods : {
            submit : function() {
                var that = this.formData;

                if (that.title == '') {
                    alert('名称不能为空');
                    return;
                }

                if (that.type == 1) {
                    if (that.const == '' || that.const <= 0) {
                        alert('请输入不为0的常数');
                        return;
                    }
                } else if (that.type == 0 ) {
                    if (that.multiple == '' || that.multiple <= 0) {
                        alert('请输入不为0的关联参数倍数');
                        return;
                    }
                }

                this.submiting = true;

                this.$http.post(
                    '<?php echo urlAdmin('parameter', 'postadd');?>',
                    that,
                    {emulateJSON : true}
                ).then((response) => {
                    var data = JSON.parse(response.body)
                    if (data.success) {
                        window.location.href='<?php echo urlAdmin('parameter', 'index');?>'
                    } else {
                        this.submiting = false;
                        alert(data.msg);
                    }
                });
            }
        }
    });
</script>