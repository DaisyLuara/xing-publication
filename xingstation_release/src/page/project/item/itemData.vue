<template>
  <div class="point-data-wrapper" v-loading="loading">
    <div class="headline-wrapper">
      <div>
        <span>节目名称：{{pointName}} </span>
        <el-select v-model="userSelected" filterable @change="pointHandle" placeholder="请选择用户(可搜索)" remote :remote-method="getUser" v-if="showUser">
          <el-option
            v-for="item in pointOptions"
            :key="item.id"
            :label="item.name"
            :value="item.name">
          </el-option>
        </el-select>
        <el-select v-model="projectSelect" filterable placeholder="请选择点位(可搜索)" remote :remote-method="getProject" @change="projectChangeHandle()">
          <el-option
            v-for="item in projectList"
            :key="item.id"
            :label="item.name"
            :value="item.alias">
          </el-option>
        </el-select>
      </div>
      <el-date-picker
      v-model="dateTime"
      type="daterange"
      start-placeholder="开始日期"
      end-placeholder="结束日期"
      :default-value="dateTime" :picker-options="pickerOptions2" @change="dateChangeHandle" :clearable="false">
      </el-date-picker>
    </div>
    <div class="content-wrapper" v-loading="poepleCountFlag">
      <ul class="btns-wrapper">
        <li v-for="(item, key) in peopleCount" :key="key">
          <a class="btn" :class="{'active': item.name == active}" @click="lineDataHandle(item)">
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
import { Row, Col, DatePicker, Select, Option} from 'element-ui'

