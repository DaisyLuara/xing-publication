<template>
  <div
    v-loading="setting.loading"
    :element-loading-text="setting.loadingText"
    class="page-list-template"
  >
    <div class="search-wrap">
      <el-form 
        ref="searchForm" 
        :inline="true" 
        :model="searchForm" 
        class="search-form">
        <el-form-item 
          label 
          prop="title">
          <el-input 
            v-model="searchForm.mobile"
            placeholder="输入手机号"
            max-length="11"
            clearable/>
        </el-form-item>
        <el-form-item>
          <el-button 
            type="primary" 
            size="small" 
            @click="search">搜索</el-button>
        </el-form-item>
      </el-form>
    </div>
    <div class="actions-wrap">
      <span class="label">数量: {{ total }}</span>
    </div>
    <div class="table-area">
      <el-table 
        :data="tableData" 
        style="width: 100%">
        <el-table-column 
          prop="username"
          label="用户名"
          min-width="100"/>
        <el-table-column 
          prop="mobile"
          label="手机号"
          min-width="100"/>
        <el-table-column 
          prop="group_name"
          label="等级"
          min-width="100"/>
        <el-table-column
          label="等级图标"
          min-width="150">
          <template slot-scope="scope">
            <img 
              :src="scope.row.group_icon" 
              width="60px;">
          </template>
        </el-table-column>
        <el-table-column 
          prop="p_credits"
          label="积分"
          min-width="100"/>
        <el-table-column
          prop="p_rep"
          label="信誉"
          min-width="100"/>
        <el-table-column
          prop="p_rmb"
          label="充值"
          min-width="100"/>
        <el-table-column
          prop="p_hd"
          label="嗨豆"
          min-width="100"/>
      </el-table>
    </div>
    <div class="pagination">
      <el-pagination
        :current-page="currentPage"
        :page-size="pageSize"
        :total="total"
        layout="total, prev, pager, next, jumper"
        @current-change="currentChange"
      />
    </div>
  </div>
</template>
<script>
import { getCreditListData } from "service";
import {
  Input,
  Button,
  FormItem,
  Form,
  Table,
  TableColumn,
  Pagination
} from "element-ui";
export default {
  components: {
    "el-input": Input,
    "el-button": Button,
    "el-form-item": FormItem,
    "el-form": Form,
    "el-table": Table,
    "el-table-column": TableColumn,
    "el-pagination": Pagination
  },
  data() {
    return {
      searchForm: {
        mobile: null
      },
      currentPage: 1,
      pageSize: 10,
      total: null,
      tableData: [],
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      }
    };
  },
  created() {
    this.getCreditList();
  },
  methods: {
    search() {
      this.currentPage = 1;
      this.getCreditList();
    },
    currentChange(e) {
      this.currentPage = e;
      this.getCreditList();
    },
    getCreditList() {
      this.setting.loading = true;
      let args = {
        page: this.currentPage,
        mobile:this.searchForm.mobile
      };
      if(this.searchForm.title===''){
        delete args.title
      }
      getCreditListData(this, args)
        .then(response => {
          this.tableData = response.data;
          this.total = response.meta.pagination.total;
          this.setting.loading = false;
        })
        .catch(err => {
          this.setting.loading = false;
        });
    },

  }
};
</script>

<style lang="less" scoped>
.page-list-template {
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
    .el-input {
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
  .actions-wrap {
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
  .copy-link {
    color: #03a9f4;
  }
}
</style>

