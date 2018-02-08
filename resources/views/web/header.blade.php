<?php
$categories = \App\Models\ArticleCategory::where('display', \App\Models\ArticleCategory::DISPLAY_SHOW)
    ->orderBy('weight', 'desc')
    ->orderBy('id', 'desc')
    ->get();
$cover = \App\Models\Cover::where('display', \App\Models\Cover::DISPLAY_SHOW)
    ->orderBy('weight', 'desc')
    ->orderBy('id', 'desc')
    ->first();
?>
<header class="header" style="background-image: url({{url('/images/overlay-lba-x2.png')}}), url({{$cover->url or ''}})">
    <section class="headings">
        <a href="/">
            <img src="http://cdn.lbog.cn/FtCx3GUQhmC1g2hyxvgRreIr2owN.jpg" class="avatar">
        </a>
        <a href="/">
            <h1>{{config('app.name')}}</h1>
            <h2>林博的博客</h2>
        </a>
    </section>

    <section class="switchboard">
        <ul>
            <li>
                <a href="https://github.com/islenbo" target="_blank"><i class="webfont lb-github"></i></a>
            </li>
        </ul>
    </section>

    <section class="intro">
        <p>large eagle narrate bygone opus.</p>
    </section>

    <section class="categories">
        <ul>
            <?php /** @var \App\Models\ArticleCategory $category */ ?>
            @foreach ($categories as $category)
                <li>
                    <a href="{{$category->url}}">{{$category->title}}</a>
                </li>
            @endforeach
        </ul>
    </section>
</header>