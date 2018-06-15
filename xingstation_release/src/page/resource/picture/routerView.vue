<template>
  <div class="picture-root">
    <router-view></router-view>
  </div>
</template>
<script>
export default {
  name: 'pictureView',
  data() {
    return {
    }
  },
  methods: {
  }
}
</script>

<style lang="less" scoped>
  .picture-root {
    height: 100%;
    margin: 10px;
    background-color: #EFF2F7;
  }
</style>
