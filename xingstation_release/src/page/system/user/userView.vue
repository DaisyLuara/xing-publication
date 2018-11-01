<template>
  <div 
    class="user-wrap">
    <div 
      class="topbar">
      <el-breadcrumb 
        separator="/">
        <el-breadcrumb-item>用户管理</el-breadcrumb-item>
      </el-breadcrumb>
      <headModule/>
    </div>
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'UserHome'
}
</script>

<style lang="less">
.user-wrap {
  padding: 33px;
}
</style>
