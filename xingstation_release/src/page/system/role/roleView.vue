<template>
<div class="role-wrap">
<div class="topbar">
  <el-breadcrumb separator="/">
    <el-breadcrumb-item>角色管理</el-breadcrumb-item>
  </el-breadcrumb>
</div>
<router-view></router-view>
</div>
</template>
<script>

export default {
  name: 'roleHome',
  data () {
    return {
    }
  },
  methods: {
  }
}
</script>

<style lang="less">
.role-wrap{
  padding: 33px;
}
</style>
