<template>
  <div class="root">
    <div class="item-list-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="item-content-wrap">
        <div class="actions-wrap">
          <span class="label">
            通知数量: {{pagination.total}}
          </span>
        </div>
        <el-table :data="tableData" style="width: 100%" ref="multipleTable">
          <el-table-column
            prop="name"
            label="名称"
            min-width="130"
            :show-overflow-tooltip="true"
            >
          </el-table-column>
          <el-table-column
            prop="desc"
            label="描述"
            min-width="150"
            >
          </el-table-column>
          <el-table-column
            prop="property"
            label="操作详情"
            min-width="150"
            :show-overflow-tooltip="true"
            >
          </el-table-column>
          <el-table-column
            prop="created_at"
            label="创建时间"
            min-width="100"
            :show-overflow-tooltip="true"
            >
          </el-table-column>
          <el-table-column
            prop="update_at"
            label="更新时间"
            min-width="100"
            :show-overflow-tooltip="true"
            >
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
import notice from 'service/notice'

import { Button, Input, Table, TableColumn, Pagination, MessageBox, DatePicker} from 'element-ui'

export default {
  data () {
    return {
      setting: {
        loadingText: '拼命加在中...',
        loading: false,
      },
      tableData: [],
      pagination: {
        total: 100,
        pageSize: 10,
        currentPage: 1
      }
    }
  },
  mounted() {
  },
  created () {
   
  },
  methods: {
    getOperateList() {
      return search.getOperateList(this).then((response) => {
        this.sceneList = response.data
      }).catch(err=> {
        console.log(err)
      })
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage
      this.getOperateList()
    }

  },
  components: {
    "el-table": Table,
    "el-date-picker": DatePicker,
    "el-table-column":  TableColumn,
    "el-button": Button,
    "el-input": Input,
    "el-pagination": Pagination
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
            width: 180px;
          }
          .item-input{
            width: 180px;
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
