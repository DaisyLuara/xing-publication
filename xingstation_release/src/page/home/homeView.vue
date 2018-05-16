<template>
  <div class="home-root">
    <!-- <second-sidebar module="home"></second-sidebar> -->
    <div class="topbar">
      {{this.$route.meta.title}}
    </div>
    <router-view></router-view>
  </div>
</template>
<script>
export default {
  name: 'homeView',
  data() {
    return {
    }
  },
  methods: {
  }
}
</script>

<style lang="less" scoped>
  .home-root {
    height: 100%;
    margin-left: -110px;
    background-color: #EFF2F7;
    .topbar {
      position: absolute;
      top: 0;
      left: 90px;
      right: 0;
      height: 75px;
      line-height: 75px;
      border-bottom: 1px solid #D3DCE6;
      background-color: #fff;
      z-index: 98;
      font-size: 16px;
      color: #5E6D82;
      padding-left: 35px;
    }
  }
</style>
