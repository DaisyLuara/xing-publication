<template>
  <div class="root">
    <div class="item-list-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="item-content-wrap">
        <div class="total-wrap">
          <span class="label">
            总数:{{pagination.total}} 
          </span>
        </div>
       <el-table :data="tableData" style="width: 100%" highlight-current-row>
          <el-table-column type="selection" width="55" ></el-table-column>
          <el-table-column
            prop="id"
            label="ID"
            min-width="80"
            fixed
            >
          </el-table-column>
          <el-table-column
            prop="icon"
            label="产品"
            min-width="140"
            >
            <template slot-scope="scope">
              <img :src="scope.row.point.icon" alt="" class="icon-item"/>
            </template>
          </el-table-column>
          <el-table-column
            prop="area"
            label="区域"
            width="130"
            >
          </el-table-column>
          <el-table-column
            prop="market"
            label="商场"
            width="200">
          </el-table-column>
          <el-table-column
            prop="point.name"
            label="点位"
            width="200">
          </el-table-column>
          <el-table-column
            prop="faceDate"
            label="上次互动"
            width="100">
          </el-table-column>
          <el-table-column
            prop="networkDate"
            label="联网时间"
            width="100">
          </el-table-column>
          <el-table-column
            prop="screenStatus"
            label="屏幕状态"
            width="100">
          </el-table-column>
          <el-table-column
            prop="loginDate"
            label="登录时间"
            width="150">
          </el-table-column>
          <el-table-column
            prop="on/off_time"
            label="开/关机"
            width="100">
          </el-table-column>
          <el-table-column
            prop="version"
            label="版本"
            width="120">
          </el-table-column>
          <el-table-column
            prop="system"
            label="系统"
            width="120">
          </el-table-column>
          <el-table-column
            prop="smart_strip"
            label="智能插排"
            width="100">
          </el-table-column>
          <el-table-column label="操作" width="150" fixed="right">
            <template slot-scope="scope">
               <el-button size="small" type="primary" @click="linkToEdit()">重启</el-button> 
               <el-button size="small" type="warning" @click="showData()">编辑</el-button>
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
import equipment from 'service/equipment'

import { Button, Input, Table, TableColumn, Pagination, Form, FormItem, MessageBox, DatePicker} from 'element-ui'

export default {
  data () {
    return {
      filters: {
        name: ''
      },
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
      tableData: []
    }
  },
  mounted() {
  },
  created () {
    this.gettEquipmentList();
    
  },
  methods: {
    gettEquipmentList () {
      this.setting.loadingText = "拼命加载中"
      this.setting.loading = true;
      let searchArgs = {
        page : this.pagination.currentPage,
        include: 'point.projects',
        oid: 128
        }
      equipment.gettEquipmentList(this, searchArgs).then((response) => {
       let data = response.data
       this.tableData = data
       console.log('=========');
       console.log(response);
       console.log('=========');
       this.pagination.total = response.meta.pagination.total
       this.setting.loading = false;
      }).catch(error => {
        console.log(error)
      this.setting.loading = false;
      })
    },
    
    changePage (currentPage) {
      this.pagination.currentPage = currentPage
      this.gettEquipmentList ();
    },
  },
  components: {
    "el-table": Table,
    "el-date-picker": DatePicker,
    "el-table-column":  TableColumn,
    "el-button": Button,
    "el-input": Input,
    "el-pagination": Pagination,
    "el-form": Form,
    "el-form-item": FormItem
  }
}
</script>

<style lang="less" scoped>
  .root {
    font-size: 14px;
    color: #5E6D82;
    .item-list-wrap{
      background: #fff;
      padding: 30px;
      .el-form-item{
        margin-bottom: 0;
      }
      .item-content-wrap{
        .icon-item{
          padding: 10px;
          width: 50%;
        }
        .total-wrap{
          margin-top: 5px;
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
