<template>
  <div class="root">
    <div class="item-list-wrap" :element-loading-text="setting.loadingText" v-loading="setting.loading">
      <div class="item-content-wrap">
       <div id="button-wrap">
           
        <el-form :model="filters" :inline="true" ref="searchForm" >
           <el-button  class="active" round v-for="(item,index) in groupData" :id="item.id" @click="say($event)" >{{item.attributes.name=='ACTIVIEW'?'团队成员':item.attributes.name}}</el-button>
          </el-form>
            
        </div>
      <div class="member-wrap">
        <div class="total-wrap">
            <h3 class="label" style="font-size:16px">
            {{gropName}}
            <span style="font-size:10px">共（<b>{{updateDate.length}}</b>）人</span>
            </h3>
        </div>
        <el-table :data="updateDate" style="width: 100%" :show-header="false">
          <el-table-column
            prop="attributes.gavatar"
            label="图标"
            min-width="130"
            >
            <template slot-scope="scope">
              <img :src="scope.row.attributes.gavatar" alt=""  class="icon-item"/> 
            </template>
          </el-table-column>
          <el-table-column
            prop="attributes.nickname"
            label="名称"
            min-width="100"
            >
            <template slot-scope="scope">
              <a>{{scope.row.attributes.nickname}}</a>
              <el-tag type="info" v-for="(item,index) in scope.row.relationships.groups.data ">{{item.id | groupFilters(groupData)}}</el-tag>
            </template>
          </el-table-column>
          <el-table-column
            prop="attributes.phone"
            label="手机号"
            min-width="100"
            >
          </el-table-column>
          <el-table-column
            prop="attributes.mailbox"
            label="邮箱"
            min-width="100"
           >
          </el-table-column>
          <el-table-column
            prop="attributes.comment"
            label="内容"
            min-width="100"
           >
          </el-table-column>
        </el-table>
     </div>
      
      </div>  
    </div>
  </div>
</template>

<script>
 import team from 'service/team'
let th=this;
import { Button, Input, Table, TableColumn, Pagination,Dialog, Form, FormItem, MessageBox, DatePicker, Select, Option, CheckboxGroup, Checkbox,Tag} from 'element-ui'

