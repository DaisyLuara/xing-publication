<template>
  <div class="item-wrap-template">
    <div 
      v-loading="setting.loading" 
      :element-loading-text="setting.loadingText" 
      class="pane">
      <div class="pane-title">{{ businessID ? '修改商户' : '新增商户' }}</div>
      <el-form
        ref="businessForm"
        :model="businessForm"
        :rules="rules"
        label-width="150px"
        class="business-form"
      >
        <el-tabs 
          v-model="activeName" 
          type="card">
          <el-tab-pane 
            label="商户配置" 
            name="first">
            <el-form-item 
              label="商户名称" 
              prop="name">
              <el-input 
                v-model="businessForm.name" 
                placeholder="请输入商户名称" 
                class="item-input"/>
            </el-form-item>
            <el-form-item 
              label="公司名称" 
              prop="company_id">
              <el-select
                v-model="businessForm.company_id"
                :loading="searchLoading"
                placeholder="请选择公司名称"
                filterable
                @change="companyHandle"
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
              label="商户属性" 
              prop="type">
              <el-radio-group v-model="businessForm.type">
                <el-radio :label="1">自营</el-radio>
                <el-radio :label="2">连锁</el-radio>
              </el-radio-group>
            </el-form-item>
            <el-form-item 
              label="关联场地" 
              prop="market">
              <el-radio-group 
                v-model="businessForm.market" 
                @change="handleRelatedField">
                <el-radio :label="1">是</el-radio>
                <el-radio :label="2">否</el-radio>
              </el-radio-group>
            </el-form-item>
            <el-form-item 
              label="区域" 
              prop="areaid">
              <el-select
                v-model="businessForm.areaid"
                :loading="searchLoading"
                placeholder="请选择区域"
                filterable
                @change="areaHandle"
              >
                <el-option
                  v-for="item in areaList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>
            <el-form-item 
              v-if="marketShow" 
              label="场地名称" 
              prop="marketid">
              <el-select
                v-model="businessForm.marketid"
                :remote-method="getMarket"
                :loading="searchLoading"
                placeholder="请输入场地名称"
                filterable
                remote
                clearable
              >
                <el-option
                  v-for="item in markteList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>
            <el-form-item 
              label="商户logo" 
              prop="media_id">
              <div 
                class="avatar-uploader" 
                @click="panelVisible=true">
                <img 
                  v-if="logoUrl" 
                  :src="logoUrl" 
                  class="avatar">
                <i 
                  v-else 
                  class="el-icon-plus avatar-uploader-icon"/>
              </div>
            </el-form-item>
            <el-form-item 
              label="商户电话" 
              prop="phone">
              <el-input 
                v-model="businessForm.phone" 
                placeholder="请输入商户电话" 
                class="item-input"/>
            </el-form-item>
            <el-form-item 
              label="商户地址" 
              prop="address">
              <el-input 
                v-model="businessForm.address" 
                placeholder="请输入商户地址" 
                class="item-input"/>
            </el-form-item>
            <el-form-item 
              label="商户详情" 
              prop="description">
              <el-input
                v-model="businessForm.description"
                type="textarea"
                placeholder="请输入商户详情"
                class="item-input"
              />
            </el-form-item>
          </el-tab-pane>
          <el-tab-pane 
            v-loading="contractFlag" 
            label="合约配置" 
            name="second">
            <el-form-item 
              label="合同" 
              prop="contract">
              <el-radio-group 
                v-model="businessForm.contract" 
                @change="handleContract">
                <el-radio :label="0">无</el-radio>
                <el-radio :label="1">有</el-radio>
              </el-radio-group>
            </el-form-item>
            <el-form-item 
              v-if="contractShow" 
              label="合同编号" 
              prop="contract_id">
              <el-select 
                v-model="businessForm.contract_id" 
                placeholder="请选择合同编号">
                <el-option
                  v-for="item in contractList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>
            <el-form-item 
              label="所属人" 
              prop="user_id">
              <el-select
                v-model="businessForm.user_id"
                :loading="searchLoading"
                filterable
                placeholder="请选择所属人"
              >
                <el-option
                  v-for="item in userList"
                  :key="item.id"
                  :label="item.name"
                  :value="item.id"
                />
              </el-select>
            </el-form-item>
            <el-form-item 
              label="合同开始时间" 
              prop="start_date">
              <el-date-picker
                v-model="businessForm.start_date"
                type="date"
                placeholder="选择日期"
                class="coupon-form-date"
              />
            </el-form-item>
            <el-form-item 
              label="合同结束时间" 
              prop="end_date">
              <el-date-picker
                v-model="businessForm.end_date"
                type="date"
                placeholder="选择日期"
                class="coupon-form-date"
              />
            </el-form-item>
          </el-tab-pane>
        </el-tabs>
        <el-form-item>
          <el-button 
            :loading="btnLoading" 
            type="primary" 
            @click="submit('businessForm')">保存</el-button>
          <el-button @click="historyBack">返回</el-button>
        </el-form-item>
      </el-form>
    </div>
    <PicturePanel 
      :panel-visible.sync="panelVisible" 
      :single-flag="singleFlag" 
      @close="handleClose"/>
  </div>
