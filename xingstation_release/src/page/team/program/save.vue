<template>
  <div 
    class="item-wrap-template">
    <div 
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText" 
      class="pane">
      <div 
        class="pane-title">
        {{ programID ? (role.name==='project-manager'? '修改项目' : '查看项目') : '新增项目'}}
      </div>
      <el-form
        ref="programForm"
        :model="programForm"
        label-position="left"
        label-width="80px">
        <el-row>
          <el-col :span="12">
            <el-form-item 
              label="节目名称" 
              prop="belong" 
              :rules="[{ required: true, message: '请输入节目名称', trigger: 'submit' }]">
              <el-select 
                v-model="programForm.belong" 
                :loading="searchLoading"
                remote
                :remote-method="getProject"
                placeholder="请输入节目名称" 
                filterable 
                clearable>
                <el-option
                  v-for="item in projectList"
                  :key="item.alias"
                  :label="item.name"
                  :value="item.alias + ',' + item.name"/>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item 
              label="申请人" 
              prop="applicant_name" >
              <el-input 
                v-model="programForm.applicant_name" 
                :disabled="true"
                :maxlength="50"
                class="item-input"/>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item 
              label="节目属性" 
              prop="project_attribute" >
              <el-radio-group v-model="programForm.project_attribute">
                <el-radio :label="1">基础条目</el-radio>
                <el-radio :label="2">通用节目</el-radio>
                <el-radio :label="3">定制节目</el-radio>
                <el-radio :label="4">定制项目</el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item 
              label="联动属性" 
              prop="link_attribute" >
              <el-radio-group v-model="programForm.link_attribute">
                <el-radio :label="1">是</el-radio>
                <el-radio :label="0">否</el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item 
              label="H5属性" 
              prop="h5_attribute">
              <el-radio-group 
                v-model="programForm.h5_attribute"
                @change="h5Handle">
                <el-radio :label="1">基础模版</el-radio>
                <el-radio :label="2">复杂模版</el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item 
              label="小偶属性" 
              prop="xo_attribute" >
              <el-radio-group v-model="programForm.xo_attribute">
                <el-radio :label="1">是</el-radio>
                <el-radio :label="0">否</el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item 
              label="交互技术" 
              prop="interactionVal" >
              <span style="color: #999;font-size:14px;">{{ interactionRate }} * 系数</span>
              <el-select 
                v-model="programForm.interactionVal" 
                :loading="searchLoading"
                :multiple-limit="5"
                multiple
                collapse-tags
                placeholder="请添加人员" 
                filterable 
                clearable
                @change="peopleHandle($event,interactionRate,'interaction')">
                <el-option
                  v-for="item in userList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"/>
              </el-select>
              <el-button 
                type="text"
                size="mini"
                @click="modifyHandle(programForm.interaction,interactionRate,'interaction')">修改</el-button>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item 
              label="节目创意" 
              prop="creative" >
              <span style="color: #999;font-size:14px;">{{ creativeRate }} * 系数</span>
              <el-select 
                v-model="programForm.creative" 
                :loading="searchLoading"
                :multiple-limit="5"
                multiple
                collapse-tags
                placeholder="请添加人员" 
                filterable 
                clearable
                @change="peopleHandle($event,creativeRate,'creative')">
                <el-option
                  v-for="item in userList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"/>
              </el-select>
              <el-button 
                type="text" 
                size="mini"
                @click="modifyHandle(programForm.originality,creativeRate,'creative')">修改</el-button>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item 
              label="H5开发" 
              prop="H5Val" >
              <span style="color: #999;font-size:14px;">{{ h5Rate }} * 系数</span>
              <el-select 
                v-model="programForm.H5Val" 
                :loading="searchLoading"
                :multiple-limit="5"
                multiple
                placeholder="请添加人员" 
                filterable 
                clearable
                collapse-tags
                @change="peopleHandle($event,h5Rate,'H5')">
                <el-option
                  v-for="item in userList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"/>
              </el-select>
              <el-button
                type="text" 
                size="mini"
                @click="modifyHandle(programForm.h5,h5Rate,'H5')">修改</el-button>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item 
              label="设计动画" 
              prop="animate" >
              <span style="color: #999;font-size:14px;">{{ animateRate }} * 系数</span>
              <el-select 
                v-model="programForm.animate" 
                :loading="searchLoading"
                :multiple-limit="5"
                multiple
                placeholder="请添加人员" 
                filterable 
                collapse-tags
                clearable
                @change="peopleHandle($event,animateRate,'animate')">
                <el-option
                  v-for="item in userList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"/>
              </el-select>
              <el-button 
                type="text" 
                size="mini"
                @click="modifyHandle(programForm.animation,animateRate,'animate')">修改</el-button>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item 
              label="节目统筹" 
              prop="whole" >
              <span style="color: #999;font-size:14px;">{{ wholeRate }} * 系数</span>
              <el-select 
                v-model="programForm.whole" 
                :loading="searchLoading"
                :multiple-limit="5"
                multiple
                placeholder="请添加人员" 
                filterable 
                clearable
                collapse-tags
                @change="peopleHandle($event,wholeRate,'whole')">
                <el-option
                  v-for="item in userList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"/>
              </el-select>
              <el-button 
                type="text" 
                size="mini"
                @click="modifyHandle(programForm.plan,wholeRate,'whole')">修改</el-button>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item 
              label="节目测试" 
              prop="test" >
              <span style="color: #999;font-size:14px;">{{ testRate }} * 系数</span>
              <el-select 
                v-model="programForm.test" 
                :loading="searchLoading"
                :multiple-limit="5"
                multiple
                placeholder="请添加人员" 
                filterable 
                collapse-tags
                clearable
                @change="peopleHandle($event,testRate,'test')">
                <el-option
                  v-for="item in userList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"/>
              </el-select>
              <el-button 
                type="text" 
                size="mini"
                @click="modifyHandle(programForm.tester,testRate,'test')">修改</el-button>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item 
              label="平台运营" 
              prop="platform" >
              <span style="color: #999;font-size:14px;">{{ platformRate }} * 系数</span>
              <el-select 
                v-model="programForm.platform" 
                :loading="searchLoading"
                :multiple-limit="5"
                multiple
                collapse-tags
                placeholder="请添加人员" 
                filterable 
                clearable
                @change="peopleHandle($event,platformRate,'platform')">
                <el-option
                  v-for="item in userList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"/>
              </el-select>
              <el-button 
                type="text" 
                size="mini"
                @click="modifyHandle(programForm.operation,platformRate,'platform')">修改</el-button>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="24">
            <el-form-item 
              label="项目说明" 
              prop="remark">
              <el-input
                v-model="programForm.remark"
                :autosize="{ minRows: 2, maxRows: 4}"
                :maxlength="400"
                type="textarea"
                placeholder="请填写项目说明"
                class="text-input"/>
            </el-form-item>
          </el-col>
        </el-row>
        <el-form-item>
          <!-- 产品经理可以保存 -->
          <el-button
            v-if="role.name === 'project-manager'" 
            type="primary"
            @click="submit('programForm')">保存</el-button>
          <el-button @click="historyBack">返回</el-button>
        </el-form-item>
      </el-form>
    </div>
    <!-- 修改比列 -->
    <el-dialog 
      :visible.sync="dialogFormVisible"
      :show-close="false"
      title="绩效更改">
      <el-form 
        :model="form"
        label-width="90px">
        <el-form-item label="总点数">
          <el-input 
            v-model="form.total" 
            :disabled="disabledChange"/>
        </el-form-item>
        <el-form-item 
          v-for="item in peopleList"
          :key="item.id" 
          :label="item.user_name">
          <el-input v-model="item.rate"/>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false,disabledChange = true">取 消</el-button>
        <el-button type="primary" @click="rateSubmit">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import {
  Form,
  Select,
  Option,
  FormItem,
  Button,
  Input,
  Row,
  MessageBox,
  RadioGroup,
  Radio,
  Col,
  Dialog,
  Tooltip
} from 'element-ui'
import search from 'service/search'
import {
  saveProgram,
  historyBack,
  getProgramDetails,
  modifyProgram
} from 'service'
import { Cookies } from 'utils/cookies'

