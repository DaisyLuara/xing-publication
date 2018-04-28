<template>
  <account-form :type="'findPassword'"></account-form>
</template>
<script>
import accountForm from '../components/common/accountForm'
import auth from 'service/auth'
import { Button, Input, Form, FormItem, Checkbox, CheckboxGroup, Col } from 'element-ui'
import axios from 'axios'
export default {
  name: 'find-password',
  data() {
    return {
    }
  },
  created(){
  },
  methods: {
  },
  components: {
    accountForm
  }
}
</script>
<style scoped lang="less">
.account-form-wrap{
  .account-form-container{
    .account-form-body {
      .account-form-submit{
        margin-top: 32px;
      }
    }
  }
}
</style>
