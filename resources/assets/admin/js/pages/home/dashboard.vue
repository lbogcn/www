<template>
    <div id="dashboard" class="page">
        <h2 class="app-name">欢迎来到 <b>{{appName}}</b> 管理后台</h2>

        <el-row>
            <el-col :span="12" class="stat-box" id="stat-pv">
                <h3 class="title">PV统计</h3>

                <el-radio-group v-model="stat.pv.daily" size="mini">
                    <el-radio-button :label="7">最近7日</el-radio-button>
                    <el-radio-button :label="30">最近30日</el-radio-button>
                </el-radio-group>

                <div id="stat-pv-chart" style="width: 100%; height: 300px;"></div>
            </el-col>

            <el-col :span="12" class="stat-box" id="stat-baiduspider">
                <h3 class="title">百度抓取次数</h3>

                <el-radio-group v-model="stat.baidu_spider.daily" size="mini">
                    <el-radio-button :label="7">最近7日</el-radio-button>
                    <el-radio-button :label="30">最近30日</el-radio-button>
                </el-radio-group>

                <div id="stat-baiduspider-chart" style="width: 100%; height: 300px;"></div>
            </el-col>
        </el-row>
    </div>
</template>

<script>
    import echarts from 'echarts';

    export default {
        data() {
            return {
                stat: {
                    pv: {daily: 0, chart: null,},
                    baidu_spider: {daily: 0, chart: null,},
                }
            };
        },
        computed: {
            appName() {return this.$store.state.appName},
        },
        methods: {
            queryStat(type, daily, loadTarget) {
                let self = this;
                return new Promise((resolve) => {
                    self.$http.get('/stat?type=' + type + '&daily=' + daily, {loadTarget}).then(resp => {
                        if (resp.data.code === 0) {
                            resolve(resp.data.data[type]);
                        }
                    });
                });
            },
            drawPv(axis, series){
                this.stat.pv.chart.setOption({
                    //数据提示框配置
                    tooltip: {trigger: 'axis'},
                    //轴配置
                    xAxis: [{
                        type: 'category',
                        data:  axis,
                    }],
                    //Y轴配置
                    yAxis: [{
                        type: 'value',
                    }],
                    //图表Series数据序列配置
                    series: [{
                        name: '浏览量（PV）',
                        type: 'line',
                        data: series,
                    }]
                });
            },
            drawBaiduSpider(axis, series){
                this.stat.baidu_spider.chart.setOption({
                    //数据提示框配置
                    tooltip: {trigger: 'axis'},
                    //轴配置
                    xAxis: [{
                        type: 'category',
                        data:  axis,
                    }],
                    //Y轴配置
                    yAxis: [{
                        type: 'value',
                    }],
                    //图表Series数据序列配置
                    series: [{
                        name: '浏览量（PV）',
                        type: 'line',
                        data: series,
                    }]
                });
            }
        },
        watch: {
            'stat.pv.daily'(val) {
                let self = this;
                this.queryStat('pv', val, '#stat-pv').then(data => {
                    self.drawPv(Object.keys(data), Object.values(data));
                });
            },
            'stat.baidu_spider.daily'(val) {
                let self = this;
                this.queryStat('baidu_spider', val, '#stat-baiduspider').then(data => {
                    self.drawBaiduSpider(Object.keys(data), Object.values(data));
                });
            },
        },
        created() {
            this.$nextTick(function() {
                this.stat.pv.chart = echarts.init(document.getElementById('stat-pv-chart'));
                this.stat.pv.daily = 7;
                this.stat.baidu_spider.chart = echarts.init(document.getElementById('stat-baiduspider-chart'));
                this.stat.baidu_spider.daily = 7;
            })
        },
    }
</script>

<style lang="less">
    #dashboard{
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;

        .app-name {
            text-align: center;
            font-weight: normal;
            border-bottom: 1px #f0f0f0 solid;
            padding-bottom: 10px;
            margin: auto auto 20px;
        }

        .stat-box {
            margin: 10px 0 15px;

            .title {
                text-align: center;
            }
        }
    }
</style>