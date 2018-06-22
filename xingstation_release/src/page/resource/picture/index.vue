<template>
  <div>
    <div class="topbar">
    <el-breadcrumb separator="/">
      <el-breadcrumb-item>图片管理</el-breadcrumb-item>
    </el-breadcrumb>
    </div>
    <div class="picture-manage" v-loading="loading">
      <div class="grouping-image-wrap">
        <div class="image-warp">
          <div class="image-title-group">
            <el-checkbox v-model="checkbox.allChecked" size="small" @change="allCheckedHandle">全选</el-checkbox>
            <el-popover ref="delete-image" placement="bottom" width="260" v-model="mediaImage.mediaDelete" :disabled="mediaGroup.disabledFlag">
              <p>确定删除该图片?</p>
              <p class="hint">若删除，不会对目前已使用该图片的相关业务造成影响。</p>
              <div class="btn-wrap">
                <el-button type="primary" size="small" @click="imageDeleteHandle">确定</el-button>
                <el-button size="small" @click="mediaImage.mediaDelete = false,setModelFlag(mediaImage.mediaList)">取消</el-button>
              </div>
            </el-popover>
            <a v-popover:delete-image :class="{haveChooseImage : !mediaGroup.disabledFlag}">删除</a>
          </div>
          <ul class="image-list">
            <div class="hint-message" v-show="mediaImage.mediaList.length == 0">暂无数据，可点击左下角“上传图片”按钮添加</div>
            <li v-for="(imageItem, index) in mediaImage.mediaList" :key="imageItem.id">
              <img class="image-file" :src="imageItem.media_url" @click="mediaImage.imageVisible = true, mediaImage.mediaImageUrl = imageItem.media_url">
              <p class="item-text"><el-checkbox size="small" v-model="checkbox" @change="checkBoxChange(index,imageItem.id)">{{imageItem.media_name}}</el-checkbox></p>
              <div class="image-operation">
                <el-popover placement="bottom" width="260" v-model="operate">
                  <p>编辑名称</p>
                  <el-input v-model="operate" placeholder="请输入名称" class="rename-input"></el-input>
                  <div class="btn-wrap">
                    <el-button type="primary" size="small" @click="mediaRenameHandle(index,imageItem.id)">确定</el-button>
                    <el-button size="small" @click="handleOperationButtonClick(index, 'rename', imageItem.id)">取消</el-button>
                  </div>
                  <a slot="reference">重命名</a>
                </el-popover>
                <el-popover placement="bottom" width="260" v-model="operate">
                  <p>确定删除该图片?</p>
                  <p class="hint">若删除，不会对目前已使用该图片的相关业务造成影响。</p>
                  <div class="btn-wrap">
                    <el-button type="primary" size="small" @click="imageDeleteHandle(index,imageItem.id)">确定</el-button>
                    <el-button size="small" @click="handleOperationButtonClick(index, '', imageItem.id)">取消</el-button>
                  </div>
                  <a slot="reference">删除</a>
                </el-popover>
              </div>
            </li>
          </ul>
          <div class="submit-warp">
            <el-upload class="upload" :action="mediaBase + '/api/media/media'" :data="{media_group_id: mediaGroup.media_group_id}" :headers="formHeader" :before-upload="beforeUpload" :on-success="handleSuccess" :multiple="true" :auto-upload="true" :show-file-list="false" list-type="picture">
              <el-button size="small" type="success">上传图片</el-button>
            </el-upload>
            <span class="image-type">仅支持jpg、gif、png三种格式</span>
            <div class="pagination">
              <el-pagination layout="total,prev, pager, next" :page-size="pagination.limit" :total="pagination.count" :current-page.sync="pagination.page_num" @current-change="getMediaListByGroupId('')">
              </el-pagination>
            </div>
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="widget-image" v-show="mediaImage.imageVisible">
      <div class="shade-image"></div>
      <div class="widget-content">
        <img :src="mediaBase + mediaImage.mediaImageUrl" />
      </div>
      <div class="widget-close" @click="handleImageClose">
        <i class="widget-icon">X</i>
      </div>
    </div>
  </div>
