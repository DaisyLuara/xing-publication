<template>
  <div class="root">
    <div class="item-list-wrap">
      <el-tabs 
        v-model="activeName" 
        @tab-click="handleClick">
        <el-tab-pane 
          label="中台记录" 
          name="first">
          <OperateTable :type="type"/>
        </el-tab-pane>
        <el-tab-pane 
          label="商户记录" 
          name="second">
          <OperateTable :type="type" />
        </el-tab-pane>
      </el-tabs>
    </div>
  </div>
</template>

<script>
import OperateTable from "./com/operateTable";
import { MessageBox, TabPane, Tabs } from "element-ui";

export default {
  components: {
    "el-tabs": Tabs,
    "el-tab-pane": TabPane,
    OperateTable
  },
  data() {
    return {
      type: "user",
      activeName: "first",
      setting: {
        loadingText: "拼命加载中...",
        loading: false
      }
    };
  },
  created() {
    localStorage.setItem("activeName", this.activeName);
  },
  methods: {
    handleClick(val) {
      if (val.name === "first") {
        this.type = "user";
      } else {
        this.type = "customer";
      }
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
