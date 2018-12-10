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
          class="actions-wrap">
          <span 
            class="label">
            通知数量: {{ pagination.total }}
          </span>
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
                <el-form-item 
                  label="操作名称">
                  <span>{{ scope.row.log_name }}</span>
                </el-form-item>
                <el-form-item 
                  label="操作人">
                  <span>{{ scope.row.causer.name }}</span>
                </el-form-item>
                <el-form-item 
                  label="描述">
                  <span>{{ scope.row.description }}</span>
                </el-form-item>
                <el-form-item 
                  label="创建时间">
                  <span>{{ scope.row.created_at }}</span>
                </el-form-item>
                <el-form-item 
                  label="更新时间">
                  <span>{{ scope.row.updated_at }}</span>
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="log_name"
            label="操作名称"
            min-width="130"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="name"
            label="操作人"
            min-width="100"
          >
            <template 
              slot-scope="scope">
              {{ scope.row.causer.name }}
            </template>
          </el-table-column>
          <el-table-column
            prop="description"
            label="描述"
            min-width="150"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="created_at"
            label="创建时间"
            min-width="120"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="updated_at"
            label="更新时间"
            min-width="120"
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
import { getActivitiesList } from 'service'

import {
  Button,
  Input,
  Table,
  TableColumn,
  Pagination,
  MessageBox,
  DatePicker,
  FormItem,
  Form
} from 'element-ui'

export default {
  components: {
    'el-table': Table,
    'el-date-picker': DatePicker,
    'el-table-column': TableColumn,
    'el-button': Button,
    'el-input': Input,
    'el-form-item': FormItem,
    'el-form': Form,
    'el-pagination': Pagination
  },
  data() {
    return {
      setting: {
        loadingText: '拼命加在中...',
        loading: false
      },
      tableData: [],
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      }
    }
  },
  created() {
    this.getActivitiesList()
  },
  methods: {
    getActivitiesList() {
      this.setting.loading = true
      let args = {
        include: 'causer',
        page: this.pagination.currentPage
      }
      getActivitiesList(this, args)
        .then(response => {
          this.tableData = response.data
          this.pagination.total = response.meta.pagination.total
          this.setting.loading = false
        })
        .catch(err => {
          this.setting.loading = false
          console.log(err)
        })
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage
      this.getActivitiesList()
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
