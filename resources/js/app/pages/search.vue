<template>
<div>

    <!-- Search Form -->
    <div :style="this.$vuetify.breakpoint.mdAndUp ? 'width: 400px; position: fixed;' : ''">
        <v-card tile>
            <v-card-text>
                <!-- Filters -->
                    <!-- Search Resource -->
                    <div class="caption mb-n4" v-text="$root.label('search_resource')" />
                    <v-radio-group v-model="search.resource">
                        <v-row>
                            <v-col cols=6 v-for="(item, i) in resources" :key="'resource' + i">
                                <v-radio :label="item.text" :value="item.value" />
                            </v-col>
                        </v-row>
                    </v-radio-group>
                    <!-- Search String -->
                    <div class="caption mb-n4" v-text="$root.label('search_string')" />
                    <v-text-field
                        v-model="search.string"
                        v-on:keyup.enter="setSearchURL()"
                    />
                    <!-- Logical Connector -->
                    <div class="caption mb-n4" v-text="$root.label('search_connector')" />
                    <v-radio-group v-model="search.connector">
                        <v-row>
                            <v-col>
                                <v-radio :label="$root.label('search_connector_and')" value="AND" />
                            </v-col>
                            <v-col>
                                <v-radio :label="$root.label('search_connector_or')" value="OR" />
                            </v-col>
                        </v-row>
                    </v-radio-group>
                    <!-- Case sensitivity -->
                    <div class="caption mb-n4" v-text="$root.label('search_case')" />
                    <v-radio-group v-model="search.case">
                        <v-row>
                            <v-col>
                                <v-radio :label="$root.label('search_case_insensitive')" value="insensitive" />
                            </v-col>
                            <v-col>
                                <v-radio :label="$root.label('search_case_sensitive')" value="sensitive" />
                            </v-col>
                        </v-row>
                    </v-radio-group>
                    <!-- Search Gender -->
                    <div class="caption mb-n4 mt-1" v-text="$root.label('search_gender')" />
                    <v-select
                        v-model="search.gender"
                        :items="genders"
                        v-on:keyup.enter="setSearchURL()"
                    />
                    <!-- Search Class -->
                    <div class="caption mb-n4 mt-n1" v-text="$root.label('search_class')" />
                    <v-select
                        v-model="search.class"
                        :items="classes"
                        v-on:keyup.enter="setSearchURL()"
                    />
                <!-- Search Button -->
                <v-btn
                    tile
                    block
                    class="mt-4 primary"
                    v-text="$root.label('search')"
                    @click="setSearchURL()"
                />
                <!-- Search Reset -->
                <div class="mt-2 mb-n2 d-flex justify-center">
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
                <instructions />
            </v-card-text>

            <!-- Results -->
            <div v-else>

                <!-- Results Header -->
                <div>

                    <!-- Instructions -->
                    <div style="position: absolute; right: 0;">
                        <v-btn icon large class="ma-1" @click="instructions = true">
                            <v-icon large v-text="'help_outline'" />
                        </v-btn>
                    </div>

                    <!-- Message -->
                    <div
                        class="title text-center pa-2"
                        v-html="loading ? '...' : countMessage()"
                    />
                    <!-- Cite Link -->
                    <v-expand-transition>
                        <div v-if="!loading && citeSearch" class="d-flex justify-center pa-1 mt-n3">
                            <div class="text-truncate caption" v-html="citeSearch" />
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <v-icon
                                        v-on="on"
                                        x-small
                                        class="ml-1"
                                        v-text="'content_paste'"
                                        @click="copyToClipboard(citeSearch)"
                                    />
                                </template>
                                <span v-text="$root.label('clipboard_copy')" />
                            </v-tooltip>
                        </div>
                    </v-expand-transition>

                    <!-- Divider -->
                    <v-progress-linear v-if="loading" indeterminate color="accent" />
                    <div v-else style="border-bottom: 3px solid #b51212;" />

                    <!-- Pagination -->
                    <pagination
                        color="transparent"
                        :pagination="pagination"
                        v-on:navigate="(emit) => { this.setSearchURL(emit) }"
                    ></pagination>
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
                            class="mb-6"
                        >
                            <!-- annotated Name -->
                            <div class="body-1 mb-1" v-html="item.annotated ? parseName(item.annotated) : ''" />
                            <div class="body-2">
                                <!-- Information -->
                                <div class="mt-1" v-html="parseInfo(item)" />
                                <!-- Reference -->
                                <div v-if="item.reference" class="mb-1" v-html="$root.label('reference') + ': ' + item.reference" />
                            </div>
                            <div class="d-flex justify-space-between caption">
                                <!-- Citation -->
                                <div class="d-flex">
                                    <div>
                                        {{ $root.label('cite_item') }}:
                                        <i>
                                            {{ item.id }}
                                            {{ date }}
                                        </i>
                                    </div>
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <v-icon
                                                v-on="on"
                                                x-small
                                                class="ml-1"
                                                v-text="'content_paste'"
                                                @click="copyToClipboard(item.id + ' ' + date)"
                                            />
                                        </template>
                                        <span v-text="$root.label('clipboard_copy')" />
                                    </v-tooltip>
                                </div>
                                <!-- Download-->
                                <div class="d-flex">
                                    <a :href="item.id + '.txt'" download v-text="'TXT'" class="mr-3" />
                                    <a :href="item.id + '.jsonld'" download v-text="'JSON-LD'" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        v-else
                        class="pa-10 text-center"
                        v-text="$root.label('result_none')"
                    />
                </v-expand-transition>

                <!-- Results Footer -->
                <div style="border-top: 3px solid #b51212;">
                    <!-- Pagination -->
                    <pagination
                        color="transparent"
                        :pagination="pagination"
                        v-on:navigate="(emit) => { this.setSearchURL(emit) }"
                    ></pagination>
                </div>
            </div>
        </v-card>
    </div>

