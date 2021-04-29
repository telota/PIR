<template>
<div>

    <!-- Query Form -->
    <v-row justify="center">
        <v-col cols=12 sm=10>

            <!-- Search Form -->
            <div :style="this.$vuetify.breakpoint.mdAndUp ? 'width: 400px; position: fixed;' : ''">
                <v-card tile>
                    <v-card-text>
                        <!-- Filters -->
                            <!-- Search String -->
                            <div class="caption mb-n4" v-text="$root.label('search_string')" />
                            <v-text-field
                                v-model="search.string"
                                v-on:keyup.enter="RunSearch()"
                            />
                            <!-- Search Mode -->
                            <div class="caption mb-n3" v-text="$root.label('search_mode')" />
                            <v-radio-group v-model="search.mode">
                                <v-row>
                                    <v-col cols=6 v-for="(item, i) in modes" :key="'sm' + i">
                                        <v-radio :label="item.text" :value="item.value" />
                                    </v-col>
                                </v-row>
                            </v-radio-group>
                            <!-- Logical Connector -->
                            <div class="caption mb-n3 mt-1" v-text="$root.label('search_isOr')" />
                            <v-radio-group v-model="search.isOr">
                                <v-row>
                                    <v-col>
                                        <v-radio :label="$root.label('search_isOr_false')" :value="false" />
                                    </v-col>
                                    <v-col>
                                        <v-radio :label="$root.label('search_isOr_true')" :value="true" />
                                    </v-col>
                                </v-row>
                            </v-radio-group>
                            <!-- Case sensitivity -->
                            <div class="caption mb-n3 mt-1" v-text="$root.label('search_isCs')" />
                            <v-radio-group v-model="search.isCs">
                                <v-row>
                                    <v-col>
                                        <v-radio :label="$root.label('search_isCs_false')" :value="false" />
                                    </v-col>
                                    <v-col>
                                        <v-radio :label="$root.label('search_isCs_true')" :value="true" />
                                    </v-col>
                                </v-row>
                            </v-radio-group>
                        <!-- Search Button -->
                        <v-btn
                            tile
                            block
                            class="mt-5 primary"
                            v-text="$root.label('search')"
                            @click="setSearchURL()"
                        />
                        <!-- Search Reset -->
                        <div class="mt-2 d-flex justify-center">
                            <v-btn
                                text
                                small
                                v-text="$root.label('search_reset')"
                                @click="ResetFilters(true)"
                            ></v-btn>
                        </div>
                    </v-card-text>
                </v-card>
            </div>

            <!-- Container -->
            <div :style="this.$vuetify.breakpoint.mdAndUp ? 'margin-left: 400px;' : ''">
                <v-card tile class="mb-5" :class="this.$vuetify.breakpoint.mdAndUp ? 'ml-5' : 'mt-5'">

                    <!-- Instructions -->
                    <v-card-text v-if="instructions">
                        <instructions :mode="mode" />
                    </v-card-text>

                    <!-- Results -->
                    <div v-else>
                        <!-- Pagination -->
                        <div>
                            <div style="position: absolute; right: 0;">
                                <v-btn icon large class="ma-1" @click="instructions = true">
                                    <v-icon large v-text="'help_outline'" />
                                </v-btn>
                            </div>
                            <div class="pa-2 pt-3 title text-center" v-html="count_formated + ' ' + $root.label('records_for') + ' \'' + searchedString + '\''" />
                            <v-progress-linear v-if="loading" indeterminate color="accent" />
                            <div v-else style="border-bottom: 3px solid #b51212;" />
                            <pagination  color="transparent" :pagination="pagination" v-on:navigate="(emit) => { this.setSearchURL(emit) }"></pagination>
                        </div>

                        <!-- Result List -->
                        <v-expand-transition>
                            <div
                                v-if="loading"
                                class="pa-10 text-center"
                                v-text="$root.label('processing')"
                            />
                            <div v-else-if="items[0]" class="pa-5">
                                <div
                                    v-for="(item, i) in items"
                                    :key="i"
                                    class="mb-5"
                                >
                                    <div v-html="item.htmlContent" />
                                    <div v-html="item.reference" />
                                </div>
                            </div>
                            <div
                                v-else
                                class="pa-10 text-center"
                                v-text="$root.label('result_none')"
                            />
                        </v-expand-transition>

                        <!-- Pagination -->
                        <div style="border-top: 3px solid #b51212;">
                            <pagination color="transparent" :pagination="pagination" v-on:navigate="(emit) => { this.setSearchURL(emit) }"></pagination>
                        </div>
                    </div>
                </v-card>
            </div>

        </v-col>
    </v-row>

