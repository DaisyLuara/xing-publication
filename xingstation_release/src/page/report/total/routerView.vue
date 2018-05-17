<template>
  <div class="total-root">
    <router-view></router-view>
  </div>
</template>
<script>
export default {
  name: 'totalView',
  data() {
    return {
    }
  },
  methods: {
  }
}
</script>

<style lang="less" scoped>
  .total-root {
    height: 100%;
    background-color: #EFF2F7;
  }
</style>
