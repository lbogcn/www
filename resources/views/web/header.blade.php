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
<header class="header" id="header" style="background-image: url({{url('/images/overlay-lba-x2.png')}}), url({{$cover->url or ''}})">
    <section class="headings">
        <a href="/">
            <img src="{{\App\Models\KeyValue::getValue('web-avatar')}}" class="avatar">
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

    @if($cover)
        <span class="corner">图片出处：<a href="{{$cover->source}}" target="_blank" class="cover-source">{{$cover->title}}</a></span>
    @endif
</header>

<div class="circle-loader hide">
    <div class="circle-line">
        <div class="circle circle-blue"></div>
        <div class="circle circle-blue"></div>
        <div class="circle circle-blue"></div>
    </div>
    <div class="circle-line">
        <div class="circle circle-yellow"></div>
        <div class="circle circle-yellow"></div>
        <div class="circle circle-yellow"></div>
    </div>
    <div class="circle-line">
        <div class="circle circle-red"></div>
        <div class="circle circle-red"></div>
        <div class="circle circle-red"></div>
    </div>
    <div class="circle-line">
        <div class="circle circle-green"></div>
        <div class="circle circle-green"></div>
        <div class="circle circle-green"></div>
    </div>
</div>
