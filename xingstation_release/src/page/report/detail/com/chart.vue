<template>
  <!-- why do i write this stupid code? -->
  <div 
    :style="style.root"
    class="root"
  >
    <div
      v-for="(item, index) in chartdata"
      v-show="dataOptions[index] && index !== chartdata.length - 1"
      :key="index"
      :style="bindStyle[index]"
    >
      <!-- end special process -->
      <div 
        v-if="chartdata.length - index === 2" 
        :style="childStyles[0]"/>
      <div 
        v-if="chartdata.length - index === 1" 
        :style="childStyles[1]"/>
      <div 
        :style="innerTextStyles[index]" 
        class="text-inner">
        <span :class="{'add-top': chartdata.length - index === 1 }">
          {{ dataName[index] }}
        </span>
        <span :class="{'add-margin': chartdata.length - index === 2 }">
          {{ chartdata[index] }}
        </span>
      </div>

      <!-- circles -->
      <div
        v-show="index > 1" 
        :style="circleArea[index]">
        <div :style="innerCircle[index]">
          <div :style="lineStyle[index]">
            <div :style="circlePoint[index]"/>
          </div>
          <div
            :style="whiteCirlce[index]">
            <div
              v-if="index > 0"
              :style="smallCirlce[index]" 
              class="percent">
              {{ computedRate[index - 1] }} %
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    chartdata: {
      type: Array,
      default: function() {
        return [90291, 9078, 7461, 5463, 3258, 2434, 834]
      }
    },
    dataOptions: {
      type: Array,
      default: function() {
        return [true, true, true, true, true, true, true]
      }
    },
    width: {
      type: Number,
      default: 400
    }
  },
  data() {
    return {
      // chartdata: [90291, 9078, 7461, 5463, 3258, 2434, 834],
      dataName: [
        '曝光人次',
        '大屏围观参与人数',
        '大瓶活跃玩家人数',
        '大屏铁杆玩家人数',
        'OMO有效跳转人数',
        '扫码啦心会员注册总数',
        '完成转发分享人数'
      ],
      // dataOptions: [true, true, true, true, true, true, true],
      bindColors: [
        '#8FE5B8',
        '#0099FF',
        '#22B573',
        '#F8B62D',
        '#E80F9B',
        '#E83828',
        '#9E8047'
      ],
      circleColors: [
        '#036EA4',
        '#197748',
        '#F7931E',
        '#BE136E',
        '#BC1313',
        '#7F4F21'
      ],
      styleCalculated: [],
      calStore: [],
      bindStyle: [],
      topWidth: [],
      cover: [],
      height: 1500,
      // width: this.$innerWidth(),
      hvw: 1500 / 880,
      sh: 160,
      dh: 320,
      sp: 10,
      settingSin: 23 / 50,
      bvt1: 790 / 840,
      bvt: 640 / 780,
      style: {
        root: {}
      },
      rsh: null,
      rsp: null,
      childStyles: [],
      innerTextStyles: [],
      circleArea: [],
      innerCircle: [],
      whiteCirlce: [],
      smallCirlce: [],
      lineStyle: [],
      circlePoint: [],
      computedRate: []
    }
  },
  watch: {
    dataOptions: function() {
      this.risizeCanvas()
      this.calculate()
      this.calculateStyles()
    },
    chartdata: function() {
      this.risizeCanvas()
      this.calculate()
      this.calculateStyles()
    },
    width: function() {
      this.risizeCanvas()
      this.calculate()
      this.calculateStyles()
    }
  },
  created() {
    // set canvas map
    this.risizeCanvas()
    // calculate
    this.calculate()
    // calculate style and rerender
    this.calculateStyles()
  },
  mounted() {},
  methods: {
    risizeCanvas() {
      this.height = this.width * this.hvw
      this.style.root = {
        height: this.height + 'px'
      }
    },
    calculate() {
      // special top 1, bottom 2
      this.calStore = []
      this.computedRate = []
      for (let i = 0; i < this.chartdata.length; i++) {
        if (i > 0) {
          this.computedRate.push(
            parseInt(this.chartdata[i] / this.chartdata[i - 1] * 100)
          )
        }
        let dataObj = {}
        if (i === 0) {
          dataObj.topWidth = this.width
          dataObj.bottomWidth = this.width
          dataObj.height = this.height * this.sh / 1500
        }
        if (i === 1) {
          dataObj.topWidth = this.width
          dataObj.bottomWidth = this.width * this.bvt1
          dataObj.height = this.height * this.sh / 1500
        }
        if (i > 1 && i < this.chartdata.length - 2) {
          dataObj.topWidth = this.calStore[i - 1].bottomWidth
          dataObj.bottomWidth = dataObj.topWidth * this.bvt
          dataObj.height = this.height * this.sh / 1500
        }
        if (i === this.chartdata.length - 2 && i > 2) {
          dataObj.topWidth = this.calStore[i - 1].bottomWidth
          dataObj.bottomWidth = dataObj.topWidth
          dataObj.height = this.height * this.dh / 1500
        }
        if (i === this.chartdata.length - 1 && i > 3) {
          dataObj.topWidth = this.calStore[i - 1].topWidth
          dataObj.bottomWidth = dataObj.topWidth
          dataObj.height = this.height * this.dh / 1500
        }
        this.calStore.push({ ...dataObj })
      }
    },
    calculateStyles() {
      this.bindStyle = []
      this.childStyles = []
      this.innerTextStyles = []
      this.circleArea = []
      this.innerCircle = []
      this.lineStyle = []
      for (let i = 0; i < this.chartdata.length; i++) {
        // out style Object
        let bindStyleObject = {}

        // innerText
        let innerTextStyle = {
          height: String(this.calStore[i].height.toFixed(1)) + 'px',
          width: this.calStore[i].bottomWidth + 'px',
          position: 'absolute',
          top: '-' + String(this.calStore[i].height.toFixed(1)) + 'px',
          left: 0,
          color: 'white'
        }

        // lineStyle

        // circleArea
        let circleAreaObj = {}
        if (i % 2 === 0) {
          circleAreaObj = {
            width: this.width / 2 + 'px',
            height: String(this.calStore[i].height.toFixed(1)) + 'px',
            position: 'absolute',
            right: '-' + this.width / 2 + 'px',
            top: '-' + String(this.calStore[i].height.toFixed(1)) + 'px'
          }
        } else {
          circleAreaObj = {
            width: this.width / 2 + 'px',
            height: String(this.calStore[i].height.toFixed(1)) + 'px',
            position: 'absolute',
            left: '-' + this.width / 2 + 'px',
            top: '-' + String(this.calStore[i].height.toFixed(1)) + 'px'
          }
        }

        let innerCircleObj = {}
        let whiteCircleObj = {}
        let smallCirlceObj = {}
        // innerCircle
        if (i > 0) {
          let w = String(this.calStore[i].height.toFixed(1))
          innerCircleObj = {
            height: w + 'px',
            width: w + 'px',
            borderRadius: w / 2 + 'px',
            backgroundColor: this.circleColors[i - 1],
            position: 'absolute',
            top: '0',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center'
          }

          if (i % 2 === 0) {
            innerCircleObj.right = '0'
          } else {
            innerCircleObj.left = '0'
          }

          let w2 = w - 4
          whiteCircleObj = {
            height: w2 + 'px',
            width: w2 + 'px',
            borderRadius: w2 / 2 + 'px',
            backgroundColor: 'white',
            position: 'abosulte',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center'
          }

          let w3 = w2 - 4
          smallCirlceObj = {
            height: w3 + 'px',
            width: w3 + 'px',
            borderRadius: w3 / 2 + 'px',
            backgroundColor: this.circleColors[i - 1],
            position: 'abosulte',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center'
          }
        }

        this.innerCircle.push({ ...innerCircleObj })
        this.whiteCirlce.push({ ...whiteCircleObj })
        this.smallCirlce.push({ ...smallCirlceObj })

        // line
        let lineObj = {}
        if (i > 0) {
          let w = String(this.calStore[i].height.toFixed(1))
          lineObj = {
            width: w * 1 / 3 + 'px',
            position: 'absolute',
            top: w / 2 + 'px',
            height: '2px',
            backgroundColor: this.circleColors[i - 1]
          }
          if (i % 2 === 0) {
            lineObj.left = '-' + w * 1 / 3 + 'px'
          } else {
            lineObj.right = '-' + w * 1 / 3 + 'px'
          }
        }
        this.lineStyle.push({ ...lineObj })

        // linePoint
        let pointObj = {}

        if (i > 0) {
          pointObj = {
            width: '8px',
            height: '8px',
            top: '-3px',
            borderRadius: '4px',
            backgroundColor: this.circleColors[i - 1],
            position: 'absolute'
          }
          if (i % 2 === 0) {
            pointObj.left = '0'
          } else {
            pointObj.right = '0'
          }
        }
        this.circlePoint.push({ ...pointObj })

        if (i === 0) {
          bindStyleObject.width = this.width + 'px'
          bindStyleObject.color = this.bindColors[i]
          bindStyleObject.height =
            String(this.calStore[i].height.toFixed(1)) + 'px'
          bindStyleObject.backgroundColor = this.bindColors[0]

          delete innerTextStyle.top
          innerTextStyle.color = 'black'

          circleAreaObj.top = '0'
        }
        if (i === 1) {
          bindStyleObject.width = this.calStore[i].topWidth + 'px'
          bindStyleObject.color = this.bindColors[i]
          bindStyleObject.height =
            String(this.calStore[i].height.toFixed(1)) + 'px'
          bindStyleObject.borderTop =
            bindStyleObject.height + ' solid ' + this.bindColors[i]
          let cutWidth = (
            (this.calStore[i].topWidth - this.calStore[i].bottomWidth) /
            2
          ).toFixed(1)
          bindStyleObject.borderLeft = String(cutWidth) + 'px solid white'
          bindStyleObject.borderRight = bindStyleObject.borderLeft
        }
        if (i > 1 && i < this.chartdata.length - 2) {
          bindStyleObject.width = this.calStore[i].topWidth + 'px'
          bindStyleObject.color = this.bindColors[i]
          bindStyleObject.height =
            String(this.calStore[i].height.toFixed(1)) + 'px'
          bindStyleObject.borderTop =
            bindStyleObject.height + ' solid ' + this.bindColors[i]
          let cutWidth = (
            (this.calStore[i].topWidth - this.calStore[i].bottomWidth) /
            2
          ).toFixed(1)
          bindStyleObject.borderLeft = String(cutWidth) + 'px solid white'
          bindStyleObject.borderRight = bindStyleObject.borderLeft
        }
        if (i === this.chartdata.length - 2 && i > 2) {
          bindStyleObject.width = this.calStore[i].topWidth + 'px'
          bindStyleObject.backgroundColor = this.bindColors[i]
          bindStyleObject.height =
            String(this.calStore[i].height.toFixed(1)) + 'px'

          let childStyle = {
            width: this.calStore[i].topWidth + 'px',
            height: String((this.calStore[i].height / 2).toFixed(1)) + 'px',
            position: 'absolute',
            bottom: 0,
            left: 0,
            borderLeft: this.calStore[i].topWidth / 2 + 'px solid white',
            borderRight: this.calStore[i].topWidth / 2 + 'px solid white',
            borderTop:
              String((this.calStore[i].height / 2).toFixed(1)) +
              'px solid ' +
              this.bindColors[i]
          }
          this.childStyles.push({ ...childStyle })
          innerTextStyle.height =
            String(this.calStore[i].height.toFixed(1)) + 'px'
          delete innerTextStyle.top

          circleAreaObj.top = '0'
        }
        if (i === this.chartdata.length - 1 && i > 3) {
          bindStyleObject.width = this.calStore[i].topWidth + 'px'
          bindStyleObject.backgroundColor = this.bindColors[i]
          bindStyleObject.height =
            String(this.calStore[i].height.toFixed(1)) + 'px'

          let childStyle = {
            width: this.calStore[i].topWidth + 'px',
            height: String((this.calStore[i].height / 2).toFixed(1)) + 'px',
            position: 'absolute',
            top: 0,
            left: 0,
            borderLeft: this.calStore[i].topWidth / 2 + 'px solid white',
            borderRight: this.calStore[i].topWidth / 2 + 'px solid white',
            borderBottom:
              String((this.calStore[i].height / 2).toFixed(1)) +
              'px solid ' +
              this.bindColors[i]
          }
          this.childStyles.push({ ...childStyle })
          innerTextStyle.height =
            String(this.calStore[i].height.toFixed(1)) + 'px'
          delete innerTextStyle.top
          circleAreaObj.top = '0'
        }
        delete bindStyleObject.color
        bindStyleObject.marginBottom =
          String((this.sp / 1500 * this.width).toFixed(1)) + 'px'
        bindStyleObject.position = 'relative'
        this.bindStyle.push(bindStyleObject)
        this.innerTextStyles.push({ ...innerTextStyle })
        this.circleArea.push({ ...circleAreaObj })
      }
    }
  }
}
</script>

<style lang="less" scoped>
.root {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: flex-start;
  .text-inner {
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    align-items: center;
    .add-top {
      margin-top: 30%;
    }
    .add-margin {
      margin-bottom: 30%;
    }
  }
  .percent {
    color: white;
  }
}
</style>