</div>
</template>


<script>

export default {
    data () {
        return {
            loading: false,
            filterExpanded: true,
            searchedString: null,
            citeSearch: null,

            search: {
                resource: 'keywords',
                gender: 'all',
                class: 'all',
                string: null,
                connector: 'AND',
                case: 'insensitive'
            },
            searchDefaults: {},

            resources: [
                { value: 'keywords', text: this.$root.label('search_resource_keywords') },
                { value: 'addenda', text: this.$root.label('search_resource_addenda') }
            ],
            classes: [
                { value: 'all', text: this.$root.label('search_class_all') },
                { value: 'normal', text: this.$root.label('search_class_normal') },
                { value: 'eques', text: this.$root.label('search_class_eques') },
                { value: 'senator', text: this.$root.label('search_class_senator') }
            ],
            genders: [
                { value: 'all', text: this.$root.label('search_gender_all') },
                { value: 'female', text: this.$root.label('search_gender_female') },
                { value: 'male', text: this.$root.label('search_gender_male') }
            ],

            items: [],

            instructions: true,
            date: null,

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
        count_formated () { // Beautify result counter
            let count = this.pagination.count

            if (count > 0) {
                if (count > 999999) count = count.toString().substring(0, count.toString().length - 6) + '.' + count.toString().substring(count.toString().length - 6, count.toString().length - 3) + '.' + count.toString().substring(count.toString().length - 3)
                if (count > 999) count = count.toString().substring(0, count.toString().length - 3) + '.' + count.toString().substring(count.toString().length - 3)
                return count
            } else {
                return this.$root.language === 'de' ? 'Keine' : 'No'
            }
        }
    },

    watch: {
        $route(to, from) {
            if (window.location.hash.split('?')[1]) this.runSearch()
        }
    },

    created () {
        Object.keys(this.search).forEach((key) => {
            this.searchDefaults[key] = this.search[key]
        })

        const query = window.location.hash.split('?')[1]
        if (query) {
            Object.keys(this.search).forEach((key) => {
                const value = query.split(key + '=')[1]?.split('&')[0]
                this.search[key] = value ? decodeURI(value) : this.searchDefaults[key]
            })
            this.runSearch()
        }
    },

    methods: {
        setSearchURL (url) {
            const params = {}

            if (url) {
                url.split('?')[1]?.split('&').forEach((param) => {
                    const split = param.split('=')
                    params[split[0]] = decodeURI(split[1])
                })
            }
            else {
                Object.keys(this.search).forEach((key) => {
                    if (this.search[key]) params[key] = this.search[key]
                })
            }

            this.$router.push({ path: '/search', query: params }).catch((error) => { this.runSearch() })
        },

        async runSearch () { // Execute Query
            this.instructions = false
            this.setCiteSearch(null)
            this.items = []

            const params = window.location.hash.split('?')[1] ?? ''
            const fetch = await this.FetchData('/api/website?' + params)

            // Set Pagination
            this.pagination = {
                count: fetch?.pagination?.count ?? 0,
                page: fetch?.pagination?.page ?? 1,
                first: fetch?.pagination?.firstPage ?? null,
                previous: fetch?.pagination?.previousPage ?? null,
                next: fetch?.pagination?.nextPage ?? null,
                last: fetch?.pagination?.lastPage ?? null,
            }
            this.date = '(' + this.$root.label('cite_date') + ' ' + this.$root.formatDate(fetch?.pagination?.requestedAt) + ')'

            const pageOf = fetch?.pagination?.pageOf?.split('?')?.[1] ?? null
            this.setCiteSearch(pageOf && this.pagination.count > 0 ? pageOf : null)

            // Set Items
            this.items = fetch?.contents ?? []
        },

        async FetchData (url) {
            this.loading = true
            this.searchedString = this.search.string ? this.search.string : '-'
            const fetch = await axios.get(url).catch((error) => { console.log(error) })
            this.loading = false
            return fetch ? fetch.data : {}
        },

        ResetFilters () { // Reset Search Form Fields to default
            Object.keys(this.search).forEach((key) => {
                this.search[key] = this.searchDefaults[key]
            })
            if (window.location.hash.split('?')[1]) this.$router.push('/search')
        },

        parseInfo (item) {
            const html = []

            // Basic Information
            const base = []

            if (item.gender) {
                if (item.gender.toLowerCase().substring(0, 1) === 'f') base.push('weiblich')
                else if (item.gender.toLowerCase().substring(0, 1) === 'm') base.push('männlich')
            }

            if (item.class) {
                if (item.class.toLowerCase() === 'senator') base.push('ordo senatorius')
                else if (item.class.toLowerCase() === 'eques') base.push('ordo equester')
            }

            if (base[0]) html.push(base.join(', '))

            // Additional Information
            ;[
                'tribe',
                'origin',
                'lifespan',
                'career',
                'occupations',
                'relatives',
                'article',
                'sources',
                'literature',
                'notes'
            ].forEach((key) => {
                if (item[key]) {
                    html.push(this.$root.label('add_' + key) + ': ' + item[key])
                }
            })

            return html.map((string) => { return '<div class="mb-2">' + string + '</div>' }).join('\n')
        },

        parseName (name) {
            return name
                .replaceAll(/<b>(.*?)<\/b>/g, (match) => { return '<span class="font-weight-medium">' + match + '</span>'})
                .replaceAll(/<k>(.*?)<\/k>/g, (match) => { return '<span class="font-weight-light text-uppercase">' + match + '</span>'})
        },

        setCiteSearch (query) {
            this.citeSearch = query ? ('https://pir.bbaw.de/search?' + query) : null
        },

        countMessage () {
            const id = window.location.hash.split('id=')[1]?.split('&')?.[0] ?? null
            if (id) this.setCiteSearch(null)
            return this.count_formated + ' ' + this.$root.label('records_for') + ' "' + (id ? ('ID ' + id) : this.searchedString) + '"'
        },

        copyToClipboard (value) {
            // create temp element
            var el = document.createElement('textarea');
            el.value = value;
            el.setAttribute('readonly', '');
            el.style = { position: 'absolute', left: '-9999px' };
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            // remove temp element
            document.body.removeChild(el);

            alert(this.$root.label('clipboard_copied'));
        }
    }
}
</script>
