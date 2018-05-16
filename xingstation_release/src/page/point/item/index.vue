<template>
  <div class="root">
    <div class="item-list-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="item-content-wrap">
        <div class="search-wrap">
          <el-form :model="filters" :inline="true" ref="searchForm" >
            <el-form-item label="" prop="name">
              <el-select v-model="filters.scene" placeholder="请选择场景" @change="sceneChangeHandle" filterable clearable>
                <el-option
                  v-for="item in sceneList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id">
                </el-option>
              </el-select>
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
            点位数量: {{pagination.total}}
          </span>
          <div>
            <el-button size="small" type="info">点位报表</el-button>
            <el-button size="small" type="success" @click="linkToAddItem">新增点位</el-button>
          </div>
        </div>
        <!-- <el-table :data="tableData" style="width: 100%" highlight-current-row  @selection-change="handleSelectionChange" ref="multipleTable" type="expand"> -->
        <el-table :data="tableData" style="width: 100%" ref="multipleTable" type="expand">
          <!-- <el-table-column type="selection" width="45" ></el-table-column> -->
          <el-table-column type="expand">
            <template slot-scope="scope">
              <el-form label-position="left" inline class="demo-table-expand">
                <el-form-item label="区域">
                  <span>{{scope.row.area}}</span>
                </el-form-item>
                <el-form-item label="商场">
                  <span>{{scope.row.market_name}}</span>
                </el-form-item>
                <el-form-item label="点位">
                  <span>{{scope.row.point_name}}</span>
                </el-form-item>
                <el-form-item label="状态">
                  <span>{{scope.row.status}}</span>
                </el-form-item>
                <el-form-item label="类型">
                  <span>{{scope.row.type}}</span>
                </el-form-item>
                <el-form-item label="图标">
                  <a :href="scope.row.icon" target="_blank" style="color: blue">查看</a>
                </el-form-item>
                <el-form-item label="创建时间">
                  <span>{{ scope.row.created_at }}</span>
                </el-form-item>
                <el-form-item label="修改时间">
                  <span>{{ scope.row.updated_at }}</span>
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
            prop="area"
            label="区域"
            min-width="100"
            :show-overflow-tooltip="true"
            >
          </el-table-column>
          <el-table-column
            prop="market_name"
            label="商场"
            min-width="100"
            :show-overflow-tooltip="true"
            >
          </el-table-column>
          <el-table-column
            prop="point_name"
            label="位置"
            min-width="100"
            :show-overflow-tooltip="true"
            >
          </el-table-column>
          <el-table-column
            prop="status"
            label="状态"
            min-width="80"
            :show-overflow-tooltip="true"
            >
          </el-table-column>
          <el-table-column
            prop="type"
            label="类型"
            min-width="100"
            :show-overflow-tooltip="true"
            >
          </el-table-column>
          <el-table-column
            prop="icon"
            label="图标"
            min-width="140"
            >
            <template slot-scope="scope">
              <img :src="scope.row.icon" alt="" class="icon-item"/>
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
              <el-button size="small" type="warning" @click="showData(scope.row, scope.row)">数据</el-button>
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
      eidtkList: [],
      filters: {
        scene: '',
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
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      sceneList: [],
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
      tvoids: [],
      tableData: [{
        id: 390,
        area: '上海',
        market_name: '光谷希尔顿',
        point_name: '宴会厅',
        status:'显示',
        type:'实体店',
        icon: '',
        created_at: '2018-05-11 15:49:49'
      }],
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
    sceneChangeHandle() {

    },
    handleSelectionChange(val) {
      this.selectAll = val
      console.log(this.selectAll.length)
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
        path: '/point/item/add'
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
