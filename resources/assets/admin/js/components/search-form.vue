<template>
    <el-dialog :visible.sync="data.visible" :modal-append-to-body="false" :close-on-click-modal="false" title="搜索" class="default-dialog">
        <el-form :model="data" ref="searchForm" label-width="80px" @submit.native.prevent>
            <el-form-item v-for="(item, index) in data.formItem" :label="item.label" :key="index" :prop="'formData.' + item.field">
                <el-input v-model="data.formData[item.field]" v-if="item.type === 'input'"></el-input>

                <el-select v-model="data.formData[item.field]" v-if="item.type === 'select'" :clearable="item.clearable">
                    <el-option v-for="option in item.options" :value="getOptionProp(option, item.optionProp).value" :label="getOptionProp(option, item.optionProp).label" :key="getOptionProp(option).value"></el-option>
                </el-select>
            </el-form-item>
        </el-form>

        <span slot="footer" class="dialog-footer">
            <el-button type="primary" @click="$emit('submit')">搜索</el-button>
            <el-button @click="resetForm">重置</el-button>
        </span>
    </el-dialog>
</template>

<script>
    export default {
        props: ['data'],
        methods: {
            resetForm() {
                this.$refs['searchForm'].resetFields();
            },
            getOptionProp(option, prop) {
                prop = prop || {label: 'label', value: 'value'};
                return {
                    label: option[prop.label],
                    value: option[prop.value],
                };
            }
        },
        watch: {
            'data'(val) {
                this.data = val;
                this.$emit('update:data', this.data);
            }
        },
    }
</script>