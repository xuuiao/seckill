<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>日志排查</title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 40px;
        }
        h1 {
            font-size: 30px;
            line-height: 80px;
        }
        h3 {
            font-size: 20px;
            line-height: 60px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>开发环境日志排查</h1>
        <h3>日志文件：<?=$file?></h3>
        <div class="data">
            <pre><?=$data?></pre>
        </div>
    </div>
</body>
</html>