<template>
  <div class="operate-root">
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'OperateView'
}
</script>

<style lang="less" scoped>
  .operate-root {
    height: 100%;
    margin: 10px;
    background-color: #EFF2F7;
  }
</style>
