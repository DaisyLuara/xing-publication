<template>
  <div>
    <div
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText"
      class="picture-manage"
    >
      <div class="grouping-image-wrap">
        <div class="image-warp">
          <div class="image-title-group"></div>
          <!-- 图片列表 -->
          <ul class="image-list">
            <div
              v-show="mediaImage.mediaList.length == 0"
              class="hint-message"
            >暂无数据，可点击左下角“上传图片”按钮添加</div>
            <li v-for="(imageItem, index) in mediaImage.mediaList" :key="imageItem.id">
              <img
                :src="imageItem.url"
                class="image-file"
                @click="mediaImage.imageVisible = true, mediaImage.mediaImageUrl = imageItem.url"
              >
              <div class="image-size">{{ imageItem.width }} * {{ imageItem.height }}</div>
              <p
                class="item-text"
              >{{ imageItem.name.length>13 ? imageItem.name.substring(0,12)+'...':imageItem.name }}</p>
              <div class="image-operation">
                <!-- 编辑名称 -->
                <el-popover
                  v-model="operate.renamePopoverArray[index].flag"
                  placement="bottom"
                  width="260"
                >
                  <p>编辑名称</p>
                  <el-input
                    v-model="operate.renameValueArray[index].name"
                    placeholder="请输入名称"
                    class="rename-input"
                  />
                  <div class="btn-wrap">
                    <el-button
                      type="primary"
                      size="small"
                      @click="mediaRenameHandle(index,imageItem.id)"
                    >确定</el-button>
                    <el-button
                      size="small"
                      @click="handleOperationButtonClick(index, 'rename', imageItem.id)"
                    >取消</el-button>
                  </div>
                  <a slot="reference">重命名</a>
                </el-popover>
              </div>
            </li>
          </ul>
          <!-- 图片上传 -->
          <div class="submit-warp">
            <el-upload
              ref="upload"
              :action="Domain"
              :data="uploadForm"
              :before-upload="beforeUpload"
              :on-success="handleSuccess"
              :multiple="false"
              :auto-upload="true"
              :show-file-list="false"
              :on-error="handleError"
              list-type="picture"
              class="upload"
            >
              <el-button size="small" type="success">上传图片</el-button>
            </el-upload>
            <span class="image-type">仅支持jpg、jpeg、gif 、png四种格式, 大小为10M以内</span>
            <div class="pagination">
              <el-pagination
                :page-size="pagination.limit"
                :total="pagination.count"
                :current-page.sync="pagination.page_num"
                layout="prev, pager, next, jumper, total"
                @current-change="changeCurrent"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- 图片弹窗 -->
    <div v-show="mediaImage.imageVisible" class="widget-image">
      <div class="shade-image"/>
      <div class="widget-content">
        <img :src="mediaImage.mediaImageUrl">
      </div>
      <div class="widget-close" @click="handleImageClose">
        <i class="widget-icon">X</i>
      </div>
    </div>
  </div>
</template>

<script>
import {
  getImgMediaList,
  modifyImgMedia,
  getQiniuToken,
  imgMediaUpload
} from "service";

import {
  Popover,
  Button,
  Input,
  Upload,
  Pagination,
  Dialog,
  Message
} from "element-ui";

