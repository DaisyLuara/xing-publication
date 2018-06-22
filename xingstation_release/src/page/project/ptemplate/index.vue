<template>
  <div class="schedule-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
    <!-- 搜索 -->
    <div class="search-wrap">
      <el-form :model="searchForm" :inline="true" ref="searchForm" >
        <!-- <el-form-item label="" prop="area_id">
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
        </el-form-item> -->
        <el-form-item label="" prop="name">
          <el-input v-model="searchForm.name" placeholder="请输入模板名称" class="item-input" clearable></el-input>
        </el-form-item>
        <el-button @click="search('searchForm')" type="primary" size="small">搜索</el-button>
      </el-form>
    </div>
    <div class="actions-wrap">
      <span class="label">
        模板数量: {{pagination.total}}
      </span>
      <div>
        <el-button size="small" type="success" @click="addTemplate">新增</el-button>
      </div>
    </div>
    <!-- 模板列表 -->
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
        prop="icon"
        label="归属"
        min-width="100"
        >
      </el-table-column>
      <el-table-column
        prop="created_at"
        label="创建时间"
        min-width="150"
        :show-overflow-tooltip="true"
        >
      </el-table-column>
      <el-table-column label="操作" width="100">
        <template slot-scope="scope">
          <el-button size="small" type="warning" @click="modifyTemplate">修改</el-button>
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
      ref="templateForm"
      :model="templateForm" label-width="150px">
        <el-form-item label="区域" prop="area_id" :rules="[{ type: 'number', required: true, message: '请选择区域', trigger: 'submit' }]">
          <el-select v-model="templateForm.area_id" placeholder="请选择区域" filterable clearable @change="areaChangeHandle">
            <el-option
              v-for="item in areaList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="商场" prop="market_id" :rules="[{ type: 'number', required: true, message: '请搜索商场', trigger: 'submit' }]">
          <el-select v-model="templateForm.market_id" placeholder="请搜索商场" filterable :loading="searchLoading" remote :remote-method="getMarket"  @change="marketChangeHandle" clearable>
            <el-option
              v-for="item in marketList"
              :key="item.id"
              :label="item.name"
              :value="item.id">
            </el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="点位" prop="point_id" :rules="[{ type: 'number', required: true, message: '请选择点位', trigger: 'submit' }]">
          <el-select v-model="templateForm.point_id" placeholder="请选择点位" filterable :loading="searchLoading" clearable>
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
  Dialog,
  Select,
  Option,
  Pagination,
  Table,
  TableColumn,
  Input
} from 'element-ui'
import search from 'service/search'

export default {
  components: {
    ElPagination: Pagination,
    ElDialog: Dialog,
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
      iconList: [],
      templateForm: {
        name: '',
        icon: ''
      },
      loading: false,
      templateVisible: false,
      title: '',
      tableData: [
        {
          id: '12987122',
          name: '王者+魏巡+王者',
          icon: '星视度',
          created_at: '2018-06-10 18:03:56'
        },
        {
          id: '12987122',
          name: '王者+魏巡+王者',
          icon: '星视度',
          created_at: '2018-06-10 18:03:56'
        }
      ],
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
    modifyTemplate() {
      this.title = '修改模板'
      this.templateVisible = true
    },
    addTemplate() {
      this.title = '新增模板'
      this.templateVisible = true
    },
    dialogClose() {
      this.templateVisible = false
    },
    submit(formName) {
      this.templateVisible = false
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
    width: 200px;
  }
  .item-select {
    width: 200px;
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
