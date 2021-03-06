<template>
  <div
    v-loading="setting.loading"
    :element-loading-text="setting.loadingText"
    class="user-list-wrap"
  >
    <div class="user-list-content">
      <div class="search-wrap">
        <el-form 
          :model="filters" 
          :inline="true">
          <el-form-item label>
            <el-input
              v-model="filters.phone"
              style="width:200px"
              placeholder="请输入搜索的手机号"
              clearable
            />
          </el-form-item>
          <el-form-item label>
            <el-input 
              v-model="filters.name" 
              style="width:200px" 
              placeholder="请输入搜索的名字" 
              clearable/>
          </el-form-item>
          <el-form-item label>
            <el-select 
              v-model="filters.role_id" 
              placeholder="请选择角色" 
              filterable 
              clearable>
              <el-option
                v-for="item in roleList"
                :key="item.id"
                :label="item.display_name"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
          <el-button 
            type="primary" 
            size="small" 
            @click="search">搜索</el-button>
          <el-button 
            type="default" 
            size="small" 
            @click="resetSearch">重置</el-button>
        </el-form>
      </div>
      <div class="actions-wrap">
        <span class="label">用户数量: {{ pagination.total }}</span>
        <el-button 
          size="small" 
          type="success" 
          @click="linkToAddUser">新增用户</el-button>
      </div>
      <el-table 
        ref="userTable" 
        :data="userList" 
        highlight-current-row 
        style="width: 100%" 
        type="expand">
        <el-table-column type="expand">
          <template slot-scope="scope">
            <el-form 
              label-position="left" 
              inline 
              class="demo-table-expand">
              <el-form-item label="ID:">
                <span>{{ scope.row.id }}</span>
              </el-form-item>
              <el-form-item label="姓名:">
                <span>{{ scope.row.name }}</span>
              </el-form-item>
              <el-form-item label="手机号码:">
                <span>{{ scope.row.phone }}</span>
              </el-form-item>
              <el-form-item label="是否绑定微信:">
                <span>{{ scope.row.bind_weixin === true ? '是' : '否' }}</span>
              </el-form-item>
              <el-form-item label="是否有z值:">
                <span>{{ scope.row.z ? '有' :'无' }}</span>
              </el-form-item>
              <el-form-item label="创建时间:">
                <span>{{ scope.row.created_at }}</span>
              </el-form-item>
              <el-form-item label="更新时间:">
                <span>{{ scope.row.updated_at }}</span>
              </el-form-item>
            </el-form>
          </template>
        </el-table-column>
        <el-table-column 
          :show-overflow-tooltip="true" 
          prop="name" 
          label="姓名" 
          min-width="100"/>
        <el-table-column 
          :show-overflow-tooltip="true" 
          prop="phone" 
          label="手机号码" 
          min-width="150"/>
        <el-table-column 
          :show-overflow-tooltip="true" 
          prop="role" 
          label="角色" 
          min-width="100"/>
        <el-table-column 
          :show-overflow-tooltip="true" 
          prop="z" 
          label="是否有z值" 
          min-width="120">
          <template slot-scope="scope">
            <span>{{ scope.row.z ? '是' : '否' }}</span>
          </template>
        </el-table-column>
        <el-table-column
          :show-overflow-tooltip="true"
          prop="updated_at"
          label="修改时间"
          min-width="120"
        />
        <el-table-column 
          label="操作" 
          width="280">
          <template slot-scope="scope">
            <el-button
              v-if="scope.row.role === 'BD主管' || scope.row.role === 'BD'"
              size="small"
              @click="migration(scope.row)"
            >迁移</el-button>
            <el-button 
              size="small" 
              type="warning" 
              @click="linkToEdit(scope.row)">修改</el-button>
            <el-button 
              size="small" 
              type="danger" 
              @click="deleteUsers(scope.row)">删除</el-button>
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
    <el-dialog 
      :visible.sync="dialogFormVisible" 
      :show-close="false" 
      title="账号迁移">
      <el-form 
        ref="accountForm" 
        :model="accountForm">
        <el-form-item
          :rules="{
            required: true, message: '账号迁移目标不能为空', trigger: 'submit'
          }"
          label="账号迁移目标"
          label-width="150px"
          prop="user_id"
        >
          <el-select 
            v-model="accountForm.user_id" 
            placeholder="请选择账号迁移目标" 
            clearable 
            filterable>
            <el-option 
              v-for="item in bdList" 
              :label="item.name" 
              :value="item.id" 
              :key="item.id"/>
          </el-select>
        </el-form-item>
      </el-form>
      <div 
        slot="footer" 
        class="dialog-footer">
        <el-button @click="cancel('accountForm')">取 消</el-button>
        <el-button 
          type="primary" 
          @click="submit('accountForm')">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>
