<template>
  <div class="root">
    <div
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText"
      class="item-list-wrap"
    >
      <div class="item-content-wrap">
        <!-- 搜索 -->
        <div class="search-wrap">
          <el-form ref="searchForm" :model="searchForm" :inline="true">
            <el-form-item label prop="store_name">
              <el-input
                v-model="searchForm.store_name"
                clearable
                placeholder="请输入商户名称"
                class="item-input"
              />
            </el-form-item>
            <el-form-item label prop="company_name">
              <el-input
                v-model="searchForm.company_name"
                clearable
                placeholder="请输入公司名称"
                class="item-input"
              />
            </el-form-item>
            <el-form-item label prop="areaid">
              <el-select
                v-model="searchForm.areaid"
                placeholder="区域"
                filterable
                clearable
                @change="areaHandle"
              >
                <el-option
                  v-for="item in areaList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>
            <el-form-item label prop="marketid">
              <el-select
                v-model="searchForm.marketid"
                :remote-method="getMarket"
                :loading="searchLoading"
                placeholder="场地名称"
                remote
                filterable
                clearable
              >
                <el-option
                  v-for="item in marketList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>
            <el-form-item label prop="type">
              <el-select v-model="searchForm.type" placeholder="商户属性" filterable clearable>
                <el-option
                  v-for="item in typeList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>

            <el-button type="primary" size="small" @click="search('searchForm')">搜索</el-button>
            <el-button type="default" size="small" @click="resetSearch('searchForm')">重置</el-button>
          </el-form>
        </div>
        <!-- 点位列表 -->
        <div class="total-wrap">
          <span class="label">总数:{{ pagination.total }}</span>
          <div>
            <el-button size="small" type="success" @click="addBusiness">新增商户</el-button>
          </div>
        </div>
        <el-table :data="tableData" style="width: 100%">
          <el-table-column type="expand">
            <template slot-scope="scope">
              <el-form label-position="left" inline class="demo-table-expand">
                <el-form-item label="ID:">
                  <span>{{ scope.row.id }}</span>
                </el-form-item>
                <el-form-item label="商户名称:">
                  <span>{{ scope.row.name }}</span>
                </el-form-item>
                <el-form-item label="公司名称:">
                  <span>{{ scope.row.company_name }}</span>
                </el-form-item>
                <el-form-item label="所属场地:">
                  <span>{{ scope.row.market ? scope.row.market.name:'无' }}</span>
                </el-form-item>
                <el-form-item label="商户属性:">
                  <span>{{ scope.row.type===1 ?'自营':'连锁' }}</span>
                </el-form-item>
                <el-form-item label="区域:">
                  <span>{{ scope.row.area.name}}</span>
                </el-form-item>
                <el-form-item label="所属人:">
                  <span>{{ scope.row.user ? scope.row.user.name : '无' }}</span>
                </el-form-item>
                <el-form-item label="商户LOGO:">
                  <img
                    :src="scope.row.media.url"
                    v-if="scope.row.media"
                    style="width:180px;height:180px;"
                  >
                  <!-- <span>{{ scope.row.media ? scope.row.media.url : ''}}</span> -->
                </el-form-item>
                <el-form-item label="修改时间:">
                  <span>{{ scope.row.updated_at }}</span>
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column :show-overflow-tooltip="true" prop="id" label="ID" width="80"/>
          <el-table-column :show-overflow-tooltip="true" prop="name" label="商户名称" min-width="100"/>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="company_name"
            label="公司名称"
            min-width="100"
          >
            <template slot-scope="scope">{{ scope.row.company.name }}</template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="market_name"
            label="所属场地"
            min-width="100"
          >
            <template slot-scope="scope">{{ scope.row.market ? scope.row.market.name:'无' }}</template>
          </el-table-column>
          <el-table-column :show-overflow-tooltip="true" prop="type" label="商户属性" min-width="100">
            <template slot-scope="scope">{{ scope.row.type===1 ?'自营':'连锁' }}</template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="area_name"
            label="区域"
            min-width="100"
          >
            <template slot-scope="scope">{{ scope.row.area.name}}</template>
          </el-table-column>
          <el-table-column :show-overflow-tooltip="true" prop="db" label="所属人" min-width="100">
            <template slot-scope="scope">{{ scope.row.user ? scope.row.user.name : '无' }}</template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="updated_at"
            label="修改时间"
            min-width="80"
          />
          <el-table-column label="操作" min-width="100">
            <template slot-scope="scope">
              <el-button size="mini" type="warning" @click="editBusiness(scope.row)">编辑</el-button>
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
import {
  getBusinessList,
  getSearchMarketList,
  getSearchAeraList
} from "service";

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
} from "element-ui";

export default {
  components: {
    "el-table": Table,
    "el-table-column": TableColumn,
    "el-button": Button,
    "el-input": Input,
    "el-pagination": Pagination,
    "el-form": Form,
    "el-form-item": FormItem,
    "el-select": Select,
    "el-option": Option,
    "el-row": Row,
    "el-col": Col
  },
  data() {
    return {
      searchForm: {
        store_name: "",
        areaid: "",
        type: "",
        marketid: "",
        company_name: ""
      },
      marketList: [],
      typeList: [
        {
          id: 1,
          name: "自营"
        },
        {
          id: 2,
          name: "连锁"
        }
      ],
      areaList: [],
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      searchLoading: false,
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      marketid: null,
      areaid: null,
      tableData: []
    };
  },
  created() {
    this.getAeraList();
    this.getBusinessList();
  },
  methods: {
    addBusiness() {
      this.$router.push({
        path: "/market/business/add"
      });
    },
    editBusiness(data) {
      this.$router.push({
        path: "/market/business/edit/" + data.id
      });
    },
    areaHandle() {
      this.searchForm.marketid = "";
      this.getMarket(this.searchForm.marketid);
    },
    getMarket(query) {
      this.searchLoading = true;
      let args = {
        name: query,
        include: "area",
        area_id: this.searchForm.areaid
      };
      return getSearchMarketList(this, args)
        .then(response => {
          this.marketList = response.data;
          if (this.marketList.length == 0) {
            this.searchForm.marketid = "";
            this.marketList = [];
          }
          this.searchLoading = false;
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
          this.searchLoading = false;
        });
    },
    getBusinessList() {
      this.setting.loading = true;
      let args = {
        page: this.pagination.currentPage,
        include: "company,area,market,user,media",
        store_name: this.searchForm.store_name,
        marketid: this.searchForm.marketid,
        areaid: this.searchForm.areaid,
        type: this.searchForm.type,
        company_name: this.searchForm.company_name
      };
      if (this.searchForm.marketid === "") {
        delete args.marketid;
      }
      if (this.searchForm.store_name === "") {
        delete args.store_name;
      }
      if (this.searchForm.areaid === "") {
        delete args.areaid;
      }
      if (this.searchForm.type === "") {
        delete args.type;
      }
      if (!this.searchForm.company_name) {
        delete args.company_name;
      }
      getBusinessList(this, args)
        .then(res => {
          this.tableData = res.data;
          this.pagination.total = res.meta.pagination.total;
          this.setting.loading = false;
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
          this.setting.loading = false;
        });
    },
    getAeraList() {
      getSearchAeraList(this)
        .then(result => {
          this.areaList = result.data;
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage;
      this.getBusinessList();
    },
    search() {
      this.pagination.currentPage = 1;
      this.getBusinessList();
    },
    resetSearch(formName) {
      this.$refs[formName].resetFields();
      this.pagination.currentPage = 1;
      this.getBusinessList();
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
