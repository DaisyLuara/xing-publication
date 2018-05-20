<template>
  <div class="home-wrap" v-loading="loading">
    <div class="search-wrap">
      <el-date-picker
      v-model="dataValue"
      type="daterange"
      align="right"
      unlink-panels
      start-placeholder="开始日期"
      end-placeholder="结束日期"
      :default-value="dataValue"
      :clearable="false"
      :picker-options="pickerOptions2"
      @change="dateChangeHandle">
    </el-date-picker>
    </div>
    <div class="tendency-wrap">
      <el-card shadow="always" v-loading="lookerFlag">
        <highcharts :options="pointOptions" class="highchart" ref="pointChar"></highcharts>
      </el-card>
    </div>
    <div class="ranking-wrap">
      <el-row :gutter="20">
        <el-col :span="12">
          <el-card shadow="always" v-loading="pointFlag">
            <highcharts :options="pointTenOptions" class="highchart" ref="pointTenChar"></highcharts>
          </el-card>
        </el-col>
        <el-col :span="12">
          <el-card shadow="always" v-loading="projectFlag">
            <highcharts :options="projectTenOptions" class="highchart" ref="projectTenChar"></highcharts>
          </el-card>
        </el-col>
      </el-row>
    </div>
    <div class="age-gender-wrap">
      <el-row :gutter="20">
        <el-col :span="12">
          <el-card shadow="always"  v-loading="sexFlag">
              <highcharts :options="sexOptions" class="highchart" ref="sexPie"></highcharts>
          </el-card>
        </el-col>
        <el-col :span="12">
          <el-card shadow="always" v-loading="ageFlag">
            <highcharts :options="ageOptions" class="highchart" ref="agePie" ></highcharts>
          </el-card>
        </el-col>
      </el-row>
    </div>
  </div>
</template>
<script>
import { Tabs, TabPane, Button, Row, Col, Card, DatePicker} from 'element-ui'
import Highcharts from 'highcharts';
import loadExporting from 'highcharts/modules/exporting';
import loadExportData from 'highcharts/modules/export-data';
loadExporting(Highcharts);
loadExportData(Highcharts);

import stats from 'service/stats'
import chartData from 'service/chart'

