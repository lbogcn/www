<template>
    <div class="page">
        <h2 class="page-header">用户管理</h2>

        <el-row>
            <el-col class="text-right">
                <el-button-search @click="searchFormData.visible = true">搜索</el-button-search>
                <el-button-create @click="handleCreate">新建</el-button-create>
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
                            <el-button @click="handleShowRole(scope.row)" type="text">角色</el-button>
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

        <el-dialog :visible.sync="dialogVisibleUser" :modal-append-to-body="false" :close-on-click-modal="false" class="default-dialog">
            <el-form label-width="80px">
                <el-form-item label="用户名">
                    <el-input v-model="storeData.username" :disabled="!!storeData.id"></el-input>
                </el-form-item>

                <el-form-item label="姓名">
                    <el-input v-model="storeData.name"></el-input>
                </el-form-item>

                <el-form-item label="密码">
                    <el-input v-model="storeData.password" type="password" :placeholder="!!storeData.id ? '若无需修改请留空' : ''"></el-input>
                </el-form-item>
            </el-form>

            <span slot="footer" class="dialog-footer">
                <el-button type="primary" @click="handleStore">保存</el-button>
            </span>
        </el-dialog>

        <search-form :data.sync="searchFormData" @submit="search"></search-form>

        <el-dialog :visible.sync="dialogVisibleRole" :modal-append-to-body="false" :close-on-click-modal="false" class="default-dialog">
            <el-form label-width="80px">
                <el-form-item label="用户">
                    <p>{{storeDataRole.user.username}} - {{storeDataRole.user.name}}</p>
                </el-form-item>

                <el-form-item label="角色">
                    <el-checkbox-group v-model="storeDataRole.role_id">
                        <el-checkbox v-for="role in roles" :label="role.id" :key="role.id" class="checkbox-permission-item">{{role.role}}</el-checkbox>
                    </el-checkbox-group>
                </el-form-item>
            </el-form>

            <span slot="footer" class="dialog-footer">
                <el-button type="primary" @click="handleStoreRole">保存</el-button>
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
                dialogVisibleUser: false,
                storeData: {},

                searchFormData: {
                    visible: false,
                    formItem: [
                        {
                            label: '用户名',
                            field: 'username',
                            type: 'input',
                        },
                    ],
                    formData: {
                        username: null,
                    },
                },

                dialogVisibleRole: false,
                storeDataRole: {
                    user: {},
                    user_id: null,
                    role_id: [],
                },
                roles: [],
            };
        },
        computed: {
            action() {return this.$store.state.resources.PermissionUser;},
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
                    username: null, name: null, password: null
                };
                this.dialogVisibleUser = true;
            },
            handleEdit(index, row) {
                this.storeData = JSON.parse(JSON.stringify(row));
                this.dialogVisibleUser = true;
            },
            handleStore() {
                let self = this;
                let method = this.storeData.id ? 'put' : 'post';

                this.$http.resource[method](this.action, this.storeData).then(() => {
                    self.$message({type: 'success', message: '保存成功!'});
                    self.dialogVisibleUser = false;
                    self.search();
                });
            },
            handleShowRole(row) {
                let self = this;
                this.$http.get(`/permission/user/${row.id}/role`).then(data => {
                    self.storeDataRole.user = row;
                    self.storeDataRole.user_id = row.id;
                    self.storeDataRole.role_id = data.role_id;
                    self.roles = data.roles;
                    self.dialogVisibleRole = true;
                });
            },
            handleStoreRole() {
                let self = this;
                this.$http.post(`/permission/user/${this.storeDataRole.user_id}/role`, this.storeDataRole).then(() => {
                    self.$message({type: 'success', message: '保存成功!'});
                    self.search();
                    self.dialogVisibleRole = false;
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
    .checkbox-permission-item.el-checkbox.el-checkbox{
        margin-left: 0;
        margin-right: 15px;
    }
</style>