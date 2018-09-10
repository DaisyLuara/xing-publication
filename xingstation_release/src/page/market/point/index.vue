<template>
  <div 
    class="root">
    <div 
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText" 
      class="item-list-wrap">
      <div 
        class="item-content-wrap">
        <!-- 搜索 -->
        <div 
          class="search-wrap">
          <el-form 
            ref="searchForm" 
            :model="searchForm" 
            :inline="true">
            <el-row 
              :gutter="20">
              <el-col
                :span="8">
                <el-form-item 
                  label=""
                  prop="name">
                  <el-input 
                    v-model="searchForm.name"
                    clearable
                    placeholder="点位名称" 
                    class="item-input"/>
                </el-form-item>
              </el-col>
              <el-col
                :span="8">
                <el-form-item 
                  label="" 
                  prop="area">
                  <el-select 
                    v-model="searchForm.area_id" 
                    placeholder="区域" 
                    filterable 
                    clearable
                    @change="areaHandle">
                    <el-option
                      v-for="item in areaList"
                      :key="item.id"
                      :label="item.name"
                      :value="item.id"/>
                  </el-select>
                </el-form-item>
              </el-col>
              <el-col
                :span="8">
                <el-form-item 
                  label="" 
                  prop="mode">
                  <el-select 
                    v-model="searchForm.site" 
                    :remote-method="getMarket"
                    :loading="searchLoading" 
                    placeholder="场地名称"
                    remote
                    filterable 
                    clearable>
                    <el-option
                      v-for="item in siteList"
                      :key="item.id"
                      :label="item.name"
                      :value="item.id"/>
                  </el-select>
                </el-form-item>
              </el-col>
              
            </el-row>
            <el-row 
              :gutter="20">
              <el-col
                :span="8">
                <el-form-item 
                  label="" 
                  prop="permission">
                  <el-select 
                    v-model="searchForm.permission" 
                    placeholder="点位权限" 
                    multiple
                    filterable 
                    clearable>
                    <el-option
                      v-for="item in permissionList"
                      :key="item.id"
                      :label="item.name"
                      :value="item.id"/>
                  </el-select>
                </el-form-item>
              </el-col>
              <el-col
                :span="8">
                <el-form-item 
                  label="" 
                  prop="mode">
                  <el-select 
                    v-model="searchForm.mode" 
                    placeholder="合作模式" 
                    filterable 
                    clearable>
                    <el-option
                      v-for="item in modeList"
                      :key="item.id"
                      :label="item.name"
                      :value="item.id"/>
                  </el-select>
                </el-form-item>
              </el-col>
              <el-col
                :span="8">
                <el-form-item 
                  label="" 
                  prop="type">
                  <el-select 
                    v-model="searchForm.type" 
                    placeholder="点位类型" 
                    filterable 
                    clearable>
                    <el-option
                      v-for="item in typeList"
                      :key="item.id"
                      :label="item.name"
                      :value="item.id"/>
                  </el-select>
                </el-form-item>
              </el-col>
            </el-row>
            <el-button 
              type="primary" 
              size="small"
              @click="search('searchForm')">搜索</el-button>
            <el-button
              type="default" 
              size="small"
              @click="resetSearch('searchForm')">重置</el-button>
          </el-form>
        </div>
        <!-- 点位列表 -->
        <div 
          class="total-wrap">
          <span 
            class="label">
            总数:{{ pagination.total }} 
          </span>
          <div>
          <el-button 
            size="small" 
            type="success"
            @click="addPoint">新建点位</el-button>
          </div>
        </div>
        <el-table 
          :data="tableData" 
          style="width: 100%" >
          <el-table-column 
            type="expand">
            <template 
              slot-scope="scope">
              <el-form 
                label-position="left" 
                inline 
                class="demo-table-expand">
                <el-form-item 
                  label="ID:">
                  <span>{{ scope.row.id }}</span> 
                </el-form-item>
                <el-form-item 
                  label="点位名称:">
                  <span>{{ scope.row.name }}</span> 
                </el-form-item>
                <el-form-item 
                  label="区域:">
                  <span>{{ scope.row.area.name }}</span> 
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="id"
            label="ID"
            width="80"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="name"
            label="点位名称"
            min-width="100"
          >
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="area"
            label="区域"
            min-width="80">
            <template slot-scope="scope">
              {{scope.row.area.name}}
            </template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="market"
            label="场地名称"
            min-width="80">
            <template slot-scope="scope">
              {{scope.row.market.name}}
            </template>
          </el-table-column>
          <el-table-column 
            label="操作" 
            min-width="100">
            <template 
              slot-scope="scope">
              <el-button 
                size="mini" 
                type="warning"
                @click="editPoint(scope.row)">编辑</el-button>
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
import market from 'service/market'
import search from 'service/search'

