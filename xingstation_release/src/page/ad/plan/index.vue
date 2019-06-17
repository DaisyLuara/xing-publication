<template>
  <div class="root">
    <div
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText"
      class="item-list-wrap"
    >
      <div class="item-content-wrap">
        <div class="search-wrap">
          <el-form 
            ref="adSearchForm" 
            :model="adSearchForm" 
            :inline="true" 
            class="search-form">
            <el-form-item prop="type">
              <el-select
                v-model="adSearchForm.type"
                :loading="searchLoading"
                placeholder="请选择类型"
                clearable
                @change="typeAndAdTradeChangeHandle"
              >
                <el-option 
                  key="program" 
                  label="节目广告" 
                  value="program"/>
                <el-option 
                  key="ads" 
                  label="小屏广告" 
                  value="ads"/>
              </el-select>
            </el-form-item>
            <el-form-item 
              label 
              prop="adTrade">
              <el-select
                v-model="adSearchForm.ad_trade_id"
                filterable
                placeholder="请搜索广告行业"
                clearable
                @change="typeAndAdTradeChangeHandle"
              >
                <el-option
                  v-for="item in adTradeList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>

            <el-form-item 
              label 
              prop="ad_plan_id">
              <el-select
                v-model="adSearchForm.ad_plan_id"
                :loading="searchLoading"
                filterable
                placeholder="请搜索广告模版"
                clearable
              >
                <el-option
                  v-for="item in adPlanList"
                  :key="item.id"
                  :label="item.name+'('+item.type_text+')'"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>

            <el-form-item prop="type">
              <el-input 
                v-model="adSearchForm.ad_plan_name" 
                placeholder="广告模版名称"/>
            </el-form-item>
            <el-form-item>
              <el-button 
                type="primary" 
                @click="search('adSearchForm')">搜索</el-button>
              <el-button @click="resetSearch('adSearchForm')">重置</el-button>
            </el-form-item>
          </el-form>
        </div>
        <div class="actions-wrap">
          <span class="label">广告模版数量: {{ pagination.total }}</span>
          <el-button 
            size="small" 
            type="success" 
            @click="linkToAddPlan">新增广告模版</el-button>
        </div>
        <el-table 
          ref="multipleTable" 
          :data="adPlanList" 
          style="width: 100%" 
          highlight-current-row>
          <el-table-column type="expand">
            <template slot-scope="scope">
              <el-form 
                label-position="left" 
                inline 
                class="demo-table-expand">
                <el-form-item label="类型">
                  <span>{{ scope.row.type_text }}</span>
                </el-form-item>
                <el-form-item label="广告行业">
                  <span>{{ scope.row.ad_trade_name }}</span>
                </el-form-item>
                <el-form-item label="图标">
                  <a 
                    :href="scope.row.icon" 
                    target="_blank" 
                    style="color: blue">查看</a>
                </el-form-item>
                <el-form-item label="广告模版">
                  <span>{{ scope.row.name }}</span>
                </el-form-item>
                <el-form-item label="节目运行状态">
                  <span>{{ scope.row.type === 'ads' ? '--' : (scope.row.hardware ? '关闭' : '开启') }}</span>
                </el-form-item>
                <el-form-item label="播放模式">
                  <span>{{ scope.row.tmode_text }}</span>
                </el-form-item>
                <el-form-item label="创建人">
                  <span>{{ scope.row.create_user_name }}</span>
                </el-form-item>
                <el-form-item label="公司简称">
                  <span>{{ scope.row.create_user_company }}</span>
                </el-form-item>
                <el-form-item label="修改时间">
                  <span>{{ scope.row.created_at }}</span>
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column 
            prop="id" 
            label="ID" 
            min-width="50"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="type_text"
            label="类型"
            min-width="60"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="ad_trade_name"
            label="广告行业"
            min-width="80"
          />
          <el-table-column 
            label="图片" 
            min-width="50">
            <template slot-scope="scope">
              <span>
                <img 
                  :src="scope.row.icon" 
                  width="40px">
              </span>
            </template>
          </el-table-column>
          <el-table-column 
            :show-overflow-tooltip="true" 
            prop="name" 
            label="广告模版" 
            min-width="130"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="hardware"
            label="节目运行状态"
            min-width="100"
          >
            <template
              slot-scope="scope"
            >{{ scope.row.type === 'ads' ? '--' : (scope.row.hardware ? '关闭' : '开启') }}</template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="tmode_text"
            label="播放模式"
            min-width="100"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="create_user_name"
            label="创建人"
            min-width="60"
          >
            <template slot-scope="scope">
              <div>{{ scope.row.create_user_name }}</div>
              <div>{{ scope.row.create_user_company }}</div>
            </template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="created_at"
            label="修改时间"
            min-width="120"
          />
          <el-table-column 
            label="操作" 
            min-width="150">
            <template slot-scope="scope">
              <el-button 
                size="small" 
                type="warning" 
                @click="linkToEditPlan(scope.row.id)">编辑</el-button>
              <el-button 
                size="small" 
                type="default" 
                @click="linkToPlanTimeList(scope.row)">子条目</el-button>
            </template>
          </el-table-column>
        </el-table>
        <div class="pagination-wrap">
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
import { getAdPlanList, getSearchAdTrade, getSearchAdPlan } from "service";

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
} from "element-ui";