</template>

<script>
import PicturePanel from "components/common/picturePanel";

import {
  historyBack,
  getBusinessDetail,
  saveBusiness,
  modifyBusiness,
  getSearchAeraList,
  getSearchMarketList,
  getSearchCompany,
  getSearchUserList,
  getContractReceiptList,
  handleDateTimeTransform,
  getSearchCustomer
} from "service";

import {
  Form,
  Select,
  Option,
  FormItem,
  Button,
  Input,
  DatePicker,
  Tabs,
  TabPane,
  RadioGroup,
  Radio,
  Tooltip,
  Checkbox,
  CheckboxGroup,
  MessageBox
} from "element-ui";

export default {
  components: {
    ElForm: Form,
    ElSelect: Select,
    ElOption: Option,
    ElFormItem: FormItem,
    ElButton: Button,
    ElInput: Input,
    ElDatePicker: DatePicker,
    ElTabs: Tabs,
    ElTabPane: TabPane,
    ElRadioGroup: RadioGroup,
    ElRadio: Radio,
    ElTooltip: Tooltip,
    ElCheckboxGroup: CheckboxGroup,
    ElCheckbox: Checkbox,
    PicturePanel
  },
  data() {
    let checkEnterEndDate = (rule, value, callback) => {
      if (!value) {
        return callback(new Error("合同结束时间不能为空"));
      }
      if (
        new Date(value.replace(/\-/g, "/")).getTime() <
        new Date(
          this.businessForm.contract.start_date.replace(/\-/g, "/")
        ).getTime()
      ) {
        callback(new Error("结束日期要大于开始日期"));
      } else {
        callback();
      }
    };
    let checkNumber = (rule, value, callback) => {
      if (value < 0) {
        callback(new Error("不能小于0"));
      } else if (typeof parseFloat(value) !== "number") {
        callback(new Error("必须是数字"));
      } else {
        callback();
      }
    };
    return {
      panelVisible: false,
      singleFlag: true,
      btnLoading: false,
      logoUrl: "",
      marketShow: true,
      contractShow: true,
      contractFlag: false,
      searchLoading: false,
      activeName: "first",
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: "拼命加载中"
      },
      contractList: [],
      companyList: [],
      userList: [],
      businessID: "",
      passwordShow: false,
      businessForm: {
        media_id: null,
        marketid: null,
        areaid: null,
        company_id: null,
        type: 1,
        market: 1,
        name: "",
        phone: "",
        address: "",
        description: "",
        contract: 1,
        contract_id: null,
        user_id: null,
        start_date: "",
        end_date: ""
      },
      markteList: [],
      areaList: [],
      customerList: [],
      rules: {
        name: [{ required: true, message: "请输入名称", trigger: "submit" }],
        areaid: [{ required: true, message: "请选择区域", trigger: "submit" }],
        company_id: [
          { required: true, message: "请选择公司", trigger: "submit" }
        ],
        marketid: [
          { required: true, message: "请输入场地名称", trigger: "submit" }
        ],
        type: [
          { required: true, message: "请选择商户属性", trigger: "submit" }
        ],
        market: [
          { required: true, message: "请选择关联场地", trigger: "submit" }
        ]
      }
    };
  },
  created() {
    this.getSearchCompany();
    this.setting.loading = true;
    this.businessID = this.$route.params.uid;
    this.getAreaList();
    this.getSearchUserList();
    if (this.businessID) {
      this.getBusinessDetail();
    } else {
      this.setting.loading = false;
    }
  },
  methods: {
    handleClose(data) {
      if (data && data.length > 0) {
        let { media_id, url } = data[0];
        this.businessForm.media_id = media_id;
        this.logoUrl = url;
      } else {
        // this.$message({
        //   type: "warning",
        //   message: "图片上传失败"
        // });
      }
    },
    getSearchCustomer(val) {
      this.searchLoading = true;
      let args = {
        company_id: val
      };
      getSearchCustomer(this, args)
        .then(res => {
          this.customerList = res;
          this.searchLoading = false;
        })
        .catch(err => {
          this.searchLoading = false;

          this.$message({
            type: "warning",
            message: err.response.date.message
          });
        });
    },
    handleRelatedField(val) {
      if (val === 1) {
        this.marketShow = true;
      } else {
        this.marketShow = false;
      }
    },
    handleContract(val) {
      if (val === 1) {
        this.contractShow = true;
      } else {
        this.contractShow = false;
      }
    },
    companyHandle(val, noClear) {
      let args = {
        company_id: val
      };
      this.getSearchCustomer(val);
      getContractReceiptList(this, args)
        .then(res => {
          this.contractList = res;
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message: err.response.date.message
          });
        });
    },
    getSearchUserList() {
      this.searchLoading = true;
      getSearchUserList(this)
        .then(res => {
          this.searchLoading = false;
          this.userList = res.data;
        })
        .catch(err => {
          this.searchLoading = false;
          this.$message({
            type: "warning",
            message: err.response.date.message
          });
        });
    },
    getSearchCompany() {
      this.searchLoading = true;
      getSearchCompany(this)
        .then(res => {
          this.companyList = res.data;
          this.searchLoading = false;
        })
        .catch(err => {
          this.searchLoading = false;

          this.$message({
            type: "warning",
            message: err.response.date.message
          });
        });
    },
    getBusinessDetail() {
      this.setting.loading = true;
      let id = this.businessID;
      let args = {
        include: "company,market,area,contract,media,user,customer"
      };
      getBusinessDetail(this, args, id)
        .then(res => {
          this.businessForm.name = res.name;
          this.businessForm.areaid = res.area.id;
          this.businessForm.company_id = res.company.id;
          this.businessForm.marketid = res.market ? res.market.id : null;
          this.businessForm.market = res.market ? 1 : 2;
          if (res.market) {
            this.getMarket(res.market.name);
          }
          this.marketShow = res.market ? true : false;
          this.businessForm.phone = res.phone;
          this.businessForm.address = res.address;
          this.businessForm.description = res.description;
          this.businessForm.type = res.type;
          this.businessForm.start_date = res.contract
            ? res.contract.start_date
            : "";
          this.businessForm.end_date = res.contract
            ? res.contract.end_date
            : "";
          this.businessForm.contract_id = res.contract ? res.contract.id : null;
          this.businessForm.contract = res.contract ? 1 : 0;
          this.contractShow = res.contract ? true : false;
          this.businessForm.user_id = res.user ? res.user.id : null;
          this.businessForm.media_id = res.media ? res.media.id : null;
          this.logoUrl = res.media ? res.media.url : "";
          this.companyHandle(this.businessForm.company_id, true);
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
    getAreaList() {
      return getSearchAeraList(this)
        .then(res => {
          this.areaList = res.data;
          this.setting.loading = false;
        })
        .catch(error => {
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    areaHandle() {
      this.businessForm.marketid = null;
      this.getMarket(this.businessForm.marketid);
    },
    getMarket(query) {
      this.searchLoading = true;
      let args = {
        name: query,
        include: "area",
        area_id: this.businessForm.areaid
      };
      return getSearchMarketList(this, args)
        .then(response => {
          this.markteList = response.data;
          if (this.markteList.length == 0) {
            this.businessForm.marketid = null;
            this.businessForm.markteList = [];
          }
          this.setting.loading = false;
          this.searchLoading = false;
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
          this.setting.loading = false;
          this.searchLoading = false;
        });
    },
    historyBack() {
      historyBack();
    },
    submit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          let args = this.businessForm;
          if (this.businessForm.start_date) {
            args.start_date = handleDateTimeTransform(
              this.businessForm.start_date
            );
          } else {
            args.start_date = null;
          }
          if (this.businessForm.end_date) {
            args.end_date = handleDateTimeTransform(this.businessForm.end_date);
          } else {
            args.end_date = null;
          }
          if (this.businessForm.contract === 1) {
            if (this.businessForm.contract_id === null) {
              this.$message({
                type: "warning",
                message: "有合同，合同编号必填"
              });
              return;
            }
          }
          delete args.contract;
          delete args.market;
          if (this.businessID) {
            this.btnLoading = true;
            modifyBusiness(this, args, this.businessID)
              .then(res => {
                this.btnLoading = false;
                this.$message({
                  message: "商户修改成功",
                  type: "success"
                });
                this.$router.push({
                  path: "/market/business"
                });
              })
              .catch(err => {
                this.btnLoading = false;
                this.$message({
                  message: err.response.data.message,
                  type: "warning"
                });
              });
          } else {
            this.btnLoading = true;
            saveBusiness(this, args)
              .then(res => {
                this.btnLoading = false;
                this.$message({
                  message: "商户保存成功",
                  type: "success"
                });
                this.$router.push({
                  path: "/market/business"
                });
              })
              .catch(err => {
                this.btnLoading = false;
                this.$message({
                  message: err.response.data.message,
                  type: "warning"
                });
              });
          }
        } else {
          this.$message({
            showClose: true,
            message: "请填写完表单必填项，在提交",
            type: "error",
            duration: 5000
          });
        }
      });
    }
  }
};
</script>

<style lang="less" scoped>
.item-wrap-template {
  .business-form {
    width: 900px;
    .avatar-uploader {
      width: 178px;
      height: 178px;
      line-height: 178px;
      border: 1px dashed #d9d9d9;
      border-radius: 6px;
    }
    .avatar-uploader .el-upload {
      cursor: pointer;
      position: relative;
      overflow: hidden;
    }
    .avatar-uploader .el-upload:hover {
      border-color: #409eff;
    }
    .avatar-uploader-icon {
      font-size: 28px;
      color: #8c939d;
      width: 178px;
      height: 178px;
      line-height: 178px;
      text-align: center;
    }
    .avatar {
      width: 178px;
      height: 178px;
      display: block;
    }
  }
  .pane {
    border-radius: 5px;
    background-color: white;
    padding: 20px 40px 80px;

    .el-select,
    .item-input,
    .el-date-editor.el-input {
      width: 380px;
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
