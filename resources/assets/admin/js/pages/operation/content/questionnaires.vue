<template>
    <div class="page">
        <h2 class="page-header">问卷管理</h2>

        <el-row>
            <el-col class="text-right">
                <el-button-search @click="searchFormData.visible = true">搜索</el-button-search>
                <el-button-create @click="handleCreate">新建</el-button-create>
            </el-col>
        </el-row>

        <el-row>
            <el-col>
                <el-table size="small" :data="paginate.data" stripe>
                    <el-table-column prop="title" label="标题"></el-table-column>
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

        <el-dialog :visible.sync="dialogVisibleStore" :modal-append-to-body="false" :close-on-click-modal="false" width="600px">

            <el-form size="small" label-width="80px">
                <el-form-item label="标题">
                    <el-input v-model="storeData.title"></el-input>
                </el-form-item>

                <div v-for="(question, index) in storeData.questions">
                    <el-form-item :label="'Q' + (index + 1)" style="margin-bottom: 5px;">
                        <el-input v-model="question.title" style="width: 345px;" placeholder="请输入问题"></el-input>

                        <el-select v-model="question.type" style="width: 80px;" @change="addStoreDataQuestionOptions(index)">
                            <el-option :value="1" label="单选"></el-option>
                            <el-option :value="2" label="多选"></el-option>
                            <el-option :value="3" label="填空"></el-option>
                        </el-select>

                        <el-button @click="subtractStoreDataQuestion(index)" icon="el-icon-close" v-if="storeData.questions.length > 1"></el-button>
                    </el-form-item>

                    <el-form-item label="" v-if="question.type === 1 || question.type === 2">
                        <template>{{fixOptions(index)}}</template>
                        <template v-for="(option, optionIndex) in question.options">
                            <el-col :span="12" style="padding-right: 5px; margin-bottom: 5px;">
                                <el-input v-model="option.option" placeholder="请输入选项">
                                    <el-button v-if="optionIndex === question.options.length - 1" @click="addStoreDataQuestionOption(index)" slot="append">+</el-button>
                                    <el-button v-if="optionIndex !== question.options.length - 1" @click="subtractStoreDataQuestionOption(question.options, optionIndex)" slot="append">-</el-button>
                                </el-input>
                            </el-col>
                            <div class="clearfix" v-if="(optionIndex + 1) % 2 === 0"></div>
                        </template>
                    </el-form-item>
                </div>

                <el-form-item label="">
                    <el-button type="success" @click="addStoreDataQuestion" plain>添加问题</el-button>
                </el-form-item>
            </el-form>

            <span slot="footer" class="dialog-footer">
                <el-button type="primary" size="mini" @click="handleStore">保存</el-button>
            </span>
        </el-dialog>

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
                dialogVisibleStore: false,
                storeData: {
                    title: null,
                    questions: [],
                },

                searchFormData: {
                    visible: false,
                    formItem: [
                        {
                            label: '标题',
                            field: 'title',
                            type: 'input',
                        },
                    ],
                    formData: {
                        title: null,
                    },
                },
            };
        },
        methods: {
            search() {
                let self = this;
                let action = '/questionnaires?' + qs.stringify({
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
                    this.$http.delete('/questionnaires/' + row.id).then(resp => {
                        if (resp.data.code === 0) {
                            self.paginate.data.splice(index, 1);
                            self.$message({type: 'success', message: '删除成功!'});
                        }
                    });
                });
            },
            handleCreate() {
                this.storeData = {
                    title: null,
                    questions: [{
                        type: 1,
                        title: '',
                        required: true,
                        options: [{id: 1, option: ''}]
                    }]
                };
                this.dialogVisibleStore = true;
            },
            handleStore() {
                let self = this;
                let req = null;

                if (this.storeData.id) {
                    req = this.$http.put('/questionnaires/' + this.storeData.id, this.storeData);
                } else {
                    req = this.$http.post('/questionnaires', this.storeData);
                }

                req.then(resp => {
                    if (resp.data.code === 0) {
                        self.dialogVisibleStore = false;
                        self.$message({type: 'success', message: '保存成功!'});
                        self.search();
                    }
                });
            },
            handleEdit(index, row) {
                let questions = [];
                try {
                    questions = JSON.parse(row.config).questions;
                } catch (e) {
                } finally {
                }

                this.storeData = {
                    id: row.id,
                    title: row.title,
                    questions: questions,
                };
                this.dialogVisibleStore = true;
            },
            addStoreDataQuestion() {
                let max = 50;
                if (this.storeData.questions.length >= max) {
                    alert('最多配置' + max + '个问题');
                    return;
                }

                this.storeData.questions.push({
                    type: 1,
                    title: '',
                    required: true,
                    options: [{id: 1, option: ''}]
                });
            },
            subtractStoreDataQuestion(index) {
                if (this.storeData.questions.length <= 1) {
                    alert('最少配置1个问题');
                    return;
                }

                this.storeData.questions.splice(index, 1);
            },
            addStoreDataQuestionOption(index) {
                var max = 10;
                if (this.storeData.questions[index].options.length >= max) {
                    alert('最多配置' + max + '个选项');
                    return;
                }

                this.storeData.questions[index].options.push({
                    id: this.storeData.questions[index].options.length + 1,
                    option: '',
                });
            },
            subtractStoreDataQuestionOption(options, optionIndex) {
                if (options.length <= 1) {
                    alert('最少配置1个选项');
                    return;
                }

                options.splice(optionIndex, 1)
            },
            addStoreDataQuestionOptions(index) {
                let type = this.storeData.questions[index].type;
                if (type === 1 || type === 2) {
                    if (typeof this.storeData.questions[index].options === 'undefined') {
                        this.storeData.questions[index].options = [];
                        this.addStoreDataQuestionOption(index);
                    }
                }
            },
            fixOptions(index) {
                if (typeof this.storeData.questions[index].options === 'undefined') {
                    this.storeData.questions[index].options = [];
                    this.addStoreDataQuestionOption(index);
                }
            }
        },
        mounted() {
            this.$http.defaults.loadTarget = '.wrapper';
            this.search();

            // this.storeData = {
            //     title: null,
            //     questions: [{
            //         type: 1,
            //         title: '您的住址',
            //         required: true,
            //         options: [{id: 1, option: 'aaa'}, {id: 2, option: 'bbb'}]
            //     }, {
            //         type: 1,
            //         title: '您的年龄',
            //         required: true,
            //         options: [{id: 1, option: 'ccc'}, {id: 2, option: 'ddd'}]
            //     }]
            // };
            // this.dialogVisibleStore = true;
        }
    };
</script>

<style lang="less" scoped>
</style>