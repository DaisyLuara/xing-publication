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
              <template slot-scope="scope">即“通用券”，建议当以上四种无法满足需求时采用</template>
            </el-form-item>
            <el-form-item>
              <el-button
                type="success"
                size="medium"
                class="confirm"
                @click="submit()"
              >确定</el-button>
            </el-form-item>
          </el-form>
        </el-dialog>
        <el-table
          :data="dataList"
          style="width: 100%"
        >
          <el-table-column
            :show-overflow-tooltip="true"
            prop="typeName"
            label="卡券类型"
            min-width="100"
          />
          <el-table-column
            :show-overflow-tooltip="true"
            prop="title"
            label="全部卡券"
            min-width="100"
          >
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            prop="dateDetail"
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
            prop="quantity"
            label="库存"
            min-width="100"
          >
          </el-table-column>
          <el-table-column
            :show-overflow-tooltip="true"
            label="库存修改"
            min-width="100"
          >
            <template slot-scope="scope">
              <el-popover
                placement="bottom"
                width="250"
                v-model="scope.row.visible"
              >
                <el-radio
                  v-model="modifyChange"
                  :label="0"
                >增加</el-radio>
                <el-radio
                  v-model="modifyChange"
                  :label="1"
                >减少</el-radio>

                <div style="margin:10px 0"><input
                    type="text"
                    v-model="modifyQuantity"
                    style="width: 200px;
                           font-size: 14px;
                            border: 1px solid #ebeef5;
                            border-radius:5px;
                            height:30px;
                            padding:0 10px;
                            "
                  />
                  <span>份</span>
                </div>
                <div
                  class="errMessage"
                  style="font-size:12px;margin:10px 0;color:#e15f63;"
                  v-show="submitCheck.quantityCheck1"
                >库存不能小于1</div>
                <div
                  class="errMessage"
                  style="font-size:12px;margin:10px 0;color:#e15f63;"
                  v-show="submitCheck.quantityCheck2"
                >减少库存量不能大于已有库存量</div>
                <div
                  class="message"
                  style="font-size:12px;margin:10px 0"
                >每个用户领券上限，如不填，则默认为1</div>
                <div style="text-align: left; margin: 0">
                  <el-button
                    type="success"
                    size="mini"
                    @click="confirmModify(scope.row)"
                  >确定</el-button>
                  <el-button
                    size="mini"
                    @click="cancleModify(scope.row)"
                  >取消</el-button>
                </div>
                <el-button slot="reference"><i class="el-icon-edit"></i></el-button>
              </el-popover>
            </template>
          </el-table-column>
          <el-table-column
            label="操作"
            min-width="150"
          >
            <template slot-scope="scope">
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
        <!-- <div class="pagination-wrap">
          <el-pagination
            :total="pagination.total"
            :page-size="pagination.pageSize"
            :current-page="pagination.currentPage"
            layout="prev, pager, next, jumper, total"
            @current-change="changePage"
          />
        </div> -->
      </div>
    </div>
  </div>
</template>

