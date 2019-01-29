<template>
  <div class="list-root">
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'ListView'
}
</script>

<style lang="less" scoped>
.list-root {
  height: 100%;
  background-color: #eff2f7;
}
</style>