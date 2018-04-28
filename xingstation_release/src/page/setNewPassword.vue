<template>
  <account-form :type="'setNewPassword'"></account-form>
</template>
<script>
import accountForm from '../components/common/accountForm'
import auth from 'service/auth'
import { Button, Input, Form, FormItem, Checkbox, CheckboxGroup, Col } from 'element-ui'
import axios from 'axios'
export default {
  name: 'setNewPassword',
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
  
</style>
