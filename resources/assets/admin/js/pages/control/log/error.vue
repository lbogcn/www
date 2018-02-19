<template>
    <div class="page">
        <h2 class="page-header">错误日志</h2>

        <el-row>
            <el-col>
                <el-select size="mini" v-model="searchFormData.file">
                    <el-option v-for="(file, index) in files" :key="index" :label="file" :value="file"></el-option>
                </el-select>
            </el-col>
        </el-row>

        <el-row>
            <el-col>
                <pre style="background: #eee;overflow-x: auto; padding: 5px;">{{paginate.data}}</pre>
            </el-col>
        </el-row>

        <el-row>
            <el-col>
                <el-pagination class="pull-right" :current-page.sync="paginate.current_page" :page-size="paginate.per_page" :total="paginate.total" @current-change="search" layout="total, prev, pager, next"></el-pagination>
            </el-col>
        </el-row>
    </div>
</template>

<script>
    import qs from 'qs';
    export default {
        data() {
            return {
                searchFormData: {
                    file: '',
                },

                paginate: {
                    data: '',
                    current_page: 1,
                    per_page: 0,
                    total: 0,
                },

                files: [],
            };
        },
        methods: {
            search() {
                let self = this;
                let action = '/log/error?' + qs.stringify({
                    search: this.searchFormData,
                    page: this.paginate.current_page,
                });

                this.$http.get(action).then(resp => {
                    if (resp.data.code === 0) {
                        self.paginate = resp.data.data.paginate;
                    }
                });
            },
            init() {
                let self = this;
                return this.$http.get('/log/error/init').then(resp => {
                    if (resp.data.code === 0) {
                        self.files = resp.data.data.files;
                        if (self.files.length > 0) {
                            self.searchFormData.file = self.files[0]
                        }
                    }
                });
            },
        },
        watch: {
            'searchFormData.file'() {
                this.search();
            }
        },
        mounted() {
            this.$http.defaults.loadTarget = '.wrapper';
            this.init();
        }
    };
</script>
