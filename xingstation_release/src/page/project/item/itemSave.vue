<template>
  <div class="item-wrap-template" :element-loading-text="setting.loadingText" v-loading="setting.loading">
    <div class="topbar">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/project/item/index' }">节目投放管理</el-breadcrumb-item>
        <el-breadcrumb-item>添加</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    <div class="pane">
      <div class="pane-title">
        新增节目投放
      </div>
      <el-form
        ref="projectForm"
        :model="projectForm" label-width="150px">
        <el-form-item label="节目名称" prop="project" :rules="[{ required: true, message: '请输入节目名称', trigger: 'submit',type: 'number'}]">
          <el-select v-model="projectForm.project" filterable placeholder="请搜索" remote :remote-method="getProject" @change="projectChangeHandle">
            <el-option
              v-for="item in projectList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="区域" prop="area"  :rules="[{ required: true, message: '请输入区域', trigger: 'submit' ,type: 'number'}]">
          <el-select v-model="projectForm.area" placeholder="请选择" filterable @change="areaChangeHandle">
            <el-option
              v-for="item in areaList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="商场" prop="market" :rules="[{ required: true, message: '请输入商场', trigger: 'submit' ,type: 'number'}]">
          <el-select v-model="projectForm.market"  placeholder="请搜索" filterable :loading="searchLoading" remote :remote-method="getMarket" @change="marketChangeHandle">
            <el-option
              v-for="item in marketList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="点位" prop="point" :rules="[{ required: true, message: '请输入点位', trigger: 'submit',type: 'array'}]">
          <el-select v-model="projectForm.point" placeholder="请选择"  multiple filterable @change="pointChangeHandle" :loading="searchLoading" :multiple-limit="10">
            <el-option
              v-for="item in pointList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="工作日模版">
          <el-select v-model="projectForm.weekday" placeholder="请选择" filterable>
            <el-option
              v-for="item in weekdayList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="周末模版">
          <el-select v-model="projectForm.weekend" placeholder="请选择" filterable>
            <el-option
              v-for="item in weekendList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="自定义模版">
          <el-select v-model="projectForm.define" placeholder="请选择" filterable>
            <el-option
              v-for="item in defineList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="投放开始时间" prop="sdate" :rules="[{ type: 'date', required: true, message: '请输入投放开始时间', trigger: 'submit' }]">
          <el-date-picker
          v-model="projectForm.sdate"
          type="date"
          placeholder="选择投放开始时间" :editable="false">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="投放结束时间" prop="edate" :rules="[{ type: 'date', required: true, message: '请输入投放结束时间', trigger: 'submit' }]">
          <el-date-picker
          v-model="projectForm.edate"
          type="date"
          placeholder="选择投放结束时间"
          :editable="false"
          >
          </el-date-picker>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="submit('projectForm')">完成</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import search from 'service/search'
import project from 'service/project'
import {
  Form,
  Select,
  Option,
  FormItem,
  Button,
  Input,
  DatePicker,
  MessageBox,
} from 'element-ui'

