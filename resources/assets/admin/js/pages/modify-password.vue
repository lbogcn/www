<template>
    <el-card class="box-card">
        <div slot="header" class="clearfix">
            <span>修改密码</span>
        </div>
        <div class="text item">
            <el-form size="small" label-width="80px">
                <el-form-item label="原密码">
                    <el-input v-model="form.old_password" type="password"></el-input>
                </el-form-item>

                <el-form-item label="新密码">
                    <el-input v-model="form.password" type="password"></el-input>
                </el-form-item>

                <el-form-item label="确认密码">
                    <el-input v-model="form.password_confirmation" type="password"></el-input>
                </el-form-item>

                <el-form-item>
                    <el-button size="mini" type="primary" @click="handleSubmit">提交</el-button>
                </el-form-item>
            </el-form>
        </div>
    </el-card>
</template>

<script>
    export default {
        data() {
            return {
                form: {
                    old_password: null,
                    password: null,
                    password_confirmation: null,
                },
            };
        },
        methods: {
            handleSubmit() {
                let self = this;
                this.$http.post('/modify-password', this.form).then(resp => {
                    if (resp.data.code === 0) {
                        self.$message({message: '修改成功，请重新登录', type: 'success',});
                        self.$router.push({path: '/login'});
                    }
                });
            }
        },
        mounted() {
        }
    }
</script>

<style lang="less" scoped>
    .box-card {
        width: 400px;
        margin: 30px auto;
    }
</style>