<template>
  <div class="add-coupon-import-wrap">
    <div
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText">
      <div class="coupon-title">{{ $route.name }}</div>
      <el-form
        ref="couponForm"
        :model="couponForm"
        label-width="180px">
        <el-form-item
          :rules="{required: true, message: '公司不能为空', trigger: 'submit'}"
          label="公司"
          prop="company_id"
        >
          <el-select
            v-model="couponForm.company_id"
            :loading="searchLoading"
            filterable
            placeholder="请选择公司"
            class="coupon-form-select"
            @change="handleCompany"
          >
            <el-option
              v-for="item in companyList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item
          :rules="{required: true, message: '奖品模版不能为空', trigger: 'submit'}"
          label="奖品模版"
          prop="policy_id"
        >
          <el-select
            v-model="couponForm.policy_id"
            :loading="searchLoading"
            filterable
            placeholder="请选择奖品模版"
            class="coupon-form-select"
          >
            <el-option
              v-for="item in policiesList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item
          label="创建人"
          prop="user_name">
          <el-input
            v-model="user_name"
            :disabled="true"
            class="coupon-form-input"/>
        </el-form-item>
        <el-form-item
          label="投放场地（嗨蚪）"
          prop="marketid">
          <el-select
            v-model="couponForm.marketid"
            :remote-method="getMarket"
            :loading="searchLoading"
            :multiple-limit="1"
            multiple
            placeholder="请搜索投放场地（嗨蚪）"
            filterable
            remote
            clearable
            class="coupon-form-select"
            @change="marketChangeHandle"
          >
            <el-option
              v-for="item in marketList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item
          label="投放点位（嗨蚪）"
          prop="oid">
          <el-select
            v-model="couponForm.oid"
            :loading="searchLoading"
            :multiple-limit="10"
            placeholder="请选择投放点位（嗨蚪）"
            multiple
            filterable
            clearable
            class="coupon-form-select"
          >
            <el-option
              v-for="item in pointList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item
          :rules="{required: true, message: '适用场景不能为空', trigger: 'submit'}"
          label="适用场景"
          prop="scene_type">
          <el-radio-group
            v-model="couponForm.scene_type"
            @change="handleSceneType">
            <el-tooltip
              class="item"
              effect="dark"
              content="可在同一场地下的多家商户核销"
              placement="top">
              <el-radio :label="1">场地通用</el-radio>
            </el-tooltip>
            <el-tooltip
              class="item"
              effect="dark"
              content="仅供某一特定场地核销"
              placement="top">
              <el-radio :label="2">场地自营</el-radio>
            </el-tooltip>
            <el-tooltip
              class="item"
              effect="dark"
              content="可在同一主体下，多家连锁商户核销"
              placement="top">
              <el-radio :label="3">商户通用</el-radio>
            </el-tooltip>
            <el-tooltip
              class="item"
              effect="dark"
              content="仅供某一特定商户核销"
              placement="top">
              <el-radio :label="4">商户自营</el-radio>
            </el-tooltip>
          </el-radio-group>
        </el-form-item>
        <el-form-item
          :rules="writeOffMidRules"
          label="适用场地"
          prop="write_off_mid">
          <el-select
            v-model="couponForm.write_off_mid"
            :loading="searchLoading"
            :disabled="writeOffMarketShow"
            placeholder="请选择适用场地"
            filterable
            clearable
            class="coupon-form-select"
            @change="writeOffMarketHandle"
          >
            <el-option
              v-for="item in writeOffMarketList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item
          :rules="writeOffSidRules"
          label="适用商户"
          prop="write_off_sid">
          <el-select
            v-model="couponForm.write_off_sid"
            :disabled="writeOffSiteShow"
            :loading="searchLoading"
            :multiple-limit="multipleNum"
            multiple
            placeholder="请选择适用商户"
            filterable
            clearable
            class="coupon-form-select"
          >
            <el-option
              v-for="item in writeOffSiteList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item
          label="导入数据EXCEL文档"
          prop="ids">
          <el-upload
            ref="upload"
            :action="Domain"
            :data="uploadForm"
            :on-success="handleSuccess"
            :before-upload="beforeUpload"
            :on-remove="handleRemove"
            :before-remove="beforeRemove"
            :file-list="fileList"
            :limit="1"
            :on-exceed="handleExceed"
            class="upload-demo"
          >
            <el-button
              size="small"
              type="primary">点击上传</el-button>
            <div
              slot="tip"
              style="display:inline-block"
              class="el-upload__tip"
            >文件类型只支持=xlsx、xls</div>
          </el-upload>
        </el-form-item>


        <el-form-item>
          <el-button
            type="primary"
            @click="onSubmit('couponForm')">保存</el-button>
          <el-button @click="historyBack">取消</el-button>
        </el-form-item>
      </el-form>

      <h3>导入的Excel格式要求如下：</h3>
      <p>
        <span>1、第一行为列名，请注意数据先后</span><br>
        <span>2、当无限领取【开启】时，最大获取数请填写 0</span><br>
        <span>3、时间格式为【yyyy-mm-ss hh:mm:ss】(右击单元格，可设置自定义)</span><br>
      </p>
      <img 
        :src="getImportFormatImg()" 
        width="100%">
    </div>
  </div>
