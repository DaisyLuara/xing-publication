<template>
<div class="role-list-wrap" v-loading="setting.loading">
  <div class="role-list-content">
    <div class="actions-wrap">
      <!--<el-button size="small"  @click="openSelectAll">{{ setting.isOpenSelectAll ? "取消" : "多选" }}</el-button>-->
      <el-button size="small" type="danger"  @click="deleteRoles(selectedRoles)">删除</el-button>
      <el-button size="small" type="success"  @click="linktoAddRole">新增角色</el-button>
    </div>
    <el-table :data="roleList" highlight-current-row  @selection-change="handleSelect" :element-loading-text="setting.loadingText" ref="roleTable">
      <el-table-column type="selection" width="55" v-if="setting.isOpenSelectAll"></el-table-column>
      <el-table-column prop="display_name" label="角色名"></el-table-column>
      <el-table-column prop="created_at" label="创建时间"></el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <el-button size="small" v-if="scope.row.role_template_id == 0" type="danger" @click="deleteRoles(scope.row)">删除</el-button>
          <el-button size="small" @click="linkToEdit(scope.row)">修改</el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="pagination-wrap">
      <el-pagination
      layout="prev, pager, next"
      :total="pagination.total"
      :page-size="pagination.pageSize"
      :current-page="pagination.currentPage"
      @current-change="changePage">
      </el-pagination>
    </div>
  </div>
</div>
</template>
<script>

import role from 'service/role'
import user from 'service/user'
import { Button, Input, Table, TableColumn, Pagination, Form, FormItem, MessageBox } from 'element-ui'

export default {
  name: 'roleList',
  data () {
    return {
      roleList: [],
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: "拼命加载中"
      },
      pagination: {
        total: 1,
        pageSize: 10,
        currentPage: 1
      },
      selectedRoles: []
    }
  },
  created () {
    this.getRoleList();
  },
  methods: {
    openSelectAll () {
      this.setting.isOpenSelectAll = !this.setting.isOpenSelectAll;
      // 清除多选数组
      if(!this.setting.isOpenSelectAll){
        this.$refs.roleTable.clearSelection();
      }
    },
    handleSelect (selection) {
      this.selectedRoles = selection;
    },
    linkToEdit (currentRole) {
      this.$router.push({
        path: '/system/role/edit/' + currentRole.id
      })
    },
    deleteRoles (roles) {
      let submitDelete = [];
      if(roles.id){
        submitDelete.push(roles.id);
      }else{
        for(let i = 0, sL = roles.length; i < sL; i++){
          submitDelete.push(roles[i].id)
        }
      }

      if(submitDelete.length < 1){
        this.$message.error("请先选择一个要删除的角色")
      }else{
        MessageBox.confirm('确认删除选中角色?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          this.setting.loadingText = "删除中"
          this.setting.loading = true;
          role.deleteRoles(this, { ids: submitDelete}).then(response => {
            this.setting.loading = false;
            this.$message({
              type: "success",
              message: "删除成功！"
            })
            this.getRoleList();
          }).catch(error => {
            this.setting.loading = false;
            console.log(error);
          })
        }).catch(() => {
        });
      }
    },
    getRoleList (){
      if(this.setting.loading == true){
        return false;
      }

      this.setting.loadingText = "拼命加载中"
      this.setting.loading = true;
      let searchArgs = {
        limit: this.pagination.pageSize,
        page_num: this.pagination.currentPage
      }

      user.getManageableRoles(this, searchArgs).then(response => {
        this.roleList = response.data;
        this.pagination.total = response.page.count;
        this.setting.loading = false;
      }).catch(error => {
        this.setting.loading = false;
      })
    },
    changePage (currentPage) {
      this.pagination.currentPage = currentPage;
      this.getRoleList();
    },
    search () {
      this.pagination.currentPage = 1;
      //this.getRoleList();
    },
    resetSearch () {
      this.pagination.currentPage = 1;
      // 清空filter
      // this.getRoleList();
    },
    linktoAddRole () {
      this.$router.push({
        path: '/system/role/add'
      })
    }
  },
  components: {
    "el-table": Table,
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
.role-list-wrap{
  h1{
    text-align: center;
  }
}
.role-list-content{
  .actions-wrap{
    margin: 10px auto;
  }
  .pagination-wrap{
    margin: 10px auto;
    text-align: right;
  }
}
</style>
