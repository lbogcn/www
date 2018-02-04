<template>
    <div id="permission-user" class="page">
        <h2 class="page-header">文章管理</h2>

        <el-row>
            <el-col class="text-right">
                <el-button type="primary" size="mini" @click="searchFormData.visible = true">搜索</el-button>
                <el-button type="success" size="mini" @click="handleCreate">新建</el-button>
            </el-col>
        </el-row>

        <el-row>
            <el-col>
                <el-table size="small" :data="paginate.data" stripe>
                    <el-table-column prop="title" label="标题"></el-table-column>
                    <el-table-column prop="author" label="作者"></el-table-column>
                    <el-table-column prop="image" label="封面图片"></el-table-column>
                    <el-table-column prop="excerpt" label="摘要"></el-table-column>
                    <el-table-column prop="weight" label="权重"></el-table-column>
                    <el-table-column prop="pv" label="PV"></el-table-column>
                    <el-table-column prop="display" label="显示">
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

        <search-form :data.sync="searchFormData" @submit="search"></search-form>

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
            },
            handleEdit(index, row) {
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