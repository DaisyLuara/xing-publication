<template>
  <div class="add-role-wrap">
    <div v-loading="setting.loading" :element-loading-text="setting.loadingText">
      <div class="role-title">{{ $route.name }}</div>
      <el-form ref="roleForm" :model="roleForm" label-width="100px" class="roleForm">
        <el-form-item
          :rules="[{ required: true, message: '角色名称不能为空',trigger:'submit'}]"
          label="角色名称"
          prop="name"
        >
          <el-input v-model="roleForm.name" placeholder="输入角色名称" class="role-form-input"/>
        </el-form-item>
        <el-form-item
          :rules="[{ required: true, message: '显示名称不能为空',trigger:'submit'}]"
          label="显示名称"
          prop="display_name"
        >
          <el-input v-model="roleForm.display_name" placeholder="输入显示名称" class="role-form-input"/>
        </el-form-item>
        <el-form-item label="权限">
          <el-table :data="allPerms" border ref="permsTable" class="role-table">
            <el-table-column label="一级">
              <template slot-scope="scope">
                <el-checkbox-group @change="handleChange(scope.row)" v-model="roleForm.ids">
                  <el-checkbox :label="scope.row.id">{{scope.row.display_name}}</el-checkbox>
                </el-checkbox-group>
              </template>
            </el-table-column>
            <el-table-column label="二级">
              <template slot-scope="scope">
                <el-table :data="scope.row.children" :show-header="false">
                  <el-table-column>
                    <template slot-scope="scope">
                      <el-checkbox-group @change="handleChange(scope.row)" v-model="roleForm.ids">
                        <el-checkbox :label="scope.row.id">{{scope.row.display_name}}</el-checkbox>
                      </el-checkbox-group>
                    </template>
                  </el-table-column>
                </el-table>
              </template>
            </el-table-column>
            <el-table-column label="权限配置细则">
              <template slot-scope="scope">
                <el-table :data="scope.row.children" :show-header="false">
                  <el-table-column>
                    <template slot-scope="scope">
                      <el-checkbox
                        v-model="roleForm.ids"
                        @change="handleChange(thirdChild)"
                        v-for="thirdChild in scope.row.children"
                        v-bind:data="thirdChild"
                        v-bind:key="thirdChild.id"
                        :label="thirdChild.id"
                      >{{thirdChild.display_name}}</el-checkbox>
                    </template>
                  </el-table-column>
                </el-table>
              </template>
            </el-table-column>
          </el-table>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onSubmit('roleForm')">保存</el-button>
          <el-button @click="historyBack()">取消</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import { historyBack, saveRole, getRoleInfo, getPermission } from "service";
import {
  Button,
  Input,
  Form,
  FormItem,
  CheckboxGroup,
  Checkbox,
  Table,
  TableColumn,
  MessageBox
} from "element-ui";

