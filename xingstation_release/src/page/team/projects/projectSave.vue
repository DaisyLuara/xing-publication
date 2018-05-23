<template>
  <div class="item-wrap-template">
    <div class="team-project-wrap" v-loading="loading">
      <el-card class="box-card">
        <h3 class="project-headline">
          创建项目
        </h3>
        <div class="project-content-wrap">
          <el-form ref="form" :model="form" label-width="20px">
            <el-form-item prop="name" label=" " :rules="[{ required: true, message: '请输入项目名称', trigger: 'submit'}]">
              <el-input v-model="form.name" placeholder="项目名称" style="width: 548px;font-size: 18px;font-weight: 500;color: #444;"></el-input>
            </el-form-item>
            <el-form-item prop="desc">
              <el-input
                type="textarea"
                placeholder="简单描述项目，便于其他人理解（选填）"
                :autosize="{ minRows: 4, maxRows: 4}"
                resize="none"
                v-model="form.desc"  style="width: 548px;font-size: 12px;font-weight: 500;color: #444;">
              </el-input>
            </el-form-item>
            <!-- <el-form-item prop="type">
              <h4 class="project-type">项目类型</h4>
                <div>
                  <el-radio label="看板项目" name="type" v-model="form.type" style="font-size:16px;">
                    <span class="label">看板项目</span><span class="note">更好地组织、细分和管理任务，适用于一般项目管理</span>
                  </el-radio>
                </div>
                <div>
                  <el-radio label="标准项目" name="type" v-model="form.type">
                    <span class="label">标准项目</span><span class="note">擅长处理流程化任务，适用于产品研发、用户支持等场景</span>
                  </el-radio>
                </div>
            </el-form-item>
            <el-form-item prop="type">
              <h4 class="project-type">项目公开性</h4>
                <div>
                  <el-radio label="私有项目" name="openness" v-model="form.openness" style="font-size:16px;">
                    <span class="label">私有项目</span><span class="note">仅项目成员可以查看和编辑该项目</span>
                  </el-radio>
                </div>
                <div>
                  <el-radio label="公开项目" name="openness" v-model="form.openness">
                    <span class="label">公开项目</span><span class="note">任何人都可以通过链接查看该项目，仅项目成员可以编辑该项目</span>
                  </el-radio>
                </div>
            </el-form-item> -->
            <!-- <el-form-item prop="type">
              <h4 class="project-type">项目分组</h4>
                 <el-checkbox-group v-model="form.grouping">
                  <el-checkbox :label="item.id" v-for="(item, index) in projectList" :key="item.id"><span class="label">{{item.attributes.name}}</span></el-checkbox>
                </el-checkbox-group>
            </el-form-item> -->
            <el-form-item prop="type">
              <h4 class="project-type">选择项目成员</h4>
              <span class="note">管理员可以邀请和移除项目成员，只有被邀请的团队成员才能访问该项目的信息。</span>
              <div class="btn-group">
                <el-select v-model="value" placeholder="请添加成员" size="mini" clearable @change="addMember">
                  <el-option
                    v-for="item in membersList"
                    :key="item.id"
                    :label="item.attributes.nickname"
                    :value="item.id">
                  </el-option>
                </el-select>
                <el-checkbox-button :indeterminate="isIndeterminate" v-model="checkAll" @change="handleCheckAllChange">所有人</el-checkbox-button>
                <el-checkbox-group v-model="checkedGroup"  @change="chooseGroupMember" >
                  <el-checkbox-button v-for="item in groupList" :label="item.id" :key="item.id" v-if="item.id !== 'c6dc912c2f494e7ea73bed4488bb3493'">{{item.attributes.name}}</el-checkbox-button>
                </el-checkbox-group>
              </div>
               <el-checkbox-group v-model="form.projectMembers">
                <el-checkbox :label="item.id" class="project-member" v-for="(item, index) in membersList" :key=item.id><span class="label">{{item.attributes.nickname}}</span></el-checkbox>
              </el-checkbox-group>
            </el-form-item>
            <el-form-item>
              <el-button type="success" size="small" style="background: #67c23a;color: #fff;" @click="submit('form')">创建项目</el-button>
              <el-button size="small" style="color: #888;background: #fff;" @click="cancleHandle">取消</el-button>
            </el-form-item>
          </el-form>
        </div>
      </el-card>
    </div>
  </div>