</template>

<script>
// import picture from 'service/picture'
import Vue from 'vue'
import {
  Popover,
  Button,
  Input,
  Checkbox,
  Upload,
  Pagination,
  Radio,
  Dialog,
  MessageBox
} from 'element-ui'
import auth from 'service/auth'

// const MEDIA_API = '/api/media/media'
// const MEDIA_GROUP_API = '/api/media/mediaGroups'

export default {
  data() {
    return {
      loading: true,
      mediaGroup: {
        mediaGroupDeleteFlag: false,
        mediaGroupRenameFlag: false,
        mediaGroupAddFlag: false,
        mediaGroupList: [],
        disabledFlag: false,
        addGroupNameValue: '',
        renameGroupValue: '',
        groupingRadio: '',
        groupingAllRadio: '',
        is_ext: true,
        media_group_id: null
      },
      pagination: {
        limit: 15,
        page_num: 1,
        count: 0
      },
      operate: {
        renamePopoverArray: [],
        deletePopoverArray: [],
        groupingPopoverArray: [],
        renameValueArray: []
      },
      checkbox: {
        allChecked: false,
        checkboxList: [],
        checkedList: []
      },
      mediaImage: {
        imageVisible: false,
        mediaDelete: false,
        mediaRename: false,
        mediaImageUrl: '',
        mediaList: [
        {
          id: 4,
          media_name: 'WechatIMG9.jpeg',
          media_type: 10,
          media_size: 193003,
          media_url:
            'http://o9xrbl1oc.bkt.clouddn.com/1007/image/234_istar2_icon.png',
          tenant_id: 3,
          media_group_id: 1,
          image_height: 873,
          image_width: 476,
          deleted_at: null,
          created_at: '2018-01-30 10:42:08',
          updated_at: '2018-01-30 10:42:08'
        },
        {
          id: 5,
          media_name: 'TH980oN9b.jpeg',
          media_type: 10,
          media_size: 193003,
          media_url:
            'http://o9xrbl1oc.bkt.clouddn.com/1007/image/100_icon.png',
          tenant_id: 0,
          media_group_id: 1,
          image_height: 873,
          image_width: 476,
          deleted_at: null,
          created_at: '2018-01-30 10:42:20',
          updated_at: '2018-01-30 10:42:20'
        },
        {
          id: 6,
          media_name: 'icon2.png',
          media_type: 10,
          media_size: 106671,
          media_url:
            'http://o9xrbl1oc.bkt.clouddn.com/1007/image/234_istar2_icon.png',
          tenant_id: 1,
          media_group_id: 1,
          image_height: 300,
          image_width: 300,
          deleted_at: null,
          created_at: '2018-02-05 10:20:41',
          updated_at: '2018-02-05 10:20:41'
        },
        {
          id: 7,
          media_name: 'new.png',
          media_type: 10,
          media_size: 2395635,
          media_url:
            'http://o9xrbl1oc.bkt.clouddn.com/1007/image/234_istar2_icon.png',
          tenant_id: 1,
          media_group_id: 1,
          image_height: 1980,
          image_width: 1080,
          deleted_at: null,
          created_at: '2018-02-05 10:25:14',
          updated_at: '2018-02-05 10:25:14'
        },
        {
          id: 8,
          media_name: 'WechatIMG10.png',
          media_type: 10,
          media_size: 26449,
          media_url:
            'http://o9xrbl1oc.bkt.clouddn.com/1007/image/234_istar2_icon.png',
          tenant_id: 1,
          media_group_id: 1,
          image_height: 300,
          image_width: 300,
          deleted_at: null,
          created_at: '2018-02-05 10:39:22',
          updated_at: '2018-02-05 10:39:22'
        },
        {
          id: 9,
          media_name: 'WechatIM.jpeg',
          media_type: 10,
          media_size: 277532,
          media_url:
            'http://o9xrbl1oc.bkt.clouddn.com/1007/image/234_istar2_icon.png',
          tenant_id: 1,
          media_group_id: 1,
          image_height: 1920,
          image_width: 1080,
          deleted_at: null,
          created_at: '2018-02-05 10:39:29',
          updated_at: '2018-02-05 10:39:29'
        },
        {
          id: 10,
          media_name: '042.png',
          media_type: 10,
          media_size: 2425727,
          media_url:
            'http://o9xrbl1oc.bkt.clouddn.com/1007/image/234_istar2_icon.png',
          tenant_id: 1,
          media_group_id: 1,
          image_height: 1980,
          image_width: 1080,
          deleted_at: null,
          created_at: '2018-02-06 15:18:52',
          updated_at: '2018-02-06 15:18:52'
        },
        {
          id: 11,
          media_name: 'timg-300x244.jpeg',
          media_type: 10,
          media_size: 14390,
          media_url:
            'http://o9xrbl1oc.bkt.clouddn.com/1007/image/234_istar2_icon.png',
          tenant_id: 0,
          media_group_id: 1,
          image_height: 244,
          image_width: 300,
          deleted_at: null,
          created_at: '2018-04-24 09:43:30',
          updated_at: '2018-04-24 09:43:30'
        },
        {
          id: 12,
          media_name: 'dockerlnmp.png',
          media_type: 10,
          media_size: 21981,
          media_url:
            'http://o9xrbl1oc.bkt.clouddn.com/1007/image/234_istar2_icon.png',
          tenant_id: 0,
          media_group_id: 1,
          image_height: 337,
          image_width: 337,
          deleted_at: null,
          created_at: '2018-05-06 11:23:22',
          updated_at: '2018-05-06 11:23:22'
        }
      ]
      },
      formHeader: {
        Authorization: ''
      },
      mediaBase: process.env.SERVER_URL,
      defaultGroupId: ''
    }
  },
  created: function() {
    // this.getGroupsList()
    this.loading = false
  },
  computed: {
    maxlength: function() {
      return Number.parseInt(6)
    },
    deleteGroup: function() {
      let booleanFlag = true
      return this.mediaImage.mediaList.length > 0 ? booleanFlag : !booleanFlag
    }
  },
  methods: {
    handleImageClose() {
      this.mediaImage.imageVisible = false
    },
    // 得到所有的分组列表
    getGroupsList() {
      picture.getMediaGroupsList(this, 'picture_system', picture)
    },
    getGroupId() {
      let groupId = this.mediaGroup.mediaGroupList[this.defaultGroupId].id
      return groupId
    },
    // 根据分组ID得到分组下的详细列表
    getMediaListByGroupId(groupId) {
      this.loading = true
      if (groupId) {
        this.mediaGroup.media_group_id =
          groupId === undefined ? this.mediaGroup.media_group_id : groupId
        this.mediaGroup.groupingRadio = groupId
        this.mediaGroup.groupingAllRadio = groupId
        this.mediaGroup.renameGroupValue = this.mediaGroup.mediaGroupList[
          groupId
        ].media_group_name
        this.mediaGroup.is_ext = this.mediaGroup.mediaGroupList[groupId].is_ext
      }
      let params = {
        media_group_id: this.mediaGroup.media_group_id,
        limit: this.pagination.limit,
        page_num: this.pagination.page_num
      }
      picture.getMediaListById(this, params, 'picture_system')
    },
    // 设置for循环列表中的v-model值
    setModelFlag(dataList) {
      this.checkbox.checkboxList = []
      this.operate.renameValueArray = []
      this.operate.renamePopoverArray = []
      this.operate.deletePopoverArray = []
      this.operate.groupingPopoverArray = []
      dataList.map(v => {
        this.operate.groupingPopoverArray.push({ flag: false })
        this.operate.renamePopoverArray.push({ flag: false })
        this.operate.deletePopoverArray.push({ flag: false })
        this.checkbox.checkboxList.push({ flag: false })
        this.operate.renameValueArray.push({ media_name: v.media_name })
      })
      this.checkbox.allChecked = false
      this.mediaGroup.disabledFlag = true
      this.checkbox.checkedList = []
    },
    // 处理图片列表中按钮的确定和取消操作的标记
    handleOperationButtonClick(index, modelName, imageId) {
      if (modelName === 'rename') {
        this.operate.renameValueArray[
          index
        ].media_name = this.mediaImage.mediaList[index].media_name
        this.operate.renamePopoverArray[index].flag = false
      } else if (modelName === 'grouping') {
        this.mediaGroup.groupingRadio = this.mediaGroup.media_group_id
        this.operate.groupingPopoverArray[index].flag = false
      } else {
        this.operate.deletePopoverArray[index].flag = false
      }
      this.checkbox.checkboxList[index].flag = false
      this.disabledHandle(imageId)
    },
    // 处理修改分组，删除是否给以点击
    disabledHandle(imageId, type) {
      if (type != 'boxChange') {
        let indexArr = this.checkbox.checkedList.indexOf(imageId)
        if (indexArr !== -1) {
          this.checkbox.checkedList.splice(indexArr, 1)
        }
      }
      if (this.checkbox.checkedList.length > 0) {
        this.mediaGroup.disabledFlag = false
        if (
          this.checkbox.checkedList.length == this.checkbox.checkboxList.length
        ) {
          this.checkbox.allChecked = true
        }
      } else {
        this.mediaGroup.disabledFlag = true
        this.checkbox.allChecked = false
      }
    },
    // 图片的重命名处理
    mediaRenameHandle(index, imageId) {
      if (this.operate.renameValueArray[index].media_name.trim()) {
        this.$http
          .put(
            `${MEDIA_API}/${imageId}/mediaName/${
              this.operate.renameValueArray[index].media_name
            }`
          )
          .then(res => {
            this.getMediaListByGroupId()
          })
          .catch(function(err) {
            console.log(err)
          })
        this.operate.renamePopoverArray[index].flag = false
      } else {
        this.operate.renameValueArray[
          index
        ].media_name = this.mediaImage.mediaList[index].media_name
        this.operate.renamePopoverArray[index].flag = false
        MessageBox.alert('请输入要重命名的内容')
      }
    },
    // 图片的删除操作
    imageDeleteHandle(index, imageId) {
      this.loading = true
      if (imageId) {
        this.checkbox.checkedList.push(imageId)
      }
      this.$http
        .delete(`${MEDIA_API}/${this.checkbox.checkedList.join(',')}`)
        .then(res => {
          this.getMediaListByGroupId()
          if (this.mediaImage.mediaDelete) {
            this.mediaImage.mediaDelete = false
          } else {
            this.operate.deletePopoverArray[index].flag = false
          }
        })
        .catch(function(err) {
          console.log(err)
        })
    },
    beforeUpload(file) {
      let mediaGroupId =
        this.mediaGroup.media_group_id === null
          ? this.getGroupId()
          : this.mediaGroup.media_group_id
      let isJPG =
        file.type === 'image/jpeg' ||
        file.type === 'image/png' ||
        file.type === 'image/gif' ||
        file.type === 'image/bmp'
      if (!isJPG) {
        this.$message.error('上传图片仅支持jpg、gif、png三种格式!')
        return isJPG
      } else {
        picture.beforeUploadImage(this, mediaGroupId, auth, 'picture_system')
      }
    },
    // 上传成功后的处理
    handleSuccess() {
      this.getMediaListByGroupId()
    },
    // 图片分组处理
    imageGroupingHandle(index, imageId, type) {
      this.loading = true
      let imageIdArr = []
      let _this = this
      let mediaGroupId = 0
      if (type === 'single') {
        imageIdArr.push(imageId)
        this.operate.groupingPopoverArray[index].flag = false
        mediaGroupId = Number.parseInt(this.mediaGroup.groupingRadio)
      } else {
        imageIdArr = imageId
        this.mediaImage.mediaRename = false
        mediaGroupId = Number.parseInt(this.mediaGroup.groupingAllRadio)
        this.checkbox.allChecked = false
      }
      for (let item of this.checkbox.checkboxList) {
        item.flag = false
      }
      this.checkbox.checkedList = []
      let ids = imageIdArr.join(',')
      this.$http
        .put(MEDIA_API + '/' + ids + '/mediaGroup/' + mediaGroupId)
        .then(res => {
          if (!this.serch.searchFlag) {
            this.mediaGroup.mediaGroupList[
              this.mediaGroup.media_group_id
            ].media_count =
              this.mediaGroup.mediaGroupList[this.mediaGroup.media_group_id]
                .media_count - imageIdArr.length
            this.mediaGroup.mediaGroupList[mediaGroupId].media_count =
              this.mediaGroup.mediaGroupList[mediaGroupId].media_count +
              imageIdArr.length
            this.getMediaListByGroupId(this.mediaGroup.media_group_id)
          } else {
            this.loading = false
          }
        })
        .catch(function(err) {
          _this.checkbox.allChecked = false
          console.log(err)
        })
    },
    // 多选框改变时候的处理
    checkBoxChange(index, imageId) {
      if (this.checkbox.checkboxList[index].flag === true) {
        this.checkbox.checkedList.push(imageId)
      } else {
        let indexArr = this.checkbox.checkedList.indexOf(imageId)
        this.checkbox.checkedList.splice(indexArr, 1)
      }
      this.disabledHandle(imageId, 'boxChange')
    },
    // 全选框处理
    allCheckedHandle() {
      if (this.checkbox.allChecked) {
        this.checkbox.checkedList = []
        for (let item of this.checkbox.checkboxList) {
          item.flag = true
        }
        for (let imageItem of this.mediaImage.mediaList) {
          this.checkbox.checkedList.push(imageItem.id)
        }
        if (this.mediaImage.mediaList.length != 0) {
          this.mediaGroup.disabledFlag = false
        } else {
          this.mediaGroup.disabledFlag = true
        }
      } else {
        for (let item of this.checkbox.checkboxList) {
          item.flag = false
        }
        this.checkbox.checkedList = []
        this.mediaGroup.disabledFlag = true
      }
    },
  },
  components: {
    'el-button': Button,
    'el-popover': Popover,
    'el-input': Input,
    'el-checkbox': Checkbox,
    'el-upload': Upload,
    'el-pagination': Pagination,
    'el-radio': Radio,
    'el-dialog': Dialog
  }
}
</script>

