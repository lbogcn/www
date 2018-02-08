<?php
$categories = \App\Models\ArticleCategory::where('display', \App\Models\ArticleCategory::DISPLAY_SHOW)
    ->orderBy('weight', 'desc')
    ->orderBy('id', 'desc')
    ->get();
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@if(isset($sub_title)){{$sub_title}} - @endif{{config('app.name')}}</title>
    <link rel="stylesheet" href="{{mix('css/app.css')}}">
</head>
<body>
@include('web.header')

<section class="main">
    <section class="articles">
        <?php /** @var \App\Models\Article $article */ ?>
        @forelse($articles as $article)
            <article>
                <header>
                    <a href="{{$article->url}}">
                        <h2>{{$article->title}}</h2>
                        <p>{{$article->excerpt}}</p>
                    </a>
                    <span class="meta">
                        <span>{{$article->release_time}}</span>
                    </span>
                </header>

                <footer>
                    <a href="{{$article->url}}">
                        <div class="cover" style="height: {{$article->cover_height}}px; background-image: url({{$article->cover}});"></div>
                    </a>
                </footer>
            </article>
        @empty
            <p>作者有点懒，什么都没留下~</p>
        @endforelse
    </section>

    <?php /** @var \Illuminate\Pagination\Paginator $articles */ ?>
    {{$articles->render()}}

    @include('web.footer')
</section>

<script src="{{mix('js/app.js')}}"></script>
</body>
</html>
