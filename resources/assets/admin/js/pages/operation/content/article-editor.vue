<template>
    <div class="page" id="article-editor">
        <header>
            <i class="el-icon-arrow-left back" @click="back"></i>
        </header>

        <el-form label-width="100px">
            <el-form-item label="标题" class="default-form-item">
                <el-input v-model="storeData.title"></el-input>
            </el-form-item>

            <el-form-item label="封面">
                <el-col style="width: auto">
                    <el-button-upload @success="handleSuccessUploadCover">上传</el-button-upload>
                </el-col>

                <el-col :span="10" :offset="1">
                    <img :src="storeData.cover" class="cover-preview">
                </el-col>
            </el-form-item>

            <el-form-item label="所属类目">
                <el-select v-model="storeData.first_category_id">
                    <el-option v-for="category in categories" :key="category.id" :value="category.id" :label="category.title"></el-option>
                </el-select>
            </el-form-item>

            <el-form-item label="权重" class="default-form-item">
                <el-input v-model="storeData.weight"></el-input>
            </el-form-item>

            <el-form-item label="显示状态">
                <el-select v-model="storeData.display">
                    <el-option :value="1" label="显示"></el-option>
                    <el-option :value="2" label="隐藏"></el-option>
                </el-select>
            </el-form-item>

            <el-form-item label="摘要" class="default-form-item">
                <el-input v-model="storeData.excerpt" type="textarea" :rows="3" placeholder="限500字内"></el-input>
            </el-form-item>

            <el-form-item label="内容">
                <el-row>
                    <el-button-upload icon="el-icon-picture" type="default" @success="handleSuccessUploadArticleImage"> </el-button-upload>
                </el-row>

                <el-row class="editor">
                    <el-col class="markdown">
                        <el-input v-model="storeData.markdown" type="textarea" :rows="20" ref="markdown" placeholder="请输入正文内容" :style="{height: editorHeight}"></el-input>
                    </el-col>

                    <el-col class="content">
                        <div v-html="storeData.content" class="article-body" ref="content"></div>
                    </el-col>
                </el-row>
            </el-form-item>

            <el-form-item>
                <el-button type="primary" @click="handleStore">保存</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
    import marked from 'marked';
    import _ from 'lodash';

    export default {
        data() {
            return {
                storeData: {
                    id: null, title: null, cover: null, cover_width: 0, cover_height: 0, excerpt: null, weight: 10, display: 1, markdown: '', content: null
                },
                editor: null,
                categories: [],
                editorHeight: 'auto',
            };
        },
        methods: {
            back() {
                window.history.back();
            },
            handleSuccessUploadCover(data) {
                this.storeData.cover = data.url;
                this.storeData.cover_width = parseInt(data.body.width);
                this.storeData.cover_height = parseInt(data.body.height);
            },
            handleSuccessUploadArticleImage(data) {
                let $ctn = this.$refs.markdown.$el.querySelector('textarea');
                window.ctn = $ctn;
                let selectionStart = $ctn.selectionStart;
                let left  = this.storeData.markdown.substr(0, selectionStart);
                let right = this.storeData.markdown.substr(selectionStart);
                let image = `\n![](${data.url})\n`;

                this.storeData.markdown = left + image + right;

                this.$nextTick(() => {
                    $ctn.focus();
                    $ctn.selectionStart = selectionStart + 3;
                    $ctn.selectionEnd = selectionStart + 3;
                });
            },
            initStartData(id) {
                let self = this;

                return new Promise((resolve) => {
                    if (id) {
                        this.$http.get('/article/' + id).then(resp => {
                            if (resp.data.code === 0) {
                                self.storeData = resp.data.data.article;
                            }
                        }).then(resolve, resolve);
                    } else {
                        this.storeData = {title: null, cover: null, cover_width: 0, cover_height: 0, excerpt: null, weight: 10, display: 1, markdown: '', content: null, first_category_id: null};
                        resolve();
                    }
                });
            },
            loadCategory() {
                let self = this;
                return this.$http.get('/article-categories').then(resp => {
                    if (resp.data.code === 0) {
                        self.categories = resp.data.data.categories;
                    }
                });
            },
            handleStore() {
                let self = this;
                let cbk = resp => {
                    if (resp.data.code === 0) {
                        self.$message({type: 'success', message: '保存成功!'});

                        // 删除本地备份
                        localStorage.removeItem(this.backupKey());

                        this.$router.push({path: '/operation/content/article'});
                    }
                };

                if (this.storeData.id) {
                    this.$http.put('/article/' + this.storeData.id, this.storeData).then(cbk);
                } else {
                    this.$http.post('/article', this.storeData).then(cbk);
                }
            },
            loadLocalStorage(backupData) {
                let self = this;
                if (backupData && backupData !== self.storeData.markdown) {
                    this.$confirm('检测到本地有备份数据，是否恢复？', {
                        confirmButtonText: '确定',
                        cancelButtonText: '取消',
                        type: 'warning'
                    }).then(() => {
                        self.storeData.markdown = backupData;
                    }, () => {
                        localStorage.removeItem(this.backupKey());
                    });
                }
            },
            backupKey() {
                return 'article-backup-' + this.$route.path.substr(16, this.$route.path.length);
            },
        },
        watch: {
            'storeData.markdown': _.debounce(function (val) {
                this.storeData.content = marked(val, {sanitize: true});

                // 本地备份
                localStorage.setItem(this.backupKey(), val.trim());

                if (this.$refs.content) {
                    let self = this;
                    this.$nextTick(() => {
                        let height = self.$refs.content.offsetHeight;
                        if (height < 300) {
                            height = 300;
                        }

                        self.editorHeight = height + 'px';
                    });
                }
            }, 300),
            '$route.params.id'(id) {
                this.initStartData(id);
            },
        },
        mounted() {
            let self = this;
            let  backupData = localStorage.getItem(this.backupKey());

            self.initStartData(this.$route.params.id).then(() => {
                self.loadCategory().then(() => {
                    self.loadLocalStorage(backupData);
                });
            });
        }
    }
</script>

<style lang="less">
    #article-editor {
        header{
            margin-bottom: 15px;

            .back{
                cursor: pointer;
            }
        }

        .editor{
            display: flex;

            .markdown {
                padding: 5px;
                flex: 4;
                border-right: 2px #ccc dashed;

                textarea{
                    height: 100%;
                }
            }

            .content {
                padding: 5px;
                flex: 6;
            }
        }

        .cover-preview{
            height: 100px;
            width: auto;
        }

        .default-form-item{
            width: 500px;
        }
    }
</style>