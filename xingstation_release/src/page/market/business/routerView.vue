<template>
  <div class="business-root">
    <router-view/>
  </div>
</template>
<script>
export default {
  name: "BusinessView"
};
</script>

<style lang="less" scoped>
.business-root {
  height: 100%;
  background-color: #eff2f7;
}
</style>