<template>
  <div class="operation-root">
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'OperationView'
}
</script>

<style lang="less" scoped>
.operation-root {
  height: 100%;
  background-color: #eff2f7;
}
</style>
