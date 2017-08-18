
seajs.config({
    base: './js',
    alias: {
        'jq': '/webhtml/common/js/vendor/jquery',
        'avalon': '/js/vendor/avalon'
    },
    map:[
        [/(.*js\/[^\/\.]*\.(?:js))(?:.*)/, '$1?_=' + new Date().getTime()]
    ],
    preload: [
        "jq",
        this.avalon ? '' : 'avalon',
    ]
})

