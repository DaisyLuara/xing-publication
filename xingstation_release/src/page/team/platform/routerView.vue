<template>
  <div class="platform-root">
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'PlatformView'
}
</script>

<style lang="less" scoped>
.platform-root {
  height: 100%;
  background-color: #eff2f7;
}
</style>
