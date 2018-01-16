<template>
    <div>
        <el-row class="navbar-horizontal">
            <div class="brand">
                Brand
            </div>

            <el-menu
                    :default-active="modulePath[0]"
                    class="navbar-first"
                    mode="horizontal"
                    @select="onSelectModule"
                    background-color="#545c64"
                    text-color="#fff"
                    active-text-color="#ffd04b">
                <el-menu-item v-for="menu in menus" :index="menu.name" :key="menu.name">{{menu.title}}</el-menu-item>
            </el-menu>
        </el-row>

        <div class="wrapper">
            <div class="navbar-vertical">
                <el-menu
                        :default-active="modulePath[1]"
                        @select="handleJump"
                        background-color="#545c64"
                        text-color="#fff"
                        active-text-color="#ffd04b">

                    <template v-for="menu in verticalMenus">
                        <el-submenu v-if="!menu.is_link" :index="menu.name">
                            <template slot="title">
                                <i v-if="menu.icon" :class="menu.icon"></i>
                                <span>{{menu.title}}</span>
                            </template>

                            <el-menu-item v-for="child in menu.childs" :key="child.name" :index="menu.name + '/' + child.name">{{child.title}}</el-menu-item>
                        </el-submenu>

                        <el-menu-item v-if="menu.is_link" :index="menu.name">
                            <i v-if="menu.icon" :class="menu.icon"></i>
                            <span>{{menu.title}}</span>
                        </el-menu-item>
                    </template>
                </el-menu>
            </div>

            <div class="container">
                <router-view></router-view>
            </div>

        </div>
    </div>
</template>

<script>
    import router from '../router';

    export default {
        data() {
            return {
                menus: {
                    home: {
                        name: 'home',
                        title: '首页',
                        childs: {
                            dashboard: {
                                name: 'dashboard',
                                title: '仪表盘',
                                is_link: true,
                            }
                        },
                    },
                    process_center: {
                        name: 'process_center',
                        title: '处理中心',
                        childs: {
                            user: {
                                name: 'user',
                                title: '用户中心',
                                icon: 'el-icon-menu',
                                childs: {
                                    server: {
                                        name: 'server',
                                        title: '客户服务',
                                    },
                                    manage: {
                                        name: 'manage',
                                        title: '用户管理',
                                    },
                                    test: {
                                        name: 'test',
                                        title: '这是一个测试'
                                    }
                                }
                            },

                            operation: {
                                name: 'operation',
                                title: '运营中心',
                                childs: {
                                    banner: {
                                        name: 'banner',
                                        title: '轮播图管理',
                                    },

                                    push: {
                                        name: 'push',
                                        title: '推送管理',
                                    }
                                }
                            },

                            test: {
                                name: 'test',
                                title: '测试',
                                is_link: true,
                            }
                        },
                    },
                    order: {
                        name: 'order',
                        title: '订单管理',
                        childs: {

                        },
                    },
                },
                verticalMenus: {},
                verticalSubMenus: {},
                modulePath: ['', ''],
            };
        },
        router,
        methods: {
            onSelectModule(index) {
                this.verticalMenus = this.menus[index].childs;
                this.modulePath[0] = index;
            },
            handleJump(index) {
                this.modulePath[1] = index;
                let path = '/' + this.modulePath.join('/');

                this.$router.push(path);
            },
            initNav(route) {
                let char = '/';
                let path = route.replace(new RegExp('^\\'+char+'+|\\'+char+'+$', 'g'), '').split(char);

                this.onSelectModule(path.splice(0, 1).join(''));
                this.handleJump(path.join('/'));
            }
        },
        created() {
            this.initNav(this.$route.path);
        }
    }
</script>

<style lang="less">
    .navbar-horizontal {
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
    .wrapper{
        position: absolute;
        left: 0;
        right: 0;
        top: 62px;
        bottom: 0;

        .navbar-vertical{
            float: left;
            width: 200px;
            height: 100%;
            position: relative;
            background-color: #545c64;
            overflow: auto;
            margin-right: 10px;

            .el-menu {
                width: 100%;
            }
        }
    }
</style>