export default {
  components: {
    ElRow: Row,
    ElCol: Col,
    ElForm: Form,
    ElSelect: Select,
    ElOption: Option,
    ElFormItem: FormItem,
    ElButton: Button,
    ElInput: Input,
    ElRadioGroup: RadioGroup,
    ElRadio: Radio,
    ElDialog: Dialog,
    ElTooltip: Tooltip
  },
  data() {
    return {
      disabledChange: true,
      form: {
        total: 0
      },
      peopleList: [],
      dialogFormVisible: false,
      projectList: [],
      searchLoading: false,
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: '拼命加载中'
      },
      programID: '',
      programForm: {
        applicant: '',
        belong: '',
        remark: '',
        applicant_name: '',
        project_attribute: 1,
        link_attribute: 0,
        h5_attribute: 1,
        xo_attribute: 0,
        interactionVal: [],
        H5Val: [],
        creative: [],
        platform: [],
        test: [],
        animate: [],
        whole: [],
        operation: [],
        interaction: [],
        originality: [],
        h5: [],
        plan: [],
        animation: [],
        tester: []
      },
      type: '',
      userList: [],
      interactionRate: 0.3,
      creativeRate: 0.1,
      h5Rate: 0.025,
      testRate: 0.12,
      platformRate: 0.04,
      animateRate: 0.3,
      wholeRate: 0.06,
      role: null
    }
  },
  created() {
    this.programID = this.$route.params.uid
    let user_info = JSON.parse(Cookies.get('user_info'))
    this.role = user_info.roles.data[0]
    this.getUserList()
    if (this.programID) {
      this.getProgramDetails()
    } else {
      this.programForm.applicant_name = user_info.name
      this.programForm.applicant = user_info.id
    }
  },
  methods: {
    getProgramDetails() {
      this.setting.loading = true
      getProgramDetails(this, this.programID)
        .then(res => {
          this.programForm.applicant = res.applicant
          this.programForm.applicant_name = res.applicant_name
          this.getProject(res.project_name)
          this.programForm.belong = res.belong + ',' + res.project_name
          this.programForm.link_attribute = res.link_attribute
          this.programForm.h5_attribute = res.h5_attribute
          this.programForm.project_attribute = res.project_attribute
          this.programForm.xo_attribute = res.xo_attribute
          this.programForm.animation = res.member.animation
          this.programForm.h5 = res.member.h5
          this.programForm.interaction = res.member.interaction
          this.programForm.originality = res.member.originality
          this.programForm.operation = res.member.operation
          this.programForm.plan = res.member.plan
          this.programForm.tester = res.member.tester
          this.programForm.remark = res.remark
          if (res.member.animation.length > 0) {
            res.member.animation.map(r => {
              this.programForm.animate.push(r.user_id)
            })
          }
          if (res.member.plan.length > 0) {
            res.member.plan.map(r => {
              this.programForm.whole.push(r.user_id)
            })
          }
          if (res.member.interaction.length > 0) {
            res.member.interaction.map(r => {
              this.programForm.interactionVal.push(r.user_id)
            })
          }
          if (res.member.h5.length > 0) {
            res.member.h5.map(r => {
              this.programForm.H5Val.push(r.user_id)
            })
          }
          if (res.member.tester.length > 0) {
            res.member.tester.map(r => {
              this.programForm.test.push(r.user_id)
            })
          }
          if (res.member.operation.length > 0) {
            res.member.operation.map(r => {
              this.programForm.platform.push(r.user_id)
            })
          }
          if (res.member.originality.length > 0) {
            res.member.originality.map(r => {
              this.programForm.creative.push(r.user_id)
            })
          }
          this.setting.loading = false
        })
        .catch(err => {
          this.$message({
            type: 'warning',
            message: err.response.data.message
          })
          this.setting.loading = false
        })
    },
    // 自定义比列修改modifyHandle，rateSubmit，performanceChange
    modifyHandle(obj, rate, type) {
      let length = obj.length
      this.form.total = rate
      this.peopleList = []
      this.dialogFormVisible = true
      this.type = type
      if (length > 0) {
        switch (type) {
          case 'interaction':
            this.peopleList = JSON.parse(JSON.stringify(this.programForm.interaction))
            break
          case 'creative':

            this.peopleList = JSON.parse(JSON.stringify(this.programForm.originality))
            break
          case 'H5':
            this.peopleList = JSON.parse(JSON.stringify(this.programForm.h5))
            break
          case 'animate':
            this.peopleList = JSON.parse(JSON.stringify(this.programForm.animation))
            break
          case 'whole':
            this.peopleList = JSON.parse(JSON.stringify(this.programForm.plan))
            break
          case 'test':
            this.peopleList = JSON.parse(JSON.stringify(this.programForm.tester))
            break
          case 'platform':
            this.peopleList = JSON.parse(JSON.stringify(this.programForm.operation))
            break
        }
      }
    },
    rateSubmit() {
      let type = this.type
      let sum = 0
      switch (type) {
        case 'interaction':
          this.interactionRate = this.form.total
          this.performanceChange('interaction', sum)
          break
        case 'creative':
          this.creativeRate = this.form.total
          this.performanceChange('originality', sum)
          break
        case 'H5':
          this.h5Rate = this.form.total
          this.performanceChange('h5', sum)
          break
        case 'animate':
          this.animateRate = this.form.total
          this.performanceChange('animation', sum)
          break
        case 'whole':
          this.wholeRate = this.form.total
          this.performanceChange('plan', sum)
          break
        case 'test':
          this.testRate = this.form.total
          this.performanceChange('tester', sum)
          break
        case 'platform':
          this.platformRate = this.form.total
          this.performanceChange('operation', sum)
          break
      }
      this.disabledChange = true
      this.dialogFormVisible = false
    },
    performanceChange(name, sum) {
      this.peopleList.map(r => {
        sum += parseFloat(r.rate)
      })
      if (sum <= this.form.total) {
        this.programForm[name] = this.peopleList
      } else {
        this.$message({
          type: 'warning',
          message: '绩效比例不正确'
        })
      }
    },
    // h5比列
    h5Handle(val) {
      this.h5Rate = val === 1 ? 0.025 : 0.1
    },
    // 添加人员 均分比列
    peopleHandle(val, rate, type) {
      let length = val.length
      if (length > 0) {
        let averageRate = (rate / length).toFixed(4)
        switch (type) {
          case 'interaction':
            this.addRate('interaction', val, averageRate)
            break
          case 'creative':
            this.addRate('originality', val, averageRate)
            break
          case 'H5':
            this.addRate('h5', val, averageRate)
            break
          case 'animate':
            this.addRate('animation', val, averageRate)
            break
          case 'whole':
            this.addRate('plan', val, averageRate)
            break
          case 'test':
            this.addRate('tester', val, averageRate)
            break
          case 'platform':
            this.addRate('operation', val, averageRate)
            break
        }
      }
    },
    addRate(obj, val, averageRate) {
      this.programForm[obj] = []
      val.map(r => {
        this.userList.filter(item => {
          if (item.id === r) {
            this.programForm[obj].push({
              user_id: item.id,
              user_name: item.name
            })
          }
        })
      })
      this.programForm[obj].map(i => {
        i.rate = averageRate
      })
    },
    getUserList() {
      this.searchLoading = true
      return search
        .getUserList(this)
        .then(response => {
          this.userList = response.data
          this.searchLoading = false
        })
        .catch(err => {
          this.searchLoading = false
        })
    },
    getProject(query) {
      if (query !== '') {
        this.searchLoading = true
        let args = {
          name: query
        }
        return search
          .getProjectList(this, args)
          .then(response => {
            this.projectList = response.data
            if (this.projectList.length == 0) {
              this.projectList = []
            }
            this.searchLoading = false
          })
          .catch(err => {
            this.searchLoading = false
          })
      } else {
        this.projectList = []
      }
    },
    submit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          this.setting.loading = true
          let member = {}
          let args = {
            belong: this.programForm.belong.split(',')[0],
            project_name: this.programForm.belong.split(',')[1],
            applicant: this.programForm.applicant,
            project_attribute: this.programForm.project_attribute,
            link_attribute: this.programForm.link_attribute,
            h5_attribute: this.programForm.h5_attribute,
            xo_attribute: this.programForm.xo_attribute,
            remark: this.programForm.remark
          }
          if (this.programForm.interaction.length > 0) {
            member.interaction = this.programForm.interaction
          }
          if (this.programForm.originality.length > 0) {
            member.originality = this.programForm.originality
          }
          if (this.programForm.h5.length > 0) {
            member.h5 = this.programForm.h5
          }
          if (this.programForm.animation.length > 0) {
            member.animation = this.programForm.animation
          }
          if (this.programForm.plan.length > 0) {
            member.plan = this.programForm.plan
          }
          if (this.programForm.tester.length > 0) {
            member.tester = this.programForm.tester
          }
          if (this.programForm.operation.length > 0) {
            member.operation = this.programForm.operation
          }
          args.member = member
          if (this.programID) {
            modifyProgram(this, args, this.programID)
              .then(res => {
                this.$message({
                  message: '修改成功',
                  type: 'success'
                })
                this.$router.push({
                  path: '/team/program'
                })
                this.setting.loading = false
              })
              .catch(err => {
                this.setting.loading = false
                this.$message({
                  message: err.response.data.message,
                  type: 'warning'
                })
              })
          } else {
            saveProgram(this, args)
              .then(res => {
                this.$message({
                  message: '提交成功',
                  type: 'success'
                })
                this.$router.push({
                  path: '/team/program'
                })
                this.setting.loading = false
              })
              .catch(err => {
                this.setting.loading = false
                this.$message({
                  message: err.response.data.message,
                  type: 'warning'
                })
              })
          }
        }
      })
    },
    historyBack() {
      historyBack()
    }
  }
}
</script>

<style lang="less" scoped>
.item-wrap-template {
  .pane {
    border-radius: 5px;
    background-color: white;
    padding: 20px 40px 80px;
    .el-select,
    .item-input,
    .payment-time,
    .el-date-editor.el-input {
      width: 300px;
    }
    .text-input {
      width: 70%;
    }

    .pane-title {
      padding-bottom: 20px;
      font-size: 18px;
      display: flex;
      flex-direction: row;
      justify-content: space-between;
    }
  }
  .hint {
    font-size: 20px;
  }
  .modify-change {
    text-align: right;
    color: #0c98d7;
    cursor: pointer;
    font-weight: 600;
    font-size: 14px;
  }
}
</style>
