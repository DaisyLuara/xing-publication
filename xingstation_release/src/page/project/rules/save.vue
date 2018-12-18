
<template>
  <div class="add-coupon-wrap">
    <div v-loading="setting.loading" :element-loading-text="setting.loadingText">
      <div class="coupon-title">{{ $route.name }}</div>
      <el-form ref="couponForm" :model="couponForm" :rules="rules" label-width="180px">
        <el-form-item
          :rules="{required: true, message: '公司不能为空', trigger: 'submit'}"
          label="公司"
          prop="company_id"
        >
          <el-select
            v-model="couponForm.company_id"
            placeholder="请选择公司"
            class="coupon-form-select"
            :disabled="couponID ? true : false"
          >
            <el-option
              v-for="item in companyList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="创建人" prop="user_name">
          <el-input v-model="user_name" :disabled="true" class="coupon-form-input"/>
        </el-form-item>
        <el-form-item label="商场" prop="marketid">
          <el-select
            v-model="couponForm.marketid"
            :remote-method="getMarket"
            :loading="searchLoading"
            :multiple-limit="1"
            multiple
            placeholder="请搜索"
            filterable
            remote
            clearable
            @change="marketChangeHandle"
            class="coupon-form-select"
          >
            <el-option
              v-for="item in marketList"
              :key="item.id"
              :label="item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="点位" prop="oid">
          <el-select
            v-model="couponForm.oid"
            :loading="searchLoading"
            :multiple-limit="10"
            placeholder="请选择"
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
        <el-form-item label="标题" prop="title">
          <el-input
            type="textarea"
            :maxlength="500"
            v-model="couponForm.title"
            class="coupon-form-input"
          />
        </el-form-item>
        <el-form-item label="动态库存" prop="dynamic_stock_status">
          <el-radio-group v-model="couponForm.dynamic_stock_status" @change="handleWriteOffStatus">
            <el-radio :label="0">关闭</el-radio>
            <el-tooltip
              class="item"
              effect="dark"
              content="计算规则：由剩余库存 减去 当天领取但未使用的优惠券数量"
              placement="right"
            >
              <el-radio :label="1">开启</el-radio>
            </el-tooltip>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="核销减库存" prop="write_off_status">
          <el-tooltip class="item" effect="dark" content="领券后，库存自动减少" placement="left">
            <el-radio
              v-model="couponForm.write_off_status"
              :label="0"
              :disabled="disabledWriteStatus"
            >关闭</el-radio>
          </el-tooltip>
          <el-tooltip class="item" effect="dark" content="核销后，库存自动减少" placement="right">
            <el-radio
              v-model="couponForm.write_off_status"
              :label="1"
              :disabled="disabledWriteStatus"
            >开启</el-radio>
          </el-tooltip>
        </el-form-item>
        <el-form-item
          :rules="{required: true, message: '优惠券名称不能为空', trigger: 'submit'}"
          label="优惠券名称"
          prop="name"
        >
          <el-input v-model="couponForm.name" class="coupon-form-input"/>
        </el-form-item>
        <el-form-item label="使用说明" prop="description">
          <el-input
            v-model="couponForm.description"
            :maxlength="1000"
            type="textarea"
            class="coupon-form-input"
          />
        </el-form-item>
        <el-form-item label="积分" prop="credit">
          <el-input v-model="couponForm.credit" class="coupon-form-input"/>
        </el-form-item>
        <el-form-item label="h5图片链接" prop="image_url">
          <el-input v-model="couponForm.image_url" class="coupon-form-input"/>
        </el-form-item>
        <el-form-item label="大屏图片链接" prop="bs_image_url">
          <el-input v-model="couponForm.bs_image_url" class="coupon-form-input"/>
        </el-form-item>
        <el-form-item label="跳转链接" prop="redirect_url">
          <el-input v-model="couponForm.redirect_url" class="coupon-form-input"/>
        </el-form-item>
        <el-form-item label="优先级" prop="sort_order">
          <el-input v-model="couponForm.sort_order" :maxlength="3" class="coupon-form-input"/>
        </el-form-item>
        <el-form-item label="类型" prop="type">
          <el-radio v-model="couponForm.type" :label="1">优惠券</el-radio>
          <el-radio v-model="couponForm.type" :label="2">小样</el-radio>
        </el-form-item>
        <el-form-item label="金额" prop="amount">
          <el-input v-model="couponForm.amount" :maxlength="6" class="coupon-form-input"/>
        </el-form-item>
        <el-form-item
          :rules="{required: true, message: '库存总数不能为空', trigger: 'submit'}"
          label="库存总数"
          prop="count"
        >
          <el-input v-model="couponForm.count" :maxlength="6" class="coupon-form-input"/>
        </el-form-item>
        <el-form-item
          :rules="{required: true, message: '剩余库存不能为空', trigger: 'submit'}"
          label="剩余库存"
          prop="stock"
        >
          <el-input v-model="couponForm.stock" :maxlength="6" class="coupon-form-input"/>
        </el-form-item>
        <el-form-item label="每人最大获取数" prop="people_max_get">
          <el-input v-model="couponForm.people_max_get" :maxlength="6" class="coupon-form-input"/>
        </el-form-item>
        <el-form-item label="是否开启每人无限领取" prop="pmg_status">
          <el-radio v-model="couponForm.pmg_status" :label="1">开启</el-radio>
          <el-radio v-model="couponForm.pmg_status" :label="0">关闭</el-radio>
        </el-form-item>
        <el-form-item label="每天最大获取数" prop="day_max_get">
          <el-input v-model="couponForm.day_max_get" :maxlength="6" class="coupon-form-input"/>
        </el-form-item>
        <el-form-item label="是否开启每天无限领取" prop="dmg_status">
          <el-radio v-model="couponForm.dmg_status" :label="1">开启</el-radio>
          <el-radio v-model="couponForm.dmg_status" :label="0">关闭</el-radio>
        </el-form-item>
        <el-form-item label="是否固定日期" prop="is_fixed_date">
          <el-radio-group v-model="couponForm.is_fixed_date" @change="fixedDateHandle">
            <el-radio :label="1">固定</el-radio>
            <el-radio :label="0">不固定</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item v-if="!dateShow" label="延后生效日期" prop="delay_effective_day">
          <el-input
            v-model="couponForm.delay_effective_day"
            :maxlength="6"
            class="coupon-form-input"
          />
        </el-form-item>
        <el-form-item v-if="!dateShow" label="有效天数" prop="effective_day">
          <el-input v-model="couponForm.effective_day" :maxlength="6" class="coupon-form-input"/>
        </el-form-item>
        <el-form-item v-if="dateShow" label="开始日期" prop="start_date">
          <el-date-picker
            v-model="couponForm.start_date"
            type="date"
            placeholder="选择日期"
            class="coupon-form-date"
            value-format="yyyy-MM-dd HH:mm:ss"
          />
        </el-form-item>
        <el-form-item v-if="dateShow" label="结束日期" prop="end_date">
          <el-date-picker
            v-model="couponForm.end_date"
            type="date"
            placeholder="选择日期"
            class="coupon-form-date"
            value-format="yyyy-MM-dd HH:mm:ss"
          />
        </el-form-item>
        <el-form-item label="状态" prop="is_active">
          <el-radio v-model="couponForm.is_active" :label="1">启用</el-radio>
          <el-radio v-model="couponForm.is_active" :label="0">停用</el-radio>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onSubmit('couponForm')">保存</el-button>
          <el-button @click="historyBack">取消</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import {
  handleDateTransform,
  historyBack,
  getCouponDetial,
  getSearchCompanyList,
  saveCoupon,
  getSearchMarketList,
  getSearchPointList
} from "service";

