<template>
  <div
    v-loading="setting.loading"
    :element-loading-text="setting.loadingText"
    class="user-list-wrap"
  >
    <div class="user-list-content">
      <el-tabs v-model="activeName" type="card" @tab-click="handleClick">
        <el-tab-pane label="父权限" name="first">
          <div class="search-wrap">
            <el-form :model="filters" :inline="true">
              <el-form-item label>
                <el-input v-model="filters.name" style="width:200px" placeholder="请输入名称" clearable/>
              </el-form-item>
              <el-button type="primary" size="small" @click="search">搜索</el-button>
              <el-button type="default" size="small" @click="resetSearch">重置</el-button>
            </el-form>
          </div>
          <div class="actions-wrap">
            <span class="label">数量: {{ pagination.total }}</span>
            <el-button size="small" type="success" @click="addFirstPerms">新增权限</el-button>
          </div>
          <el-table ref="userTable" :data="firstTableData" style="width: 100%">
            <el-table-column prop="id" label="ID" min-width="100"/>
            <el-table-column prop="name" label="名称" min-width="150"/>
            <el-table-column prop="display_name" label="英文名称" min-width="150"/>
            <el-table-column label="操作" min-width="180">
              <template slot-scope="scope">
                <el-button size="small" @click="showSencodMenu(scope.row)">查看子权限</el-button>
                <el-button size="small" type="warning" @click="modifyFirstPerms(scope.row)">修改</el-button>
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
        </el-tab-pane>
        <el-tab-pane label="子权限" name="second" :disabled="secondTabDisable">
          <div class="actions-wrap">
            <span class="label">数量: {{ secondTableData.length }}</span>
            <!-- 模板增加 -->
            <div>
              <el-button size="small" type="success" @click="addSecondPerms">新增二级权限</el-button>
            </div>
          </div>
          <el-collapse v-model="activeNames" accordion>
            <el-collapse-item v-for="(item, index) in secondTableData" :name="index" :key="item.id">
              <template slot="title">
                {{ item.display_name }}
                <el-button
                  type="primary"
                  icon="el-icon-edit"
                  circle
                  size="mini"
                  @click="modifySecondPerms(item)"
                />
              </template>
              <div class="actions-wrap">
                <span class="label">数目: {{ item.children.length }}</span>
                <div>
                  <el-button size="small" @click="addThirdPerms(index)">增加</el-button>
                </div>
              </div>
              <el-table :data="item.children" style="width: 100%">
                <el-table-column prop label="中文名称" min-width="150">
                  <template slot-scope="scope">
                    <el-input v-model="scope.row.display_name" placeholder="中文名称" :maxlength="50"/>
                  </template>
                </el-table-column>
                <el-table-column prop label="英文名称" min-width="150">
                  <template slot-scope="scope">
                    <el-input v-model="scope.row.name" placeholder="英文名称" :maxlength="50"/>
                  </template>
                </el-table-column>
                <el-table-column label="操作" min-width="100">
                  <template slot-scope="scope">
                    <el-button
                      v-if="scope.row.created_at"
                      size="mini"
                      type="warning"
                      @click="editPerms(scope.row)"
                    >编辑</el-button>
                    <el-button
                      v-if="!scope.row.created_at"
                      size="mini"
                      type="danger"
                      icon="el-icon-delete"
                      @click="deleteAddPerms(index, scope.$index, scope.row)"
                    />
                    <el-button
                      v-if="!scope.row.created_at"
                      size="mini"
                      style="background-color: #8bc34a;border-color: #8bc34a; color: #fff;"
                      @click="savePerms(scope.row)"
                    >保存</el-button>
                  </template>
                </el-table-column>
              </el-table>
            </el-collapse-item>
          </el-collapse>
        </el-tab-pane>
      </el-tabs>
    </div>
    <el-dialog :title="title" :visible.sync="permsVisible" @close="dialogClose">
      <el-form v-loading="loading" ref="permsForm" :model="permsForm" label-width="80px">
        <el-form-item
          :rules="[{ type: 'string', required: true, message: '请输入中文名称', trigger: 'submit' }]"
          label="中文名称"
          prop="name"
        >
          <el-input v-model="permsForm.display_name" placeholder="请输入中文名称" class="item-input"/>
        </el-form-item>
        <el-form-item
          :rules="[{ type: 'string', required: true, message: '请输入英文名称', trigger: 'submit' }]"
          label="英文名称"
          prop="name"
        >
          <el-input v-model="permsForm.name" placeholder="请输入英文名称" class="item-input"/>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" size="small" @click="submit('permsForm')">完成</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>