<style lang="less" scoped>
.picture-manage {
  background-color: #fff;
  min-height: 500px;
  padding-bottom: 100px;
  .grouping-image-wrap {
    padding-top: 30px;
    display: -webkit-flex;
    display: -moz-flex;
    display: flex;
    .image-warp {
      margin-left: 40px;
      position: relative;
      width: 100%;
      .image-title-group {
        background-color: #eff2f7;
        height: 57px;
        line-height: 57px;
        margin-right: 30px;
        width: 97.5%;
      }
      .image-title-group a {
        color: #6b798c;
        cursor: pointer;
        font-size: 13px;
        margin-right: 10px;
      }
      .image-title-group .el-checkbox {
        color: #6b798c;
        margin: 0 10px;
        font-size: 13px;
      }
      .image-title-group .haveChooseImage {
        color: #20a0ff;
      }
    }
    .image-list {
      margin-top: 10px;
      padding-bottom: 80px;
      .image-size {
        background-color: rgba(0, 0, 0, 0.5);
        bottom: 47px;
        color: #fff;
        font-size: 14px;
        text-align: center;
        height: 35px;
        line-height: 35px;
        position: absolute;
        width: 130px;
      }
    }
    .image-list li {
      width: 150px;
      display: inline-block;
      position: relative;
      margin-top: 5px;
      margin-bottom: 10px;
    }
    .image-list li p {
      color: #494949;
      font-size: 14px;
      line-height: 18px;
      height: 18px;
      margin: 5px 0;
      width: 130px;
    }
  }
}
.hint-message {
  text-align: center;
  font-size: 12px;
  color: #ccc;
}
.picture-group-title {
  padding: 27px 0 13px 27px;
}
.picture-group-title span {
  color: #5e6d82;
  font-size: 18px;
  line-height: 25px;
}
.picture-group-title a {
  color: #20a0ff;
  cursor: pointer;
  font-size: 13px;
  line-height: 18px;
  padding-left: 18px;
}
.el-popover {
  padding: 10px 15px;
}
.el-popover p {
  font-size: 14px;
  line-height: 20px;
}
.rename-input,
.group-input {
  margin-bottom: 15px;
}
.hint {
  color: #999;
}
.btn-wrap {
  text-align: right;
}

