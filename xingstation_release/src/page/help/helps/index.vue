<template>
  <div class="root">
    <div class="help-wrap">
      <el-tabs v-model="activeName" @tab-click="handleClick">
        <el-tab-pane label="新手入门" name="first">
          <div>
            <el-form ref="form" :model="form" label-width="80px">
              <el-form-item label="">
                <el-input v-model="form.name" class="help-input" placeholder="您好，请问需要什么帮助？"></el-input>
              </el-form-item>
            </el-form>
            <el-card class="box-card">
              <ul class="help-item-list">
                <li class="help-item">
                  <div class="item-title">星视度互动广告是什么？</div>
                  <p class="item-content">
                    依托人工智能算法，不同用户呈现因人而异、因时而异、因地而异，且资讯量巨大。
                  </p>
                </li>
                <li class="help-item">
                  <div class="item-title">星视度互动广告是什么？</div>
                  <p class="item-content">
                    依托人工智能算法，不同用户呈现因人而异、因时而异、因地而异，且资讯量巨大。
                  </p>
                </li>
                <li class="help-item">
                  <div class="item-title">基本名次解释</div>
                  <p class="item-content">
                    依托人工智能算法，不同用户呈现因人而异、因时而异、因地而异，且资讯量巨大。
                    依托人工智能算法，不同用户呈现因人而异。
                    依托人工智能算法，不同用户呈现因人而异、因时而异、因地而异。
                    依托人工智能算法，不同用户呈现因人而异、因时而异、因地而异，且资讯量巨大。
                  </p>
                </li>
              </ul>
            </el-card>
          </div>
        </el-tab-pane>
        <el-tab-pane label="创建客户" name="second">创建客户</el-tab-pane>
        <el-tab-pane label="创建节目" name="third">创建节目</el-tab-pane>
        <el-tab-pane label="数据查看" name="fourth">数据查看</el-tab-pane>
        <el-tab-pane label="常见问题" name="fifth">常见问题</el-tab-pane>
      </el-tabs>
    </div>
  </div>
</template>

<script>
import { TabPane, Tabs, Input, Form, FormItem, Card} from 'element-ui'

export default {
  data () {
    return {
      activeName: 'first',
      input: '',
      form: {
        name: ''
      }
    }
  },
  methods: {
    handleClick(tab, event) {
      console.log(tab, event);
    }
  },
  components: {
    "el-tabs": Tabs,
    "el-tab-pane": TabPane,
    "el-input": Input,
    "el-form": Form,
    "el-form-item": FormItem,
    "el-card": Card
  }
}
</script>

<style lang="less" scoped>
  .root {
    padding: 10px;
    font-size: 14px;
    color: #5E6D62;
    .help-wrap{
      background: #fff;
      padding: 10px 30px;
      .help-input{
        width: 700px;
      }
      .box-card{
        width: 700px;
        margin: 0 0 80px 80px;
        .help-item-list{
          .help-item{
            text-decoration: none;
            margin-bottom: 20px;
            .item-title{
              font-size: 15px;
              color: #444;
              font-weight: 600;
            }
            .item-content{
              margin: 0;
              padding: 5px 0;
              font-size: 14px;
              line-height: 16px;
              letter-spacing: 0.8px;
            }
          }
        }
      }
    }
  }
</style>
