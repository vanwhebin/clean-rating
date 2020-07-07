<template>
    <div>
        <van-nav-bar :title="curObject.name" left-text="返回" left-arrow @click-left="onClickLeft"></van-nav-bar>
        <van-cell-group>
            <img class="user-poster" :src="curObject.img" alt="" :style="{width:'100%','margin-bottom': '20px'}">
        </van-cell-group>

        <van-tabs v-model="tabs.active">
            <van-tab :title="key" :key="key" v-for="(item,key) in tabs.data">
                <van-grid>
                    <van-grid-item :icon="it.icon ? it.icon : 'question-o'" :key="ind" :text='it.title' v-for="(it, ind) in item"
                                   @click="selectRule(it)"></van-grid-item>
                </van-grid>
            </van-tab>
        </van-tabs>
        <div class="block" v-if="submitting">
            <van-loading :style="{'text-align': 'center','padding-top': '35px'}" size="35px"></van-loading>
        </div>
        <div class="submit" v-else><van-button round plain hairline type="info" @click="submitAll">提交评分</van-button></div>

        <div class="rater" v-show="selectedRule.weight">
            <van-action-sheet v-model="showPicker" close-on-click-action @cancel="onCancel"
                              :description="selectedRule.desc">
                <div class="content">
                    <van-picker title="请评分" show-toolbar :columns="columns" @confirm="onConfirm" @cancel="onCancel"
                                @change="onChange"></van-picker>
                </div>
            </van-action-sheet>
        </div>

    </div>
</template>

<script>
    import { getDeptRule, postScore, getScoredRule, putSubmit } from '@/api/api'
    import config from '@/config'
    import { getStore } from '@/utils/storage'

    import { Tab, Tabs, NavBar, Toast, Grid, GridItem, Picker, ActionSheet, CellGroup, Button, Notify, Loading } from 'vant'

    export default {
        name: "Score",
        components: {
            [Loading.name]: Loading,
            [CellGroup.name]: CellGroup,
            [Button.name]: Button,
            [Notify.Component.name]: Notify.Component,
            [ActionSheet.name]: ActionSheet,
            [Picker.name]: Picker,
            [NavBar.name]: NavBar,
            [Grid.name]: Grid,
            [GridItem.name]: GridItem,
            [Tab.name]: Tab,
            [Tabs.name]: Tabs
        },
        data () {
            return {
                curObject: {
                    id: 0,
                    name: '',
                    img: 'https://img.yzcdn.cn/public_files/2017/10/23/8690bb321356070e0b8c4404d087f8fd.png'
                },
                columns: [],
                selectedRule: {
                    rule_id: 0,
                    weight: 0,
                    desc: ''
                },
                submitting: false,
                showPicker: false,
                tabs: {
                    data: [],
                    active: 0
                },
                rules: [],
                statusIcon: ''
            }
        },
        mounted () {
            this.curObject.id = this.$route.params.id
            this.curObject.name = this.$route.params.name
            this.getRule()
        },
        methods: {
            getRule () {
                getDeptRule(this.curObject.id).then((res) => {
                    this.tabs.data = res.data
                })
            },
            onClickLeft () {
                this.$router.push({ name: 'list' })
            },
            selectRule (item) {
                this.columns = []
                for (let i = 1; i <= item.weight; i++) {
                    this.columns.push(i)
                }
                this.selectedRule = item
                this.showPicker = true
            },
            onConfirm (value, index) {
                const _this = this
                console.log(this.selectedRule)
                Toast(`当前值：${value}`)
                this.showPicker = false
                this.submitting = true

                const campaignID = getStore(config.campaignRef)
                const data = {campaignID: campaignID, ruleID: _this.selectedRule.rule_id, score: value}
                postScore(_this.curObject.id, data).then((res) => {
                    console.log(res)
                    if (!res.code) {
                        setTimeout(function() {_this.submitting = false}, 500)

                        Notify({ type: 'success', message: '此项评分成功', duration: 800 })
                        getScoredRule(_this.curObject.id, data).then((r) => {
                            _this.updateFormat(r.data)
                        })
                    } else {
                        Notify({ type: 'danger', message: '哦豁，出错了', duration: 1500 });
                    }
                })
            },
            updateFormat (ratedIDS) {
                // 更新当前评分状态
                for (let item in this.tabs.data) {
                    this.tabs.data[item].forEach(function(it){
                        if (ratedIDS.indexOf(it.rule_id) !== -1) {
                            it.icon = 'success'
                        }
                    })
                }
            },
            onChange (picker, value, index) {
                Toast(`当前值：${value}`)
            },
            onCancel () {
                Toast('取消')
                this.showPicker = false
            },
            submitAll () {
                // 提交信息 不允许修改
                if (this.checkRating()) {
                    const _this = this
                    const campaignID = getStore(config.campaignRef)
                    const data = {campaignID: campaignID}
                    putSubmit(_this.curObject.id, data).then((r) => {
                        if (!r.code) {
                            Notify({ type: 'success', message: '提交成功', duration: 800, onClose: () => {
                                    _this.$router.push({name: 'list'})
                                }
                            });
                        } else {
                            Notify({ type:  'danger', message: r.msg, duration: 1500});
                        }

                    })
                } else {
                  Notify({type:'warning', message: '请完成所有的评分维度', duration: 2000})
                }
            },
            checkRating () {
                for (let item in this.tabs.data) {
                    for (let i = 0;i<this.tabs.data[item].length;i++) {
                        if (this.tabs.data[item][i].icon === undefined ) {
                            return false
                        }
                    }
                }
                return true
            }
        }
    }
</script>

<style scoped>
    .submit{
        text-align: center;
        margin-top: 100px;
    }
    .block {
        margin:auto;
        text-align: center;
        width: 120px;
        height: 120px;
        border-radius: 3px;
        background-color: #eceaea5c;
    }
</style>
