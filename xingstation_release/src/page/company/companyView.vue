<template>
  <div class="company-root">
    <second-sidebar module="company"/>
    <div class="topbar">
      {{ this.$route.meta.title }}
    </div>
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'CompanyView',
  data() {
    return {}
  }
}
</script>

<style lang="less" scoped>
.company-root {
  height: 100%;
  background-color: #eff2f7;
  .topbar {
    position: absolute;
    top: 0;
    left: 200px;
    right: 0;
    height: 75px;
    line-height: 75px;
    border-bottom: 1px solid #d3dce6;
    background-color: #fff;
    z-index: 98;
    font-size: 16px;
    color: #5e6d82;
    padding-left: 35px;
  }
}
</style>
