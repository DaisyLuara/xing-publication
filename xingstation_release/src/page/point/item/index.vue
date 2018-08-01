<template>
  <div 
    class="root">
    <div  
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText" 
      class="item-list-wrap">
      <div 
        class="item-content-wrap">
        <div 
          class="search-wrap">
          <el-form 
            ref="searchForm"
            :model="filters" 
            :inline="true">
            <el-form-item 
              label="" 
              prop="name">
              <el-select 
                v-model="filters.scene" 
                placeholder="请选择场景" 
                filterable 
                clearable
                @change="sceneChangeHandle" >
                <el-option
                  v-for="item in sceneList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"/>
              </el-select>
            </el-form-item>
            <el-form-item 
              label="" 
              prop="area">
              <el-select 
                v-model="filters.area" 
                placeholder="请选择区域" 
                filterable 
                clearable
                @change="areaChangeHandle" >
                <el-option
                  v-for="item in areaList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"/>
              </el-select>
            </el-form-item>
            <el-form-item 
              label="" 
              prop="market">
              <el-select 
                v-model="filters.market" 
                :remote-method="getMarket"
                :loading="marketLoading" 
                placeholder="请选择商场" 
                filterable 
                remote 
                clearable
                @change="marketChangeHandle">
                <el-option
                  v-for="item in marketList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"/>
              </el-select>
            </el-form-item>
            <el-button
              type="primary" 
              @click="search('searchForm')">搜索</el-button>
            <el-button 
              type="default"
              @click="resetSearch" >重置</el-button>
          </el-form>
        </div>
        <div 
          class="actions-wrap">
          <span 
            class="label">
            点位数量: {{ pagination.total }}
          </span>
          <div>
            <el-button 
              size="small" 
              type="info">点位报表</el-button>
            <el-button 
              size="small" 
              type="success" 
              @click="linkToAddItem">新增点位</el-button>
          </div>
        </div>
        <el-table 
          ref="multipleTable" 
          :data="tableData" 
          style="width: 100%" 
          type="expand">
          <el-table-column 
            type="expand">
            <template 
              slot-scope="scope">
              <el-form 
                label-position="left" 
                inline 
                class="demo-table-expand">
                <el-form-item label="区域">
                  <span>{{ scope.row.area }}</span>
                </el-form-item>
                <el-form-item 
                  label="商场">
                  <span>{{ scope.row.market_name }}</span>
                </el-form-item>
                <el-form-item 
                  label="点位">
                  <span>{{ scope.row.point_name }}</span>
                </el-form-item>
                <el-form-item 
                  label="状态">
                  <span>{{ scope.row.status }}</span>
                </el-form-item>
                <el-form-item 
                  label="类型">
                  <span>{{ scope.row.type }}</span>
                </el-form-item>
                <el-form-item 
                  label="图标">
                  <a 
                    :href="scope.row.icon" 
                    target="_blank" 
                    style="color: blue">查看</a>
                </el-form-item>
                <el-form-item 
                  label="创建时间">
                  <span>{{ scope.row.created_at }}</span>
                </el-form-item>
                <el-form-item 
                  label="修改时间">
                  <span>{{ scope.row.updated_at }}</span>
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column
            prop="id"
            label="ID"
            width="80"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="area"
            label="区域"
            min-width="100"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="market_name"
            label="商场"
            min-width="100"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="point_name"
            label="位置"
            min-width="100"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="status"
            label="状态"
            min-width="80"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="type"
            label="类型"
            min-width="100"
          />
          <el-table-column
            prop="icon"
            label="图标"
            min-width="140"
          >
            <template 
              slot-scope="scope">
              <img 
                :src="scope.row.icon"
                alt="" 
                class="icon-item">
            </template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="created_at"
            label="创建时间"
            min-width="150"
          />
          <el-table-column 
            label="操作" 
            width="80">
            <template 
              slot-scope="scope">
              <el-button 
                size="small" 
                type="warning" 
                @click="showData">数据</el-button>
            </template>
          </el-table-column>
        </el-table>
        <div 
          class="pagination-wrap">
          <el-pagination
            :total="pagination.total"
            :page-size="pagination.pageSize"
            :current-page="pagination.currentPage"
            layout="prev, pager, next, jumper, total"
            @current-change="changePage"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import project from 'service/project'
