<template>
  <div class="schedule-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
    <!-- 搜索 -->
    <div class="search-wrap">
      <el-form :model="searchForm" :inline="true" ref="searchForm" >
        <el-form-item label="" prop="name">
          <el-input v-model="searchForm.name" placeholder="请输入模板名称" class="item-input" clearable></el-input>
        </el-form-item>
        <el-button @click="search" type="primary" size="small">搜索</el-button>
      </el-form>
    </div>
    <div class="actions-wrap">
      <span class="label">
        数量: {{pagination.total}}
      </span>
      <!-- 模板增加 -->
      <div>
        <el-button size="small" type="success" @click="addTemplate">新增模板</el-button>
      </div>
    </div>
    <!-- 模板排期列表 -->
    <el-collapse v-model="activeNames">
      <el-collapse-item :name="index" v-for="(item, index) in tableData" :key="item.id" >
        <template slot="title">
          {{item.name}} <el-button type="primary" icon="el-icon-edit" circle size="mini" @click="modifyTemplateName"></el-button>
        </template>
        <div class="actions-wrap">
          <span class="label">
            数目: {{item.schedules.data.length}}
          </span>
          <div>
            <el-button size="small" @click="addSchedule(index)">增加</el-button>
            <el-button size="small" type="warning" @click="editSchedule(index)">修改</el-button>
          </div>
        </div>
        <el-table :data="item.schedules.data" style="width: 100%">
          <el-table-column
            prop="name"
            label="节目名称"
            min-width="150"
            >
            <template slot-scope="scope">
              <el-select v-model="scope.row.project.name" filterable placeholder="请搜索" remote :remote-method="getProject" clearable :loading="searchLoading" style="width: 180px;" @change="projectChangeHandle(index, scope.$index, scope.row.project.name)" >
                <el-option
                  v-for="item in projectList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.alias">
                </el-option>
              </el-select>
            </template>
          </el-table-column>
          <el-table-column
            prop="icon"
            label="节目图标"
            width="100"
            >
            <template slot-scope="scope">
              <img :src="scope.row.project.icon" style="width: 50%"/>
            </template>
          </el-table-column>
          <el-table-column
            prop="stime"
            label="开始时间"
            min-width="120"
            >
            <template slot-scope="scope">
              <el-time-select
                placeholder="开始时间"
                format="HH:mm"
                v-model="scope.row.date_start"
                :picker-options="{
                  start: '10:00',
                  step: '02:00',
                  end: '22:00'
                }"
                style="width: 150px">
              </el-time-select>
            </template>
          </el-table-column>
          <el-table-column
            prop="etime"
            label="结束时间"
            min-width="120"
            >
            <template slot-scope="scope">
              <el-time-select
                placeholder="结束时间"
                format="HH:mm"
                v-model="scope.row.date_end"
                :picker-options="{
                  start: '10:00',
                  step: '02:00',
                  end: '22:00',
                  minTime: scope.row.date_start
                }"
                style="width: 150px">
              </el-time-select>
            </template>
          </el-table-column>
          <el-table-column
            prop="time"
            label="时间"
            min-width="100"
            :show-overflow-tooltip="true"
            >
            <template slot-scope="scope">
              {{scope.row.project.created_at}}
            </template>
          </el-table-column>
          <el-table-column label="操作" min-width="100">
            <template slot-scope="scope">
              <el-button size="mini" type="danger" v-if="scope.row.project.icon">删除</el-button>
              <el-button size="mini" type="danger" icon="el-icon-delete" v-if="!scope.row.project.icon" @click="deleteAddSchedule(index, scope.$index, scope.row)"></el-button>
              <el-button size="mini" style="background-color: #8bc34a;border-color: #8bc34a; color: #fff;" v-if="!scope.row.project.icon">保存</el-button>
            </template>
          </el-table-column>
        </el-table> 
      </el-collapse-item>
    </el-collapse>
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
    <!-- 新增，修改 -->
    <el-dialog :title="title" :visible.sync="templateVisible" @close="dialogClose" v-loading="loading">
      <el-form
      ref="templateForm"
      :model="templateForm" label-width="150px">
        <el-form-item label="区域" prop="area_id" :rules="[{ type: 'number', required: true, message: '请选择区域', trigger: 'submit' }]">
          <el-select v-model="templateForm.area_id" placeholder="请选择区域" filterable clearable @change="areaChangeHandle" class="item-select">
            <el-option
              v-for="item in areaList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="商场" prop="market_id" :rules="[{ type: 'number', required: true, message: '请搜索商场', trigger: 'submit' }]">
          <el-select v-model="templateForm.market_id" placeholder="请搜索商场" filterable :loading="searchLoading" remote :remote-method="getMarket"  @change="marketChangeHandle" clearable class="item-select">
            <el-option
              v-for="item in marketList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="点位" prop="point_id" :rules="[{ type: 'number', required: true, message: '请选择点位', trigger: 'submit' }]">
          <el-select v-model="templateForm.point_id" placeholder="请选择点位" filterable :loading="searchLoading" clearable class="item-select">
            <el-option
              v-for="item in pointList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="模板名" :rules="[{ required: true, message: '请输入名称', trigger: 'submit' }]">
          <el-input v-model="templateForm.name" placeholder="请输入名称" class="item-input"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="submit('templateForm')" size="small">完成</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>
