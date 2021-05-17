// vendor
window.Vue = require('vue');
window.axios = require('axios');

// 3rd party
import 'vuetify/dist/vuetify.min.css';
import Vue from 'vue';
import Vuetify from 'vuetify';
import router from './global/router';
//import store from './global/Store';

// this is the vuetify theming options
Vue.use(Vuetify);

// global component registrations here
Vue.component('settings',       require('./modules/settings.vue').default);
Vue.component('appheader',      require('./modules/appHeader.vue').default);
Vue.component('appfooter',      require('./modules/appFooter.vue').default);
//Vue.component('trackingconsent',require('./modules/trackingConsent.vue').default);

// Own global JS variables/functions
import localization from './global/localization';

Vue.use(localization);


const app = new Vue({

    vuetify: new Vuetify({
        theme: {
            dark: false,
            themes: {
                light: {
                    app_bg:     '#eeeeee',
                    primary:    '#23608b',
                    accent:     '#b51212',
                    secondary:  '#93bacb',
                    bar_prim:   '#e0e0e0',

                    invert:     '#111111',
                    marked:     '#666666',
                    imgbg:      '#606060',
                },
                dark: {
                    app_bg:     '#181818',
                    primary:    '#8a8a8a',
                    //secondary:  '#b0bec5',
                    accent:     '#8c9eff',

                    invert:     '#eeeeee',
                    marked:     '#666666',
                    imgbg:      '#606060',
                },
            },
        },
        icons: {
            iconfont: 'md'
        }
    }),

    el: '#app',
    router,
    //store,

    data () {
        return {
            loading: false,

            snack: {
                color: null,
                message: null
            },

            // Settings provided by Backend when created
            language: 'en',
            baseURL: null,
            statistics: {},
        }
    },

    created () {},

    computed: {
        labels() {
            return this.$localization[this.language]
        },
        stats () {
            const stats = {}

            stats.total = this.statistics?.total
            stats.date = this.formatDate(this.statistics?.date)
            stats.dist = {}
            const total = {
                normal: 0,
                eques: 0,
                senator: 0,
                total: stats.total
            }

            ;['female', 'male', 'notKnown'].forEach((key) => {
                stats.dist[key] = {
                    normal: this.statistics?.[key].normal ?? 0,
                    eques: this.statistics?.[key].eques ?? 0,
                    senator: this.statistics?.[key].senator ?? 0,
                }
                stats.dist[key].total = stats.dist[key].normal + stats.dist[key].eques + stats.dist[key].senator

                total.normal += stats.dist[key].normal
                total.eques += stats.dist[key].eques
                total.senator += stats.dist[key].senator
            })

            stats.dist.total = total

            return stats
        }
    },

    methods: {
        label (string) {
            if (string) {
                const response = []
                string.split(',').forEach((value) => {
                    if (this.labels[value]) response.push(this.labels[value])
                    else response.push(value.slice(0, 1).toUpperCase() + value.slice(1).replaceAll('_', ' '))
                })
                return response.join(" ")
            }
            else return 'NONE'
        },

        openInNewTab (link) {
            if (link) { window.open(link) }
        },

        formatDate (date) {
            if (!date) date = ['----', '--', '--']
            else date = date?.split('-')
            return this.language === 'de' ? date.reverse().join('.') : [date[1], date[2], date[0]].join('/')
        }
    }
});
