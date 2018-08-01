<template>
  <div 
    class="module-system">
    <second-sidebar 
      module="system"/>
    <div 
      class="content">
      <router-view/>
    </div>
  </div>
</template>
<script>
export default {
  name: 'SystemHome'
}
</script>

<style lang="less">
</style>
