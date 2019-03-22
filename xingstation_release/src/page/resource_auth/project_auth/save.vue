<template>
</template>

<script>


  export default {};
</script>

<style lang="less" scoped>
  .item-wrap-template {
    .point-form {
      width: 900px;
    }
    .pane {
      border-radius: 5px;
      background-color: white;
      padding: 20px 40px 80px;

      .el-select,
      .item-input,
      .el-date-editor.el-input {
        width: 380px;
      }
      .item-list {
        .program-title {
          color: #555;
          font-size: 14px;
        }
      }
      .pane-title {
        padding-bottom: 20px;
        font-size: 18px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
      }
    }
  }
</style>
