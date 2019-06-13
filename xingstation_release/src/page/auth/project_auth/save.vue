<template>
  <div class="item-wrap-template">
    <div 
      v-loading="setting.loading" 
      :element-loading-text="setting.loadingText" 
      class="pane">
      <div class="pane-title">{{ projectAuthId ? '修改节目授权' : '新增节目授权' }}</div>
      <el-form 
        ref="projectAuthForm" 
        :model="projectAuthForm" 
        label-width="150px" 
        class="duty-form">
        <el-form-item
          :rules="{required: true, message: '节目不能为空', trigger: 'submit'}"
          prop="project_id"
          label="节目"
        >
          <el-select
            v-model="projectAuthForm.project_id"
            :loading="searchLoading"
            :remote-method="getProject"
            remote
            placeholder="请输入节目名称"
            filterable
            clearable
            @change="getProject"
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
          :rules="{required: true, message: '节目皮肤不能为空', trigger: 'submit'}"
          label="节目皮肤"
          prop="skin_id"
        >
          <el-select
            v-model="projectAuthForm.skin_id"
            :loading="searchLoading"
            placeholder="请选择节目皮肤"
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
          :rules="{required: true, message: '场地主不能为空', trigger: 'submit'}"
          prop="customer_id"
          label="场地主"
        >
          <el-select
            v-model="projectAuthForm.customer_id"
            :loading="searchLoading"
            placeholder="请选择场地主"
            filterable
            clearable
          >
            <el-option
              v-for="item in marketOwnerList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>

        <el-form-item>
          <el-button 
            type="primary" 
            size="small" 
            @click="submit('projectAuthForm')">保存</el-button>
          <el-button 
            size="small" 
            @click="historyBack">返回</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import {
  getProjectAuthDetailData,
  getSearchMarketOwnerCustomer,
  getSearchProject,
  modifyProjectAuth,
  saveProjectAuth,
  historyBack,
  getSearchSkin
} from "service";

import {
  Button,
  Form,
  FormItem,
  Input,
  Option,
  Select
} from "element-ui";

export default {
  components: {
    ElForm: Form,
    ElFormItem: FormItem,
    ElButton: Button,
    ElInput: Input,
    ElSelect: Select,
    ElOption: Option
  },
  data() {
    return {
      projectAuthForm: {
        id: "",
        customer_id: "",
        project_id: "",
        skin_id: ""
      },
      marketOwnerList: [],
      projectList: [],
      skinList:[],
      searchLoading: false,
      projectAuthId: "",
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: "拼命加载中"
      },
    };
  },
  created() {
    this.projectAuthId = this.$route.params.uid;
    this.getMarketOwnerList();
    if (this.projectAuthId) {
      this.getProjectAuthDetail();
    }
  },
  methods: {
    getMarketOwnerList() {
      getSearchMarketOwnerCustomer(this)
        .then(result => {
          this.marketOwnerList = result;
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message:
              err.response && err.response.data
                ? err.response.data.message
                : "查询场地住出错"
          });
        });
    },

    getProject(query) {
      this.projectAuthForm.skin_id=''
      if (query !== "") {
        this.searchLoading = true;
        let args = {
          name: query
        };
        return getSearchProject(this, args)
          .then(response => {
            this.projectList = response.data;
            if (this.projectList.length == 0) {
              this.projectList = [];
            }
            this.searchLoading = false;
            this.getskin()
          })
          .catch(err => {
            this.searchLoading = false;
          });
      } else {
        this.projectList = [];
      }
    },
    getskin(){
      let args = {
        project_id: this.projectList[0].id
      };
      return getSearchSkin(this,args)
        .then(response =>{
          this.skinList = response
        })
        .catch(err => {
          this.searchLoading = false;          
        })
    },
    historyBack() {
      historyBack();
    },
    async getProjectAuthDetail() {
      this.setting.loading = true;
      try {
        let res = await getProjectAuthDetailData(this, this.projectAuthId);
        await this.getProject(res.project_name);
        this.projectAuthForm = res;
        console.log(this.projectAuthForm)
        this.setting.loading = false;
      } catch (e) {
        this.setting.loading = false;
        this.$message({
          type: "warning",
          message: "查询授权详情出错"
        });
      }
    },

    submit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          this.setting.loading = true;
          let args = this.projectAuthForm;
          if (this.projectAuthId) {
            modifyProjectAuth(this, this.projectAuthId, args)
              .then(res => {
                this.$message({
                  type: "success",
                  message: "修改成功"
                });
                this.historyBack()
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
            saveProjectAuth(this, args)
              .then(res => {
                this.$message({
                  type: "success",
                  message: "保存成功"
                });
               this.historyBack()
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
    }
  }
};
</script>

<style lang="less" scoped>
.item-wrap-template {
  .pane {
    border-radius: 5px;
    background-color: white;
    padding: 20px 40px 80px;

    .el-select,
    .item-input,
    .el-date-editor.el-input {
      width: 350px;
    }
    .item-list {
      .program-title {
        color: #555;
        font-size: 14px;
      }
    }
    .duty-form {
      width: 900px;
    }
    .pane-title {
      padding-bottom: 20px;
      font-size: 18px;
      display: flex;
      flex-direction: row;
      justify-content: space-between;
    }
  }
}
</style>
