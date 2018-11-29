<template>
  <div 
    class="center-root">
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'CenterView',
  data() {
    return {
    }
  },
  methods: {
  }
}
</script>

<style lang="less" scoped>
  .center-root {
    height: 100%;
    background-color: #EFF2F7;
  }
</style>
