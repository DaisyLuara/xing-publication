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
            :inline="true" >
            <el-form-item 
              label="" 
              prop="coupon_batch_id">
              <el-input 
                v-model="filters.coupon_batch_id"  
                placeholder="请输入优惠券ID" 
                clearable/>
            </el-form-item>
            <el-form-item 
              label="" 
              prop="status">
              <el-select 
                v-model="filters.status" 
                placeholder="请选择优惠券状态" 
                clearable
                class="coupon-form-select">
                <el-option
                  v-for="item in statusList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"/>
              </el-select>
            </el-form-item>
            <el-button 
              type="primary" 
              size="small"
              @click="search()">搜索</el-button>
          </el-form>
        </div>
        <div 
          class="total-wrap">
          <span 
            class="label">
            总数:{{ pagination.total }} 
          </span>
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
                  label="优惠券编码">
                  <span>{{ scope.row.code }}</span> 
                </el-form-item>
                <el-form-item 
                  label="优惠券名称">
                  <span>{{ scope.row.name }}</span> 
                </el-form-item>
                <el-form-item 
                  label="优惠券状态">
                  <span v-if="scope.row.status===0">未领取</span> 
                  <span v-if="scope.row.status===1">已使用</span> 
                  <span v-if="scope.row.status===2">停用</span> 
                  <span v-if="scope.row.status===3">未使用</span> 
                </el-form-item>
                <el-form-item 
                  label="手机号">
                  <span>{{ scope.row.mobile }}</span> 
                </el-form-item>
                <el-form-item 
                  label="微信ID">
                  <span>{{ scope.row.wx_user_id }}</span> 
                </el-form-item>
                <el-form-item 
                  label="淘宝ID">
                  <span>{{ scope.row.taobao_user_id }}</span> 
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="code"
            label="优惠券编码"
            min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="name"
            label="优惠券名称"
            min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="name"
            label="名称"
            min-width="100"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="status"
            label="优惠券状态"
            min-width="100">
            <template slot-scope="scope">
              <span v-if="scope.row.status===0">未领取</span> 
              <span v-if="scope.row.status===1">已使用</span> 
              <span v-if="scope.row.status===2">停用</span> 
              <span v-if="scope.row.status===3">未使用</span> 
            </template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="mobile"
            label="手机号"
            min-width="100"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="wx_user_id"
            label="微信ID"
            min-width="100"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="taobao_user_id"
            label="淘宝ID"
            min-width="100"
          />
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
import coupon from 'service/coupon'
import {
  Button,
  Input,
  Table,
  Select,
  Option,
  TableColumn,
  Pagination,
  Form,
  FormItem,
  MessageBox
} from 'element-ui'

export default {
  components: {
    'el-table': Table,
    'el-table-column': TableColumn,
    'el-button': Button,
    'el-input': Input,
    'el-pagination': Pagination,
    'el-form': Form,
    'el-select': Select,
    'el-option': Option,
    'el-form-item': FormItem
  },
  data() {
    return {
      loading: true,
      templateVisible: false,
      filters: {
        coupon_batch_id: '',
        status: ''
      },
      setting: {
        loading: false,
        loadingText: '拼命加载中'
      },
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      statusList: [
        {
          id: 0,
          name: '未领取'
        },
        {
          id: 1,
          name: '已使用'
        },
        {
          id: 2,
          name: '停用'
        },
        {
          id: 3,
          name: '未使用'
        }
      ],
      tableData: []
    }
  },
  created() {
    this.putInCouponList()
  },
  methods: {
    putInCouponList() {
      this.setting.loading = true
      let args = {
        page: this.pagination.currentPage,
        coupon_batch_id: this.filters.coupon_batch_id,
        status: this.filters.status
      }
      if (this.filters.coupon_batch_id === '') {
        delete args.coupon_batch_id
      }
      if (this.filters.status === '') {
        delete args.status
      }
      coupon
        .putInCouponList(this, args)
        .then(response => {
          let data = response.data
          console.log(response)
          this.tableData = data
          this.pagination.total = response.meta.pagination.total
          this.setting.loading = false
        })
        .catch(error => {
          console.log(error)
          this.setting.loading = false
        })
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage
      this.putInCouponList()
    },
    search() {
      this.pagination.currentPage = 1
      this.putInCouponList()
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
          margin-bottom: 0;
        }
        .el-select {
          width: 200px;
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
