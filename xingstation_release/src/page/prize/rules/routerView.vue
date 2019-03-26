<template>
  <div class="rules-root">
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'RulesView'
}
</script>

<style lang="less" scoped>
.rules-root {
  height: 100%;
  background-color: #eff2f7;
}
</style>