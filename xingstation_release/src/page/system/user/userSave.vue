<template>
  <div class="add-user-wrap">
    <div v-loading="setting.loading" :element-loading-text="setting.loadingText">
      <div class="user-title">{{ $route.name }}</div>
      <el-form ref="userForm" :model="userForm" :rules="rules" label-width="80px" class="userForm">
        <el-form-item label="姓名" prop="user.name">
          <el-input v-model="userForm.user.name" :maxlength="10" class="user-form-input"/>
        </el-form-item>
        <el-form-item label="手机号码" prop="user.phone">
          <el-input
            v-model="userForm.user.phone"
            :maxlength="11"
            placeholder="输入手机号"
            class="user-form-input"
          />
        </el-form-item>
        <el-form-item label="密码" prop="user.password">
          <el-input v-model="userForm.user.password" type="password" class="user-form-input"/>
        </el-form-item>
        <el-form-item label="确认密码" prop="user.repassword">
          <el-input v-model="userForm.user.repassword" type="password" class="user-form-input"/>
        </el-form-item>
        <el-form-item label="角色" prop="user.role_id">
          <el-radio-group v-model="userForm.user.role_id" @change="rolesChange">
            <el-radio
              v-for="role in allRoles"
              :data="role"
              :key="role.id"
              :label="role.id"
              class="role-radio"
            >{{ role.display_name }}</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item v-if="legelFlag" label="主管" prop="user.parent_id">
          <el-select
            v-model="userForm.user.parent_id"
            :loading="searchLoading"
            filterable
            placeholder="请选择主管"
            class="user-form-select"
          >
            <el-option
              v-for="item in custodianList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button :loading="loading" type="primary" @click="onSubmit('userForm')">保存</el-button>
          <el-button @click="historyBack()">取消</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import {
  getUserDetial,
  saveUser,
  historyBack,
  getSearchLegalManagerList,
  getManageableRoles,
  getSearchBDManagerList
} from "service";
import {
  Button,
  Input,
  Form,
  FormItem,
    RadioGroup,
  Select,
  Option,
    Radio
} from "element-ui";

export default {
  name: "AddUser",
  components: {
    "el-button": Button,
    "el-input": Input,
    "el-form": Form,
    "el-form-item": FormItem,
    "el-radio-group": RadioGroup,
    "el-radio": Radio,
    "el-select": Select,
    "el-option": Option
  },
  data() {
    return {
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: "拼命加载中"
      },
      searchLoading: false,
      custodianList: [],
      userForm: {
        user: {
          name: "",
          parent_id: null,
          phone: "",
          password: "",
          role_id: 0,
          repassword: ""
        }
      },
      id: null,
      panelVisible: false,
      userID: "",
      allRoles: [],
      rules: {
        "user.phone": [
          {
            validator: (rule, value, callback) => {
              if (/^\s*$/.test(value)) {
                callback("请输入手机");
              } else if (!/^1[3456789]\d{9}$/.test(value)) {
                callback("手机格式不正确,请重新输入");
              } else {
                callback();
              }
            },
            trigger: "blur",
            required: true
          }
        ],
        "user.name": [
          { message: "请输入姓名", trigger: "blur", required: true }
        ],
        "user.password": [
          {
            validator: (rule, value, callback) => {
              if (/^\s*$/.test(value)) {
                callback("请输入密码");
              } else if (!/^.{8,20}$/.test(value)) {
                callback("密码长度不正确,请重新输入");
              } else {
                callback();
              }
            },
            trigger: "blur"
          }
        ],
        "user.repassword": [
          {
            validator: (rule, value, callback) => {
              if (value === "") {
                callback(new Error("请再次输入密码"));
              } else if (value !== this.userForm.user.password) {
                callback(new Error("两次输入密码不一致!"));
              } else {
                callback();
              }
            },
            trigger: "blur"
          }
        ],
        "user.role_id": [
          {
            message: "请选择角色",
            trigger: "change",
            required: true,
            type: "number"
          }
        ]
      },
      legelFlag: false,
      loading: false
    };
  },
  created: function() {
    if (this.setting.loading == true) {
      return false;
    }
    this.userID = this.$route.params.uid;
    this.setting.loadingText = "拼命加载中";
    this.setting.loading = true;
    // 获取当前用户可分配的角色
    let rolesPromise = getManageableRoles(this)
      .then(result => {
        this.allRoles = result.data;
      })
      .catch(error => {
        console.log(error);
      });
    Promise.all([rolesPromise]).then(() => {
      if (this.userID) {
        let args = {
          include: "roles"
        };
        getUserDetial(this, this.userID, args)
          .then(result => {
            this.userForm.user.phone = result.phone;
            this.userForm.user.name = result.name;
            let role = result.roles.data[0];
            this.id = result.id;
            this.userForm.user.role_id = role.id;
            if (role.display_name === "BD" || role.display_name === "法务") {
              role.display_name === "BD"
                ? this.getBDManagerList()
                : this.getLegalManagerList();
            }
            this.userForm.user.parent_id = result.parent_id;
            if (role.display_name === "BD" || role.display_name === "法务") {
              this.legelFlag = true;
            }
            if (
              role.display_name === "BD主管" ||
              role.display_name === "法务主管"
            ) {
              this.userForm.user.parent_id = this.id;
            }
            this.setting.loading = false;
          })
          .catch(error => {
            console.log(error);
          });
      } else {
        this.setting.loading = false;
      }
    });
  },
  methods: {
    rolesChange(val) {
      this.allRoles.map(r => {
        if (r.id === val) {
          if (r.display_name === "BD" || r.display_name === "法务") {
            r.display_name === "BD"
              ? this.getBDManagerList()
              : this.getLegalManagerList();
            this.legelFlag = true;
            return;
          } else {
            this.legelFlag = false;
            this.userForm.user.parent_id = null;
          }
          if (r.display_name === "BD主管" || r.display_name === "法务主管") {
            this.userForm.user.parent_id = this.id;
          }
        }
      });
    },
    getBDManagerList() {
      this.searchLoading = true;
      getSearchBDManagerList(this)
        .then(res => {
          this.custodianList = res.data;
          this.searchLoading = false;
        })
        .catch(err => {
          this.searchLoading = false;
        });
    },
    getLegalManagerList() {
      this.searchLoading = true;
      getSearchLegalManagerList(this)
        .then(res => {
          this.custodianList = res.data;
          this.searchLoading = false;
        })
        .catch(err => {
          this.searchLoading = false;
        });
    },
    onSubmit(formName) {
      if (
        this.userID &&
        !this.userForm.user.password &&
        !this.userForm.user.repassword
      ) {
        delete this.rules["user.password"];
        delete this.rules["user.repassword"];
        delete this[formName].user.password;
      }
      this.$refs[formName].validate(valid => {
        if (valid) {
          delete this[formName].user.repassword;
          this.loading = true;
          saveUser(this, this[formName].user, this.userID)
            .then(result => {
              this.loading = false;
              this.$message({
                message: this.userID ? "修改成功" : "添加成功",
                type: "success"
              });
              // todo是否返回用户列表
              this.$router.push({
                path: "/system/user/"
              });
            })
            .catch(error => {
              this.loading = false;
              console.log(error);
            });
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
.add-user-wrap {
  .user-form-input,
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
  .userForm {
    width: 800px;
  }
  .user-title {
    margin-bottom: 20px;
  }
  .el-checkbox {
    margin-left: 0px;
    margin-right: 15px;
  }
}
</style>