.grouping-wrap {
  background-color: #eff2f7;
  margin-left: 27px;
  max-height: 768px;
  min-width: 180px;
  min-height: 400px;
  overflow: auto;
}
.grouping-list li {
  cursor: pointer;
  color: #5e6d82;
  font-size: 14px;
  line-height: 42px;
  padding: 0 18px;
  transition: all 0.5s;
}
.grouping-list li:hover {
  color: #000;
}
.grouping-active {
  background-color: #fff;
}
.number {
  float: right;
}
.grouping-wrap .el-button {
  background-color: #eff2f7;
  color: #56636d;
  font-size: 14px;
  margin: 10px 18px 10px;
  outline: none;
  width: 144px;
  border-radius: initial;
}
.grouping-wrap .el-button:hover {
  border: 1px solid #bfcbd9;
}

.submit-warp {
  background-color: #eff2f7;
  bottom: 0;
  height: 57px;
  margin-right: 20px;
  left: 0;
  position: absolute;
  min-width: 700px;
  width: 97.5%;
}

.upload {
  text-align: left;
  display: inline-block;
  margin-left: 23px;
  margin-top: 16px;
  width: 100px;
}
.pagination {
  text-align: right;
  margin-top: -32px;
  margin-right: 10px;
}


.image-operation a {
  color: #20a0ff;
  cursor: pointer;
  font-size: 13px;
  line-height: 17px;
  margin-right: 6px;
}
.image-file {
  cursor: pointer;
  height: 130px;
  margin-right: 10px;
  width: 130px;
}
.groupRadioList {
  height: 200px;
  overflow-y: auto;
}
.image-type {
  color: #999;
  font-size: 12px;
}
.groupRadioList li {
  padding: 5px 0;
}


