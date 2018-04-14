<template>
    <el-button type="primary" @click="$emit('click')" icon="el-icon-search">
        <slot></slot>
    </el-button>
</template>