import {
  Button,
  Input,
  Table,
  TableColumn,
  Pagination,
  Form,
  FormItem,
  MessageBox,
  Select,
  Option,
  Row,
  Col
} from 'element-ui'

export default {
  components: {
    'el-table': Table,
    'el-table-column': TableColumn,
    'el-button': Button,
    'el-input': Input,
    'el-pagination': Pagination,
    'el-form': Form,
    'el-form-item': FormItem,
    'el-select': Select,
    'el-option': Option,
    'el-row': Row,
    'el-col': Col
  },
  data() {
    return {
      searchForm: {
        name: '',
        area_id: '',
        type: '',
        mode: '',
        permission: [],
        site: ''
      },
      modeList: [
        {
          id: 'part',
          name: '分成'
        },
        {
          id: 'exchange',
          name: '置换'
        },
        {
          id: 'none',
          name: '无要求'
        }
      ],
      siteList: [],
      permissionList: [
        {
          id: '0',
          name: '代理'
        },
        {
          id: '1',
          name: '场地主'
        },
        {
          id: '2',
          name: '广告主'
        },
        {
          id: '3',
          name: 'VIP广告主'
        }
      ],
      typeList: [
        {
          id: 'sell',
          name: '出售'
        },
        {
          id: 'lease',
          name: '租借'
        },
        {
          id: 'activity',
          name: '活动'
        },
        {
          id: 'agent',
          name: '代理'
        },
        {
          id: 'tmp',
          name: '过桥'
        },
        {
          id: 'free',
          name: '免费入驻'
        },
        {
          id: 'pay',
          name: '付费入驻'
        }
      ],
      areaList: [],
      setting: {
        loading: false,
        loadingText: '拼命加载中'
      },
      searchLoading: false,
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      tableData: []
    }
  },
  created() {
    this.getAeraList()
    this.getPointList()
  },
  methods: {
    addPoint() {
      this.$router.push({
        path: '/market/point/add'
      })
    },
    editPoint(data) {
      this.$router.push({
        path: '/market/point/edit/' + data.id
      })
    },
    areaHandle() {
      this.searchForm.site = ''
      this.getMarket(this.searchForm.site)
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
          this.siteList = response.data
          if (this.siteList.length == 0) {
            this.searchForm.site = ''
            this.siteList = []
          }
          this.searchLoading = false
        })
        .catch(err => {
          console.log(err)
          this.searchLoading = false
        })
    },
    getPointList() {
      this.setting.loading = true
      let args = {
        page: this.pagination.currentPage,
        include: 'share,contract,area,market'
      }
      market
        .getPointList(this, args)
        .then(res => {
          this.tableData = res.data
          this.pagination.total = res.meta.pagination.total
          this.setting.loading = false
        })
        .catch(err => {
          this.setting.loading = false
        })
    },
    permissionHandle(data) {
      let site = data.share.site
      let vipad = data.share.vipad
      let ad = data.share.ad
      let agent = data.share.agent
      if (site === 1) {
        return '场地主'
      }
      if (vipad === 1) {
        return 'VIP广告主'
      }
      if (ad === 1) {
        return '广告主'
      }
      if (agent === 1) {
        return '代理'
      }
    },
    getAeraList() {
      search
        .getAeraList(this)
        .then(result => {
          this.areaList = result.data
        })
        .catch(err => {
          console.log(err)
        })
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage
      this.getPointList()
    },
    search() {
      this.pagination.currentPage = 1
      this.getPointList()
    },
    resetSearch(formName) {
      this.$refs[formName].resetFields()
      this.pagination.currentPage = 1
    }
  }
}
</script>

<style lang="less" scoped>
.root {
  font-size: 14px;
  color: #5e6d82;
  .item-list-wrap {
    background: #fff;
    padding: 30px;

    .el-form-item {
      margin-bottom: 0;
    }
    .item-content-wrap {
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
      .icon-item {
        padding: 10px;
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
          margin-bottom: 10px;
        }
        .el-select {
          width: 200px;
        }
        .item-input {
          width: 200px;
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
      .total-wrap {
        margin-top: 5px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        font-size: 16px;
        align-items: center;
        margin-bottom: 10px;
        .label {
          font-size: 14px;
          margin: 5px 0;
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
