<?php /** @var \App\Models\Article $article */ $article = $model; ?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$article->title}} - {{config('app.name')}}</title>
</head>
<body>
<div id="app">
    {{dump($article->toArray())}}
</div>
</body>
</html>
