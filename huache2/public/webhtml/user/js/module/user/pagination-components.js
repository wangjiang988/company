define(function (require, exports, module) {
  
	Vue.component("pagination", {
        props:["currents","showItem","allPage"],
        template: `
            <ul class="pagination vue-page" >
                <li class="prev" v-show="current != 1" @click="current-- && goto(current--)"><a href="javascript:;">上一页</a></li>
                <li v-for="pageindex in pages" @click="goto(pageindex)" :class="{'active':current == pageindex}" :key="pageindex">
                  <a href="javascript:;" >{{pageindex}}</a>
                </li>
                <li class="last-w" v-show="allPage != current && allPage != 0 " @click.stop-1="current++ && goto(current++)" ><a href="javascript:;" class="next">下一页</a></li>
                <li>
                    <span class="p-info">共{{allPage}}页</span>
                </li>
            </ul>
        `,
        data: function() {
            return { 
                current: this.currents 
            }
        },
        computed: {
            pages:function(){
          
              var pag = []
              if( this.current < this.showItem ){ //如果当前的激活的项 小于要显示的条数
                   //总页数和要显示的条数那个大就显示多少条
                   var i = Math.min(this.showItem,this.allPage)
                   while(i){
                       pag.unshift(i--)
                   }
               }else{ //当前页数大于显示页数了
                   var middle = this.current - Math.floor(this.showItem / 2 ),//从哪里开始
                       i = this.showItem
                   if( middle >  (this.allPage - this.showItem)  ){
                       middle = (this.allPage - this.showItem) + 1
                   }
                   while(i--){
                       pag.push( middle++ )
                   }
               }
               return pag
            }
        },
        mounted:function(){
        	
        },
        methods: {
            goto: function(index) {
                if (index >= this.allPage)  index = this.allPage
                if (index == this.current) return
                this.current = index
                this.$emit('receive-params',this.current)
            }  
        },
        watch:{
             
             
        }

  })

  module.exports = {
      
  }

})