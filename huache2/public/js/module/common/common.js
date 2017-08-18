
define(function (require, exports, module) {
	if (document.getElementById('smiple-login')) require("./simple-login")
    
    require("/webhtml/common/js/module/head")
    if ($(".slide")[0]) 
        require("/webhtml/common/js/module/left")
});