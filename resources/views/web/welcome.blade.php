<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$main_title}}</title>
    <link rel="stylesheet" href="{{mix('css/app.css')}}">
</head>
<body>
    <div class="header" style="background-image: url({{url('/images/overlay-lba-x2.png')}}), url({{$cover}})">
        <section class="headings">
            <a href="">
                <img src="http://cdn.lbog.cn/FtCx3GUQhmC1g2hyxvgRreIr2owN.jpg" class="avatar">
            </a>
            <a href="">
                <h1>{{$main_title}}</h1>
                <h2>{{$sub_title}}</h2>
            </a>
        </section>

        <section class="switchboard">
            <ul>
                <li>
                    <a href="https://github.com/islenbo" target="_blank">
                        <i class="lb-github"></i>
                    </a>
                </li>
            </ul>
        </section>

        <section class="intro">
            <p>{{$intro}}</p>
        </section>

        <section class="categories">
            <ul>
                <?php /** @var \App\Models\ArticleCategory $category */ ?>
                @foreach ($categories as $category)
                    <li>
                        <a href="">{{$category->title}}</a>
                    </li>
                @endforeach
            </ul>
        </section>

        <section class="copyright">
            <p>&copy; {{date('Y')}} lenbo 粤ICP备17008394号-1</p>
        </section>
    </div>

    <div class="main">

    </div>
</body>
</html>
