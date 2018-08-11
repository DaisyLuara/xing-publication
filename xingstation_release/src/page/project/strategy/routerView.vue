<template>
  <div class="strategy-root">
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'StrategyView'
}
</script>

<style lang="less" scoped>
.strategy-root {
  height: 100%;
  margin: 10px;
  background-color: #eff2f7;
}
</style>
