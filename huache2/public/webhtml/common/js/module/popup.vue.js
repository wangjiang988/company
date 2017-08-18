define(function (require, exports, module) {

	Vue.component("popup", {
        props:[],
        template: `
            <div class="dela hide" :class="{show:isshow}">
                <div class="deal-zm" @click="display"></div>
                <div class="deal-content" :style="{left:offsetLeft,top:offsetTop}">
                    <slot name="title"></slot>
                    <a href="javascript:;" @click="display" class="deal-close"></a>
                    <div class="deal-wrapper">
                        <slot name="header"></slot>
                        <slot name="main"></slot>
                    </div>
                    <p class="tac mt-30">
                        <button @click="display" type="button" class="btn btn-danger btn-service">同意并继续</button>
                    </p>
                </div>
            </div>
        `,
        data: function() {
            return { 
                isshow:!1,
                
            }
        },
        computed: {
            offsetLeft:function(){
                var width = window.screen.width
                var popupwidth = 955
                return ((width-popupwidth)/2) + 'px'
            },
            offsetTop:function(){
                var height = window.screen.height
                if (height <= 768) height = 15
                else height = 90
                return height + 'px'
            }
        },
        mounted:function(){
            
        },
        methods: {
            display:function(event){
                this.isshow = !this.isshow
                if (event && event.target.tagName == "BUTTON") {
                    this.$emit('update:status',!0)
                }
            }  
        },
        watch:{
            
        }

    })

    module.exports = {
                
    }
})