<template>
  <div class="add-role-wrap">
    <div v-loading="setting.loading" :element-loading-text="setting.loadingText">
      <div class="role-title">角色{{ $route.name }}</div>
      <el-form ref="roleForm" :model="roleForm" label-width="120px" class="roleForm">
        <el-form-item
          :rules="[{ required: true, message: '角色名称不能为空',trigger:'submit'}]"
          label="角色名称"
          prop="name"
        >
          <el-input v-model="roleForm.name" placeholder="输入角色名称" class="role-form-input"/>
        </el-form-item>
        <el-form-item
          :rules="[{ required: true, message: '角色英文名称不能为空',trigger:'submit'}]"
          label="角色英文名称"
          prop="display_name"
        >
          <el-input v-model="roleForm.display_name" placeholder="输入角色英文名称" class="role-form-input"/>
        </el-form-item>
        <el-form-item
          label="角色描述"
          prop="descripte"
        >
          <el-input v-model="roleForm.descripte" placeholder="输入角色描述" class="role-form-input"/>
        </el-form-item>
        <el-form-item label="权限">
          <el-table :data="allPerms" border ref="permsTable" class="role-table">
            <el-table-column label="一级">
              <template slot-scope="scope">
                <el-checkbox-group @change="handleChange(scope.row)" v-model="roleForm.perms">
                  <el-checkbox :label="scope.row.id">{{scope.row.display_name}}</el-checkbox>
                </el-checkbox-group>
              </template>
            </el-table-column>
            <el-table-column label="二级">
              <template slot-scope="scope">
                <el-table :data="scope.row.children" :show-header="false">
                  <el-table-column>
                    <template slot-scope="scope">
                      <el-checkbox-group @change="handleChange(scope.row)" v-model="roleForm.perms">
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
                        v-model="roleForm.perms"
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
import { historyBack, saveRole, getRoleInfoByRid } from "service";
import {
  Button,
  Input,
  Form,
  FormItem,
  CheckboxGroup,
  Checkbox,
  Table,
  TableColumn
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
        descripte: "",
        perms: []
      },
      roleID: "",
      allPerms: [
        {
          id: 1,
          sort: 0,
          status: 1,
          name: "main",
          display_name: "首页",
          description: "首页",
          parent_id: null,
          lft: 1,
          rgt: 2,
          depth: 0,
          created_at: "2017-08-31 01:31:55",
          updated_at: "2017-08-31 01:31:55",
          children: []
        },
        {
          id: 2,
          sort: 0,
          status: 1,
          name: "system",
          display_name: "权限",
          description: "权限",
          parent_id: null,
          lft: 3,
          rgt: 26,
          depth: 0,
          created_at: "2017-08-31 01:31:55",
          updated_at: "2017-08-31 01:31:56",
          children: [
            {
              id: 3,
              sort: 0,
              status: 1,
              name: "system.user",
              display_name: "用户",
              description: "用户",
              parent_id: 2,
              lft: 4,
              rgt: 9,
              depth: 1,
              created_at: "2017-08-31 01:31:55",
              updated_at: "2017-08-31 01:31:55",
              children: [
                {
                  id: 4,
                  sort: 0,
                  status: 1,
                  name: "system.user.view",
                  display_name: "列表",
                  description: "列表",
                  parent_id: 3,
                  lft: 5,
                  rgt: 6,
                  depth: 2,
                  created_at: "2017-08-31 01:31:55",
                  updated_at: "2017-08-31 01:31:55",
                  children: []
                },
                {
                  id: 5,
                  sort: 0,
                  status: 1,
                  name: "system.user.edit",
                  display_name: "\u7f16\u8f91",
                  description: "\u7f16\u8f91\u7528\u6237\u5217\u8868",
                  parent_id: 3,
                  lft: 7,
                  rgt: 8,
                  depth: 2,
                  created_at: "2017-08-31 01:31:55",
                  updated_at: "2017-08-31 01:31:55",
                  children: []
                }
              ]
            },
            {
              id: 6,
              sort: 0,
              status: 1,
              name: "system.role",
              display_name: "角色",
              description: "角色",
              parent_id: 2,
              lft: 10,
              rgt: 15,
              depth: 1,
              created_at: "2017-08-31 01:31:55",
              updated_at: "2017-08-31 01:31:55",
              children: [
                {
                  id: 7,
                  sort: 0,
                  status: 1,
                  name: "system.role.view",
                  display_name: "列表",
                  description: "列表",
                  parent_id: 6,
                  lft: 11,
                  rgt: 12,
                  depth: 2,
                  created_at: "2017-08-31 01:31:55",
                  updated_at: "2017-08-31 01:31:55",
                  children: []
                },
                {
                  id: 8,
                  sort: 0,
                  status: 1,
                  name: "system.role.edit",
                  display_name: "编辑",
                  description: "编辑",
                  parent_id: 6,
                  lft: 13,
                  rgt: 14,
                  depth: 2,
                  created_at: "2017-08-31 01:31:55",
                  updated_at: "2017-08-31 01:31:55",
                  children: []
                }
              ]
            }
          ]
        },
        {
          id: 12,
          sort: 0,
          status: 1,
          name: "cms",
          display_name: "节目",
          description: "节目",
          parent_id: null,
          lft: 27,
          rgt: 34,
          depth: 1,
          created_at: "2017-08-31 01:31:55",
          updated_at: "2017-08-31 01:31:56",
          children: [
            {
              id: 13,
              sort: 0,
              status: 1,
              name: "cms.store",
              display_name: "节目投放",
              description: "节目投放",
              parent_id: 12,
              lft: 28,
              rgt: 33,
              depth: 2,
              created_at: "2017-08-31 01:31:55",
              updated_at: "2017-08-31 01:31:56",
              children: [
                {
                  id: 14,
                  sort: 0,
                  status: 1,
                  name: "cms.store.view",
                  display_name: "列表",
                  description: "列表",
                  parent_id: 13,
                  lft: 29,
                  rgt: 30,
                  depth: 3,
                  created_at: "2017-08-31 01:31:55",
                  updated_at: "2017-08-31 01:31:56",
                  children: []
                },
                {
                  id: 15,
                  sort: 0,
                  status: 1,
                  name: "cms.store.edit",
                  display_name: "新增",
                  description: "新增",
                  parent_id: 13,
                  lft: 31,
                  rgt: 32,
                  depth: 3,
                  created_at: "2017-08-31 01:31:55",
                  updated_at: "2017-08-31 01:31:56",
                  children: []
                }
              ]
            },
            {
              id: 90,
              sort: 0,
              status: 1,
              name: "cms.store",
              display_name: "模版排期",
              description: "模版排期",
              parent_id: 12,
              lft: 28,
              rgt: 33,
              depth: 2,
              created_at: "2017-08-31 01:31:55",
              updated_at: "2017-08-31 01:31:56",
              children: [
                {
                  id: 91,
                  sort: 0,
                  status: 1,
                  name: "cms.store.view",
                  display_name: "列表",
                  description: "列表",
                  parent_id: 13,
                  lft: 29,
                  rgt: 30,
                  depth: 3,
                  created_at: "2017-08-31 01:31:55",
                  updated_at: "2017-08-31 01:31:56",
                  children: []
                },
                {
                  id: 92,
                  sort: 0,
                  status: 1,
                  name: "cms.store.edit",
                  display_name: "编辑",
                  description: "编辑",
                  parent_id: 13,
                  lft: 31,
                  rgt: 32,
                  depth: 3,
                  created_at: "2017-08-31 01:31:55",
                  updated_at: "2017-08-31 01:31:56",
                  children: []
                }
              ]
            },
            {
              id: 93,
              sort: 0,
              status: 1,
              name: "cms.store",
              display_name: "节目列表",
              description: "节目列表",
              parent_id: 12,
              lft: 28,
              rgt: 33,
              depth: 2,
              created_at: "2017-08-31 01:31:55",
              updated_at: "2017-08-31 01:31:56",
              children: [
                {
                  id: 94,
                  sort: 0,
                  status: 1,
                  name: "cms.store.view",
                  display_name: "列表",
                  description: "列表",
                  parent_id: 13,
                  lft: 29,
                  rgt: 30,
                  depth: 3,
                  created_at: "2017-08-31 01:31:55",
                  updated_at: "2017-08-31 01:31:56",
                  children: []
                },
                {
                  id: 95,
                  sort: 0,
                  status: 1,
                  name: "cms.store.edit",
                  display_name: "编辑",
                  description: "编辑",
                  parent_id: 13,
                  lft: 31,
                  rgt: 32,
                  depth: 3,
                  created_at: "2017-08-31 01:31:55",
                  updated_at: "2017-08-31 01:31:56",
                  children: []
                }
              ]
            }
          ]
        }
      ]
    };
  },
  created: function() {
    this.roleID = this.$route.params.uid;
  },
  getRoleInfoByRid() {
    getRoleInfoByRid(this, this.roleID)
      .then(res => {
        this.roleForm = res;
      })
      .catch(err => {
        console.log(err);
      });
  },
  methods: {
    selectChildPerm(checkedPerm, type) {
      let subPerm = [],
        checkedPerms = this.roleForm.perms;
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
      let checkedPerms = this.roleForm.perms;
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
      let checkedPerms = this.roleForm.perms;
      if (this.roleForm.perms.includes(checkedPerm.id)) {
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
          // delete this[formName].user.repassword;
          // this.loading = true;
          // saveUser(this, this[formName].user, this.roleID)
          //   .then(result => {
          //     this.loading = false;
          //     this.$message({
          //       message: this.roleID ? "修改成功" : "添加成功",
          //       type: "success"
          //     });
          //     // todo是否返回用户列表
          //     this.$router.push({
          //       path: "/system/user/"
          //     });
          //   })
          //   .catch(error => {
          //     this.loading = false;
          //     console.log(error);
          //   });
        } else {
          return;
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
