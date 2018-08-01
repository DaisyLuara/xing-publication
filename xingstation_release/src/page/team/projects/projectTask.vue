<template>
  <div 
    class="root">
    <div  
      v-loading="setting.loading"
      :element-loading-text="setting.loadingText"  
      class="item-list-wrap">
      <div 
        class="item-content-wrap">
        <el-card 
          class="box-card">
          <!-- 标题 -->
          <div
            class="search-wrap">
            <h3>
              {{ filters.name }}
            </h3>
            <hr>
          </div>
          <div 
            class="tit">
            <h2 
              style="padding:0 0 6px 5px;font-weight:700">任务 </h2>
          </div>
          <!-- 需求池 -->
          <div 
            class="task-wrap">             
            <div 
              v-for="(item,index) in newTodolists" 
              :key="index"
              class="need-wrap" 
            >
              <div 
                class="top">
                <div>
                  <el-button 
                    size="small" 
                    class="item">{{ item.todos.length }}</el-button>
                  <span 
                    class="name">{{ item.attributes.name }}</span>
                </div>
                <div 
                  class="icon-right">
                  <i 
                    class="el-icon-circle-plus-outline"
                    @click="addDiv(item.id)"/>
                  <i 
                    class="el-icon-tickets"/>
                </div>
              </div>
              <ul 
                class="list" >
                <li
                  v-for="(element,index) in item.todos" 
                  :key="index"
                  class="dragArea" >
                  <div 
                    class="dragArea-top">
                    <el-checkbox 
                      label=""  
                      class="lable"
                      @change="completion(item.id,element,index)"/>
                    <span 
                      v-if="element.contentShow"
                      refs="text"
                      class="text"  
                      style="white-space: pre-wrap;"  
                      @click="goTaskDetail(element.id,ID)" 
                    >{{ element.attributes.content }}</span>
                    <textarea 
                      v-if="!element.contentShow"
                      v-model="element.attributes.content"  
                      refs="text" 
                      class="text"
                    />
                  </div>
                  <p>
                    <span 
                      class="member"                      
                      @click="comfirmMember(element.relationships.assignee.data,element.attributes.due_at,item.id,element.id,index)">
                      {{ (element.relationships.assignee.data !=='' && element.relationships.assignee.data !== null && element.relationships.assignee.data !== undefined) ? element.relationships.assignee.data.id : '' | getMemberName(members) }} {{ element.attributes.due_at !== null ? element.attributes.due_at.split('T')[0] : '' }}
                    </span>
                    <i 
                      class="el-icon-edit"
                      style="float:right;" 
                      @click="modify(item.id,element.id)"/>
                    <i 
                      class="el-icon-delete"
                      style="float:right;margin-right:15px"  
                      @click="deleteTodos(item.id,element.id,index)"/>
                  </p>
                  <p 
                    v-if="element.show">
                    <a 
                      class="save" 
                      @click="comfirm(item.id,element,index)">确认</a >
                    <a 
                      class="cancle"
                      @click="cancleModify(item.id,element.id)">取消</a>
                  </p>
                </li>
                <div 
                  class=" list add-list">
                  <form>
                    <div  
                      v-for="(element,index) in item.NewDiv" 
                      :key="index"
                      class="dragArea" >
                      <div 
                        class="dragArea-top">
                        <el-checkbox 
                          label="" 
                          class="lable"/>
                        <textarea 
                          v-model="element.content" 
                          refs="text"
                          placeholder="新建任务"
                          class="text"/>
                      </div>
                      <p>
                        <span 
                          class="member"
                          @click="comfirmMember(null,'',item.id,'',index)" >
                          {{ (element.assignee_id !== '' && element.assignee_id !== null && element.assignee_id) ? element.assignee_id : '' | getMemberName(members) }} {{ (element.due_at !== null && element.due_at !== '') ? element.due_at : ''| getDateTime() }}
                        </span>
                      </p>
                      <p>
                        <a 
                          class="save"
                          @click="save(item.id,element,index)">添加任务</a >
                        <a 
                          class="cancle" 
                          @click="cancle(item.id,index)">取消</a>
                      </p>
                    </div>
                  </form>
                </div>
              </ul>
              <div 
                v-if="item.todos.length === 0 && item.NewDiv.legth === 0"
                class="zanwushuju" 
              >{{ notData }}</div>
            </div> 
            <div 
              v-if="newTodolists.length===0"
              class="no-data" 
            >{{ notDataContent }}</div>
          </div>
        </el-card>
        <!-- <弹出层>1 -->
        <el-dialog  
          :visible.sync="dialogFormVisible">
          <el-form 
            :model="form">
            <el-form-item 
              :label-width="formLabelWidth"
              label="指派任务人" >
              <el-select 
                v-model="todos_assignment.todos_assignment.assignee_id" 
                placeholder="请选择">
                <el-option
                  v-for="(item,index) in members"
                  :key="index"
                  :label="item.attributes.nickname"
                  :value="item.id"/>
              </el-select>
            </el-form-item>
            <el-form-item 
              :label-width="formLabelWidth"
              label="任务截止时间" >
              <el-date-picker
                v-model="todos_due.todos_due.due_at"
                type="date"
                placeholder="选择日期"/>
            </el-form-item>
          </el-form>
          <div 
            slot="footer" 
            class="dialog-footer">
            <el-button 
              @click="cancleAssignMember()">取 消</el-button>
            <el-button 
              type="primary" 
              @click="assignMember()">确 定</el-button>
          </div>
        </el-dialog>
      </div>  
    </div>
  </div>
