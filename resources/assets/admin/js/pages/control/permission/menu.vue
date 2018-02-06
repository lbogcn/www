<template>
    <div class="page">
        <h2 class="page-header">菜单管理</h2>

        <el-row>
            <el-col class="text-right">
                <!--<el-button type="success" size="mini" @click="">新建</el-button>-->
            </el-col>
        </el-row>

        <el-row>
            <el-col>
                <el-tree
                        :data="menu"
                        :props="defaultProps"
                        node-key="route"
                        default-expand-all
                        :expand-on-click-node="false"
                        :render-content="renderContent">
                </el-tree>
            </el-col>
        </el-row>

        <el-dialog :visible.sync="dialogVisibleEdit" :modal-append-to-body="false" :close-on-click-modal="false">
            <el-form size="small" label-width="80px">
                <el-form-item label="菜单">
                    <p>{{storeData.menuTitle}}</p>
                </el-form-item>
                <el-form-item label="角色">
                    <el-select v-model="storeData.role_id" multiple style="width: 100%;">
                        <el-option v-for="role in roles" :key="role.id" :value="role.id" :label="role.role"></el-option>
                    </el-select>
                </el-form-item>
            </el-form>

            <span slot="footer" class="dialog-footer">
                <el-button type="primary" size="mini" @click="handleStore">保存</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                menu: [],
                roles: [],
                defaultProps: {
                    children: 'childs',
                    label: 'title'
                },
                dialogVisibleEdit: false,
                storeData: {}
            };
        },
        methods: {
            load() {
                let self = this;
                let action = '/permission/menu';
                this.$http.get(action).then(resp => {
                    if (resp.data.code === 0) {
                        self.menu = resp.data.data.menu;
                        self.roles = resp.data.data.roles;
                    }
                });
            },
            handleEdit(menu) {
                this.storeData = {
                    menuTitle: menu.title,
                    route: menu.route,
                    role_id: [],
                };

                if (menu.roles) {
                    for (let i in menu.roles) {
                        this.storeData.role_id.push(menu.roles[i].id);
                    }
                }

                this.dialogVisibleEdit = true;
            },
            handleStore() {
                let self = this;
                this.$http.post('/permission/menu', this.storeData).then(resp => {
                    if (resp.data.code === 0) {
                        self.$message({type: 'success', message: '保存成功!'});
                        self.dialogVisibleEdit = false;
                        self.load();
                    }
                });
            },
            renderContent(h, {node, data, store}) {
                let list = [h('span', node.label),];
                let self = this;

                if (!data.childs) {
                    let roleText = [];
                    let roles = data.roles || [];
                    let opt = {attrs: {'class': ['node-desc']}};

                    if (roles.length === 1) {
                        roleText.push(h('span', opt, [
                            h('b', opt, roles[0].role),
                            h('span', '1个角色')
                        ]));
                    } else if (roles.length > 1) {
                        roleText.push(h('span', opt, [
                            h('b', opt, roles[0].role),
                            h('span', '等' + roles.length + '个角色')
                        ]));
                    } else {
                        opt.attrs.style = 'font-style: italic; color: #bbb;';
                        roleText.push(h('span', opt, '暂无角色'));
                    }

                    list.push(
                        h('span', [
                            h('span', roleText),
                            h('el-button', {
                                attrs: {size: 'mini', type: 'text'},
                                on: {
                                    click: function () {
                                        self.handleEdit(data);
                                    }
                                }
                            }, '配置'),
                        ])
                    );
                }

                return h('span', {attrs: {style: 'flex: 1; display: flex; align-items: center; justify-content: space-between; font-size: 14px; padding-right: 8px;'}}, list);
            }
        },
        mounted() {
            this.$http.defaults.loadTarget = '.wrapper';
            this.load();
        }
    };
</script>

<style lang="less">
    .node-desc {
        padding: 0 5px;
        color: #999;

        b {
            color: #555;
        }
    }
</style>