export default {
  components: {
    "el-button": Button,
    "el-popover": Popover,
    "el-input": Input,
    "el-upload": Upload,
    "el-pagination": Pagination,
    "el-dialog": Dialog
  },
  data() {
    return {
      Domain: "http://upload.qiniu.com",
      uploadForm: {
        token: "",
        key: ""
      },
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      pagination: {
        limit: 10,
        page_num: 1,
        count: 0
      },
      operate: {
        renamePopoverArray: [],
        renameValueArray: []
      },
      mediaImage: {
        type: "image",
        disabledFlag: true,
        imageVisible: false,
        mediaRename: false,
        mediaImageUrl: "",
        mediaList: []
      }
    };
  },
  created() {
    this.init();
    this.getImgMediaList();
  },
  methods: {
    async init() {
      try {
        let res = await getQiniuToken(this);
        this.uploadForm.token = res;
      } catch (e) {
        console.log(e);
      }
    },
    handleError() {
      this.setting.loading = false;
    },
    handleImageClose() {
      this.mediaImage.imageVisible = false;
    },
    changeCurrent(currentPage) {
      this.pagination.page_num = currentPage;
      this.getImgMediaList();
    },
    // 获取图片列表
    getImgMediaList() {
      let _this = this;
      let args = {
        page: this.pagination.page_num
      };
      getImgMediaList(this, args)
        .then(res => {
          this.mediaImage.mediaList = res.data;
          this.pagination.count = res.meta.pagination.total;
          this.setModelFlag(this.mediaImage.mediaList);
          this.setting.loading = false;
        })
        .catch(err => {
          _this.setting.loading = false;
          console.log(err);
        });
    },
    // 设置for循环列表中的v-model值
    setModelFlag(dataList) {
      this.operate.renameValueArray = [];
      this.operate.renamePopoverArray = [];
      dataList.map(v => {
        this.operate.renamePopoverArray.push({ flag: false });
        this.operate.renameValueArray.push({ name: v.name });
      });
      this.mediaImage.disabledFlag = true;
    },
    // 处理图片列表中按钮的确定和取消操作的标记
    handleOperationButtonClick(index, modelName, imageId) {
      if (modelName === "rename") {
        this.operate.renameValueArray[index].name = this.mediaImage.mediaList[
          index
        ].name;
        this.operate.renamePopoverArray[index].flag = false;
      }
    },
    // 图片的重命名处理
    mediaRenameHandle(index, imageId) {
      if (this.operate.renameValueArray[index].name.trim()) {
        this.setting.loading = true;
        let name = this.operate.renameValueArray[index].name;
        this.operate.renamePopoverArray[index].flag = false;
        this.modifyMedia(imageId, name);
      } else {
        this.operate.renameValueArray[index].name = this.mediaImage.mediaList[
          index
        ].name;
        this.operate.renamePopoverArray[index].flag = false;
        this.$message({
          message: "请输入要重命名的内容",
          type: "warning"
        });
      }
    },
    // 修改名称
    modifyMedia(id, name) {
      let args = {
        name: name
      };
      modifyImgMedia(this, id, args)
        .then(res => {
          this.getImgMediaList();
        })
        .catch(err => {
          this.setting.loading = false;
          console.log(err);
        });
    },
    beforeUpload(file) {
      this.setting.loading = true;
      let name = file.name;
      let type = name.substring(name.lastIndexOf("."));
      let isLt100M = file.size / 1024 / 1024 < 100;
      let time = new Date().getTime();
      let random = parseInt(Math.random() * 10 + 1, 10);
      let suffix = time + "_" + random + "_" + name;
      let key = encodeURI(`${suffix}`);
      const isJPG =
        file.type === "image/jpg" ||
        file.type === "image/png" ||
        file.type === "image/gif" ||
        file.type === "image/jpeg";
      const isLt10M = file.size / 1024 / 1024 < 10;
      if (!isJPG) {
        this.$message.error("上传图片仅支持jpg、jpeg 、gif、png四种格式!");
        this.setting.loading = false;
        return isJPG;
      }
      if (!isLt10M) {
        this.$message.error("上传图片大小不能超过 10MB!");
        this.setting.loading = false;
        return isLt10M;
      }
      this.uploadForm.key = key;
      return this.uploadForm;
    },
    // 上传成功后的处理
    handleSuccess(response, file) {
      let [key, name, size] = [response.key, file.name, file.size];
      let type = name.substring(name.lastIndexOf("."));
      let params = {
        key: key,
        name: name,
        size: size
      };
      this.imgMediaUpload(params);
    },
    imgMediaUpload(args) {
      imgMediaUpload(this, args)
        .then(res => {
          this.getImgMediaList();
        })
        .catch(err => {});
    }
  }
};
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
        bottom: 49px;
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

.el-popover {
  padding: 10px 15px;
}
.el-popover p {
  font-size: 14px;
  line-height: 20px;
}
.rename-input {
  margin-bottom: 15px;
}
.hint {
  color: #999;
}
.btn-wrap {
  text-align: right;
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

.image-type {
  color: #999;
  font-size: 12px;
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
  img {
    width: 100%;
  }
}
</style>

