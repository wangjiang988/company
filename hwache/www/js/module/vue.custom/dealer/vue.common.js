/*Vue.config.unsafeDelimiters = ['${', '}']*/
/*define(function (require,exports,module) {*/
  var mixin = {
      data: {
          switchs:[{selectEventSwitch:false,value:'',id:''},{selectEventSwitch:false,value:''},{selectEventSwitch:false,value:''},{selectEventSwitch:false,value:''}],
          provinceDisplay:true,
          cityDisplay:false,
          provinceDisplay2:true,
          cityDisplay2:false,
          citylist:[],
          provinceTxt:"",
          cityTxt:"",
          provinceTxt2:"",
          cityTxt2:"",
          cityId:"",
          dealerlist:[]
      },
      methods: {
        showHide: function (index) {
           this.switchs[index].selectEventSwitch = !this.switchs[index].selectEventSwitch
           return false
        }
        ,
        selectEvent: function (index) {
           this.showHide(index)
           return false
        }
        ,//index为switchs中的下标 value 传递的值
        selectElmEvent: function (index,value) {
           this.showHide(index)
           this.switchs[index].value =  value
           return false
        }
        ,
        recoveryAssociated: function (index,value) {
           this.switchs[index+1].value =  value
           return false
        }
        ,
        selectAreaEvent: function (index,isCheckBrand) {
           var _flag = true
           if (isCheckBrand) {
               if (this.switchs[index-1].value.trim() == "") {
                  _flag = false
                  this.switchs[index].value = "<span class='red'>请选选择销售品牌</span>"
               }
           }
           else{

           }
           if (_flag) {
              this.showHide(index)
           }
           
           return false
        }
        ,
        getCity: function (index,pid,value,isSmall) {
           var _this                             = this
           _this.switchs[index].value            = value
           
           if (isSmall) {
              _this.provinceTxt                     = value
              this.formValite.isProvinceDisplay     = false
              _this.provinceDisplay                 = false
              _this.cityDisplay                     = true
           }else{
              _this.provinceTxt2                     = value
              this.formValite.isProvinceDisplay2     = false
              _this.provinceDisplay2                 = false
              _this.cityDisplay2                     = true
              _this.noFindForm.area                  = value                 
           }
           //ajax
           _this.citylist = [] //clear
           $.getJSON("/getcityjosn/"+pid+"/",function(data){
               _this.citylist = data
           })
           
           return false
        }
        ,
        selectCity: function (index,sid,value,isHasSeller) {
           //console.log(index,sid,value,isHasSeller)
           var _this                 = this
           this.switchs[index].value += value 
           
           this.cityId               = sid
           this.showHide(index)
           if (isHasSeller) {
              _this.dealerlist = [] //clear
              var hfbrand = _this.switchs[0].id
              $.getJSON("/dealer/getdealerlist/"+sid+"/"+hfbrand+"/",function(data){
                   _this.dealerlist = data 
                   console.log(_this.switchs[0].id)
                   var _html = ""
                   if (_this.dealerlist.length == 0) {
                      _html                 = '<span class="red">抱歉,没有找到相应的经销商!</span>'
                      _this.sellerInfo.name = ''
                   }else{
                      _html = '请选择经销商'
                   }
                   _this.switchs[index+1].value = _html
              })
              this.cityTxt                  = value
              this.formValite.isCityDisplay = false
              this.provinceDisplay          = true
              this.cityDisplay              = false
           }
           else{
              this.cityTxt2                  = value
              this.formValite.isCityDisplay2 = false
              this.provinceDisplay2          = true
              this.cityDisplay2              = false
              this.noFindForm.area          += value
           }
           
           return false
        }

      }
  }
/*})*/

