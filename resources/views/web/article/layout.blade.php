<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@if(isset($title)){{$title}} - @endif{{config('app.name')}}</title>
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
