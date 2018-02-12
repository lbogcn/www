<template>
    <div id="dashboard" class="page">
        <h2 class="app-name">欢迎来到 <b>{{appName}}</b> 管理后台</h2>
    </div>
</template>

<script>
    export default {
        computed: {
            appName() {return this.$store.state.appName},
        },
    }
</script>

<style lang="less">
    #dashboard{
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;

        .app-name {
            margin: auto;
            text-align: center;
            font-weight: normal;
        }
    }
</style>