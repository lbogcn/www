<template>
    <div class="page">
        <h2 class="page-header">节点管理</h2>

        <el-row>
            <el-col class="text-right">
                <el-button-search @click="searchFormData.visible = true">搜索</el-button-search>
                <el-button-create @click="handleCreate">新建</el-button-create>
                <el-button type="danger" @click="handleSync" icon="el-icon-lb-sync">一键同步</el-button>
            </el-col>
        </el-row>

        <el-row>
            <el-col>
                <el-table :data="paginate.data" stripe>
                    <el-table-column prop="group" label="所在组"></el-table-column>
                    <el-table-column prop="node" label="名称"></el-table-column>
                    <el-table-column prop="route" label="路由"></el-table-column>
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

        <el-dialog :visible.sync="userDialogVisible" :modal-append-to-body="false" :close-on-click-modal="false" class="default-dialog">
            <el-form label-width="80px">
                <el-form-item label="所在组">
                    <el-select v-model="storeData.group" filterable allow-create clearable>
                        <el-option v-for="item in groups" :key="item.group" :value="item.group" :label="item.group"></el-option>
                    </el-select>
                </el-form-item>

                <el-form-item label="名称">
                    <el-input v-model="storeData.node"></el-input>
                </el-form-item>

                <el-form-item label="路由">
                    <el-input v-model="storeData.route"></el-input>
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

                userDialogVisible: false,
                storeData: {},

                searchFormData: {
                    visible: false,
                    formItem: [
                        {
                            label: '所在组',
                            field: 'group',
                            type: 'select',
                            options: [
                            ],
                            optionProp: {
                                label: 'group',
                                value: 'group',
                            },
                            clearable: true,
                        }
                    ],
                    formData: {
                        group: null,
                    },
                },

                groups: []
            };
        },
        computed: {
            action() {return this.$store.state.resources.PermissionNode;},
        },
        methods: {
            init() {
                let self = this;
                this.$http.resource.get(this.action, {id: 'init'}).then(data => {
                    self.groups = data.groups;
                    self.searchFormData.formItem[0].options = self.groups;
                });
            },
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
                    group: '', node: null, route: null
                };
                this.userDialogVisible = true;
            },
            handleEdit(index, row) {
                this.storeData = JSON.parse(JSON.stringify(row));
                this.userDialogVisible = true;
            },
            handleStore() {
                let self = this;
                let method = this.storeData.id ? 'put' : 'post';

                this.$http.resource[method](this.action, this.storeData).then(() => {
                    self.$message({type: 'success', message: '保存成功!'});
                    self.userDialogVisible = false;
                    self.search();
                });
            },
            handleSync() {
                let self = this;
                this.$confirm('此操作将清除所有未知节点并覆盖所有已知节点，是否继续?', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    self.$http.resource.post(`${this.action}/sync`).then(data => {
                        let msgs = [
                            '新增节点' + data.c + '个',
                            '更新节点' + data.u + '个',
                            '删除节点' + data.d + '个',
                        ];

                        self.$message({type: 'success', message: '同步成功！' + msgs.join('；') + ''});
                    });
                }).catch(() => {});
            }
        },
        mounted() {
            this.$http.defaults.loadTarget = '.wrapper';
            this.init();
            this.search();
        }
    };
</script>

<style lang="less" scoped>
</style>