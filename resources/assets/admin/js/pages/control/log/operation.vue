<template>
    <div id="permission-user" class="page">
        <h2 class="page-header">操作日志</h2>

        <el-row>
            <el-col class="text-right">
                <el-button type="primary" size="mini" @click="dialogVisibleSearch = true">搜索</el-button>
                <el-button type="danger" size="mini" @click="handleClear">清理</el-button>
            </el-col>
        </el-row>

        <el-row>
            <el-col>
                <el-table size="small" :data="paginate.data" stripe>
                    <el-table-column prop="username" label="用户名"></el-table-column>
                    <el-table-column label="请求地址">
                        <template slot-scope="scope">
                            <el-tooltip effect="dark" placement="bottom">
                                <div slot="content" class="text-word-break-all">{{scope.row.url}}</div>
                                <p class="text-ellipsis">{{scope.row.url}}</p>
                            </el-tooltip>
                        </template>
                    </el-table-column>

                    <el-table-column prop="method" label="请求方式">
                        <template slot-scope="scope">
                            <el-tag size="small" :type="methodColor(scope.row.method)">{{scope.row.method}}</el-tag>
                        </template>
                    </el-table-column>

                    <el-table-column label="查询参数">
                        <template slot-scope="scope">
                            <el-tooltip effect="dark" placement="bottom">
                                <div slot="content" class="text-word-break-all">{{scope.row.query_string}}</div>
                                <p class="text-ellipsis">{{scope.row.query_string}}</p>
                            </el-tooltip>
                        </template>
                    </el-table-column>

                    <el-table-column label="请求内容">
                        <template slot-scope="scope">
                            <el-tooltip effect="dark" placement="bottom">
                                <div slot="content" class="text-word-break-all">{{scope.row.input_content}}</div>
                                <p class="text-ellipsis">{{scope.row.input_content}}</p>
                            </el-tooltip>
                        </template>
                    </el-table-column>

                    <el-table-column prop="created_at" label="创建时间"></el-table-column>
                </el-table>
            </el-col>
        </el-row>

        <el-row>
            <el-col>
                <el-pagination class="pull-right" :current-page.sync="paginate.current_page" :page-size="paginate.per_page" :total="paginate.total" @current-change="search" layout="total, prev, pager, next"></el-pagination>
            </el-col>
        </el-row>

        <el-dialog :visible.sync="dialogVisibleSearch" :modal-append-to-body="false" :close-on-click-modal="false" class="default-dialog">
            <el-form size="small" label-width="80px">
                <el-form-item label="用户名">
                    <el-input v-model="searchForm.username"></el-input>
                </el-form-item>

                <el-form-item label="请求方式">
                    <el-select v-model="searchForm.method" clearable>
                        <el-option value="GET" ></el-option>
                        <el-option value="POST" ></el-option>
                        <el-option value="PUT" ></el-option>
                        <el-option value="DELETE" ></el-option>
                    </el-select>
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

                dialogVisibleSearch: false,
                searchForm: {
                    username: null,
                }
            };
        },
        methods: {
            search() {
                let self = this;
                let action = '/log/operation?' + qs.stringify({
                    search: this.searchForm,
                    page: this.paginate.current_page,
                });

                this.$http.get(action).then(resp => {
                    if (resp.data.code === 0) {
                        self.searchDialogVisible = false;
                        self.paginate = resp.data.data.paginate;
                        self.dialogVisibleSearch = false;
                    }
                });
            },
            methodColor(method) {
                switch (method) {
                    case 'POST':
                        return 'success';
                    case 'PUT':
                        return 'warning';
                    case 'DELETE':
                        return 'danger';
                    default:
                        return 'info';
                }
            },
            handleClear() {
                let self = this;
                this.$confirm('确认进入清理？', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    self.recursionClear();
                });
            },
            recursionClear() {
                let self = this;

                self.$http.delete('/log/operation/-1').then(resp => {
                    if (resp.data.code === 0) {
                        if (!resp.data.data.done) {
                            let msg = '<p>清理中，请勿进行其他操作!</p><p>本次清理' + resp.data.data.clear_count + '条，还剩' + resp.data.data.total + '条</p>';
                            self.$message({
                                type: 'warning',
                                dangerouslyUseHTMLString: true,
                                message: msg,
                                duration: 800,
                                onClose: function() {
                                    self.recursionClear();
                                }
                            });
                        } else {
                            self.$message({type: 'success', message: '清理完毕!'});
                            self.search();
                        }
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
    .text-ellipsis{
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .text-word-break-all{
        max-width: 300px;
        word-break: break-all;
    }
</style>