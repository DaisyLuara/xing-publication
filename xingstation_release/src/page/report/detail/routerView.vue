<template>
  <div 
    class="project-auth-root">
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'projectAuthView'
}
</script>

<style lang="less" scoped>
.project-auth-root {
  height: 100%;
  background-color: #eff2f7;
}
</style>
