<template>
  <div class="main-root">
    <router-view></router-view>
  </div>
</template>
<script>
export default {
  name: 'mainView',
  data() {
    return {
    }
  },
  methods: {
  }
}
</script>

<style lang="less" scoped>
  .main-root {
    height: 100%;
    background-color: #EFF2F7;
  }
</style>