import {
  Button,
  Input,
  Form,
  FormItem,
  RadioGroup,
  Radio,
  DatePicker,
  Select,
  Option,
  Tooltip
} from "element-ui";

export default {
  name: "AddCoupon",
  components: {
    "el-button": Button,
    "el-input": Input,
    "el-form": Form,
    "el-form-item": FormItem,
    "el-radio-group": RadioGroup,
    "el-radio": Radio,
    "el-select": Select,
    "el-date-picker": DatePicker,
    "el-option": Option,
    "el-tooltip": Tooltip
  },
  data() {
    var checkEndDate = (rule, value, callback) => {
      if (!value) {
        callback();
        return;
      }
      if (
        new Date(value.replace(/\-/g, "/")).getTime() <
        new Date(this.couponForm.start_date.replace(/\-/g, "/")).getTime()
      ) {
        callback(new Error("结束日期要大于开始日期"));
      } else {
        callback();
      }
    };
    var checkSortOrder = (rule, value, callback) => {
      if (!value) {
        callback();
        return;
      }
      if (/^[0-9]+.?[0-9]*$/.test(value)) {
        if (parseInt(value) < 1 || parseInt(value) > 100) {
          callback(new Error("优先级只能是1-100"));
        } else {
          callback();
        }
      } else {
        callback("优先级只能是数字");
      }
    };
    return {
      dateShow: false,
      companyList: [],
      setting: {
        isOpenSelectAll: true,
        loading: false,
        loadingText: "拼命加载中"
      },
      rules: {
        end_date: [{ validator: checkEndDate, trigger: "submit" }],
        sort_order: [{ validator: checkSortOrder, trigger: "submit" }]
      },
      disabledWriteStatus: false,
      user_name: "",
      marketList: [],
      pointList: [],
      searchLoading: false,
      couponForm: {
        name: "",
        title: "",
        dynamic_stock_status: 0,
        description: "",
        company_id: "",
        type: 1,
        image_url: "",
        redirect_url: "",
        amount: 0,
        count: 0,
        sort_order: 1,
        stock: 0,
        people_max_get: 0,
        pmg_status: 0,
        day_max_get: 0,
        dmg_status: 0,
        is_fixed_date: 0,
        delay_effective_day: 0,
        effective_day: 0,
        start_date: "",
        end_date: "",
        is_active: 1,
        write_off_status: 1,
        bs_image_url: "",
        credit: 0,
        marketid: [],
        oid: []
      },
      couponID: ""
    };
  },
  created() {
    if (this.setting.loading == true) {
      return false;
    }
    let user = JSON.parse(this.$cookie.get("user_info"));
    this.user_name = user.name;
    this.couponID = this.$route.params.uid;
    this.setting.loadingText = "拼命加载中";
    this.setting.loading = true;

    //获取公司列表
    let companyPromise = getSearchCompanyList(this)
      .then(result => {
        this.companyList = result.data;
      })
      .catch(error => {
        console.log(error);
      });

    Promise.all([companyPromise]).then(() => {
      if (this.couponID) {
        let args = {
          include: "user,company,market,point"
        };
        getCouponDetial(this, this.couponID, args)
          .then(result => {
            result.market
              ? this.couponForm.marketid.push(result.market.id)
              : [];
            result.point
              ? result.point.data.map(r => {
                  let id = r.id;
                  this.couponForm.oid.push(id);
                })
              : [];
            if (result.market) {
              this.getMarket(result.market.name);
            }
            if (result.point) {
              this.getPoint();
            }
            this.couponForm.name = result.name;
            this.couponForm.description = result.description;
            this.couponForm.company_id = result.company.id;
            this.couponForm.image_url = result.image_url;
            this.couponForm.amount = result.amount;
            this.couponForm.count = result.count;
            this.couponForm.stock = result.stock;
            this.couponForm.people_max_get = result.people_max_get;
            this.couponForm.pmg_status = result.pmg_status;
            this.couponForm.day_max_get = result.day_max_get;
            this.couponForm.dmg_status = result.dmg_status;
            this.couponForm.is_fixed_date = result.is_fixed_date;
            this.couponForm.delay_effective_day = result.delay_effective_day;
            this.couponForm.effective_day = result.effective_day;
            this.couponForm.start_date = result.start_date;
            this.couponForm.end_date = result.end_date;
            this.couponForm.is_active = result.is_active;
            this.couponForm.redirect_url = result.redirect_url;
            this.couponForm.type = result.type;
            this.couponForm.sort_order = result.sort_order;
            this.couponForm.title = result.title;
            this.user_name = result.user.name;
            this.couponForm.dynamic_stock_status = result.dynamic_stock_status;
            this.couponForm.write_off_status = result.write_off_status;
            this.couponForm.credit = result.credit;
            this.couponForm.bs_image_url = result.bs_image_url;
            if (result.is_fixed_date === 1) {
              this.dateShow = true;
            } else {
              this.dateShow = false;
            }
            if (
              parseInt(result.write_off_status) === 1 &&
              parseInt(result.dynamic_stock_status) === 1
            ) {
              this.disabledWriteStatus = true;
            }
            this.setting.loading = false;
          })
          .catch(error => {
            console.log(error);
            this.setting.loading = false;
          });
      } else {
        this.user_name = user.name;
        this.setting.loading = false;
      }
    });
  },
  methods: {
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
    fixedDateHandle(val) {
      if (val === 0) {
        this.dateShow = false;
      } else {
        this.dateShow = true;
      }
    },
    handleWriteOffStatus(e) {
      if (e === 1) {
        this.couponForm.write_off_status = 1;
        this.disabledWriteStatus = true;
      } else {
        this.disabledWriteStatus = false;
      }
    },
    onSubmit(formName) {
      let company_id = this.couponForm.company_id;
      let args = {
        name: this.couponForm.name,
        description: this.couponForm.description,
        image_url: this.couponForm.image_url,
        amount: this.couponForm.amount,
        count: this.couponForm.count,
        stock: this.couponForm.stock,
        people_max_get: this.couponForm.people_max_get,
        pmg_status: this.couponForm.pmg_status,
        day_max_get: this.couponForm.day_max_get,
        dmg_status: this.couponForm.dmg_status,
        is_fixed_date: this.couponForm.is_fixed_date,
        delay_effective_day: this.couponForm.delay_effective_day,
        effective_day: this.couponForm.effective_day,
        is_active: this.couponForm.is_active,
        redirect_url: this.couponForm.redirect_url,
        type: this.couponForm.type,
        sort_order: this.couponForm.sort_order,
        title: this.couponForm.title,
        dynamic_stock_status: this.couponForm.dynamic_stock_status,
        write_off_status: this.couponForm.write_off_status,
        bs_image_url: this.couponForm.bs_image_url,
        credit: this.couponForm.credit,
        marketid: this.couponForm.marketid.join(","),
        oid: this.couponForm.oid
      };
      if (!this.couponForm.image_url) {
        delete args.image_url;
      }
      if (this.couponForm.title === "") {
        delete args.title;
      }
      if (this.couponForm.redirect_url === "") {
        delete args.redirect_url;
      }
      if (!this.couponForm.description) {
        delete args.description;
      }
      if (this.couponForm.start_date) {
        args.start_date = this.couponForm.start_date;
      }
      if (this.couponForm.end_date) {
        args.end_date = handleDateTransform(this.couponForm.end_date);
      }
      if (this.couponForm.is_fixed_date === 1) {
        if (!(this.couponForm.end_date && this.couponForm.start_date)) {
          this.$message({
            message: "选择固定日期，开始时间，结束时间都要填写！",
            type: "warning"
          });
          return;
        } else {
          delete args.effective_day;
          delete args.delay_effective_day;
        }
      } else {
        delete args.start_date;
        delete args.end_date;
      }
      this.$refs[formName].validate(valid => {
        if (valid) {
          saveCoupon(this, args, this.couponID, company_id)
            .then(result => {
              this.loading = false;
              this.$message({
                message: this.couponID ? "修改成功" : "添加成功",
                type: "success"
              });
              this.$router.push({
                path: "/project/rules/"
              });
            })
            .catch(error => {
              this.loading = false;
              this.$message({
                message: error.response.data.message,
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
.add-coupon-wrap {
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
