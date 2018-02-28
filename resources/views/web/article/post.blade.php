<article class="post">
    <header>
        <h1>{{$article->title}}</h1>
        <span class="meta">
                <span>{{$article->release_time}}</span>
                <span>{{$article->pv}} 人浏览过</span>
            </span>
    </header>

    <div class="article-body">
        {!! $article->content !!}
    </div>
</article>