export default {
  components: {
    "el-button": Button,
    "el-table": Table,
    "el-table-column": TableColumn,
    "el-input": Input,
    "el-form": Form,
    "el-form-item": FormItem,
    "el-checkbox-group": CheckboxGroup,
    "el-checkbox": Checkbox
  },
  data() {
    return {
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      roleForm: {
        name: "",
        display_name: "",
        ids: []
      },

      roleID: null,
      allPerms: [],
      permsLoading: true
    };
  },
  created: function() {
    this.roleID = this.$route.params.uid;
    let args = {
      guard_name: "web"
    };
    // 获取所有权限
    getPermission(this, args)
      .then(result => {
        this.setting.loading = true;
        let pers = result;
        for (let per in pers) {
          this.allPerms.push(pers[per]);
        }
        if (this.roleID) {
          this.getRoleInfo();
        } else {
          this.setting.loading = false;
          this.permsLoading = false;
        }
      })
      .catch(error => {
        this.setting.loading = false;
        this.permsLoading = false;
        this.$message({
          type: "warning",
          message: error.response.data.message
        });
      });
  },
  methods: {
    getRoleInfo() {
      getRoleInfo(this, this.roleID)
        .then(result => {
          let roleInfo = result;
          this.roleForm.name = roleInfo.name;
          this.roleForm.display_name = roleInfo.display_name;
          for (let i = 0, rL = roleInfo.permission.length; i < rL; i++) {
            this.roleForm.ids.push(roleInfo.permission[i].id);
          }
          this.setting.loading = false;
          this.permsLoading = false;
        })
        .catch(error => {
          this.setting.loading = false;
          this.permsLoading = false;
          this.$message({
            type: "warning",
            message: error.response.data.message
          });
        });
    },
    selectChildPerm(checkedPerm, type) {
      let subPerm = [],
        checkedPerms = this.roleForm.ids;
      if (checkedPerm.children && checkedPerm.children.length > 0) {
        subPerm = checkedPerm.children;
        for (let i in subPerm) {
          switch (type) {
            case "select":
              if (!checkedPerms.includes(subPerm[i].id)) {
                checkedPerms.push(subPerm[i].id);
              }
              break;
            case "inselect":
              checkedPerms.find(function(value, index, arr) {
                if (value == subPerm[i].id) {
                  arr.splice(index, 1);
                }
              });
              break;
            default:
              break;
          }
          this.selectChildPerm(subPerm[i], type);
        }
      } else {
        return false;
      }
    },
    selectParentPerm(parentPerm, permsName, times) {
      let checkedPerms = this.roleForm.ids;
      for (let per in parentPerm) {
        let nameArry = parentPerm[per].name.split(".");
        if (nameArry[times] == permsName[times]) {
          if (!checkedPerms.includes(parentPerm[per].id)) {
            checkedPerms.push(parentPerm[per].id);
          }
          if (
            permsName.length > ++times &&
            parentPerm[per].children &&
            parentPerm[per].children.length > 0
          ) {
            this.selectParentPerm(parentPerm[per].children, permsName, times);
          }
          break;
        }
      }
    },
    handleChange(checkedPerm) {
      let checkedPerms = this.roleForm.ids;
      if (this.roleForm.ids.includes(checkedPerm.id)) {
        // 选中: 所有子权限
        this.selectChildPerm(checkedPerm, "select");
        // 选择: 所有父权限
        let parentName = checkedPerm.name.split(".");
        this.selectParentPerm(this.allPerms, parentName, 0);
      } else {
        // 反选:所有子权限
        this.selectChildPerm(checkedPerm, "inselect");
      }
    },
    onSubmit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          this.setting.loading = true;
          let args = this[formName];
          if (this.roleID) {
            args.id = this.roleID;
          }
          saveRole(this, args, this.roleID)
            .then(result => {
              this.$message({
                message: this.roleID ? "修改成功" : "添加成功",
                type: "success"
              });
              this.setting.loading = false;
              this.$router.push({
                path: "/system/role/"
              });
            })
            .catch(error => {
              this.setting.loading = false;
              console.log(error);
            });
        }
      });
    },
    historyBack() {
      historyBack();
    }
  }
};
</script>
<style scoped lang="less">
.add-role-wrap {
  .el-form-item__content {
    width: 50%;
  }
  .perms-wrap {
    .el-form-item__content {
      width: auto;
    }
  }
  .role-form-input,
  .user-form-select {
    width: 385px;
  }
  .up-area-cover {
    border: 1px dashed #d9d9d9;
    width: 228px;
    height: 228px;
    cursor: pointer;
    position: relative;
    .cover {
      width: 228px;
      height: 228px;
      display: block;
    }
    .cover-uploader-icon {
      font-size: 28px;
      color: #8c939d;
      width: 228px;
      height: 228px;
      line-height: 228px;
      text-align: center;
    }
    .delete-icon-image {
      position: absolute;
      top: 5px;
      right: 5px;
      font-size: 20px;
      color: #83909a;
      cursor: pointer;
    }
  }
  .role-title {
    margin-bottom: 20px;
  }
  .el-checkbox {
    margin-left: 0px;
    margin-right: 15px;
  }
}
</style>
