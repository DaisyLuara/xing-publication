<template>
  <div class="point-data-wrapper" :element-loading-text="setting.loadingText" v-loading="setting.loading">
    <div class="headline-wrapper">
      <div>
        <!-- <span>节目名称：{{pointName}} </span> -->
        <el-select v-model="userSelect" filterable placeholder="请选择用户(可搜索)" v-if="showUser" :loading="projectLoading" remote :remote-method="getUser" @change="userChangeHandle">
          <el-option
            v-for="item in userList"
            :key="item.id"
            :label="item.name"
            :value="item.id">
          </el-option>
        </el-select>
        <el-select v-model="projectSelect" filterable placeholder="请选择节目(可搜索)" :loading="projectLoading" remote :remote-method="getProject" @change="projectChangeHandle">
          <el-option
            v-for="item in projectList"
            :key="item.id"
            :label="item.name"
            :value="item.alias">
          </el-option>
        </el-select>
        <el-button @click="searchHandle" type="primary">搜索</el-button>
        <el-button @click="resetSearch">重置</el-button>
      </div>
      <el-date-picker
      v-model="dateTime"
      type="daterange"
      start-placeholder="开始日期"
      end-placeholder="结束日期"
      :default-value="dateTime" :picker-options="pickerOptions2" @change="dateChangeHandle" :clearable="false" style="width:230px">
      </el-date-picker>
    </div>
    <div class="content-wrapper" v-loading="poepleCountFlag">
      <ul class="btns-wrapper">
        <li v-for="(item, key) in peopleCount" :key="key">
          <a class="btn" :class="{'active': item.name == active}" @click="lineDataHandle(item)">
            <!-- <i class="title" v-if="item.alias === 'scannum'">
              {{item.name}} / {{(item.count==0 & item.out == 0) ? 0 : new Number((item.count/item.out)*100).toFixed(1)}}%
            </i> -->
            <i class="title">
              {{item.name}}
            </i>
            <span class="count" v-if="item.alias === 'scannum'">
              {{item.count}} / {{(item.count==0 & item.out == 0) ? 0 : new Number((item.count/item.out)*100).toFixed(1)}}%
            </span>
            <span class="count" v-if="item.alias !== 'scannum'">
              {{item.count}}
            </span>
            <i class="arrow-icon"></i>
            <i class="right-arrow-icon" v-if="key != peopleCountLength -1">{{(peopleCount[key+1].count==0 & item.count == 0) ? 0 : new Number((peopleCount[key+1].count/item.count)*100).toFixed(1)}}%</i>
          </a>
        </li>
      </ul>
      <div class="chart">
        <highcharts :options="splineOptions" class="highchart" ref="lineChar"></highcharts>
      </div>
    </div>
    <div class="pie-content-wrapper">
      <el-row :gutter="20">
        <el-col :span="12">
          <div class="pie-sex-wrapper">
            <div class="headline-wrapper">
              <span>性别分布</span>
            </div>
            <div class="pie-sex-content" v-loading="sexFlag">
              <div v-show="ageType" style="text-align:center">暂无数据</div>
              <highcharts :options="sexPieOptions" class="highchart" ref="sexPie"></highcharts>
            </div>
          </div>
        </el-col>
        <el-col :span="12">
          <div class="pie-age-wrapper">
            <div class="headline-wrapper">
              <span>年龄分布</span>
            </div>
            <div class="pie-age-content" v-loading="ageFlag">
              <div v-show="sexType" style="text-align:center">暂无数据</div>
              <highcharts :options="agePieOptions" class="highchart" ref="agePie" ></highcharts>
            </div>
          </div>
        </el-col>
      </el-row>
    </div>
  </div>
</template>
<script>
import stats from 'service/stats'
import { Row, Col, DatePicker, Select, Option, Button} from 'element-ui'
import Highcharts from 'highcharts';
import load3D from 'highcharts/highcharts-3d';
load3D(Highcharts)

