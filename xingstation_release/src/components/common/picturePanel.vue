<template>
  <div 
    class="picture-panel">
    <el-dialog 
      :visible.sync="panelVisible" 
      :before-close="cancel"
      size="large" 
      @open="handleOpen()">
      <div 
        slot="title">
        <span 
          v-show="!serch.searchFlag"
          class="picture-panel__title">图片管理</span>
        <span 
          v-show="serch.searchFlag">
          <a 
            class="backImgPanel" 
            href="javascript:;" 
            @click="serch.searchFlag=false,pagination.page_num = 1">  我的图片 </a> | 搜索结果</span>
        <input 
          v-model="serch.searchText" 
          placeholder="搜索"
          class="picture-panel__search" 
          @keyup.enter="searchMedia()">
      </div>
      <div>
        <el-tabs 
          v-loading="loading" 
          v-show="!serch.searchFlag"
          v-model="activeTabName"
          type="card" 
          @tab-click="handleTabsClick">
          <el-tab-pane 
            v-for="item in mediaGroup.mediaGroupList" 
            :name="item.media_group_name" 
            :media-group-id="item.id" 
            :key="item.id">
            <span 
              slot="label"
              :mediaGroupId="item.id"> 
              {{ item.media_group_name }}
              <span 
                class="number">
                {{ item.media_count }}
              </span>
            </span>
            <div 
              class="picture-panel__body">
              <li 
                v-for="obj in item.data" 
                :key="obj.id"
                class="picture-panel__img-item"  
                @click="selectImg(obj)" >
                <img 
                  :src="mediaBase + obj.media_url"
                  class="picture-panel__img">
                <div 
                  class="picture-panel__img-size">{{ obj.image_width }} * {{ obj.image_height }}</div>
                <div 
                  class="picture-panel__img-name">{{ obj.media_name }}</div>
                <div 
                  v-for="selectedObj in selectedImgs" 
                  :key="selectedObj.id">
                  <div 
                    v-if="obj.id == selectedObj.id">
                    <div 
                      class="picture-panel__arrow-wrap"/>
                    <i 
                      class="picture-panel__arrow"/>
                  </div>
                </div>
              </li>
            </div>
          </el-tab-pane>
        </el-tabs>
        <div 
          v-loading="loading" 
          v-show="serch.searchFlag">
          <div 
            class="picture-panel__searched-body">
            <li 
              v-for="obj in searchedMediaList" 
              :key="obj.id" 
              class="picture-panel__img-item"  
              @click="selectImg(obj)">
              <img 
                :src="mediaBase + obj.media_url"
                class="picture-panel__img" >
              <div 
                class="picture-panel__img-size">{{ obj.image_width }} * {{ obj.image_height }}</div>
              <div 
                class="picture-panel__img-name">{{ obj.media_name }}</div>
              <div 
                v-for="selectedObj in selectedImgs" 
                :key="selectedObj.id">
                <div  
                  v-show="obj.id==selectedObj.id">
                  <div 
                    class="picture-panel__arrow-wrap"/>
                  <i 
                    class="picture-panel__arrow"/>
                </div>
              </div>
            </li>
          </div>
        </div>
        <div 
          class="picture-panel__footer">
          <el-upload 
            :action="mediaBase + '/api/media/media'" 
            :data="form" 
            :headers="formHeader" 
            :before-upload="beforeUpload" 
            :on-success="handleSuccess" 
            :multiple="true" 
            :auto-upload="true" 
            :show-file-list="false" 
            :disabled="uploadDisabled"
            list-type="picture" 
            class="picture-panel__upload">
            <el-button 
              size="small" 
              type="primary"
              class="picture-panep__upload-btn" >点击上传</el-button>
          </el-upload>
          <span 
            class="image-type">仅支持jpg、gif、png三种格式</span>
          <div 
            class="picture-panel__page">
            <el-pagination 
              :total="pagination.count" 
              :page-size="pagination.limit" 
              :current-page.sync="pagination.page_num"
              layout="total, prev, pager, next" 
              @current-change="getMedia('')"/>
          </div>
        </div>
      </div>
      <div 
        slot="footer">
        <div 
          name="footer" 
          class="footer">
          <div 
            class="picture-panel__choose-num">
            已选择{{ selectedImgs.length }}张图片
          </div>
          <el-button 
            @click="cancel()">取 消</el-button>
          <el-button 
            type="primary" 
            @click="confirm()">确 定</el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script>
