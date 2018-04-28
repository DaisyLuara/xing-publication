<template>
<div class="user-wrap">
<div class="topbar">
  <el-breadcrumb separator="/">
    <el-breadcrumb-item>用户管理</el-breadcrumb-item>
  </el-breadcrumb>
</div>
<router-view></router-view>
</div>
</template>
<script>

export default {
  name: 'userHome',
  data () {
    return {
    }
  },
  methods: {
  }
}
</script>

<style lang="less">
.user-wrap{
  padding: 33px;
}
</style>
