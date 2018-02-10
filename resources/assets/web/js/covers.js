import http from 'axios';

let Covers = () => {
    let self = {
        $el: document.querySelector('#header'),
        covers: [],
        offset: 1,
    };

    self.load = () => {
        http.get('/static/covers.json').then(resp => {
            if (resp.data instanceof Array) {
                self.covers = resp.data;

                // 预加载第二张图片
                if (self.covers.length > 1) {
                    self.preload(1);
                }
            }
        });
    };

    self.preload = (offset) => {
        let cover = self.covers[offset];
        if (cover.loaded) {
            return;
        }

        let img = new Image();
        img.src = cover.url;
        cover.loaded = true;
    };

    self.change = () => {
        if (self.covers.length === 0) {
            return;
        }

        let cover = self.covers[self.offset] || null;
        if (!cover) {
            return;
        }

        // 是否已预加载
        if (!cover.loaded) {
            self.preload(self.offset);
            return;
        }

        self.offset++;
        if (self.offset === self.covers.length) {
            self.offset = 0;
        }
        self.preload(self.offset);

        let bgs = self.$el.style.backgroundImage.split(', ');
        bgs[bgs.length - 1] = `url(${cover.url})`;

        self.$el.style.backgroundImage = bgs.join(', ');
        self.$el.querySelector('.cover-source').setAttribute('href', cover.source);
        self.$el.querySelector('.cover-source').innerHTML = cover.title;
    };

    // 判断window是否加载完毕
    (() => {
        let oldonload = window.onload;
        if (typeof window.onload !== 'function') {
            window.onload = () => {
                self.load();
            };
        } else {
            window.onload = function () {
                if (oldonload) {
                    oldonload();
                }

                self.load();
            }
        }
    })();

    setInterval(self.change, 5000);

    return self;
};

export default Covers;