<script>
import { getPermissionList, getPermissionInfo, savePermission } from "service";
import {
  Collapse,
  CollapseItem,
  Button,
  Input,
  Table,
  TableColumn,
  Pagination,
  Form,
  FormItem,
  MessageBox,
  TabPane,
  Tabs,
  Dialog
} from "element-ui";

export default {
  components: {
    "el-collapse": Collapse,
    "el-collapse-item": CollapseItem,
    "el-table": Table,
    "el-table-column": TableColumn,
    "el-button": Button,
    "el-input": Input,
    "el-pagination": Pagination,
    "el-form": Form,
    "el-form-item": FormItem,
    "el-tabs": Tabs,
    "el-tab-pane": TabPane,
    "el-dialog": Dialog
  },
  data() {
    return {
      loading: true,
      permsVisible: false,
      title: "",
      permsForm: {
        name: "",
        display_name: ""
      },
      secondTabDisable: true,
      activeName: "first",
      activeNames: 0,
      parent_id: 0,
      dataTest: [
        {
          id: 54,
          name: "company",
          display_name: "公司",
          children: [],
          created_at: "2018-12-24",
          updated_at: "2018-12-24"
        },
        {
          id: 55,
          name: "system",
          display_name: "设置",
          children: {
            "64": {
              id: 64,
              name: "system.user",
              guard_name: "web",
              display_name: "用户管理",
              parent_id: 55,
              lft: 4,
              rgt: 5,
              depth: 1,
              created_at: "2018-12-24 17:59:03",
              updated_at: "2018-12-24 17:59:03",
              children: []
            },
            "66": {
              id: 66,
              name: "system.role",
              guard_name: "web",
              display_name: "角色管理",
              parent_id: 55,
              lft: 6,
              rgt: 13,
              depth: 1,
              created_at: "2018-12-25 09:38:26",
              updated_at: "2018-12-27 15:37:00",
              children: [
                {
                  id: 67,
                  name: "system.role.edit",
                  guard_name: "web",
                  display_name: "编辑",
                  parent_id: 66,
                  lft: 7,
                  rgt: 8,
                  depth: 2,
                  created_at: "2018-12-25 11:55:42",
                  updated_at: "2018-12-25 11:55:42",
                  children: []
                },
                {
                  id: 68,
                  name: "system.role.view",
                  guard_name: "web",
                  display_name: "查看",
                  parent_id: 66,
                  lft: 9,
                  rgt: 10,
                  depth: 2,
                  created_at: "2018-12-25 11:55:51",
                  updated_at: "2018-12-25 11:55:51",
                  children: []
                },
                {
                  id: 70,
                  name: "system.role.test",
                  guard_name: "web",
                  display_name: "测试",
                  parent_id: 66,
                  lft: 11,
                  rgt: 12,
                  depth: 2,
                  created_at: "2018-12-27 15:37:00",
                  updated_at: "2018-12-27 15:37:00",
                  children: []
                }
              ]
            }
          },
          created_at: "2018-12-24",
          updated_at: "2018-12-27"
        }
      ],
      firstTableData: [
        {
          id: 1,
          name: "节目",
          display_name: "project"
        }
      ],
      secondTableData: [],
      tap: null,
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      filters: {
        name: ""
      },
      permsId: null,
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      }
    };
  },
  created() {
    this.firstTableData = this.dataTest;
    // this.getPermissionList();
  },
  methods: {
    modifySecondPerms(item) {
      this.permsId = item.id;
      this.tap = "second";
      this.getPermissionInfo(this.permsId);
      this.loading = false;
      this.permsVisible = true;
    },
    modifyFirstPerms(data) {
      this.permsId = data.id;
      this.tap = "first";
      this.getPermissionInfo(this.permsId);
      this.loading = false;
      this.permsVisible = true;
    },
    getPermissionInfo(id) {
      getPermissionInfo(this, id)
        .then(res => {
          this.permsForm.name = res.name;
          this.permsForm.display_name = res.display_name;
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    submit(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          let args = {
            name: this.permsForm.name,
            display_name: this.permsForm.display_name,
            parent_id: this.parent_id
          };
          if (this.tap === "first") {
            delete args.parent_id;
          }
          if (this.permsId) {
            savePermission(this, args, this.permsId)
              .then(res => {
                this.$message({
                  type: "success",
                  message: "修改成功"
                });
                this.getPermissionList();
                this.permsVisible = false;
              })
              .catch(err => {
                this.permsVisible = false;
                this.$message({
                  type: "warning",
                  message: err.response.data.message
                });
              });
          } else {
            savePermission(this, args)
              .then(res => {
                this.permsVisible = false;
                this.$message({
                  type: "success",
                  message: "保存成功"
                });
                this.getPermissionList();
              })
              .catch(err => {
                this.permsVisible = false;
                this.$message({
                  type: "warning",
                  message: err.response.data.message
                });
              });
          }
        }
      });
    },
    addFirstPerms() {
      this.parent_id = 0;
      this.tap = "first";
      this.loading = false;
      this.permsForm.name = "";
      this.permsVisible = true;
    },
    addSecondPerms() {
      this.tap = "second";
      this.loading = false;
      this.permsForm.name = "";
      this.permsVisible = true;
    },
    dialogClose() {
      this.permsVisible = false;
    },
    showSencodMenu(data) {
      this.parent_id = data.id;
      this.secondTableData = [];
      let secondData = data.children;
      for (let sData in secondData) {
        this.secondTableData.push(secondData[sData]);
      }
      console.log(this.secondTableData);
      this.secondTabDisable = false;
      this.activeName = "second";
    },
    handleClick(val) {
      this.secondTabDisable = true;
    },
    addThirdPerms(index) {
      console.log(this.secondTableData[index]);
      let parent_id = this.secondTableData[index].id;
      let td = {
        id: "",
        name: "",
        parent_id: parent_id
      };
      this.secondTableData[index].children.push(td);
    },
    savePerms(data) {
      let args = {
        name: data.name,
        display_name: data.display_name,
        parent_id: parent_id
      };
      savePermission(this, args)
        .then(res => {
          this.$message({
            type: "success",
            message: "保存成功"
          });
          this.showSencodMenu(this.parent_id);
          this.tap = "second";
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    deleteAddPerms(pIndex, index, r) {
      this.secondTableData[pIndex].perms.data.splice(index, 1);
    },
    editPerms(data) {
      let args = {
        name: data.name,
        display_name: data.display_name,
        parent_id: parent_id
      };
      savePermission(this, args, data.id)
        .then(res => {
          this.$message({
            type: "success",
            message: "修改成功"
          });
          this.showSencodMenu(this.parent_id);
          this.tap = "second";
        })
        .catch(err => {
          this.$message({
            type: "warning",
            message: err.response.data.message
          });
        });
    },
    getPermissionList() {
      this.setting.loading = true;
      let args = {
        page: this.pagination.currentPage,
        display_name: this.filters.name
      };
      if (this.filters.name === "") {
        delete args.display_name;
      }
      return getPermissionList(this, args)
        .then(response => {
          this.setting.loading = false;
          this.firstTableData = response.data;
          this.pagination.total = response.meta.pagination.total;
        })
        .catch(error => {
          this.setting.loading = false;
        });
    },
    changePage(currentPage) {
      this.pagination.currentPage = currentPage;
      this.getPermissionList();
    },
    search() {
      this.pagination.currentPage = 1;
      this.getPermissionList();
    },
    resetSearch() {
      this.filters.name = "";
      this.pagination.currentPage = 1;
      this.getPermissionList();
    }
  }
};
</script>

<style lang="less" scoped>
.user-list-wrap {
  h1 {
    text-align: center;
  }
}
.user-list-content {
  .photo_img {
    width: 100%;
    padding: 5px;
  }
  .item-input {
    width: 300px;
  }
  .actions-wrap {
    margin-top: 5px;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    font-size: 16px;
    align-items: center;
    margin-bottom: 10px;
    .label {
      font-size: 14px;
    }
  }
  .pagination-wrap {
    margin: 10px auto;
    text-align: right;
  }
}
</style>
