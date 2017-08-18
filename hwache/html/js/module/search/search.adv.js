define(function (require, exports, module) {

    //分页组件
    Vue.component("page-info", {
        template: "#vue-page",
        data: function() {
            return { 
                current: 1,
                showItem: 5,
                allpage: 13,
                input:"",
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
            goto: function(index) {
                if (index == this.current) return
                this.current = index
                //分页请求
                app.getSearchList()
            } 
        }

    })

    //info: !0 == true , !1 == false
    var app = new Vue({
        el: '.SearchPageForm',
        data: {
            url:{
                areaListUrl:"/js/vendor/front-area.js",         //获取地区列表的请求地址
                brandListUrl:"/js/vendor/brand.js",             //获取品牌列表的请求地址
                carSeriesListUrl:"/js/vendor/car-info.js",      //获取车系列表的请求地址
                vehicleModeListUrl:"/js/vendor/car-info.js",    //获取车型列表的请求地址
                searchListUrl:"/js/vendor/data.json",           //获取总的车源列表的请求地址
                brandImageUrl:"http://upload.123.com/"          //修正品牌图片地址(真实环境可置为空或者'http://upload.hwache.com/')
            },
            search:{
                province:"",                                    //当前省份
                areaList:[],                                    //获取的地区列表
                cityList:[],                                    //获取的城市列表
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
                        display:false
                    },
                    //车型下拉框
                    vehicleModel:{
                        id:-1,
                        searchId:-1,                            //触发车源列表加载,搜索 'search.mainOption.vehicleModel.searchId'
                        text:"",
                        display:false
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
                    interiorColorList:[],                       //内饰颜色列表用于显示
                    interiorColor:"",                           //内饰颜色用于搜索
                    mileage:"-1",                               //行驶里程
                    factoryMonthly:"-1",                        //出厂年月
                    emissionStandards:"-1",                     //排放标准
                    registrationApplication:"0",                //上牌用途
                    payment:"0",                                //付款方式                   
                    isDistance : !0,                            //是否车源距离不限
                    isBodyColor : !0,                           //是否车身颜色不限
                    isInteriorColor : !0,                       //是否内饰颜色不限
                    isMileage : !0,                             //是否行驶里程不限
                    isFactoryMonthly : !0,                      //是否出厂年月不限
                    isEmissionStandards : !0,                   //是否排放标准不限
                    isPayment : !0,                             //是否默认付款方式
                    isRegApplication : !0,                      //是否默认上牌用途
                    isCanSearch : !1  ,                         //是否显示搜索按钮
                    //车源距离列表
                    distanceList:[{txt:"\u4e0d\u9650",isSelect:!0,id:0},{txt:"\u4ec5\u770b\u672c\u5730",isSelect:!1,id:1},{txt:"\u5468\u8fb9\u5730\u533a",isSelect:!1,id:2},{txt:"\u66f4\u8fdc\u5730\u533a",isSelect:!1,id:3},{txt:"\u4e2d\u5fc3\u57ce\u5e02",isSelect:!1,id:4,isCenter:!0}],
                    //行驶里程列表
                    mileageList:[{txt:"\u4e0d\u9650",isSelect:!0,id:0},{txt:20,isSelect:!1,id:20},{txt:50,isSelect:!1,id:50},{txt:100,isSelect:!1,id:100}],
                    //出厂年月列表
                    factoryMonthlyList:[{txt:"\u4e0d\u9650",isSelect:!0,id:0},{txt:'\u534a\u5e74\u5185',isSelect:!1,id:6},{txt:'\u4e00\u5e74\u5185',isSelect:!1,id:12},{txt:'\u8fdc\u671f\u5b9a\u5236',isSelect:!1,id:999}],
                    //排放标准列表
                    emissionStandardsList:[{txt:"\u4e0d\u9650",isSelect:!0,id:-1},{txt:'\u56fd\u56db',isSelect:!1,id:0},{txt:'\u56fd\u4e94',isSelect:!1,id:1}],
                    //付款方式列表
                    paymentList:[{txt:"\u5168\u6b3e",isSelect:!0,id:0}],
                    //上牌用途列表
                    registrationApplicationList:[{txt:"\u975e\u8425\u4e1a\u4e2a\u4eba\u6c7d\u8f66",isSelect:!0,id:0},{txt:'\u975e\u8425\u4e1a\u4f01\u4e1a\u6c7d\u8f66',isSelect:!1,id:1}]
                   
                } 

            },
            isCanContrast:false,                                //是否显示对比按钮
            isCanceContrast:false,                              //是否显示取消按钮
            isSlideOption:false,                                //是否显示高级搜索条件
            //高级搜索条件显示触发按钮切换文字
            SlideOptionTxtArr:["\u66f4\u591a\u9009\u9879","\u6536\u8d77\u7b5b\u9009"],
            //高级搜索条件显示触发按钮当前文字
            SlideOptionTxt:"",
            list:{
                selectedCount : 0                               //判断车源列表是否选中了两个
            },
            //"i"文字提示触发条件
            tips:[{isShow:false},{isShow:false},{isShow:false},{isShow:false},{isShow:false},{isShow:false},{isShow:false},{isShow:false},{isShow:false},{isShow:false},{isShow:false}]
        },
        mounted:function(){
            this.getAreaList() 
            this.SlideOptionTxt = this.SlideOptionTxtArr[0]
            this.getBrandList() 
        }
        ,
        methods:{
           
            //车源列表排序后
            //重置列表行背景色相间性问题
            orderList:function(tag,event){
                //if (this.isCanceContrast) return
                var _target = event.target
                if (_target.tagName === "SPAN" || _target.tagName === "I")  
                    _target = _target.parentNode
                _target.className = "order-selected"
                $(_target).siblings().removeClass('order-selected')

                if (tag === "def") {

                }
                else if (tag === "up") {
                    this.search.searchList.sort(function(a, b){
                        return a.bj_lckp_price - b.bj_lckp_price
                    })
                    
                }
                else if (tag === "down") {
                    this.search.searchList.sort(function(a, b){
                        return b.bj_lckp_price - a.bj_lckp_price
                    })
                }

                this.resetTrandBg()
                
            },
            //重置列表行背景色相间性问题
            resetTrandBg:function(obj){
                $.each(this.search.searchList,function(index,item){
                    item.isTrandBg = index % 2
                })
            },
            //重置搜索条件
            resetSearch:function(obj){
                this.getSearchListWithOption()
            },
            //搜索条件选择好后的搜索点击事件
            searchValite:function(obj){
                this.setBodyColor()
                this.setInteriorColor()
                this.getSearchListWithOption()
            },
            //车身颜色和内饰颜色
            //取消不限选中
            //字体变黄色
            //选中
            //检测是否显示搜索按钮
            setColorClass:function(color,index){
                if(index == 0){
                    app.search.option.bodyColorList[0].isSelect = !1
                    this.search.option.isBodyColor = !1
                }
                else if(index == 1){
                    app.search.option.interiorColorList[0].isSelect = !1
                    this.search.option.isInteriorColor = !1
                }
                if(color) color.isSelect = !color.isSelect 
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
                }
                else if(index == 1){
                    $.each(app.search.option.interiorColorList,function(index,item){
                        item.isSelect = !1
                    })
                    this.search.option.isInteriorColor = !0
                }
                if(color) color.isSelect = !0
                this.setCanSearch()                    
            },  
            //检测是否显示搜索按钮
            setCanSearch:function(){
                this.search.option.isCanSearch = !(
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

                this.setCanSearch()    
                   
            },
            //初始化特定地区
            clearCenterCity:function(index,value){
                this.search.specifiedArea = ""
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
                _this.search.mainOption.isLoadOk = !1
                $.ajax({
                     type: "GET",
                     url:_this.url.searchListUrl,
                     data: {
                        brandId:_this.search.mainOption.brand.id,
                        carSeriesId:_this.search.mainOption.carSeries.id,
                        vehicleModelId:_this.search.mainOption.vehicleModel.searchId
                        //其他参数
                     },
                     dataType: "json",
                     success: function(data){
                         
                        _this.search.mainOption.isLoadOk = !0 
                        //biaozhun ?? body_color chuchang city fukuan interior_color juli licheng page??
                        var _search = data.search
                        //list
                        var _datalist = data.dataList
                        //extend添加的属性说明
                        //commercialInsurance:指定投保,自由投保鼠标悬浮文字触发机构
                        //registrationService:指定上牌,自选上牌,本人上牌,接受安排鼠标悬浮文字触发机构
                        //temporaryCard:指定服务,自选服务鼠标悬浮文字触发机构
                        //isSelect:列表中checkbox是否选中
                        //isTrShow:用于对比确认后的只显示选中的两行
                        //isTrandBg:行相间背景色切换(当选中两行,点击确定后,背景色不会设置其他行的隐藏而有所变化,所以特意添加此属性用于判断是否是相间的行)
                        $.each(_datalist,function(index,item){
                            $.extend(true, item, {commercialInsurance:!1,registrationService:!1,temporaryCard:!1,isSelect:!1,isTrShow:!0,isTrandBg:index%2});
                        })
                        _this.search.searchList = _datalist
                     }
                }) 

            },
            //页面加载时候的列表
            getSearchList:function(){
                var _this = this 
                $.ajax({
                     type: "GET",
                     url:_this.url.searchListUrl,
                     data: {
                        brandId:_this.search.mainOption.brand.id,
                        carSeriesId:_this.search.mainOption.carSeries.id,
                        vehicleModelId:_this.search.mainOption.vehicleModel.searchId,
                        r:Math.random()
                        //其他参数
                     },
                     dataType: "json",
                     success: function(data){
                        //base
                        var _carTag = data.carTag
                        _this.search.mainOption.guidePrice = _carTag.zhidaojia
                        _this.search.mainOption.seating = _carTag.seat_num 
                        _this.search.mainOption.country = _carTag.guobie == 0 ? "\u56fd\u4ea7" : "\u8fdb\u53e3"
                        _this.search.mainOption.isLoadOk = !0
                        //添加车身颜色选择tag
                        var _body_color = _carTag.body_color
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
                        /*_bodyTmp = _body_color[0]
                        _body_color[0] = _limit
                        _body_color[_body_color.length-1] = _bodyTmp
                        */
                        //添加内饰颜色选择tag
                        var _interior_color = _carTag.interior_color
                        $.each(_interior_color,function(index,item){
                            _interior_color[index] = {color:item,"isSelect":!1}
                        })
                        _interior_color.reverse()
                        _interior_color.push(Object.assign({}, _limit))
                        _interior_color.reverse()
                        _this.search.option.interiorColorList = _interior_color
                        //options
                        //biaozhun ?? body_color chuchang city fukuan interior_color juli licheng page??
                        var _search = data.search
                        //list
                        var _datalist = data.dataList
                        //extend添加的属性说明
                        //commercialInsurance:指定投保,自由投保鼠标悬浮文字触发机构
                        //registrationService:指定上牌,自选上牌,本人上牌,接受安排鼠标悬浮文字触发机构
                        //temporaryCard:指定服务,自选服务鼠标悬浮文字触发机构
                        //isSelect:列表中checkbox是否选中
                        //isTrShow:用于对比确认后的只显示选中的两行
                        //isTrandBg:行相间背景色切换(当选中两行,点击确定后,背景色不会设置其他行的隐藏而有所变化,所以特意添加此属性用于判断是否是相间的行)
                        $.each(_datalist,function(index,item){
                            $.extend(true, item, {commercialInsurance:!1,registrationService:!1,temporaryCard:!1,isSelect:!1,isTrShow:!0,isTrandBg:index%2});
                        })
                        _this.search.searchList = _datalist
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
                this.search.mainOption.vehicleModel.searchId = gc_id
                this.search.mainOption.vehicleModel.text = vehicleMode
                this.search.mainOption.vehicleModel.display = !1 
            },
            //设置车系
            selectCar:function(id,carSeries){
                //this.search.mainOption.carSeries.id = id
                this.search.mainOption.carSeries.text = carSeries
                this.search.mainOption.carSeries.display = !1
                this.search.mainOption.vehicleModel.text = ""  
                this.search.mainOption.vehicleModel.id = id  
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
            //新车上牌地区,品牌,车系,车型规格的下拉事件
            dropdown:function(index){
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
            },
            //简易城市列表和详细的省份城市选择框中城市的选中事件
            SelectCardArea:function(index,valid,valtext,limit){
                if (index == 0) {
                    this.search.mainOption.area.id = valid
                    this.search.mainOption.area.text = valtext
                    this.search.mainOption.area.display = false
                    this.search.mainOption.area.isLimit = limit == 1 ? !0 : !1
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
                if(this.list.selectedCount >=2 && !car.isSelect) return
                car.isSelect = !car.isSelect
                this.list.selectedCount = 
                car.isSelect ? ++this.list.selectedCount
                             : --this.list.selectedCount 
                
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
                app.list.selectedCount = 0
                this.isCanceContrast = !1
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
                this.search.mainOption.brand.image = obj.brandImage
                //间隔200毫秒根据初始化的数据做查询
                setTimeout(function(){
                    app.getSearchList()
                },200)
            }, 
            //设置省份和城市
            //初始化简易城市列表选择框
            initArea:function(obj){
                //设置省份和城市
                this.search.mainOption.area.text = obj.city
                this.search.province = obj.province
            }

        }
        ,
        watch:{
            //监控地区列表变化
            //初始化当前省份城市列表
            'search.areaList':function(){
                this.setCityList()
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
                this.getSearchList()
            }
            //监控车源距离变化
            //检测是否显示搜索按钮
            ,
            'search.option.area.id':function(news,olds){
                if(news == 0 || parseInt(news) === 0) this.search.option.isDistance = !0
                else  this.search.option.isDistance = !1
                this.setCanSearch()    
            },
            //监控行驶里程变化
            //检测是否显示搜索按钮
            'search.option.mileage':function(news,olds){
                if(news == 0 || parseInt(news) === 0) this.search.option.isMileage = !0
                else  this.search.option.isMileage = !1
                this.setCanSearch()
            },
            //监控出厂年月变化
            //检测是否显示搜索按钮
            'search.option.factoryMonthly':function(news,olds){
                if(news == 0 || parseInt(news) === 0) this.search.option.isFactoryMonthly = !0
                else  this.search.option.isFactoryMonthly = !1 
                this.setCanSearch()                    
            }, 
            //监控排放标准变化
            //检测是否显示搜索按钮
            'search.option.emissionStandards':function(news,olds){
                if(news == -1 || parseInt(news) === -1) this.search.option.isEmissionStandards = !0
                else  this.search.option.isEmissionStandards = !1
                this.setCanSearch()
            },
            //监控付款方式变化
            //检测是否显示搜索按钮
            'search.option.payment':function(news,olds){
                if(news == 0 || parseInt(news) === 0) this.search.option.isPayment = !0
                else  this.search.option.isPayment = !1
                this.setCanSearch()
            },
            //监控上牌用途变化
            //检测是否显示搜索按钮
            'search.option.registrationApplication':function(news,olds){
                if(news == 0 || parseInt(news) === 0) this.search.option.isRegApplication = !0
                else  this.search.option.isRegApplication = !1
                this.setCanSearch()
            },
            //监控车源选中数量
            //当数量等于2的时候 显示确定和减除按钮
            'list.selectedCount':function(news,olds){
                if(news == 2) this.isCanContrast = !0
                else  this.isCanContrast = !1
            },

            


            
  
            
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
    }
});