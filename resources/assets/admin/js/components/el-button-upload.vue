<template>
    <el-upload action="http://up-z2.qiniu.com/" :show-file-list="false" :http-request="httpRequest" style="display: inline-block;">
        <slot></slot>
    </el-upload>
</template>

<script>
    export default {
        data() {
            return {
            };
        },
        computed: {
            token() {return this.$store.state.uploadToken;},
        },
        methods: {
            upload(action, formData) {
                let self = this;
                formData.append('token', this.token);
                this.$http.post(action, formData).then(resp => {
                    if (resp.data.code === 0) {
                        self.$emit('success', resp.data.data);
                    }
                });
            },
            getToken() {
                let self = this;
                return this.$http.get('/upload-token').then(resp => {
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