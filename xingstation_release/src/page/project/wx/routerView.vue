<template>
  <div class="wx-root">
    <keep-alive>
      <router-view v-if="$route.meta.keepAlive">
        <!-- 这里是会被缓存的视图组件，比如 Home！ -->
      </router-view>
    </keep-alive>

    <router-view v-if="!$route.meta.keepAlive">
      <!-- 这里是不被缓存的视图组件，比如 Edit！ -->
    </router-view>
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
