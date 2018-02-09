<template>
    <div class="page">
        <h2 class="page-header">文章管理</h2>

        <el-row>
            <el-col class="text-right">
                <el-button-search @click="searchFormData.visible = true">搜索</el-button-search>
                <el-button-create @click="handleCreate">新建</el-button-create>
                <el-button type="danger" size="mini" @click="handleStatic" icon="el-icon-lb-sync">静态化</el-button>
            </el-col>
        </el-row>

        <el-row>
            <el-col>
                <el-table size="small" :data="paginate.data" stripe>
                    <el-table-column prop="title" label="标题"></el-table-column>
                    <el-table-column prop="author" label="作者"></el-table-column>
                    <el-table-column prop="cover" label="封面图片">
                        <template slot-scope="scope">
                            <img :src="scope.row.cover" class="img-responsive">
                        </template>
                    </el-table-column>
                    <el-table-column prop="excerpt" label="摘要">
                        <template slot-scope="scope">
                            <el-tooltip effect="dark" placement="bottom">
                                <div slot="content" class="text-word-break-all">{{scope.row.excerpt}}</div>
                                <p class="text-ellipsis">{{scope.row.excerpt}}</p>
                            </el-tooltip>
                        </template>
                    </el-table-column>
                    <el-table-column prop="weight" label="权重" width="80px"></el-table-column>
                    <el-table-column prop="pv" label="PV" width="80px"></el-table-column>
                    <el-table-column prop="display" label="状态">
                        <template slot-scope="scope">
                            <el-tag size="small" type="success" v-if="scope.row.display === 1">显示</el-tag>
                            <el-tag size="small" type="danger" v-if="scope.row.display !== 1">隐藏</el-tag>

                            <el-tag size="small" type="default" v-if="scope.row.has_static">已静态化</el-tag>
                            <el-tag size="small" type="warning" v-if="!scope.row.has_static">未静态化</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column prop="created_at" label="创建时间"></el-table-column>
                    <el-table-column label="操作">
                        <template slot-scope="scope">
                            <el-button @click="handleEdit(scope.row.id)" type="text" size="mini">编辑</el-button>
                            <el-button @click="handleUnitStatic(scope.row.id)" type="text" size="mini">静态化</el-button>
                            <el-button @click="handleDelete(scope.$index, scope.row)" type="text" size="mini">删除</el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </el-col>
        </el-row>

        <el-row>
            <el-col>
                <el-pagination class="pull-right" :current-page.sync="paginate.current_page" :page-size="paginate.per_page" :total="paginate.total" @current-change="search" layout="total, prev, pager, next"></el-pagination>
            </el-col>
        </el-row>

        <search-form :data.sync="searchFormData" @submit="search"></search-form>

        <el-dialog :visible.sync="dialogVisibleStatic" :modal-append-to-body="false" :close-on-click-modal="false" title="静态化" class="default-dialog">
            <el-row>
                <el-col>
                    <el-progress :text-inside="true" :stroke-width="18" :percentage="staticProgress"></el-progress>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    <el-button type="primary" size="mini" @click="handleStaticSubmit" :loading="0 < staticProgress && staticProgress < 100" :disabled="staticProgress === 100">开始</el-button>
                </el-col>
            </el-row>
        </el-dialog>
    </div>
</template>

<script>
    import qs from 'qs';
    export default {
        data() {
            return {
                paginate: {
                    data: [],
                    current_page: 1,
                    per_page: 0,
                    total: 0,
                },
                storeData: {},

                searchFormData: {
                    visible: false,
                    formItem: [
                        {
                            label: '作者',
                            field: 'author',
                            type: 'input',
                        },
                    ],
                    formData: {
                        author: null,
                    },
                },

                dialogVisibleStatic: false,
                staticProgress: 0,
            };
        },
        methods: {
            search() {
                let self = this;
                let action = '/article?' + qs.stringify({
                    search: this.searchFormData.formData,
                    page: this.paginate.current_page,
                });

                this.$http.get(action).then(resp => {
                    if (resp.data.code === 0) {
                        self.paginate = resp.data.data.paginate;
                        self.searchFormData.visible = false;
                    }
                });
            },
            handleDelete(index, row) {
                let self = this;
                this.$confirm('确认删除？', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$http.delete('/article/' + row.id).then(resp => {
                        if (resp.data.code === 0) {
                            self.paginate.data.splice(index, 1);
                            self.$message({type: 'success', message: '删除成功!'});
                        }
                    });
                });
            },
            handleCreate() {
                this.$router.push({path: '/article-editor'});
            },
            handleStatic() {
                this.staticProgress = 0;
                this.dialogVisibleStatic = true;
            },
            handleStaticSubmit() {
                let self = this;
                this.$http.get('/article/static').then(resp => {
                    if (resp.data.code === 0) {
                        let total = resp.data.data.count;

                        self.recursionStatic(total, 0);
                    }
                });
            },
            recursionStatic(total, lastId) {
                let self = this;
                this.$http.post('/article/static', {last_id: lastId}, {loading: false}).then(resp => {
                    if (resp.data.code === 0) {
                        let data = resp.data.data;

                        if (data.count === 0) {
                            self.staticProgress = 100;
                            self.$message({type: 'success', message: '处理完成!'});
                        } else {
                            self.staticProgress = parseInt((total - data.count) / total * 100);
                            self.recursionStatic(total, data.last_id);
                        }
                    }
                });
            },
            handleUnitStatic(id) {
                let self = this;
                this.$http.put('/article/static/' + id).then(resp => {
                    if (resp.data.code === 0) {
                        self.$message({type: 'success', message: '处理完成!'});
                        self.search();
                    }
                });
            },
            handleEdit(id) {
                this.$router.push({path: '/article-editor/' + id});
            },
        },
        mounted() {
            this.$http.defaults.loadTarget = '.wrapper';
            this.search();
        }
    };
</script>

<style lang="less" scoped>
</style>