<script>
import {
  Form,
  FormItem,
  Button,
  Collapse,
  CollapseItem,
  Select,
  Option,
  Pagination,
  Table,
  TableColumn,
  Dialog,
  TimeSelect,
  Input
} from 'element-ui'
import search from 'service/search'
import schedule from 'service/schedule'

export default {
  components: {
    ElCollapse: Collapse,
    ElCollapseItem: CollapseItem,
    ElTimeSelect: TimeSelect,
    ElDialog: Dialog,
    ElPagination: Pagination,
    ElInput: Input,
    ElForm: Form,
    ElFormItem: FormItem,
    ElButton: Button,
    ElSelect: Select,
    ElOption: Option,
    ElTable: Table,
    ElTableColumn: TableColumn
  },
  data() {
    return {
      activeNames: ['1'],
      templateVisible: false,
      loading: false,
      title: '',
      templateList: [],
      templateForm: {
        name: '',
        area_id: '',
        market_id: '',
        point_id: ''
      },
      projectList: [],
      tableData: [],
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      areaList: [],
      marketList: [],
      pointList: [],
      searchForm: {
        name: ''
      },
      setting: {
        loading: false,
        loadingText: '拼命加载中'
      },
      searchLoading: false
    }
  },
  created() {
    this.getModuleList()
    this.getScheduleList()
  },
  methods: {
    modifyTemplateName() {
      this.templateVisible = true
      this.title = '修改模板'
    },
    projectChangeHandle(pIndex, index, val) {
      this.tableData[pIndex].schedules.data[index].project.alias = val
    },
    editSchedule(index) {
      console.log(this.tableData[index].schedules.data)
    },
    addTemplate() {
      this.templateVisible = true
      this.title = '增加模板'
    },
    deleteAddSchedule(pIndex, index, r) {
      this.tableData[pIndex].schedules.data.splice(index, 1)
    },
    getScheduleList() {
      this.setting.loading = true
      let args = {
        page: this.pagination.currentPage,
        include: 'schedules.project',
        name: this.searchForm.name
      }
      return schedule
        .getScheduleList(this, args)
        .then(response => {
          this.tableData = response.data
          this.pagination.total = response.meta.pagination.total
          this.setting.loading = false
        })
        .catch(err => {
          console.log(err)
          this.setting.loading = false
        })
    },
    addSchedule(index) {
      let td = {
        date_start: '',
        date_end: '',
        project: {
          alias: '',
          info: '',
          icon: '',
          created_at: ''
        }
      }
      this.tableData[index].schedules.data.push(td)
    },
    dialogClose() {
      this.templateVisible = false
    },
    getProject(query) {
      this.searchLoading = true
      let args = {
        name: query
      }
      return search
        .getProjectList(this, args)
        .then(response => {
          this.projectList = response.data
          if (this.projectList.length == 0) {
            // this.scheduleForm.project = ''
            this.projectList = []
          }
          this.searchLoading = false
        })
        .catch(err => {
          console.log(err)
          this.searchLoading = false
        })
    },
    getModuleList() {
      return search
        .getModuleList(this)
        .then(response => {
          let data = response.data
          this.templateList = data
        })
        .catch(error => {
          console.log(error)
          this.setting.loading = false
        })
    },
    getAreaList() {
      return search
        .getAeraList(this)
        .then(response => {
          let data = response.data
          this.areaList = data
        })
        .catch(error => {
          console.log(error)
          this.setting.loading = false
        })
    },
    getMarket(query) {
      this.searchLoading = true
      let args = {
        name: query,
        include: 'area',
        area_id: this.templateForm.area_id
      }
      return search
        .getMarketList(this, args)
        .then(response => {
          this.marketList = response.data
          if (this.marketList.length == 0) {
            this.templateForm.market_id = ''
            this.marketList = []
          }
          this.searchLoading = false
        })
        .catch(err => {
          console.log(err)
          this.searchLoading = false
        })
    },
    getPoint() {
      let args = {
        include: 'market',
        market_id: this.templateForm.market_id
      }
      this.searchLoading = true
      return search
        .gePointList(this, args)
        .then(response => {
          this.pointList = response.data
          this.searchLoading = false
        })
        .catch(err => {
          this.searchLoading = false
          console.log(err)
        })
    },
    areaChangeHandle() {
      this.templateForm.market_id = ''
      this.templateForm.point_id = ''
      this.getMarket()
    },
    marketChangeHandle() {
      this.templateForm.point_id = ''
      this.getPoint()
    },
    search() {
      this.pagination.currentPage = 1
      this.getScheduleList()
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage
      this.getScheduleList()
    }
  }
}
</script>
<style lang="less" scoped>
.schedule-wrap {
  background: #fff;
  padding: 30px;
  .search-wrap {
    margin-top: 5px;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    font-size: 16px;
    align-items: center;
    margin-bottom: 10px;
    .el-form-item {
      margin-bottom: 10px;
    }
    .el-select {
      width: 180px;
    }
    .item-input {
      width: 180px;
    }
    .warning {
      background: #ebf1fd;
      padding: 8px;
      margin-left: 10px;
      color: #444;
      font-size: 12px;
      i {
        color: #4a8cf3;
        margin-right: 5px;
      }
    }
  }
  .item-input {
    width: 220px;
  }
  .item-select {
    width: 220px;
  }
  .el-button.is-circle {
    padding: 5px;
  }
  .actions-wrap {
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
  .pagination-wrap {
    margin: 10px auto;
    text-align: right;
  }
}
</style>
