<template>
    <div class="page">
        <h2 class="page-header">留言管理</h2>

        <el-row>
            <el-col class="text-right">
                <el-button-search @click="searchFormData.visible = true">搜索</el-button-search>
            </el-col>
        </el-row>

        <el-row>
            <el-col>
                <el-table :data="paginate.data" stripe>
                    <el-table-column prop="email" label="邮箱"></el-table-column>
                    <el-table-column prop="nickname" label="昵称"></el-table-column>
                    <el-table-column prop="ip" label="IP"></el-table-column>
                    <el-table-column label="内容">
                        <template slot-scope="scope">
                            <el-tooltip effect="dark" placement="bottom">
                                <div slot="content" class="text-word-break-all">{{scope.row.content}}</div>
                                <p class="text-ellipsis">{{scope.row.content}}</p>
                            </el-tooltip>
                        </template>
                    </el-table-column>
                    <el-table-column prop="display" label="显示">
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
                <el-form-item label="邮箱">
                    <p>{{storeData.email}}</p>
                </el-form-item>

                <el-form-item label="昵称">
                    <el-input v-model="storeData.nickname"></el-input>
                </el-form-item>

                <el-form-item label="显示">
                    <el-select v-model="storeData.display">
                        <el-option label="显示" :value="1"></el-option>
                        <el-option label="隐藏" :value="2"></el-option>
                    </el-select>
                </el-form-item>

                <el-form-item label="内容">
                    <el-input v-model="storeData.content" type="textarea"></el-input>
                </el-form-item>
            </el-form>

            <span slot="footer" class="dialog-footer">
                <el-button type="primary" @click="handleStore">保存</el-button>
            </span>
        </el-dialog>

        <search-form :data.sync="searchFormData" @submit="search"></search-form>

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
                    id: null, email: null, nickname: null, display: null, content: null
                },

                searchFormData: {
                    visible: false,
                    formItem: [
                        {
                            label: '邮箱',
                            field: 'email',
                            type: 'input',
                        },
                    ],
                    formData: {
                        email: null,
                    },
                },

            };
        },
        computed: {
            action() {return this.$store.state.resources.Message;},
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
            handleEdit(index, row) {
                this.storeData = JSON.parse(JSON.stringify(row));
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
            }
        },
        mounted() {
            this.$http.defaults.loadTarget = '.wrapper';
            this.search();
        }
    };
</script>

<style lang="less" scoped>
</style>