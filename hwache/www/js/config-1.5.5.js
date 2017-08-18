﻿
seajs.config({
    base: './js',
    alias: {
        'avalon': 'vendor/avalon.shim',
        'bt': 'vendor/bootstrap',
        'jq': 'vendor/jquery',
        'cookie': 'vendor/jquery.cookie'
    },
    map:[
        //防止js文件夹下的文件被缓存
        [/(.*js\/[^\/\.]*\.(?:js))(?:.*)/, '$1?_=' + new Date().getTime()]
    ],
    preload: [
        this.avalon ? '' : 'avalon',
        "jq"
    ]
})  

 