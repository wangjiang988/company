define(function (require, exports, module) {

    //分页组件
    Vue.component("page-info", {
        props:["currents","showItem","allpage","input"],
        template: "#vue-page",
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
                   var i = Math.min(this.showItem,this.allpage)
                   while(i){
                       pag.unshift(i--)
                   }
               }else{ //当前页数大于显示页数了
                   var middle = this.current - Math.floor(this.showItem / 2 ),//从哪里开始
                       i = this.showItem
                   if( middle >  (this.allpage - this.showItem)  ){
                       middle = (this.allpage - this.showItem) + 1
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
            goto: function(index,isInput) {
                if (index >= this.allpage)  index = this.allpage
                if (index == this.current) return
                this.current = index
                if (isInput && isInput === 1)  app.pageinfo.input = index
                else app.pageinfo.input = ""
                //分页请求
                app.pageinfo.current = this.current
                app.getSearchListWithOption()
            },
            increment: function () {
              //this.$emit('increment',this.current)
            }
            ,
            selectThis: function (event) {
               $(".p-info .form-control").select()
            }
        },
        watch:{
            'currents':function(news,old){
                this.current = news 
                app.list.findIdList = [] 
                app.resetContrast() 
            }
        }

    })

    //info: !0 == true , !1 == false
    var app = new Vue({
        el: '.SearchPageForm',
        data: {
            isEmpty:!0,
            isFirstLoading:!0,
            isLoading:!0,                                       //加载动画
            isShowProvinceTxt :!0,
            //分页组件参数设置
            pageinfo:{ 
                current: 1,                                     //当前页
                showItem: 5,                                    //分页组件中数字分页的显示个数
                allpage: 0,                                     //页面加载时候会设置总页数
                input:"" ,                                      //跳转的输入框的值
                perpage : 10                                    //每页多少条数据
            },
            defOrderListTmp:[],
            url:{
                areaListUrl:"/js/vendor/front-area.js",         //获取地区列表的请求地址
                brandListUrl:"/js/vendor/brand.js",             //获取品牌列表的请求地址
                carSeriesListUrl:"/js/vendor/car-info.js",      //获取车系列表的请求地址
                vehicleModeListUrl:"/js/vendor/car-info.js",    //获取车型列表的请求地址
                searchListUrl:"/so",                            //获取总的车源列表的请求地址
                brandImageUrl:"http://upload.hwache.cn/"        //修正品牌图片地址(真实环境可置为空或者'http://upload.hwache.com/')
            },
            search:{
                province:"",                                    //当前省份
                specificProvince:"",                            //指定地区省份
                cityId:-1,                                      
                areaList:[],                                    //获取的地区列表
                cityList:[],                                    //获取的城市列表
                specificCityList:[],                            //特定地区城市列表
                isShowProvince:false,                           //是否弹出详细的省份城市选择框
                cityValIndex:0,                                 //从哪个地方出发弹出详细的省份城市选择框
                specifiedArea:"",                               //特定地区
                brandList:[],                                   //品牌列表
                carSeriesList:[],                               //车系列表
                vehicleModeList:[],                             //车型列表
                searchList:[],                                  //用于显示的车源列表
                mainOption:{
                    //新车上牌地区
                    area:{
                        id:"",                                  //选中的地区ID
                        text:"",                                //选中的地区文字
                        display:false,                          //是否下拉
                        isLimit:false                           //是否限牌
                    },
                    //品牌下拉框
                    brand:{
                        id:-1,
                        text:"",
                        display:false,
                        image:""
                    },
                    //车系下拉框
                    carSeries:{
                        id:-1,
                        text:"",
                        display:false,
                        isClear:!1
                    },
                    //车型下拉框
                    vehicleModel:{
                        id:-1,
                        searchId:-1,                            //触发车源列表加载,搜索 'search.mainOption.vehicleModel.searchId'
                        text:"",
                        display:false,
                        isClear:!1
                    },
                    guidePrice :"",                             //厂商指导价
                    seating :"",                                //座位数
                    country:"",                                 //生产国别
                    configuration:{
                        href:"#"                                //基本配置了解地址
                    },
                    isLoadOk:!1                                 //厂商指导价,座位数,生产国别是否加载完成
                },
                option:{
                    area:{
                        id:"0",                                 //中心城市ID
                        text:"",                                //中心城市名称
                        display:false ,                         //是否显示高级城市列表
                        val:"",                                 //车源距离最终选择值
                    },
                    //搜索配置
                    bodyColorList:[],                           //车身颜色列表用于显示
                    bodyColor:"",                               //车身颜色用于搜索
                    bodyColorTxt:"",                            //车身颜色用于搜索
                    bodyColorTxtList:[],                        //车身颜色用于搜索
                    interiorColorList:[],                       //内饰颜色列表用于显示
                    interiorColor:"",                           //内饰颜色用于搜索
                    interiorColorTxt:"",                        //内饰颜色用于搜索
                    interiorColorTxtList:[],                    //内饰颜色用于搜索
                    mileage:"-1",                               //行驶里程
                    mileageTxt:"",                              //行驶里程
                    factoryMonthly:"-1",                        //出厂年月
                    factoryMonthlyTxt:"-1",                     //出厂年月
                    emissionStandards:"5",                      //排放标准
                    registrationApplication:"0",                //上牌用途
                    payment:"0",                                //付款方式        
                    carCycle:"0",                               //交车周期           
                    isDistance : !0,                            //是否车源距离不限
                    isBodyColor : !0,                           //是否车身颜色不限
                    isInteriorColor : !0,                       //是否内饰颜色不限
                    isMileage : !0,                             //是否行驶里程不限
                    isFactoryMonthly : !0,                      //是否出厂年月不限
                    isEmissionStandards : !0,                   //是否排放标准不限
                    isPayment : !0,                             //是否默认付款方式
                    isRegApplication : !0,                      //是否默认上牌用途
                    isCanSearch : !1  ,                         //是否显示搜索按钮
                    isSelectMileage:!1,                         //是否选中行驶里程
                    isSelectFactoryMonthly:!1,                  //是否选中出厂年月
                    isSelectCarCycle:!1,                        //是否选中交车周期        
                    //车源距离列表
                    distanceList:[{txt:"\u4e0d\u9650",isSelect:!0,id:0},{txt:"\u4ec5\u770b\u672c\u5730",isSelect:!1,id:1},{txt:"\u5468\u8fb9\u5730\u533a",isSelect:!1,id:2},{txt:"\u66f4\u8fdc\u5730\u533a",isSelect:!1,id:3},{txt:"\u4e2d\u5fc3\u57ce\u5e02",isSelect:!1,id:4,isCenter:!0}],
                    //行驶里程列表
                    mileageList:[{txt:"\u4e0d\u9650",isSelect:!0,id:0},{txt:'20\u516c\u91cc\u4ee5\u5185',isSelect:!1,id:20},{txt:'50\u516c\u91cc\u4ee5\u5185',isSelect:!1,id:50},{txt:'50\u516c\u91cc\u4ee5\u4e0a',isSelect:!1,id:100}],
                    //出厂年月列表
                    factoryMonthlyList:[{txt:"\u4e0d\u9650",isSelect:!0,id:0},{txt:'\u534a\u5e74\u5185',isSelect:!1,id:1},{txt:'\u4e00\u5e74\u5185',isSelect:!1,id:2},{txt:'\u4e00\u5e74\u4ee5\u4e0a',isSelect:!1,id:3}],
                    //排放标准列表
                    emissionStandardsList:[/*{txt:"\u4e0d\u9650",isSelect:!0,id:-1},{txt:'\u56fd\u56db',isSelect:!1,id:4},*/{txt:'\u56fd\u4e94',isSelect:!1,id:5}],
                    //付款方式列表
                    paymentList:[{txt:"\u5168\u6b3e",isSelect:!0,id:0}],
                    //上牌用途列表
                    registrationApplicationList:[{txt:"\u975e\u8425\u4e1a\u4e2a\u4eba\u5ba2\u8f66",isSelect:!0,id:0,isHover:!1,showTxt:'\u79c1\u5bb6\u8f66'},{txt:'\u975e\u8425\u4e1a\u4f01\u4e1a\u5ba2\u8f66',isSelect:!1,id:1,isHover:!1,showTxt:'\u516c\u53f8\u81ea\u7528\u8f66'}],
                    //交车周期列表
                    carCycleList:[{txt:"\u4e0d\u9650",isSelect:!0,id:0},{txt:'1\u4e2a\u6708',isSelect:!1,id:1},{txt:'3\u4e2a\u6708',isSelect:!1,id:3},{txt:'6\u4e2a\u6708',isSelect:!1,id:6}]
                   
                } 
                ,
                order:{
                    orderBy:"def"
                }
                ,
                isSelectOption:!1,
                isNowTheCar:0

            },
            isCanContrast:false,                                //是否显示对比按钮
            isCanceContrast:false,                              //是否显示取消按钮
            isSlideOption:false,                                //是否显示高级搜索条件
            //高级搜索条件显示触发按钮切换文字
            SlideOptionTxtArr:["\u66f4\u591a\u9009\u9879","\u6536\u8d77\u7b5b\u9009"],
            //高级搜索条件显示触发按钮当前文字
            SlideOptionTxt:"",
            list:{
                selectedCount : 0,                              //判断车源列表是否选中了两个
                findIdList:[]
            },
            //"i"文字提示触发条件
            tips:[{isShow:false},{isShow:false},{isShow:false},{isShow:false},{isShow:false},{isShow:false},{isShow:false},{isShow:false},{isShow:false},{isShow:false},{isShow:false}]
        },
        mounted:function(){
            
             this.getAreaList()
             this.SlideOptionTxt = this.SlideOptionTxtArr[0]
             this.getBrandList()
             //this.setSpecificCityList()
        }
        , 
        methods:{
            showTip:function(obj){
                obj.isHover = !obj.isHover
            },
            getFactoryMonthlyById:function(id){
                var _txt = ""
                $.each(this.search.option.factoryMonthlyList,function(index,item){
                    if(item.id == id) {
                        _txt = item.txt
                        return false
                    }
                })      
                return _txt                 
            },
            getMileageById:function(id){
                var _txt = ""
                $.each(this.search.option.mileageList,function(index,item){
                    if(item.id == id) {
                        _txt = item.txt
                        return false
                    }
                })      
                return _txt                 
            },
            getDistanceById:function(id){
                if(isNaN(id)) return id
                var _txt = ""
                $.each(this.search.option.distanceList,function(index,item){
                    if(item.id == id) {
                        _txt = item.txt
                        return false
                    }
                })      
                return _txt                 
            },
            slideOptionWithJQ:function(){
                 $("#slide-wrapper").slideToggle()
                 this.isSlideOption = !this.isSlideOption
                 this.SlideOptionTxt = this.isSlideOption ? this.SlideOptionTxtArr[1] : this.SlideOptionTxtArr[0] 
            },
            //重置对比相关信息
            resetContrast:function(){
                this.isCanContrast = !1
                this.isCanceContrast = !1
                this.list.selectedCount = 0
            },
            //设置限牌
            setLimit:function(val){
                var _this = this
                $.each(this.search.cityList,function(index,item){
                    if (_this.search.cityId == item.area_id) {
                        _this.search.mainOption.area.isLimit = item.area_xianpai
                        return false
                    } 
                })
            },
            registrationWay:function(val){
                var _txt = ""
                if(val === 1) _txt = "本人上牌"
                if(val === 2) _txt = "指定上牌"
                if(val === 3 || val === 0) _txt = "自选上牌"
                if(val === 4) _txt = "接受安排"
                return _txt
            },
            temporaryCardLimit:function(val){
                var _txt = ""
                if(val === 1) _txt = "指定服务"
                if(val === 0) _txt = "自选服务"
                if(val === 2) _txt = "待定"
                return _txt
            },
            commercialInsuranceLimited:function(val){
                var _txt = ""
                if(val === 1) _txt = "指定投保"
                if(val === 0) _txt = "自由投保"
                if(val === 2) _txt = "待定"
                return _txt
            },
            //组件事件传播测试方法(请忽略或者delete)
            incrementCurrent:function(cur){
                console.log("incrementCurrent:"+cur)
            },
            //统计车源列表中的其他收费项目和金额的总价
            subProjectPrice:function(arr){
                var _price = 0
                $.each(arr,function(index,item){
                    _price+= parseFloat(item.sub_total)
                })
                return _price
            },
            //车源列表排序后
            //重置列表行背景色相间性问题
            orderList:function(tag,event){
                var _target = event.target
                if (_target.tagName === "SPAN" || _target.tagName === "I")  
                    _target = _target.parentNode
                _target.className = "order-selected"
                $(_target).siblings().removeClass('order-selected')
                if (     tag === "def")  this.search.order.orderBy = "def"
                else if (tag === "up")   this.search.order.orderBy = "up"
                else if (tag === "down") this.search.order.orderBy = "down"
                this.getSearchListWithOption()
            },
            //重置列表行背景色相间性问题
            resetTrandBg:function(){
                $.each(this.search.searchList,function(index,item){
                    item.isTrandBg = index % 2
                })
            },
            //重置搜索条件
            resetSearchOption:function(){
                this.resetSearch()
                this.getListWithTime()
            },
            //重置搜索条件
            resetSearch:function(){
               
                //重置中心城市
                this.search.specifiedArea = ""        
                //重置车源距离  
                this.search.option.area.id = ""
                this.search.option.area.text = ""
                this.search.option.area.val = ""
                //重置车源距离列表
                this.setListToDefaultValue(this.search.option.distanceList)
                //重置行驶里程
                this.search.option.mileage = "-1"
                this.setListToDefaultValue(this.search.option.mileageList) 
                //重置出厂年月
                this.search.option.factoryMonthly = "-1"
                this.setListToDefaultValue(this.search.option.factoryMonthlyList)
                //重置排放标准
                this.search.option.emissionStandards = "5"
                this.setListToDefaultValue(this.search.option.emissionStandardsList)
                //重置付款方式
                this.search.option.payment = "0"
                this.setListToDefaultValue(this.search.option.paymentList)
                //重置上牌用途
                this.search.option.registrationApplication = "0"
                this.setListToDefaultValue(this.search.option.registrationApplicationList)
                //重置车身颜色
                this.search.option.bodyColor = ""
                this.setListToDefaultValue(this.search.option.bodyColorList)
                //重置内饰颜色
                this.search.option.interiorColor = ""
                this.setListToDefaultValue(this.search.option.interiorColorList)
                //重置交车周期
                this.search.option.carCycle = "0"
                this.setListToDefaultValue(this.search.option.carCycleList)
                //重置分页组件的当前页数
                this.pageinfo.current = 1
                //重置已经选好的筛选条件
                this.search.option.bodyColorTxtList = []
                this.search.option.interiorColorTxtList = []
                this.search.option.bodyColorTxt = ""
                this.search.option.interiorColorTxt = ""
                this.search.option.area.text = ""
                this.search.option.mileageTxt = ""
                this.search.option.factoryMonthlyTxt = ""
                //重置现车和非现车选择限制
                this.search.option.isSelectMileage = !1
                this.search.option.isSelectFactoryMonthly = !1
                this.search.option.isSelectCarCycle = !1
                 
            },
            //重置搜索条件
            getListWithTime:function(){
                setTimeout(function(){
                    //隐藏查找和默认条件按钮
                    app.search.option.isCanSearch = false
                    //默认条件查询
                    app.getSearchListWithOption()
                },200)
            },
            //重置搜索条件
            getLoadingList:function(){
                setTimeout(function(){
                    //隐藏查找和默认条件按钮
                    app.search.option.isCanSearch = false
                    //默认条件查询
                    app.getSearchList()
                },200)
            },
            //重置列表中的默认选中
            setListToDefaultValue:function(list){
                $.each(list,function(index,item){
                    if(index == 0) item.isSelect = !0
                    else  item.isSelect = !1    
                })
            },
            //搜索条件选择好后的搜索点击事件
            searchValite:function(){
                this.setBodyColor()
                this.setInteriorColor()
                //重置分页组件的当前页数
                this.pageinfo.current = 1
                //标志通过什么来获取的数据源
                //通过此标志来显示不同的空搜索提示文字
                this.search.isSelectOption = !0
                this.getSearchListWithOption()
            },
            //车身颜色和内饰颜色
            //取消不限选中
            //字体变黄色
            //选中
            //检测是否显示搜索按钮
            setColorClass:function(color,index){
                if(color) color.isSelect = !color.isSelect 
                if(index == 0){
                    this.search.option.bodyColorList[0].isSelect = !1
                    this.search.option.isBodyColor = !1
                    if (color.isSelect) {
                        this.search.option.bodyColorTxtList.push(color.color)
                    }else{
                        this.search.option.bodyColorTxtList.splice($.inArray(color.color, this.search.option.bodyColorTxtList),1)
                    }
                }
                else if(index == 1){
                    this.search.option.interiorColorList[0].isSelect = !1
                    this.search.option.isInteriorColor = !1
                    if (color.isSelect) {
                        this.search.option.interiorColorTxtList.push(color.color)
                    }else{
                        this.search.option.interiorColorTxtList.splice($.inArray(color.color, this.search.option.interiorColorTxtList),1)
                    } 
                }
                
                this.setCanSearch()
            },
            //车身颜色和内饰颜色 点击不限
            //取消checkbox选中
            //自身添加边框
            //检测是否显示搜索按钮
            selectEm:function(color,index){

                if(index == 0){
                    $.each(app.search.option.bodyColorList,function(index,item){
                        item.isSelect = !1
                    })
                    this.search.option.isBodyColor = !0
                    this.search.option.bodyColorTxtList = []
                    this.search.option.bodyColorTxt = ""
                }
                else if(index == 1){
                    $.each(app.search.option.interiorColorList,function(index,item){
                        item.isSelect = !1
                    })
                    this.search.option.isInteriorColor = !0
                    this.search.option.interiorColorTxtList = []
                    this.search.option.interiorColorTxt = ""
                }
                if(color) color.isSelect = !0
                this.setCanSearch()                    
            },  
            //检测是否显示搜索按钮
            setCanSearch:function(){
                this.search.option.isCanSearch = !0 || !(
                    this.search.option.isDistance &&
                    this.search.option.isBodyColor &&
                    this.search.option.isInteriorColor &&
                    this.search.option.isMileage &&
                    this.search.option.isFactoryMonthly &&
                    this.search.option.isPayment &&
                    this.search.option.isRegApplication && 
                    this.search.option.isEmissionStandards
                )
            },
            //除车身颜色和内饰颜色的点击 
            //自身添加边框 相邻元素移除边框
            setClass:function(d,index){
                if(typeof(index) == undefined || index == 0){
                    $.each(app.search.option.distanceList,function(index,item){
                        item.isSelect = !1
                    })
                }
                else if(index == 1){
                    $.each(app.search.option.mileageList,function(index,item){
                        item.isSelect = !1
                    })
                }
                else if(index == 2){
                    $.each(app.search.option.factoryMonthlyList,function(index,item){
                        item.isSelect = !1
                    })
                }
                else if(index == 3){
                    $.each(app.search.option.emissionStandardsList,function(index,item){
                        item.isSelect = !1
                    })
                }
                else if(index == 4){
                    $.each(app.search.option.paymentList,function(index,item){
                        item.isSelect = !1
                    })
                }
                else if(index == 5){
                    $.each(app.search.option.registrationApplicationList,function(index,item){
                        item.isSelect = !1
                    })
                }
                else if(index == 6){
                    $.each(app.search.option.carCycleList,function(index,item){
                        item.isSelect = !1
                    })
                }
                if(d) d.isSelect = !0 
            },
            //*设置搜索条件值
            //检测是否显示搜索按钮
            setOptionVal:function(index,value){
                
                if(index === 0) this.search.option.area.id = value
                if(index === 1) this.search.option.mileage = value
                if(index === 2) this.search.option.factoryMonthly = value
                if(index === 3) this.search.option.emissionStandards = value
                if(index === 4) this.search.option.payment = value
                if(index === 5) this.search.option.registrationApplication = value
                if(index === 6) this.search.option.carCycle = value

                this.setCanSearch()    
                   
            }
            ,
            selectMileageStatus:function(d){

                if (this.search.option.isSelectCarCycle) return
                $.each(this.search.option.mileageList,function(index,item){
                    item.isSelect = !1
                }) 
                if(d) {
                    d.isSelect = !0
                    this.search.option.mileage = d.id
                } 
                
                this.search.option.isSelectMileage = !0
                this.search.option.isSelectFactoryMonthly = !1
                this.search.option.isSelectCarCycle = !1
                //重置交车周期
                this.search.option.carCycle = "0"
                this.setListToDefaultValue(this.search.option.carCycleList)
                
                if ((this.search.option.mileage == 0 || this.search.option.mileage == -1) && (this.search.option.factoryMonthly == 0 || this.search.option.factoryMonthly == -1)) {
                    this.search.option.isSelectMileage = !1
                    this.search.option.isSelectFactoryMonthly = !1
                }

                this.setCanSearch()    
            }
            ,
            selectFactoryMonthlyStatus:function(d){

                if (this.search.option.isSelectCarCycle) return
                $.each(this.search.option.factoryMonthlyList,function(index,item){
                    item.isSelect = !1
                })
                if(d) {
                    d.isSelect = !0
                    this.search.option.factoryMonthly = d.id
                }
                 
                this.search.option.isSelectMileage = !1
                this.search.option.isSelectFactoryMonthly = !0
                this.search.option.isSelectCarCycle = !1
                //重置交车周期
                this.search.option.carCycle = "0"
                this.setListToDefaultValue(this.search.option.carCycleList)

                if ((this.search.option.mileage == 0 || this.search.option.mileage == -1) && (this.search.option.factoryMonthly == 0 || this.search.option.factoryMonthly == -1)) {
                    this.search.option.isSelectMileage = !1
                    this.search.option.isSelectFactoryMonthly = !1
                }

                this.setCanSearch()    
                
            },
            selectCarCycleStatus:function(d){

                if (this.search.option.isSelectMileage || this.search.option.isSelectFactoryMonthly) return

                $.each(this.search.option.carCycleList,function(index,item){
                    item.isSelect = !1
                })
                if(d) {
                    d.isSelect = !0
                    this.search.option.carCycle = d.id
                }
                  
                this.search.option.isSelectMileage = !1
                this.search.option.isSelectFactoryMonthly = !1
                this.search.option.isSelectCarCycle = !0
                //重置行驶里程
                this.search.option.mileage = "-1"
                this.setListToDefaultValue(this.search.option.mileageList) 
                //重置出厂年月
                this.search.option.factoryMonthly = "-1"
                this.setListToDefaultValue(this.search.option.factoryMonthlyList)

                if (this.search.option.carCycle == 0 || this.search.option.carCycle == -1) {
                    this.search.option.isSelectCarCycle = !1
                }

                this.setCanSearch()    
                
            },
            //初始化特定地区
            clearCenterCity:function(index,value){
                this.search.specifiedArea = ""
                this.search.option.area.display = !1 
            },
            //设置选中的车身颜色
            setBodyColor:function(){
                var _bodyColorArr = []
                $.each(app.search.option.bodyColorList,function(index,item){
                    if (item.isSelect)  _bodyColorArr.push(item.color)
                })
                var _tmpVal = _bodyColorArr.join(",")
                _tmpVal = _tmpVal == "\u4e0d\u9650" ? 0 : _tmpVal
                this.search.option.bodyColor = _tmpVal
            },
            //设置选中的内饰颜色
            setInteriorColor:function(){
                var _interiorColorArr = []
                $.each(app.search.option.interiorColorList,function(index,item){
                    if (item.isSelect)  _interiorColorArr.push(item.color)
                })
                var _tmpVal = _interiorColorArr.join(",")
                _tmpVal = _tmpVal == "\u4e0d\u9650" ? 0 : _tmpVal
                this.search.option.interiorColor = _tmpVal
            },
            //只对高级筛选项的搜索
            getSearchListWithOption:function(){
      
                var _this = this 
                var _param = this.getCommonParam()
                 
                var _opt = {}
                
                if (this.search.option.area.id.toString() != "0" && this.search.option.area.id.toString() != "") {
                    $.extend(true, _opt, {scope:this.search.option.area.id})
                }
                if (this.search.option.bodyColor != "") {
                    $.extend(true, _opt, {body_color:this.search.option.bodyColor})
                }
                if (this.search.option.interiorColor != "") {
                    $.extend(true, _opt, {interior_color:this.search.option.interiorColor})
                }
                if (this.search.option.mileage.toString() != "-1") {
                    $.extend(true, _opt, {mileage:this.search.option.mileage})
                }
                if (this.search.option.factoryMonthly.toString() != "-1") {
                    $.extend(true, _opt, {factory_date:this.search.option.factoryMonthly})
                }
                if (this.search.option.emissionStandards.toString() != "5") {
                    $.extend(true, _opt, {standard:this.search.option.emissionStandards})
                }
                if (this.search.option.registrationApplication.toString() != "0") {
                    $.extend(true, _opt, {car_use:this.search.option.registrationApplication})
                }
                if (this.search.option.carCycle.toString() != "0") {
                    $.extend(true, _opt, {term:this.search.option.carCycle})
                }
                if (this.isCanceContrast) {
                    $.extend(true, _opt, {id_find:this.list.findIdList.join(",")})
                }

                //order
                if (this.search.order.orderBy === "def") {
                    $.extend(true, _opt, {order:'region',sort:'asc'})
                }
                else if (this.search.order.orderBy === "up") {
                    $.extend(true, _opt, {order:'price',sort:'asc'})
                }
                else if (this.search.order.orderBy === "down") {
                    $.extend(true, _opt, {order:'price',sort:'desc'})
                }
 
           
                $.extend(true, _param, _opt)
                this.setListData(_param,false) 

            },
            //页面加载时候的列表
            getSearchList:function(){
                var _param = this.getCommonParam()
                this.setListData(_param,true)
            },
            getCommonParam:function(){
                return {
                    city:this.search.mainOption.area.id,                    //城市ID
                    //车型ID(brand参数代表的就是车型ID...这个很纠结 只靠两个主参数 cityid和searchId)
                    brand:this.search.mainOption.vehicleModel.searchId == -1 
                        ? 
                        this.search.mainOption.brand.id 
                        : 
                        this.search.mainOption.vehicleModel.searchId,     
                    page:this.pageinfo.current,                             //当前分页的第几页 默认1 data->pageinfo->current
                    size:this.pageinfo.perpage,                             //每页显示多少条数据 默认10 data->pageinfo->perpage
                    r:Math.random()                                         //随机数 避免缓存
                } 
            

            },
            //
            setListData:function(_param,isSetSearchOption){
                var _this = this 
                $.ajax({
                     type: "GET",
                     url:_this.url.searchListUrl,
                     data: _param,
                     dataType: "json",
                     beforeSend:function(){
                        _this.isLoading = !0
                        _this.search.searchList = []
                     },
                     success: function(data){
                        _this.isLoading = !1
                        if (isSetSearchOption) {
                            //base
                            var _carTag = data.carTag
                            _this.search.mainOption.guidePrice = typeof(_carTag) === 'undefined' ? "" : _carTag.zhidaojia
                            _this.search.mainOption.seating = typeof(_carTag) === 'undefined' ? "" : _carTag.seat_num + "\u5ea7" 
                            _this.search.mainOption.country = typeof(_carTag) === 'undefined' ? "" : (_carTag.guobie == 0 ? "\u56fd\u4ea7" : "\u8fdb\u53e3")
                            _this.search.mainOption.isLoadOk = !0
                            //添加车身颜色选择tag

                            var _body_color = typeof(_carTag) === 'undefined' ? _carTag : _carTag.body_color
                            if (_body_color) {
                                $.each(_body_color,function(index,item){
                                    _body_color[index] = {color:item,"isSelect":!1}
                                })
                                //不限选项
                                var _limit = {color:"\u4e0d\u9650","isSelect":!0}
                                //_body_color数据显示顺序调整
                                _body_color.reverse()
                                _body_color.push(_limit)
                                _body_color.reverse()
                                _this.search.option.bodyColorList = _body_color 
                             }
                            //添加内饰颜色选择tag
                            var _interior_color = typeof(_carTag) === 'undefined' ? _carTag : _carTag.interior_color 
                            if (_interior_color) {
                                $.each(_interior_color,function(index,item){
                                    _interior_color[index] = {color:item,"isSelect":!1}
                                })
                                _interior_color.reverse()
                                _interior_color.push(Object.assign({}, _limit))
                                _interior_color.reverse()
                                _this.search.option.interiorColorList = _interior_color
                            }
                        }
                        //分页信息              
                        var _pageinfo = data.pages                          //获取分页信息 
                        _this.pageinfo.allpage = typeof(_pageinfo) === 'undefined' ? 1 : _pageinfo.last_page        //设置总页数 分页组价会自动分页
                        //debugger
                        //options
                        //biaozhun ?? body_color chuchang city fukuan interior_color juli licheng page??
                        var _search = typeof(data.search) === 'undefined' ? [] : data.search
                        //list
                        var _datalist = typeof(data.dataList) === 'undefined' ? [] : data.dataList  
                        //extend添加的属性说明
                        //commercialInsurance:指定投保,自由投保鼠标悬浮文字触发机构
                        //registrationService:指定上牌,自选上牌,本人上牌,接受安排鼠标悬浮文字触发机构
                        //temporaryCard:指定服务,自选服务鼠标悬浮文字触发机构
                        //isSelect:列表中checkbox是否选中
                        //isTrShow:用于对比确认后的只显示选中的两行
                        //isTrandBg:行相间背景色切换(当选中两行,点击确定后,背景色不会设置其他行的隐藏而有所变化,所以特意添加此属性用于判断是否是相间的行)
                        try{
                            $.each(_datalist,function(index,item){
                                //处理可售车源位置
                                //服务器端的数据是 "浙江省,杭州市,江苏省,苏州市,上海市,上海市"
                                //显示模式是"浙江省杭州市或江苏省苏州市或上海市" 有重复的就显示一个
                                //更改后的数据是"浙江省杭州市,江苏省苏州市,上海市上海市"
                                //indexOf就是在判断去重
                                var _scope = item.car_scope.split(",")
                                var _scopeTmp = []
                                if (_scope.length >= 1) {
                                    $.each(_scope,function (idx,it) {
                                        if(it.indexOf("\u7701") == -1) _scopeTmp.push(it.slice(it.indexOf("\u5e02") + 1 ))
                                        else _scopeTmp.push(it)
                                        /*if(idx % 2 == 0)
                                            _scopeTmp.push(_scope[idx]+(_scope[idx] == _scope[idx+1] ? "" : _scope[idx+1]))*/
                                    })
                                }
                                item.car_scope = _scopeTmp.join(",")
                                //extend属性
                                $.extend(true, item, {commercialInsurance:!1,registrationService:!1,temporaryCard:!1,isSelect:!1,isTrShow:!0,isTrandBg:index%2});
                                //记住对比的选项 
                                //不支持分页 searchList是实时更新内容的不记录翻页前的记录
                                if(_this.isCanceContrast){
                                    $.each(_this.list.findIdList,function(idx,it){
                                        if (it === item.bj_id) {
                                            item.isSelect = !0
                                        }
                                    })     
                                }    
                            }) 
                        }catch(err){

                        }

                        _this.search.searchList = _datalist
                        _this.isEmpty = _this.search.searchList.length === 0 ? !0 : !1
                       
                     }
                }) 
            },
            //获取车型列表
            getVehicleModeList:function(){
                var _this = this 
                $.ajax({
                     type: "GET",
                     url:_this.url.vehicleModeListUrl,
                     data: {id:_this.search.mainOption.vehicleModel.id},
                     dataType: "json",
                     success: function(data){
                         var _data = []
                         for(key in data) if(key == _this.search.mainOption.vehicleModel.id) _data = data[key]
                         _this.search.vehicleModeList = _data  
                     }
                }) 

            },
            //设置车型
            selectModel:function(gc_id,vehicleMode,key){
                if (gc_id === this.search.mainOption.vehicleModel.searchId){
                    this.getSearchList() 
                }
                this.search.mainOption.vehicleModel.searchId = gc_id
                this.search.mainOption.vehicleModel.text = vehicleMode
                this.search.mainOption.vehicleModel.display = !1 
                //this.getSearchList() 
                this.search.mainOption.vehicleModel.isClear = !1
                this.search.isSelectOption = !1
            },
            //设置车系
            selectCar:function(id,carSeries){
                //this.search.mainOption.carSeries.id = id
                this.search.mainOption.carSeries.text = carSeries
                this.search.mainOption.carSeries.display = !1
                this.search.mainOption.vehicleModel.text = ""  
                this.search.mainOption.vehicleModel.id = id  
                this.clearBaseInfo()
                this.resetContrast() 
                this.resetPageInfo() 
                this.search.mainOption.carSeries.isClear = !1 
            },
            //设置品牌
            selectBrand:function(valid,valtext,imgsrc,carSeriesId){
                this.search.mainOption.brand.id = valid
                this.search.mainOption.brand.text = valtext
                this.search.mainOption.brand.display = !1
                this.search.mainOption.brand.image = imgsrc
                this.search.mainOption.carSeries.id = valid
                this.search.mainOption.carSeries.text = ""
                this.search.mainOption.vehicleModel.text = ""
                this.clearBaseInfo()
                this.resetContrast() 
                this.resetPageInfo() 

            },
            resetPageInfo:function(){
                this.pageinfo = { 
                    current: 1,                                    
                    showItem: 5,                                     
                    allpage: 0,                                     
                    input:"" ,                                      
                    perpage : 10   
                }
            },
            clearBaseInfo:function(){
                this.search.mainOption.carSeries.isClear = !0
                this.search.mainOption.vehicleModel.isClear = !0
                this.search.mainOption.guidePrice = ""
                this.search.mainOption.seating = ""
                this.search.mainOption.country = ""
                this.search.searchList = []
            },
            //获取车系列表
            getCarSeriesList:function(){
                var _this = this 
                $.ajax({
                     type: "GET",
                     url:_this.url.carSeriesListUrl,
                     data: {id:_this.search.mainOption.carSeries.id},
                     dataType: "json",
                     success: function(data){
                         var _data = []
                         for(key in data) if(key == _this.search.mainOption.carSeries.id) _data = data[key] 
                         _this.search.carSeriesList = _data  
                     }
                }) 

            },
            //获取品牌列表
            getBrandList:function(){
                var _this = this 
                $.ajax({
                     type: "GET",
                     url:_this.url.brandListUrl,
                     data: {id:_this.search.mainOption.brand.id},
                     dataType: "json",
                     success: function(data){
                         _this.search.brandList = data      
                     }
                }) 

            },
            //设置从哪个地方出发弹出详细的省份城市选择框
            setCityValIndex:function(index){
                this.search.cityValIndex = index
            },
            //详细的省份城市选择框字母导航
            position:function(event){
                var _target = event.target
                if (_target.tagName === "A")  
                    _target = _target.getElementsByTagName('span')[0]
                $(_target).addClass("cur").parent().siblings().find("span").removeClass('cur')
            },
            //显示详细的省份城市选择框
            showProvince:function(){
                this.search.isShowProvince = !this.search.isShowProvince
                this.search.mainOption.area.display = !1
            },
            //显示特定地区简易城市列表
            showSelfCityList:function(){
                this.search.option.area.display = !this.search.option.area.display
            },
            //检测特定地区是否为空
            //为空间隔200毫秒隐藏
            checkSelfCityList:function(event){
                if (event.target.value == ""){
                    var _this = this
                    setTimeout(function(){
                        _this.search.option.area.display = !1
                    },200)
                }
            },
            //获取地区列表
            getAreaList:function(){
                var _this = this 
                $.ajax({
                     type: "GET",
                     url:_this.url.areaListUrl,
                     data: {},
                     dataType: "json",
                     success: function(data){
                         _this.search.areaList = data  
                     }
                }) 
            },
            //设置城市列表
            setCityList:function(){
                var _this = this
                _this.search.cityList = []
                $.each(_this.search.areaList,function(aindex,aitem){
                    $.each(aitem,function(pindex,pitem){
                        if (pitem.area_name == _this.search.province) {
                            _this.search.cityList = pitem.child
                            return false
                        }
                    })
                }) 

            },
            //设置城市列表
            setSpecificCityList:function(){
                var _this = this
                _this.search.specificCityList = []
                $.each(_this.search.areaList,function(aindex,aitem){
                    $.each(aitem,function(pindex,pitem){
                        if (pitem.area_name == _this.search.specificProvince) {
                            _this.search.specificCityList = pitem.child
                            return false
                        }
                    })
                }) 

            },
            //新车上牌地区,品牌,车系,车型规格的下拉事件
            dropdown:function(index){
                this.search.option.area.display = !1 
                if (index == 0) {
                    this.search.mainOption.area.display = !this.search.mainOption.area.display
                } 
                else if (index == 1) {
                    this.search.mainOption.brand.display = !this.search.mainOption.brand.display
                } 
                else if (index == 2) {
                    this.search.mainOption.carSeries.display = !this.search.mainOption.carSeries.display
                } 
                else if (index == 3) {
                    this.search.mainOption.vehicleModel.display = !this.search.mainOption.vehicleModel.display
                } 
            },
            //设置省份
            setProvince:function(province){

                if(this.search.cityValIndex == 0)
                    this.search.province = province
                if(this.search.cityValIndex == 9)
                    this.search.specificProvince = province
            },
            //简易城市列表和详细的省份城市选择框中城市的选中事件
            SelectCardArea:function(index,valid,valtext,limit){
                if (index == 0) {
                    this.search.mainOption.area.id = valid
                    this.search.mainOption.area.text = valtext
                    this.search.mainOption.area.display = false
                    this.search.mainOption.area.isLimit = limit == 1 ? !0 : !1
                    //判断是否是直辖市 true:不显示省份 false:显示省份
                    //北京,上海,天津,重庆
                    this.isShowProvinceTxt =  this.search.province.trim() === this.search.mainOption.area.text.trim()
                                              ? !1 : !0

                    this.search.isSelectOption = !1
                    this.getSearchList()
                }
                else if (index == 9) {
                    this.search.specifiedArea = valtext
                    this.search.option.area.text = valtext
                    this.search.option.area.id = valtext
                    //this.search.option.area.id = valid
                }
                app.setClass(null,0)

            },
            //高级筛选条件点击事件
            slideOption:function(){
                this.isSlideOption = !this.isSlideOption
                this.SlideOptionTxt = this.isSlideOption ? this.SlideOptionTxtArr[1] :  this.SlideOptionTxtArr[0]
            },
            //"i"字样悬浮显示文字事件
            tip:function(index){
                this.tips[index].isShow = !this.tips[index].isShow 
            },
            //车源列表表格中的指定投保,指定上牌,指定服务
            //的悬浮显示事件
            tblTip:function(car,index){
                if (index == 0) {
                    car.commercialInsurance = !car.commercialInsurance
                }
                else if (index == 1) {
                    car.registrationService = !car.registrationService
                }
                else if (index == 2) {
                    car.temporaryCard = !car.temporaryCard
                }
            },
            //车源列表中checkbox点击事件
            //判断是否显示确定和减除按钮
            //是否选中当前checkbox
            contrast:function(car){
                if (this.isCanceContrast) return
                //if(this.list.selectedCount >=2 && !car.isSelect) return
                car.isSelect = !car.isSelect
                this.list.selectedCount = 
                car.isSelect ? ++this.list.selectedCount
                             : --this.list.selectedCount 
                if(car.isSelect)
                    this.list.findIdList.push(car.bj_id)                             
                
            }, 
            //确定对比
            //只显示勾选的项
            //取消确定按钮
            //显示减除按钮
            //isTrandBg表示表格tr相间行的背景色切换
            sureContrast:function(){
               
                var _flag = !1
                $.each(app.search.searchList,function(index,item){
                    item.isTrShow = item.isSelect
                    if (item.isSelect) {
                        item.isTrandBg = _flag ? 1 : 0
                        _flag = !_flag
                    }
                })
                this.isCanContrast = !1
                this.isCanceContrast = !0 

            }, 
            //取消对比
            //显示所有车源
            //重置选中数量
            //取消减除按钮
            //显示对比文字按钮
            //还原修复背景色的切换
            canceContrast:function(){
                //console.log(app.search.searchList)
                $.each(app.search.searchList,function(index,item){
                    item.isTrShow = !0 
                    if (item.isSelect) {
                        if (index % 2 == 0) 
                            item.isTrandBg = 0
                        if (index % 2 == 1) 
                            item.isTrandBg = 1
                    }
                    item.isSelect = !1    
                })
                this.list.selectedCount = 0
                this.isCanceContrast = !1
                this.list.findIdList = []

                this.getSearchListWithOption()


            }, 
            formatMoney : function (_number,places, symbol, thousand, decimal) {
                places = !isNaN(places = Math.abs(places)) ? places : 2;
                symbol = symbol !== undefined ? symbol : "$";
                thousand = thousand || ",";
                decimal = decimal || ".";
                 
                var number = _number,
                    negative = number < 0 ? "-" : "",
                    i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
                    j = (j = i.length) > 3 ? j % 3 : 0;
                if (number == 0 || number == "") {
                    return ""
                }    
                return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
            },
            //初始化数据
            //页面加载的时候的初始化方法
            //加载新车上牌地区,品牌,车系,车型规格
            //厂商指导价,座位数,生产国别,基本配置
            //车源距离,车身颜色,内饰颜色,行驶里程,出厂年月,排放标准,付款方式,上牌用途
            //车源列表
            initData:function(obj){
                //初始化数据
                this.search.mainOption.brand.id = obj.brandId
                this.search.mainOption.carSeries.id = obj.carSeriesId
                this.search.mainOption.vehicleModel.id = obj.vehicleModelId
                this.search.mainOption.brand.text = obj.brand
                this.search.mainOption.carSeries.text = obj.carSeries
                this.search.mainOption.vehicleModel.text = obj.vehicleModel
                this.search.mainOption.brand.image = this.url.brandImageUrl + obj.brandImage
                //间隔200毫秒根据初始化的数据做查询
                /*setTimeout(function(){
                    app.getSearchList()
                },200)*/
                this.search.mainOption.vehicleModel.searchId = obj.brandId
            }, 
            //设置省份和城市
            //初始化简易城市列表选择框
            initArea:function(obj){
                //设置省份和城市
                this.search.mainOption.area.text = obj.city
                this.search.province = obj.province
                this.search.cityId = obj.cityId
                this.search.mainOption.area.id = obj.cityId
                this.search.specificProvince = obj.province
            },
            initUploadPath:function(_url){
                this.url.brandImageUrl = _url 
            }

        }
        ,
        watch:{
            
            //特定地区变化显示搜索按钮
            'search.specifiedArea':function(){
                this.setCanSearch() 
                this.setSpecificCityList()
            },
            //监控地区列表变化
            //初始化当前省份城市列表
            'search.areaList':function(){
                this.setCityList()
                this.setLimit()
                if(this.isFirstLoading){
                    this.setSpecificCityList()
                    this.isFirstLoading = !1
                }
            },
            //监控省份变化
            //初始化当前省份城市列表
            'search.province':function(news,olds){
                this.setCityList()
            },
            //监控品牌ID变化
            //获取品牌列表
            //主要是用于页面加载的时候初始化品牌列表
            'search.mainOption.brand.id':function(news,olds){
                this.getBrandList()
            },
            //监控车系ID变化
            //获取车系列表
            //用于初始化和选择选择品牌后的车系列表加载
            'search.mainOption.carSeries.id':function(news,olds){
                this.getCarSeriesList()
            },
            //监控车型ID变化
            //获取车型列表
            //用于初始化和选择选择车系后的车型列表加载
            'search.mainOption.vehicleModel.id':function(news,olds){
                this.getVehicleModeList()
            },
            //变相监控车型ID变化
            //获取车源列表
            //用于初始化和选择选择车系后的车源列表加载
            'search.mainOption.vehicleModel.searchId':function(news,olds){
                //console.log(news,olds)
                this.isLoading = !0
                this.resetContrast() 
                this.resetSearch()  
                this.getSearchList() 
            },
            //监控车源选中数量
            //当数量等于2的时候 显示确定和减除按钮
            'list.selectedCount':function(news,olds){
                if(news >= 2) this.isCanContrast = !0
                else  this.isCanContrast = !1
            }
             
            //监控新车上牌地区变化
            //重置筛选条件
            //查询列表
            ,
            'search.mainOption.area.id':function(news,olds){
                this.resetContrast()
                //重置筛选条件
                this.resetSearch() 
            }  
            ,
            'search.option.area.id':function(news,olds){
                this.search.option.area.text = this.getDistanceById(news)
            }
            ,
            'search.option.mileage':function(news,olds){
                if (news == 0) { 
                    this.search.option.mileageTxt = "" 
                    return
                }
                this.search.option.mileageTxt = this.getMileageById(news)
            }  
            ,
            'search.option.factoryMonthly':function(news,olds){
                if (news == 0) { 
                    this.search.option.factoryMonthlyTxt = "" 
                    return
                }
                this.search.option.factoryMonthlyTxt = this.getFactoryMonthlyById(news)
            }  
            ,
            'search.option.bodyColorTxtList.length':function(news,olds){
                this.search.option.bodyColorTxt = this.search.option.bodyColorTxtList.join(",") 
            }  
            ,
            'search.option.interiorColorTxtList.length':function(news,olds){
                this.search.option.interiorColorTxt = this.search.option.interiorColorTxtList.join(",") 
            }  
            

 
        }
         
    })

    //监控滚动条滚动事件
    $(window).scroll(function(event){
        //当滚动条高度超过车源列表table的offsetTop的时候
        //thead元素定位方式变化为fixed,悬浮在车源列表上面
        //当滚动条高度小于车源列表table的offsetTop的时候
        //thead元素移除style属性
        var _tbl = $("#tbl-list").offset().top
        if ($(this).scrollTop() > _tbl - 82 ) {
            $("#tbl-list").find("thead").css({
                position:"fixed",
                top:"88px",
                //left:"0",
                'z-index':999
            })
        }else{
           $("#tbl-list").find("thead").removeAttr("style")
        }
    })

    document.addEventListener("click", function(event){
        var e = window.event || event
        var _tar = $(e.target)
        if(_tar.parents("dl").find(".first-auto").length == 0 )
             app.search.mainOption.area.display = !1
        if(_tar.parents("dl").find(".brand-display-ol").length == 0 )
             app.search.mainOption.brand.display = !1
        if(_tar.parents("dl").find(".car-series-ol").length == 0 )
             app.search.mainOption.carSeries.display = !1
        if(_tar.parents("dl").find(".vehicle-model-txt-p").length == 0 )
             app.search.mainOption.vehicleModel.display = !1 
         if(_tar.parents("li").find(".area-city-layout").length == 0 )
             app.search.option.area.display = !1 
       
    })

 
  
    module.exports = {
        //初始化数据
        //页面加载的时候的初始化方法
        //加载新车上牌地区,品牌,车系,车型规格
        //厂商指导价,座位数,生产国别,基本配置
        //车源距离,车身颜色,内饰颜色,行驶里程,出厂年月,
        //排放标准,付款方式,上牌用途
        //车源列表
        init : function(obj){
            app.initData(obj)
        },
        //设置省份和城市
        //初始化简易城市列表选择框
        initArea : function(obj){
            app.initArea(obj)
        }
        ,
        initUploadPath:function(url){
            app.initUploadPath(url)
        }
    }
});