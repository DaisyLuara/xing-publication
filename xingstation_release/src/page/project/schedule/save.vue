<template>
  <div class="root">
    <div class="account-wrap">
      <div class="item-info">
        <div class="prize-title">{{ templateId ? '子条目修改' : '子条目新增' }}</div>
        <el-form ref="templateForm" :model="templateForm" label-width="180px">
          <el-form-item
            :rules="[{ required: true, message: '请选择节目', trigger: 'submit'}]"
            label="节目名称"
            prop="project_id"
          >
            <el-select
              v-model="templateForm.project_id"
              :loading="searchLoading"
              :remote-method="getProject"
              filterable
              placeholder="请搜索"
              remote
              clearable
              @change="handleProject"
            >
              <el-option
                v-for="item in projectList"
                :key="item.id"
                :label="item.name"
                :value="item.id"
              />
            </el-select>
          </el-form-item>
          <el-form-item
            :rules="[{ required: true, message: '请选择皮肤', trigger: 'submit'}]"
            label="皮肤"
            prop="bid"
          >
            <el-select
              v-model="templateForm.bid"
              :loading="searchLoading"
              placeholder="请选择节目"
              filterable
              clearable
            >
              <el-option
                v-for="item in skinList"
                :key="item.bid"
                :label="item.name"
                :value="item.bid"
              />
            </el-select>
          </el-form-item>
          <el-form-item
            :rules="[{ required: true, message: '请填写开始时间', trigger: 'submit'}]"
            label="开始时间"
            prop="date_start"
          >
            <el-time-select
              v-model="templateForm.date_start"
              :picker-options="{
                  start: '00:00',
                  step: '00:01',
                  end: '23:59'
                }"
              placeholder="开始时间"
              format="HH:mm"
            />
          </el-form-item>
          <el-form-item
            :rules="[{ required: true, message: '请填写结束时间', trigger: 'submit'}]"
            label="结束时间"
            prop="date_end"
          >
            <el-time-select
              v-model="templateForm.date_end"
              :picker-options="{
                  start: '00:00',
                  step: '00:01',
                  end: '23:59'
                }"
              placeholder="结束时间"
              format="HH:mm"
            />
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="submit('templateForm')">保存</el-button>
            <el-button @click="back">返回</el-button>
          </el-form-item>
        </el-form>
      </div>
    </div>
  </div>
</template>
<script>
import {
  historyBack,
  getSearchSkin,
  saveSchedule,
  modifySchedule,
  scheduleDetail,
  getSearchProjectList
} from "service";
import {
  Form,
  Select,
  Option,
  FormItem,
  Button,
  Input,
  TimeSelect,
  MessageBox
} from "element-ui";
export default {
  components: {
    ElForm: Form,
    ElSelect: Select,
    ElOption: Option,
    ElFormItem: FormItem,
    ElTimeSelect: TimeSelect,
    ElButton: Button,
    ElInput: Input
  },
  data() {
    return {
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      skinList: [],
      templateId: null,
      searchLoading: false,
      templateForm: {
        date_start: "",
        date_end: "",
        bid: null,
        project_id: null
      },
      projectList: [],
      cid: null
    };
  },
  created() {
    this.templateId = this.$route.params.uid;
    this.pid = this.$route.query.pid;
    if (this.templateId) {
      this.scheduleDetail();
    }
  },

  methods: {
    handleProject(val) {
      this.getSkin(val);
    },
    getProject(query) {
      if (query !== "") {
        this.searchLoading = true;
        let args = {
          name: query
        };
        return getSearchProjectList(this, args)
          .then(response => {
            this.projectList = response.data;
            if (this.projectList.length == 0) {
              this.projectList = [];
            }
            this.searchLoading = false;
          })
          .catch(err => {
            console.log(err);
            this.searchLoading = false;
          });
      } else {
        this.projectList = [];
      }
    },
    scheduleDetail() {
      this.setting.loading = true;
      scheduleDetail(this, this.templateId, this.pid)
        .then(res => {
          this.templateForm.date_end = res.date_end;
          this.templateForm.date_start = res.date_start;
          this.templateForm.bid = res.skin.bid;
          this.templateForm.project_id = res.project.id;
          this.getProject(res.project.name);
          this.handleProject(res.project.id);
          this.setting.loading = false;
        })
        .catch(err => {
          this.setting.loading = false;
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    submit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          let args = {
            bid: this.templateForm.bid,
            date_start: this.templateForm.date_start,
            date_end: this.templateForm.date_end,
            project_id: this.templateForm.project_id
          };
          if (this.templateId) {
            modifySchedule(this, this.templateId, this.pid, args)
              .then(response => {
                this.$message({
                  message: "修改成功",
                  type: "success"
                });
                this.$router.push({
                  path: "/project/template/schedule",
                  query: {
                    pid: this.pid
                  }
                });
                this.setting.loading = false;
              })
              .catch(err => {
                this.$message({
                  type: "warning",
                  message: err.response.data.message
                });
                this.setting.loading = false;
              });
          } else {
            saveSchedule(this, this.pid, args)
              .then(response => {
                this.$message({
                  message: "添加成功",
                  type: "success"
                });
                this.$router.push({
                  path: "/project/template/schedule",
                  query: {
                    pid: this.pid
                  }
                });
                this.setting.loading = false;
              })
              .catch(err => {
                this.$message({
                  type: "warning",
                  message: err.response.data.message
                });
                this.setting.loading = false;
              });
          }
        }
      });
    },
    getSkin(val) {
      let args = {
        project_id: val
      };
      getSearchSkin(this, args)
        .then(result => {
          this.skinList = result;
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    back() {
      historyBack();
    }
  }
};
</script>
<style lang="less" scoped>
.root {
  background: #fff;
  padding: 20px;
  .prize-title {
    font-size: 18px;
    margin: 15px 0 15px 15px;
  }
  .el-select,
  .el-input {
    width: 300px;
  }
}
</style>

