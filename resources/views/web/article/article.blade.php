<?php /** @var \App\Models\Article $article */ $article = $model; ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$article->title}} - {{config('app.name')}}</title>
    <link rel="stylesheet" href="{{mix('css/app.css')}}">
    <script src="{{mix('js/base.js')}}"></script>
</head>
<body>
@include('web.header')

<section class="main">
    <article class="post">
        <header>
            <h1>{{$article->title}}</h1>
            <span class="meta">
                <span data-time-{{$article->id}}>
                    <script>getReleaseTime('[data-time-{{$article->id}}]', '{{strtotime($article->created_at)}}');</script>
                </span>
                <span data-pv-{{$article->id}}>
                    <script src="{{url('/pv?' . http_build_query(['id' => $article->id, 'dom' => "[data-pv-{$article->id}]"]))}}"></script>
                </span>
            </span>
        </header>

        <div class="article-body">
            {!! $article->content !!}
        </div>
    </article>

    @include('web.footer')
</section>
<script src="{{mix('js/app.js')}}"></script>
</body>
</html>
