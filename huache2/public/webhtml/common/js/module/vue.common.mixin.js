var mixin = {
	methods: {
		//exp:{{ formatMoney(price,2,"￥")}}
		formatMoney : function (_number,places, symbol, thousand, decimal) {
	        places = !isNaN(places = Math.abs(places)) ? places : 2;
	        symbol = symbol !== undefined ? symbol : "$";
	        thousand = thousand || ",";
	        decimal = decimal || ".";
	        var number = _number,
	            negative = number < 0 ? "-" : "",
	            i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
	            j = (j = i.length) > 3 ? j % 3 : 0;
	        /*if (number == 0 || number == "") {
	            return ""
	        }*/  
	        return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
	    },
	    previewFile: function(event,preview) {
			var preview = document.getElementById('upload-img') || document.getElementById(preview)
			var file  = event.target.files[0];
			var reader = new FileReader();
			reader.onloadend = function () {
				preview.src = reader.result
			}
			if (file) {
				reader.readAsDataURL(file)
				this.isUpload = !1
				this.isSelectFile = !0
			} else {
				//preview.src = preview.getAttribute("data-src")
			}
		},
		//银行卡luhm算法
		luhmCheck:function(bankno){
              if (bankno.length < 16 || bankno.length > 50) {
                return false
              }
              var num = /^\d*$/
              if (!num.exec(bankno)) {
                return false
              }
              // var strBin = "10,18,30,35,37,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,58,60,62,65,68,69,84,87,88,94,95,98,99"
              // if (strBin.indexOf(bankno.substring(0, 2)) == -1) {
              //   return false
              // }
              var lastNum = bankno.substr(bankno.length - 1, 1)

              var first15Num = bankno.substr(0, bankno.length - 1)
              var newArr = new Array()
              for (var i = first15Num.length - 1; i > -1; i--) {
                newArr.push(first15Num.substr(i, 1))
              }
              var arrJiShu = new Array()
              var arrJiShu2 = new Array()

              var arrOuShu = new Array()
              for (var j = 0; j < newArr.length; j++) {
                if ((j + 1) % 2 == 1) {
                  if (parseInt(newArr[j]) * 2 < 9)
                    arrJiShu.push(parseInt(newArr[j]) * 2)
                  else
                    arrJiShu2.push(parseInt(newArr[j]) * 2)
                } else
                  arrOuShu.push(newArr[j]);
              }

              var jishu_child1 = new Array()
              var jishu_child2 = new Array()
              for (var h = 0; h < arrJiShu2.length; h++) {
                jishu_child1.push(parseInt(arrJiShu2[h]) % 10)
                jishu_child2.push(parseInt(arrJiShu2[h]) / 10)
              }

              var sumJiShu = 0
              var sumOuShu = 0
              var sumJiShuChild1 = 0
              var sumJiShuChild2 = 0
              var sumTotal = 0
              for (var m = 0; m < arrJiShu.length; m++) {
                sumJiShu = sumJiShu + parseInt(arrJiShu[m])
              }

              for (var n = 0; n < arrOuShu.length; n++) {
                sumOuShu = sumOuShu + parseInt(arrOuShu[n])
              }

              for (var p = 0; p < jishu_child1.length; p++) {
                sumJiShuChild1 = sumJiShuChild1 + parseInt(jishu_child1[p])
                sumJiShuChild2 = sumJiShuChild2 + parseInt(jishu_child2[p])
              }
              sumTotal = parseInt(sumJiShu) + parseInt(sumOuShu) + parseInt(sumJiShuChild1) + parseInt(sumJiShuChild2)
              var k = parseInt(sumTotal) % 10 == 0 ? 10 : parseInt(sumTotal) % 10;
              var luhm = 10 - k

              // if (lastNum == luhm) {
              //   return true
              // } else {
              //   return false
              // }
              return true;
    },
    replaceEmpty:function(str){
    	return str.replace(/^\s+|\s+$/g, '')
    },
    replaceMoney:function(str){
      return str.toString().replace(/,/g, '').replace(/[\￥]/g, '')
    },
    identityCodeValid:function(code){
  			var city={11:"北京",12:"天津",13:"河北",14:"山西",15:"内蒙古",21:"辽宁",22:"吉林",23:"黑龙江 ",31:"上海",32:"江苏",33:"浙江",34:"安徽",35:"福建",36:"江西",37:"山东",41:"河南",42:"湖北 ",43:"湖南",44:"广东",45:"广西",46:"海南",50:"重庆",51:"四川",52:"贵州",53:"云南",54:"西藏 ",61:"陕西",62:"甘肃",63:"青海",64:"宁夏",65:"新疆",71:"台湾",81:"香港",82:"澳门",91:"国外 "}
  			var pass = true
  			if (!code || !/^\d{6}(18|19|20)?\d{2}(0[1-9]|1[12])(0[1-9]|[12]\d|3[01])\d{3}(\d|X)$/i.test(code))
  				pass = false
  			else if (!city[code.substr(0, 2)])
  				pass = false
  			else {
  				if (code.length == 18) {
  					code = code.split('')
  					var factor = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2]
  					var parity = [1, 0, 'X', 9, 8, 7, 6, 5, 4, 3, 2]
  					var sum = 0
  					var ai = 0
  					var wi = 0
  					for (var i = 0; i < 17; i++) {
  						ai = code[i]
  						wi = factor[i]
  						sum += ai * wi
  					}
  					var last = parity[sum % 11]
  					if (parity[sum % 11] != code[17]) {
  						pass = false
  					}
  				}
  			}
  			return pass
    },
    splitBrank:function(num){
        return  num.replace(/[\s]/g, '').replace(/(\d{4})(?=\d)/g, "$1 ")
    },
    isChinese:function(value){
       return /^[\u2E80-\u9FFF]+$/.test(value)
    },
    isPhoneNo:function(phone) {
        var pattern = /^1[34578]\d{9}$/
        return pattern.test(phone)
    },
    prevFocus:function(event){
       $(event.target).prev()[0].focus()
    },
    isCardNum:function(num){
       return /^\d{10,50}$/.test(num)
    }

	}
}