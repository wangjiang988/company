<template>
  <ul class="pagination">
    <li :disabled="this.current == 1"  :class="this.current == 1?'disabled':''" @click="prePage"><span>«</span></li>
    <li v-if="pageNo !== 1" :class="1 == current ? 'active':''" @click="goPage(1)"><span>1</span></li>
    <li v-if="preClipped" class=""><span>...</span></li>
    <li v-for="index in pages" :class="index == current ? '  active':'page-index ' " @click="goPage(index)"><span>{{index}}</span></li>
    <li v-if="backClipped" class=""><span>...</span></li>
    <li :class="pageNo == current ? 'active':'' " @click="goPage(pageNo)"><span>{{pageNo}}</span></li>
    <li  :disabled="this.current == pageNo"  :class="this.current == pageNo?'disabled':''" @click="nextPage"><span>»</span></li>
  </ul>
</template>


<script>
export default {
  props: {
    // 用于记录总页码，可由父组件传过来
    pageNo: {
      type: Number,
      default: 1
    },
    // 用于记录当前页数，这个与父组件进行数据交互来完成每一页的数据更新，所以我们只要改变current的值来控制整个页面的数据即可
    current: {
      type: Number,
      default: 1
    }
  },
  data: function () {
    return {
      // 用于判断省略号是否显示
      backClipped: true, 
      preClipped: false,
        myCurrent:this.current
    }
  },
  methods: {
    prePage () {
      // 上一页
        if(this.myCurrent ==1) return false;
        if(this.myCurrent>0)
            this.myCurrent--
    },
    nextPage () {
      // 下一页
       if(this.myCurrent == this.pageNo)
           return false
      this.myCurrent++
    },
    goPage (index) {
      // 跳转到相应页面
      if (index !== this.myCurrent) {
        this.myCurrent = index
      }
    }
  },
    watch: {
      current(val){
          this.myCurrent = val;
      },
        myCurrent(val){
            this.$emit("page-change",val);
        },

    },
  computed: {
    // 使用计算属性来得到每次应该显示的页码
    pages: function () {
      let ret = []
      if (this.current > 3) {
        // 当前页码大于三时，显示当前页码的前2个
        ret.push(this.current - 2)
        ret.push(this.current - 1)
        if (this.current > 4) {
          // 当前页与第一页差距4以上时显示省略号
          this.preClipped = true
        }
      } else {
        this.preClipped = false
        for (let i = 2; i < this.current; i++) {
          ret.push(i)
        }
      }
      if (this.current !== this.pageNo && this.current !== 1) {
        ret.push(this.current)
      }
      if (this.current < (this.pageNo - 2)) {
        // 显示当前页码的后2个
        ret.push(this.current + 1)
        ret.push(this.current + 2)
        if (this.current <= (this.pageNo - 3)) {
        //  当前页与最后一页差距3以上时显示省略号
          this.backClipped = true
        }
      } else {
        this.backClipped = false
        for (let i = (this.current + 1); i < this.pageNo; i++) {
          ret.push(i)
        }
      }
      // 返回整个页码组
      return ret
    }
  }
}
</script>