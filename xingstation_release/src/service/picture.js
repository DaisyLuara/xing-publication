import { router } from '../main'
const MEDIA_API = '/api/media/media'
const MEDIA_GROUP_API = '/api/media/mediaGroups'
const pictureUseType = 'picture_system'
export default {
  getMediaGroupsList(context, type, picture) {
    let promise = new Promise(function(resolve, reject) {
      context.$http.get(MEDIA_GROUP_API).then((res) => {
        let data = res.data.data
        context.mediaGroup.mediaGroupList = picture.formatMediaGroup(data)
        if (data.length) {
          if (type == pictureUseType) {
            context.mediaGroup.media_group_id = data[0].id
            context.defaultGroupId = data[0].id
            context.mediaGroup.is_ext = context.mediaGroup.mediaGroupList[context.defaultGroupId].is_ext
            context.mediaGroup.renameGroupValue = context.mediaGroup.mediaGroupList[context.defaultGroupId].media_group_name
            context.mediaGroup.groupingRadio = context.defaultGroupId
            context.mediaGroup.groupingAllRadio = context.defaultGroupId
            context.loading = false
            context.getMediaListByGroupId()
          } else {
            context.form.media_group_id = data[0].id
            context.activeTabName = data[0].media_group_name
            context.getMedia(context.form.media_group_id)
          }
        } else {
          context.loading = false
        }
      }).catch(function(err) {
        console.log(err)
      })
    })
    return promise
  },
  formatMediaGroup(data) {
    let groups = {}
    for (let group of data) {
      groups[group.id] = group
    }
    return groups
  },
  beforeUploadImage(context, mediaGroupId, auth, type) {
    context.loading = true
    context.formHeader.Authorization = 'Bearer ' + auth.getToken()
    if (pictureUseType == type) {
      context.mediaGroup.media_group_id = mediaGroupId
    } else {
      context.form.media_group_id = mediaGroupId
    }

  },
  getMediaListById(context, params, type) {
    let promise = new Promise(function(resolve, reject) {
      let _context = context
      if (context.serch.searchFlag) {
        context.searchMedia()
      } else {
        context.$http.get(MEDIA_API, { params: params }).then((res) => {
          context.pagination.count = res.data.page.count
          if (type == pictureUseType) {
            context.mediaGroup.mediaGroupList[context.mediaGroup.media_group_id].media_count = context.pagination.count
            context.mediaImage.mediaList = res.data.data
            context.checkbox.checkedList = []
            context.checkbox.allChecked = false
            context.setModelFlag(res.data.data)
          } else {
            context.mediaGroup.mediaGroupList[context.form.media_group_id].media_count = context.pagination.count
            context.mediaGroup.mediaGroupList[context.form.media_group_id].data = res.data.data
          }
          context.loading = false
        }).catch(function(err) {
          console.log(err)
          _context.loading = false
        })
      }
    })
    return promise
  },
  searchHandle(context, params, type) {
    let promise = new Promise(function(resolve, reject) {
      let _context = context
      context.$http.get(MEDIA_API + '/search', {
        params: params
      }).then((res) => {
        context.pagination.count = res.data.page.count
        context.serch.searchFlag = true
        if (type == pictureUseType) {
          context.checkbox.allChecked = false
          context.mediaImage.mediaList = res.data.data
          context.setModelFlag(res.data.data)
        } else {
          context.searchedMediaList = res.data.data
        }
        context.loading = false
      }).catch(function(err) {
        _context.loading = false
        console.log(err)
      })
    })
    return promise
  }
}