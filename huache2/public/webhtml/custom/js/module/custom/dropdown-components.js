define(function (require, exports, module) {

	Vue.component("drop-down", {
        props:["defValue","list","className","isHighLight","isClick","name","id"],
        template: `
            <div class="btn-group fl" @click="display">
                <div :class="classList" class="btn btn-default dropdown-toggle btn-dropdown-default">
                    <span class="dropdown-label"><span :class="{juhuang:isHighLight && isSelect && defValue != def}">{{def}}</span></span>
                    <span class="caret"></span>
                </div>
                <ul :class="[{show:show},ulClassList,classList]">
                    <input type="hidden" :name="name" :id="id" :value="valId" />
                    <li v-for="item in list" @click.prevent="select(item,$event)"><a><span>{{item.name || item}}</span></a></li>
                </ul>
            </div>
        `,
        data: function() {
            return {
                show:!1,
                valId:"",
                def:"",
                classList:[],
                ulClassList:['dropdown-menu','btn-dropdown-default'],
                isSelect:!1
            }
        },
        computed: {

        },
        mounted:function(){
        	 this.def = this.defValue
           var _this = this
           if (this.className) {
              this.className.split(' ').forEach(function(item){
                 _this.classList.push(item)
              })
           }
        },
        methods: {
            display:function(event){
               if ($(event.target).parent().hasClass("disabled")) return false
               this.show = !this.show
            },
            select:function(obj,event){
                this.show = !0
                this.isSelect = !0
                if (obj.id) {
                	 this.def = obj.name
                   this.valId = obj.id
                   if(obj.array) 
                      this.$emit('receive-params',obj)
                   else
                      this.$emit('receive-params',this.valId,this.def)
                }
                else{
                   this.def = obj
                   this.$emit('receive-params',this.def)
                }
                var _event = event.target.tagName == "LI" ? event.target : $(event.target).parents("li")[0]
                _event.className = "active"
                $(_event).siblings().removeClass('active')
               //console.log(this.isHighLight,this.isSelect , this.def, this.defValue,this.def==this.defValue)
            },

        },
        watch:{
            'isClick' :function(){
               this.show = !1
            }
        }

    })
})