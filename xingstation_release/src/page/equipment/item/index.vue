<template>
  <div class="root">
    <div class="item-list-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="item-content-wrap">
        <div class="total-wrap">
          <span class="label">
            总数:{{pagination.total}} 
          </span>
        </div>
       <el-table :data="tableData" style="width: 100%" >
         <el-table-column type="expand">
            <template slot-scope="scope">
              <el-form label-position="left" inline class="demo-table-expand">
                <el-form-item label="产品">
                  <!-- <img :src="scope.row.img" alt="" class="icon-item"/> -->
                  <a :href="scope.row.img" target="_blank" style="color: blue">查看</a>
                </el-form-item>
                <el-form-item label="区域">
                  <span>{{scope.row.area}}</span>
                </el-form-item>
                <el-form-item label="商场">
                  <span>{{scope.row.market}}</span>
                </el-form-item>
                <el-form-item label="点位">
                  <span>{{scope.row.point}}</span>
                </el-form-item>
                <el-form-item label="上次互动">
                  <span>{{scope.row.faceDate}}</span>
                </el-form-item>
                <el-form-item label="联网时间">
                  <span>{{ scope.row.networkDate }}</span>
                </el-form-item>
                <el-form-item label="屏幕状态">
                  <span>{{ scope.row.screenStatus == 0 ? '开' :'关'}}</span>
                </el-form-item>
                <el-form-item label="登录时间">
                  <span>{{ scope.row.loginDate }}</span>
                </el-form-item>
                <el-form-item label="开/关机">
                  <span>{{ scope.row.on_off }}</span>
                </el-form-item>
                <el-form-item label="智能插排">
                  <span>{{ scope.row.device_id == '' ? '': '有'}}</span>
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
            min-width="80">
          </el-table-column>
          <el-table-column
            prop="img"
            label="产品"
            min-width="150"
            :show-overflow-tooltip="true"
            >
            <template slot-scope="scope">
              <img :src="scope.row.img" alt="" class="icon-item"/>
            </template>
          </el-table-column>
          <el-table-column
            prop="market"
            label="商场"
            min-width="130"
            :show-overflow-tooltip="true">
          </el-table-column>
          <el-table-column
            prop="point"
            label="点位"
            min-width="100"
            :show-overflow-tooltip="true">
          </el-table-column>
          <el-table-column
            prop="faceDate"
            label="上次互动"
            min-width="100">
          </el-table-column>
          <el-table-column
            prop="screenStatus"
            label="屏幕状态"
            min-width="80">
            <template slot-scope="scope">
              <span v-if="scope.row.screenStatus==0">开</span>
              <span v-if="scope.row.screenStatus==1">关</span>
            </template>
          </el-table-column>
          <el-table-column
            prop="loginDate"
            label="登录时间"
            min-width="100"
            :show-overflow-tooltip="true">
          </el-table-column>
          <el-table-column
            prop="on_off"
            label="开/关机"
            width="90">
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
        }
      equipment.gettEquipmentList(this, searchArgs).then((response) => {
       let data = response.data
       this.tableData = data
       this.tableData.forEach(function (value) {
        value.on_off=value['on_time']+'-'+value['off_time']+'点';
         });
       console.log(data);
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
            margin:5px 0;
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