import search from 'service/search'

import {
  Button,
  Input,
  Table,
  TableColumn,
  Pagination,
  Dialog,
  Form,
  FormItem,
  MessageBox,
  DatePicker,
  Select,
  Option,
  CheckboxGroup,
  Checkbox
} from 'element-ui'

export default {
  components: {
    'el-table': Table,
    'el-date-picker': DatePicker,
    'el-table-column': TableColumn,
    'el-button': Button,
    'el-input': Input,
    'el-pagination': Pagination,
    'el-form': Form,
    'el-form-item': FormItem,
    'el-select': Select,
    'el-option': Option,
    'el-checkbox-group': CheckboxGroup,
    'el-checkbox': Checkbox,
    'el-dialog': Dialog
  },
  data() {
    return {
      eidtkList: [],
      filters: {
        scene: '',
        market: '',
        area: ''
      },
      editCondition: {
        conditionList: []
      },
      marketLoading: false,
      marketList: [],
      areaList: [],
      setting: {
        loading: false,
        loadingText: '拼命加载中'
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
        edate: ''
      },
      tvoids: [],
      tableData: [
        {
          id: 390,
          area: '上海',
          market_name: '光谷希尔顿',
          point_name: '宴会厅',
          status: '显示',
          type: '实体店',
          icon: '',
          created_at: '2018-05-11 15:49:49'
        }
      ],
      selectAll: []
    }
  },
  methods: {
    sceneChangeHandle() {},
    handleSelectionChange(val) {
      this.selectAll = val
      console.log(this.selectAll.length)
    },
    resetSearch() {
      this.filters.market = ''
      this.filters.area = ''
      this.filters.name = ''
      this.pagination.currentPage = 1
      this.editCondition.conditionList = []
      this.getProjectList()
    },
    projectChangeHandle() {
      console.log(this.projectForm.project)
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
            this.projectForm.project = ''
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
          this.weekdayList = data
          this.weekendList = data
          this.defineList = data
        })
        .catch(error => {
          console.log(error)
          this.setting.loading = false
        })
    },
    getProjectList() {
      this.setting.loadingText = '拼命加载中'
      this.setting.loading = true
      let searchArgs = {
        page: this.pagination.currentPage,
        include: 'point.market.area,project',
        project_name: this.filters.name,
        area_id: this.filters.area,
        market_id: this.filters.market
      }
      project
        .getProjectList(this, searchArgs)
        .then(response => {
          let data = response.data
          this.tableData = data
          this.pagination.total = response.meta.pagination.total
          //  this.$nextTick(() => {
          //   this.$refs.multipleTable.doLayout()
          // })
          this.setting.loading = false
        })
        .catch(error => {
          console.log(error)
          this.setting.loading = false
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
      return search
        .getMarketList(this, args)
        .then(response => {
          this.marketList = response.data
          if (this.marketList.length == 0) {
            this.filters.market = ''
            this.marketList = []
          }
          this.marketLoading = false
        })
        .catch(err => {
          console.log(err)
          this.marketLoading = false
        })
    },
    search(formName) {
      this.pagination.currentPage = 1
      this.editCondition.conditionList = []
      this.getProjectList()
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage
      this.editCondition.conditionList = []
      this.getProjectList()
    },
    linkToAddItem() {
      this.$router.push({
        path: '/point/item/add'
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
    linkToEdit(item) {
      let pid = item.project.id
      let pname = item.project.name
      this.$router.push({
        path: '/project/item/edit'
      })
    },
    showData() {
      const { href } = this.$router.resolve({
        path: '/point/item/data'
      })
      window.open(href, '_blank')
    }
  }
}
</script>

<style lang="less" scoped>
.root {
  font-size: 14px;
  color: #5e6d82;
  .item-list-wrap {
    .el-select,
    .item-input,
    .el-input {
      width: 380px;
    }
    background: #fff;
    padding: 30px;
    .item-content-wrap {
      .icon-item {
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
      .search-wrap {
        margin-top: 5px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        font-size: 16px;
        align-items: center;
        margin-bottom: 10px;
        .el-form-item {
          margin-bottom: 0;
        }
        .el-select {
          width: 250px;
        }
        .item-input {
          width: 230px;
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
  }
}
</style>
