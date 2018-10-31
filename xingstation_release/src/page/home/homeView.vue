<template>
  <div class="home-root">
    <div class="topbar">
      {{ this.$route.meta.title }}
    </div>
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'HomeView'
}
</script>

<style lang="less" scoped>
.home-root {
  height: 100%;
  margin-left: -110px;
  background-color: #eff2f7;
  .topbar {
    position: absolute;
    top: 130px;
    left: 90px;
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
