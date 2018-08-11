<template>
  <div class="picture-root">
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'PictureView'
}
</script>

<style lang="less" scoped>
.picture-root {
  height: 100%;
  margin: 10px;
  background-color: #eff2f7;
}
</style>