export default {
  components:{
    'el-row': Row,
    'el-col': Col,
    'el-date-picker': DatePicker,
    'el-select': Select,
    'el-option': Option
  },
  data(){
    return {
      loading: true,
      dateTime: [new Date().getTime() - 3600 * 1000 * 24 * 6, new Date().getTime()],
      pickerOptions2: {
        disabledDate(time) {
          return time.getTime() < new Date('2017-12-31');
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
          // type: 'category'
          categories: ['2018-01-01', '2018-01-02', '2018-01-03', '2018-01-04', '2018-01-05', '2018-01-06','2018-01-07']
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
          data: [150, 73, 20,150, 73, 20,150],
          name:"数量"
        }]
      },
       agePieOptions : {
        chart:{
          type: 'column'
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
          dashStyle: 'shortDash',
          color: "#1e9f8e",
          name:"年龄统计",
          data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]
        }]
      },
      sexPieOptions : {
        chart:{
          type: 'pie',
          plotBackgroundColor: null,
          plotBorderWidth: null,
          plotShadow: false,
        },
        tooltip: {
          headerFormat: '{性别访问数}<br>',
          pointFormat: '{point.name}: <b>{point.y}</b>'
        },
        plotOptions: {
          pie: {
            allowPointSelect: true,
            cursor: 'pointer',
            dataLabels: {
              enabled: false
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
          data: [{name:'女',y: 45.0, color: '#7cb5ec'},
            {
              name: '男',
              y: 18.8,
              sliced: true,
              selected: true,
              color: '#90ed7d'
            }]
        }]
      },
      peopleCount: [],
      type: '',
      ageType: false,
      sexType: false,
      pointName:'',
      arUserId: '',
      poepleCountFlag: false,
      ageFlag: false,
      sexFlag: false,
      pointOptions: [{
        value: '选项1',
        label: '凯德'
      },{
        value: '选项1',
        label: '苏州中心'
      }],
      pointSelected: '',
      userSelected: '',
      projectAlias: ''
    }
  },
  created(){
    this.pointName = this.$route.query.name
    this.projectSelect = this.pointName
    this.projectAlias = this.$route.query.alias
    // this.getPointList()
    this.getPeopleCount()
    // this.getAgeInfo()
    // this.getGenderInfo()
    this.loading = false
  },
  computed:{
    'peopleCountLength': function (){
      return this.peopleCount.length
    },
    showUser(){
      let user_info = JSON.parse(localStorage.getItem('user_info'))
      console.log(user_info)
      let roles = user_info.roles.data[0].name
      return roles == 'user' ? false : true
    },
  },
  
  methods:{
    projectChangeHandle(){
      this.projectAlias = this.projectSelect
      console.log(this.projectSelect)

    },
    getUser() {
      return stats.getUser(this).then((response) => {
        console.log(response)
        // this.poepleCountFlag = false
       
      }).catch(err => {
        console.log(err)
        // this.poepleCountFlag = false
        
      })
    },
    getProject(query) {
      if(this.showUser){
        } else {
        let user_info = JSON.parse(localStorage.getItem('user_info'))
        this.arUserId = user_info.ar_user_id
      }
      let args = {
        ar_user_id: this.arUserId,
        name: query
      }
      if (query !== '') {
        this.projectLoading = true
        setTimeout(() => {
          return stats.getProject(this,args).then((response) => {
            this.projectList = response.data
            this.projectLoading = false
          }).catch(err => {
            console.log(err)
            this.projectLoading = false
          })
        }, 1000);
      } 
    },
    getPeopleCount(){
      this.poepleCountFlag = true
      let id = this.currentPointId
      let args = {}
      if((this.dateTime[1]-this.dateTime[0])/3600/1000/24<30){
        args = {
          start_date : this.handleDateTransform(this.dateTime[0]),
          end_date: this.handleDateTransform(new Date(this.dateTime[1]).getTime() + 3600 * 1000 * 24 * 1),
          alias: this.projectAlias
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
      // data.getCountDataInfoById(this, id, args).then((response) => {
      //   if(response.length>0){
      //     this.peopleCount = response.sort(this.sortNumber)
      //     this.type = this.peopleCount[0].type
      //     this.active = this.peopleCount[0].name
      //   }
      // }).catch(err => {
      //   console.log(err)
      //   this.poepleCountFlag = false
        
      // })
    },
    getAgeInfo(){
      this.ageFlag = true
      let args = {}
      let id = this.currentPointId
      if((this.dateTime[1]-this.dateTime[0])/3600/1000/24<30){
        args = {
          start_date : this.handleDateTransform(this.dateTime[0]),
          end_date: this.handleDateTransform(new Date(this.dateTime[1]).getTime() + 3600 * 1000 * 24 * 1)
        }
      }else{
        this.$message({
          type: 'warning',
          message: '时间范围不能超过30天'
        });
        this.ageFlag = false
        return false;
      }    
      data.getAgeInfoById(this, id, args).then((response) => {
        let chart = this.$refs.agePie.chart;
        let dataAge = []
        if(response.length>0){
          console.log(response)
          this.ageType = false;
          for(let i = 0; i < response.length; i++){
            if(i==0){
              dataAge.push({'name':response[i].age,'y':response[i].count})
            }else{
              dataAge.push([response[i].age,response[i].count])
            }
          }
        //  this.agePieOptions.series.data = dataAge
         chart.series[0].setData(dataAge,true)
        }else{
          this.ageType = true;
          chart.series[0].setData(dataAge,true)
        }
        this.ageFlag = false
      }).catch(err => {
        console.log(err)
        this.ageFlag = false
        
      })
    },
    getGenderInfo(){
      this.sexFlag = true
      let args = {}
      let id = this.currentPointId
      if((this.dateTime[1]-this.dateTime[0])/3600/1000/24<30){
        args = {
          start_date : this.handleDateTransform(this.dateTime[0]),
          end_date: this.handleDateTransform(new Date(this.dateTime[1]).getTime() + 3600 * 1000 * 24 * 1)
        }
      }else{
        this.$message({
          type: 'warning',
          message: '时间范围不能超过30天'
        });
        this.sexFlag = false
        return false;
      }
      data.getGenderInfoById(this, id, args).then((response) => {
        let chart = this.$refs.sexPie.chart;
        let dataGender = []
        if(response.length>0){
          this.sexType = false
          for(let i = 0; i < response.length; i++){
            if(i==0){
              dataGender.push({'name':response[i].gender == null ? '未知' : response[i].gender == 0 ? '女' : '男','y':response[i].count,'sliced': true,'selected': true})
            }else{
              dataGender.push([response[i].gender == null ? '未知' : response[i].gender == 0 ? '女' : '男',response[i].count])
            }
          }
        //  this.agePieOptions.series.data = dataGender
         console.log(dataGender)
         chart.series[0].setData(dataGender,true)
        }else{
          this.sexType = true
          chart.series[0].setData(dataGender,true)
        }
        this.sexFlag = false
      }).catch(err => {
        console.log(err)
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
          end_date: this.handleDateTransform(new Date(this.dateTime[1]).getTime() + 3600 * 1000 * 24 * 1),
          alias: this.type
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
        if(response.length>0){
          console.log(response)
          for(let j = 0; j < response.length; j++){
            console.log(response[j].face_count_logs)
            dateArr.push(response[j].face_count_logs)
          }
          console.log(dateArr)
          for(let i = 0; i < dateCount; i++){
            let startDate = new Date(this.dateTime[0]).getTime()
            newDateArr = this.updateDayArr(dateArr,this.handleDateTransform(startDate + 3600 * 1000 * 24 * i),response)
          }
          newDateArr.sort(this.sortDate)
          for(let k = 0; k < newDateArr.length; k++){
            dataLine.push({'y':newDateArr[k].count,'name':newDateArr[k].date})
          }
         chart.series[0].setData(dataLine,true)
        }else{
          console.log(33)
          for(let a = 0; a < dateCount; a++){
            let startDate = new Date(this.dateTime[0]).getTime()
            newDateArr = this.updateDayArr(dateArr,this.handleDateTransform(startDate + 3600 * 1000 * 24 * a),response)
          }
          console.log(newDateArr)
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
    getPointList(){
      stats.getPointList(this).then((response) => {
       console.log(response)
       this.pointOptions = response
       this.pointSelected = parseInt(this.$route.query.id)
      }).catch(err => {
        console.log(err)
      })
    },
    pointHandle(){
    //   console.log(this.pointSelected)
    // this.currentPointId = this.pointSelected
    // this.getPeopleCount()
    // this.getAgeInfo()
    // this.getGenderInfo()
    this.loading = false
    },
    lineDataHandle(obj){
      this.active = obj.name
      this.type = obj.alias
      this.getLineData()
    },
    updateDayArr(oldArr, newElement,res){
      console.log(oldArr)
      console.log(newElement)
      console.log(res)
      if (oldArr.indexOf(newElement) === -1) {
        res.push({"date":newElement,'count':0})
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
        this.getAgeInfo();
        this.getPeopleCount()
        this.getGenderInfo()
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
            font-size: 18px;
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

