<template>
  <div class="program-root">
    <keep-alive>
      <router-view v-if="$route.meta.keepAlive"/>
    </keep-alive>
    <router-view v-if="!$route.meta.keepAlive"/>
  </div>
</template>
<script>
export default {
  name: 'ProgramView'
}
</script>

<style lang="less" scoped>
</style>