export default {
  components: {
    "el-table": Table,
    "el-table-column": TableColumn,
    "el-button": Button,
    "el-input": Input,
    "el-pagination": Pagination,
    "el-form": Form,
    "el-select": Select,
    "el-option": Option,
    "el-form-item": FormItem
  },
  data() {
    return {
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      loading: false,
      adTradeList: [],
      searchLoading: false,
      adPlanList: [],
      adSearchForm: {
        type: "",
        ad_trade_id: "",
        ad_plan_id: "",
        ad_plan_name: ""
      },
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      }
    };
  },
  created() {
    this.init();
  },
  methods: {
    async init() {
      try {
        let res = await getSearchAdTrade(this);
        this.adTradeList = res.data;
        await this.getAdPlanList();
      } catch (e) {}
    },
    typeAndAdTradeChangeHandle() {
      this.typeAndAdTradeChange();
    },
    async typeAndAdTradeChange() {
      this.adSearchForm.ad_plan_id = "";
      let args = {
        ad_trade_id: this.adSearchForm.ad_trade_id,
        type: this.adSearchForm.type
      };
      try {
        this.searchLoading = true;
        let adPlanRes = await getSearchAdPlan(this, args);
        this.adPlanList = adPlanRes.data;
        this.searchLoading = false;
      } catch (e) {
        this.searchLoading = false;
      }
    },

    getAdPlanList() {
      this.setting.loadingText = "拼命加载中";
      this.setting.loading = true;
      let searchArgs = {
        page: this.pagination.currentPage,
        type: this.adSearchForm.type,
        ad_trade_id: this.adSearchForm.ad_trade_id,
        ad_plan_name: this.adSearchForm.ad_plan_name,
        ad_plan_id: this.adSearchForm.ad_plan_id
      };
      getAdPlanList(this, searchArgs)
        .then(response => {
          this.adPlanList = response.data;
          this.pagination.total = response.meta.pagination.total;
          this.setting.loading = false;
        })
        .catch(error => {
          console.log(error);
          this.setting.loading = false;
        });
    },
    search(formName) {
      this.pagination.currentPage = 1;
      this.getAdPlanList();
    },
    resetSearch(formName) {
      this.$refs[formName].resetFields();
      this.pagination.currentPage = 1;
      this.getAdPlanList();
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage;
      this.getAdPlanList();
    },
    linkToAddPlan() {
      this.$router.push({
        path: "/ad/plan/add"
      });
    },
    linkToEditPlan(plan_id) {
      this.$router.push({
        path: "/ad/plan/edit/" + plan_id
      });
    },
    linkToPlanTimeList(data) {
      this.$router.push({
        path: "/ad/plan/" + data.id + "/plan_time",
        query: {
          name: data.name,
          tmode: data.tmode,
          type: data.type
        }
      });
    }
  }
};
</script>

<style lang="less" scoped>
.root {
  font-size: 14px;
  color: #5e6d82;
  .item-list-wrap {
    background: #fff;
    padding: 30px;
    .el-select,
    .item-input,
    .el-input {
      width: 200px;
    }

    .el-form-item {
      margin-bottom: 20px;
    }
    .el-table__body-wrapper {
      overflow-x: auto;
      overflow-y: overlay;
      position: relative;
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
    .item-content-wrap {
      .icon-item {
        padding: 10px;
        width: 50%;
      }
      .el-button {
        margin: 2px 0;
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
      .pagination-wrap {
        margin: 10px auto;
        text-align: right;
      }
    }
  }
}
</style>
