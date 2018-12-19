<template>
  <div class="item-wrap-template">
    <div v-loading="setting.loading" :element-loading-text="setting.loadingText" class="pane">
      <div
        class="pane-title"
      >{{ programID ? (((role.name==='project-manager' && status===1) || role.name === 'legal-affairs-manager' || role.name === 'bonus-manager') ? '修改项目' : '查看项目') : '新增项目'}}</div>
      <el-form ref="programForm" :model="programForm" label-position="left" label-width="80px">
        <el-row>
          <el-col :span="12">
            <el-form-item
              label="节目名称"
              prop="belong"
              :rules="[{ required: true, message: '请输入节目名称', trigger: 'submit' }]"
            >
              <el-select
                v-model="programForm.belong"
                :loading="searchLoading"
                remote
                :remote-method="getProject"
                placeholder="请输入节目名称"
                filterable
                clearable
              >
                <el-option
                  v-for="item in projectList"
                  :key="item.alias"
                  :label="item.name"
                  :value="item.alias"
                />
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="申请人" prop="applicant_name">
              <el-input
                v-model="programForm.applicant_name"
                :disabled="true"
                :maxlength="50"
                class="item-input"
              />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item label="节目属性" prop="project_attribute">
              <el-radio-group v-model="programForm.project_attribute">
                <el-radio :label="1">基础条目</el-radio>
                <el-radio :label="2">通用节目</el-radio>
                <el-radio :label="3">定制节目</el-radio>
                <el-radio :label="4">定制项目</el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="联动属性" prop="link_attribute">
              <el-radio-group v-model="programForm.link_attribute">
                <el-radio :label="1">是</el-radio>
                <el-radio :label="0">否</el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item label="H5属性" prop="h5_attribute">
              <el-radio-group v-model="programForm.h5_attribute" @change="h5Handle">
                <el-radio :label="1">基础模版</el-radio>
                <el-radio :label="2">复杂模版</el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="小偶属性" prop="xo_attribute">
              <el-radio-group v-model="programForm.xo_attribute">
                <el-radio :label="1">是</el-radio>
                <el-radio :label="0">否</el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item label="节目类型" prop="type">
              <el-radio-group v-model="programForm.type">
                <el-radio :label="0">正常节目</el-radio>
                <el-radio :label="1">提前节目</el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item label="交互技术" prop="interactionVal">
              <span
                style="color: #999;font-size:14px;margin-right: 15px;"
              >{{ interactionRate }} * 系数</span>
              <el-select
                v-model="programForm.interactionVal"
                :loading="searchLoading"
                :multiple-limit="5"
                multiple
                placeholder="请添加人员"
                filterable
                clearable
                @change="peopleHandle($event,interactionRate,'interaction')"
              >
                <el-option
                  v-for="item in userList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
              <el-button
                type="text"
                size="mini"
                @click="modifyHandle(programForm.interaction,interactionRate,'interaction')"
              >{{(role.name === 'project-manager' || role.name === 'legal-affairs-manager' || role.name === 'bonus-manager') ? '修改':'详情' }}</el-button>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="节目创意" prop="creative">
              <span style="color: #999;font-size:14px;margin-right: 7px;">{{ creativeRate }} * 系数</span>
              <el-select
                v-model="programForm.creative"
                :loading="searchLoading"
                :multiple-limit="5"
                multiple
                placeholder="请添加人员"
                filterable
                clearable
                @change="peopleHandle($event,creativeRate,'creative')"
              >
                <el-option
                  v-for="item in userList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
              <el-button
                type="text"
                size="mini"
                @click="modifyHandle(programForm.originality,creativeRate,'creative')"
              >{{(role.name === 'project-manager' || role.name === 'legal-affairs-manager' || role.name === 'bonus-manager') ? '修改':'详情' }}</el-button>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item label="H5开发" prop="H5Val">
              <span
                :style="h5Rate==='0.1' ? 'margin-right: 15px;' : 'margin-right: 0;'"
                style="color: #999;font-size:14px;"
              >{{ h5Rate }} * 系数</span>
              <el-select
                v-model="programForm.H5Val"
                :loading="searchLoading"
                :multiple-limit="5"
                multiple
                placeholder="请添加人员"
                filterable
                clearable
                @change="peopleHandle($event,h5Rate,'H5')"
              >
                <el-option
                  v-for="item in userList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
              <el-button
                type="text"
                size="mini"
                @click="modifyHandle(programForm.h5,h5Rate,'H5')"
              >{{(role.name === 'project-manager' || role.name === 'legal-affairs-manager' || role.name === 'bonus-manager') ? '修改':'详情' }}</el-button>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="设计动画" prop="animate">
              <span style="color: #999;font-size:14px;">{{ animateRate }} * 系数</span>
              <el-select
                v-model="programForm.animate"
                :loading="searchLoading"
                :multiple-limit="5"
                multiple
                placeholder="请添加人员"
                filterable
                clearable
                @change="peopleHandle($event,animateRate,'animate')"
              >
                <el-option
                  v-for="item in userList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
              <el-button
                type="text"
                size="mini"
                @click="modifyHandle(programForm.animation,animateRate,'animate')"
              >{{(role.name === 'project-manager' || role.name === 'legal-affairs-manager' || role.name === 'bonus-manager') ? '修改':'详情' }}</el-button>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item label="节目统筹" prop="whole">
              <span style="color: #999;font-size:14px;margin-right: 8px;">{{ wholeRate }} * 系数</span>
              <el-select
                v-model="programForm.whole"
                :loading="searchLoading"
                :multiple-limit="5"
                multiple
                placeholder="请添加人员"
                filterable
                clearable
                @change="peopleHandle($event,wholeRate,'whole')"
              >
                <el-option
                  v-for="item in userList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
              <el-button
                type="text"
                size="mini"
                @click="modifyHandle(programForm.plan,wholeRate,'whole')"
              >{{(role.name === 'project-manager' || role.name === 'legal-affairs-manager' || role.name === 'bonus-manager') ? '修改':'详情' }}</el-button>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="节目测试" prop="test">
              <span style="color: #999;font-size:14px;">{{ testRate }} * 系数</span>
              <el-select
                v-model="programForm.test"
                :loading="searchLoading"
                :multiple-limit="5"
                multiple
                placeholder="请添加人员"
                filterable
                clearable
                @change="peopleHandle($event,testRate,'test')"
              >
                <el-option
                  v-for="item in userList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
              <el-button
                type="text"
                size="mini"
                @click="modifyHandle(programForm.tester,testRate,'test')"
              >{{(role.name === 'project-manager' || role.name === 'legal-affairs-manager'|| role.name === 'bonus-manager') ? '修改':'详情' }}</el-button>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item label="平台运营" prop="platform">
              <span style="color: #999;font-size:14px;margin-right: 8px;">{{ platformRate }} * 系数</span>
              <el-select
                v-model="programForm.platform"
                :loading="searchLoading"
                :multiple-limit="5"
                multiple
                placeholder="请添加人员"
                filterable
                clearable
                @change="peopleHandle($event,platformRate,'platform')"
              >
                <el-option
                  v-for="item in userList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
              <el-button
                type="text"
                size="mini"
                @click="modifyHandle(programForm.operation,platformRate,'platform')"
              >{{(role.name === 'project-manager' || role.name === 'legal-affairs-manager'|| role.name === 'bonus-manager') ? '修改':'详情' }}</el-button>
            </el-form-item>
          </el-col>
        </el-row>
        <el-form-item
          :rules="[{ required: true, message: '请输入艺术风格创新点', trigger: 'submit' }]"
          label="艺术风格创新点"
          prop="art_innovate"
          label-width="120px"
        >
          <el-input
            v-model="programForm.art_innovate"
            :autosize="{ minRows: 2}"
            :maxlength="1000"
            type="textarea"
            placeholder="请填写艺术风格创新点"
            class="text-input"
          />
        </el-form-item>
        <el-form-item
          :rules="[{ required: true, message: '请输入动效体验创新点', trigger: 'submit' }]"
          label="动效体验创新点"
          prop="dynamic_innovate"
          label-width="120px"
        >
          <el-input
            v-model="programForm.dynamic_innovate"
            :autosize="{ minRows: 2}"
            :maxlength="1000"
            type="textarea"
            placeholder="请填写动效体验创新点"
            class="text-input"
          />
        </el-form-item>
        <el-form-item
          :rules="[{ required: true, message: '请输入交互技术创新点', trigger: 'submit' }]"
          label="交互技术创新点"
          prop="interact_innovate"
          label-width="120px"
        >
          <el-input
            v-model="programForm.interact_innovate"
            :autosize="{ minRows: 2}"
            :maxlength="1000"
            type="textarea"
            placeholder="请填写交互技术创新点"
            class="text-input"
          />
        </el-form-item>
        <el-form-item label-width="120px" label="备注" prop="remark">
          <el-input
            v-model="programForm.remark"
            :autosize="{ minRows: 2}"
            :maxlength="1000"
            type="textarea"
            placeholder="请填写备注"
            class="text-input"
          />
        </el-form-item>
        <el-form-item label="上传素材" prop="ids">
          <el-upload
            ref="upload"
            :action="Domain"
            :data="uploadForm"
            :on-success="handleSuccess"
            :before-upload="beforeUpload"
            :on-remove="handleRemove"
            :on-preview="handlePreview"
            :before-remove="beforeRemove"
            :file-list="fileList"
            :limit="1"
            :on-exceed="handleExceed"
            class="upload-demo"
          >
            <el-button size="mini" type="success">点击上传</el-button>
            <div slot="tip" style="display:inline-block" class="el-upload__tip">支持类型：zip、rar,不能超过100M</div>
            <div
              v-if="fileList.length !==0"
              slot="tip"
              style="color: #ff5722;font-size: 12px;"
            >点击文件名称可以下载</div>
          </el-upload>
        </el-form-item>
        <el-form-item>
          <!-- 产品经理可以保存 -->
          <el-button
            v-if="(role.name === 'project-manager' && (status === 1 || status === 2)) || role.name === 'legal-affairs-manager'|| role.name === 'bonus-manager'"
            type="primary"
            @click="submit('programForm')"
          >保存</el-button>
          <el-button @click="historyBack">返回</el-button>
        </el-form-item>
      </el-form>
    </div>
    <!-- 修改比列 -->
    <el-dialog :visible.sync="dialogFormVisible" :show-close="false" title="绩效更改">
      <el-form :model="form" label-width="90px">
        <el-form-item label="总点数">
          <el-input v-model="form.total" :disabled="disabledChange"/>
        </el-form-item>
        <el-form-item v-for="item in peopleList" :key="item.id" :label="item.user_name">
          <el-input v-model="item.rate"/>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false,disabledChange = true">取 消</el-button>
        <el-button
          v-if="(role.name === 'project-manager' && (status === 1 || status === 2)) || role.name === 'legal-affairs-manager'|| role.name === 'bonus-manager'"
          type="primary"
          @click="rateSubmit"
        >确 定</el-button>
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
  Upload
} from "element-ui";
import {
  saveProgram,
  historyBack,
  getProgramDetails,
  modifyProgram,
  getSearchUserList,
  getSearchProjectList,
  getSearchTeamRateList,
  getQiniuToken,
  getMediaUpload
} from "service";
import { Cookies } from "utils/cookies";

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
    ElUpload: Upload
  },
  data() {
    return {
      Domain: "http://upload.qiniu.com",
      uploadToken: "",
      uploadKey: "",
      uploadForm: {
        token: "",
        key: ""
      },
      fileList: [],
      ids: [],
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
        loadingText: "拼命加载中"
      },
      status: 1,
      programID: "",
      programForm: {
        art_innovate: "",
        dynamic_innovate: "",
        interact_innovate: "",
        type: 0,
        applicant: "",
        belong: "",
        remark: "",
        applicant_name: "",
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
      type: "",
      userList: [],
      interactionRate: null,
      creativeRate: null,
      h5Rate1: null,
      h5Rate: null,
      h5Rate2: null,
      testRate: null,
      platformRate: null,
      animateRate: null,
      wholeRate: null,
      role: null
    };
  },
  created() {
    this.programID = this.$route.params.uid;
    let user_info = JSON.parse(Cookies.get("user_info"));
    this.role = user_info.roles.data[0];
    this.getUserList();
    this.getQiniuToken();
    if (this.programID) {
      this.detailInit();
    } else {
      this.setting.loading = true;
      this.getTeamRateList();
      this.programForm.applicant_name = user_info.name;
      this.programForm.applicant = user_info.id;
    }
  },
  methods: {
    async detailInit() {
      try {
        await this.getTeamRateList();
        await this.getProgramDetails();
      } catch (e) {
        console.log(e);
      }
    },
    h5Handle(val) {
      this.h5Rate = val === 1 ? this.h5Rate1 : this.h5Rate2;
    },
    handleRemove(file, fileList) {
      this.fileList = fileList;
    },
    getQiniuToken() {
      getQiniuToken(this)
        .then(res => {
          this.uploadToken = res;
          this.uploadForm.token = this.uploadToken;
        })
        .catch(err => {
          console.log(err);
        });
    },
    handlePreview(file) {
      let url = file.url;
      const xhr = new XMLHttpRequest();
      xhr.open("GET", url, true);
      xhr.responseType = "blob";
      xhr.onload = () => {
        var urlObject = window.URL || window.webkitURL || window;
        let a = document.createElement("a");
        a.href = urlObject.createObjectURL(new Blob([xhr.response]));
        a.download = file.name;
        a.click();
      };
      xhr.send();
    },
    handleExceed(files, fileList) {
      this.$message.warning(
        `当前限制选择 1 个文件，本次选择了 ${
          files.length
        } 个文件，共选择了 ${files.length + fileList.length} 个文件`
      );
    },
    beforeRemove(file, fileList) {
      return this.$confirm(`确定移除 ${file.name}？`);
    },
    beforeUpload(file) {
      let isLt100M = file.size / 1024 / 1024 < 1;
      let suffix = file.name;
      let key = encodeURI(`${suffix}`);
      if (!isLt100M) {
        this.uploadForm.token = "";
        return this.$message.error("上传大小不能超过 10MB!");
      } else {
        this.uploadForm.token = this.uploadToken;
      }
      this.uploadForm.key = key;
      return this.uploadForm;
    },
    // 上传成功后的处理
    handleSuccess(response, file, fileList) {
      let key = response.key;
      let name = file.raw.name;
      let size = file.size;
      this.getMediaUpload(key, name, size);
      
    },
    getMediaUpload(key, name, size) {
      let params = {
        key: key,
        name: name,
        size: size
      };
      getMediaUpload(this, params)
        .then(res => {
          this.fileList.push(res);
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    // 比列
    getTeamRateList() {
      getSearchTeamRateList(this)
        .then(res => {
          let data = res.data[0];
          this.interactionRate = data.interaction;
          this.creativeRate = data.originality;
          this.h5Rate1 = data.h5_1;
          this.testRate = data.tester;
          this.platformRate = data.operation;
          this.wholeRate = data.plan;
          this.animateRate = data.animation;
          this.h5Rate2 = data.h5_2;
          this.h5Rate = this.h5Rate1;
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
    getProgramDetails() {
      this.setting.loading = true;
      let params = {
        include: "media"
      };
      getProgramDetails(this, this.programID, params)
        .then(res => {
          let mediaData = [];
          if (res.media) {
            this.ids = res.media.id;
            mediaData.push(res.media);
          }
          this.fileList = mediaData;
          this.programForm.applicant = res.applicant;
          this.programForm.applicant_name = res.applicant_name;
          this.programForm.type = res.type;
          this.programForm.belong = res.belong;
          this.getProject(res.project_name);
          this.programForm.link_attribute = res.link_attribute;
          this.programForm.h5_attribute = res.h5_attribute;
          this.h5Rate = res.h5_attribute === 2 ? this.h5Rate2 : this.h5Rate1;
          this.programForm.project_attribute = res.project_attribute;
          this.programForm.xo_attribute = res.xo_attribute;
          this.programForm.animation = res.member.animation;
          this.programForm.h5 = res.member.h5;
          this.programForm.interaction = res.member.interaction;
          this.programForm.originality = res.member.originality;
          this.programForm.operation = res.member.operation;
          this.programForm.plan = res.member.plan;
          this.programForm.tester = res.member.tester;
          this.programForm.remark = res.remark;
          this.programForm.art_innovate = res.art_innovate;
          this.programForm.dynamic_innovate = res.dynamic_innovate;
          this.programForm.interact_innovate = res.interact_innovate;
          this.status = res.status;
          if (res.member.animation.length > 0) {
            res.member.animation.map(r => {
              this.programForm.animate.push(r.user_id);
            });
          }
          if (res.member.plan.length > 0) {
            res.member.plan.map(r => {
              this.programForm.whole.push(r.user_id);
            });
          }
          if (res.member.interaction.length > 0) {
            res.member.interaction.map(r => {
              this.programForm.interactionVal.push(r.user_id);
            });
          }
          if (res.member.h5.length > 0) {
            res.member.h5.map(r => {
              this.programForm.H5Val.push(r.user_id);
            });
          }
          if (res.member.tester.length > 0) {
            res.member.tester.map(r => {
              this.programForm.test.push(r.user_id);
            });
          }
          if (res.member.operation.length > 0) {
            res.member.operation.map(r => {
              this.programForm.platform.push(r.user_id);
            });
          }
          if (res.member.originality.length > 0) {
            res.member.originality.map(r => {
              this.programForm.creative.push(r.user_id);
            });
          }
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
    // 自定义比列修改modifyHandle，rateSubmit，performanceChange
    modifyHandle(obj, rate, type) {
      let length = obj.length;
      this.form.total = rate;
      this.peopleList = [];
      this.dialogFormVisible = true;
      this.type = type;
      if (length > 0) {
        switch (type) {
          case "interaction":
            this.peopleList = JSON.parse(
              JSON.stringify(this.programForm.interaction)
            );
            break;
          case "creative":
            this.peopleList = JSON.parse(
              JSON.stringify(this.programForm.originality)
            );
            break;
          case "H5":
            this.peopleList = JSON.parse(JSON.stringify(this.programForm.h5));
            break;
          case "animate":
            this.peopleList = JSON.parse(
              JSON.stringify(this.programForm.animation)
            );
            break;
          case "whole":
            this.peopleList = JSON.parse(JSON.stringify(this.programForm.plan));
            break;
          case "test":
            this.peopleList = JSON.parse(
              JSON.stringify(this.programForm.tester)
            );
            break;
          case "platform":
            this.peopleList = JSON.parse(
              JSON.stringify(this.programForm.operation)
            );
            break;
        }
      }
    },
    rateSubmit() {
      let type = this.type;
      let sum = 0;
      switch (type) {
        case "interaction":
          this.interactionRate = this.form.total;
          this.performanceChange("interaction", sum);
          break;
        case "creative":
          this.creativeRate = this.form.total;
          this.performanceChange("originality", sum);
          break;
        case "H5":
          this.h5Rate = this.form.total;
          this.performanceChange("h5", sum);
          break;
        case "animate":
          this.animateRate = this.form.total;
          this.performanceChange("animation", sum);
          break;
        case "whole":
          this.wholeRate = this.form.total;
          this.performanceChange("plan", sum);
          break;
        case "test":
          this.testRate = this.form.total;
          this.performanceChange("tester", sum);
          break;
        case "platform":
          this.platformRate = this.form.total;
          this.performanceChange("operation", sum);
          break;
      }
      this.disabledChange = true;
      this.dialogFormVisible = false;
    },
    performanceChange(name, sum) {
      this.peopleList.map(r => {
        sum += parseFloat(r.rate);
      });
      if (sum <= this.form.total) {
        this.programForm[name] = this.peopleList;
      } else {
        this.$message({
          type: "warning",
          message: "绩效比例不正确"
        });
      }
    },

    // 添加人员 均分比列
    peopleHandle(val, rate, type) {
      switch (type) {
        case "interaction":
          this.addRate("interaction", val, rate);
          break;
        case "creative":
          this.addRate("originality", val, rate);
          break;
        case "H5":
          this.addRate("h5", val, rate);
          break;
        case "animate":
          this.addRate("animation", val, rate);
          break;
        case "whole":
          this.addRate("plan", val, rate);
          break;
        case "test":
          this.addRate("tester", val, rate);
          break;
        case "platform":
          this.addRate("operation", val, rate);
          break;
      }
    },
    addRate(obj, val, rate) {
      let length = val.length;
      if (length > 0) {
        let averageRate = (rate / length).toFixed(4);
        this.programForm[obj] = [];
        val.map(r => {
          this.userList.filter(item => {
            if (item.id === r) {
              this.programForm[obj].push({
                user_id: item.id,
                user_name: item.name
              });
            }
          });
        });
        this.programForm[obj].map(i => {
          i.rate = averageRate;
        });
      } else {
        this.programForm[obj] = [];
      }
    },
    getUserList() {
      this.searchLoading = true;
      return getSearchUserList(this)
        .then(response => {
          this.userList = response.data;
          this.searchLoading = false;
        })
        .catch(err => {
          this.searchLoading = false;
        });
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
            this.searchLoading = false;
          });
      } else {
        this.projectList = [];
      }
    },
    submit(formName) {
      let mediaIds = [];
      if (this.fileList.length > 0) {
        this.fileList.map(r => {
          mediaIds.push(r.id);
        });
        this.ids = mediaIds.join(",");
      } else {
        this.$message({
          type: "warning",
          message: "素材必须上传"
        });
        return;
      }
      this.$refs[formName].validate(valid => {
        if (valid) {
          this.setting.loading = true;
          let member = {};
          let args = {
            belong: this.programForm.belong,
            applicant: this.programForm.applicant,
            project_attribute: this.programForm.project_attribute,
            link_attribute: this.programForm.link_attribute,
            h5_attribute: this.programForm.h5_attribute,
            xo_attribute: this.programForm.xo_attribute,
            remark: this.programForm.remark,
            art_innovate: this.programForm.art_innovate,
            dynamic_innovate: this.programForm.dynamic_innovate,
            interact_innovate: this.programForm.interact_innovate,
            type: this.programForm.type,
            media_id: this.ids
          };
          if (this.programForm.interaction.length > 0) {
            member.interaction = this.programForm.interaction;
          }
          if (this.programForm.originality.length > 0) {
            member.originality = this.programForm.originality;
          }
          if (this.programForm.h5.length > 0) {
            member.h5 = this.programForm.h5;
          }
          if (this.programForm.animation.length > 0) {
            member.animation = this.programForm.animation;
          }
          if (this.programForm.plan.length > 0) {
            member.plan = this.programForm.plan;
          }
          if (this.programForm.tester.length > 0) {
            member.tester = this.programForm.tester;
          }
          if (this.programForm.operation.length > 0) {
            member.operation = this.programForm.operation;
          }
          args.member = member;
          if (this.programID) {
            args.id = this.programID;
            modifyProgram(this, args, this.programID)
              .then(res => {
                this.$message({
                  message: "修改成功",
                  type: "success"
                });
                this.$router.push({
                  path: "/team/program"
                });
                this.setting.loading = false;
              })
              .catch(err => {
                this.setting.loading = false;
                this.$message({
                  message: err.response.data.message,
                  type: "warning"
                });
              });
          } else {
            saveProgram(this, args)
              .then(res => {
                this.$message({
                  message: "提交成功",
                  type: "success"
                });
                this.$router.push({
                  path: "/team/program"
                });
                this.setting.loading = false;
              })
              .catch(err => {
                this.setting.loading = false;
                this.$message({
                  message: err.response.data.message,
                  type: "warning"
                });
              });
          }
        }
      });
    },
    historyBack() {
      historyBack();
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
