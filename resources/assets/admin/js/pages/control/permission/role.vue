<template>
    <div id="permission-role" class="page">
        <h2 class="page-header">角色管理</h2>

        <el-row>
            <el-col class="text-right">
                <el-button type="success" size="mini" @click="handleCreate">新建</el-button>
            </el-col>
        </el-row>

        <el-row>
            <el-col>
                <el-table size="small" :data="paginate.data" stripe>
                    <el-table-column prop="role" label="角色"></el-table-column>
                    <el-table-column prop="name" label="描述"></el-table-column>
                    <el-table-column prop="created_at" label="创建时间"></el-table-column>
                    <el-table-column label="操作">
                        <template slot-scope="scope">
                            <el-button @click="handleShowPermission(scope.row)" type="text" size="mini">权限</el-button>
                            <el-button @click="handleEdit(scope.$index, scope.row)" type="text" size="mini">编辑</el-button>
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

        <el-dialog :visible.sync="dialogVisibleUser" :modal-append-to-body="false" :close-on-click-modal="false" class="default-dialog">
            <el-form size="small" label-width="80px">
                <el-form-item label="角色">
                    <el-input v-model="storeData.role" :disabled="!!storeData.id"></el-input>
                </el-form-item>

                <el-form-item label="描述">
                    <el-input v-model="storeData.name"></el-input>
                </el-form-item>
            </el-form>

            <span slot="footer" class="dialog-footer">
                <el-button type="primary" size="mini" @click="handleStore">保存</el-button>
            </span>
        </el-dialog>

        <el-dialog :visible.sync="dialogVisiblePermission" :modal-append-to-body="false" :close-on-click-modal="false" width="600px">
            <el-form size="small" label-width="120px">
                <el-form-item v-for="(permissions, group) in permissionsGroups" :label="group" :key="group">
                    <el-checkbox-group v-model="storeDataPermission.node_id">
                        <el-checkbox v-for="permission in permissions" :label="permission.id" :key="permission.id" class="checkbox-permission-item">{{permission.node}}</el-checkbox>
                    </el-checkbox-group>
                </el-form-item>
            </el-form>

            <span slot="footer" class="dialog-footer">
                <el-button type="primary" size="mini" @click="handleStorePermission">保存</el-button>
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
                dialogVisibleUser: false,
                storeData: {},

                dialogVisiblePermission: false,
                storeDataPermission: {
                    node_id: [],
                },
                permissionsGroups: {},
            };
        },
        methods: {
            search() {
                let self = this;
                let action = '/permission/role?' + qs.stringify({
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
                    this.$http.delete('/permission/role/' + row.id).then(resp => {
                        if (resp.data.code === 0) {
                            self.paginate.data.splice(index, 1);
                            self.$message({type: 'success', message: '删除成功!'});
                        }
                    });
                });
            },
            handleCreate() {
                this.storeData = {
                    role: null, name: null
                };
                this.dialogVisibleUser = true;
            },
            handleEdit(index, row) {
                this.storeData = JSON.parse(JSON.stringify(row));
                this.dialogVisibleUser = true;
            },
            handleStore() {
                let self = this;
                let cbk = function(resp) {
                    if (resp.data.code === 0) {
                        self.$message({type: 'success', message: '保存成功!'});
                        self.dialogVisibleUser = false;
                        self.search();
                    }
                };

                if (this.storeData.id) {
                    this.$http.put('/permission/role/' + this.storeData.id, this.storeData).then(cbk);
                } else {
                    this.$http.post('/permission/role', this.storeData).then(cbk);
                }
            },
            handleShowPermission(row) {
                let self = this;
                this.$http.get('/permission/role/' + row.id + '/permission').then(resp => {
                    if (resp.data.code === 0) {
                        let data = resp.data.data;

                        self.storeDataPermission.role_id = row.id;
                        self.storeDataPermission.node_id = data.role_permission_id;
                        self.permissionsGroups = data.groups;
                        self.dialogVisiblePermission = true;
                    }
                });
            },
            handleStorePermission() {
                let self = this;
                this.$http.post('/permission/role/' + this.storeDataPermission.role_id + '/permission', this.storeDataPermission).then(resp => {
                    if (resp.data.code === 0) {
                        self.$message({type: 'success', message: '保存成功!'});
                        self.search();
                        self.dialogVisiblePermission = false;
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
    .checkbox-permission-item.el-checkbox.el-checkbox{
        margin-left: 0;
        margin-right: 15px;
    }
</style>