</template>

<script>
import team from 'service/team'
import auth from 'service/auth'
import {
  Button,
  Input,
  Table,
  Row,
  Col,
  TableColumn,
  Form,
  FormItem,
  MessageBox,
  Card,
  CheckboxGroup,
  Checkbox,
  Select,
  Option,
  DatePicker,
  Dialog
} from 'element-ui'
export default {
  filters: {
    //获取成员名称
    getMemberName: function(arg, members) {
      if (arg === '') {
        return '未指派'
      }
      for (var i = 0; i < members.length; i++) {
        if (arg === members[i].id) {
          return members[i].attributes.nickname
        }
      }
      return '未指派'
    },
    //获取年月日
    getDateTime: function(dateTime) {
      if (dateTime === '') {
        return ''
      }
      var dateTimes = new Date(dateTime)
      var year = dateTimes.getFullYear()
      var month =
        dateTimes.getMonth() + 1 < 10
          ? '0' + (dateTimes.getMonth() + 1)
          : dateTimes.getMonth() + 1
      var day =
        dateTimes.getDate() < 10
          ? '0' + dateTimes.getDate()
          : dateTimes.getDate()
      var dateStr = year + '-' + month + '-' + day
      return dateStr
    }
  },
  components: {
    'el-table': Table,
    'el-table-column': TableColumn,
    'el-button': Button,
    'el-input': Input,
    'el-form': Form,
    'el-form-item': FormItem,
    'el-card': Card,
    'el-row': Row,
    'el-col': Col,
    'el-checkbox-group': CheckboxGroup,
    'el-checkbox': Checkbox,
    'el-select': Select,
    'el-option': Option,
    'el-date-picker': DatePicker,
    'el-dialog': Dialog
  },
  data() {
    return {
      dialogTableVisible: false,
      dialogFormVisible: false,
      formLabelWidth: '110px',
      form: {},
      members: [],
      todoID: '',
      todolistID: '',
      memberID: '',
      due_at: '',
      todos_due: { todos_due: { due_at: '' } },
      todo: { todo: { content: '', desc: '', assignee_id: '', due_at: '' } },
      todos_assignment: { todos_assignment: { assignee_id: '' } },
      filters: {
        name: ''
      },
      index: 0,
      notDataContent: '',
      notData: '暂无任务',
      ID: '',
      SERVER_URL: process.env.SERVER_URL,
      completed: true,
      setting: {
        loading: false,
        loadingText: '拼命加载中'
      },
      //dataValue: '',
      loading: true,
      //arUserName: '',
      dataShowFlag: true,
      emptyText: '暂无数据',
      todos: [],
      todolists: [],
      newTodolists: []
    }
  },
  created() {
    this.todolists = []
    this.ID = this.$route.query.id
    this.filters.name = this.$route.query.name
    auth
      .refreshUserInfo(this)
      .then(res => {
        console.log(res)
        this.getTodos()
        this.getProjectMembers()
      })
      .catch(err => {
        console.log(err)
      })
  },
  methods: {
    goTaskDetail(id, ID) {
      this.$router.push({
        path: '/team/projects/dt',
        query: { id: id, ID: ID }
      })
    },
    comfirmMember(data, dateTime, id, childrenID, index) {
      if (data != null && data != '' && data != undefined) {
        this.memberID = data.id
      }
      if (dateTime != null && dateTime != '' && dateTime != undefined) {
        this.due_at = dateTime
      }
      this.index = index
      this.todoID = childrenID
      this.todolistID = id

      this.dialogTableVisible = true
      this.dialogFormVisible = true
    },
    getTodolists() {
      // this.setting.loadingText = "拼命加载中"
      // this.setting.loading = true;
      return team
        .getTodolists(this, this.ID)
        .then(response => {
          this.todolists = response.data
          //  this.setting.loading = false;
        })
        .catch(err => {
          console.log(err)
          //  this.setting.loading = false;
        })
    },
    async getAsyncTodos() {
      for (var i = 0; i < this.todolists.length; i++) {
        let c = await team.getTodos(this, this.todolists[i].id)
        this.todolists[i].todos = c.data
        for (var j = 0; j < this.todolists[i].todos.length; j++) {
          this.todolists[i].todos[j].show = false
          this.todolists[i].todos[j].contentShow = true
        }
        this.todolists[i].index = i
        this.todolists[i].NewDiv = []
      }
      this.sort(this.todolists)
      this.newTodolists = this.todolists
      if (this.newTodolists.length === 0) {
        this.notDataContent = '任务已完成,开始下一项任务吧'
      }
      console.log(this.newTodolists)
    },
    getTodos() {
      var thef = this
      Promise.all([this.getTodolists()]).then(function(results) {
        thef.getAsyncTodos()
      })
    },
    //处理数据排序
    sort(obj) {
      obj.sort(function(a, b) {
        return a.index < b.index ? 1 : -1
      })
    },
    addDiv(id) {
      this.$forceUpdate()
      for (var i = 0; i < this.newTodolists.length; i++) {
        if (id === this.newTodolists[i].id) {
          var thef = this
          this.newTodolists[i].NewDiv.push({
            content: '',
            assignee_id: '',
            due_at: ''
          })
          this.$set(
            this.$data.newTodolists[i],
            'NewDiv',
            thef.newTodolists[i].NewDiv
          )
        }
      }
    },
    //新建保存
    save(id, item, index) {
      this.todo.todo.content = item.content
      this.todo.todo.assignee_id = item.assignee_id
      this.todo.todo.due_at = item.due_at
      this.createTodos(id, this.todo)
      this.cancle(id, index)
    },
    //新建取消
    cancle(id, index) {
      this.$forceUpdate()
      for (var i = 0; i < this.newTodolists.length; i++) {
        if (id === this.newTodolists[i].id) {
          var thef = this
          this.newTodolists[i].NewDiv.splice(index, 1)
          this.$set(
            this.$data.newTodolists[i],
            'NewDiv',
            thef.newTodolists[i].NewDiv
          )
          break
        }
      }
    },
    //修改任务
    modify(id, childrenID) {
      this.$forceUpdate()
      for (var i = 0; i < this.newTodolists.length; i++) {
        if (id === this.newTodolists[i].id) {
          for (var j = 0; j < this.newTodolists[i].todos.length; j++) {
            if (childrenID === this.newTodolists[i].todos[j].id) {
              var thef = this
              this.newTodolists[i].todos[j].show = true
              this.newTodolists[i].todos[j].contentShow = false
              this.$set(
                thef.$data.newTodolists[i],
                'todos',
                thef.newTodolists[i].todos
              )
            }
          }
          break
        }
      }
    },
    //确认修改
    async comfirm(id, element, index) {
      let responseData = await team.modifyTodos(this, element.id, {
        todo: { content: element.attributes.content }
      })
      this.cancleModify(id, element.id)
    },
    //取消修改
    cancleModify(id, childrenID) {
      this.$forceUpdate()
      for (var i = 0; i < this.newTodolists.length; i++) {
        if (id === this.newTodolists[i].id) {
          for (var j = 0; j < this.newTodolists[i].todos.length; j++) {
            if (childrenID === this.newTodolists[i].todos[j].id) {
              var thef = this
              this.newTodolists[i].todos[j].show = false
              this.newTodolists[i].todos[j].contentShow = true

              this.$set(
                thef.$data.newTodolists[i],
                'todos',
                thef.newTodolists[i].todos
              )
            }
          }
          break
        }
      }
    },
    //创建任务
    async createTodos(id, todo) {
      let responseData = await team.createTodos(this, id, todo)
      this.$forceUpdate()
      for (var i = 0; i < this.newTodolists.length; i++) {
        if (id === this.newTodolists[i].id) {
          var thef = this
          responseData.data.show = false
          responseData.data.contentShow = true
          this.newTodolists[i].todos.push(responseData.data)
          this.$set(
            this.$data.newTodolists[i],
            'todos',
            this.newTodolists[i].todos
          )
          break
        }
      }
      this.todo.todo.assignee_id = ''
      this.todo.todo.due_at = ''
      this.todo.todo.content = ''
    },
    //修改任务
    modifyTodos() {
      team
        .modifyTodos(this, this.ID)
        .then(response => {
          console.log(response.data)
        })
        .catch(err => {
          console.log(err)
        })
    },
    //删除任务
    async deleteTodos(id, childrenID, index) {
      try {
        let responseData = await team.deleteTodos(this, childrenID)
        this.$forceUpdate()
        for (var i = 0; i < this.newTodolists.length; i++) {
          if (id === this.newTodolists[i].id) {
            var thef = this
            this.newTodolists[i].todos.splice(index, 1)
            this.$set(
              thef.$data.newTodolists[i],
              'todos',
              thef.newTodolists[i].todos
            )
            break
          }
        }
      } catch (e) {
        console.log(e)
      }
    },
    //获取成员
    getProjectMembers() {
      team
        .getProjectMembers(this, this.ID)
        .then(response => {
          if (response) {
            console.log('获取所有用户')
            console.log(response.data)
            this.members = response.data
          }
        })
        .catch(error => {
          console.log(error)
        })
    },
    //指定负责人或者更新时间
    assignMember() {
      //新建处理
      if (
        this.todoID === '' ||
        this.todoID === null ||
        this.todoID === undefined
      ) {
        this.createAssignment()
        return false
      }
      if (
        this.todos_assignment.todos_assignment.assignee_id != '' &&
        this.todos_due.todos_due.due_at == ''
      ) {
        if (
          this.memberID != this.todos_assignment.todos_assignment.assignee_id
        ) {
          this.assignment()
        } else {
          this.dialogTableVisible = false
          this.dialogFormVisible = false
          this.memberID = ''
          this.todos_assignment.todos_assignment.assignee_id = ''
        }
        return false
      } else if (
        this.todos_due.todos_due.due_at != '' &&
        this.todos_assignment.todos_assignment.assignee_id == ''
      ) {
        if (
          this.due_at.split('T')[0] !=
          this.getUpdateDateTime(this.todos_due.todos_due.due_at)
        ) {
          this.due()
          this.due_at = ''
        } else {
          this.dialogTableVisible = false
          this.dialogFormVisible = false
          this.due_at = ''
          this.todos_due.todos_due.due_at = ''
        }
        return false
      } else if (
        this.todos_due.todos_due.due_at != '' &&
        this.todos_assignment.todos_assignment.assignee_id != ''
      ) {
        this.contemporaryAssigneeidOrDueat()
      } else {
        this.dialogTableVisible = false
        this.dialogFormVisible = false
      }
    },
    //负责人 任务时间更新
    contemporaryAssigneeidOrDueat() {
      if (
        this.memberID != this.todos_assignment.todos_assignment.assignee_id &&
        (this.due_at === '' ||
          this.due_at.split('T')[0] !=
            this.getUpdateDateTime(this.todos_due.todos_due.due_at))
      ) {
        var thef = this
        Promise.all([this.assignment()]).then(function(results) {
          thef.due()
          return false
        })
      } else {
        if (
          this.memberID != this.todos_assignment.todos_assignment.assignee_id
        ) {
          this.assignment()
          this.memberID
        } else if (
          this.due_at === '' ||
          this.due_at.split('T')[0] !=
            this.getUpdateDateTime(this.todos_due.todos_due.due_at)
        ) {
          this.due()
          this.due_at = ''
        } else {
          this.dialogTableVisible = false
          this.dialogFormVisible = false
          this.memberID = ''
          this.todos_assignment.todos_assignment.assignee_id = ''
          this.due_at = ''
          this.todos_due.todos_due.due_at = ''
        }
      }
    },
    //新建任务分配
    createAssignment() {
      if (
        this.todos_assignment.todos_assignment.assignee_id != '' ||
        this.todos_due.todos_due.due_at != ''
      ) {
        this.$forceUpdate()
        for (var i = 0; i < this.newTodolists.length; i++) {
          if (this.todolistID === this.newTodolists[i].id) {
            var thef = this
            if (this.todos_assignment.todos_assignment.assignee_id != '') {
              this.newTodolists[i].NewDiv[
                this.index
              ].assignee_id = this.todos_assignment.todos_assignment.assignee_id
            }
            if (this.todos_due.todos_due.due_at != '') {
              this.newTodolists[i].NewDiv[this.index].due_at =
                this.todos_due.todos_due.due_at + ''
            }
            this.$set(
              this.$data.newTodolists[i],
              'NewDiv',
              thef.newTodolists[i].NewDiv
            )
            break
          }
        }
        this.dialogTableVisible = false
        this.dialogFormVisible = false
        this.todos_assignment.todos_assignment.assignee_id = ''
        this.todos_due.todos_due.due_at = ''
      }
    },
    //分配取消
    cancleAssignMember() {
      this.dialogTableVisible = false
      this.dialogFormVisible = false
      this.todos_due.todos_due.due_at = ''
      this.todos_assignment.todos_assignment.assignee_id = ''
    },
    //指定负责人
    assignment() {
      return team
        .assignment(this, this.todoID, this.todos_assignment)
        .then(response => {
          if (response) {
            this.$forceUpdate()
            console.log('指定负责人')
            console.log(response.data)
            this.dialogTableVisible = false
            this.dialogFormVisible = false
            this.todos_assignment.todos_assignment.assignee_id = ''
            this.$set(
              this.$data.todos_assignment.todos_assignment,
              'assignee_id',
              ''
            )
            this.refresh(response.data)
          }
        })
        .catch(error => {
          this.dialogTableVisible = false
          this.dialogFormVisible = false
        })
    },
    //更新到期日
    due() {
      return team
        .due(this, this.todoID, this.todos_due)
        .then(response => {
          if (response) {
            this.$forceUpdate()
            console.log('更新时间')
            console.log(response.data)
            this.dialogTableVisible = false
            this.dialogFormVisible = false
            this.refresh(response.data)
            this.todos_due.todos_due.due_at = ''
            this.$set(this.$data.todos_due.todos_due, 'due_at', '')
          }
        })
        .catch(error => {
          console.log(error)
        })
    },
    //刷新数据
    refresh(data) {
      this.$forceUpdate()
      for (var i = 0; i < this.newTodolists.length; i++) {
        if (this.todolistID === this.newTodolists[i].id) {
          for (var j = 0; j < this.newTodolists[i].todos.length; j++) {
            if (this.todoID === this.newTodolists[i].todos[j].id) {
              var thef = this
              data.show = thef.newTodolists[i].todos[j].show
              data.contentShow = thef.newTodolists[i].todos[j].contentShow
              this.newTodolists[i].todos.splice(j, 1, data)
              this.$set(
                thef.$data.newTodolists[i],
                'todos',
                thef.newTodolists[i].todos
              )
              break
            }
          }
          break
        }
      }
    },
    //获取年月日
    getUpdateDateTime(dateTime) {
      if (dateTime === '') {
        return ''
      }
      var dateTimes = new Date(dateTime)
      var year = dateTimes.getFullYear()
      var month =
        dateTimes.getMonth() + 1 < 10
          ? '0' + (dateTimes.getMonth() + 1)
          : dateTimes.getMonth() + 1
      var day =
        dateTimes.getDate() < 10
          ? '0' + dateTimes.getDate()
          : dateTimes.getDate()
      var dateStr = year + '-' + month + '-' + day
      return dateStr
    },
    //完成任务接口
    completion(id, element, index) {
      if (element.attributes.is_completed === false) {
        this.completionTask(id, element, index)
      } else {
        this.openTask(id, element, index)
      }
    },
    //完成任务
    completionTask(id, element, index) {
      team
        .completion(this, element.id)
        .then(response => {
          console.log(response.data)
          for (var i = 0; i < this.newTodolists.length; i++) {
            if (id === this.newTodolists[i].id) {
              var thef = this
              this.newTodolists[i].todos[index].attributes.is_completed = true
              this.$set(
                thef.$data.newTodolists[i],
                'todos',
                thef.newTodolists[i].todos
              )
              break
            }
          }
        })
        .catch(error => {
          console.log(error)
        })
    },
    //打开任务
    openTask(id, element, index) {
      team
        .openTask(this, element.id)
        .then(response => {
          for (var i = 0; i < this.newTodolists.length; i++) {
            if (id === this.newTodolists[i].id) {
              var thef = this
              this.newTodolists[i].todos[index].attributes.is_completed = false
              this.$set(
                thef.$data.newTodolists[i],
                'todos',
                thef.newTodolists[i].todos
              )
              break
            }
          }
        })
        .catch(error => {
          console.log(error)
        })
    }
  }
}
</script>

