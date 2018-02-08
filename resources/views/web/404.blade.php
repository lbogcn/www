<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - {{config('app.name')}}</title>
    <link rel="stylesheet" href="{{mix('css/app.css')}}">
</head>
<body>
@include('web.header')

<section class="main">
    <section class="articles">
        <p>404咯，你是不是进错地方了？</p>
    </section>

    @include('web.footer')
</section>

<script src="{{mix('js/app.js')}}"></script>
</body>
</html>
