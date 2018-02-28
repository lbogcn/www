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
                    <span>{{$article->pv}} 人浏览过</span>
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