<template>
  <div class="root">
    <div
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText"
      class="item-list-wrap"
    >
      <div class="item-content-wrap">
        <div class="search-wrap">
          <el-form ref="filters" :model="filters" :inline="true">
            <el-form-item label prop="aid">
              <el-select
                v-model="filters.aid"
                filterable
                :loading="searchLoading"
                clearable
                placeholder="请选择玩法配置"
              >
                <el-option
                  v-for="item in deployList"
                  :key="item.aid"
                  :label="item.aid +':'+item.name"
                  :value="item.aid"
                />
              </el-select>
            </el-form-item>
            <el-form-item label prop="pass">
              <el-select v-model="filters.pass" clearable placeholder="请选择状态">
                <el-option
                  v-for="item in statusList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>
            <el-button type="primary" size="small" @click="search()">搜索</el-button>
            <el-button type="default" size="small" @click="resetSearch('filters')">重置</el-button>
          </el-form>
        </div>
        <div class="total-wrap">
          <span class="label">总数:{{ pagination.total }}</span>
        </div>
        <el-table :data="tableData" style="width: 100%">
          <el-table-column type="expand">
            <template slot-scope="scope">
              <el-form label-position="left" inline class="demo-table-expand">
                <el-form-item label="ID">
                  <span>{{ scope.row.auid }}</span>
                </el-form-item>
                <el-form-item label="账号">
                  <span>{{ scope.row.uid }}</span>
                </el-form-item>
                <el-form-item label="昵称">
                  <span>{{ scope.row.username }}</span>
                </el-form-item>
                <el-form-item label="状态">
                  <span>{{ scope.row.pass === 0 ? '未提交' : scope.row.pass === 1 ? '已参与': scope.row.pass === 2 ?'失效' :''}}</span>
                </el-form-item>
                <el-form-item label="数值">
                  <span>{{ scope.row.value }}</span>
                </el-form-item>
                <el-form-item label="标识">{{ scope.row.kid }}</el-form-item>
                <el-form-item label="玩法配置名称">{{ scope.row.playingType.name }}</el-form-item>
                <el-form-item label="获得时间">
                  <span>{{ scope.row.created_at }}</span>
                </el-form-item>
                <el-form-item label="头像">
                  <a
                    v-if="scope.row.arUserInfo"
                    :href="scope.row.arUserInfo.avatar"
                    target="_blank"
                    style="color: blue"
                  >查看</a>
                </el-form-item>
                <el-form-item label="玩法配置图标">
                  <a
                    v-if="scope.row.playingType"
                    :href="scope.row.playingType.image"
                    target="_blank"
                    style="color: blue"
                  >查看</a>
                </el-form-item>
                <el-form-item label="凭证">
                  <a :href="scope.row.link" target="_blank" style="color: blue">查看</a>
                </el-form-item>
              </el-form>
            </template>
          </el-table-column>
          <el-table-column :show-overflow-tooltip="true" prop="auid" label="ID" min-width="100"/>
          <el-table-column :show-overflow-tooltip="true" prop="uid" label="账号" min-width="100"/>
          <el-table-column prop="icon" label="昵称" min-width="130">
            <template slot-scope="scope">
              <div>{{ scope.row.username }}</div>
              <img
                :src="scope.row.arUserInfo ? scope.row.arUserInfo.avatar : ''"
                alt
                class="icon-item"
              >
            </template>
          </el-table-column>
          <el-table-column :show-overflow-tooltip="true" prop="deploy" label="玩法配置" min-width="100">
            <template slot-scope="scope">
              <div>{{ scope.row.playingType.name }}</div>
              <img
                :src="scope.row.playingType ? scope.row.playingType.image : ''"
                alt
                class="icon-item"
              >
            </template>
          </el-table-column>
          <el-table-column :show-overflow-tooltip="true" prop="value" label="数值" min-width="100"/>
          <el-table-column :show-overflow-tooltip="true" prop="kid" label="标识" min-width="100"/>
          <el-table-column :show-overflow-tooltip="true" prop="link" label="凭证" min-width="100">
            <template slot-scope="scope">
              <img :src="scope.row.link" alt class="icon-item">
            </template>
          </el-table-column>
          <el-table-column :show-overflow-tooltip="true" prop="pass" label="状态" min-width="100">
            <template
              slot-scope="scope"
            >{{ scope.row.pass === 0 ? '未提交' : scope.row.pass === 1 ? '已参与': scope.row.pass === 2 ?'失效' :''}}</template>
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="created_at"
            label="获得时间"
            min-width="150"
          />
          <el-table-column label="操作" min-width="100">
            <template slot-scope="scope">
              <el-button
                v-if="scope.row.playingType.aid === 32"
                size="small"
                type="warning"
                @click="sendRedPack(scope.row,scope.$index)"
              >发红包</el-button>
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
  getSearchPlayingTypes,
  getActivityParticipantList,
  sendRedPack
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
  Option
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
    "el-option": Option
  },
  data() {
    return {
      searchLoading: false,
      templateVisible: false,
      deployList: [],
      filters: {
        aid: "",
        pass: ""
      },
      templateForm: {
        policy_id: null,
        ids: []
      },
      policyList: [],
      statusList: [
        {
          id: 0,
          name: "未提交"
        },
        {
          id: 1,
          name: "已参与"
        },
        {
          id: 2,
          name: "失效"
        }
      ],
      loading: false,
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
    this.getSearchPlayingTypes();
    this.getActivityParticipantList();
  },
  methods: {
    sendRedPack(data, index) {
      this.$confirm("确定要发放红包吗?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          let args = {
            auid: data.auid,
            rank: index
          };
          sendRedPack(this, args)
            .then(res => {
              this.$message({
                type: "success",
                message: "发放成功"
              });
            })
            .catch(err => {
              this.$message({
                type: "warning",
                message: err.response.data.message
              });
            });
        })
        .catch(() => {
          this.$message({
            type: "info",
            message: "已取消发放"
          });
        });
    },
    getSearchPlayingTypes() {
      this.searchLoading = true;
      getSearchPlayingTypes(this)
        .then(result => {
          this.deployList = result;
          this.searchLoading = false;
        })
        .catch(err => {
          this.searchLoading = false;
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    getActivityParticipantList() {
      this.setting.loading = true;
      let args = {
        page: this.pagination.currentPage,
        include: "playingType,arUserInfo",
        aid: this.filters.aid,
        pass: this.filters.pass
      };
      if (this.filters.aid === "") {
        delete args.aid;
      }
      if (this.filters.pass === "") {
        delete args.pass;
      }
      getActivityParticipantList(this, args)
        .then(res => {
          this.tableData = res.data;
          if (this.filters.aid !== 32) {
            this.pagination.total = res.meta.pagination.total;
          } else {
            this.pagination.total = 5;
          }

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
    changePage(currentPage) {
      this.pagination.currentPage = currentPage;
      this.getActivityParticipantList();
    },
    search() {
      this.pagination.currentPage = 1;
      this.tableData = [];
      this.getActivityParticipantList();
    },
    resetSearch(formName) {
      this.$refs[formName].resetFields();
      this.pagination.currentPage = 1;
      this.getActivityParticipantList();
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
        // padding: 10px;
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
          width: 250px;
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