<script>
import {
  getSearchRole,
  getUserList,
  deleteUser,
  getSearchBD,
  migrationBDAccount
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
  Dialog
} from "element-ui";

export default {
  name: "UserList",
  components: {
    "el-dialog": Dialog,
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
      dialogFormVisible: false,
      accountForm: {
        user_id: null
      },
      userList: [],
      roleList: [],
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      filters: {
        phone: "",
        role_id: "",
        name: ""
      },
      bdList: [],
      pagination: {
        total: 100,
        pageSize: 10,
        currentPage: 1
      },
      bdID: null
    };
  },
  created() {
    this.getUserList();
    this.getRoleList();
    this.init();
  },
  methods: {
    cancel(formName) {
      this.dialogFormVisible = false;
      this.$refs[formName].resetFields();
    },
    submit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          let args = this.accountForm;
          migrationBDAccount(this, this.bdID, args)
            .then(res => {
              this.$message({
                message: "迁移成功",
                type: "success"
              });
              this.dialogFormVisible = false;
            })
            .catch(err => {
              console.log(err);
              this.dialogFormVisible = false;
            });
        }
      });
    },
    async init() {
      let res = await getSearchBD(this);
      this.bdList = res;
    },
    migration(data) {
      this.$confirm("此操作将永久迁移，不可以逆转, 是否继续?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          this.bdID = data.id;
          this.dialogFormVisible = true;
        })
        .catch(() => {});
    },
    linkToEdit(currentUser) {
      this.$router.push({
        path: "/system/user/edit/" + currentUser.id
      });
    },
    getRoleList() {
      let args = {
        guard_name: "web"
      };
      getSearchRole(this, args)
        .then(result => {
          this.roleList = result.data;
        })
        .catch(error => {
          console.log(error);
        });
    },
    getUserList() {
      if (this.setting.loading == true) {
        return false;
      }
      let pageNum = this.pagination.currentPage;
      let args = {
        include: "roles",
        page: pageNum,
        phone: this.filters.phone,
        role_id: this.filters.role_id,
        name: this.filters.name
      };
      if (this.filters.role_id === "") {
        delete args.role_id;
      }
      if (this.filters.name === "") {
        delete args.name;
      }
      if (this.filters.phone === "") {
        delete args.phone;
      }
      this.setting.loadingText = "拼命加载中";
      this.setting.loading = true;
      return getUserList(this, args)
        .then(response => {
          this.setting.loading = false;
          this.userList = response.data;
          this.pagination.total = response.meta.pagination.total;
          this.handleRole();
        })
        .catch(error => {
          this.setting.loading = false;
        });
    },
    handleRole() {
      if (this.userList.length != 0) {
        let userListLen = this.userList.length;
        for (let i = 0; i < userListLen; i++) {
          let thisUser = this.userList[i];
          let thisRoles = thisUser.roles;
          let rolesNameCombined = thisRoles.data[0].display_name;
          this.userList[i].role = rolesNameCombined;
        }
      }
    },
    deleteUsers(users) {
      let id = users.id;
      MessageBox.confirm("确认删除选中用户?", "提示", {
        confirmButtonText: "确定",
        cancelButtonText: "取消",
        type: "warning"
      })
        .then(() => {
          this.setting.loadingText = "删除中";
          this.setting.loading = true;
          deleteUser(this, id)
            .then(response => {
              this.setting.loading = false;
              this.$message({
                type: "success",
                message: "删除成功！"
              });
              this.pagination.currentPage = 1;
              this.getUserList();
            })
            .catch(error => {
              this.$message({
                type: "warning",
                message: error.response.data.message
              });
              this.setting.loading = false;
            });
        })
        .catch(e => {
          console.log(e);
        });
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage;
      this.getUserList();
    },
    search() {
      this.pagination.currentPage = 1;
      this.getUserList();
    },
    resetSearch() {
      this.filters.phone = "";
      this.filters.name = "";
      this.filters.role_id = "";
      this.pagination.currentPage = 1;
      this.getUserList();
    },
    linkToAddUser() {
      this.$router.push({
        path: "/system/user/add"
      });
    }
  }
};
</script>

<style lang="less" scoped>
.user-list-wrap {
  h1 {
    text-align: center;
  }
}
.user-list-content {
  .photo_img {
    width: 100%;
    padding: 5px;
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
</style>
