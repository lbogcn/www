<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$main_title}}</title>
    <style>
        .header {width: 35%;position: fixed;left: 0;top: 0;bottom:0;    background-color: #ddd;
            background-image: url("https://blog.dandyweng.com/wp-content/themes/albatross/images/overlay-lba-x2.png"),url("https://blog.dandyweng.com/wp-content/themes/albatross/backgrounds/03.jpg");
            background-repeat: repeat, no-repeat;
            background-size: auto, cover;
            transition: background-image 2s ease-in-out;
            will-change: transition;}
        .headings {margin-top: 60px; text-align: center;}
        .avatar {
            max-width: 75px;
            margin-top: 5px;
            margin-bottom: 10px;
            border-radius: 100%;
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.5), 0 2px 20px 3px rgba(0, 0, 0, 0.25);
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="headings">
            <a href="">
                <img src="http://cdn.lbog.cn/FtCx3GUQhmC1g2hyxvgRreIr2owN.jpg" class="avatar">
                <h2>{{$main_title}}</h2>
                <h3>{{$sub_title}}</h3>
            </a>
        </div>

        <div class="switchboard">
            <a href="https://github/islenbo"><img src="github"></a>
        </div>

        <div class="intro">
            <p>{{$intro}}</p>
        </div>
    </div>
    <div class="main">

    </div>
</body>
</html>
