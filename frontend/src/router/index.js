import Vue from 'vue'
import Router from 'vue-router'
import Home from '@/pages/Home.vue'

const routes = [
    { path: '/', name: 'home', component: Home, redirect: '/list' },
    { path: '/login', name: 'login', component: () => import('@/pages/Login'), meta: { title: '登录' } },
    { path: '/score', name: 'score', component: () => import('@/pages/Score'), meta: { title: '评分' } },
    { path: '/result', name: 'result', component: () => import('@/pages/Result'), meta: { title: '结果' } },
    { path: '/top', name: 'top', component: () => import('@/pages/Top'), meta: { title: '排行榜' } },
    { path: '/list', name: 'list', component: () => import('@/pages/List'), meta: { title: '列表' } },
    { path: '*', name: '404', component: () => import('@/pages/404'), meta: { title: '404' } }
]
Vue.use(Router)


// add route path
routes.forEach(route => {
    route.path = route.path || '/' + (route.name || '');
});

const router = new Router({ routes, mode: 'history' });

router.beforeEach((to, from, next) => {
    const title = to.meta && to.meta.title;
    if (title) {
        document.title = title;
    }
    next();
})

export default router
