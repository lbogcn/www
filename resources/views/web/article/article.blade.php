<?php /** @var \App\Models\Article $article */ $article = $model; ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$article->title}} - {{config('app.name')}}</title>
    <link rel="stylesheet" href="{{mix('css/app.css')}}">
</head>
<body>
@include('web.header')

<section class="main">
    <article class="post">
        <header>
            <h1>{{$article->title}}</h1>
            <span class="meta">
                <span>{{$article->release_time}}</span>
            </span>
        </header>

        <div class="content">
            {!! $article->content !!}
        </div>
    </article>

    @include('web.footer')
</section>
<script src="{{mix('js/app.js')}}"></script>
</body>
</html>
