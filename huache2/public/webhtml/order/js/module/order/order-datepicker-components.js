/* hwache custom time component
 * time:2017-5-7
 * author llm
*/

define(function (require, exports, module) {
  //执行顺序
  //exports中的init
  //computed中的计算属性
  //mounted中的方法
  //支持显示和输出双格式
  //<datepicker output-format="yyyy/MM/dd" :colorlist="colorList" @gettime="getTime" el="timeSelect" curdate="2017-5-10"></datepicker>
  //必给参数 el(目前版本只支持id)和curdate
  var calendar = function(y,M,d){
    this.year   = y
    this.months = M
    this.month  = d
  }
  var datepicker =
	Vue.component("datepicker", {
        props:["curdate","ishighlightcurdate","yearlist","format","el","colorlist","outputFormat","html"],
        template: `
           <div id="hc-datepicker" class="datepicker-wrapper" :class="{show:display,psa:display}">
              <div class="picker-header">
                  <span @click="setYear(year-1)" class="year-prev">&lt;</span>
                  <span class="year" @click="getYearList">{{year}}年</span>
                  <span @click="setYear(year+1)" class="year-next">&gt;</span>
                  <span @click="setMonth(month-1)" class="month-prev">&lt;</span>
                  <span class="month" @click="getMonthList">{{month}}月</span>
                  <span @click="setMonth(month+1)" class="month-next">&gt;</span>
                  <ul class="year-list" :class="{show:isDisplayYearList}">
                      <li @click="selectYear(y)" v-for="y in years">{{y}}</li>
                  </ul>
                  <ul class="month-list" :class="{show:isDisplayMonthList}">
                      <li @click="selectMonth(m)" v-for="m in months">{{m}}</li>
                  </ul>
              </div>
              <div class="date-tip">
                  <span v-for="dayzh in lang.zh">{{dayzh}}</span>
              </div>
              <div class="datepicker-split"></div>
              <div class="datepicker-content">
                  <span @click="selectDate($event,d.year,d.month,d.date)" v-for="(d,i) in dates" :class="[{red:curColor(d.year,d.month,d.date)},d.class]">{{d.date}}</span>
              </div>
              <p class="tip-txt" v-html="html || '注：蓝色为免收超期费可选日期，<br>黑色为可能加收超期费可选日期！'"></p>
           </div>
        `,
        data: function() {
            return {
                lang:{
                  zh:["日","一","二","三","四","五","六"]
                },
                list:[],
                dates:[],
                monthDayIndex:-1,
                isDisplayMonthList:!1,
                isDisplayYearList:!1,
                month:-1,
                year:-1,
                selectDateTxt:"",
                outputDateTxt:"",
                display:!1,
                cale:null
            }
        },
        created:function(){
          this.$on("test",function(msg){
             console.log(msg)
          })
        },
        computed: {

            listLength:function(){
                return 42
            },
            years:function(){
                return this.yearlist || [2010,2011,2012,2013,2014,2015,2016,2017,2018,2019,2020,2021,2022,2023]
            },
            months:function(){
                return [1,2,3,4,5,6,7,8,9,10,11,12]
            },
            monthCount:function(){
                return this.getMonthDayCount()
            },
            inityear:function(){
                return new Date(this.curdate).getFullYear()
            },
            curyear:function(){
                return new Date(this.curdate).getFullYear()
            },
            curmonth:function(){
                return new Date(this.curdate).getMonth() + 1
            },
            currentdate:function(){
                return new Date(this.curdate).getDate()
            },
            date:function(){
                return new Date(this.curdate).getDate()
            },
            day:function(){
                return new Date(this.curdate).getDay()
            },

        },
        mounted:function(){
           this.initEvent()
           this.setYear()
           this.setMonth()
           //生成日历数据
           this.list = this.getList()
           //计算当月是周几
           this.setMonthDayIndex()
           //设置当月显示的内容
           this.setCurMonthList()
           //设置当月显示颜色
           //this.setCurMonthColor()

        },


        methods: {
           setCurMonthColor:function(){
              if (this.colorlist) {
                  //console.log(this.colorlist)
                  var _this = this
                  var _colorlast = this.colorlist[this.colorlist.length-1]

                  this.dates.forEach(function(time,idx){
                      time.class = "gray"
                      _this.colorlist.forEach(function(t,i){
                          if (time.year == t.year && time.month == t.month && time.date == t.day) {
                             time.class= t.select == 1 ? "gray" : "blue"
                          }
                      })

                      if (
                           time.year > _colorlast.year
                           ||
                           (time.year >= _colorlast.year && time.month > _colorlast.month)
                           ||
                           (time.year >= _colorlast.year && time.month >= _colorlast.month && time.date > _colorlast.day)
                        )
                        time.class = "black"

                  })

                  /*console.log(this.dates)
                  console.log(_colorlast)*/
              }
           },
           initEvent:function(){
                var _this = this
                var _el   = document.getElementById(this.el)
                this.cale = document.getElementById("hc-datepicker")
                _el.addEventListener("focus",function(){
                   _this.display = !0
                })
                window.addEventListener("click",function(event){
                  if (event.target != _el)
                    if (!_this.cale.contains(event.target))
                        _this.display = !1
                })
           },
           selectDate:function(event,y,m,d){
                if (event.target.className == "gray") return
                var _time = new Date(y + "-" + m + "-" + d )
                this.selectDateTxt = _time.Format(this.format || "yyyy年MM月dd日")
                this.outputDateTxt = _time.Format(this.outputFormat || (this.format || "yyyy年MM月dd日"))
              //outputFormat
                this.year          = y
                this.month         = m
                this.date          = d
                this.$emit('gettime',this.el,this.selectDateTxt,this.outputDateTxt,y,m,d,event.target.className)
                this.display = !1
                this.cale.blur()
           },
           curColor:function(y,m,d){
               return d == this.currentdate && m == this.curmonth && y == this.curyear && (JSON.parse(this.ishighlightcurdate || !true))
           },
           selectYear:function(year){
               this.year = year
               this.getYearList()
           },
           selectMonth:function(month){
               this.month = month
               this.getMonthList()
           },
           getYearList:function(){
               this.isDisplayYearList = !this.isDisplayYearList
           },
           getMonthList:function(){
               this.isDisplayMonthList = !this.isDisplayMonthList
           },
           //计算当前月第一天是星期几
           setMonthDayIndex:function(){
              this.monthDayIndex = $.inArray(this.list[this.year].month[this.month][0].day,this.lang.zh)
           },
           //日历当前显示的日期
           setCurMonthList:function(){
               var _year        = this.year,
                   _prevyear    = this.year,
                   _nextyear    = this.year,
                   _prevmonth   = this.month - 1,
                   _month       = this.month,
                   _nextmonth   = this.month + 1
               if (_month == 1) {
                  _prevyear--
                  _prevmonth = 12
               }else if (_month == 12) {
                  _nextyear++
                  _nextmonth = 1
               }

               var _prevarray   = this.list[_prevyear].month[_prevmonth],
                   _currarray   = this.list[_year].month[_month],
                   _nextarray   = this.list[_nextyear].month[_nextmonth],
                   _            = _prevarray.concat(_currarray).concat(_nextarray),
                   _startIndex  = _prevarray.length - (this.monthDayIndex == 0 ? 7 : this.monthDayIndex),
                   _endIndex    = _startIndex + this.listLength
                   this.dates   = _.slice(_startIndex,_endIndex)
           },
           getMonthDayCount:function(_time){
              var _curDate  = new Date(_time || this.curdate)
              var _curMonth = _curDate.getMonth()
              _curDate.setMonth(_curMonth + 1)
              _curDate.setDate(0)
              return _curDate.getDate()
           },
           getList:function(){
              var _this = this
              var _obj = {}
              this.years.forEach(function(year){
                  var _month = {}
                  _this.months.forEach(function(month,index){
                      var _monthcount = _this.getMonthDayCount(new Date(year + "-" + month + "-" + month))
                      _month[month] = _this.setMonthDate(year,month,_monthcount)
                  })
                  var _o = new calendar(year,_this.months,_month)
                  _obj[year] = _o
              })
              return _obj
           },
           setMonthDate:function(year,month,count){
              var _array = []
              for (var i = 1; i <=count; i++)
                  _array.push({class:'',year:year,month:month,date:i,day:this.lang.zh[new Date(year+"-"+month + "-" +i).getDay()]})
              return _array
           },
           setYear:function(year){
                this.year = year || this.inityear
           },
           setMonth:function(month){
                if (month == 0){
                    this.year--
                    this.month = 12
                    return
                }else if(month > 12){
                    this.year++
                    this.month = 1
                    return
                }
                this.month = month || new Date(this.curdate).getMonth() + 1
           }
        },
        watch:{

              'month':function(n,o){
                  this.setMonthDayIndex()
                  this.setCurMonthList()
                  this.setCurMonthColor()
              },
              'year':function(n,o){
                  this.setMonthDayIndex()
                  this.setCurMonthList()
                  this.setCurMonthColor()
              },
              //深度监控
              colorlist:{
                 handler: function(n,o){
                    //this.setCurMonthColor()
                },
                deep:true
            },


        }

  })

  if(!!window.find){
    HTMLElement.prototype.contains = function(B){
      return this.compareDocumentPosition(B) - 19 > 0
    }
  }

  Date.prototype.Format = function (fmt) {
    var o = {
        "M+": this.getMonth() + 1,
        "d+": this.getDate(),
        "h+": this.getHours(),
        "m+": this.getMinutes(),
        "s+": this.getSeconds(),
        "q+": Math.floor((this.getMonth() + 3) / 3),
        "S" : this.getMilliseconds()
    }
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length))
    for (var k in o)
        if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)))
    return fmt
  }

  module.exports = {
      initTime:function(list){

      }
  }

})