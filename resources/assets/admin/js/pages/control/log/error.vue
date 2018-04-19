<template>
    <div id="log-error" class="page">
        <h2 class="page-header">错误日志</h2>

        <el-row>
            <el-col>
                <el-select v-model="searchFormData.file">
                    <el-option v-for="(file, index) in files" :key="index" :label="file" :value="file"></el-option>
                </el-select>
            </el-col>
        </el-row>

        <el-row>
            <el-col style="display: flex;">
                <ul class="line">
                    <li v-for="item in paginate.data" :key="item.line" v-html="item.line"></li>
                </ul>
                <ul class="code">
                    <li v-for="item in paginate.data" :key="item.line" v-html="item.content || '_'"></li>
                </ul>
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
        computed: {
            action() {return this.$store.state.resources.LogError;},
        },
        methods: {
            search() {
                let self = this;
                let params = {
                    search: this.searchFormData,
                    page: this.paginate.current_page,
                };

                this.$http.resource.get(this.action, null, {params}).then(data => {
                    self.paginate = data.paginate;
                });
            },
            init() {
                let self = this;
                return this.$http.resource.get(this.action, {id: 'init'}).then(data => {
                    self.files = data.files;
                    if (self.files.length > 0) {
                        self.searchFormData.file = self.files[0]
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

<style lang="less">
    #log-error {
        .line {
            list-style: none;
            flex: 1;
            color: #999;
            margin-right: 10px;
            font-size: 14px;
        }

        .code {
            overflow-x: auto;
            list-style: none;
            background: #eee;
            font-size: 14px;
            color: #333;
            padding: 0 5px;

            li {
                white-space: nowrap;
            }
        }
    }
</style>