<style lang="less" scoped>
.root {
  font-size: 14px;
  color: #5e6d82;
  .el-form-item__content {
    margin: 0;
  }
  .item-list-wrap {
    background: #fff;
    padding: 30px;
    .item-content-wrap {
      position: relative;
      //width: 960px;
      margin: 0 auto;

      .search-wrap {
        h3 {
          padding: 15px 5px;
          font-size: 24px;
        }
      }
      .tit {
        display: flex;
        justify-content: space-between;
        margin: 8px 0;
      }
      .task-wrap {
        // display: flex;
        // justify-content: space-around;
        //overflow: hidden;
        overflow-x: scroll;
        white-space: nowrap;
        width: 100%;
        height: 700px;
        .no-data {
          width: 100%;
          line-height: 500px;
          text-align: center;
          font-size: 24px;
        }
        .zanwushuju {
          text-align: center;
          padding: 5px 0;
        }
        .need-wrap {
          display: inline-block;
          width: 220px;
          margin: 0 5px;
          background: #f4f5f2;
          height: 700px;
          border-radius: 10px;
        }

        .top {
          display: flex;
          justify-content: space-between;
          padding: 10px 5px;
          margin: 0 5px;
          .item {
            width: 18px;
            height: 18px;
            border: 1px solid #ccc;
            border-radius: 50%;
            background: #ccc;
            text-align: center;
            padding: 2px;
          }
          .name {
            font-weight: 700;
            margin-left: 5px;
          }
          .icon-right {
            text-align: center;
            .el-icon-tickets {
              margin-left: 10px;
            }
          }
        }
        .list {
          height: 650px;
          margin: 0 5px;
          overflow: hidden;
          overflow-y: auto;
          .dragArea {
            position: relative;
            padding: 10px;
            width: 100%;
            background-color: #ffffff;
            border: solid 1px #e3e3e3;
            margin: 0 0 10px;
            border-radius: 5px;
            overflow: hidden;
            .task-copntent {
              h5 {
                font-weight: 700;
                margin: 5px 0;
              }
              width: 300px;
              position: absolute;
              left: 0;
              top: 0;
              border: 1px solid #ccc;
              border-radius: 10px;
              padding: 10px;
              z-index: 2;
            }

            .dragArea-top {
              position: relative;
              .lable {
                position: absolute;
                left: 0;
                top: 5%;
              }

              .text {
                margin-left: 10%;
                border: none;
                padding: 0;
                overflow: hidden;
                resize: none;
                border-bottom: 1px dashed #cccccc;
              }
            }
            .member {
              width: 50px;
              padding: 0;
              margin: 5px 8px;
              border: 1px solid #efefef;
              background: #efefef;
              border-radius: 10px;
              text-align: center;
              font-size: 10px;
            }
            .save {
              display: inline-block;
              width: 50px;
              line-height: 20px;
              font-size: 12px;
              border-radius: 2px;
              background: #78c23c;
              text-align: center;
              color: #fff;
            }
            .cancle {
              margin-left: 5px;
              display: inline-block;
              width: 50px;
              font-size: 12px;
              line-height: 20px;
              border-radius: 2px;
              background: #ccc;
              text-align: center;
              color: #fff;
            }
          }
        }
      }
    }
  }
}
</style>
