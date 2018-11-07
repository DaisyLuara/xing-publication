<template>
  <div 
    class="feedback-root">
    <router-view/>
  </div>
</template>
<script>
export default {
  name: 'FeedbackView',
  data() {
    return {}
  }
}
</script>

<style lang="less" scoped>
.feedback-root {
  height: 100%;
  background-color: #eff2f7;
}
</style>
