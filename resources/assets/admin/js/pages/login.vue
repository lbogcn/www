<template>
    <el-card class="box-card">
        <div slot="header" class="clearfix">
            <span>登录</span>
        </div>
        <div class="text item">
            <el-form label-width="60px">
                <el-form-item label="账号">
                    <el-input v-model="form.username"></el-input>
                </el-form-item>

                <el-form-item label="密码">
                    <el-input v-model="form.password" type="password"></el-input>
                </el-form-item>

                <el-form-item>
                    <el-button type="primary" @click="handleSubmit">登录</el-button>
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
                    username: null,
                    password: null
                },
            };
        },
        methods: {
            handleSubmit() {
                let self = this;
                this.$http.post('/login', this.form).then(() => {
                    self.$store.commit('login');
                    self.$router.back(-1);
                    self.$message({message: '登录成功', type: 'success',});
                });
            }
        },
        mounted() {
            this.$http.defaults.loadTarget = '.wrapper';
        }
    }
</script>

<style lang="less" scoped>
    .box-card {
        width: 400px;
        margin: 30px auto;
    }
</style>