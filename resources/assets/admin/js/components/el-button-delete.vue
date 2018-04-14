<template>
    <el-button type="danger" @click="$emit('click')" icon="el-icon-delete">
        <slot></slot>
    </el-button>
</template>