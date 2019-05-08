<template>
  <div class="tenant-root">
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'TenantView'
}
</script>

<style lang="less" scoped>
.tenant-root {
  height: 100%;
  background-color: #eff2f7;
}
</style>
