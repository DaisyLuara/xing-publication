<template>
  <div class="root">
    <div
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText"
      class="item-list-wrap"
    >
      <div class="item-content-wrap">
        <!-- 搜素条件 -->
        <div class="search-wrap">
          <el-form 
            ref="searchForm" 
            :model="searchForm" 
            :inline="true">
            <el-row :gutter="20">
              <el-col :span="8">
                <el-form-item 
                  label 
                  prop="name">
                  <el-input
                    v-model="searchForm.name"
                    clearable
                    placeholder="场地名称"
                    class="item-input"
                  />
                </el-form-item>
              </el-col>
              <el-col :span="8">
                <el-form-item 
                  label 
                  prop="area_id">
                  <el-select 
                    v-model="searchForm.area_id" 
                    placeholder="区域" 
                    filterable 
                    clearable>
                    <el-option
                      v-for="item in areaList"
                      :key="item.id"
                      :label="item.name"
                      :value="item.id"
                    />
                  </el-select>
                </el-form-item>
              </el-col>
              <el-col :span="8">
                <el-form-item 
                  label 
                  prop="type">
                  <el-select 
                    v-model="searchForm.type" 
                    placeholder="场地类型" 
                    filterable 
                    clearable>
                    <el-option
                      v-for="item in typeList"
                      :key="item.id"
                      :label="item.name"
                      :value="item.id"
                    />
                  </el-select>
                </el-form-item>
              </el-col>
            </el-row>
            <el-row :gutter="20">
              <el-col :span="8">
                <el-form-item 
                  label 
                  prop="permission">
                  <el-select
                    v-model="searchForm.permission"
                    placeholder="场地权限"
                    multiple
                    filterable
                    clearable
                  >
                    <el-option
                      v-for="item in permissionList"
                      :key="item.id"
                      :label="item.name"
                      :value="item.id"
                    />
                  </el-select>
                </el-form-item>
              </el-col>
              <el-col :span="8">
                <el-form-item 
                  label 
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
                      :value="item.id"
                    />
                  </el-select>
                </el-form-item>
              </el-col>
              <el-col :span="8">
                <el-button 
                  type="primary" 
                  size="small" 
                  @click="search('searchForm')">搜索</el-button>
                <el-button 
                  type="default" 
                  size="small" 
                  @click="resetSearch('searchForm')">重置</el-button>
              </el-col>
            </el-row>
          </el-form>
        </div>
        <!-- 场地列表 -->
        <div class="total-wrap">
          <span class="label">总数:{{ pagination.total }}</span>
          <div>
            <el-button 
              size="small" 
              type="success" 
              @click="addSite">新建场地</el-button>
          </div>
        </div>
        <el-table 
          :data="tableData" 
          style="width: 100%">
          <el-table-column type="expand">
            <template slot-scope="scope">
              <el-form 
                label-position="left" 
                inline 
                class="demo-table-expand">
                <el-form-item label="ID:">
                  <span>{{ scope.row.id }}</span>
                </el-form-item>
                <el-form-item label="场地名称:">
                  <span>{{ scope.row.name }}</span>
                </el-form-item>
                <el-form-item label="公司名称:">
                  <span>{{ (scope.row.marketConfig && scope.row.marketConfig.company) ? scope.row.marketConfig.company.name : '无' }}</span>
                </el-form-item>

                <el-form-item label="区域:">
                  <span>{{ scope.row.area.name }}</span>
                </el-form-item>
                <el-form-item label="所属人:">
                  <span>{{ (scope.row.marketConfig && scope.row.marketConfig.bdUser) ? scope.row.marketConfig.bdUser.name : '无' }}</span>
                </el-form-item>
                <el-form-item label="场地类型:">
                  <span>
                    {{ scope.row.contract ? (scope.row.contract.type === 'free' ? '免费入驻'
                    : scope.row.contract.type === 'pay'? '付费入驻'
                    : scope.row.contract.type === 'sell'? '出售'
                    : scope.row.contract.type === 'lease'? '租借'
                    : scope.row.contract.type === 'activity'? '活动'
                    : scope.row.contract.type === 'agent'? '代理'
                    : scope.row.contract.type === 'tmp'? '过桥'
                    :''):'' }}
                  </span>
                </el-form-item>
                <el-form-item label="场地权限:">
                  <span>
                    {{ scope.row.share ? ((scope.row.share.site === 0
                      && scope.row.share.vipad === 0
                      && scope.row.share.ad === 0
                    && scope.row.share.agent === 0) ? '无' : permissionHandle(scope.row)) : '' }}
                  </span>
                </el-form-item>
                <el-form-item label="合同:">
                  <span>{{ scope.row.contract ? (scope.row.contract.contract === 0 ? '无': '有'):'' }}</span>
                </el-form-item>
                <el-form-item label="合同公司:">
                  <span>{{ scope.row.contract ? scope.row.contract.contract_company : '' }}</span>
                </el-form-item>
                <el-form-item label="合同编号:">
                  <span>{{ scope.row.contract ? scope.row.contract.contract_num : '' }}</span>
                </el-form-item>
                <el-form-item label="合同联系人:">
                  <span>{{ scope.row.contract ? scope.row.contract.contract_user : '' }}</span>
                </el-form-item>
                <el-form-item label="合同联系方式:">
                  <span>{{ scope.row.contract ? scope.row.contract.contract_phone : '' }}</span>
                </el-form-item>
                <el-form-item label="合作模式:">
                  <span>
                    {{ scope.row.contract ? (scope.row.contract.mode === 'none' ? '无要求'
                    : scope.row.contract.mode === 'part'? '分成'
                    : scope.row.contract.mode === 'exchange'? '置换'
                    :''):'' }}
                  </span>
                </el-form-item>
                <el-form-item label="租金元/年:">
                  <span>{{ scope.row.contract ? (scope.row.contract.pay):'' }}</span>
                </el-form-item>
                <el-form-item label="入驻时间:">
                  <span>{{ scope.row.contract ? (scope.row.contract.enter_sdate + '~' + scope.row.contract.enter_edate) : '' }}</span>
                </el-form-item>
                <el-form-item label="运营时间:">
                  <span>{{ scope.row.contract ? (scope.row.contract.oper_sdate + '~' + scope.row.contract.oper_edate) : '' }}</span>
                </el-form-item>
                <el-form-item label="场地LOGO:">
                  <img
                    v-if="scope.row.marketConfig && scope.row.marketConfig.media"
                    :src="scope.row.marketConfig.media.url"
                    alt="logo"
                    style="width:150px;height:150px;"
                  >
                </el-form-item>
                <el-form-item label="修改时间:">
                  <span>{{ scope.row.updated_at }}</span>
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column 
            :show-overflow-tooltip="true" 
            prop="id" 
            label="ID" 
            width="80"/>
          <el-table-column 
            :show-overflow-tooltip="true" 
            prop="name" 
            label="场地名称" 
            min-width="100"/>
          <el-table-column 
            :show-overflow-tooltip="true" 
            prop 
            label="公司名称" 
            min-width="100">
            <template
              slot-scope="scope"
            >{{ (scope.row.marketConfig && scope.row.marketConfig.company) ? scope.row.marketConfig.company.name : '无' }}</template>
          </el-table-column>
          <el-table-column 
            :show-overflow-tooltip="true" 
            prop="area" 
            label="区域" 
            min-width="80">
            <template slot-scope="scope">{{ scope.row.area.name }}</template>
          </el-table-column>
          <el-table-column 
            :show-overflow-tooltip="true" 
            prop 
            label="所属人" 
            min-width="100">
            <template
              slot-scope="scope"
            >{{ (scope.row.marketConfig && scope.row.marketConfig.bdUser) ? scope.row.marketConfig.bdUser.name : '无' }}</template>
          </el-table-column>
          <el-table-column 
            label="LOGO" 
            min-width="100">
            <template slot-scope="scope">
              <img
                v-if="scope.row.marketConfig && scope.row.marketConfig.media"
                :src="scope.row.marketConfig.media.url"
                alt="logo"
                style="width:100px;height:100px;"
              >
            </template>
          </el-table-column>
          <el-table-column 
            :show-overflow-tooltip="true" 
            prop="date" 
            label="修改时间" 
            min-width="100">
            <template slot-scope="scope">{{ scope.row.updated_at }}</template>
          </el-table-column>
          <el-table-column 
            label="操作" 
            min-width="180">
            <template slot-scope="scope">
              <el-button 
                size="mini" 
                type="warning" 
                @click="editSite(scope.row)">编辑</el-button>
              <el-button 
                size="small" 
                type="primary" 
                @click="pointLink(scope.row)">点位</el-button>
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
import { getSiteMarketList, getSearchAeraList } from "service";

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
        name: "",
        area_id: "",
        type: "",
        mode: "",
        permission: []
      },
      modeList: [
        {
          id: "part",
          name: "分成"
        },
        {
          id: "exchange",
          name: "置换"
        },
        {
          id: "none",
          name: "无要求"
        }
      ],
      permissionList: [
        {
          id: "agent",
          name: "代理"
        },
        {
          id: "site",
          name: "场地主"
        },
        {
          id: "ad",
          name: "广告主"
        },
        {
          id: "vipad",
          name: "VIP广告主"
        }
      ],
      typeList: [
        {
          id: "sell",
          name: "出售"
        },
        {
          id: "lease",
          name: "租借"
        },
        {
          id: "activity",
          name: "活动"
        },
        {
          id: "agent",
          name: "代理"
        },
        {
          id: "tmp",
          name: "过桥"
        },
        {
          id: "free",
          name: "免费入驻"
        },
        {
          id: "pay",
          name: "付费入驻"
        }
      ],
      areaList: [],
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      tableData: []
    };
  },
  created() {
    this.getAeraList();
    this.getMarketList();
  },
  methods: {
    addSite() {
      this.$router.push({
        path: "/market/site/add"
      });
    },
    editSite(data) {
      this.$router.push({
        path: "/market/site/edit/" + data.id
      });
    },
    pointLink(data) {
      this.$router.push({
        path: "/market/point",
        query: {
          marketid: data.id,
          areaid: data.area.id
        }
      });
    },
    getMarketList() {
      this.setting.loading = true;
      let args = {
        page: this.pagination.currentPage,
        include:
          "share,contract,area,marketConfig.company,marketConfig.media,marketConfig.bdUser,marketConfig.adContract",
        market_name: this.searchForm.name,
        areaid: this.searchForm.area_id,
        contract_type: this.searchForm.type,
        contract_mode: this.searchForm.mode,
        share_users: this.searchForm.permission.join(",")
      };
      if (!this.searchForm.name) {
        delete args.market_name;
      }
      if (!this.searchForm.area_id) {
        delete args.areaid;
      }
      if (!this.searchForm.type) {
        delete args.contract_type;
      }
      if (!this.searchForm.mode) {
        delete args.contract_mode;
      }
      if (this.searchForm.permission.length === 0) {
        delete args.share_users;
      }
      getSiteMarketList(this, args)
        .then(res => {
          this.tableData = res.data;
          this.pagination.total = res.meta.pagination.total;
          this.setting.loading = false;
        })
        .catch(err => {
          this.setting.loading = false;
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    permissionHandle(data) {
      let site = data.share.site;
      let vipad = data.share.vipad;
      let ad = data.share.ad;
      let agent = data.share.agent;
      let permission = [];
      if (site === 1) {
        permission.push("场地主");
      }
      if (vipad === 1) {
        permission.push("VIP广告主");
      }
      if (ad === 1) {
        permission.push("广告主");
      }
      if (agent === 1) {
        permission.push("代理");
      }
      return permission.join(",");
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
      this.getMarketList();
    },
    search() {
      this.pagination.currentPage = 1;
      this.getMarketList();
    },
    resetSearch(formName) {
      this.$refs[formName].resetFields();
      this.pagination.currentPage = 1;
      this.getMarketList();
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
