<template>
    <el-upload action="" :show-file-list="false" :http-request="httpRequest" :disabled="loading" class="el-button-upload">
        <el-button :type="type || 'success'" :loading="loading" :icon="icon || 'el-icon-upload'">
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
        props: ['icon', 'type'],
        computed: {
            uploadToken() {return this.$store.state.uploadToken;},
        },
        methods: {
            upload(formData) {
                let self = this;
                formData.append('token', this.uploadToken.token);
                this.$http.post(this.uploadToken.action, formData, {loading: false}).then(data => {
                    self.loading = false;
                    self.$emit('success', data);
                });
            },
            getToken() {
                let self = this;
                return this.$http.get('/upload-token', {loading: false}).then(data => {
                    self.$store.commit('setUploadToken', data);
                });
            },
            httpRequest(option) {
                let self = this;
                let formData = new FormData();
                formData.append(option.filename, option.file);
                this.loading = true;

                if (!this.uploadToken) {
                    this.getToken().then(() => {
                        self.upload(formData);
                    });
                } else {
                    self.upload(formData);
                }
            }
        }
    }
</script>