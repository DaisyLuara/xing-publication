<template>
  <div class="schedule-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
    <!-- 搜索 -->
    <div class="search-wrap">
      <el-form :model="searchForm" :inline="true" ref="searchForm" >
        <el-form-item label="" prop="area_id">
          <el-select v-model="searchForm.area_id" placeholder="请选择区域" filterable clearable @change="areaChangeHandle">
            <el-option
              v-for="item in areaList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="" prop="market_id">
          <el-select v-model="searchForm.market_id" placeholder="请搜索商场" filterable :loading="searchLoading" remote :remote-method="getMarket"  @change="marketChangeHandle" clearable>
            <el-option
              v-for="item in marketList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="" prop="point_id">
          <el-select v-model="searchForm.point_id" placeholder="请选择点位" filterable :loading="searchLoading" clearable>
            <el-option
              v-for="item in pointList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="" prop="name">
          <el-input v-model="searchForm.name" placeholder="请输入模板名称" class="item-input" clearable></el-input>
        </el-form-item>
        <el-button @click="search('searchForm')" type="primary" size="small">搜索</el-button>
      </el-form>
    </div>
    <div class="actions-wrap">
      <span class="label">
        模板排期数量: {{pagination.total}}
      </span>
      <div>
        <el-button size="small" type="success" @click="addSchedule">新增</el-button>
      </div>
    </div>
    <!-- 模板排期列表 -->
    <el-table :data="tableData" style="width: 100%">
      <el-table-column
        prop="id"
        label="ID"
        min-width="80"
        >
      </el-table-column>
      <el-table-column
        prop="name"
        label="名称"
        min-width="150"
        :show-overflow-tooltip="true"
        >
      </el-table-column>
      <el-table-column
        prop="pname"
        label="节目名"
        min-width="100"
        >
      </el-table-column>
      <el-table-column
        prop="icon"
        label="节目图标"
        min-width="100"
        >
        <template slot-scope="scope">
          <img :src="scope.row.icon" style="width: 40%"/>
        </template>
      </el-table-column>
      <el-table-column
        prop="piont"
        label="归属"
        min-width="100"
        >
      </el-table-column>
      <el-table-column
        prop="stime"
        label="开始时间"
        min-width="100"
        >
      </el-table-column>
      <el-table-column
        prop="etime"
        label="结束时间"
        min-width="100"
        >
      </el-table-column>
      <el-table-column
        prop="time"
        label="时间"
        min-width="100"
        >
      </el-table-column>
      <el-table-column label="操作" width="100">
        <template slot-scope="scope">
          <el-button size="small" type="warning" >修改</el-button>
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
    <!-- 新增，修改 -->
    <el-dialog :title="title" :visible.sync="templateVisible" @close="dialogClose" v-loading="loading">
      <el-form
        ref="scheduleForm"
        :model="scheduleForm" label-width="150px">
        <el-form-item label="节目名称" prop="project" :rules="[{ required: true, message: '请输入节目名称', trigger: 'submit',type: 'number'}]">
          <el-select v-model="scheduleForm.project" filterable placeholder="请搜索" remote :remote-method="getProject" clearable :loading="searchLoading" class="item-select">
            <el-option
              v-for="item in projectList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="模版" :rules="[{ required: true, message: '请请选择模版', trigger: 'submit',type: 'number'}]">
          <el-select v-model="scheduleForm.template" placeholder="请选择" filterable clearable class="item-select">
            <el-option
              v-for="item in templateList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="开始时间" prop="sdate" >
          <el-time-picker
            v-model="scheduleForm.sdate"
            placeholder="请选择开始时间"
            format="HH:mm">
          </el-time-picker>
        </el-form-item>
        <el-form-item label="结束时间" prop="edate">
          <el-time-picker
            v-model="scheduleForm.edate"
            placeholder="请选择结束时间"
            format="HH:mm">
          </el-time-picker>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="submit('scheduleForm')">完成</el-button>
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
  Select,
  Option,
  Pagination,
  Table,
  TableColumn,
  Dialog,
  TimePicker,
  Input
} from 'element-ui'
import search from 'service/search'

export default {
  components: {
    ElTimePicker: TimePicker,
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
      templateVisible: false,
      loading: false,
      title: '',
      iconList: [],
      templateForm: {
        name: '',
        icon: ''
      },
      scheduleForm: {
        project: '',
        template: '',
        sdate: '',
        edate: ''
      },
      projectList: [],
      templateList: [],
      tableData: [{
        id:1,
        name:'test',
        pname: 'ptest',
        icon: 'http://o9xrbl1oc.bkt.clouddn.com/1007/image/234_istar2_icon.png',
        piont: 'test',
        stime: '8:00',
        etime: '23:00',
        time: '2018-09-09'
      }],
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      areaList: [],
      marketList: [],
      pointList: [],
      searchForm: {
        area_id: '',
        market_id: '',
        point_id: ''
      },
      setting: {
        loading: false,
        loadingText: '拼命加载中'
      },
      searchLoading: false
    }
  },
  created() {
    this.getAreaList()
  },
  methods: {
    dialogClose() {
      this.templateVisible = false
    },
    addSchedule() {
      this.title = '新增模板排期'
      this.templateVisible = true
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
            this.scheduleForm.project = ''
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
        area_id: this.searchForm.area_id
      }
      return search
        .getMarketList(this, args)
        .then(response => {
          this.marketList = response.data
          if (this.marketList.length == 0) {
            this.searchForm.market_id = ''
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
        market_id: this.searchForm.market_id
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
      this.searchForm.market_id = ''
      this.searchForm.point_id = ''
      this.getMarket()
    },
    marketChangeHandle() {
      this.searchForm.point_id = ''
      this.getPoint()
    },
    search() {},
    changePage() {}
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
