<template>
  <div class="ratio-root">
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'RatioView'
}
</script>

<style lang="less" scoped>
.ratio-root {
  height: 100%;
  background-color: #eff2f7;
}
</style>
