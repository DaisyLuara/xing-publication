<template>
<div class="user-list-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
  <div class="user-list-content">
    <div class="search-wrap">
      <el-form  :model="filters" :inline="true">
        <el-form-item label="">
          <el-input v-model="filters.mobile" style="width:300px" placeholder="请输入搜索的手机号"></el-input>
        </el-form-item>
        <el-button @click="search" type="primary">搜索</el-button>
      </el-form>
    </div>
    <div class="actions-wrap">
      <el-button size="small" type="danger"  @click="deleteUsers(selectedUsers)">删除</el-button>
      <el-button size="small" type="success"  @click="linkToAddUser">新增用户</el-button>
    </div>
    <el-table :data="userList" highlight-current-row ref="userTable" style="width: 100%">
      <el-table-column type="selection" width="55" v-if="setting.isOpenSelectAll"></el-table-column>
      <el-table-column prop="photo" label="头像照片">
        <template slot-scope="scope">
          <img alt="" :src="scope.row.photo" class="photo_img"/>
        </template>
      </el-table-column>
      <el-table-column prop="name" label="姓名"></el-table-column>
      <el-table-column prop="mobile" label="手机号码"></el-table-column>
      <el-table-column prop="roles_name" label="角色"></el-table-column>
      <el-table-column prop="identity_card" label="身份证"></el-table-column>
      <el-table-column prop="department" label="部门"></el-table-column>
      <el-table-column prop="position" label="职位"></el-table-column>
      <el-table-column prop="status" label="状态"></el-table-column>
      <el-table-column prop="initial_password" label="初始密码"></el-table-column>
      <el-table-column label="操作" width="250">
        <template slot-scope="scope">
          <el-button size="small" v-if="scope.row.is_admin != 1 || scope.row.id != currentUser.id" type="danger" @click="deleteUsers(scope.row)">删除</el-button>
          <el-button size="small" @click="linkToEdit(scope.row)">修改</el-button>
          <el-button size="small" type="primary">状态修改</el-button>
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
import store from 'service/store'
import { Button, Input, Table, TableColumn, Pagination, Form, FormItem, MessageBox } from 'element-ui'

export default {
  name: 'userList',
  data () {
    return {
      userList: [{
        photo: 'http://sapi.xingstation.com/storage/2018/01/18/HvzTVmGedMvBcNBxYyzGTmaVsfK9GUqTH980oN9b.jpeg',
        name: '王小虎',
        mobile: '13453432342',
        roles_name: '管理者',
        identity_card: '2303442003090230003',
        department: '互联网',
        position: '开发',
        status: '正常',
        initial_password: '123456'
      }, {
        photo: 'http://sapi.xingstation.com/storage/2018/01/18/HvzTVmGedMvBcNBxYyzGTmaVsfK9GUqTH980oN9b.jpeg',
        name: '王小虎',
        mobile: '13453432342',
        roles_name: '管理者',
        identity_card: '2303442003090230003',
        department: '互联网',
        position: '开发',
        status: '正常',
        initial_password: '123456'
      }, {
        photo: 'http://sapi.xingstation.com/storage/2018/01/18/HvzTVmGedMvBcNBxYyzGTmaVsfK9GUqTH980oN9b.jpeg',
        name: '王小虎',
        mobile: '13453432342',
        roles_name: '管理者',
        identity_card: '2303442003090230003',
        department: '互联网',
        position: '开发',
        status: '正常',
        initial_password: '123456'
      }, {
        photo: 'http://sapi.xingstation.com/storage/2018/01/18/HvzTVmGedMvBcNBxYyzGTmaVsfK9GUqTH980oN9b.jpeg',
        name: '王小虎',
        mobile: '13453432342',
        roles_name: '管理者',
        identity_card: '2303442003090230003',
        department: '互联网',
        position: '开发',
        status: '正常',
        initial_password: '123456'
      }],
      allStores: [],
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: "拼命加载中"
      },
      filters: {
        name: "",
        mobile: ""
      },
      pagination: {
        total: 100,
        pageSize: 10,
        currentPage: 1
      },
      selectedUsers: [],
      currentUser: auth.getUserInfo(),
      storeMatched: false
    }
  },
  created () {
    // let allStoresPromise = this.getAllStores()
    // let userListPromise = this.getUserList()
    // Promise.all([allStoresPromise, userListPromise]).then(() => {
    //   this.storeMatched = true
    //   this.matchStoreIdToName()
    // })
  },
  methods: {
    openSelectAll () {
      this.setting.isOpenSelectAll = !this.setting.isOpenSelectAll;
      // 清除多选数组
      if(!this.setting.isOpenSelectAll){
        this.$refs.userTable.clearSelection();
      }
    },
    handleSelect (selection) {
      this.selectedUsers = selection;
    },
    linkToEdit (currentUser) {
      this.$router.push({
        path: '/system/user/edit/' + currentUser.id
      })
    },
    getUserList () {
      if(this.setting.loading == true){
        return false;
      }
      this.setting.loadingText = "拼命加载中"
      this.setting.loading = true;
      let searchArgs = {
        limit: this.pagination.pageSize,
        page_num: this.pagination.currentPage
      }
      return user.getUserList(this, searchArgs).then(response => {
        this.userList = response.data;
        this.pagination.total = response.page.count;
        this.setting.loading = false;
        this.combineRolesName()
        if(!this.storeMatched) {
          this.matchStoreIdToName()
        }
      }).catch(error => {
        this.setting.loading = false;
      })
    },
    getAllStores () {
      return store.getStoreList(this, {}).then(response => {
        this.allStores = response.data
      }).catch(error => {
        console.log(error)
      })
    },
    combineRolesName () {
      if(this.userList.length != 0) {
        let userListLen = this.userList.length
        for(let i = 0; i < userListLen; i++) {
          let thisUser = this.userList[i]
          let thisRoles = thisUser.roles
          let thisRolesLen = thisRoles.length
          let rolesNameCombined = thisRoles[0].display_name
          for(let j = 1; j < thisRolesLen; j++) {
            rolesNameCombined = rolesNameCombined + ', ' + thisRoles[j].display_name
          }
          thisUser.roles_name = rolesNameCombined
        }
      }
    },
    matchStoreIdToName () {
      if(this.userList.length != 0 && this.allStores.length != 0) {
        let userListLen = this.userList.length
        for(let i = 0; i < userListLen; i++) {
          let allStoresLen = this.allStores.length
          this.userList[i].optical_store_display_name = ''
          for(let j = 0; j < allStoresLen; j++) {
            if(this.userList[i].optical_store_id === this.allStores[j].id) {
              this.userList[i].optical_store_display_name = this.allStores[j].name
              break
            }
          }
        }
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
      this.storeMatched = false
    },
    search (){
      this.pagination.currentPage = 1;
      //this.getUserList();
    },
    resetSearch () {
      this.pagination.currentPage = 1;
      // 清空filter
      // this.getUserList();
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
    margin: 10px auto;
  }
  .pagination-wrap{
    margin: 10px auto;
    text-align: right;
  }
}
</style>
