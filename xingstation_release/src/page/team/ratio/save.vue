<template>
  <div class="item-wrap-template">
    <div v-loading="setting.loading" :element-loading-text="setting.loadingText" class="pane">
      <div class="pane-title">修改比例</div>
      <el-form ref="rateForm" :model="rateForm" label-width="150px">
        <el-form-item
          prop="interaction_api"
          label="交互技术-中间件"
          :rules="{required: true, message: '交互技术-中间件不能为空', trigger: 'submit'}"
        >
          <el-input
            v-model="rateForm.interaction_api"
            placeholder="请输入交互技术-中间件"
            class="item-input"
          />
        </el-form-item>
        <el-form-item
          prop="interaction_linkage"
          label="交互技术-联动引擎"
          :rules="{required: true, message: '交互技术-联动引擎不能为空', trigger: 'submit'}"
        >
          <el-input
            v-model="rateForm.interaction_linkage"
            placeholder="请输入交互技术-联动引擎"
            class="item-input"
          />
        </el-form-item>
        <el-form-item
          prop="h5_1"
          label="H5基础"
          :rules="{required: true, message: 'H5基础不能为空', trigger: 'submit'}"
        >
          <el-input 
            v-model="rateForm.h5_1" 
            placeholder="请输入H5基础" 
            class="item-input"/>
        </el-form-item>
        <el-form-item
          prop="h5_2"
          label="H5复杂"
          :rules="{required: true, message: 'H5复杂不能为空', trigger: 'submit'}"
        >
          <el-input 
            v-model="rateForm.h5_2" 
            placeholder="请输入H5基础" 
            class="item-input"/>
        </el-form-item>
        <el-form-item
          prop="plan"
          label="节目统筹"
          :rules="{required: true, message: '节目统筹不能为空', trigger: 'submit'}"
        >
          <el-input 
            v-model="rateForm.plan" 
            placeholder="请输入节目统筹" 
            class="item-input"/>
        </el-form-item>
        <el-form-item
          prop="operation"
          label="运营基本"
          :rules="{required: true, message: '运营基本不能为空', trigger: 'submit'}"
        >
          <el-input 
            v-model="rateForm.operation" 
            placeholder="请输入运营基本" 
            class="item-input"/>
        </el-form-item>
        <el-form-item
          prop="operation_quality"
          label="运营保质"
          :rules="{required: true, message: '运营保质不能为空', trigger: 'submit'}"
        >
          <el-input 
            v-model="rateForm.operation_quality" 
            placeholder="请输入运营保质" 
            class="item-input"/>
        </el-form-item>
        <el-form-item
          prop="originality"
          label="节目创意"
          :rules="{required: true, message: '节目创意不能为空', trigger: 'submit'}"
        >
          <el-input 
            v-model="rateForm.originality" 
            placeholder="请输入节目创意" 
            class="item-input"/>
        </el-form-item>
        <el-form-item
          prop="animation"
          label="设计动画"
          :rules="{required: true, message: '设计动画不能为空', trigger: 'submit'}"
        >
          <el-input 
            v-model="rateForm.animation" 
            placeholder="请输入设计动画" 
            class="item-input"/>
        </el-form-item>
        <el-form-item
          prop="animation_hidol"
          label="设计动画-Hidol对接"
          :rules="{required: true, message: '设计动画-Hidol对接不能为空', trigger: 'submit'}"
        >
          <el-input
            v-model="rateForm.animation_hidol"
            placeholder="请输入设计动画-Hidol对接"
            class="item-input"
          />
        </el-form-item>
        <el-form-item
          prop="hidol_patent"
          label="Hidol专利"
          :rules="{required: true, message: 'Hidol专利不能为空', trigger: 'submit'}"
        >
          <el-input 
            v-model="rateForm.hidol_patent" 
            placeholder="请输入Hidol专利" 
            class="item-input"/>
        </el-form-item>
        <el-form-item
          prop="tester"
          label="测试基本"
          :rules="{required: true, message: '测试基本不能为空', trigger: 'submit'}"
        >
          <el-input 
            v-model="rateForm.tester" 
            placeholder="请输入测试基本" 
            class="item-input"/>
        </el-form-item>
        <el-form-item
          prop="tester_quality"
          label="测试保质"
          :rules="{required: true, message: '测试保质不能为空', trigger: 'submit'}"
        >
          <el-input 
            v-model="rateForm.tester_quality" 
            placeholder="请输入测试保质" 
            class="item-input"/>
        </el-form-item>
        <el-form-item
          prop="backend_docking"
          label="后端IT技术对接"
          :rules="{required: true, message: '后端IT技术对接不能为空', trigger: 'submit'}"
        >
          <el-input
            v-model="rateForm.backend_docking"
            placeholder="请输入后端IT技术对接"
            class="item-input"
          />
        </el-form-item>
        <el-form-item>
          <el-button type="primary" size="small" @click="submit('rateForm')">保存</el-button>
          <el-button size="small" @click="historyBack">返回</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import { modifyTeamRate, getTeamRateDetails, historyBack } from "service";
import { Form, FormItem, Button, Input, MessageBox } from "element-ui";

export default {
  components: {
    ElForm: Form,
    ElFormItem: FormItem,
    ElButton: Button,
    ElInput: Input
  },
  data() {
    return {
      rateForm: {
        interaction_api: "",
        interaction_linkage: "",
        h5_1: "",
        h5_2: "",
        plan: "",
        operation: "",
        operation_quality: "",
        originality: "",
        animation: "",
        animation_hidol: "",
        hidol_patent: "",
        tester: "",
        tester_quality: "",
        backend_docking: ""
      },
      rateId: "",
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: "拼命加载中"
      }
    };
  },
  mounted() {},
  created() {
    this.rateId = this.$route.params.uid;
    this.getTeamRateDetails();
  },
  methods: {
    historyBack() {
      historyBack();
    },
    getTeamRateDetails() {
      this.setting.loading = true;
      getTeamRateDetails(this, this.rateId)
        .then(res => {
          this.rateForm = res;
          delete this.rateForm.id;
          this.setting.loading = false;
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
          this.setting.loading = false;
        });
    },
    submit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          this.setting.loading = true;
          let args = this.rateForm;
          modifyTeamRate(this, this.rateId, args)
            .then(res => {
              this.$message({
                type: "success",
                message: "修改成功"
              });
              this.$router.push({
                path: "/team/ratio"
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
      width: 300px;
    }
    .item-list {
      .program-title {
        color: #555;
        font-size: 14px;
      }
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
