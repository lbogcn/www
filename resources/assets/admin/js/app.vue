<template>
    <el-container>
        <el-header>
            <div class="brand">
                {{appName}}
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

                <el-submenu index="control-user" v-if="user.name" class="navbar-control">
                    <template slot="title">
                        <i class="el-icon-lb-user"></i>
                        <span>{{user.name}}</span>
                    </template>

                    <el-menu-item index="modify-password"><i class="el-icon-lb-xiugaimima"></i><span>修改密码</span></el-menu-item>
                    <el-menu-item index="logout"><i class="el-icon-lb-logout"></i><span>退出登录</span></el-menu-item>
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
                            <span slot="title">{{child.title}}</span>
                        </el-menu-item>
                    </el-submenu>

                    <el-menu-item v-if="!menu.childs" :index="'/' + modulePath + '/' + menu.name">
                        <i v-if="menu.icon" :class="menu.icon"></i>
                        <span slot="title">{{menu.title}}</span>
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
                isCollapse: window.sessionStorage.getItem('menu_collapse') === 'true',
            };
        },
        computed: {
            menus() {return this.$store.state.menus;},
            verticalMenus() {return this.$store.state.verticalMenus;},
            modulePath() {return this.$store.state.modulePath;},
            user() {return this.$store.state.user;},
            appName() {return this.$store.state.appName},
        },
        router,
        methods: {
            onSelectModule(index) {
                switch (index) {
                    case 'logout':
                        this.logout();break;
                    case 'modify-password':
                        this.$router.push({path: '/' + index});break;
                    default:
                        this.$store.commit('changeModule', index);
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
        watch: {
            isCollapse(val) {
                window.sessionStorage.setItem('menu_collapse', val ? 'true' : 'false');
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
        position: relative;
        z-index: 10001;

        .brand{
            float: left;
            width: 201px;
            text-align: center;
            line-height:60px;
            color: #FFF;
            border-bottom: solid 1px #e6e6e6;
        }

        .navbar-module{
            float: left;
            position: absolute;
            left: 201px;
            right: 0;
            padding-right: 15px;
            
            .navbar-control{
                float: right;

                .el-menu-item{
                    min-width: auto;
                }
            }
        }
    }

    .el-main {
        padding: 0;
        position: relative;
    }

    .el-footer {
        background-color: #B3C0D1;
        color: #333;
        text-align: center;
        line-height: 60px;
    }

    .container{
        position: fixed;
        top: 61px;
        left: 0;
        right: 0;
        bottom: 0;
    }

    .wrapper {
        margin-left: 3px;
    }

    .vertical-menu {
        z-index: 10001;

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