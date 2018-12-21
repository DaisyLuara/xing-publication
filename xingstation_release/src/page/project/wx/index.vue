<template>
  <div class="root">
    <div
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText"
      class="item-list-wrap"
    >
      <div class="item-content-wrap">
        <div class="total-wrap">
          <span class="label">总数:{{ pagination.total }}</span>
          <div>
            <el-button
              size="small"
              type="success"
              @click="addCoupon"
            >新增</el-button>
          </div>
        </div>
        <!-- 创建优惠券 -->
        <el-dialog
          :title="title"
          :visible.sync="templateVisible"
          @close="dialogClose"
        >
          <el-form
            ref="templateForm"
            :model="templateForm"
            label-width="150px"
          >

            <!-- <el-radio
              v-model="radio"
              label="1"
            >我要创建普通优惠券</el-radio>
            <el-form-item>
              <template slot-scope="scope">传统优惠券的电子版，可在微信中收纳、传播和使用。只可领取到我的卡券自己使用</template>
            </el-form-item> -->
            <el-radio
              v-model="card_type"
              :label="DISCOUNT.card_type"
            >折扣券</el-radio>
            <el-form-item>
              <template slot-scope="scope">可为用户提供消费折扣</template>
            </el-form-item>
            <el-radio
              v-model="card_type"
              :label="CASH.card_type"
            >代金券</el-radio>
            <el-form-item>
              <template slot-scope="scope">可为用户提供抵扣现金服务。可设置成为"满*元，减*元"</template>
            </el-form-item>
            <el-radio
              v-model="card_type"
              :label="GIFT.card_type"
            >兑换券</el-radio>
            <el-form-item>
              <template slot-scope="scope">可为用户提供消费送赠品服务</template>
            </el-form-item>
            <el-radio
              v-model="card_type"
              :label="GROUPON.card_type"
            >团购券</el-radio>
            <el-form-item>
              <template slot-scope="scope">可为用户提供团购套餐服务</template>
            </el-form-item>
            <el-radio
              v-model="card_type"
              :label="GENERAL_COUPON.card_type"
            >优惠券</el-radio>
            <el-form-item>
              <template slot-scope="scope">即“通用券”，建议当以上四种无法满足需求时采用{{card_type}}</template>
            </el-form-item>

            <el-form-item>
              <el-button
                type="success"
                size="medium"
                class="confirm"
                @click="submit('templateForm')"
              >确定</el-button>
            </el-form-item>
          </el-form>
        </el-dialog>
        <el-table
          :data="tableData"
          style="width: 100%"
        >
          <el-table-column
            :show-overflow-tooltip="true"
            prop="type"
            label="卡券类型"
            min-width="100"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="all_type"
            label="全部卡券"
            min-width="100"
          >
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="time"
            label="卡券有效期"
            min-width="100"
          >
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="status"
            label="全部状态"
            min-width="100"
          >
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="count"
            label="库存"
            min-width="100"
          >
          </el-table-column>
          <el-table-column
            label="操作"
            min-width="150"
          >
            <template slot-scope="scope">
              <el-button
                size="small"
                type="warning"
                @click="linkToView(scope.row)"
              >详情</el-button>
              <el-button
                size="small"
                @click="linkToEdit(scope.row)"
              >修改</el-button>
              <el-button
                size="small"
                @click="linkToDelete(scope.row)"
              >删除</el-button>
            </template>
          </el-table-column>
        </el-table>
        <div class="pagination-wrap">
          <el-pagination
            :total="pagination.total"
            :page-size="pagination.pageSize"
            :current-page="pagination.currentPage"
            layout="prev, pager, next, jumper, total"
            @current-change="changePage"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {
  getCouponList,
} from "service";

import {
  Button,
  Input,
  Table,
  Select,
  Option,
  TableColumn,
  Pagination,
  Form,
  Dialog,
  FormItem,
  MessageBox,
  RadioGroup,
  Radio
} from "element-ui";

export default {
  components: {
    "el-table": Table,
    "el-table-column": TableColumn,
    "el-button": Button,
    "el-input": Input,
    "el-pagination": Pagination,
    "el-form": Form,
    "el-select": Select,
    "el-option": Option,
    "el-form-item": FormItem,
    "el-dialog": Dialog,
    'el-radio-group': RadioGroup,
    'el-radio': Radio,

  },
  data() {
    return {
      card_type: 'DISCOUNT',
      GROUPON: { card_type: 'GROUPON', title: '团购券' },
      CASH: { card_type: 'CASH', title: '代金券' },
      DISCOUNT: { card_type: 'DISCOUNT', title: '折扣券' },
      GIFT: { card_type: 'GIFT', title: '兑换券' },
      GENERAL_COUPON: { card_type: 'GENERAL_COUPON', title: '优惠券' }
      ,
      loading: true,
      title: '',
      radio: "1",
      companyList: [],
      templateForm: {
        company_id: null
      },
      templateVisible: false,
      filters: {
        name: "",
        company_id: ""
      },
      templateForm: {
        company_id: '',
        name: '',
        id: ''
      },
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      tableData: [
        { type: "折扣券", all_type: "1元换购", time: "2018-12-12", status: "已使用", count: "0", }
      ],
      setting: {
        loading: false,
        loadingText: '拼命加载中'
      },
    };
  },
  created() {
  },
  methods: {
    addCoupon() {
      console.log("新增")
      this.templateForm.name = ''
      this.templateForm.company_id = ''
      this.templateVisible = true
      this.title = '创建优惠券'
    },
    submit() {
      console.log("提交券类型")
      this.templateVisible = false
      this.$router.push({
        path: "/project/wx_cardpackage/add/",
        query: {
          card_type: this.card_type
        }
      });
    },
    linkToView() {
      console.log("查看")
      //this.setting.loading = true;
    },
    linkToEdit() {
      console.log("修改")
      // this.$router.push({
      //   path: "/project/rules/edit/" + currentCoupon.id
      // });
    },
    linkToDelete() {
      console.log("删除")
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage;
      //this.getCouponList();
    },
    dialogClose() {
      this.templateVisible = false
    },
  }
};
</script>

<style lang="less" scoped>
.root {
  font-size: 14px;
  color: #5e6d82;

  .item-list-wrap {
    background: #fff;
    padding: 30px;

    .el-form-item {
      margin: 0;
      padding: 0;
    }
    .el-dialog {
      display: flex;
      justify-content: flex-start;
      align-items: center;
      flex-direction: column;
    }
    .el-radio {
      margin-left: 80px;
    }
    .confirm {
      width: 120px;
      height: 50px;
      margin: 30px 60px;
    }
    .item-content-wrap {
      .total-wrap {
        margin-top: 5px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        font-size: 16px;
        align-items: center;
        margin-bottom: 10px;
        .label {
          font-size: 14px;
          margin: 5px 0;
        }
      }
      .pagination-wrap {
        margin: 10px auto;
        text-align: right;
      }
    }
  }
}
</style>