.picture-group-title .serachTitle {
  color: rgb(51, 136, 255);
  cursor: pointer;
}
.widget-close {
  background: #fff;
  border-radius: 50%;
  cursor: pointer;
  position: absolute;
  right: 5%;
  top: 5%;
  z-index: 3444;
}
.widget-icon {
  display: block;
  font-style: normal;
  text-align: center;
  height: 40px;
  line-height: 40px;
  width: 40px;
}
.shade-image {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: #000;
  opacity: 0.6;
  z-index: 2000;
}
.widget-content {
  -ms-transform: translateX(-50%);
  left: 50%;
  transform: translateX(-50%);
  top: 20%;
  position: absolute;
  z-index: 2001;
}
.search-after-submit {
  bottom: -24px;
  left: 0;
  min-width: 796px;
}
.search-after-submit .pagination {
  margin-top: 13px;
}
.serch-input {
  float: right;
  margin-top: -60px;
  padding-left: 30px;
  margin-right: 35px;
  border: 1px solid #d3dce6;
  border-radius: 5px;
  width: 163px;
  height: 33px;
  background-color: #eff2f7;
  background-image: url('/assets/images/icons/search-icon.png');
  background-repeat: no-repeat;
  background-position: 5% 50%;
}
@media (max-width: 1255px) {
  .serch-input {
    margin-right: 30px;
  }
}
@media (min-width: 1497px) {
  .serch-input {
    margin-right: 45px;
  }
}
</style>

