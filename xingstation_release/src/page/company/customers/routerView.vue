<template>
  <div class="custom-root">
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'CustomView',
  data() {
    return {
    }
  },
  methods: {
  }
}
</script>

<style lang="less" scoped>
  .custom-root {
    height: 100%;
    margin: 10px;
    background-color: #EFF2F7;
  }
</style>
