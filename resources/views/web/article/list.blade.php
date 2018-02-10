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
    <script src="{{mix('js/base.js')}}"></script>
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
                        <span data-time-{{$article->id}}>
                            <script>getReleaseTime('[data-time-{{$article->id}}]', '{{strtotime($article->created_at)}}');</script>
                        </span>
                        <span data-pv-{{$article->id}}>
                            <script src="{{url('/pv?' . http_build_query(['id' => $article->id, 'read' => 1, 'dom' => "[data-pv-{$article->id}]"]))}}"></script>
                        </span>
                    </span>
                </header>

                <footer>
                    <a href="{{$article->url}}">
                        <div class="cover" style="background-image: url({{$article->cover}})"></div>
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
