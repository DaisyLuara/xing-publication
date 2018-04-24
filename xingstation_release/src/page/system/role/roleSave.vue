<template>
  <div class="add-role-wrap">
    <div class="topbar">
      <el-breadcrumb separator="/">
        <el-breadcrumb-item :to="{ path: '/system/role'}">角色管理</el-breadcrumb-item>
        <el-breadcrumb-item>{{roleID ? '修改' : '添加'}}</el-breadcrumb-item>
      </el-breadcrumb>
    </div>
    {{$route.name}}
    <el-form ref="roleForm" :model="roleForm" :rules="rules" label-width="100px" v-loading="allLoading">
      <el-form-item label="角色名称" prop="role.name">
        <el-input class="role-form-input" v-model="roleForm.role.name"></el-input>
      </el-form-item>
      <el-form-item label="显示名称" prop="role.display_name">
        <el-input class="role-form-input" v-model="roleForm.role.display_name"></el-input>
      </el-form-item>
      <el-form-item label="角色描述" prop="role.description">
        <el-input class="role-form-input" v-model="roleForm.role.description"></el-input>
      </el-form-item>
      <el-form-item label="权限">
        <el-table :data="allPerms" border ref="permsTable">
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
                    <el-checkbox v-model="roleForm.perms"  @change="handleChange(thirdChild)" v-for="thirdChild in scope.row.children" v-bind:data="thirdChild" v-bind:key="thirdChild.id" :label="thirdChild.id">{{thirdChild.display_name}}</el-checkbox>
                  </template>
                </el-table-column>
              </el-table>
            </template>
          </el-table-column>
        </el-table>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" :loading="submiting"  @click="onSubmit('roleForm')">保存</el-button>
        <el-button @click="resetForm('roleForm')">重置</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import role from 'service/role'
import { Button, Input, Form, FormItem, Checkbox, CheckboxGroup, Table, TableColumn } from 'element-ui'

export default {
  name: 'addRole',
  data() {
    return {
      roleForm: {
        role: {
          name: "",
          display_name: "",
          description: ""
        },
        perms: []
      },
      roleFormBack: {
        role: {
          name: "",
          display_name: "",
          description: ""
        },
        perms: []
      },
      roleID: '',
      allPerms: [],
      rules: {
        "role.name": [
          { required: true, message: '请输入角色名称', trigger: 'change' }
        ],
        "role.display_name": [
          { required: true, message: '请输入角色显示名称', trigger: 'change' }
        ]
      },
      permsLoading: true,
      submiting: false,
      allLoading: true
    }
  },
  created: function(){
    this.roleID = this.$route.params.rid;
    // 获取所有权限
    role.getManageablePers(this).then(result => {
      let pers = result;
      for(let per in pers){
        this.allPerms.push(pers[per])
      }
      if(this.roleID){
        role.getRoleInfoByRid(this, this.roleID).then(result => {
          let roleInfo = result;
          this.roleForm.role.name = roleInfo.name;
          this.roleForm.role.display_name = roleInfo.display_name;
          this.roleForm.role.description = roleInfo.description;
          for(let i = 0, rL = roleInfo.perms.length; i < rL; i++){
            this.roleForm.perms.push(roleInfo.perms[i].id)
          }
          // back
          this.roleFormBack.role.name = roleInfo.name;
          this.roleFormBack.role.display_name = roleInfo.display_name;
          this.roleFormBack.role.description = roleInfo.description;
          for(let i = 0, rL = roleInfo.perms.length; i < rL; i++){
            this.roleFormBack.perms.push(roleInfo.perms[i].id)
          }

          this.allLoading = false;
          this.permsLoading = false;
        }).catch(error => {
          this.allLoading = false;
          this.permsLoading = false;
          console.log(error)
        })
      }else{
        this.allLoading = false;
        this.permsLoading = false;
      }
    }).catch(error => {
      this.allLoading = false;
      this.permsLoading = false;
      console.log(error)
    })

  },
  methods: {
    handleChange(checkedPerm) {
      let checkedPerms = this.roleForm.perms;
      if(this.roleForm.perms.includes(checkedPerm.id)){
        // 选中: 所有子权限
        this.selectChildPerm(checkedPerm, "select")
        // 选择: 所有父权限
        let parentName = checkedPerm.name.split(".")
        this.selectParentPerm(this.allPerms, parentName, 0)
      }else{
        // 反选:所有子权限
        this.selectChildPerm(checkedPerm, "inselect")
      }
    },
    selectChildPerm(checkedPerm, type){
      let subPerm = [],
          checkedPerms = this.roleForm.perms;
      if(checkedPerm.children && checkedPerm.children.length > 0){
        subPerm = checkedPerm.children;
        for(let i in subPerm){
          switch (type) {
            case "select":
              if(!checkedPerms.includes(subPerm[i].id)){
                checkedPerms.push(subPerm[i].id)
              }
              break;
            case "inselect":
              checkedPerms.find(function(value, index, arr) {
                if(value == subPerm[i].id){
                  arr.splice(index,1)
                }
              })
              break;
            default:
              break;
          }
          this.selectChildPerm(subPerm[i], type)
        }
      }else{
        return false;
      }

    },
    selectParentPerm(parentPerm, permsName, times){
      let checkedPerms = this.roleForm.perms;
      for (let per in parentPerm){
        let nameArry = parentPerm[per].name.split(".")
        if( nameArry[times] == permsName[times]){
          if(!checkedPerms.includes(parentPerm[per].id)){
            checkedPerms.push(parentPerm[per].id)
          }
          if(permsName.length > ++times && parentPerm[per].children && parentPerm[per].children.length > 0){
            this.selectParentPerm(parentPerm[per].children, permsName, times);
          }
          break;
        }
      }
    },
    onSubmit(formName) {
      this.$refs[formName].validate((valid) => {
        if(valid){
          this.submiting = true;
          role.saveRole(this, this[formName], this.roleID).then(result => {
            this.$message({
              message: this.roleID ? "修改成功" : "添加成功",
              type: "success"
            })
            this.submiting = false;
            this.$router.push({
              path: "/system/role/"
            })
          }).catch(error => {
            this.submiting = false;
            console.log(error)
          })
        }else{
          console.log('error submit');
          return;
        }
      })
    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
      this.roleForm.role.name = this.roleFormBack.role.name;
      this.roleForm.role.display_name = this.roleFormBack.role.display_name;
      this.roleForm.role.description = this.roleFormBack.role.description;
      this.roleForm.perms = [];
      for(let i = 0, rL = this.roleFormBack.perms.length; i < rL; i++){
        this.roleForm.perms.push(this.roleFormBack.perms[i])
      }
    }
  },
  components: {
    "el-button": Button,
    "el-input": Input,
    "el-form": Form,
    "el-form-item": FormItem,
    "el-checkbox": Checkbox,
    "el-checkbox-group": CheckboxGroup,
    "el-table": Table,
    "el-table-column": TableColumn
  }
}
</script>
<style scoped lang="less">
  .add-role-wrap{
    .role-form-input{
      width: 385px;
    }
    .el-form-item__content{
      width: 50%;
    }
    .perms-wrap{
      .el-form-item__content{
        width: auto;
      }
    }
    .el-checkbox{
      margin-left: 0px;
      margin-right: 15px;
    }
  }
</style>
