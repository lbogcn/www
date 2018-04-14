<template>
    <div class="page" id="cover">
        <h2 class="page-header">封面管理</h2>

        <el-row>
            <el-col class="text-right">
                <el-button-create @click="handleCreate">新建</el-button-create>
                <el-button type="danger" @click="handleStatic" icon="el-icon-lb-sync">静态化</el-button>
            </el-col>
        </el-row>

        <el-row>
            <el-col>
                <el-table :data="paginate.data" stripe>
                    <el-table-column prop="title" label="标题"></el-table-column>
                    <el-table-column label="预览">
                        <template slot-scope="scope">
                            <img :src="scope.row.url" class="img-responsive">
                        </template>
                    </el-table-column>
                    <el-table-column prop="source" label="来源">
                        <template slot-scope="scope">
                            <el-tooltip effect="dark" placement="bottom">
                                <div slot="content" class="text-word-break-all">{{scope.row.source}}</div>
                                <p class="text-ellipsis">{{scope.row.source}}</p>
                            </el-tooltip>
                        </template>
                    </el-table-column>
                    <el-table-column prop="weight" label="权重"></el-table-column>
                    <el-table-column prop="display" label="状态">
                        <template slot-scope="scope">
                            <el-tag type="success" v-if="scope.row.display === 1">显示</el-tag>
                            <el-tag type="danger" v-if="scope.row.display !== 1">隐藏</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column prop="created_at" label="创建时间"></el-table-column>
                    <el-table-column label="操作">
                        <template slot-scope="scope">
                            <el-button @click="handleEdit(scope.$index, scope.row)" type="text">编辑</el-button>
                            <el-button @click="handleDelete(scope.$index, scope.row)" type="text">删除</el-button>
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

        <el-dialog :visible.sync="dialogVisibleStore" :modal-append-to-body="false" :close-on-click-modal="false" class="default-dialog">
            <el-form label-width="80px">
                <el-form-item label="标题">
                    <el-input v-model="storeData.title"></el-input>
                </el-form-item>

                <el-form-item label="图片">
                    <el-col :span="6">
                        <el-button-upload @success="handleSuccessUpload">上传</el-button-upload>
                    </el-col>

                    <el-col :span="15" :offset="1">
                        <img :src="storeData.url" class="img-responsive preview">
                    </el-col>
                </el-form-item>

                <el-form-item label="来源">
                    <el-input v-model="storeData.source" placeholder="输入来源网址"></el-input>
                </el-form-item>

                <el-form-item label="权重">
                    <el-input v-model="storeData.weight"></el-input>
                </el-form-item>

                <el-form-item label="显示状态">
                    <el-select v-model="storeData.display">
                        <el-option :value="1" label="显示"></el-option>
                        <el-option :value="2" label="隐藏"></el-option>
                    </el-select>
                </el-form-item>
            </el-form>

            <span slot="footer" class="dialog-footer">
                <el-button type="primary" @click="handleStore">保存</el-button>
            </span>
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

                dialogVisibleStore: false,
                storeData: {
                    title: null, url: null, source: null, weight: 10, display: 1
                },
            };
        },
        methods: {
            search() {
                let self = this;
                let action = '/cover?' + qs.stringify({
                    page: this.paginate.current_page,
                });

                this.$http.get(action).then(resp => {
                    if (resp.data.code === 0) {
                        self.paginate = resp.data.data.paginate;
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
                    this.$http.delete('/cover/' + row.id).then(resp => {
                        if (resp.data.code === 0) {
                            self.paginate.data.splice(index, 1);
                            self.$message({type: 'success', message: '删除成功!'});
                        }
                    });
                });
            },
            handleCreate() {
                this.storeData = {
                    title: null, url: null, source: null, weight: 10, display: 1
                };
                this.dialogVisibleStore = true;
            },
            handleStore() {
                let self = this;
                let cbk = resp => {
                    if (resp.data.code === 0) {
                        self.$message({type: 'success', message: '保存成功!'});
                        self.dialogVisibleStore = false;
                        self.search();
                    }
                };

                if (this.storeData.id) {
                    this.$http.put('/cover/' + this.storeData.id, this.storeData).then(cbk);
                } else {
                    this.$http.post('/cover', this.storeData).then(cbk);
                }
            },
            handleEdit(index, row) {
                this.storeData = JSON.parse(JSON.stringify(row));
                this.dialogVisibleStore = true;
            },
            handleSuccessUpload(data) {
                this.storeData.url = data.url;
            },
            handleStatic() {
                let self = this;
                this.$http.post('/cover/static').then(resp => {
                    if (resp.data.code === 0) {
                        self.$message({type: 'success', message: '操作成功!'});
                    }
                });
            }
        },
        mounted() {
            this.$http.defaults.loadTarget = '.wrapper';
            this.search();
        }
    };
</script>

<style lang="less">
    #cover{
        .preview{
            width: 100%;
            display: block;

            &[src=""],
            &:not([src]) {
                opacity: 0;
            }
        }
    }
</style>