import picture from 'service/picture'
import {
  Dialog,
  Button,
  Tabs,
  TabPane,
  Upload,
  Pagination,
  MessageBox
} from 'element-ui'
import auth from 'service/auth'

export default {
  name: 'PicturePanel',
  components: {
    'el-dialog': Dialog,
    'el-button': Button,
    'el-tabs': Tabs,
    'el-tab-pane': TabPane,
    'el-upload': Upload,
    'el-pagination': Pagination
  },
  props: {
    panelVisible: {
      type: Boolean,
      required: true
    },
    singleFlag: {
      type: Boolean,
      required: true
    }
  },
  data() {
    return {
      loading: true,
      mediaGroup: {
        mediaGroupList: []
      },
      activeTabName: '',
      searchedMediaList: [],
      serch: {
        searchText: '',
        searchFlag: false
      },
      formHeader: {
        Authorization: ''
      },
      form: {
        media_group_id: null
      },
      pagination: {
        limit: 15,
        page_num: 1,
        count: 0
      },
      selectedImgs: [],
      mediaBase: process.env.SERVER_URL,
      uploadDisabled: false
    }
  },
  methods: {
    handleOpen() {
      this.loading = true
      this.getMediaGroup()
    },

    handleClose(selectedImgs) {
      this.mediaGroup.mediaGroupList = []
      this.form.media_group_id = 0
      this.activeTabName = ''
      this.serch.searchFlag = false
      this.serch.searchText = ''
      this.earchedMediaList = []
      this.selectedImgs = []
      this.uploadDisabled = false
      this.$emit('update:panelVisible', false)
      this.$emit('close', selectedImgs)
    },

    cancel() {
      this.handleClose([])
    },

    confirm() {
      this.handleClose(this.selectedImgs)
    },

    selectImg(obj) {
      var isExsisted = false
      if (this.singleFlag) {
        this.selectedImgs = []
        this.selectedImgs.push(obj)
      } else {
        for (let i = 0; i < this.selectedImgs.length; i++) {
          if (this.selectedImgs[i].id == obj.id) {
            isExsisted = true
            this.selectedImgs.splice(i, 1)
            break
          }
        }
        if (isExsisted == false) {
          if (this.selectedImgs.length < 5) {
            // 存储所上传的图片用，图片的数量不超过5张
            this.selectedImgs.push(obj)
          } else {
            MessageBox.alert('存储所上传的图片数量不超过5张')
          }
        }
      }
    },

    getMediaGroup() {
      picture.getMediaGroupsList(this, '', picture)
    },
    getMedia(mediaGroupId) {
      this.loading = true
      let params = {
        media_group_id:
          mediaGroupId === undefined ? mediaGroupId : this.form.media_group_id,
        limit: this.pagination.limit,
        page_num: this.pagination.page_num
      }
      picture.getMediaListById(this, params, '')
    },
    searchMedia() {
      this.loading = true
      let params = {
        media_name: this.serch.searchText,
        limit: this.pagination.limit,
        page_num: this.pagination.page_num
      }
      picture.searchHandle(this, params, '')
    },

    handleTabsClick(tab, event) {
      var selId = tab.$vnode.data.attrs.mediaGroupId
      if (selId == this.form.media_group_id) {
        return
      }
      this.form.media_group_id = selId
      this.loading = true
      this.getMedia(this.form.media_group_id)
    },

    handleSuccess(response, file, fileList) {
      this.getMedia(this.form.media_group_id)
      this.selectImg(response.data)
      if (this.selectedImgs.length >= 5) {
        this.uploadDisabled = true
      }
    },

    beforeUpload(file) {
      let mediaGroupId = this.form.media_group_id
      let isJPG =
        file.type === 'image/jpeg' ||
        file.type === 'image/png' ||
        file.type === 'image/gif' ||
        file.type === 'image/bmp'
      if (!isJPG) {
        this.$message.error('上传图片仅支持jpg、gif、png三种格式!')
        return isJPG
      } else {
        picture.beforeUploadImage(this, mediaGroupId, auth)
      }
    }
  }
}
</script>


