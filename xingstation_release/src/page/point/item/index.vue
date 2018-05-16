<template>
  <div class="root">
    <div class="item-list-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="item-content-wrap">
        <div class="search-wrap">
          <el-form :model="filters" :inline="true" ref="searchForm" >
            <el-form-item label="" prop="name">
              <el-input v-model="filters.name" placeholder="请输入节目名称" style="width: 250px;"></el-input>
            </el-form-item>
            <el-form-item label="" prop="area">
              <el-select v-model="filters.area" placeholder="请选择区域" @change="areaChangeHandle" filterable clearable>
                <el-option
                  v-for="item in areaList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id">
                </el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="" prop="market">
              <el-select v-model="filters.market" placeholder="请选择商场" filterable :loading="marketLoading" remote :remote-method="getMarket" @change="marketChangeHandle" clearable>
                <el-option
                  v-for="item in marketList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id">
                </el-option>
              </el-select>
            </el-form-item>
            <el-button @click="search('searchForm')" type="primary">搜索</el-button>
            <el-button @click="resetSearch" type="default">重置</el-button>
          </el-form>
        </div>
        <div class="actions-wrap">
          <span class="label">
            节目数量: {{pagination.total}}
          </span>
          <div>
            <el-button size="small" type="info">节目报表</el-button>
            <el-button size="small" type="success" @click="linkToAddItem">投放节目</el-button>
          </div>
        </div>
        <el-table :data="tableData" style="width: 100%" highlight-current-row  @selection-change="handleSelectionChange" ref="multipleTable" type="expand">
          <el-table-column type="selection" width="45" ></el-table-column>
          <el-table-column type="expand">
            <template slot-scope="scope">
              <el-form label-position="left" inline class="demo-table-expand">
                <el-form-item label="节目名称">
                  <span>{{scope.row.project.name}}</span>
                </el-form-item>
                <el-form-item label="节目icon">
                  <a :href="scope.row.project.icon" target="_blank" style="color: blue">查看</a>
                </el-form-item>
                <el-form-item label="区域">
                  <span>{{scope.row.point.market.area.name}}</span>
                </el-form-item>
                <el-form-item label="商场">
                  <span>{{scope.row.point.market.name}}</span>
                </el-form-item>
                <el-form-item label="点位">
                  <span>{{scope.row.point.name}}</span>
                </el-form-item>
                <el-form-item label="创建时间">
                  <span>{{ scope.row.created_at }}</span>
                </el-form-item>
                <el-form-item label="修改时间">
                  <span>{{ scope.row.updated_at }}</span>
                </el-form-item>
                <el-form-item label="自定义开始时间">
                  <span>{{ scope.row.start_date }}</span>
                </el-form-item>
                <el-form-item label="自定义结束时间">
                  <span>{{ scope.row.end_date }}</span>
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column
            prop="id"
            label="ID"
            width="80"
            >
          </el-table-column>
          <el-table-column
            prop="name"
            label="节目名称"
            min-width="150"
            :show-overflow-tooltip="true"
            >
            <template slot-scope="scope">
              {{scope.row.project.name}}
            </template>
          </el-table-column>
          <el-table-column
            prop="icon"
            label="节目icon"
            min-width="100"
            >
            <template slot-scope="scope">
              <img :src="scope.row.project.icon" alt="" class="icon-item"/>
            </template>
          </el-table-column>
          <el-table-column
            prop="market_name"
            label="商场"
            min-width="100"
            :show-overflow-tooltip="true"
            >
            <template slot-scope="scope">
              {{scope.row.point.market.name}}
            </template>
          </el-table-column>
          <el-table-column
            prop="point_name"
            label="点位"
            min-width="100"
            :show-overflow-tooltip="true"
            >
            <template slot-scope="scope">
              {{scope.row.point.name}}
            </template>
          </el-table-column>
          <el-table-column
            prop="created_at"
            label="创建时间"
            min-width="150"
            :show-overflow-tooltip="true"
            >
          </el-table-column>
          <el-table-column label="操作" width="80">
            <template slot-scope="scope">
              <el-button size="small" type="warning" @click="showData(scope.row.project.alias, scope.row.project.name, arUserName)" v-if="dataShowFlag">数据</el-button>
            </template>
          </el-table-column>
        </el-table>
        <div class="pagination-wrap">
          <el-pagination
          layout="prev, pager, next, jumper, total"
          :total="pagination.total"
          :page-size="pagination.pageSize"
          :current-page="pagination.currentPage"
          @current-change="changePage"
          >
          </el-pagination>
        </div>
      </div>
      <el-dialog title="批量修改" :visible.sync="editVisible" @close="dialogClose" v-loading="loading">
        <el-form
        ref="projectForm"
        :model="projectForm" label-width="150px">
          <el-form-item label="节目名称" prop="project"  v-if="modifyOptionFlag.project" :rules="[{ type: 'number', required: true, message: '请输入节目', trigger: 'submit' }]">
            <el-select v-model="projectForm.project" filterable placeholder="请搜索" remote :remote-method="getProject" @change="projectChangeHandle" clearable>
              <el-option
                v-for="item in projectList"
                :key="item.id"
                :label="item.name"
                :value="item.id">
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="工作日模版" prop="weekday" v-if="modifyOptionFlag.weekday" :rules="[{ type: 'number', required: true, message: '请选择工作日模版', trigger: 'submit' }]">
            <el-select v-model="projectForm.weekday" placeholder="请选择" filterable clearable>
              <el-option
                v-for="item in weekdayList"
                :key="item.id"
                :label="item.name"
                :value="item.id">
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="周末模版" prop="weekend" v-if="modifyOptionFlag.weekend" :rules="[{ type: 'number', required: true, message: '请选择周末模版', trigger: 'submit' }]">
            <el-select v-model="projectForm.weekend" placeholder="请选择" filterable clearable>
              <el-option
                v-for="item in weekendList"
                :key="item.id"
                :label="item.name"
                :value="item.id">
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="自定义模版" prop="define" v-if="modifyOptionFlag.define" :rules="[{ required: true, message: '请选择自定义模版', trigger: 'submit',type: 'number' }]">
            <el-select v-model="projectForm.define" placeholder="请选择" filterable clearable>
              <el-option
                v-for="item in defineList"
                :key="item.id"
                :label="item.name"
                :value="item.id">
              </el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="自定义开始时间" prop="sdate" v-if="modifyOptionFlag.sdate" :rules="[{ type: 'date', required: true, message: '请输入自定义开始时间', trigger: 'submit' }]">
            <el-date-picker
            v-model="projectForm.sdate"
            type="date"
            placeholder="选择自定义开始时间" :editable="false">
            </el-date-picker>
          </el-form-item>
          <el-form-item label="自定义结束时间" prop="edate" v-if="modifyOptionFlag.edate" :rules="[{ type: 'date', required: true, message: '请输入自定义结束时间', trigger: 'submit' }]">
            <el-date-picker
            v-model="projectForm.edate"
            type="date"
            placeholder="选择自定义结束时间"
            :editable="false"
            >
            </el-date-picker>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="submitModify('projectForm')">完成</el-button>
          </el-form-item>
         </el-form>
      </el-dialog>
    </div>
  </div>
