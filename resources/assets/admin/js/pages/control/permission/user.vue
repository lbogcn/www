<template>
    <div id="permission-user" class="page">
        <h2 class="page-header">用户管理</h2>

        <el-row>
            <el-col class="text-right">
                <el-button type="primary" size="mini" @click="searchDialogVisible = true">搜索</el-button>
                <el-button type="success" size="mini" @click="handleCreate">新建</el-button>
            </el-col>
        </el-row>

        <el-row>
            <el-col>
                <el-table :data="paginate.data" stripe>
                    <el-table-column prop="username" label="用户名"></el-table-column>
                    <el-table-column prop="name" label="姓名"></el-table-column>
                    <el-table-column prop="roles_text" label="所属角色"></el-table-column>
                    <el-table-column prop="created_at" label="创建时间"></el-table-column>
                    <el-table-column label="操作">
                        <template slot-scope="scope">
                            <el-button @click="handleEdit(scope.$index, scope.row)" type="text" size="small">编辑</el-button>
                            <el-button @click="handleEdit(scope.$index, scope.row)" type="text" size="small">角色</el-button>
                            <el-button @click="handleDelete(scope.$index, scope.row)" type="text" size="small">删除</el-button>
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

        <el-dialog :visible.sync="userDialogVisible" :modal-append-to-body="false" :close-on-click-modal="false">
            <el-form ref="form" label-width="80px">
                <el-form-item label="用户名">
                    <el-input v-model="userData.username" :disabled="!!userData.id"></el-input>
                </el-form-item>

                <el-form-item label="姓名">
                    <el-input v-model="userData.name"></el-input>
                </el-form-item>

                <el-form-item label="密码">
                    <el-input v-model="userData.password" type="password" :placeholder="!!userData.id ? '若无需修改请留空' : ''"></el-input>
                </el-form-item>
            </el-form>

            <span slot="footer" class="dialog-footer">
                <el-button type="primary" size="mini" @click="handleStore">保存</el-button>
            </span>
        </el-dialog>

        <el-dialog :visible.sync="searchDialogVisible" :modal-append-to-body="false" :close-on-click-modal="false">
            <el-form ref="form" label-width="80px">
                <el-form-item label="用户名">
                    <el-input v-model="searchForm.username"></el-input>
                </el-form-item>
            </el-form>

            <span slot="footer" class="dialog-footer">
                <el-button type="primary" size="mini" @click="search()">搜索</el-button>
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
                searchForm: {
                    username: null
                },
                userDialogVisible: false,
                userData: {},
                searchDialogVisible: false,
            };
        },
        methods: {
            search() {
                let self = this;
                let action = '/permission/user?' + qs.stringify({
                    search: this.searchForm,
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
                this.$confirm('确认删除？').then(() => {
                    this.$http.delete('/permission/user/' + row.id).then(resp => {
                        if (resp.data.code === 0) {
                            self.paginate.data.splice(index, 1);
                            self.$message({type: 'success', message: '删除成功!'});
                        }
                    });
                });
            },
            handleCreate() {
                this.userData = {
                    username: null, name: null, password: null
                };
                this.userDialogVisible = true;
            },
            handleEdit(index, row) {
                this.userData = JSON.parse(JSON.stringify(row));
                this.userDialogVisible = true;
            },
            handleStore() {
                let self = this;
                let cbk = function(resp) {
                    if (resp.data.code === 0) {
                        self.$message({type: 'success', message: '保存成功!'});
                        self.userDialogVisible = false;
                        self.search();
                    }
                };

                if (this.userData.id) {
                    this.$http.put('/permission/user/' + this.userData.id, this.userData).then(cbk);
                } else {
                    this.$http.post('/permission/user', this.userData).then(cbk);
                }
            }
        },
        mounted() {
            this.$http.defaults.loadTarget = '#permission-user';
            this.search();
        }
    };
</script>

<style lang="less">

</style>