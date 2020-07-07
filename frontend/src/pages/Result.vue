<template>
    <div>
        <van-nav-bar :title="campaign.title" left-text="返回" left-arrow @click-left="onClickLeft"></van-nav-bar>

        <van-list @load="onLoad" :style="{'margin-top': '15px'}">
            <van-cell v-for="(item, k) in list" :key="k" :title="k" :center="center" :value="item"></van-cell>
        </van-list>
        <div class="submit" v-show="checkAllBtn"><van-button round plain hairline type="info" @click="checkAll">查看全部</van-button></div>
    </div>
</template>

<script>
    import { Button, Cell, List, NavBar } from 'vant'
    import { getRanking } from '@/api/api'
    import { getStore, setStore } from '@/utils/storage'
    import config from '@/config'

    export default {
        name: "Result",
        components: {
            [Button.name]: Button,
            [NavBar.name]: NavBar,
            [Cell.name]: Cell,
            [List.name]: List
        },
        data () {
            return {
                campaign: {
                    title: '',
                    id: null
                },
                center: true,
                list: [],
                loading: false,
                checkAllBtn: false
            }
        },
        mounted () {
            this.campaign.title = getStore(config.campaignTitle)
            this.campaign.id = getStore(config.campaignRef)
            this.getData((res) => {
                this.list = res.data
                this.checkAllBtn = true
            }, {pageSize: 3})
        },
        methods: {
            onClickLeft () {
                this.$router.push({ name: 'list' })
            },
            // onLoad () {
            //     // 异步更新数据
            //     // setTimeout 仅做示例，真实场景中一般为 ajax 请求
            //     const _this = this
            //
            // },
            checkAll () {
                const _this = this
                this.getData((res) => {
                    _this.list = res.data
                    _this.checkAllBtn = false
                })
            },
            getData (callback, parameter) {
                getRanking(this.campaign.id, parameter).then((res) => callback && callback(res))
            }
        }
    }
</script>

<style scoped>
    .result-heading {
        text-align: center;
        margin-top: 35px;
    }
    .submit{
        text-align: center;
        margin-top: 100px;
    }
</style>
