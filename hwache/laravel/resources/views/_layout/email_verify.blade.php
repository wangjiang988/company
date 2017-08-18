<!doctype html>
<html lang="zh-cn">
<head>
<meta charset="UTF-8">
<title>华车</title>
<style>
  body{margin: 0;padding: 20px;}
</style>
</head>
<body>
<p>{{ $name }}，您好：</p>
<p>欢迎您选择了 {{ $email }} 作为您的华车邮箱。为验证此电子邮件地址属于您，请点击下方链接确认即可。</p>
<p><a href="{{ $url }}">{{ $url }}</a></p>
<p>(如果该链接无法点击，请将它完成复制并粘贴至浏览器的地址栏中访问)</p>
<p>此致</p>
<p>华车</p>
</body>
</html>