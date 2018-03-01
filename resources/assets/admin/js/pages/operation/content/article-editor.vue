<template>
    <div class="page" id="article-editor">
        <header>
            <i class="el-icon-arrow-left back" @click="back"></i>
        </header>

        <el-form size="small" label-width="100px">
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
                <el-row class="editor">
                    <el-col class="markdown">
                        <el-input v-model="storeData.markdown" type="textarea" :rows="20" placeholder="请输入正文内容" :style="{height: editorHeight}"></el-input>
                    </el-col>

                    <el-col class="content">
                        <div v-html="storeData.content" class="article-body" ref="content"></div>
                    </el-col>
                </el-row>
            </el-form-item>

            <el-form-item>
                <el-button type="primary" size="mini" @click="handleStore">保存</el-button>
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
            initStartData(id) {
                let self = this;
                if (id) {
                    this.$http.get('/article/' + id).then(resp => {
                        if (resp.data.code === 0) {
                            self.storeData = resp.data.data.article;
                        }
                    });
                } else {
                    this.storeData = {title: null, cover: null, cover_width: 0, cover_height: 0, excerpt: null, weight: 10, display: 1, markdown: '', content: null, first_category_id: null};
                }
            },
            loadCategory() {
                let self = this;
                this.$http.get('/article-categories').then(resp => {
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
                        this.$router.push({path: '/operation/content/article'});
                    }
                };

                if (this.storeData.id) {
                    this.$http.put('/article/' + this.storeData.id, this.storeData).then(cbk);
                } else {
                    this.$http.post('/article', this.storeData).then(cbk);
                }
            }
        },
        watch: {
            'storeData.markdown': _.debounce(function (val) {
                this.storeData.content = marked(val, {sanitize: true});

                if (this.$refs.content) {
                    let self = this;
                    this.$nextTick(() => {
                        self.editorHeight = self.$refs.content.offsetHeight + 'px';
                    });
                }
            }, 300),
            '$route.params.id'(id) {
                this.initStartData(id);
            },
        },
        mounted() {
            this.initStartData(this.$route.params.id);
            this.loadCategory();
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