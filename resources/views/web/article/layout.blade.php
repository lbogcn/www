<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@if(isset($title)){{$title}} - @endif{{config('app.name')}}</title>
    <meta name="description" content="林博，英文名 Lenbo，90后程序猿一枚，目前在广州某互联网公司任职PHP开发工程师，创建博客的目的，是为了记录和分享平时生活的点滴。">
    <link rel="stylesheet" href="{{mix('css/app.css')}}">
</head>
<body>
<div id="app">
    @include('web.header')

    <section class="main">
        <div class="pajx-container">
            @if($type == 'post')@include('web.article.post')@endif
            @if($type == 'list')@include('web.article.list')@endif
        </div>

        @include('web.footer')
    </section>
</div>
<script src="{{mix('js/app.js')}}"></script>
</body>
</html>