</div>
</template>


<script>

export default {
    data () {
        return {
            loading: false,
            filterExpanded: true,
            searchedString: null,
            no_result: '&ensp;',

            search: {
                mode: this.$route.params.mode ?? 'keywords',
                string: null,
                isOr: false,
                isCs: false,
            },

            modes: [
                { value: 'keywords', text: this.$root.label('search_mode_keywords') },
                { value: 'addenda', text: this.$root.label('search_mode_addenda') }
            ],

            items: [],

            instructions: true,
            queryRefresh: 0,
            queryDialog: false,

            pagination: {
                offset: 0,
                limit: 20,
                count: 0,
                page: {
                current: 1,
                total: 1
                },
                first: null,
                previous: null,
                next: null,
                last: null
            }
        }
    },

    computed: {

        mode () {
            return this.$route.params.mode ?? 'keywords'
        },

        count_formated () { // Beautify result counter
            let count = this.pagination.count

            if (count > 0) {
                if (count > 999999) {
                count = count.toString().substring(0, count.toString().length - 6) + '.' + count.toString().substring(count.toString().length - 6, count.toString().length - 3) + '.' + count.toString().substring(count.toString().length - 3)
                }
                if (count > 999) {
                count = count.toString().substring(0, count.toString().length - 3) + '.' + count.toString().substring(count.toString().length - 3)
                }
                return count
            } else {
                return this.$root.language === 'de' ? 'Keine' : 'No'
            }
        }
    },

    watch: {
        $route(to, from) {
            if (window.location.hash.split('?')[1]) this.runSearch()
        },
        'search.mode' () {
            this.$router.push('/search/' + this.search.mode)
        }
    },

    created () {
        const query = window.location.hash.split('?')[1]
        if (query) {
            this.search.string = query.split('string=')[1]?.split('&')[0] ? decodeURI(query.split('string=')[1]?.split('&')[0]) : null
            this.search.isCs = query.split('isCs=')[1]?.split('&')[0] == 1 ? true : false
            this.search.isOr = query.split('isOr=')[1]?.split('&')[0] == 1 ? true : false
            this.runSearch()
        }
    },

    methods: {
        OpenNewBrowserTab (url) { // Handler for Links to external resources
            window.open(url, '_blank')
        },

        setSearchURL (url) {
            const params = {}

            if (url) {
                url.split('?')[1]?.split('&').forEach((p) => {
                    params[p.split('=')[0]] = p.split('=')[1]
                })
            }
            else {
                if (this.search.string) params.string = this.search.string
                if (this.search.isCs) params.isCs = 1
                if (this.search.isOr) params.isOr = 1
            }

            this.$router.push({ path: '/search/' + this.mode, query: params }).catch((error) => { this.runSearch() })
        },

        async runSearch () { // Execute Query
            this.instructions = false
            this.items = []

            const params = window.location.hash.split('?')[1] ?? ''
            console.log(params)
            const fetch = await this.FetchData('/api/' + this.mode + '?' + params)

            // Check if result is empty
            if (!fetch?.contents?.[0]) this.no_result = this.$root.label('no_records')

            // Set Pagination
            this.pagination = {
                count: fetch.pagination.count,
                page: fetch.pagination.page,
                first: fetch.pagination.firstPage,
                previous: fetch.pagination.previousPage,
                next: fetch.pagination.nextPage,
                last: fetch.pagination.lastPage,
            }

            // Set Items
            this.items = fetch.contents
        },

        async FetchData (url) { // Axios Fetch to given URL
            this.loading = true
            this.searchedString = this.search.string ? this.search.string : '-'
            const fetch = await axios.get(url).catch((error) => { console.log(error) })
            this.loading = false
            return fetch ? fetch.data : {}
        },

        ResetFilters () { // Reset Search Form Fields to default
            Object.keys(this.search).forEach((key) => {
                this.search[key] = key === 'mode' ? this.mode : null
            })
            if (window.location.hash.split('?')[1]) this.$router.push('/search/' + this.mode)
        },
    }
}
</script>

<style>
  a {
    text-decoration: none !important;
  }
</style>