export default {
  components:{
    'el-row': Row,
    'el-col': Col,
    'el-date-picker': DatePicker,
    'el-select': Select,
    'el-button': Button,
    'el-option': Option
  },
  data(){
    
    return {
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: "拼命加载中"
      },
      dateTime: [new Date().getTime() - 3600 * 1000 * 24 * 6, new Date().getTime()],
      pickerOptions2: {
        disabledDate(time) {
          return time.getTime() < new Date('2016-12-31');
        }
      },
      projectSelect: '',
      projectLoading: false,
      projectList: [],
      active: '围观人数',
      splineOptions : {
        chart:{
          type: 'spline'
        },
        title: {
          text: null
        },
        xAxis: {
          type: 'category'
        },
        yAxis: [{
          title: {
            text: null,
          },
          floor: 0,
          tickAmount: 5
        }],
        legend: {
          enabled: false
        },
        credits: {
          enabled: false
        },
        series: [{
          color: "#919fc1",
          name:"数量"
        }]
      },
       agePieOptions : {
        chart:{
          type: 'column',
          // options3d: {
          //   enabled: true,
          //   alpha: 3,
          //   beta: 13,
          //   depth: 30,
          //   // viewDistance: 10
          // }
        },
        plotOptions: {
          series: {
            animation: {
              duration: 2000,
              easing: 'easeOutBounce'
            }
          }
        },
        title: {
          text: null
        },
        xAxis: {
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
          // dashStyle: 'shortDash',
          color: "#7cb5ec",
          name:"年龄统计",
        }]
      },
      sexPieOptions : {
        chart:{
          type: 'pie',
          options3d: {
            enabled: true,
            alpha: 40,
            beta: 0
          },
          // plotBackgroundColor: null,
          // plotBorderWidth: null,
          // plotShadow: false,
        },
        tooltip: {
          headerFormat: '{性别访问数}<br>',
          pointFormat: '{point.name}: <b>{point.y} 占比{point.percentage:.1f}%</b>'
        },
        colors: ['#8bbc21', '#f28f43'],
        plotOptions: {
          pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            depth: 40,
            dataLabels: {
              enabled: true,
              format: '{point.name} {point.y} 占比{point.percentage:.1f}% '
            },
            showInLegend: true
          }
        },
        title: {
          text: null
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
        series: [{
          type: 'pie',
          name: '性别访问数',
        }]
      },
      peopleCount: [],
      type: '',
      userList: [],
      ageType: false,
      sexType: false,
      pointName:'',
      arUserId: '',
      poepleCountFlag: false,
      ageFlag: false,
      sexFlag: false,
      userSelect: '',
      projectAlias: ''
    }
  },
  created(){
    this.setting.loading = true
    this.pointName = this.$route.query.name
    this.projectSelect = this.pointName
    this.projectAlias = this.$route.query.alias
    this.allPromise()
  },
  computed:{
    'peopleCountLength': function (){
      return this.peopleCount.length
    },
    showUser(){
      let user_info = JSON.parse(localStorage.getItem('user_info'))
      let roles = user_info.roles.data[0].name
      return roles == 'user' ? false : true
    },
  },
  
  methods:{
    animation(pos) {
      // Math.easeOutBounce = function (pos) {
        if ((pos) < (1 / 2.75)) {
            return (7.5625 * pos * pos);
        }
        if (pos < (2 / 2.75)) {
            return (7.5625 * (pos -= (1.5 / 2.75)) * pos + 0.75);
        }
        if (pos < (2.5 / 2.75)) {
            return (7.5625 * (pos -= (2.25 / 2.75)) * pos + 0.9375);
        }
        return (7.5625 * (pos -= (2.625 / 2.75)) * pos + 0.984375);
      // };
    },
    searchHandle() {
      this.active = '围观人数'
      this.projectAlias = this.projectSelect
      if(/.*[\u4e00-\u9fa5]+.*$/.test(this.projectAlias)){
        this.projectAlias = this.$route.query.alias
      }
      let dateCount = (this.dateTime[1]-this.dateTime[0])/3600/1000/24
      if(dateCount > 29){
        this.$message({
          type: 'warning',
          message: '时间范围不能超过30天'
        });
      }else{
        this.setting.loading = true
        this.allPromise()
      }
    },
    resetSearch(){
      if(this.showUser){
        this.userSelect = ''
        this.arUserId = this.userSelect
        this.projectSelect = ''
      } else{
        this.projectSelect = ''
      }
      this.setting.loading = true
      this.allPromise()
    },
    projectChangeHandle() {
      this.projectAlias = this.projectSelect
      console.log(this.projectAlias)
    },
    userChangeHandle(){
      this.arUserId = this.userSelect
      this.projectSelect = ''
      if(this.arUserId) {
        this.getProject('')
      }
    },
    allPromise(){
      let peopleCount = this.getPeopleCount()
      let ageAndGender = this.getAgeAndGender()
      Promise.all([peopleCount, ageAndGender]).then(() => {
        this.setting.loading = false
      }).catch((err) => {
        console.log(err)
        this.setting.loading = false
      })
    },
    getUser(query) {
      let args = {
        name: query
      }
      if (query !== '') {
        this.projectLoading = true
          return stats.getUser(this, args).then((response) => {
            this.userList = response.data
            if(this.userList.length == 0) {
              this.projectList = []
              this.projectSelect = ''
            }
            this.projectLoading = false
          }).catch(err => {
            console.log(err)
            this.projectLoading = false
          })
      }else{
        this.userSelect = ''
        this.userList = []
        return false
      }
    },
    getProject(query) {
      let args = {
        ar_user_id: this.arUserId,
        name: query
      }
      if(this.showUser){
        this.projectLoading = true
        if(!this.arUserId){
          delete args.ar_user_id
        } 
        return stats.getProject(this,args).then((response) => {
          this.projectList = response.data
          this.projectLoading = false
        }).catch(err => {
          console.log(err)
          this.projectLoading = false
        })
        } else {
          let user_info = JSON.parse(localStorage.getItem('user_info'))
          this.arUserId = user_info.ar_user_id
          if (query !== '') {
            this.projectLoading = true
              return stats.getProject(this,args).then((response) => {
                this.projectList = response.data
                this.projectLoading = false
              }).catch(err => {
                console.log(err)
                this.projectLoading = false
            })
        }else{
          this.projectSelect = ''
          this.projectList = []
          return false
        }
      }
    },
    getPeopleCount(){
      this.poepleCountFlag = true
      let id = this.currentPointId
      let args = {}
      if((this.dateTime[1]-this.dateTime[0])/3600/1000/24<30){
        args = {
          start_date : this.handleDateTransform(this.dateTime[0]),
          end_date: this.handleDateTransform(new Date(this.dateTime[1]).getTime()),
          alias: this.projectAlias,
          ar_user_id: this.arUserId
        }
        if(!this.projectSelect) {
          delete args.alias
        }
        if(!this.arUserId) {
          delete args.ar_user_id
        }
      }else{
        this.$message({
          type: 'warning',
          message: '时间范围不能超过30天'
        });
        this.poepleCountFlag = false
        return false;
      }    
      return stats.getStaus(this,args).then((response) => {
        this.peopleCount = response
        this.type = this.peopleCount[0].alias
        this.getLineData()
      }).catch((err) => {
        this.poepleCountFlag = false
        console.log(err)
      })
    },
    getAgeAndGender() {
      this.ageFlag = true
      this.sexFlag = true
      let args = {}
      let id = this.currentPointId
      if((this.dateTime[1]-this.dateTime[0])/3600/1000/24<30){
        args = {
          start_date : this.handleDateTransform(this.dateTime[0]),
          end_date: this.handleDateTransform(new Date(this.dateTime[1]).getTime()),
          alias: this.projectAlias,
          ar_user_id: this.arUserId
        }
        if(!this.projectSelect) {
          delete args.alias
        }
        if(!this.arUserId) {
          delete args.ar_user_id
        }
      }else{
        this.$message({
          type: 'warning',
          message: '时间范围不能超过30天'
        });
        this.ageFlag = false
        this.sexFlag = false
        return false;
      }
      stats.getAgeAndGender(this, args).then((response) => {
        let ageChart = this.$refs.agePie.chart;
        let genderChat = this.$refs.sexPie.chart;
        let dataAge = []
        let dataGender = []
        // let colors = ['#7cb5ec', '#91e8e1', '#90ed7d', '#f7a35c', '#8085e9','#f15c80'];
        if(response !== '{}'){
          let ageArr = response.age
          let genderArr = response.gender
          this.ageType = false;
          this.sexFlag = false
          for(let i = 0; i < ageArr.length; i++){
            if(i==0){
              dataAge.push({'name':ageArr[i].age,'y':parseInt(ageArr[i].count)})
            }else{
              dataAge.push([ageArr[i].age,parseInt(ageArr[i].count)])
            }
          }
          dataGender.push({'name':'女','y':parseInt(genderArr.female),'sliced': true,'selected': true})
          dataGender.push({'name':'男','y':parseInt(genderArr.male)})
          ageChart.series[0].setData(dataAge,true)
          // let pointsList = ageChart.series[0].points;
          //   //遍历设置每一个数据点颜色
          //   for (let k = 0; k < pointsList.length; k++) {
          //     ageChart.series[0].points[k].update({
          //     color: {
          //       linearGradient: { x1: 0, y1: 0, x2: 1, y2: 0 }, //横向渐变效果 如果将x2和y2值交换将会变成纵向渐变效果
          //       stops: [
          //         [0, Highcharts.Color(colors[k]).setOpacity(1).get('rgba')],
          //         [0.5, 'rgb(255, 255, 255)'],
          //         [1, Highcharts.Color(colors[k]).setOpacity(1).get('rgba')]
          //         ] 
          //       }
          //     });
          //   }
          genderChat.series[0].setData(dataGender,true)
        }else{
          this.ageType = true;
          this.sexFlag = true
          ageChart.series[0].setData(dataAge,true)
          genderChat.series[0].setData(dataGender,true)
          // let pointsList = ageChart.series[0].points;
          // for (let k = 0; k < pointsList.length; k++) {
          //   ageChart.series[0].points[k].update({
          //     color: {
          //       linearGradient: { x1: 0, y1: 0, x2: 1, y2: 0 }, //横向渐变效果 如果将x2和y2值交换将会变成纵向渐变效果
          //       stops: [
          //         [0, Highcharts.Color(colors[k]).setOpacity(1).get('rgba')],
          //         [0.5, 'rgb(255, 255, 255)'],
          //         [1, Highcharts.Color(colors[k]).setOpacity(1).get('rgba')]
          //         ] 
          //       }
          //     });
          //   }
        }
        this.ageFlag = false
        this.sexFlag = false
      }).catch(err => {
        console.log(err)
        this.ageFlag = false
        this.sexFlag = false
      })
    },
    getLineData(){
      this.poepleCountFlag = true
      let args = {}
      let id = this.currentPointId
      if((this.dateTime[1]-this.dateTime[0])/3600/1000/24<30){
        args = {
          start_date : this.handleDateTransform(this.dateTime[0]),
          end_date: this.handleDateTransform(new Date(this.dateTime[1]).getTime()),
          type: this.type,
          alias: this.projectAlias,
          ar_user_id: this.arUserId
        }
        if(!this.projectSelect) {
          delete args.alias
        }
        if(!this.arUserId) {
          delete args.ar_user_id
        }
      }else{
        this.$message({
          type: 'warning',
          message: '时间范围不能超过30天'
        });
        this.poepleCountFlag = false
        return false;
      }
      stats.getDayDetail(this,args).then((response) => {
        let chart = this.$refs.lineChar.chart;
        let dataLine = []
        let dateArr = []
        let newDateArr = []
        let dateCount = (this.dateTime[1]-this.dateTime[0])/3600/1000/24 + 1
        let res = response.data
        if(res.length > 0){
          for(let j = 0; j < res.length; j++){
            dateArr.push(res[j].date)
          }
          for(let i = 0; i < dateCount; i++){
            let startDate = new Date(this.dateTime[0]).getTime()
            newDateArr = this.updateDayArr(dateArr,this.handleDateTransform(startDate + 3600 * 1000 * 24 * i),res)
          }
          
          newDateArr.sort(this.sortDate)
          for(let k = 0; k < newDateArr.length; k++){
            dataLine.push({'y':newDateArr[k].count,'name':newDateArr[k].date})
          }
         chart.series[0].setData(dataLine,true)
        }else{
          for(let a = 0; a < dateCount; a++){
            let startDate = new Date(this.dateTime[0]).getTime()
            newDateArr = this.updateDayArr(dateArr,this.handleDateTransform(startDate + 3600 * 1000 * 24 * a), res)
          }
          newDateArr.sort(this.sortDate)
          for(let h = 0; h < newDateArr.length; h++){
            dataLine.push({'y':newDateArr[h].count,'name':newDateArr[h].date})
          }
          chart.series[0].setData(dataLine,true)
        }
        this.poepleCountFlag = false
      }).catch(err => {
        console.log(err)
        this.poepleCountFlag = false
      })
    },
    lineDataHandle(obj){
      this.active = obj.name
      this.type = obj.alias
      this.getLineData()
    },
    updateDayArr(oldArr, newElement,res){
      if (oldArr.indexOf(newElement) === -1) {
        res.push({'count':0,"date":newElement})
        return res;
      } else {
        return res
      }
    },
    dateChangeHandle(){
      let dateCount = (this.dateTime[1]-this.dateTime[0])/3600/1000/24
      if(dateCount>29){
        this.$message({
          type: 'warning',
          message: '时间范围不能超过30天'
        });
      }else{
        this.setting.loading = true
        this.allPromise()
        // this.getAgeAndGender();
        // this.getPeopleCount()
      }
    },
    sortNumber(countA,countB)
    {
      return (countA.count - countB.count < 0)
    },
    sortDate(countA,countB)
    {
      return new Date(countA.date) > new Date(countB.date) ? 1 : -1;
    },
    handleDateTransform (valueDate) {
      let date = new Date (valueDate)
      let year = date.getFullYear() + '-';
      let mouth = (date.getMonth()+1 < 10 ? '0'+(date.getMonth()+1) : date.getMonth()+1) + '-';
      let day = (date.getDate() <10  ? '0'+(date.getDate()) : date.getDate()) + '';
      return year+mouth+day
    }
  }
}
</script>
<style lang="less" scoped>
  .point-data-wrapper{
    // padding: 15px;
    .headline-wrapper{
      padding: 20px;
      display: flex;
      flex-direction: row;
      justify-content: space-between;
      font-size: 16px;
      background-color: #fff;
    }
    .content-wrapper{
      padding: 15px;
      background-color: #fff;
      .btns-wrapper{
        min-height: 170px;
        padding: 10px 0;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        li{
          padding-right: 95px;
          padding-top: 20px; 
        }
        .btn{
          cursor: pointer;
          width: 145px;
          height: 145px;
          display: block;
          border-radius: 5px;
          background:url('~assets/images/program/circle.png') center 35px no-repeat #f6f6f6;
          position: relative;
          .title{
            display: block;
            height: 35px;
            line-height: 35px;
            padding-left: 20px;
            font-size: 14px;
            color: #999;
            font-weight: 600;
            font-style: normal;
          }
          .count{
            display: block;
            text-align: center;
            height: 30px;
            padding-top: 40px;
            font-size: 15px;
            color: #517ebb;
          }
          .arrow-icon{
            position: absolute;
            z-index: 2;
            top: 145px;
            left: 66px;
            display: none;
            width: 17px;
            height: 9px;
            background: url('~assets/images/program/arrow.png') 50% no-repeat;
          }
          .right-arrow-icon{
            position: absolute;
            z-index: 2;
            text-align: center;
            color: #fff;
            line-height: 34px;
            top: 63px;
            right: -90px;
            width: 82px;
            height: 34px;
            background: url('~assets/images/program/right-arrow.png') 50% no-repeat;
          }
        }
        .active,.btn:hover{
          .title{
            color: #fff;
          }
          .arrow-icon{
            display: block;
          }
          background-color: #517ebb;
        }
      }
      .chart{
        padding-top: 30px;
        width: 100%;
        .highcharts-container {
          // width: 100%;
        }
      }
    }
    .pie-content-wrapper{
      margin-top: 15px;
      .pie-sex-wrapper{
        background-color: #fff;
        width: 100%;
        .headline-wrapper{
          padding: 20px;
          display: flex;
          flex-direction: row;
          justify-content: space-between;
          font-size: 16px;
          background-color: #fff;
          color: #444;
        }
        .pie-sex-content{
          .highchart{
            .highcharts-container { overflow: hidden !important; }
          }
        }
      }
      .pie-age-wrapper{
        background-color: #fff;
        .headline-wrapper{
          padding: 20px;
          display: flex;
          flex-direction: row;
          justify-content: space-between;
          font-size: 16px;
          background-color: #fff;
          color: #444;
        }
      }
    }
  }
</style>

