<template>
  <div 
    class="account-root">
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'AccountView',
  data() {
    return {
    }
  },
  methods: {
  }
}
</script>

<style lang="less" scoped>
  .account-root {
    height: 100%;
    background-color: #EFF2F7;
  }
</style>
