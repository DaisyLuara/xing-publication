<template>
  <div 
    class="report-root">
    <second-sidebar 
      module="report"/>
    <div class="topbar">
      {{ this.$route.meta.title }}
    </div>
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'ReportView',
  data() {
    return {
    }
  },
  methods: {
  }
}
</script>

<style lang="less" scoped>
  .report-root {
    height: 100%;
    background-color: #EFF2F7;
    .topbar {
      position: absolute;
      top: 130px;
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
