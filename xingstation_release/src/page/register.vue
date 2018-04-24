<template>
  <account-form :type="'register'"></account-form>
</template>
<script>
import accountForm from '../components/common/accountForm'
import auth from 'service/auth'
import { Button, Input, Form, FormItem, Checkbox, CheckboxGroup, Col } from 'element-ui'
import axios from 'axios'
export default {
  name: 'register',
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
<style lang="less" scoped>
.account-form-wrap{
  .account-form-container{
    .account-form-header{
      .header-link{
        .register-label{
          color: #474747;
          font-size: 16px;
          .sub-label{
            color: #20A0FF;
            cursor: pointer;
          }
        }
      }
    }
    .account-form-body {
      .account-form-submit{
        margin-top: 32px;
      }
    }
  }
}
</style>
