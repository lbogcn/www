<template>
    <div class="page" id="keyvalue">
        <h2 class="page-header">通用配置</h2>

        <el-row>
            <el-col class="text-right">
                <el-button-create @click="handleCreate">新建</el-button-create>
                <el-button type="danger" @click="handleStatic" icon="el-icon-lb-sync">静态化</el-button>
            </el-col>
        </el-row>

        <el-row>
            <el-col>
                <el-table :data="paginate.data" stripe>
                    <el-table-column prop="key" label="Key"></el-table-column>
                    <el-table-column prop="value" label="Value">
                        <template slot-scope="scope">
                            <el-tooltip effect="dark" placement="bottom">
                                <div slot="content" class="text-word-break-all">{{scope.row.value}}</div>
                                <p class="text-ellipsis">{{scope.row.value}}</p>
                            </el-tooltip>
                        </template>
                    </el-table-column>
                    <el-table-column label="说明">
                        <template slot-scope="scope">
                            <el-tooltip effect="dark" placement="bottom">
                                <div slot="content" class="text-word-break-all">{{scope.row.describe}}</div>
                                <p class="text-ellipsis">{{scope.row.describe}}</p>
                            </el-tooltip>
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
                <el-form-item label="Key">
                    <el-input v-model="storeData.key" :disabled="!!storeData.id"></el-input>
                </el-form-item>

                <el-form-item label="Value">
                    <el-input v-model="storeData.value" type="textarea"></el-input>
                </el-form-item>

                <el-form-item label="说明">
                    <el-input v-model="storeData.describe"></el-input>
                </el-form-item>

            </el-form>

            <span slot="footer" class="dialog-footer">
                <el-button type="primary" @click="handleStore">保存</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
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
                    key: null, value: null, describe: null,
                },

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
            };
        },
        computed: {
            action() {return this.$store.state.resources.KeyValue;},
        },
        methods: {
            search() {
                let self = this;
                let params = {
                    search: this.searchFormData.formData,
                    page: this.paginate.current_page,
                };

                this.$http.resource.get(this.action, null, {params}).then(data => {
                    self.paginate = data.paginate;
                    self.searchFormData.visible = false;
                });
            },
            handleDelete(index, row) {
                let self = this;
                this.$confirm('确认删除？', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$http.resource.delete(this.action, row).then(() => {
                        self.paginate.data.splice(index, 1);
                        self.$message({type: 'success', message: '删除成功!'});
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
                let method = this.storeData.id ? 'put' : 'post';

                this.$http.resource[method](this.action, this.storeData).then(() => {
                    self.$message({type: 'success', message: '保存成功!'});
                    self.dialogVisibleStore = false;
                    self.search();
                });
            },
            handleEdit(index, row) {
                this.storeData = JSON.parse(JSON.stringify(row));
                this.dialogVisibleStore = true;
            },
            handleStatic() {
                let self = this;
                this.$http.post('/key-value/static').then(() => {
                    self.$message({type: 'success', message: '操作成功!'});
                });
            }
        },
        mounted() {
            this.$http.defaults.loadTarget = '.wrapper';
            this.search();
        }
    };
</script>
