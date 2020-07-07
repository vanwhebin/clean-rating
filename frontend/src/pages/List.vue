<template>
    <div>
        <img class="user-poster" src="../assets/6s.png" alt="" :style="{width:'100%','margin-bottom': '10px'}">
        <van-skeleton title :row="17" :loading="loading" class="skeleton">
            <div>
                <van-cell-group>
                    <van-cell icon="records" :title="item.name" :key="key" v-for="(item, key) in depts" @click="goRating(item)" is-link :value="item.status?'':'已评分'"></van-cell>
                </van-cell-group>
            </div>
        </van-skeleton>
        <div class="submit" v-show="showResultBtn"><van-button round plain hairline type="info" @click="$router.push({name:'result'})">查看结果</van-button></div>
    </div>
</template>

<script>
import { Row, Col, Icon, Cell, CellGroup, Skeleton, Toast, Button } from 'vant'
import { getCampaignDepts, getLatestCampaign } from '@/api/api'
import config  from '@/config'
import { getStore, setStore } from '@/utils/storage'

export default {
    name: "List",
    components: {
        [Row.name]: Row,
        [Col.name]: Col,
        [Button.name]: Button,
        [Icon.name]: Icon,
        [Cell.name]: Cell,
        [CellGroup.name]: CellGroup,
        [Skeleton.name]: Skeleton
    },
    mounted () {
        this.getData()
    },
    data () {
        return {
            showResultBtn: false,
            depts: [],
            loading: true,
            img: 'https://img.yzcdn.cn/public_files/2017/10/23/8690bb321356070e0b8c4404d087f8fd.png'
        }
    },
    methods: {
        getData () {
            let campaignID = getStore(config.campaignRef)
            const _this = this
            if (!campaignID) {
                getLatestCampaign().then((res) => {
                    console.log(res)
                    setStore(config.campaignRef, res.data.id)
                    setStore(config.campaignTitle, res.data.title)
                    _this.getDepts(res.data.id)
                })
            } else {
                this.getDepts(campaignID)
            }
        },
        getDepts (campaignID) {
            getCampaignDepts(campaignID).then((res) => {
                console.log(res.data)
                this.depts = res.data
                this.loading = false
                this.checkResult()
            })
        },
        goRating (item) {
            if (!item.status) {
                Toast('该部门已提交评分，不可重复评分')
                return false
            }
            this.$router.push({name: 'score', params: {id: item.id, name: item.name}})
        },
        checkResult () {
            const _this = this
            this.showResultBtn = true
            this.depts.forEach(function(item){
                console.log(item.status)
                if (item.status) {
                    _this.showResultBtn = false
                    return false
                }
            })
        }
    }
}
</script>


<style scoped >
    .skeleton {
        margin-bottom: 10px;
    }
    .submit{
        text-align: center;
        margin-top: 30px;
        margin-bottom: 30px;
    }
</style>
