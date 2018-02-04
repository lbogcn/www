<template>
    <el-button type="danger" size="mini" @click="$emit('click')" icon="el-icon-delete">
        <slot></slot>
    </el-button>
</template>