<style lang="less">
.picture-panel {
  .el-dialog--large {
    width: 70%;
  }

  .el-dialog__header {
    padding: 20px 20px 20px;
  }

  .el-dialog__headerbtn {
    position: absolute;
    right: 15px;
    top: 20px;
    .el-icon {
      font-size: 20px;
    }
  }
  .image-type {
    color: #999;
    font-size: 12px;
  }
  .el-dialog__body {
    position: relative;
    padding: 0px;
    border-top: 1px solid #d3dce6;
    border-bottom: 1px solid #d3dce6;
    border-right: 1px solid #d3dce6;
    .el-tabs {
      height: 450px;
      .el-tabs__header {
        float: left;
      }
    }
    .el-tabs__header {
      z-index: 3;
      background-color: white;
      height: 100%;
      width: 170px;
      border-right: 1px solid rgb(209, 219, 229);
      border-bottom: none;
      padding: 0;
      position: relative;
      margin: 0 0 15px;
      float: left;
      .el-tabs__nav {
        width: 100%;
        .el-tabs__item {
          display: block;
          background-color: #eff2f7;
          .number {
            float: right;
          }
          &.is-active {
            border: none;
            background-color: white;
          }
        }
      }
    }
    .el-tabs__content {
      height: 100%;
      overflow: scroll;
      .el-tab-pane {
        padding-bottom: 100px;
        height: 100%;
      }
    }
  }
  .el-dialog__footer {
    .footer {
      text-align: center;
      button {
        width: 150px;
      }
    }
  }
}
.picture-panel__choose-num {
  font-size: 14px;
  display: inline-block;
  position: absolute;
  left: 18px;
  line-height: 40px;
  color: #56636d;
}

.picture-panel__title {
  color: #c0ccda;
  font-size: 20px;
  font-weight: 300;
  .backImgPanel {
    color: #38f;
    font-weight: bold;
  }
  .backImgPanel:hover {
    color: #07d;
  }
}
.picture-panel__search {
  float: right;
  margin-top: -7px;
  padding-left: 30px !important;
  margin-right: 30px;
  border: 1px solid #d3dce6;
  border-radius: 5px;
  width: 163px;
  height: 33px;
  background-color: #eff2f7;
  background-image: url('../../assets/images/icons/search-icon.png');
  background-repeat: no-repeat;
  background-position: 5% 50%;
}

.picture-panel__body {
  padding-bottom: 100px;
}
.picture-panel__img-item {
  overflow: hidden;
  position: relative;
  margin-left: 20px;
  margin-top: 20px;
  margin-bottom: 24px;
  height: 160px;
  display: inline-block;
  cursor: pointer;
}
.picture-panel__img {
  width: 130px;
  height: 130px;
}
.picture-panel__img-size {
  position: absolute;
  bottom: 30px;
  width: 130px;
  color: white;
  background-color: rgba(0, 0, 0, 0.5);
  text-align: center;
  height: 25px;
  line-height: 25px;
  font-size: 12px;
}
.picture-panel__img-name {
  position: absolute;
  bottom: 10px;
  width: 130px;
  white-space: nowrap;
  text-overflow: ellipsis;
  -o-text-overflow: ellipsis;
  overflow: hidden;
}
.picture-panel__arrow {
  background-image: url('../../assets/images/icons/selected.png');
  background-repeat: no-repeat;
  height: 15px;
  position: absolute;
  top: 4px;
  right: 4px;
  width: 15px;
}
.picture-panel__arrow-wrap {
  transform: rotate(45deg);
  width: 50px;
  height: 50px;
  background-color: #20a0ff;
  position: absolute;
  top: -25px;
  right: -25px;
}

.picture-panel__searched-body {
  overflow: scroll;
  padding-bottom: 100px;
}

.picture-panel__footer {
  height: 57px;
  background-color: #eff2f7;
  position: absolute;
  bottom: 0px;
  padding-left: 170px;
  width: 100%;
}
.picture-panel__upload {
  display: inline-block;
  .el-upload {
    line-height: 57px;
    margin-left: 30px;
  }
}

.picture-panep__upload-btn {
  width: 86px;
  height: 36px;
  background-color: #13ce66;
  border: none;
}

.picture-panel__page {
  float: right;
  margin-top: 13px;
  display: inline-block;
  vertical-align: middle;
}
</style>