export default {
  data () {
      return {
      filters: {
        name: ''
      },
      setting: {
        loading: false,
        loadingText: "拼命加载中"
      },
      dataValue: '',
      loading: true,
      arUserName: '',
      dataShowFlag: true,
      pagination: {
        total: 0,
        pageSize: 10,
        currentPage: 1
      },
      tableData: [],
      updateDate:[],
      groupData:[],
      gropName:'团队成员',
      responseDate:[
        {
            "id": "8277540e755348c2bb520f270f661675",
            "type": "members",
            "attributes": {
                "nickname": "包臻卿",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/noon.jpg",
                "role": "visitor",
                "comment": null,
                "mailbox": "baozhenqing@xingshidu.com",
                "phone": null,
                "created_at": "2018-04-26T22:24:47.000+08:00",
                "updated_at": "2018-04-27T16:28:55.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        },
                        {
                            "id": "ff84d99229b7402ebf873f1e9caacfa7",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "74095b5003a44b5086eb43244dca6c41",
            "type": "members",
            "attributes": {
                "nickname": "陈欢",
                "is_active": true,
                "gavatar": "https://avatar.tower.im/5ec5b9082e1e47079a8be1a0af745d10",
                "role": "member",
                "comment": null,
                "mailbox": "1594347785@qq.com",
                "phone": null,
                "created_at": "2018-05-07T13:56:01.000+08:00",
                "updated_at": "2018-05-11T17:15:09.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "6daa556ccf8e4bd4be9cb3a031bc6370",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "ce5fd2f3dc30444c81b2e0d01a0b7594",
            "type": "members",
            "attributes": {
                "nickname": "陈重",
                "is_active": true,
                "gavatar": "https://avatar.tower.im/ab6fff764c624bf19cbf2f3b0a35c694",
                "role": "admin",
                "comment": null,
                "mailbox": "717513736@qq.com",
                "phone": null,
                "created_at": "2018-03-16T20:02:05.000+08:00",
                "updated_at": "2018-05-11T14:33:36.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "1be7af344918447d8ecd87a2445f21ab",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        },
                        {
                            "id": "5f9f69afda9c4e859c2a098ac24d82b5",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "662e364e17844fc595599bb6058e8086",
            "type": "members",
            "attributes": {
                "nickname": "邓永华",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/waves.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "112055749@qq.com",
                "phone": null,
                "created_at": "2018-04-20T16:23:29.000+08:00",
                "updated_at": "2018-05-03T16:53:44.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": []
                }
            }
        },
        {
            "id": "046cd66f3b2a464baa60148c064de571",
            "type": "members",
            "attributes": {
                "nickname": "方圆",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/path.jpg",
                "role": "admin",
                "comment": null,
                "mailbox": "yuanfang@jingfree.com",
                "phone": null,
                "created_at": "2018-03-01T09:23:54.000+08:00",
                "updated_at": "2018-05-11T18:17:45.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "289bc1e3a72f473b830bad449afcb5ee",
                            "type": "subgroups"
                        },
                        {
                            "id": "1be7af344918447d8ecd87a2445f21ab",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "500f49a93fe04303a5eb94207eeb13a7",
            "type": "members",
            "attributes": {
                "nickname": "范佳伟",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/waves.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "308316123@qq.com",
                "phone": null,
                "created_at": "2018-03-01T10:25:28.000+08:00",
                "updated_at": "2018-05-10T18:43:34.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "5f9f69afda9c4e859c2a098ac24d82b5",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "2454d0875df84b9ea47e9b998c9855e6",
            "type": "members",
            "attributes": {
                "nickname": "fanyingzhao",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/winter.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "645743245@qq.com",
                "phone": null,
                "created_at": "2018-03-01T10:38:35.000+08:00",
                "updated_at": "2018-05-08T10:51:43.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "5f9f69afda9c4e859c2a098ac24d82b5",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "90dc4eca08944f6b80dd92fff6747b43",
            "type": "members",
            "attributes": {
                "nickname": "范卓",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/noon.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "fans1992@qq.com",
                "phone": null,
                "created_at": "2018-04-11T10:57:55.000+08:00",
                "updated_at": "2018-05-11T09:28:51.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "5f9f69afda9c4e859c2a098ac24d82b5",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        },
                        {
                            "id": "1be7af344918447d8ecd87a2445f21ab",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "08b33a3e0a7f4ff190bc8c3aaec1390b",
            "type": "members",
            "attributes": {
                "nickname": "高鑫凯",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/waves.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "gaoxinkai@jingfree.com",
                "phone": null,
                "created_at": "2018-04-23T23:15:17.000+08:00",
                "updated_at": "2018-05-10T16:35:23.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        },
                        {
                            "id": "ff84d99229b7402ebf873f1e9caacfa7",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "7a572245e36a4948bffddf0437d35edf",
            "type": "members",
            "attributes": {
                "nickname": "海阔天空",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/winter.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "823157645@qq.com",
                "phone": null,
                "created_at": "2018-04-20T17:03:35.000+08:00",
                "updated_at": "2018-05-08T16:29:03.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        },
                        {
                            "id": "ff84d99229b7402ebf873f1e9caacfa7",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "feaa6b3e3c324b29979c2031db763e33",
            "type": "members",
            "attributes": {
                "nickname": "海育群",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/path.jpg",
                "role": "visitor",
                "comment": null,
                "mailbox": "124177028@qq.com",
                "phone": null,
                "created_at": "2018-04-26T22:25:10.000+08:00",
                "updated_at": "2018-05-03T21:34:41.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        },
                        {
                            "id": "ff84d99229b7402ebf873f1e9caacfa7",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "5a38448109484c108b5916df2de11e99",
            "type": "members",
            "attributes": {
                "nickname": "韩珂",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/cloud.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "hanke32@126.com",
                "phone": null,
                "created_at": "2018-03-01T16:51:41.000+08:00",
                "updated_at": "2018-05-07T14:31:05.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "6daa556ccf8e4bd4be9cb3a031bc6370",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "20728ab8f3ee41e2a089c45091d68533",
            "type": "members",
            "attributes": {
                "nickname": "华嘉琦",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/cloud.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "huajiaqi@jingfree.com",
                "phone": null,
                "created_at": "2018-03-27T10:46:29.000+08:00",
                "updated_at": "2018-04-17T16:13:13.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "1be7af344918447d8ecd87a2445f21ab",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "4c244dac026543a888b47a1c0910572e",
            "type": "members",
            "attributes": {
                "nickname": "黄凤娟",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/waves.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "huangfengjuan@jingfree.com",
                "phone": null,
                "created_at": "2018-03-16T12:00:52.000+08:00",
                "updated_at": "2018-05-11T16:38:16.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        },
                        {
                            "id": "4b55cf5610204dd384108464984331b0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "c853007e37fa4bcf9bbf27ed0f22fab4",
            "type": "members",
            "attributes": {
                "nickname": "黄凯",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/winter.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "huangkai@xingshidu.com",
                "phone": null,
                "created_at": "2018-04-23T10:14:46.000+08:00",
                "updated_at": "2018-05-08T17:48:23.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        },
                        {
                            "id": "ff84d99229b7402ebf873f1e9caacfa7",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "bb90286415d6442395f76cfe02215268",
            "type": "members",
            "attributes": {
                "nickname": "黄一",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/nightfall.jpg",
                "role": "admin",
                "comment": null,
                "mailbox": "95487710@qq.com",
                "phone": null,
                "created_at": "2018-02-28T18:46:39.000+08:00",
                "updated_at": "2018-05-08T16:26:54.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "5f9f69afda9c4e859c2a098ac24d82b5",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "53f4d0434b3b46e8a53bf317a3c79fe0",
            "type": "members",
            "attributes": {
                "nickname": "黄周维",
                "is_active": true,
                "gavatar": "https://avatar.tower.im/a06f43dfb30c4664847055cae4778090",
                "role": "visitor",
                "comment": null,
                "mailbox": "axmumu@163.com",
                "phone": null,
                "created_at": "2018-04-26T21:32:59.000+08:00",
                "updated_at": "2018-05-09T11:00:25.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        },
                        {
                            "id": "ff84d99229b7402ebf873f1e9caacfa7",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "22e5e5c319dc4148bf5856f8bf5ccb21",
            "type": "members",
            "attributes": {
                "nickname": "胡联洋",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/cloud.jpg",
                "role": "visitor",
                "comment": null,
                "mailbox": "hulianyang@xingshidu.com",
                "phone": null,
                "created_at": "2018-04-27T09:26:37.000+08:00",
                "updated_at": "2018-04-27T09:26:37.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        },
                        {
                            "id": "ff84d99229b7402ebf873f1e9caacfa7",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "1ba2e2b0c4c64fc7913c3df46b729ec1",
            "type": "members",
            "attributes": {
                "nickname": "贾琛琛",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/jokul.jpg",
                "role": "visitor",
                "comment": null,
                "mailbox": "jonec117@163.com",
                "phone": null,
                "created_at": "2018-04-26T21:24:28.000+08:00",
                "updated_at": "2018-05-08T16:27:26.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        },
                        {
                            "id": "ff84d99229b7402ebf873f1e9caacfa7",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "2c0e0a52b329487199cff36f5953cd72",
            "type": "members",
            "attributes": {
                "nickname": "金妙玲",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/jokul.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "879395846@qq.com",
                "phone": null,
                "created_at": "2018-03-01T16:59:17.000+08:00",
                "updated_at": "2018-05-07T14:10:21.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "6daa556ccf8e4bd4be9cb3a031bc6370",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "a844dca4543445a3a4114e5bf5f8c76a",
            "type": "members",
            "attributes": {
                "nickname": "姬睿",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/nightfall.jpg",
                "role": "admin",
                "comment": null,
                "mailbox": "blade-kindjal@163.com",
                "phone": null,
                "created_at": "2018-03-02T17:48:38.000+08:00",
                "updated_at": "2018-05-08T16:32:40.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "76cddc18e3b24f08999a2330a010ee2b",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "b4e6ebdaf78e4fae88f1449b301cacf1",
            "type": "members",
            "attributes": {
                "nickname": "Kuncci",
                "is_active": true,
                "gavatar": "https://avatar.tower.im/78fdaac5f924439ba17c7e5864d1480c",
                "role": "member",
                "comment": null,
                "mailbox": "kuncci_yang@163.com",
                "phone": null,
                "created_at": "2018-03-01T13:44:46.000+08:00",
                "updated_at": "2018-05-09T18:39:58.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "6daa556ccf8e4bd4be9cb3a031bc6370",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "f956c32259d5447e8a7df0633135164a",
            "type": "members",
            "attributes": {
                "nickname": "李盼",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/jokul.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "1481795291@qq.com",
                "phone": null,
                "created_at": "2018-03-01T11:21:48.000+08:00",
                "updated_at": "2018-05-11T17:36:21.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "5f9f69afda9c4e859c2a098ac24d82b5",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "34ed4f27db3e4473a1b53e71884b4385",
            "type": "members",
            "attributes": {
                "nickname": "李乾铭",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/waves.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "1468151354@qq.com",
                "phone": null,
                "created_at": "2018-05-08T16:30:40.000+08:00",
                "updated_at": "2018-05-08T16:31:06.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "6daa556ccf8e4bd4be9cb3a031bc6370",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "3573f15b96d5423a8881a3a9c6fc20d1",
            "type": "members",
            "attributes": {
                "nickname": "李秋阳",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/cloud.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "lewind@qq.com",
                "phone": null,
                "created_at": "2018-03-01T10:25:27.000+08:00",
                "updated_at": "2018-05-11T11:39:23.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "5f9f69afda9c4e859c2a098ac24d82b5",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "1763a5753fa44066bdad179493bb8a54",
            "type": "members",
            "attributes": {
                "nickname": "刘敏",
                "is_active": true,
                "gavatar": "https://avatar.tower.im/b2dcd5ce74ab4cad8e73c551d7cafc6d",
                "role": "member",
                "comment": null,
                "mailbox": "471736824@qq.com",
                "phone": null,
                "created_at": "2018-03-01T11:21:48.000+08:00",
                "updated_at": "2018-05-11T15:38:30.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "6daa556ccf8e4bd4be9cb3a031bc6370",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "a64b5960bd754c72a4654f56c9ed9b95",
            "type": "members",
            "attributes": {
                "nickname": "陆柳",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/path.jpg",
                "role": "visitor",
                "comment": null,
                "mailbox": "luliu@xongshidu.com",
                "phone": null,
                "created_at": "2018-04-26T20:18:21.000+08:00",
                "updated_at": "2018-05-09T15:39:57.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        },
                        {
                            "id": "ff84d99229b7402ebf873f1e9caacfa7",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "671471cb2280404eb8304bb15c9c3811",
            "type": "members",
            "attributes": {
                "nickname": "倪一旻",
                "is_active": true,
                "gavatar": "https://avatar.tower.im/64f8afa5d90a413093221106b3333e93",
                "role": "member",
                "comment": null,
                "mailbox": "leoniconcept@gmail.com",
                "phone": null,
                "created_at": "2018-05-08T16:37:44.000+08:00",
                "updated_at": "2018-05-10T09:36:55.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "76cddc18e3b24f08999a2330a010ee2b",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "2411b9d3298f4bd6aaeb3fab3cbac62f",
            "type": "members",
            "attributes": {
                "nickname": "钱雅婷",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/path.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "632596138@qq.com",
                "phone": null,
                "created_at": "2018-04-04T11:27:28.000+08:00",
                "updated_at": "2018-05-11T09:52:45.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "6daa556ccf8e4bd4be9cb3a031bc6370",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "ec7abe3146bb4f11bc4c01cb38220444",
            "type": "members",
            "attributes": {
                "nickname": "秦洁莲",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/nightfall.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "qinjielian@jingfree.com",
                "phone": null,
                "created_at": "2018-03-21T18:15:10.000+08:00",
                "updated_at": "2018-05-11T16:04:50.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        },
                        {
                            "id": "53ca8be409084b0bbe2a886afafaf086",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "4ba097096e444d78b213a65d220c55f1",
            "type": "members",
            "attributes": {
                "nickname": "全孟操",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/noon.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "quanmengcao@xingstation.com",
                "phone": null,
                "created_at": "2018-04-21T18:31:47.000+08:00",
                "updated_at": "2018-04-26T21:23:42.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        },
                        {
                            "id": "ff84d99229b7402ebf873f1e9caacfa7",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "eb0e90d28c6f47c39bd2278ad716d222",
            "type": "members",
            "attributes": {
                "nickname": "任亚捷",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/nightfall.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "312349632@qq.com",
                "phone": null,
                "created_at": "2018-03-05T16:15:50.000+08:00",
                "updated_at": "2018-05-11T10:42:05.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "6daa556ccf8e4bd4be9cb3a031bc6370",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "18f951202ceb4f2489b9c45874a90555",
            "type": "members",
            "attributes": {
                "nickname": "时运",
                "is_active": true,
                "gavatar": "https://avatar.tower.im/eb558956465a4619afa8fa21b5a150ff",
                "role": "admin",
                "comment": null,
                "mailbox": "syun1024@126.com",
                "phone": null,
                "created_at": "2018-03-01T17:06:51.000+08:00",
                "updated_at": "2018-05-11T15:41:20.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "76cddc18e3b24f08999a2330a010ee2b",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "7ca240394a9b477280a611e22f733222",
            "type": "members",
            "attributes": {
                "nickname": "舒俊",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/jokul.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "349020183@qq.com",
                "phone": null,
                "created_at": "2018-04-20T14:47:53.000+08:00",
                "updated_at": "2018-05-08T13:59:05.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        },
                        {
                            "id": "6daa556ccf8e4bd4be9cb3a031bc6370",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "6b7d16f159974cdb940717dcd874722b",
            "type": "members",
            "attributes": {
                "nickname": "宋文娟",
                "is_active": true,
                "gavatar": "https://avatar.tower.im/779091f522b340eaaf8904ff6d44ed3b",
                "role": "owner",
                "comment": null,
                "mailbox": "songwenjuan@jingfree.com",
                "phone": null,
                "created_at": "2018-02-27T17:36:50.000+08:00",
                "updated_at": "2018-05-11T09:53:56.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        },
                        {
                            "id": "4b55cf5610204dd384108464984331b0",
                            "type": "subgroups"
                        },
                        {
                            "id": "1be7af344918447d8ecd87a2445f21ab",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "06117509e1e54ddeb1809d2db61c9eff",
            "type": "members",
            "attributes": {
                "nickname": "sora ",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/noon.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "403700533@qq.com",
                "phone": null,
                "created_at": "2018-03-01T11:21:49.000+08:00",
                "updated_at": "2018-05-11T09:49:11.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "6daa556ccf8e4bd4be9cb3a031bc6370",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "b91c04d896ad4c90a47778dcdf7993ee",
            "type": "members",
            "attributes": {
                "nickname": "Taki",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/nightfall.jpg",
                "role": "visitor",
                "comment": null,
                "mailbox": "shaojia@xingstation.com",
                "phone": null,
                "created_at": "2018-04-26T21:25:25.000+08:00",
                "updated_at": "2018-05-08T16:28:26.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        },
                        {
                            "id": "ff84d99229b7402ebf873f1e9caacfa7",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "4948a2752a4343509176c319a0477849",
            "type": "members",
            "attributes": {
                "nickname": "王铎",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/jokul.jpg",
                "role": "admin",
                "comment": null,
                "mailbox": "wangduo@jingfree.com",
                "phone": null,
                "created_at": "2018-03-05T16:40:44.000+08:00",
                "updated_at": "2018-05-11T11:48:56.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "76cddc18e3b24f08999a2330a010ee2b",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "8dd3dbf4681d410fa6922c84aa084337",
            "type": "members",
            "attributes": {
                "nickname": "王婷",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/cloud.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "417966243@qq.com",
                "phone": null,
                "created_at": "2018-03-01T11:21:48.000+08:00",
                "updated_at": "2018-05-11T20:48:47.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "6daa556ccf8e4bd4be9cb3a031bc6370",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "e9ee3d54cb9a477ca11facd846ffd1f7",
            "type": "members",
            "attributes": {
                "nickname": "王翔",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/cloud.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "joywangx@sina.com",
                "phone": null,
                "created_at": "2018-03-01T10:44:12.000+08:00",
                "updated_at": "2018-05-11T10:13:53.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "5f9f69afda9c4e859c2a098ac24d82b5",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "3645abd659734fa08e8eccab20e64663",
            "type": "members",
            "attributes": {
                "nickname": "威廉",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/noon.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "saintzhg@163.com",
                "phone": null,
                "created_at": "2018-04-23T15:48:10.000+08:00",
                "updated_at": "2018-05-11T09:57:26.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "6daa556ccf8e4bd4be9cb3a031bc6370",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "4b5517342f0844c99d75d99f8dc999ea",
            "type": "members",
            "attributes": {
                "nickname": "吴杰",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/path.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "569788264@qq.com",
                "phone": null,
                "created_at": "2018-05-08T16:28:18.000+08:00",
                "updated_at": "2018-05-08T16:28:44.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "76cddc18e3b24f08999a2330a010ee2b",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "bf7814234be74353a824d462a146ab61",
            "type": "members",
            "attributes": {
                "nickname": "夏冬平",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/noon.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "xiadongping@huiti.com",
                "phone": null,
                "created_at": "2018-04-23T22:51:16.000+08:00",
                "updated_at": "2018-05-04T17:47:58.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        },
                        {
                            "id": "ff84d99229b7402ebf873f1e9caacfa7",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "a8670efc9f614474ac6db83cfa7325fc",
            "type": "members",
            "attributes": {
                "nickname": "熊俊",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/winter.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "xiongjun@jingfree.com",
                "phone": null,
                "created_at": "2018-04-02T09:50:54.000+08:00",
                "updated_at": "2018-05-08T10:59:29.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": []
                }
            }
        },
        {
            "id": "b4564cc082ff4cd59109ed211c0cf146",
            "type": "members",
            "attributes": {
                "nickname": "xubaolun",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/jokul.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "644893183@qq.com",
                "phone": null,
                "created_at": "2018-03-01T10:51:19.000+08:00",
                "updated_at": "2018-05-07T12:15:14.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "5f9f69afda9c4e859c2a098ac24d82b5",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "b54d2ebd7b8e4712a0dcbd5b970bcdd3",
            "type": "members",
            "attributes": {
                "nickname": "徐本纯",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/waves.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "xubenchun@xingshidu.com",
                "phone": null,
                "created_at": "2018-04-20T16:40:27.000+08:00",
                "updated_at": "2018-04-23T10:42:06.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        },
                        {
                            "id": "ff84d99229b7402ebf873f1e9caacfa7",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "d578bbe5547e46b9a74403895785a913",
            "type": "members",
            "attributes": {
                "nickname": "杨建",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/waves.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "1552457395@qq.com",
                "phone": null,
                "created_at": "2018-03-01T10:25:27.000+08:00",
                "updated_at": "2018-05-08T16:36:08.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "5f9f69afda9c4e859c2a098ac24d82b5",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "c5c069b98e8a46709c0504ffe048054b",
            "type": "members",
            "attributes": {
                "nickname": "杨凯帆",
                "is_active": true,
                "gavatar": "https://avatar.tower.im/b17df0410bfa49c3a55267471e3c6db3",
                "role": "admin",
                "comment": null,
                "mailbox": "m13681971646@163.com",
                "phone": null,
                "created_at": "2018-02-28T21:53:46.000+08:00",
                "updated_at": "2018-05-11T09:52:41.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "76cddc18e3b24f08999a2330a010ee2b",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "9a2e5e19d6d8494f977e01f858994bb3",
            "type": "members",
            "attributes": {
                "nickname": "仰强",
                "is_active": true,
                "gavatar": "https://avatar.tower.im/c1761908bfe740d89e75ebaa241cf396",
                "role": "member",
                "comment": null,
                "mailbox": "920176893@qq.com",
                "phone": null,
                "created_at": "2018-05-10T11:18:57.000+08:00",
                "updated_at": "2018-05-11T09:17:45.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": []
                }
            }
        },
        {
            "id": "5691e10bf18c4d0bb94a8743bc964d3c",
            "type": "members",
            "attributes": {
                "nickname": "杨昀",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/noon.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "y781177469@163.com",
                "phone": null,
                "created_at": "2018-04-16T11:18:40.000+08:00",
                "updated_at": "2018-05-11T13:40:16.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "289bc1e3a72f473b830bad449afcb5ee",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "fbde31bb3b464fab8533c5b1372e40e3",
            "type": "members",
            "attributes": {
                "nickname": "严乐鹏",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/path.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "yanyuepeng@xingshidu.com",
                "phone": null,
                "created_at": "2018-04-20T16:41:32.000+08:00",
                "updated_at": "2018-05-10T02:42:45.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        },
                        {
                            "id": "ff84d99229b7402ebf873f1e9caacfa7",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "d4e85961364148de903950a9d6a6b29f",
            "type": "members",
            "attributes": {
                "nickname": "叶国强",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/jokul.jpg",
                "role": "admin",
                "comment": null,
                "mailbox": "263593188@qq.com",
                "phone": null,
                "created_at": "2018-03-01T12:31:45.000+08:00",
                "updated_at": "2018-05-11T14:35:04.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "76cddc18e3b24f08999a2330a010ee2b",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "f4d03b0050aa4bb78a4c9d32bd52c0e1",
            "type": "members",
            "attributes": {
                "nickname": "叶小静",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/jokul.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "1003668649@qq.com",
                "phone": null,
                "created_at": "2018-03-01T10:25:27.000+08:00",
                "updated_at": "2018-05-08T16:29:17.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "5f9f69afda9c4e859c2a098ac24d82b5",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "111d27740215410cb9255e4d36fe2edd",
            "type": "members",
            "attributes": {
                "nickname": "尤鎏峰",
                "is_active": true,
                "gavatar": "https://avatar.tower.im/d71d8f3bfaa54a4cad885776b8d092cd",
                "role": "member",
                "comment": null,
                "mailbox": "248189463@qq.com",
                "phone": null,
                "created_at": "2018-04-11T20:41:40.000+08:00",
                "updated_at": "2018-05-09T18:44:44.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "2e8b0bf138c14d11835c74c5a69e8fe8",
            "type": "members",
            "attributes": {
                "nickname": "张骥",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/path.jpg",
                "role": "visitor",
                "comment": null,
                "mailbox": "edioe@vip.qq.com",
                "phone": null,
                "created_at": "2018-04-27T02:21:25.000+08:00",
                "updated_at": "2018-04-27T16:25:48.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "ff84d99229b7402ebf873f1e9caacfa7",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "1a6b0bfdf42241e1b3e4ee74b4311209",
            "type": "members",
            "attributes": {
                "nickname": "张京京",
                "is_active": true,
                "gavatar": "https://avatar.tower.im/fa1910e0fa874b1296a0ca65e234abad",
                "role": "member",
                "comment": null,
                "mailbox": "1765559690@qq.com",
                "phone": null,
                "created_at": "2018-03-01T10:44:11.000+08:00",
                "updated_at": "2018-04-23T09:43:38.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "5f9f69afda9c4e859c2a098ac24d82b5",
                            "type": "subgroups"
                        },
                        {
                            "id": "1be7af344918447d8ecd87a2445f21ab",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "9d089cd04a2c43f89646760cc73a07cb",
            "type": "members",
            "attributes": {
                "nickname": "张卡比",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/waves.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "youlingztz@163.com",
                "phone": null,
                "created_at": "2018-03-26T15:33:52.000+08:00",
                "updated_at": "2018-05-08T16:50:54.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "1be7af344918447d8ecd87a2445f21ab",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "17e1f98cee1c4af0859431fe4eb7e394",
            "type": "members",
            "attributes": {
                "nickname": "张钊",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/waves.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "187372605@qq.com",
                "phone": null,
                "created_at": "2018-03-05T12:40:01.000+08:00",
                "updated_at": "2018-05-10T01:08:14.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "6daa556ccf8e4bd4be9cb3a031bc6370",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "3c37b11628df4b7996222205676cc8a9",
            "type": "members",
            "attributes": {
                "nickname": "赵俊",
                "is_active": true,
                "gavatar": "https://avatar.tower.im/2701b7ab783d4616b4637c0cac2cd4ea",
                "role": "member",
                "comment": null,
                "mailbox": "synac1012@aliyun.com",
                "phone": null,
                "created_at": "2018-03-01T10:44:11.000+08:00",
                "updated_at": "2018-05-11T08:51:45.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "5f9f69afda9c4e859c2a098ac24d82b5",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "d55f680cc0f942238a63c307a1497b57",
            "type": "members",
            "attributes": {
                "nickname": "赵小兜",
                "is_active": true,
                "gavatar": "https://avatar.tower.im/782e724e8d09457f8f9516309771f916",
                "role": "member",
                "comment": null,
                "mailbox": "454168779@qq.com",
                "phone": null,
                "created_at": "2018-03-26T15:35:14.000+08:00",
                "updated_at": "2018-04-27T10:13:38.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "1be7af344918447d8ecd87a2445f21ab",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "a8a5cf19d9334dc19c450938d55dd87a",
            "type": "members",
            "attributes": {
                "nickname": "赵正好",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/nightfall.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "2932472154@qq.com",
                "phone": null,
                "created_at": "2018-03-01T10:51:18.000+08:00",
                "updated_at": "2018-05-11T09:52:23.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "5f9f69afda9c4e859c2a098ac24d82b5",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "75b91718b6e64a7f971d2ec7effe4d7a",
            "type": "members",
            "attributes": {
                "nickname": "郑问鼎",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/cloud.jpg",
                "role": "member",
                "comment": null,
                "mailbox": "aforanluis@gmail.com",
                "phone": null,
                "created_at": "2018-03-01T12:37:17.000+08:00",
                "updated_at": "2018-05-11T09:43:32.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "5f9f69afda9c4e859c2a098ac24d82b5",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "29e9a3a2a9774c87ba8dace9c292b8dd",
            "type": "members",
            "attributes": {
                "nickname": "周虎",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/cloud.jpg",
                "role": "visitor",
                "comment": null,
                "mailbox": "zhouhu@xingshidu.com",
                "phone": null,
                "created_at": "2018-04-27T18:08:00.000+08:00",
                "updated_at": "2018-05-09T09:32:33.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        },
                        {
                            "id": "ff84d99229b7402ebf873f1e9caacfa7",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "d167adecf44842ba89b698181114fd37",
            "type": "members",
            "attributes": {
                "nickname": "朱建飞",
                "is_active": true,
                "gavatar": "https://tower.im/assets/default_avatars/nightfall.jpg",
                "role": "visitor",
                "comment": null,
                "mailbox": "1044065911@qq.com",
                "phone": null,
                "created_at": "2018-04-26T21:32:18.000+08:00",
                "updated_at": "2018-04-26T21:32:18.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        },
                        {
                            "id": "ff84d99229b7402ebf873f1e9caacfa7",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        },
        {
            "id": "b6d9a14758064715a268e51b608ce697",
            "type": "members",
            "attributes": {
                "nickname": "宗坚",
                "is_active": true,
                "gavatar": "https://avatar.tower.im/3c642efd7f504cc0944cc3d58110ca55",
                "role": "admin",
                "comment": null,
                "mailbox": "497793053@qq.com",
                "phone": null,
                "created_at": "2018-02-28T21:56:42.000+08:00",
                "updated_at": "2018-05-11T16:24:56.000+08:00"
            },
            "relationships": {
                "team": {
                    "data": {
                        "id": "c6dc912c2f494e7ea73bed4488bb3493",
                        "type": "teams"
                    }
                },
                "groups": {
                    "data": [
                        {
                            "id": "76cddc18e3b24f08999a2330a010ee2b",
                            "type": "subgroups"
                        },
                        {
                            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
                            "type": "subgroups"
                        }
                    ]
                }
            }
        }
    ],
    responseIncluded: [
        {
            "id": "c6dc912c2f494e7ea73bed4488bb3493",
            "type": "teams",
            "attributes": {
                "name": "ACTIVIEW",
                "created_at": "2018-02-27T17:36:50.000+08:00",
                "updated_at": "2018-05-11T00:11:37.000+08:00"
            }
        },
        {
            "id": "1f49e54bcf1b42c49cff222894dd2bc0",
            "type": "subgroups",
            "attributes": {
                "name": "星视度智造"
            }
        },
        {
            "id": "ff84d99229b7402ebf873f1e9caacfa7",
            "type": "subgroups",
            "attributes": {
                "name": "业务"
            }
        },
        {
            "id": "6daa556ccf8e4bd4be9cb3a031bc6370",
            "type": "subgroups",
            "attributes": {
                "name": "UI设计"
            }
        },
        {
            "id": "1be7af344918447d8ecd87a2445f21ab",
            "type": "subgroups",
            "attributes": {
                "name": "颜镜店"
            }
        },
        {
            "id": "5f9f69afda9c4e859c2a098ac24d82b5",
            "type": "subgroups",
            "attributes": {
                "name": "开发"
            }
        },
        {
            "id": "289bc1e3a72f473b830bad449afcb5ee",
            "type": "subgroups",
            "attributes": {
                "name": "测试"
            }
        },
        {
            "id": "4b55cf5610204dd384108464984331b0",
            "type": "subgroups",
            "attributes": {
                "name": "知识产权"
            }
        },
        {
            "id": "76cddc18e3b24f08999a2330a010ee2b",
            "type": "subgroups",
            "attributes": {
                "name": "产品经理"
            }
        },
        {
            "id": "53ca8be409084b0bbe2a886afafaf086",
            "type": "subgroups",
            "attributes": {
                "name": "平台运营"
            }
        }
    ]
    }
  },
  mounted() {
  },
  created () {
    this.getTowerList();
    //alert(document.getElementById("button-wrap").offsetWidth);
    
    // this.getProjectListDetails();
    // let user_info = JSON.parse(localStorage.getItem('user_info'))
    // this.arUserName = user_info.name
    // this.dataShowFlag = user_info.roles.data[0].name === 'legal-affairs' ? false : true
    
  },
  methods: {
    say(event)
    {
      //获取当前元素id
      var el = event.currentTarget;
      console.log(this.groupData);
		   alert("当前对象的内容："+el.innerHTML);
       var id='ff84d99229b7402ebf873f1e9caacfa7';
       this.updateDate=new Array();
       for(var i=0;i<this.groupData.length;i++)
       {
        if(id===this.groupData[i].id)
        {
          this.gropName=this.groupData[i].attributes.name;
          break;
        }
       }
       //获取对应分组数据
       for(var i=0;i<this.tableData.length;i++)
       {
        for(var j=0;j<this.tableData[i].relationships.groups.data.length;j++)
        {
        if(id==this.tableData[i].relationships.groups.data[j].id)
        {
           this.updateDate.push(this.tableData[i]);
           break;
        }
        }
       }
       
    },
   getTowerList () {
      this.setting.loadingText = "拼命加载中"
      this.setting.loading = true;
      let searchArgs = {}
      // team.getTowerList(this, searchArgs).then((response) => {
      //  let data = response.data
      //  this.tableData = data
      //  console.log(response);
      //  this.setting.loading = false;
      // }).catch(error => {
      //   console.log(error)
      // this.setting.loading = false;
      // })
     
      this.tableData=this.responseDate;
      this.updateDate=this.responseDate;
      this.groupData=this.responseIncluded;
      this.setting.loading = false;
    }
  },
  filters:{
    groupFilters:function (arg,datas) {
     for(var i=0;i<datas.length;i++){
      if(arg==datas[i].id){
         return datas[i].attributes.name;
      }
     }
        return "";
    }
    },
  components: {
    "el-table": Table,
    "el-date-picker": DatePicker,
    "el-table-column":  TableColumn,
    "el-button": Button,
    "el-input": Input,
    "el-pagination": Pagination,
    "el-form": Form,
    "el-form-item": FormItem,
    'el-select': Select,
    'el-option': Option,
    'el-checkbox-group': CheckboxGroup,
    'el-checkbox': Checkbox,
    'el-dialog':Dialog,
    'el-tag':Tag
  }
}
</script>

<style lang="less" scoped>
  .root {
    font-size: 14px;
    color: #5E6D82;
    .item-list-wrap{
      background: #fff;
      padding: 30px;
      .el-form-item{
        margin-bottom: 0;
      }
      .item-content-wrap{
        .demo-table-expand {
        font-size: 0;
        }
        .demo-table-expand label {
          width: 90px;
          color: #99a9bf;
        }
        .demo-table-expand .el-form-item {
          margin-right: 0;
          margin-bottom: 0;
          width: 50%;
        }
        .icon-item{
          padding: 10px;
          width: 50%;
        }
        #button-wrap{
          margin-top: 5px;
          display: flex;
          flex-direction: row;
          justify-content: space-between;
          font-size: 16px;
          align-items: center;
          margin-bottom: 10px;
          .el-form-item{
            margin-bottom: 0;
          }
          .el-button{
            margin-top:5px;
            margin-left:0;
          }
          .active{
            background:#72b7e8;
          } 
        }
        .total-wrap{
          margin-top: 5px;
          display: flex;
          flex-direction: row;
          justify-content: space-between;
          font-size: 16px;
          align-items: center;
          margin-bottom: 10px;
          .label {
            font-size: 14px;
            margin:5px 0;
          }
        }
        .pagination-wrap{
          margin: 10px auto;
          text-align: right;
        }
      }
    }
  }
</style>
