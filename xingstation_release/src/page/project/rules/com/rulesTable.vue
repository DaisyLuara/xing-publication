<template>
  <div>
    <el-table :data="tableData" style="width: 100%">
      <el-table-column type="expand">
        <template slot-scope="scope">
          <el-form label-position="left" inline class="demo-table-expand">
            <el-form-item label="ID">
              <span>{{ scope.row.id }}</span>
            </el-form-item>
            <el-form-item label="公司">
              <span>{{ scope.row.company.name }}</span>
            </el-form-item>
            <el-form-item label="创建人">
              <span>{{ scope.row.user.name }}</span>
            </el-form-item>
            <el-form-item label="优惠券名称">
              <span>{{ scope.row.name }}</span>
            </el-form-item>
            <el-form-item label="标题">
              <span>{{ scope.row.title }}</span>
            </el-form-item>
            <el-form-item label="动态库存">
              <span>{{ scope.row.dynamic_stock_status === 0 ? '关闭' : '开启'}}</span>
            </el-form-item>
            <el-form-item label="优惠券描述">
              <span>{{ scope.row.description }}</span>
            </el-form-item>
            <el-form-item label="h5图片">
              <a :href="scope.row.image_url" target="_blank" style="color: blue">查看</a>
            </el-form-item>
            <el-form-item label="大屏图片">
              <a :href="scope.row.bs_image_url" target="_blank" style="color: blue">查看</a>
            </el-form-item>
            <el-form-item label="金额">
              <span>{{ scope.row.amount }}</span>
            </el-form-item>
            <el-form-item label="积分">
              <span>{{ scope.row.credit }}</span>
            </el-form-item>
            <el-form-item label="库存总数">
              <span>{{ scope.row.count }}</span>
            </el-form-item>
            <el-form-item label="优先级">
              <span>{{ scope.row.sort_order }}</span>
            </el-form-item>
            <el-form-item label="剩余库存">
              <span>{{ scope.row.stock }}</span>
            </el-form-item>
            <el-form-item label="每人最大获取数">
              <span>{{ scope.row.people_max_get }}</span>
            </el-form-item>
            <el-form-item label="是否开启每人无限领取">
              <span>{{ scope.row.pmg_status === 1 ? '开启' : '关闭' }}</span>
            </el-form-item>
            <el-form-item label="每天最大获取数">
              <span>{{ scope.row.day_max_get }}</span>
            </el-form-item>
            <el-form-item label="是否开启每天无限领取">
              <span>{{ scope.row.dmg_status === 1 ? '固定' : '不固定' }}</span>
            </el-form-item>
            <el-form-item label="是否固定日期">
              <span>{{ scope.row.is_fixed_date === 1 ? '固定' : '不固定' }}</span>
            </el-form-item>
            <el-form-item v-if="scope.row.is_fixed_date === 0" label="延后生效天数">
              <span>{{ scope.row.delay_effective_day }}</span>
            </el-form-item>
            <el-form-item v-if="scope.row.is_fixed_date === 0" label="有效天数">
              <span>{{ scope.row.effective_day }}</span>
            </el-form-item>
            <el-form-item v-if="scope.row.is_fixed_date === 1" label="开始日期">
              <span>{{ scope.row.start_date }}</span>
            </el-form-item>
            <el-form-item v-if="scope.row.is_fixed_date === 1" label="结束日期">
              <span>{{ scope.row.end_date }}</span>
            </el-form-item>
            <el-form-item label="状态">
              <span>{{ scope.row.is_active === 1 ? '启用' :'停用' }}</span>
            </el-form-item>
          </el-form>
        </template>
      </el-table-column>
      <el-table-column :show-overflow-tooltip="true" prop="id" label="ID" min-width="100"/>
      <el-table-column :show-overflow-tooltip="true" prop="company_id" label="公司" min-width="100">
        <template slot-scope="scope">{{ scope.row.company.name }}</template>
      </el-table-column>
      <el-table-column :show-overflow-tooltip="true" prop="user_name" label="创建人" min-width="100">
        <template slot-scope="scope">{{ scope.row.user.name }}</template>
      </el-table-column>
      <el-table-column :show-overflow-tooltip="true" prop="name" label="名称" min-width="100"/>
      <el-table-column :show-overflow-tooltip="true" prop="sort_order" label="优先级" min-width="100"/>
      <el-table-column :show-overflow-tooltip="true" prop="amount" label="金额" min-width="100"/>
      <el-table-column :show-overflow-tooltip="true" prop="count" label="库存总数" min-width="100"/>
      <el-table-column :show-overflow-tooltip="true" prop="stock" label="剩余库存" min-width="100"/>
      <el-table-column label="操作" min-width="150">
        <template slot-scope="scope">
          <el-button size="small" type="warning" @click="linkToEdit(scope.row)">编辑</el-button>
          <el-button size="small" @click="copyRules(scope.row)">复制</el-button>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>
<script>
import { saveCoupon } from "service";
import {
  Button,
  Table,
  TableColumn,
  Pagination,
  Form,
  FormItem,
  MessageBox
} from "element-ui";
export default {
  props: {
    tableData: {
      type: Array,
      required: true,
      default: []
    }
  },
  components: {
    "el-table": Table,
    "el-table-column": TableColumn,
    "el-button": Button,
    "el-pagination": Pagination,
    "el-form": Form,
    "el-form-item": FormItem
  },
  data() {
    return {};
  },
  methods: {
    linkToEdit(currentCoupon) {
      this.$router.push({
        path: "/project/rules/edit/" + currentCoupon.id
      });
    },
    addCoupon() {
      this.$router.push({
        path: "/project/rules/add"
      });
    },
    copyRules(data) {
      this.setting.loading = true;
      let company_id = data.company.id;
      let args = {
        name: data.name,
        description: data.description,
        image_url: data.image_url,
        amount: data.amount,
        count: data.count,
        stock: data.stock,
        people_max_get: data.people_max_get,
        pmg_status: data.pmg_status,
        day_max_get: data.day_max_get,
        dmg_status: data.dmg_status,
        is_fixed_date: data.is_fixed_date,
        delay_effective_day: data.delay_effective_day,
        effective_day: data.effective_day,
        start_date: data.start_date,
        end_date: data.end_date,
        is_active: data.is_active,
        redirect_url: data.redirect_url,
        type: data.type,
        sort_order: data.sort_order,
        title: data.title,
        dynamic_stock_status: data.dynamic_stock_status,
        write_off_status: data.write_off_status,
        credit: data.credit,
        bs_image_url: data.bs_image_url
      };
      if (!args.image_url) {
        delete args.image_url;
      }
      if (args.title === "") {
        delete args.title;
      }
      if (args.redirect_url === "") {
        delete args.redirect_url;
      }
      if (!args.description) {
        delete args.description;
      }
      if (!args.end_date) {
        delete args.end_date;
      }
      if (!args.start_date) {
        delete args.start_date;
      }
      saveCoupon(this, args, "", company_id)
        .then(result => {
          this.loading = false;
          this.$message({
            message: "复制成功",
            type: "success"
          });
          this.getCouponList();
        })
        .catch(error => {
          this.loading = false;
          console.log(error);
        });
    }
  }
};
</script>
<style lang="less" scoped>
</style>

