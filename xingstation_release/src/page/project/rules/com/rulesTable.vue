<template>
  <div>
    <el-table :data="tableData" style="width: 100%">
      <el-table-column type="expand">
        <template slot-scope="scope">
          <el-form label-position="left" inline class="demo-table-expand">
            <el-form-item label="ID">
              <span>{{ scope.row.id }}</span>
            </el-form-item>
            <el-form-item label="优惠券类型">
              <span>{{ scope.row.scene_type === 1 ? '场地通用' : scope.row.scene_type === 2 ? '场地自营' : scope.row.scene_type === 3 ? '商户通用' : scope.row.scene_type === 4 ? '商户自营' : '无'}}</span>
            </el-form-item>
            <el-form-item label="优惠券名称">
              <span>{{ scope.row.name }}</span>
            </el-form-item>
            <el-form-item label="公司名称">
              <span>{{ scope.row.company.name }}</span>
            </el-form-item>
            <el-form-item label="创建人">
              <span>{{ scope.row.user.name }}</span>
            </el-form-item>
            <el-form-item label="优先级">
              <span>{{ scope.row.sort_order }}</span>
            </el-form-item>
            <el-form-item label="剩余库存">
              <span>{{ scope.row.stock }}</span>
            </el-form-item>
            <el-form-item label="修改时间">
              <span>{{ scope.row.updated_at }}</span>
            </el-form-item>
          </el-form>
        </template>
      </el-table-column>
      <el-table-column :show-overflow-tooltip="true" prop="id" label="ID" min-width="100"/>
      <el-table-column
        :show-overflow-tooltip="true"
        prop="scene_type"
        label="优惠券类型"
        min-width="100"
      >
        <template
          slot-scope="scope"
        >{{ scope.row.scene_type === 1 ? '场地通用' : scope.row.scene_type === 2 ? '场地自营' : scope.row.scene_type === 3 ? '商户通用' : scope.row.scene_type === 4 ? '商户自营' : '无'}}</template>
      </el-table-column>
      <el-table-column :show-overflow-tooltip="true" prop="name" label="优惠券名称" min-width="100"/>
      <el-table-column
        :show-overflow-tooltip="true"
        prop="company_name"
        label="公司名称"
        min-width="100"
      >
        <template slot-scope="scope">{{ scope.row.company.name }}</template>
      </el-table-column>
      <el-table-column :show-overflow-tooltip="true" prop="user_name" label="创建人" min-width="100">
        <template slot-scope="scope">{{ scope.row.user.name }}</template>
      </el-table-column>
      <el-table-column :show-overflow-tooltip="true" prop="stock" label="剩余库存" min-width="100"/>
      <el-table-column
        :show-overflow-tooltip="true"
        prop="updated_at"
        label="修改时间"
        min-width="100"
      />
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
    return {
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      }
    };
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
      let write_off_sid = [];
      if (data.writeOffStore) {
        data.writeOffStore.data.map(r => {
          let id = r.id;
          write_off_sid.push(id);
        });
      } else {
        write_off_sid = [];
      }
      let write_off_mid = null;
      if (data.writeOffMarket) {
        write_off_mid = data.writeOffMarket.id;
      }
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
        bs_image_url: data.bs_image_url,
        write_off_sid: write_off_sid,
        scene_type: data.scene_type,
        write_off_mid: write_off_mid
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
          this.$emit("getCouponList");
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
.demo-table-expand {
  font-size: 0;
}

.demo-table-expand label {
  width: 90px;
  color: #99a9bf;
}
.demo-table-expand .el-form-item {
  margin-right: 0;
  margin-bottom: 0;
  width: 50%;
}
</style>

