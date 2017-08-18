define(function (require, exports, module) {

  Vue.component("province-city", {
        props:["defValue","isSelectProvince","provinceTmp","cityTmp"],
        template: `
            <div class="form-txt psr inlineblock" id="area-component">
                <div class="btn-group m-r pdi-drop pdi-drop-warp">
                  <div @click="initProvince" class="btn btn-sm btn-default dropdown-toggle area-drop-btn">
                      <span class="dropdown-label"><span>{{def}}</span></span>
                      <span class="caret"></span>
                  </div>
                  <div :class="{show:showArea,'dropdown-menu':true,'dropdown-select':true,'area-tab-div':true}">
                      <input type="hidden" name="province" :value="province" />
                      <input type="hidden" name="city" :value="city" />
                      <p class="area-tab"><span :class="{'cur-tab':showProince}" @click="showProvince">省份</span><span :class="{'cur-tab':!showProince}" @click="showCity">城市</span></p>
                      <dl :class="{dl:true,none:!showProince}">
                           <dd v-for="(item,index) in provinceList" @click="selectProvince(item)">{{item.area_name}}</dd>
                           <div class="clear"></div>
                      </dl>
                      <dl :class="{dl:true,none:showProince}">
                        <dd v-for="(item,index) in cityList" @click="selectCity(item)">{{item.area_name}}</dd>
                        <div class="clear"></div>
                      </dl>
                  </div>
                </div>
                <div :class="{'error-div':isSelectProvince,'red':true}">请正确选择省份和城市</div>
                <div class="clear"></div>
            </div>
        `,
        data: function() {
            return { 
                provinceList:[],
                cityList:[],
                showArea:!1,
                showProince:!0, 
                province:"",
                city:"",
                def:"" 
            }
        },
        computed: {
            
        },
        mounted:function(){
          this.def = this.defValue
          this.province = this.provinceTmp
          this.city = this.cityTmp
          
        },
        methods: {
            initProvince:function(){
                this.showArea = !this.showArea
                var _this = this
                $.getJSON('/js/vendor/front-area.js', function(json, textStatus) {
                  _this.provinceList = []
                    $.each(json,function(index,item){
                        $.each(item,function(idx,it){
                            _this.provinceList.push(it)
                        })
                    })
                })

            },
            selectProvince:function(obj){
              var _this = this
              this.showProince = !this.showProince
              this.province = obj.area_name
              $.each(this.provinceList,function(index,item){
                if (obj.area_id === item.area_id) {
                  _this.cityList = item.child
                }
              })
            },
            showProvince:function(){
              this.showProince = !0
            },
            showCity:function(){
              this.showProince = !1
            },
            selectCity:function(obj){
              this.showProince = !this.showProince
              this.showArea = !this.showArea
              this.city = obj.area_name
            }
        },
        watch:{
             'city' :function(n,o){
                if (typeof(n) == "undefined"){
                    this.def = this.defValue
                }else{
                    this.def = this.province + this.city 
                    this.$emit('receive-params',this.province,this.city)
                }
             }
        }

    })
})