</template>

<script>
import project from 'service/project'
import search from 'service/search'

import { Button, Input, Table, TableColumn, Pagination,Dialog, Form, FormItem, MessageBox, DatePicker, Select, Option, CheckboxGroup, Checkbox} from 'element-ui'

export default {
  data () {
    return {
      editVisible: false,
      eidtkList: [],
      filters: {
        name: '',
        market: '',
        area: ''
      },
      editCondition:{
        conditionList: [],
      },
      marketLoading: false,
      marketList: [],
      areaList: [],
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      dataValue: '',
      loading: true,
      arUserName: '',
      dataShowFlag: true,
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      weekdayList: [],
      weekendList: [],
      defineList: [],
      projectList: [],
      searchLoading: false,
      projectForm: {
        project: '',
        weekday: '',
        weekend: '',
        define: '',
        sdate: '',
        edate: '',
      },
      loading: true,
      modifyOptionFlag: {
        project: false,
        weekday: false,
        weekend: false,
        define: false,
        sdate: false,
        edate: false,
      },
      tvoids: [],
      tableData: [],
      selectAll: [],
    }
  },
  mounted() {
  },
  created () {
    // this.getProjectList()
    // this.getAreaList()
    // let user_info = JSON.parse(localStorage.getItem('user_info'))
    // this.arUserName = user_info.name
    // this.dataShowFlag = user_info.roles.data[0].name === 'legal-affairs' ? false : true
  },
  methods: {
    dialogClose() {
      if(!this.editVisible) {
        this.editCondition.conditionList = []
        this.$refs.multipleTable.clearSelection();
      }
    },
    handleSelectionChange(val) {
      this.selectAll = val
      console.log(this.selectAll.length)
    },
    modifyEdit() {
      if(this.selectAll.length == 0 ){
        this.$message({
          message: "请选择节目",
          type: "warning"
        })
      }else{
        if(this.editCondition.conditionList.length == 0) {
            this.$message({
            message: "请选择修改项目",
            type: "warning"
          })
        } else{
          this.getModuleList()
          // this.$refs[projectForm].resetFields();
          this.projectForm = {
            project: '',
            weekday: '',
            weekend: '',
            define: '',
            sdate: '',
            edate: '',
          }
          this.tvoids = []
          let optionModify = this.editCondition.conditionList
          for (let i = 0; i < this.selectAll.length; i++) {
            let id = this.selectAll[i].id
            this.tvoids.push(id)
          }
          this.modifyOptionFlag.project = false
          this.modifyOptionFlag.weekend = false
          this.modifyOptionFlag.weekday = false
          this.modifyOptionFlag.sdate = false
          this.modifyOptionFlag.edate = false
          this.modifyOptionFlag.define = false
          for (let k = 0; k < optionModify.length; k++) {
            let type = optionModify[k]
            switch(type) {
              case '节目名称':
                this.modifyOptionFlag.project = true
              break
              case '周末模版':
                this.modifyOptionFlag.weekend= true
              break
              case '工作日模版':
                this.modifyOptionFlag.weekday = true
              break
              case '自定义开始时间':
                this.modifyOptionFlag.sdate = true
              break
              case '自定义结束时间':
                this.modifyOptionFlag.edate = true
              break
              case '自定义模版':
                this.modifyOptionFlag.define = true
              break
            }
          }
          this.editVisible = true
        }
      }
    },
    resetSearch () {
      this.filters.market = ''
      this.filters.area = ''
      this.filters.name = ''
      this.pagination.currentPage = 1;
      this.editCondition.conditionList = []
      this.getProjectList();
    },
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
    submitModify(formName) {
      this.$refs[formName].validate((valid) => {
        if(valid){
          let edate = (new Date(this.projectForm.edate).getTime() + ((((23*60+59)*60)+59)*1000)) / 1000
          this.loading = true
          let args = {
            tvoids: this.tvoids,
            default_plid: this.projectForm.project,
            sdate: new Date(this.projectForm.sdate).getTime() / 1000,
            edate: edate,
            weekday_tvid: this.projectForm.weekday,
            weekend_tvid: this.projectForm.weekend,
            div_tvid: this.projectForm.define,
          }
          this.modifyOptionFlag.project ? args : delete args.default_plid 
          this.modifyOptionFlag.weekday ? args : delete args.weekday_tvid 
          this.modifyOptionFlag.weekend ? args : delete args.weekend_tvid 
          this.modifyOptionFlag.define ? args : delete args.div_tvid 
          this.modifyOptionFlag.sdate ? args : delete args.sdate 
          this.modifyOptionFlag.edate ? args : delete args.edate 
          console.log(args)
          this.loading = false
          return project.modifyProjectLaunch(this, args).then((response) => {
            this.setting.loading = false
            this.$message({
              message: "修改成功",
              type: "success"
            })
            this.getProjectList();
            this.editVisible = false
            this.editCondition.conditionList = []
            console.log(response)
          }).catch((err) => {
            this.loading = false
            console.log(err)
          })
        }else{
          this.loading = false
          console.log('error submit');
          return;
        }
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
    getProjectList () {
      this.setting.loadingText = "拼命加载中"
      this.setting.loading = true;
      let searchArgs = {
        page : this.pagination.currentPage,
        include: 'point.market.area,project',
        project_name: this.filters.name,
        area_id: this.filters.area,
        market_id: this.filters.market
      }
      project.getProjectList(this, searchArgs).then((response) => {
       let data = response.data
       this.tableData = data
       this.pagination.total = response.meta.pagination.total
      //  this.$nextTick(() => {
      //   this.$refs.multipleTable.doLayout()
      // })
      this.setting.loading = false;
      }).catch(error => {
        console.log(error)
      this.setting.loading = false;
      })
    },
    marketChangeHandle() {
      console.log(this.filters.market)
    },
    areaChangeHandle() {
      this.filters.market = ''
      this.getMarket(this.filters.market)
    },
    getMarket(query) {
      this.marketLoading = true
      let args = {
        name: query,
        include: 'area',
        area_id: this.filters.area
      }
      return search.getMarketList(this,args).then((response) => {
        this.marketList = response.data
        if(this.marketList.length == 0) {
          this.filters.market = ''
          this.marketList = []
        }
        this.marketLoading = false
      }).catch(err => {
        console.log(err)
        this.marketLoading = false
      })
    },
    search (formName) {
      this.pagination.currentPage = 1;
      this.editCondition.conditionList = []
      this.getProjectList();
    },
    changePage (currentPage) {
      this.pagination.currentPage = currentPage
      this.editCondition.conditionList = []
      this.getProjectList()
    },
    linkToAddItem () {
      this.$router.push({
        path: '/project/item/add'
      })
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
    linkToEdit (item) {
      let pid = item.project.id
      let pname = item.project.name
      this.$router.push({
        path: '/project/item/edit',
      })
    },
    showData (alias,name,userId) {
      const { href } = this.$router.resolve({
        path: '/project/item/data',
        query: {
          alias: alias,
          name: name,
          uName: userId
        }
      })
      window.open(href, '_blank')
    }
  },
  components: {
    "el-table": Table,
    "el-date-picker": DatePicker,
    "el-table-column":  TableColumn,
    "el-button": Button,
    "el-input": Input,
    "el-pagination": Pagination,
    "el-form": Form,
    "el-form-item": FormItem,
    'el-select': Select,
    'el-option': Option,
    'el-checkbox-group': CheckboxGroup,
    'el-checkbox': Checkbox,
    'el-dialog':Dialog
  }
}
</script>

<style lang="less" scoped>
  .root {
    font-size: 14px;
    color: #5E6D82;
    .item-list-wrap{
      .el-select,.item-input,.el-input{
        width: 380px;
      }
      background: #fff;
      padding: 30px;
      .item-content-wrap{
        .icon-item{
          padding: 10px;
          width: 60%;
        }
        .demo-table-expand {
          font-size: 0;
        }
        .demo-table-expand label {
          width: 90px;
          color: #99a9bf;
        }
        .demo-table-expand .el-form-item {
          margin-right: 0;
          margin-bottom: 0;
          width: 50%;
        }
        .search-wrap{
          margin-top: 5px;
          display: flex;
          flex-direction: row;
          justify-content: space-between;
          font-size: 16px;
          align-items: center;
          margin-bottom: 10px;
          .el-form-item{
            margin-bottom: 0;
          }
          .el-select{
            width: 250px;
          }
          .item-input{
            width: 230px;
          }
          .warning{
            background: #ebf1fd;
            padding: 8px;
            margin-left: 10px;
            color: #444;
            font-size: 12px;
            i{
              color: #4a8cf3;
              margin-right: 5px;
            }
          }
        }
        .actions-wrap{
          display: flex;
          flex-direction: row;
          justify-content: space-between;
          font-size: 16px;
          align-items: center;
          margin-bottom: 10px;
          .label {
            font-size: 14px;
          }
        }
        .pagination-wrap{
          margin: 10px auto;
          text-align: right;
        }
      }
    }
  }
</style>
