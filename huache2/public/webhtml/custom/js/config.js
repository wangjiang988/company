
seajs.config({
    base: './js',
    alias: {
        'jq': '/webhtml/common/js/vendor/jquery'
    },
    map:[
        [/(.*js\/[^\/\.]*\.(?:js))(?:.*)/, '$1?_=' + new Date().getTime()]
    ],
    preload: [
        "jq"
    ]
})  

 