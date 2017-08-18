# 短信路由说明

## 路由地址


    http://dev.hwache.cn/member/sendSms?phone=18626210573&type=78735055

## 请求方式 
    get,post


## 请求参数

###请求参数分为两种， 一种模板参数（get），另一种为接口参数(get or post)

请参照短信模板里边的参数  
如短信模板为：
    
`您于${time}收到新订单${order}，反馈时间20分钟，请立即处理。  `

则请求则请求参数为

    `phone`           (电话号码, 接口要求参数 get参数)
    `type`  （模板code，接口要求参数， get参数）   
    `time`   （模板要求参数, 不过这个time由后台生成，不用传参数）
    `order`  （模板要求参数）

```注意：所有模板要求参数请求参数中,  code ,  time 由后台生成，不用传参 ```


## 返回错误
    [
        'error_code' => 1,
        'success'    => false,
        'error_msg'  => '没有该短信模板或者参数不完整',
    ];








