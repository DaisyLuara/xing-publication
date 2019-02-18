<template>
  <div class="item-wrap-template">
    <div v-loading="setting.loading" :element-loading-text="setting.loadingText" class="pane">
      <div
        class="pane-title"
      >{{ programID ? (((projectManage && status===1) || legalAffairsManager || bonusManage) ? '修改节目' : '查看节目') : '新增节目'}}</div>
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
        <el-form-item label="节目属性" prop="project_attribute">
          <el-radio-group v-model="programForm.project_attribute">
            <el-radio :label="0">不计入</el-radio>
            <el-radio :label="2">简单条目
              <el-tooltip class="item" effect="dark" content="0.1个条目（如简单换Logo等）" placement="bottom">
                <i class="el-icon-info"/>
              </el-tooltip>
            </el-radio>
            <el-radio :label="1">基础条目
              <el-tooltip class="item" effect="dark" content="1个条目（如镜视界类节目）" placement="bottom">
                <i class="el-icon-info"/>
              </el-tooltip>
            </el-radio>
            <el-radio :label="3">节目
              <el-tooltip class="item" effect="dark" content="1个节目（如创新玩法类节目）" placement="bottom">
                <i class="el-icon-info"/>
              </el-tooltip>
            </el-radio>
            <el-radio :label="4">更多</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-row>
          <el-col :span="12">
            <el-form-item label="原创属性" prop="copyright_attribute">
              <el-radio-group
                v-model="programForm.copyright_attribute"
                @change="copyrightAttributeHandle"
              >
                <el-radio :label="0">原创节目</el-radio>
                <el-radio :label="1">非原创节目</el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col>
          <el-col :span="12" v-if="copyrightFlag">
            <el-form-item
              :rules="[{ required: true, message: '请输入原创节目', trigger: 'submit' }]"
              label="原创节目"
              prop="copyright_project_id"
            >
              <el-select
                v-model="programForm.copyright_project_id"
                :loading="searchLoading"
                remote
                :remote-method="getSearchCopyrightProject"
                placeholder="无原创节目"
                filterable
                clearable
              >
                <el-option
                  v-for="item in copyrightProjectList"
                  :key="item.id"
                  :label="item.project_name"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item label="节目类型" prop="type">
              <el-radio-group v-model="programForm.type">
                <el-radio :label="1">提前节目</el-radio>
                <el-radio :label="0">正常节目</el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="定制属性" prop="individual_attribute">
              <el-radio-group v-model="programForm.individual_attribute" @change="handleCustom">
                <el-radio :label="0">非定制节目
                  <el-tooltip class="item" effect="dark" content="无合同的节目" placement="bottom">
                    <i class="el-icon-info"/>
                  </el-tooltip>
                </el-radio>
                <el-radio :label="1">定制特别节目
                  <el-tooltip class="item" effect="dark" content="有合同，且为对方特别定制的节目" placement="bottom">
                    <i class="el-icon-info"/>
                  </el-tooltip>
                </el-radio>
                <el-radio :label="2">定制通用节目
                  <el-tooltip class="item" effect="dark" content="有合同，但并非为对方特别定制的节目" placement="bottom">
                    <i class="el-icon-info"/>
                  </el-tooltip>
                </el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item label="合同编号" prop="type">
              <el-select
                v-model="programForm.contract_id"
                :disabled="contractDisable"
                filterable
                clearable
                placeholder="请选择合同编号"
                @change="contractHandle"
              >
                <el-option
                  v-for="item in contractList"
                  :key="item.id"
                  :label="item.contract_number"
                  :value="item.id"
                ></el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="合同金额" prop="money">
              <el-input
                v-model="programForm.money"
                :disabled="true"
                placeholder="请输入合同金额"
                class="item-input"
              />
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item label="联动属性" prop="link_attribute">
              <el-radio-group v-model="programForm.link_attribute">
                <el-radio :label="0">否</el-radio>
                <el-radio :label="1">是</el-radio>
              </el-radio-group>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="Hidol属性" prop="hidol_attribute">
              <el-radio-group v-model="programForm.hidol_attribute">
                <el-radio :label="0">否</el-radio>
                <el-radio :label="1">是</el-radio>
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
            <el-form-item
              :rules="[{ required: true, message: '请选择交互属性', trigger: 'submit' }]"
              label="交互属性"
              prop="interaction_attribute"
            >
              <el-checkbox-group
                v-model="programForm.interaction_attribute"
                :min="1"
                @change="interactionHandle"
              >
                <el-checkbox label="interaction_api">中间件调用</el-checkbox>
                <el-checkbox label="interaction_linkage">交互引擎</el-checkbox>
              </el-checkbox-group>
            </el-form-item>
          </el-col>
        </el-row>
        <h2 class="title">节目制造团队</h2>
        <el-row>
          <el-col :span="12">
            <el-form-item label="节目创意" prop="creative">
              <span
                style="color: #999;font-size:14px;margin-right: 15px;"
              >{{ rate.originality }} * 系数</span>
              <el-select
                v-model="programForm.creative"
                :loading="searchLoading"
                :multiple-limit="5"
                multiple
                placeholder="请添加人员"
                filterable
                clearable
                @change="peopleHandle($event,rate.originality,'creative')"
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
                @click="modifyHandle(programForm.originality,rate.originality,'creative')"
              >{{(projectManage || legalAffairsManager || bonusManage) ? '修改':'详情' }}</el-button>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="节目统筹" prop="whole">
              <span style="color: #999;font-size:14px;">{{ rate.plan }} * 系数</span>
              <el-select
                v-model="programForm.whole"
                :loading="searchLoading"
                :multiple-limit="5"
                multiple
                placeholder="请添加人员"
                filterable
                clearable
                @change="peopleHandle($event,rate.plan,'whole')"
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
                @click="modifyHandle(programForm.plan,rate.plan,'whole')"
              >{{(projectManage || legalAffairsManager || bonusManage) ? '修改':'详情' }}</el-button>
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
        <el-row>
          <el-col :span="12">
            <el-form-item label="设计动画" prop="animat">
              <span style="color: #999;font-size:14px;margin-right: 8px;">{{ rate.animation }} * 系数</span>
              <el-select
                v-model="programForm.animat"
                :loading="searchLoading"
                :multiple-limit="5"
                multiple
                placeholder="请添加人员"
                filterable
                clearable
                @change="peopleHandle($event,rate.animation,'animat')"
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
                @click="modifyHandle(programForm.animation,rate.animation,'animat')"
              >{{(projectManage || legalAffairsManager || bonusManage) ? '修改':'详情' }}</el-button>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="设计动画.Hidol" prop="animatHidol" label-width="105px">
              <span style="color: #999;font-size:14px;">{{ rate.animation_hidol }} * 系数</span>
              <el-select
                v-model="programForm.animatHidol"
                :loading="searchLoading"
                :multiple-limit="5"
                multiple
                placeholder="请添加人员"
                filterable
                clearable
                @change="peopleHandle($event,rate.animation_hidol,'animatHidol')"
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
                @click="modifyHandle(programForm.animation_hidol,rate.animation_hidol,'animatHidol')"
              >{{(projectManage || legalAffairsManager || bonusManage) ? '修改':'详情' }}</el-button>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item label="Hidol专利" prop="hidol">
              <span
                style="color: #999;font-size:14px;margin-right: 8px;"
              >{{ rate.hidol_patent }} * 系数</span>
              <el-select
                v-model="programForm.hidol"
                :loading="searchLoading"
                :multiple-limit="5"
                multiple
                placeholder="请添加人员"
                filterable
                clearable
                @change="peopleHandle($event,rate.hidol_patent,'hidol')"
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
                @click="modifyHandle(programForm.hidol_patent,rate.hidol_patent,'hidol')"
              >{{(projectManage || legalAffairsManager || bonusManage) ? '修改':'详情' }}</el-button>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item
              :rules="[{ required: true, message: '请上传素材', trigger: 'submit' }]"
              label="上传素材"
              prop
            >
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
                <div
                  slot="tip"
                  style="display:inline-block;margin-left: 10px;"
                  class="el-upload__tip"
                >支持类型：zip、rar,不能超过100M</div>
                <div
                  v-if="fileList.length !==0"
                  slot="tip"
                  style="color: #ff5722;font-size: 12px;"
                >点击文件名称可以下载</div>
              </el-upload>
            </el-form-item>
          </el-col>
        </el-row>
        <h2 class="title">中后台团队</h2>
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
              >{{(projectManage || legalAffairsManager || bonusManage) ? '修改':'详情' }}</el-button>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="后端IT技术" prop="backend" label-width="100px">
              <span
                style="color: #999;font-size:14px;margin-right: 8px;"
              >{{ rate.backend_docking }} * 系数</span>
              <el-select
                v-model="programForm.backend"
                :loading="searchLoading"
                :multiple-limit="5"
                multiple
                placeholder="请添加人员"
                filterable
                clearable
                @change="peopleHandle($event,rate.backend_docking,'backend')"
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
                @click="modifyHandle(programForm.backend_docking,rate.backend_docking,'backend')"
              >{{(projectManage || legalAffairsManager || bonusManage) ? '修改':'详情' }}</el-button>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item label="节目测试" prop="test">
              <span style="color: #999;font-size:14px;margin-right: 8px;">{{ rate.tester }} * 系数</span>
              <el-select
                v-model="programForm.test"
                :loading="searchLoading"
                :multiple-limit="5"
                multiple
                placeholder="请添加人员"
                filterable
                clearable
                @change="peopleHandle($event,rate.tester,'test')"
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
                @click="modifyHandle(programForm.tester,rate.tester,'test')"
              >{{(projectManage || legalAffairsManager|| bonusManage) ? '修改':'详情' }}</el-button>
              <el-tooltip
                class="item"
                effect="dark"
                content="节目测试责任总责奖金0.06部分,系统将自动计算。"
                placement="right"
              >
                <i class="el-icon-info"/>
              </el-tooltip>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="H5开发" prop="H5Val" label-width="100px">
              <span
                :style="h5Rate === '0.1' ? 'margin-right: 15px;' : 'margin-right: 0;'"
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
              >{{(projectManage || legalAffairsManager || bonusManage) ? '修改':'详情' }}</el-button>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="12">
            <el-form-item label="平台运营" prop="platform">
              <span style="color: #999;font-size:14px;margin-right: 8px;">{{ rate.operation }} * 系数</span>
              <el-select
                v-model="programForm.platform"
                :loading="searchLoading"
                :multiple-limit="5"
                multiple
                placeholder="请添加人员"
                filterable
                clearable
                @change="peopleHandle($event,rate.operation,'platform')"
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
                @click="modifyHandle(programForm.operation,rate.operation,'platform')"
              >{{(projectManage || legalAffairsManager|| bonusManage) ? '修改':'详情' }}</el-button>
              <el-tooltip
                class="item"
                effect="dark"
                content="平台运营验收奖金0.02部分,系统将自动计算。"
                placement="right"
              >
                <i class="el-icon-info"/>
              </el-tooltip>
            </el-form-item>
          </el-col>
          <el-col v-if="testFile" :span="12">
            <el-form-item label="测试文档">
              <div @click="handlePreview(testFile)" style="cursor:pointer;">{{testFile.name}}</div>
            </el-form-item>
          </el-col>
        </el-row>

        <el-form-item>
          <!-- 产品经理可以保存 -->
          <el-button
            v-if="(projectManage && (status === 1 || status === 2)) || legalAffairsManager|| bonusManage"
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
          v-if="(projectManage && (status === 1 || status === 2)) || legalAffairsManager|| bonusManage"
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
  CheckboxGroup,
  Checkbox,
  Col,
  Dialog,
  Upload,
  Tooltip
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
  getMediaUpload,
  getContractReceiptList,
  getSearchCopyrightProject
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
    ElCheckboxGroup: CheckboxGroup,
    ElCheckbox: Checkbox,
    ElDialog: Dialog,
    ElUpload: Upload,
    ElTooltip: Tooltip
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
      testFile: null,
      contractList: [],
      copyrightProjectList: [],
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
        copyright_attribute: 0,
        copyright_project_id: null,
        money: "",
        hidol_attribute: 0,
        contract_id: "",
        art_innovate: "",
        dynamic_innovate: "",
        interact_innovate: "",
        type: 0,
        individual_attribute: 0,
        applicant: "",
        belong: "",
        remark: "",
        applicant_name: "",
        project_attribute: 1,
        link_attribute: 0,
        h5_attribute: 1,
        interaction_attribute: ["interaction_linkage"],
        interactionVal: [],
        H5Val: [],
        creative: [],
        platform: [],
        test: [],
        animateHidol: [],
        animate: [],
        hidol: [],
        backend: [],
        whole: [],
        operation: [],
        operation_quality: [],
        interaction: [],
        originality: [],
        h5: [],
        plan: [],
        animation: [],
        animation_hidol: [],
        hidol_patent: [],
        backend_docking: [],
        tester: [],
        tester_quality: []
      },
      isRefresh: false,
      copyrightFlag: false,
      type: "",
      userList: [],
      rate: {
        tester_quality: null,
        operation_quality: null,
        backend_docking: null,
        interaction_linkage: null,
        interaction_api: null,
        originality: null,
        h5_1: null,
        h5_2: null,
        tester: null,
        operation: null,
        animation: null,
        hidol_patent: null,
        animation_hidol: null,
        plan: null
      },
      interactionRate: null,
      h5Rate: null,
      role: null,
      contractDisable: true
    };
  },
  computed: {
    projectManage: function() {
      return this.role.find(r => {
        return r.name === "project-manager";
      });
    },
    bonusManage: function() {
      return this.role.find(r => {
        return r.name === "bonus-manager";
      });
    },
    legalAffairsManager: function() {
      return this.role.find(r => {
        return r.name === "legal-affairs-manager";
      });
    }
  },
  created() {
    this.getSearchCopyrightProject("丛林");
    this.programID = this.$route.params.uid;
    let user_info = JSON.parse(Cookies.get("user_info"));
    this.role = user_info.roles.data;
    this.getUserList();

    this.getQiniuToken();
    if (this.programID) {
      this.detailInit();
    } else {
      this.setting.loading = true;
      this.getTeamRateList();
      this.getContractReceiptList();
      this.programForm.applicant_name = user_info.name;
      this.programForm.applicant = user_info.id;
    }
  },
  methods: {
    async detailInit() {
      try {
        await this.getTeamRateList();
        await this.getProgramDetails();
        await this.getContractReceiptList();
      } catch (e) {
        console.log(e);
      }
    },
    getContractReceiptList() {
      let args = {
        type: 0
      };
      getContractReceiptList(this, args)
        .then(res => {
          this.contractList = res;
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    copyrightAttributeHandle(val) {
      if (val === 0) {
        this.copyrightFlag = false;
      } else {
        this.copyrightFlag = true;
      }
    },
    h5Handle(val) {
      let idArr = [];
      this.h5Rate = val === 1 ? this.rate.h5_1 : this.rate.h5_2;
      if (JSON.stringify(this.programForm.h5) !== "[]") {
        this.programForm.h5.map(r => {
          idArr.push(r.user_id);
        });
        this.peopleHandle(idArr, this.h5Rate, "H5");
      }
    },

    handleRemove(file, fileList) {
      this.fileList = fileList;
    },
    interactionHandle(val) {
      let idArr = [];
      if (val.length === 2) {
        this.interactionRate =
          parseFloat(this.rate.interaction_linkage) +
          parseFloat(this.rate.interaction_api);
      } else if (val.length === 1) {
        let value = val[0];
        if (value === "interaction_api") {
          this.interactionRate = this.rate.interaction_api;
        } else {
          this.interactionRate = this.rate.interaction_linkage;
        }
      }
      if (JSON.stringify(this.programForm.interaction) !== "[]") {
        this.programForm.interaction.map(r => {
          idArr.push(r.user_id);
        });
        this.peopleHandle(idArr, this.interactionRate, "interaction");
      }
    },
    contractHandle(val) {
      let contractChoose = {};
      this.contractList.filter(r => {
        if (r.id === val) {
          contractChoose = r;
          return;
        }
      });
      this.programForm.money = contractChoose.amount;
    },
    handleCustom(val) {
      if (val === 1 || val === 2) {
        this.contractDisable = false;
      } else {
        this.programForm.contract_id = "";
        this.programForm.money = "";
        this.contractDisable = true;
      }
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
      let name = file.name;
      let type = name.substring(name.lastIndexOf("."));
      let isLt100M = file.size / 1024 / 1024 < 100;
      let time = new Date().getTime();
      let random = parseInt(Math.random() * 10 + 1, 10);
      let suffix = time + "_" + random + "_" + name;
      let key = encodeURI(`${suffix}`);
      if (!(type === ".zip" || type === ".rar")) {
        this.uploadForm.token = "";
        return this.$message.error("类型只支持zip、rar");
      }
      if (!isLt100M) {
        this.uploadForm.token = "";
        return this.$message.error("上传大小不能超过 100MB!");
      } else {
        this.uploadForm.token = this.uploadToken;
      }
      this.uploadForm.key = key;
      return this.uploadForm;
    },
    // 上传成功后的处理
    handleSuccess(response, file, fileList) {
      let key = response.key;
      let name = file.name;
      let size = file.size;
      let type = name.substring(name.lastIndexOf("."));
      this.getMediaUpload(key, name, size, type);
    },
    getMediaUpload(key, name, size, type) {
      let params = {
        key: key,
        name: name,
        size: size
      };
      getMediaUpload(this, params)
        .then(res => {
          if (type === ".zip" || type === ".rar") {
            this.fileList.push(res);
          }
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
          this.rate = data;
          this.interactionRate = this.rate.interaction_linkage;
          this.h5Rate = this.rate.h5_1;
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
        include: "contract"
      };
      getProgramDetails(this, this.programID, params)
        .then(res => {
          let planMediaData = [];
          if (res.tester_media) {
            this.testFile = res.tester_media;
          }
          let animationMediaData = [];
          if (res.animation_media) {
            animationMediaData.push(res.animation_media);
          }
          this.fileList = animationMediaData;
          this.programForm.applicant = res.applicant;
          this.programForm.applicant_name = res.applicant_name;
          this.programForm.type = res.type;
          this.programForm.belong = res.belong;
          this.getProject(res.project_name);
          this.programForm.link_attribute = res.link_attribute;
          this.programForm.h5_attribute = res.h5_attribute;
          this.programForm.interaction_attribute = res.interaction_attribute;
          this.programForm.individual_attribute = res.individual_attribute;
          this.programForm.copyright_project_id = res.copyright_project_id;
          this.programForm.copyright_attribute = res.copyright_attribute;
          if (res.copyright_attribute === 0) {
            this.copyrightFlag = false;
          } else {
            this.copyrightFlag = true;
          }
          if (res.copyright_project_id) {
            this.getSearchCopyrightProject(res.copyright_project_name);
          }
          if (res.individual_attribute === 1 || res.individual_attribute === 2) {
            this.programForm.contract_id = res.contract_id;
            this.programForm.money = res.contract.amount;
          }
          this.contractDisable = (res.individual_attribute === 1||res.individual_attribute === 2) ? false : true;
          this.h5Rate =
            res.h5_attribute === 2 ? this.rate.h5_2 : this.rate.h5_1;
          this.programForm.project_attribute = res.project_attribute;
          (this.programForm.hidol_attribute = res.hidol_attribute),
            (this.programForm.remark = res.remark);
          this.programForm.art_innovate = res.art_innovate;
          this.programForm.dynamic_innovate = res.dynamic_innovate;
          this.programForm.interact_innovate = res.interact_innovate;
          this.interactionRate =
            res.interaction_attribute.length === 2
              ? parseFloat(this.rate.interaction_linkage) +
                parseFloat(this.rate.interaction_api)
              : res.interaction_attribute[0] === "interaction_api"
              ? this.rate.interaction_api
              : this.rate.interaction_linkage;

          this.status = res.status;
          if (JSON.stringify(res.member) !== "[]") {
            // 动画设计
            this.peopleArrHandle(res, "animation", "animat");
            // 节目统筹
            this.peopleArrHandle(res, "plan", "whole");
            // 交互技术
            this.peopleArrHandle(res, "interaction", "interactionVal");
            // h5
            this.peopleArrHandle(res, "h5", "H5Val");
            // 测试
            this.peopleArrHandle(res, "tester", "test");
            // 运营
            this.peopleArrHandle(res, "operation", "platform");
            // 节目创意
            this.peopleArrHandle(res, "originality", "creative");
            // 动画设计.Hidol
            this.peopleArrHandle(res, "animation_hidol", "animatHidol");
            // 后端iT技术
            this.peopleArrHandle(res, "backend_docking", "backend");
            // Hidol专利
            this.peopleArrHandle(res, "hidol_patent", "hidol");
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

    // 人员数组变化
    peopleArrHandle(res, name, oldName) {
      if (res.member[name]) {
        this.programForm[name] = res.member[name];
        res.member[name].map(r => {
          this.programForm[oldName].push(r.user_id);
        });
      } else {
        this.programForm[name] = [];
      }
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
          case "animat":
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
          case "animatHidol":
            this.peopleList = JSON.parse(
              JSON.stringify(this.programForm.animation_hidol)
            );
            break;
          case "hidol":
            this.peopleList = JSON.parse(
              JSON.stringify(this.programForm.hidol_patent)
            );
            break;
          case "backend":
            this.peopleList = JSON.parse(
              JSON.stringify(this.programForm.backend_docking)
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
          this.rate.originality = this.form.total;
          this.performanceChange("originality", sum);
          break;
        case "H5":
          this.h5Rate = this.form.total;
          this.performanceChange("h5", sum);
          break;
        case "animat":
          this.rate.animation = this.form.total;
          this.performanceChange("animation", sum);
          break;
        case "whole":
          this.rate.plan = this.form.total;
          this.performanceChange("plan", sum);
          break;
        case "test":
          this.rate.tester = this.form.total;
          this.performanceChange("tester", sum);
          break;
        case "platform":
          this.rate.operation = this.form.total;
          this.performanceChange("operation", sum);
          break;
        case "animatHidol":
          this.rate.animation_hidol = this.form.total;
          this.performanceChange("animation_hidol", sum);
          break;
        case "hidol":
          this.rate.hidol_patent = this.form.total;
          this.performanceChange("hidol_patent", sum);
          break;
        case "backend":
          this.rate.backend_docking = this.form.total;
          this.performanceChange("backend_docking", sum);
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
        case "animat":
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
        case "animatHidol":
          this.addRate("animation_hidol", val, rate);
          break;
        case "hidol":
          this.addRate("hidol_patent", val, rate);
          break;
        case "backend":
          this.addRate("backend_docking", val, rate);
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
    getSearchCopyrightProject(query) {
      if (query !== "") {
        this.searchLoading = true;
        let args = {
          project_name: query,
          copyright_attribute: 0
        };
        return getSearchCopyrightProject(this, args)
          .then(response => {
            this.copyrightProjectList = response;
            if (this.copyrightProjectList.length == 0) {
              this.copyrightProjectList = [];
            }
            this.searchLoading = false;
          })
          .catch(err => {
            this.searchLoading = false;
          });
      } else {
        this.copyrightProjectList = [];
      }
    },
    submit(formName) {
      console.log(this.ids);
      this.getQiniuToken();
      let animationMediaIds = [];
      if (this.fileList.length > 0) {
        this.fileList.map(r => {
          animationMediaIds.push(r.id);
        });
        this.ids = animationMediaIds.join(",");
      } else {
        this.$message({
          type: "warning",
          message: "设计动画素材必须上传"
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
            individual_attribute: this.programForm.individual_attribute,
            hidol_attribute: this.programForm.hidol_attribute,
            remark: this.programForm.remark,
            art_innovate: this.programForm.art_innovate,
            dynamic_innovate: this.programForm.dynamic_innovate,
            interact_innovate: this.programForm.interact_innovate,
            type: this.programForm.type,
            animation_media_id: this.ids,
            copyright_attribute: this.programForm.copyright_attribute,
            copyright_project_id: this.programForm.copyright_project_id,
            interaction_attribute: this.programForm.interaction_attribute
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
            let tester = JSON.stringify(this.programForm.tester);
            this.programForm.tester_quality = [];
            let testerMulriple = parseFloat(
              (
                parseFloat(this.rate.tester_quality) /
                parseFloat(this.rate.tester)
              ).toFixed(1)
            );
            JSON.parse(tester).map(r => {
              this.programForm.tester_quality.push({
                user_id: r.user_id,
                user_name: r.user_name,
                rate: (r.rate * testerMulriple).toFixed(4)
              });
            });
            member.tester_quality = this.programForm.tester_quality;
          }
          if (this.programForm.operation.length > 0) {
            member.operation = this.programForm.operation;
            this.programForm.operation_quality = [];
            let operationMulriple = parseFloat(
              (
                parseFloat(this.rate.operation_quality) /
                parseFloat(this.rate.operation)
              ).toFixed(1)
            );
            let operation = JSON.stringify(this.programForm.operation);
            JSON.parse(operation).map(r => {
              this.programForm.operation_quality.push({
                user_id: r.user_id,
                user_name: r.user_name,
                rate: (r.rate * operationMulriple).toFixed(4)
              });
            });
            member.operation_quality = this.programForm.operation_quality;
          }
          if (this.programForm.backend_docking.length > 0) {
            member.backend_docking = this.programForm.backend_docking;
          }
          if (this.programForm.hidol_patent.length > 0) {
            member.hidol_patent = this.programForm.hidol_patent;
          }
          if (this.programForm.animation_hidol.length > 0) {
            member.animation_hidol = this.programForm.animation_hidol;
          }
          if (this.programForm.individual_attribute === 1 || this.programForm.individual_attribute === 2) {
            if (this.programForm.contract_id === "") {
              this.$message({
                type: "warning",
                message: "为定制的时候合同编号，不能为空"
              });
              this.setting.loading = false;
              return;
            } else {
              args.contract_id = this.programForm.contract_id;
            }
          }
          args.member = member;
          if (this.programID) {
            args.id = this.programID;
            modifyProgram(this, args, this.programID)
              .then(res => {
                this.isRefresh = true;
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
                this.isRefresh = true;
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
  },
  beforeRouteLeave(to, from, next) {
    if (!this.isRefresh) {
      if (to.path == "/team/program") {
        to.meta.keepAlive = true;
      } else {
        to.meta.keepAlive = false;
      }
      next();
    } else {
      to.meta.keepAlive = false;
      next();
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
  .title {
    font-weight: 600;
    margin-bottom: 15px;
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
