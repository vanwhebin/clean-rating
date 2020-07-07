<template>
    <div class="login-form">
        <div class="login-heading"><h3>登录</h3></div>
        <van-form @submit="onSubmit">
            <van-field
                v-model="form.username"
                name="email" label="邮箱"
                placeholder="企业邮箱"
                :rules="[{
                 required: true,
                 message: '请填写邮箱'}]">
            </van-field>
            <van-field v-model="form.password" type="password" name="password" label="密码" placeholder="密码即邮箱" :rules="[{ required: true, message: '请填写密码' }]"></van-field>
            <div style="margin: 16px;">
                <van-button round block type="info" native-type="submit">提交</van-button>
            </div>
        </van-form>
    </div>
</template>

<script>
import { Form, Field, Button, Notify } from 'vant'
import { login } from '@/api/api'
import config from '@/config'
import { setStore, getStore} from '@/utils/storage'

export default {
    name: "Login",
    components: {
        [Form.name]: Form,
        [Notify.Component.name]: Notify.Component,
        [Button.name]: Button,
        [Field.name]: Field
    },
    data () {
        return {
            emailPattern: new RegExp('/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\\.[a-zA-Z0-9_-]{2,3}){1,2})$/'),
            form: {
                username: '',
                password: ''
            }
        }
    },
    methods: {
        onSubmit(values) {
            const _this = this
            console.log('submit', values);
            login(values).then((res) => {
                if (!res.code) {
                    _this.loginSuccess(res)
                } else {
                    _this.loginFail(res)
                }
            }).catch((err) => {
                this.loginFail(err)
            })
        },
        loginSuccess (values) {
            console.log(values)
            setStore(config.token, values.data.access_token)
            Notify({ type: 'success', message: '登录成功', duration: 1000, onClose: () => {
                    this.$router.push({name: 'list'})
                }
            });
        },
        loginFail (values) {
            console.log(values)
            Notify({ type: 'danger', message: '登录失败: 帐号或密码错误' });
        }
    }
}
</script>

<style scoped>
    /*body{*/
        /*background: #e3e3e3;*/
    /*}*/
    .login-form{
        /*background: white;*/
        margin-top: 30%;
        padding: 50px;
    }
    .login-heading {
        text-align: center;
    }
</style>
