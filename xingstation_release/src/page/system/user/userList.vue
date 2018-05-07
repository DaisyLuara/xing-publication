<template>
<div class="user-list-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
  <div class="user-list-content">
    <div class="search-wrap">
      <el-form  :model="filters" :inline="true">
        <el-form-item label="">
          <el-input v-model="filters.phone" style="width:300px" placeholder="请输入搜索的手机号"></el-input>
        </el-form-item>
        <el-button @click="search" type="primary">搜索</el-button>
        <el-button @click="resetSearch" type="default">重置</el-button>
      </el-form>
    </div>
    <div class="actions-wrap">
      <el-button size="small" type="success"  @click="linkToAddUser">新增用户</el-button>
    </div>
    <el-table :data="userList" highlight-current-row ref="userTable" style="width: 100%">
      <el-table-column prop="name" label="姓名"></el-table-column>
      <el-table-column prop="phone" label="手机号码"></el-table-column>
      <el-table-column prop="role" label="角色"></el-table-column>
      <el-table-column label="操作" width="200">
        <template slot-scope="scope">
          <el-button size="small" @click="linkToEdit(scope.row)">修改</el-button>
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
</template>
<script>

import user from 'service/user'
import auth from 'service/auth'
import { Button, Input, Table, TableColumn, Pagination, Form, FormItem, MessageBox } from 'element-ui'

export default {
  name: 'userList',
  data () {
    return {
      userList: [],
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      filters: {
        phone: ""
      },
      pagination: {
        total: 100,
        pageSize: 10,
        currentPage: 1
      },
    }
  },
  created () {
    this.getUserList()
  },
  methods: {
    linkToEdit (currentUser) {
      this.$router.push({
        path: '/system/user/edit/' + currentUser.id
      })
    },
    getUserList () {
      if(this.setting.loading == true){
        return false;
      }
      let pageNum = this.pagination.currentPage
      let args = {
        include: 'roles',
        page: pageNum,
        phone: this.filters.phone
      }
      this.setting.loadingText = "拼命加载中"
      this.setting.loading = true;
      return user.getUserList(this, args).then(response => {
        this.setting.loading = false;
        this.userList = response.data;
        this.pagination.total = response.meta.pagination.total;
        this.handleRole();
      }).catch(error => {
        this.setting.loading = false;
      })
    },
    handleRole() {
      if(this.userList.length != 0) {
        let userListLen = this.userList.length
        for(let i = 0; i < userListLen; i++) {
          let thisUser = this.userList[i]
          let thisRoles = thisUser.roles
          let rolesNameCombined = thisRoles.data[0].name
          this.userList[i].role = rolesNameCombined
        }
        console.log(this.userList)
      }
    },
    deleteUsers (users) {
      let submitDelete = [];
      if(users.id){
        submitDelete.push(users.id);
      }else{
        for(let i = 0, sL = users.length; i < sL; i++){
          submitDelete.push(users[i].id)
        }
      }
      if(submitDelete.length < 1){
        this.$message.error("请先选择一个要删除的用户")
      }else{
        MessageBox.confirm('确认删除选中用户?', '提示', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          this.setting.loadingText = "删除中"
          this.setting.loading = true;
          user.deleteUsers(this, { ids: submitDelete}).then(response => {
            this.setting.loading = false;
            this.$message({
              type: "success",
              message: "删除成功！"
            })

            this.getUserList();
          }).catch(error => {
            this.setting.loading = false;
            console.log(error);
          })
        }).catch(() => {
        });
      }
    },
    changePage (currentPage) {
      this.pagination.currentPage = currentPage
      this.getUserList()
    },
    search (){
      this.pagination.currentPage = 1;
      this.getUserList();
    },
    resetSearch () {
      this.filters.phone = ''
      this.pagination.currentPage = 1;
      this.getUserList();
    },
    linkToAddUser () {
      this.$router.push({
        path: '/system/user/add'
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
.user-list-wrap{
  h1{
    text-align: center;
  }
}
.user-list-content{
  .photo_img{
    width: 100%;
    padding: 5px;
  }
  .actions-wrap{
    text-align: right;
    margin: 10px auto;
  }
  .pagination-wrap{
    margin: 10px auto;
    text-align: right;
  }
}
</style>
