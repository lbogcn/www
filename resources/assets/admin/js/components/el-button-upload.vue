<template>
    <el-upload action="http://up-z2.qiniu.com/" :show-file-list="false" :http-request="httpRequest" :disabled="loading" class="el-button-upload">
        <el-button type="success" size="mini" :loading="loading" icon="el-icon-upload">
            <slot></slot>
        </el-button>
    </el-upload>
</template>

<script>
    export default {
        data() {
            return {
                loading: false,
            };
        },
        computed: {
            token() {return this.$store.state.uploadToken;},
        },
        methods: {
            upload(action, formData) {
                let self = this;
                formData.append('token', this.token);
                this.$http.post(action, formData, {loading: false}).then(resp => {
                    self.loading = false;

                    if (resp.data.code === 0) {
                        self.$emit('success', resp.data.data);
                    }
                });
            },
            getToken() {
                let self = this;
                return this.$http.get('/upload-token', {loading: false}).then(resp => {
                    if (resp.data.code === 0) {
                        self.$store.commit('setUploadToken', resp.data.data.token);
                    } else {
                        throw resp.data.msg;
                    }
                });
            },
            httpRequest(option) {
                let self = this;
                let formData = new FormData();
                formData.append(option.filename, option.file);
                this.loading = true;

                if (!this.token) {
                    this.getToken().then(() => {
                        self.upload(option.action, formData);
                    });
                } else {
                    self.upload(option.action, formData);
                }
            }
        }
    }
</script>