<template>
    <el-button type="primary" size="mini" @click="$emit('click')" icon="el-icon-search">
        <slot></slot>
    </el-button>
</template>