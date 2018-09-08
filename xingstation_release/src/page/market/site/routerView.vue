<template>
  <div class="site-root">
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'SiteView'
}
</script>

<style lang="less" scoped>
.site-root {
  height: 100%;
  margin: 10px;
  background-color: #eff2f7;
}
</style>