export default {
  components: {
    ElForm: Form,
    ElSelect: Select,
    ElOption: Option,
    ElFormItem: FormItem,
    ElButton: Button,
    ElInput: Input,
    ElDatePicker: DatePicker,
  },
  data() {
    // let edate = (rule, value, callback) => {
    //   if (value === '') {
    //       callback(new Error('请输入投放结束日期'));
    //   } else {
    //     let sdate = this.projectForm.sdate
    //     console.log(new Date(sdate).getTime() - new Date(value).getTime() > 0)
    //     if(new Date(sdate).getTime() - new Date(value).getTime() > 0){
    //       callback(new Error('投放结束日期要比投放开始日期大'));
    //     } else {
    //       callback();
    //     }
    //   }
    // }
    return {
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: "拼命加载中"
      },
      marketList: [],
      weekdayList: [],
      weekendList: [],
      defineList: [],
      pointList: [],
      projectList: [],
      searchLoading: false,
      projectForm: {
        project: '',
        area: '',
        market: '',
        point: [],
        weekday: '',
        weekend: '',
        define: '',
        sdate: '',
        edate: '',
      },
      areaList: [],
      // rules:{
      //   edate: [
      //     { validator: edate, trigger: 'submit',type: 'date', required: true},
      //   ],
      // },
    }
  },
  mounted() {
  },
  created() {
    this.setting.loading = true
    let moduleList = this.getModuleList()
    let areaList = this.getAreaList()
    Promise.all([moduleList, areaList]).then(() => {
      this.setting.loading = false
    }).catch((err) => {
      console.log(err)
      this.setting.loading = false
    })
  },
  methods: {
    projectChangeHandle() {
      console.log(this.projectForm.project)
    },
    getProject(query) {
      this.searchLoading = true
      let args = {
        name: query,
      }
      return search.getProjectList(this,args).then((response) => {
        this.projectList = response.data
        if(this.projectList.length == 0) {
          this.projectForm.project = ''
          this.projectList = []
        }
        this.searchLoading = false
      }).catch(err => {
        console.log(err)
        this.searchLoading = false
      })
    },
    getModuleList() {
      return search.getModuleList(this).then((response) => {
       let data = response.data
       this.weekdayList = data
       this.weekendList = data
       this.defineList = data
      }).catch(error => {
        console.log(error)
      this.setting.loading = false;
      })
    },
    areaChangeHandle() {
      console.log(this.projectForm.area)
      this.projectForm.market = ''
      this.getMarket(this.projectForm.market)
    },
    getAreaList () {
      return search.getAeraList(this).then((response) => {
       let data = response.data
       this.areaList = data
      }).catch(error => {
        console.log(error)
      this.setting.loading = false;
      })
    },
    marketChangeHandle() {
      console.log(this.projectForm.market)
      this.projectForm.point = []
      this.getPoint()
    },
    pointChangeHandle() {
      console.log(this.projectForm.point)
    },
    getPoint() {
      let args = {
        include: 'market',
        market_id: this.projectForm.market
      }
      this.searchLoading = true
      return search.gePointList(this, args).then((response) => {
        console.log(response)
        this.pointList = response.data
        this.searchLoading = false
      }).catch(err => {
        this.searchLoading = false
        console.log(err)
      })
    },
    getMarket(query) {
      this.searchLoading = true
      let args = {
        name: query,
        include: 'area',
        area_id: this.projectForm.area
      }
      return search.getMarketList(this,args).then((response) => {
        this.marketList = response.data
        if(this.marketList.length == 0) {
          this.projectForm.market = ''
          this.projectForm.marketList = []
        }
        this.searchLoading = false
      }).catch(err => {
        console.log(err)
        this.searchLoading = false
      })
    },
    submit(formName) {
      this.$refs[formName].validate((valid) => {
        if(valid){
        this.setting.loading = true
          let args = {
            sdate: new Date(this.projectForm.sdate).getTime() / 1000,
            edate: new Date(this.projectForm.edate).getTime() / 1000,
            default_plid: this.projectForm.project,
            weekday_tvid: this.projectForm.weekday,
            weekend_tvid: this.projectForm.weekend,
            div_tvid: this.projectForm.define,
            oids: this.projectForm.point
          }
          return project.savePorjectLaunch(this, args).then((response) => {
            this.setting.loading = false
            this.$message({
              message: "添加成功",
              type: "success"
            })
            this.$router.push({
              path: "/project/item/index"
            })
            console.log(response)
          }).catch((err) => {
            this.setting.loading = false
            console.log(err)
          })
        }else{
          console.log('error submit');
          return;
        }
      })
    },
  },
}
</script>

<style lang="less" scoped>
  .item-wrap-template {
    .pane {
      border-radius: 5px;
      background-color: white;
      padding: 20px 40px 80px;

      .el-select,.item-input,.el-date-editor.el-input{
        width: 380px;
      }
      .item-list{
        .program-title{
          color: #555;
          font-size: 14px;
        }
      }
      .pane-title {
        padding-bottom: 20px;
        font-size: 18px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
      }
    }
  }
</style>
