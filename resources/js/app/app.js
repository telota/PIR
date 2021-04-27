// vendor
window.Vue = require('vue');
window.axios = require('axios');

// 3rd party
//import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/dist/vuetify.min.css';
import Vue from 'vue';
import Vuetify from 'vuetify';
import router from './global/router';
import store from './global/Store';

// this is the vuetify theming options
Vue.use(Vuetify);

// global component registrations here
Vue.component('sessioninfo',        require('./modules/sessioninfo.vue').default);
Vue.component('pagination',         require('./modules/pagination.vue').default);
Vue.component('ace',                require('./pages/search.vue').default);


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
    store,


    data () {
        return {
            loading: false,
            dialog: {
                about: false,
                license: false
            },
            consent: null,
            error: {
                active: false
            },
            snack: {
                color: null,
                message: null
            },
            child_dialog: {
                width: '75%',
                fullscreen: false
            },

            preferences: {
                show_filters: false,
            },
            language: 'en'
        }
    },

    created () {
        //this.drawer.active = this.$vuetify.breakpoint.mdAndUp;
    },

    computed: {
        getBreadcrumbs() {
            return store.getters.getBreadcrumbs
        },

        labels() {
            return this.$localization[this.language]
        }
    },

    methods: {
        async InitializeSession (data) {
            // set language (and check if language is supported)
            this.language = Object.keys(this.$localization).includes(data.language) ? data.language : 'en'
        },

        label (string) {
            if (string) {
                const response = []
                string.split(',').forEach((value) => {
                    if (this.labels[value]) {
                        response.push(this.labels[value])
                    }
                    else {
                        response.push(value.slice(0, 1).toUpperCase() + value.slice(1).replaceAll('_', ' '))
                    }
                })
                return response.join(" ")
            }
            else {
                return 'NONE'
            }
        },

        openInNewTab (link) {
            if (link) { window.open(link) }
        },

        // JK: DBI-API-AXIOS Functions ----------------------------------------------------------------------------------
        async DBI_SELECT_GET (entity, id) {
            if (entity) {
                const self = this
                const source = 'dbi/' + entity + (id ? ('/' + id) : '')
                let dbi = {}

                console.log ('AXIOS: Fetching Data from "' + source + '" using GET. Awaiting Server Response ...');

                await axios.get(source)
                    .then((response) => {
                        dbi = response.data
                        console.log('AXIOS: ' + (dbi.contents?.[0] ? ((dbi.contents?.[0].id ? dbi.contents?.length : 0) + ' items') : 'data') + ' received.')
                        console.log(response)
                    })
                    .catch((error) => {
                        self.AXIOS_ERROR_HANDLER(error)
                    })

                return dbi
            }
        },

        /*async DBI_SELECT_POST (entity, params, search) {
            if (entity) {
                const self = this
                const source = 'dbi/' + entity
                let dbi = {}

                console.log ('AXIOS: Fetching Data from "' + source + '" using POST. Awaiting Server Response ...');

                if (search) {
                    for (const[key, value] of Object.entries(search)) {
                        params[key] = value
                    }
                }

                await axios.post(source, Object.assign ({}, params))
                    .then((response) => {
                        dbi = response.data
                        console.log ('AXIOS: ' + (dbi.contents?.[0] ? ((dbi.contents?.[0].id ? dbi.contents?.length : 0) + ' items') : 'data') + ' received.')
                        console.log (response)
                    })
                    .catch((error) => {
                        self.AXIOS_ERROR_HANDLER (error)
                    })

                return dbi
            }
        },*/

        AXIOS_ERROR_HANDLER (error) {
            this.snackbar('System Error!', 'error')
            console.log('RESPONSE CHECK: System Error:')
            console.log(error)

            this.error = {
                active:     true,
                validation: null,
                resource:   error.config   ? (error.config.url ? error.config.url : 'unknown') : 'unknown',
                exception:  error.response ? error.response.data.exception : (error.request ? error.request.data.exception : 'unknown'),
                message:    error.response ? error.response.data.message   : (error.request ? error.request.data.message   : (error.message ? error.message : '--') ),
                params:     error.config   ? (error.config.data ? error.config.data : 'none given') : 'none given'
            }
        }
    }
});
