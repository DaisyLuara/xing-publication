<!-- <div class="pane" v-if="this.step === 2">
      <div class="pane-title">
        定向设置
      </div>
      <el-form
        ref="stepTwo"
        :model="stepTwo" label-width="80px">
        <el-form-item label="节目形式" prop="modality">
          <el-radio-group v-model="stepTwo.modality">
            <el-radio :label="1">硬广</el-radio>
            <el-radio :label="2">应用</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="性别">
          <el-radio-group v-model="stepTwo.gender">
            <el-radio :label="1">男</el-radio>
            <el-radio :label="2">女</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="年龄">
          <el-radio-group v-model="stepTwo.age">
            <el-radio :label="1">0-17</el-radio>
            <el-radio :label="2">18-35</el-radio>
            <el-radio :label="3">36-45</el-radio>
            <el-radio :label="4">46以上</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="颜值">
          <el-radio-group v-model="stepTwo.appearance">
            <el-radio :label="1">低</el-radio>
            <el-radio :label="2">中</el-radio>
            <el-radio :label="3">高</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="人数">
          <el-radio-group v-model="stepTwo.peopleCount">
            <el-radio :label="1">0</el-radio>
            <el-radio :label="2">1</el-radio>
            <el-radio :label="3">2</el-radio>
            <el-radio :label="4">3人以上</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="穿衣指数">
          <el-radio-group v-model="stepTwo.dressingIndex">
            <el-radio :label="1">冷</el-radio>
            <el-radio :label="2">凉</el-radio>
            <el-radio :label="3">舒适</el-radio>
            <el-radio :label="4">热</el-radio>
            <el-radio :label="5">炎热</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="气象指数">
          <el-radio-group v-model="stepTwo.weatherIndex">
            <el-radio :label="1">晴天</el-radio>
            <el-radio :label="2">阴天</el-radio>
            <el-radio :label="3">雨天</el-radio>
            <el-radio :label="4">雾霾</el-radio>
            <el-radio :label="5">雪</el-radio>
          </el-radio-group>
        </el-form-item>
      </el-form>
      <el-button type="default" size="small" @click="goPreStep">上一步</el-button>
      <el-button type="primary" size="small" @click="goNextStep">下一步</el-button>
    </div>
    <div class="pane" v-if="this.step === 3" v-show="playType">
      <div class="pane-title">
        节目设置<el-button type="success" size="small" @click="addProgram">增加</el-button>
      </div>
      <el-form
        ref="stepThree"
        :model="stepThree" label-width="80px">
        <div class="item-list" v-for="item in items" :key="item.id">
          <label class="program-title">
            节目 {{item.id}}
            <el-button type="danger" size="small" @click="removeProgram(item.id)">删除</el-button>
          </label>
          <el-form-item label="播放类型" prop="playType">
            <el-radio-group v-model="stepThree.playType">
              <el-radio :label="1">大屏播放</el-radio>
              <el-radio :label="2">小屏播放</el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item label="素材类型">
            <el-radio-group v-model="stepThree.materialType">
              <el-radio :label="1">图片</el-radio>
              <el-radio :label="2">视频</el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item label="上传素材">
            <div class="upload-block">
              <div class="up-area-cover">
                <img v-if="stepThree.imageUrl" :src="stepThree.imageUrl" class="cover">
                <i v-else class="el-icon-plus cover-uploader-icon" @click="handleOpenPane"></i>
                <i v-if="stepThree.imageUrl !== ''" class="el-icon-circle-cross delete-icon-image" @click="handleImageDelete()"></i>
                <span>1920 x 1080 ( 推荐尺寸，jpg / png )</span>
              </div>
            </div>
          </el-form-item>
          <el-form-item label="转场效果">
            <el-radio-group v-model="stepThree.transitionType">
              <el-radio :label="1">淡进淡出</el-radio>
              <el-radio :label="2">放大缩小</el-radio>
              <el-radio :label="3">旋转大小</el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item label="播放时间">
            <el-radio-group v-model="stepThree.playDate">
              <el-radio :label="1">6秒</el-radio>
              <el-radio :label="2">15秒</el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item label="落地页面">
            <el-radio-group v-model="stepThree.pageType">
              <el-radio :label="1">H5</el-radio>
              <el-radio :label="2">小程序</el-radio>
              <el-radio :label="3">公众号</el-radio>
            </el-radio-group>
            <label style="margin-left: 30px;">修改落地页，广告不下线</label>
            <el-switch v-model="switchValue" ></el-switch>
          </el-form-item>
          <hr class="hr-line"/>
        </div>
      </el-form>
      <el-button type="default" size="small" @click="goPreStep">上一步</el-button>
      <el-button type="primary" size="small" @click="goNextStep">下一步</el-button>
    </div>
    <div class="pane" v-if="this.step === 3" v-show="!playType">
      <div class="pane-title">
        节目设置
      </div>
      <el-form
        ref="stepThree"
        :model="stepThree" label-width="80px">
        <div class="">
          <el-form-item label="上传应用">
            <div class="upload-block">
              <div class="up-area-cover">
                <img v-if="stepThree.imageUrl" :src="stepThree.imageUrl" class="cover">
                <i v-else class="el-icon-plus cover-uploader-icon" @click="handleOpenPane"></i>
                <i v-if="stepThree.imageUrl !== ''" class="el-icon-circle-cross delete-icon-image" @click="handleImageDelete()"></i>
                <span>9:16竖屏，最大不超过100MB</span>
              </div>
            </div>
          </el-form-item>
          <el-form-item label="落地页面">
            <el-radio-group v-model="stepThree.pageType">
              <el-radio :label="1">H5</el-radio>
              <el-radio :label="2">小程序</el-radio>
              <el-radio :label="3">公众号</el-radio>
            </el-radio-group>
          </el-form-item>
        </div>
      </el-form>
      <el-button type="default" size="small" @click="goPreStep">上一步</el-button>
      <el-button type="primary" size="small" @click="goNextStep">下一步</el-button>
    </div>
    <div class="pane" v-if="this.step === 4">
      <div class="pane-title">
        提交信息
      </div>
      <div>
        <el-card class="box-card">
          <div slot="header" class="clearfix header">
            <span>信息预览</span>
          </div>
          <div class="preview-content">
            <div class="preview-info-line">
              <el-row>
                <el-col :span="2"><label class="preview-title">所属客户:</label></el-col>
                <el-col :span="10"><label class="preview-content">测试客户</label></el-col>
              </el-row>
            </div>
            <div class="preview-info-line">
              <el-row>
                <el-col :span="2"><label class="preview-title">节目合约:</label></el-col>
                <el-col :span="10"><label class="preview-content">测试客户</label></el-col>
              </el-row>
            </div>
            <div class="preview-info-line">
              <el-row>
                <el-col :span="2"><label class="preview-title">节目名称:</label></el-col>
                <el-col :span="10"><label class="preview-content">测试客户</label></el-col>
              </el-row>
            </div>
            <div class="preview-info-line">
              <el-row>
                <el-col :span="2"><label class="preview-title">发布位置:</label></el-col>
                <el-col :span="10"><label class="preview-content">上海浦东新区八佰伴1楼</label></el-col>
              </el-row>
            </div>
            <div class="preview-info-line">
              <el-row>
                <el-col :span="2"><label class="preview-title">投放日期:</label></el-col>
                <el-col :span="10"><label class="preview-content">2018-09-09</label></el-col>
              </el-row>
            </div>
            <div class="preview-info-line">
              <el-row>
                <el-col :span="2"><label class="preview-title">投放时段:</label></el-col>
                <el-col :span="10"><label class="preview-content">2018-09-09 - 2018-10-09</label></el-col>
              </el-row>
            </div>
          </div>
        </el-card>
      </div>
      <el-form
        ref="stepTwo"
        :model="stepFour" label-width="80px">
        <el-form-item label="审核人">
          <el-select v-model="stepFour.checkPeople" filterable placeholder="请选择">
            <el-option
              v-for="item in checkOptions"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </el-form-item>
      </el-form>
      <el-button type="default" size="small" @click="goPreStep">上一步</el-button>
      <el-button type="primary" size="small" @click="submit">确定发布</el-button>
    </div> -->