</template>
<script>
import team from 'service/team' 
import {Card, Form, FormItem, Input, Radio, Checkbox, CheckboxGroup, Button, Select, Option, CheckboxButton, MessageBox} from 'element-ui'
export default {
  data() {
    return {
      value: '',
      loading: false,
      groupList: [],
      groupOptions: [],
      membersList: [],
      projectList: [],
      checkedGroup: [],
      selectList: [],
      checkAll: false,
      isIndeterminate: false,
      form: {
        name: '',
        desc: '',
        // type: '看板项目',
        // grouping: [],
        // openness: '公开项目',
        projectMembers: []
      }
    }
  },
  mounted() {
  },
  created() {
    this.getProjectsList()
    this.getMembersList()
  },
  methods: {
    submit(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          let id = 'c6dc912c2f494e7ea73bed4488bb3493'
          let args = {
            "project": {
              "name": this.form.name,
              "desc": this.form.desc,
              "member_ids": this.form.projectMembers
            }
          }
          console.log(this.form)
          console.log(args)
          console.log('submit')
          return team.saveProjects(this, args ,id).then((response) => {
            console.log(response)
            this.$message({
              message: "创建成功",
              type: "success"
            })
            this.$router.push({
              path: '/team/projects/index'
            })
          }).catch((err) => {
            console.log(err)
          })
        } else {
          console.log('error')
        }
      })

    },
    addMember() {
      this.selectList.push(this.value)
      this.form.projectMembers.push(this.value)
    },
    handleCheckAllChange(val) {
      this.checkedGroup = val ? this.groupOptions : [];
      this.isIndeterminate = false;
      this.chooseGroupMember()
    },
    chooseGroupMember() {
      let checkedCount = this.checkedGroup.length;
      this.checkAll = checkedCount === this.groupOptions.length;
      this.isIndeterminate = checkedCount > 0 && checkedCount < this.groupOptions.length;
      this.form.projectMembers = []
      for(let h = 0; h < this.selectList.length; h++) {
        this.form.projectMembers.push(this.selectList[h])
      }
      for ( let k = 0;k < this.checkedGroup.length; k++) {
        for (let i = 0;i < this.membersList.length; i++) {
          if(!this.isIndeterminate) {
              this.form.projectMembers.push(this.membersList[i].id);
            } else {
              for (let j= 0;j < this.membersList[i].relationships.groups.data.length; j++) {
                if (this.checkedGroup[k] === this.membersList[i].relationships.groups.data[j].id) {
                  this.form.projectMembers.push(this.membersList[i].id);
                  break;
                }
              }
          }
        }
      }
    },
    getMembersList () {
      this.loading = true
      let id = 'c6dc912c2f494e7ea73bed4488bb3493'
      team.getTowerList(this, id).then((response) => {
        if(response){
          this.membersList = response.data;
          this.groupList = response.included;
          for(let i = 0;i<this.groupList.length; i++) {
            if (this.groupList[i].id !== 'c6dc912c2f494e7ea73bed4488bb3493') {
              this.groupOptions.push(this.groupList[i].id)
            }
          }
          this.loading = false
        }
      }).catch(error => {
        this.loading = false
        console.log(error)
      })
    },
    getProjectsList() {
      let id = 'c6dc912c2f494e7ea73bed4488bb3493'
      team.getProjectsList(this, id).then((response) => {
        if(response){
          this.projectList = response.included;
        }
      }).catch(error => {
        console.log(error)
      })
    },
    cancleHandle() {
      this.$router.push({
        path: '/team/projects/index'
      })
    }
  },
  components: {
    ElCheckboxButton: CheckboxButton,
    ElCard: Card,
    ElSelect: Select,
    ElOption: Option,
    ElForm: Form,
    ElFormItem: FormItem,
    ElInput: Input,
    ElRadio: Radio,
    ElButton: Button,
    ElCheckbox: Checkbox,
    ElCheckboxGroup: CheckboxGroup,
  }
}
</script>
<style lang="less" scoped>
  .item-wrap-template{
    background: #fff;
    .team-project-wrap{
      padding: 30px 0;
      position: relative;
      width: 962px;
      margin: 0 auto;
      .project-headline {
        margin: 10px 15px;
        font-size: 21px;
        font-weight: 500;
      }
      .project-content-wrap{
        .el-button{
          background: #EBEBEB;
          border: 1px solid #EBEBEB;
          color: #999;
        }
        .el-button:hover{
          color: #606266;
          border-color: #EBEBEB;
        }
        .el-button:focus,.el-button:active {
          color: #606266;
          border-color: #EBEBEB;
        }
        .el-button--mini.is-round{
          padding: 4px 15px;
        }
        padding: 10px 0;
        .project-type{
          font-size: 16px;
          font-weight: 500;
        }
        .project-member{
          width: 170px;
          margin-right: 10px;
          margin-left: 0;
        }
        .label{
          font-size: 16px;
          color: #444;
        }
        .note{
          color: #999;
          font-size: 14px;
          margin-left: 5px;
        }
      }
    }
  }
</style>