export default {
  components: {
    ElRow: Row,
    ElCol: Col,
    ElCard: Card,
    ElDatePicker: DatePicker
  },
  computed: {
  
  },
  data() {
    return {
      loading: false,
      dataValue: [new Date().getTime() - 3600 * 1000 * 24 * 7, new Date().getTime()],
      pickerOptions2: {
        shortcuts: [{
          text: '今天',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              picker.$emit('pick', [start, end]);
            }
          }, {
          text: '昨天',
            onClick(picker) {
              const end = new Date();
              const start = new Date();
              start.setTime(start.getTime() - 3600 * 1000 * 24);
              end.setTime(end.getTime() - 3600 * 1000 * 24);
              picker.$emit('pick', [start, end]);
            }
          },{
          text: '最近一周',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
            picker.$emit('pick', [start, end]);
          }
        }, {
          text: '最近一个月',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
            picker.$emit('pick', [start, end]);
          }
        },{
          text: '最近三个月',
          onClick(picker) {
            const end = new Date();
            const start = new Date();
            start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
            picker.$emit('pick', [start, end]);
          }
        }]
      },
      pointOptions : {
        title: {
          text: '点位趋势',
          style: {'fontSize': '20px'},
          align: 'left'
        },
        xAxis: {
          title: {
            text: ''
          },
          type: 'category',
        },
        yAxis: [{
          title: {
            text: null,
          },
        }],
        legend: {
          enabled: false
        },
        credits: {
          enabled: false
        },
        tooltip: {
          shared: true,
          formatter: function () {
            var s = ''
            let percent = 0
            if(this.points[1]) {
              percent = new Number(((this.points[1].y - this.points[0].y) /  this.points[0].y) * 100).toFixed(2)
              s = '<b>' + percent +'%' + '</b>';
            }
            console.log(this.points)
            for(let i=0;i<this.points.length; i++) {
              s += '<br/>' + this.points[i].point.name + ': ' +
              this.y;
            }
            return s;
          },
        },
        plotOptions: {
          area: {
            marker: {
              enabled: false,
              symbol: 'circle',
              radius: 2,
              states: {
                hover: {
                  enabled: true
                }
              }
            }
          }
        },
        series: [{
          name: '围观人数',
        }, {
          name: '',
          type: 'area',
          lineWidth: 1,
          color: '#e09f91',
          fillOpacity: 0.5,
          zIndex: 0,
          marker: {
            enabled: false
          }
        }]
      },
      pointTenOptions: {
        chart: {
          type: 'bar'
        },
        title: {
          text: '点位前10名',
          align: 'left',
          style: {'fontSize': '20px'}
        },
        subtitle: {
        },
        xAxis: {
          title:{
            text: ''
          },
          type: 'category',
          labels: {
            formatter: function() {
              return this.value.substring(0,5) + '...'
						},
            // staggerLines: 3,
          }
        },
        yAxis: {
          min: 0,
          title: null,
          labels: {
            overflow: 'justify',
          }
        },
        tooltip: {
        },
        plotOptions: {
          bar: {
            dataLabels: {
              enabled: true
            }
          }
        },
        legend: false,
        credits: {
          enabled: false
        },
        series: [{
          color: '#abce5b',
          name: '数量',
        }]
      },
      projectTenOptions: {
        chart: {
          type: 'bar'
        },
        title: {
          text: '节目前10名',
          align: 'left',
          style: {'fontSize': '20px'}
        },
        subtitle: {
        },
        
        xAxis: {
          type: 'category',
          title:{
            text: ''
          },
          labels: {
            autoRotationLimit:40,
            formatter: function() {
              return this.value.substring(0,5) + '...'
						},
          }
        },
        yAxis: {
          min: 0,
          title: null,
          labels: {
            overflow: 'justify'
          }
        },
        tooltip: {
        },
        plotOptions: {
          bar: {
            dataLabels: {
              enabled: true
            }
          }
        },
        legend: false,
        credits: {
          enabled: false
        },
        series: [{
          color: '#abce5b',
          name: '数量',
        }]
      },
      sexOptions: {
        chart:{
          animation: {
            duration: 1000
          },
          type: 'pie',
          plotBackgroundColor: null,
          plotBorderWidth: null,
          plotShadow: false,
        },
        tooltip: {
          headerFormat: '{性别访问数}<br>',
          pointFormat: '{point.name}: <b>{point.y} 占比{point.percentage:.1f}%</b>'
        },
        plotOptions: {
          pie: {
            // innerSize: 100,
            innerSize: '20%',
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
              enabled: true,
              format: '{point.name} {point.y} 占比{point.percentage:.1f}% '
            },
            showInLegend: true
          }
        },
        title: {
          text: '性别趋势',
          style: {'fontSize': '20px'},
          align: 'left'
        },
        legend: {
          align: 'right',
          verticalAlign: 'middle',
          layout: 'vertical',
          symbolHeight: 12,
          symbolWidth: 20,
          symbolRadius: 2,
          squareSymbol: false
        },
        credits: {
          enabled: false
        },
        yAxis: [{
          title: {
            text: '',
          }
        }],
        series: [{
          type: 'pie',
          name: '性别访问数',
        }]
      },
      ageOptions: {
        chart:{
          type: 'column',
          animation: {
            duration: 3000
          },
        },
        plotOptions: {
          column: {
            dataLabels: {
              enabled: true
            }
          }
        },
        title: {
          text: '年龄趋势',
          style: {'fontSize': '20px'},
          align: 'left'
        },
        xAxis: {
          title: {
            text: ''
          },
          type: 'category'
        },
        yAxis: [{
          title: {
            text: '年龄统计',
          },
          tickAmount: 5
        }],
        legend: {
          enabled: false
        },
        credits: {
          enabled: false
        },
        series: [{
          color: "#7cb5ec",
          name:"年龄统计",
        }]
      },
      ageFlag: false,
      sexFlag: false,
      projectFlag: false,
      pointFlag: false,
      lookerFlag: false
    }
  },
  mounted() {
  },
  created() {
    this.getProjectTenChartData()
    this.getPointTenChartData()
    this.getSexChartData()
    this.getAgeChartData()
    this.getLookersChartData()
  },
  methods: {
   dateChangeHandle(){
    this.getProjectTenChartData()
    this.getPointTenChartData()
    this.getSexChartData()
    this.getAgeChartData()
    this.getLookersChartData()
    },
    getProjectTenChartData() {
      this.getChartData('project','3')
    },
    getPointTenChartData() {
      this.getChartData('point','2')
    },
    getSexChartData() {
      this.getChartData('sex', '5')
    },
    getAgeChartData() {
      this.getChartData('age', '4')
    },
    getLookersChartData() {
      this.getChartData('lookers', '1')
    },
    getChartData (type,id) {
      let args = {
        start_date : this.handleDateTransform(this.dataValue[0]),
        end_date: this.handleDateTransform(new Date(this.dataValue[1]).getTime()),
        home_page: true
      }
      switch(id) {
        case '1':
        args.id = '1'
        this.lookerFlag = true;
        break;
        case '2':
        args.id = '2'
        this.pointFlag = true;
        break;
        case '3':
        args.id = '3'
        this.projectFlag = true;
        break;
        case '4':
        args.id = '4'
        this.ageFlag = true;
        break;
        case '5':
        args.id = '5'
        this.sexFlag = true;
        break;
      }
      chartData.getChartData(this, args).then((response) => {
        switch(type) {
          case 'project':
            let projectData = []
            let projectChart = this.$refs.projectTenChar.chart;
            if(response.length>0){
              this.drawChart(response,projectData)
            projectChart.update({
              series: [{
                data: projectData,
              }],
            });
            this.projectFlag = false;
          }else{
            this.projectFlag = false;
            projectChart.series[0].setData(projectData,true)
          }
        break
        case 'point':
          let pointData = []
          let pointChart = this.$refs.pointTenChar.chart;
          if(response.length>0){
            this.drawChart(response,pointData)
          pointChart.update({
            series: [{
              data: pointData,
            }],
          });
          this.pointFlag = false;
          }else{
            this.pointFlag = false;
            pointChart.series[0].setData(pointData,true)
          }
        break
        case 'sex':
          let sexData = []
          let sexChart = this.$refs.sexPie.chart;
          if(response.length>0){
            for(let i = 0; i < response.length; i++){
              if(i==0){
                sexData.push({'name':response[i].display_name,'y':parseInt(response[i].count),'sliced': true,'selected': true,color: '#5eb6c8'})
              }else{
                sexData.push({'name':response[i].display_name, color: '#ffd259',y:parseInt(response[i].count)})
              }
            }
          sexChart.update({
            series: [{
              data: sexData,
            }],
          });
          this.sexFlag = false;
          }else{
            this.sexFlag = false;
            sexChart.series[0].setData(sexData,true)
          }
        break
        case 'age':
          let ageData = []
          let ageChart = this.$refs.agePie.chart;
          if(response.length > 0){
            this.drawChart(response, ageData)
          ageChart.update({
            series: [{
              data: ageData,
            }],
          });
          this.ageFlag = false;
          }else{
            this.ageFlag = false;
            ageChart.series[0].setData(ageData,true)
          }
        break
        case 'lookers':
          let lookersData = []
          let lookerChart = this.$refs.pointChar.chart;
          if(response.length>0){
            this.drawChart(response,lookersData)
          lookerChart.update({
            series: [{
              type: 'area',
              lineWidth: 1,
              color: '#e09f91',
              fillOpacity: 0.5,
              zIndex: 0,
              marker: {
                enabled: false
              },
              data: lookersData,
            },
            ],
          });
          this.lookerFlag = false;
          }else{
            this.lookerFlag = false;
            lookerChart.update({
              series: [{
                data: lookersData,
              }],
            });
          }
        break
      }
      }).catch(err => {
        switch(type) {
          case 'project':
            this.projectFlag = false;
          break
          case 'point':
            this.pointFlag = false;
          break
          case 'sex':
            this.sexFlag = false;
          break
          case 'age':
            this.ageFlag = false;
          break
          case 'lookers':
            this.lookerFlag = false;
          break
        }
      })
    },
    drawChart(response,data) {
      for(let j = 0; j < response.length; j++){
        if(j==0){
          data.push({'name':response[j].display_name,'y':parseInt(response[j].count)})
        }else{
          data.push([response[j].display_name,parseInt(response[j].count)])
        }
      }
    },
    handleDateTransform (valueDate) {
      let date = new Date (valueDate)
      let year = date.getFullYear() + '-';
      let mouth = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
      let day = (date.getDate() <10  ? '0'+(date.getDate()) : date.getDate()) + '';
      return year+mouth+day
    }
  },
}
</script>
<style lang="less" scoped>
  .home-wrap {
    background-color: #fff;
    padding: 15px;
    .search-wrap{
      text-align: right;
      margin-top: 5px;
      font-size: 16px;
      align-items: center;
      margin-bottom: 10px;
    }
    .tendency-wrap, .ranking-wrap, .age-gender-wrap{
      margin-bottom: 15px;
    }
    .age-gender-wrap{

    }
  }
</style>
