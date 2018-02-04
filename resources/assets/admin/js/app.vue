<template>
    <el-container>
        <el-header>
            <div class="brand">
                Brand
            </div>

            <el-menu
                    :default-active="modulePath"
                    class="navbar-module"
                    mode="horizontal"
                    @select="onSelectModule"
                    background-color="#545c64"
                    text-color="#fff"
                    active-text-color="#ffd04b">
                <el-menu-item v-for="menu in menus" :index="menu.name" :key="menu.name">{{menu.title}}</el-menu-item>
            </el-menu>

            <el-menu
                    class="navbar-control"
                    mode="horizontal"
                    background-color="#545c64"
                    text-color="#fff"
                    @select="onSelectControl"
                    active-text-color="#ffd04b">
                <el-submenu index="control-user" v-if="user.name">
                    <template slot="title">{{user.name}}</template>
                    <el-menu-item index="logout">退出登录</el-menu-item>
                </el-submenu>
            </el-menu>
        </el-header>

        <el-container class="container">
            <el-menu
                    class="vertical-menu"
                    :router="true"
                    :default-active="$route.path"
                    :collapse="isCollapse"
                    background-color="#545c64"
                    text-color="#fff"
                    active-text-color="#ffd04b">

                <template v-for="menu in verticalMenus">
                    <el-submenu v-if="menu.childs" :index="menu.name">
                        <template slot="title">
                            <i v-if="menu.icon" :class="menu.icon"></i>
                            <span>{{menu.title}}</span>
                        </template>

                        <el-menu-item v-for="child in menu.childs" :key="child.name" :index="'/' + modulePath + '/' + menu.name + '/' + child.name">
                            <i v-if="child.icon" :class="child.icon"></i>
                            <span>{{child.title}}</span>
                        </el-menu-item>
                    </el-submenu>

                    <el-menu-item v-if="!menu.childs" :index="'/' + modulePath + '/' + menu.name">
                        <i v-if="menu.icon" :class="menu.icon"></i>
                        <span>{{menu.title}}</span>
                    </el-menu-item>
                </template>

                <div @click="isCollapse = !isCollapse" class="menu-collapse">
                    <i v-show="!isCollapse" class="el-icon-lb-shouqicaidan"></i>
                    <i v-show="isCollapse" class="el-icon-lb-zhankaicaidan"></i>
                </div>
            </el-menu>

            <el-container class="wrapper">
                <el-main>
                    <router-view></router-view>
                </el-main>
            </el-container>
        </el-container>
    </el-container>
</template>

<script>
    import router from './router';

    export default {
        data() {
            return {
                isCollapse: false,
            };
        },
        computed: {
            menus() {return this.$store.state.menus;},
            verticalMenus() {return this.$store.state.verticalMenus;},
            modulePath() {return this.$store.state.modulePath;},
            user() {return this.$store.state.user;},
        },
        router,
        methods: {
            onSelectModule(index) {
                this.$store.commit('changeModule', index);
            },
            onSelectControl(index) {
                switch (index) {
                    case 'logout':
                        this.logout();break;
                }
            },
            logout() {
                let self = this;
                this.$http.get('/logout').then(resp => {
                    if (resp.data.code === 0) {
                        self.$store.commit('afterLogout');// 置空菜单
                        self.$router.push({path: '/login'});
                    }
                });
            },
            globalHttpResponse() {
                let self = this;
                this.$http.interceptors.response.use(resp => {
                    switch (resp.data.code) {
                        case -10001:
                            self.$store.commit('afterLogout');// 置空菜单
                            self.$router.push({path: '/login'});
                            break;
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
        padding: 0;

        .brand{
            float: left;
            width: 201px;
            text-align: center;
            line-height:60px;
            color: #FFF;
        }

        .navbar-module{float: left;}
        .navbar-control{
            float: right;
            margin-right: 20px;

            .el-menu-item{
                min-width: auto;
            }
        }
    }

    .el-main {
        padding: 0;
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

    .wrapper {
        margin-left: 3px;
    }

    .vertical-menu {
        &:not(.el-menu--collapse) {
            width: 200px;

            .menu-collapse{
                text-align: right;
                right: 20px;
            }
        }

        .menu-collapse{
            position: absolute;
            bottom: 0;
            right: 0;
            left: 0;
            cursor: pointer;
            color: #fff;
            font-size: 24px;
            padding: 5px 0;
            text-align: center;
        }
    }

</style>