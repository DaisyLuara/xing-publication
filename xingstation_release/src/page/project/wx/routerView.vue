<template>
  <div class="wx-root">
    <router-view />
  </div>
</template>
<script>
export default {
  name: 'wxView'
}
</script>

<style lang="less" scoped>
.wx-root {
  height: 100%;
  background-color: #eff2f7;
}
</style>