</template>

<script>
import {
  saveCouponByImport,
  handleDateTransform,
  historyBack,
  getSearchCompany,
  getSearchMarketList,
  getSearchPointList,
  getStoresList,
  getPoliciesListByCompany,
  getMediaUpload,
  getQiniuToken,
  getCompanyMarketList
} from "service";

import {
  Button,
  Upload,
  Input,
  Form,
  FormItem,
  RadioGroup,
  Radio,
  DatePicker,
  Select,
  Option,
  Tooltip,
  Tabs,
  TabPane
} from "element-ui";

export default {
  name: "AddCouponImport",
  components: {
    "el-upload": Upload,
    "el-button": Button,
    "el-input": Input,
    "el-form": Form,
    "el-form-item": FormItem,
    "el-radio-group": RadioGroup,
    "el-radio": Radio,
    "el-select": Select,
    "el-date-picker": DatePicker,
    "el-option": Option,
    "el-tooltip": Tooltip,
    "el-tabs": Tabs,
    "el-tab-pane": TabPane
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

      multipleNum: 0,
      writeOffMarketShow: true,
      writeOffSiteShow: true,
      peopleReceiveShow: true,
      peopleDayShow: true,
      activeName: "first",
      dateShow: false,
      companyList: [],
      policiesList:[],
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: "拼命加载中"
      },

      writeOffSidRules: null,
      writeOffMidRules: {
        required: true,
        message: "核销场地不能为空",
        trigger: "submit"
      },
      disabledWriteStatus: false,
      user_name: "",
      marketList: [],
      pointList: [],
      writeOffSiteList: [],
      writeOffMarketList: [],
      searchLoading: false,

      couponForm: {
        company_id: "",
        marketid: [],
        oid: [],
        scene_type: null,
        write_off_mid: null,
        write_off_sid: [],
        policy_id:null,
      },
    };
  },
  created() {
    let user = JSON.parse(this.$cookie.get("user_info"));
    this.user_name = user.name;
    this.setting.loadingText = "拼命加载中";
    this.getQiniuToken();
    this.getSearchCompany();
    this.user_name = user.name;

  },
  methods: {
    getImportFormatImg(){
      return require("../../../assets/images/import_coupons_format.png");
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

    handleExceed(files, fileList) {
      this.$message.warning(
        `当前限制选择 1 个文件，本次选择了 ${
          files.length
          } 个文件，共选择了 ${files.length + fileList.length} 个文件`
      );
    },
    handleRemove(file, fileList) {
      this.fileList = fileList;
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
      let key = (`${suffix}`);
      if (
        !(
          type === ".xlsx" ||
          type === ".xls"
        )
      ) {
        this.uploadForm.token = "";
        return this.$message.error(
          "文件类型只支持xlsx、xls"
        );
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


    handleCompany(val) {
      this.getCompanyMarketList(val);
      this.getStoresList(val);
      this.getPoliciesList(val);
    },
    writeOffMarketHandle() {
      this.couponForm.write_off_sid = [];
      if (this.couponForm.scene_type === 1) {
        this.getStoresList(this.couponForm.write_off_mid, true);
      }
    },

    getPoliciesList(val) {
      getPoliciesListByCompany(this, val)
        .then(res => {
          console.log(res);
          this.policiesList = res;
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },

    getStoresList(val, type) {
      let args = {
        company_id: val
      };
      if (type) {
        args.market_id = val;
        delete args.company_id;
      }

      getStoresList(this, args)
        .then(res => {
          this.writeOffSiteList = res;
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    getCompanyMarketList(val) {
      let args = {
        company_id: val
      };
      getCompanyMarketList(this, args)
        .then(res => {
          this.writeOffMarketList = res;
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    handleSceneType(val) {
      if (val === 1) {
        this.writeOffMidRules = {
          required: true,
          message: "核销场地不能为空",
          trigger: "submit"
        };
        this.writeOffSidRules = null;
        this.writeOffSiteShow = false;
        this.writeOffMarketShow = false;
        this.multipleNum = 0;
      } else if (val === 2) {
        this.writeOffMarketShow = false;
        this.writeOffSiteShow = true;
        this.writeOffSidRules = null;
        this.couponForm.write_off_sid = [];
        this.writeOffMidRules = {
          required: true,
          message: "核销场地不能为空",
          trigger: "submit"
        };
        this.multipleNum = 1;
      } else if (val === 3) {
        this.couponForm.write_off_mid = null;
        this.writeOffMarketShow = true;
        this.writeOffSiteShow = false;
        this.writeOffMidRules = null;
        this.writeOffSidRules = null;
        this.multipleNum = 0;
      } else {
        this.couponForm.write_off_mid = null;
        this.couponForm.write_off_sid = [];
        this.writeOffMarketShow = true;
        this.writeOffSiteShow = false;
        this.writeOffMidRules = null;
        this.writeOffSidRules = {
          required: true,
          message: "核销商户不能为空",
          trigger: "submit"
        };
        this.multipleNum = 1;
      }
    },
    getSearchCompany() {
      this.searchLoading = true;
      getSearchCompany(this)
        .then(result => {
          this.searchLoading = false;
          this.companyList = result.data;
        })
        .catch(error => {
          this.searchLoading = false;

          console.log(error);
        });
    },
    marketChangeHandle() {
      this.couponForm.oid = [];
      this.getPoint();
    },
    getPoint() {
      let args = {
        include: "market",
        market_id: this.couponForm.marketid
      };
      this.searchLoading = true;
      return getSearchPointList(this, args)
        .then(response => {
          this.pointList = response.data;
          this.searchLoading = false;
        })
        .catch(err => {
          this.searchLoading = false;
          console.log(err);
        });
    },
    getMarket(query) {
      if (query !== "") {
        this.searchLoading = true;
        let args = {
          name: query,
          include: "area"
        };
        return getSearchMarketList(this, args)
          .then(response => {
            this.marketList = response.data;
            if (this.marketList.length == 0) {
              this.couponForm.marketid = [];
              this.marketList = [];
            }
            this.searchLoading = false;
          })
          .catch(err => {
            console.log(err);
            this.searchLoading = false;
          });
      } else {
        this.marketList = [];
      }
    },

    onSubmit(formName) {
      let company_id = this.couponForm.company_id;

      let operationMediaIds = [];
      if (this.fileList.length > 0) {
        this.fileList.map(r => {
          operationMediaIds.push(r.id);
        });
        this.ids = operationMediaIds.join(",");
      } else {
        this.$message({
          type: "warning",
          message: "导入execel必须上传"
        });
        return;
      }

      let args = {
        marketid: this.couponForm.marketid.join(","),
        oid: this.couponForm.oid,
        scene_type: this.couponForm.scene_type,
        write_off_mid: this.couponForm.write_off_mid,
        write_off_sid: this.couponForm.write_off_sid,
        policy_id: this.couponForm.policy_id,
        media_id: this.ids
      };

      if (
        args.write_off_sid.length === 0 &&
        (this.scene_type === 1 || this.scene_type === 3)
      ) {
        this.writeOffSiteList.map(r => {
          args.write_off_sid.push(r.id);
        });
      }

      this.$refs[formName].validate(valid => {
        if (valid) {
          console.log("saveCouponByImport");
          console.log(company_id);
          console.log(args);
          saveCouponByImport(this,company_id, args)
            .then(result => {
              this.loading = false;
              this.$message({
                type: "success",
                message: "导入成功"
              });
              this.$router.push({
                path: "/prize/rules/"
              });
            })
            .catch(error => {
              this.loading = false;
              console.log(error.response);
              this.$message({
                message:(error.response && error.response.data.message) ? error.response.data.message : '出现错误',
                type: "warning"
              });
            });
        }
      });
    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
    },
    historyBack() {
      historyBack();
    }
  }
};
</script>
<style scoped lang="less">
.add-coupon-import-wrap {
  background: #fff;
  padding: 20px;
  .coupon-form-input {
    width: 300px;
  }
  .coupon-form-date {
    width: 300px;
  }
  .coupon-form-select {
    width: 300px;
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
  .coupon-title {
    margin-bottom: 20px;
  }
  .el-checkbox {
    margin-left: 0px;
    margin-right: 15px;
  }
}
</style>
