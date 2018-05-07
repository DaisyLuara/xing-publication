<template>
  <div class="customer-root">
    <second-sidebar module="customer"></second-sidebar>
    <div class="topbar">
      {{this.$route.meta.title}}
    </div>
    <router-view></router-view>
  </div>
</template>
<script>
export default {
  name: 'customerView',
  data() {
    return {
    }
  },
  methods: {
  }
}
</script>

<style lang="less" scoped>
  .customer-root {
    height: 100%;
    background-color: #EFF2F7;
    .topbar {
      position: absolute;
      top: 0;
      left: 200px;
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
