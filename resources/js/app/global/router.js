import Vue from 'vue'
import Router from 'vue-router'
// import store from './Store'

Vue.use(Router)

const router = new Router ({
    routes: [
        { path: '/', redirect: '/overview', },

        {
            path: '/search',
            name: 'search',
            component: require('./../pages/search').default
        },
        {
            path: '/overview',
            name: 'overview',
            component: require('./../pages/overview').default
        },
        {
            path: '/publications',
            name: 'publications',
            component: require('./../pages/publications').default
        },
        {
            path: '/methods',
            name: 'methods',
            component: require('./../pages/methods').default
        },
        {
            path: '/history',
            name: 'history',
            component: require('./../pages/history').default
        },
        {
            path: '/resources',
            name: 'resources',
            component: require('./../pages/resources').default
        },
        {
            path: '/api',
            name: 'api',
            component: require('./../pages/api').default
        }
    ],
})


router.beforeEach((to, from, next) => {
    //store.commit('showLoader')
    next()
})

router.afterEach((to, from) => {
    /*setTimeout(()=>{
        store.commit('hideLoader')
    },1000)*/
})

export default router