<script>
import {
  getCardList,
  getSingleCard,
  deleteSingleCard,
  modifyInventory
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
  Radio,
  Popover
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
    'el-popover': Popover
  },
  data() {
    return {
      card_type: 'DISCOUNT',
      GROUPON: { card_type: 'GROUPON', title: '团购券' },
      CASH: { card_type: 'CASH', title: '代金券' },
      DISCOUNT: { card_type: 'DISCOUNT', title: '折扣券' },
      GIFT: { card_type: 'GIFT', title: '兑换券' },
      GENERAL_COUPON: { card_type: 'GENERAL_COUPON', title: '优惠券' },
      card_id: '',
      card_id_list: [],
      loading: true,
      title: '',
      submitCheck: {
        quantityCheck1: false,
        quantityCheck2: false
      },
      templateVisible: false,
      dataList: [
      ],
      modifyChange: 0,
      modifyQuantity: null,
      templateForm: {
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
      obj: {
        GIFT: { prop: 'gift', typeName: '兑换券' },
        DISCOUNT: { prop: 'discount', typeName: '折扣劵' },
        CASH: { prop: 'cash', typeName: '代金券' },
        GENERAL_COUPON: { prop: 'general_coupon', typeName: '优惠劵' },
        GROUPON: { prop: 'groupon', typeName: '团购劵' }      }
    };
  },
  created() {
  },
  mounted() {
    //查询优惠劵列表
    this.getCardList()
    sessionStorage.setItem('isRefresh', 0)
  },
  methods: {
    //卡券列表查询
    getCardList() {
      this.dataList = [];
      let params = {
        authorizer_id: 6
      }
      getCardList(this, params)
        .then(res => {
          console.log("卡劵列表card_id========")
          console.log(res)
          this.card_id_list = res.card_id_list
          //根据卡劵id循环查询卡劵信息
          this.listHandle()
        })
        .catch(err => {
          console.log(err);
        });
    },
    listHandle() {
      for (let i = 0; i < this.card_id_list.length; i++) {
        this.getSingleCard(this.card_id_list[i])
      }
    },
    //卡券详情查询
    getSingleCard(card_id) {
      let params = {
        authorizer_id: 6,
        card_id: card_id
      }
      console.log('卡劵查询详情参数==============>')
      console.log(params)
      getSingleCard(this, params)
        .then(res => {
          let card = res.card
          let object = this.obj[card.card_type]
          let data = { card_id: card[object.prop].base_info.id, card_type: card.card_type, typeName: object.typeName, title: card[object.prop].base_info.title }
          //库存
          data.quantity = card[object.prop].base_info.sku.quantity
          //库存修改弹框出现的状态
          data.visible = false
          //代金券标题处理
          if (card.card_type === 'CASH') {
            data.title = card[object.prop].reduce_cost + '元代金券'
          } else {
            data.title = card[object.prop].base_info.title
          }
          //审核中
          if (card[object.prop].base_info.status === 'CARD_STATUS_NOT_VERIFY') {
            data.status = '审核中'
          }
          //待投放
          else if (card[object.prop].base_info.status === 'CARD_STATUS_VERIFY_OK') {
            data.status = '待投放'
          }
          //已投放
          else if (card[object.prop].base_info.status === 'CARD_STATUS_DISPATCH') {
            data.status = '已投放'
          }
          else {
            data.status = ''
          }
          if (card[object.prop].base_info.date_info.type === 'DATE_TYPE_FIX_TIME_RANGE') {
            data.dateDetail = this.formatDateTime(card[object.prop].base_info.date_info.begin_timestamp) +
              '至' + this.formatDateTime(card[object.prop].base_info.date_info.end_timestamp)
          } else {
            data.dateDetail = '领取后' + (card[object.prop].base_info.date_info.fixed_begin_term === 0 ? '当' :
              card[object.prop].base_info.date_info.fixed_begin_term) + '天生效' + card[object.prop].base_info.date_info.fixed_term + '天有效'
          }
          if (card[object.prop].base_info.status !== 'CARD_STATUS_DELETE') {
            this.dataList.push(data)
            this.pagination.total++
          }
        })
        .catch(err => {
          console.log(err);
        });
    },
    //删除卡券
    deleteSingleCard(args) {
      let params = {
        authorizer_id: 6,
        card_id: args.card_id
      }
      deleteSingleCard(this, params)
        .then(res => {
          console.log('删除成功==============>')
          console.log(res)
          this.getCardList()
        })
        .catch(err => {
          console.log(err);
        });
    },
    //修改库存
    modifyInventory(args) {
      modifyInventory(this, args)
        .then(res => {
          console.log(res)
          //库存修改成功以后刷新页面
          if (res.errcode === 0) {
            this.getCardList()
            console.log("修改成功")
          }
        })
        .catch(err => {
          console.log(err);
        });
    },
    //确认修改库存
    confirmModify(args) {
      if (!this.validate(args)) {
        return
      }
      let updateParams = {
        authorizer_id: 6,
        card_id: args.card_id,
        increase_stock_value: 0,
        reduce_stock_value: 0
      }
      //增加
      if (this.modifyChange === 0) {
        updateParams.increase_stock_value = this.modifyQuantity
      } else {
        updateParams.reduce_stock_value = this.modifyQuantity
      }
      this.modifyInventory(updateParams)
      args.visible = false
    },
    //取消修改库存
    cancleModify(args) {
      args.visible = false
    },
    //时间戳转化为日期
    formatDateTime(timeStamp) {
      var date = new Date()
      date.setTime(timeStamp * 1000)
      var y = date.getFullYear()
      var m = date.getMonth() + 1
      m = m < 10 ? ('0' + m) : m
      var d = date.getDate()
      d = d < 10 ? ('0' + d) : d
      var h = date.getHours()
      h = h < 10 ? ('0' + h) : h
      var minute = date.getMinutes()
      var second = date.getSeconds()
      minute = minute < 10 ? ('0' + minute) : minute
      second = second < 10 ? ('0' + second) : second
      return y + '-' + m + '-' + d
    },

    addCoupon() {
      console.log("新增")
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
    linkToEdit(args) {
      this.$router.push({
        path: '/project/wx_cardpackage/add/',
        query: {
          card_id: args.card_id,
          card_type: args.card_type
        }
      })
    },
    linkToDelete(args) {
      console.log("删除")
      this.deleteSingleCard(args)
    },
    // changePage(currentPage) {
    //   this.pagination.currentPage = currentPage;
    // },
    dialogClose() {
      this.templateVisible = false
    },
    //校验
    validate(data) {
      this.submitCheck.quantityCheck1 = false
      this.submitCheck.quantityCheck2 = false
      let flag = true
      let reg = /^[0-9]*$/
      //库存校验
      if (this.modifyQuantity === '' || this.modifyQuantity === null || !reg.test(this.modifyQuantity) || this.modifyQuantity <= 0) {
        this.submitCheck.quantityCheck1 = true
        flag = false
      }
      if (this.modifyChange !== 0) {
        if (data.quantity < this.modifyQuantity) {
          this.submitCheck.quantityCheck2 = true
          flag = false
        }
      }
      return flag
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
    .el-popover {
      .coupon-form-input {
        width: 200px;
        display: inline-block;
        .input {
          width: 200px;
          font-size: 14px;
          border: 1px solid #8d8d8d;
        }
      }
    }
    .errMessage {
      color: #e15f63;
      font-size: 12px;
    }
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
    .el-input {
      width: 100px;
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
