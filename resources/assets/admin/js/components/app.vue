<template>
    <el-container>
        <el-header>
            <div class="brand">
                Brand
            </div>

            <el-menu
                    :default-active="modulePath"
                    class="navbar-first"
                    mode="horizontal"
                    @select="onSelectModule"
                    background-color="#545c64"
                    text-color="#fff"
                    active-text-color="#ffd04b">
                <el-menu-item v-for="menu in menus" :index="menu.name" :key="menu.name">{{menu.title}}</el-menu-item>
            </el-menu>
        </el-header>

        <el-container class="container">
            <el-aside width="200px">
                <el-menu
                        :router="true"
                        :default-active="$route.path"
                        background-color="#545c64"
                        text-color="#fff"
                        active-text-color="#ffd04b">

                    <template v-for="menu in verticalMenus">
                        <el-submenu v-if="!menu.is_link" :index="menu.name">
                            <template slot="title">
                                <i v-if="menu.icon" :class="menu.icon"></i>
                                <span>{{menu.title}}</span>
                            </template>

                            <el-menu-item v-for="child in menu.childs" :key="child.name" :index="'/' + modulePath + '/' + menu.name + '/' + child.name">{{child.title}}</el-menu-item>
                        </el-submenu>

                        <el-menu-item v-if="menu.is_link" :index="'/' + modulePath + '/' + menu.name">
                            <i v-if="menu.icon" :class="menu.icon"></i>
                            <span>{{menu.title}}</span>
                        </el-menu-item>
                    </template>
                </el-menu>
            </el-aside>

            <el-container class="wrapper">
                <el-main>
                    <router-view></router-view>
                </el-main>
            </el-container>
        </el-container>
    </el-container>
</template>

<script>
    import router from '../router';

    export default {
        data() {
            return {
            };
        },
        computed: {
            menus() {return this.$store.state.menus;},
            verticalMenus() {return this.$store.state.verticalMenus;},
            modulePath() {return this.$store.state.modulePath;},
        },
        router,
        methods: {
            onSelectModule(index) {
                this.$store.commit('changeModule', index);
            },
            globalHttpResponse() {
                let self = this;
                this.$http.interceptors.response.use(resp => {
                    switch (resp.data.code) {
                        case -10001:
                            self.$store.commit('setMenus', {});// 置空菜单
                            this.$router.push({path: '/login'});break;
                    }

                    return resp;
                }, error => {return Promise.reject(error);});
            }
        },
        created() {
            this.globalHttpResponse();
            this.$store.commit('loadMenus', this);
        }
    }
</script>

<style lang="less">
    .el-header {
        background-color: #545c64;

        .brand{
            float: left;
            width: 201px;
            text-align: center;
            line-height:60px;
            color: #FFF;
        }

        .navbar-first{float: left;}
    }

    .el-footer {
        background-color: #B3C0D1;
        color: #333;
        text-align: center;
        line-height: 60px;
    }

    .container{
        position: fixed;
        top: 62px;
        left: 0;
        right: 0;
        bottom: 0;
    }

    .el-aside {
        overflow-y: auto;
        overflow-x: hidden;

        .el-menu {
            width: 100%;
            height: 100%;
        }
    }

    .wrapper {
        margin-left: 3px;
    }

</style>