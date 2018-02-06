<template>
    <div class="page">
        <h2 class="page-header">类目管理</h2>

        <el-row>
            <el-col class="text-right">
                <el-button-create @click="handleCreate">新建</el-button-create>
            </el-col>
        </el-row>

        <el-row>
            <el-col>
                <el-table size="small" :data="paginate.data" stripe>
                    <el-table-column prop="alias" label="别名"></el-table-column>
                    <el-table-column prop="title" label="标题"></el-table-column>
                    <el-table-column prop="weight" label="权重" width="80px"></el-table-column>
                    <el-table-column prop="display" label="状态">
                        <template slot-scope="scope">
                            <el-tag size="small" type="success" v-if="scope.row.display === 1">显示</el-tag>
                            <el-tag size="small" type="danger" v-if="scope.row.display !== 1">隐藏</el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column prop="created_at" label="创建时间"></el-table-column>
                    <el-table-column label="操作">
                        <template slot-scope="scope">
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

        <el-dialog :visible.sync="dialogVisibleStore" :modal-append-to-body="false" :close-on-click-modal="false" class="default-dialog">
            <el-form size="small" label-width="80px">
                <el-form-item label="别名">
                    <el-input v-model="storeData.alias" placeholder="英文且唯一，显示在url上" :disabled="!!storeData.id"></el-input>
                </el-form-item>

                <el-form-item label="标题">
                    <el-input v-model="storeData.title" placeholder="显示在页面菜单上"></el-input>
                </el-form-item>

                <el-form-item label="权重">
                    <el-input v-model="storeData.weight" placeholder="请输入小于100的正整数，越大越靠前"></el-input>
                </el-form-item>

                <el-form-item label="显示状态">
                    <el-select v-model="storeData.display">
                        <el-option :value="1" label="显示"></el-option>
                        <el-option :value="2" label="隐藏"></el-option>
                    </el-select>
                </el-form-item>
            </el-form>

            <span slot="footer" class="dialog-footer">
                <el-button type="primary" size="mini" @click="handleStore">保存</el-button>
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
                    alias: null, title: null, weight: 0, display: 2
                },
            };
        },
        methods: {
            search() {
                let self = this;
                let action = '/article/category?' + qs.stringify({
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
                    this.$http.delete('/article/category/' + row.id).then(resp => {
                        if (resp.data.code === 0) {
                            self.paginate.data.splice(index, 1);
                            self.$message({type: 'success', message: '删除成功!'});
                        }
                    });
                });
            },
            handleCreate() {
                this.storeData = {
                    alias: null, title: null, weight: 0, display: 2
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
                    this.$http.put('/article/category/' + this.storeData.id, this.storeData).then(cbk);
                } else {
                    this.$http.post('/article/category', this.storeData).then(cbk);
                }
            },
            handleEdit(index, row) {
                this.storeData = row;
                this.dialogVisibleStore = true;
            },
        },
        mounted() {
            this.$http.defaults.loadTarget = '.wrapper';
            this.search();
        }
    };
</script>

<style lang="less">
</style>