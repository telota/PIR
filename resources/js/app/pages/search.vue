<template>
<div>

    <!-- Query Form -->
    <v-row justify="center">
        <v-col cols=12 sm=4 md=3>
            <v-card tile>
                <v-card-text>
                    <!-- Filters -->
                    <v-select
                        v-model="search.mode"
                        :items="modes"
                        :label="$root.label('search_mode')"
                        @change="$router.push('/search/' + search.mode)"
                    />
                    <v-text-field
                        v-model="search.string"
                        :label="$root.label('search_string')"
                    />
                    <div class="d-flex justify-center flex-wrap">
                        <v-checkbox
                            v-model="search.isOr"
                            :label="$root.label('search_isOr')"
                            class="mr-5"
                        />
                        <v-checkbox
                            v-model="search.isCs"
                            :label="$root.label('search_isCs')"
                        />
                    </div>
                    <!-- Search Button -->
                    <v-btn
                        tile
                        block
                        class="mt-5 primary"
                        v-text="$root.label('search')"
                        @click="RunSearch()"
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
        </v-col>

        <v-col cols=12 sm=6 md=7>
            <v-card tile :loading="loading">
                <v-card-title>
                    {{ mode === 'keyword' ? 'Stichwort-' : 'Addenda-'}}Suche
                </v-card-title>
                <v-card-text>
                    <template v-if="!items[0] && mode === 'keyword'">
                        <p>
                            Bitte geben sie die Suchbegriffe (oder Zeichenfolgen, "strings") in normaler
                            Schrift und ohne die epigraphischen Klammern ein (also U als U und V als V).
                        </p>
                        <p>
                            Die Ausgabe erfolgt dann wie in den gedruckten PIR-Bänden: Senatoren in Versalien,
                            Ritter höheren Ranges in Fettdruck, nur teilweise erhaltene Namen mit den üblichen Klammern versehen.
                        </p>
                        <p>
                            Trennen Sie die einzelnen Suchbegriffe ("strings") bitte durch Blank (= Leerzeichen) ab.
                            Für die Suche können Sie auch Platzhalter-Zeichen ("Joker" oder "wildcards") verwenden.
                        </p>
                    </template>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>

    <!-- Instructions -->
    <v-dialog
        v-model="instructions.active"
        :max-width="$vuetify.breakpoint.lgAndUp ? '50%' : '67%'"
        scrollable
        :fullscreen="$vuetify.breakpoint.smAndDown"
    >
        <v-card tile>
            <!-- Header -->
            <div class="d-flex align-center justify-space-between">
            <div class="font-weight-bold caption ml-3" v-html="$root.label('abbreviations')"></div>
            <v-btn text depressed small @click="abbreviations.active = false">
                <v-icon small v-text="'clear'"></v-icon>
            </v-btn>
            </div>

            <div style="border-bottom: 2px solid #b51212; background-color: #fefefe"></div>

            <v-card-title class="bar_prim">

            </v-card-title>

            <v-card-text class="pt-5" :style="$vuetify.breakpoint.smAndDown ? '' : 'height: 500px'">

            </v-card-text>
        </v-card>
    </v-dialog>

</div>
</template>


<script>

export default {
  data () {
    return {
      loading: false,
      filterExpanded: true,
      searched: false,
      no_result: '&ensp;',

      search: {
          mode: 'keyword',
          string: null,
          isOr: false,
          isCs: false,
      },

      modes: [
          { value: 'keyword', text: this.$root.label('search_mode_keyword') },
          { value: 'addenda', text: this.$root.label('search_mode_addenda') }
      ],

      items: [],
      itemsDetails: {},
      itemsExpanded: [],

      instructions: {
        active: false,
        search: ''
      },
      queryRefresh: 0,
      queryDialog: false,

      pagination: {
        offset: 0,
        limit: 50,
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
    api () {
        return '/api'
    },

    mode () {
        return this.$route.params.mode ?? 'keyword'
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
      } else { return null }
    }
  },

  created () {
      this.search.mode = this.mode
  },

  methods: {
    OpenNewBrowserTab (url) { // Handler for Links to external resources
      window.open(url, '_blank')
    },

    async RunSearch () { // Execute Query
        this.searched = true
      this.itemsExpanded = []
      this.items = []
      this.itemsDetails = {}
      const fetch = await this.FetchData(this.BuildFetchURL())
      // Check if result
      if (fetch?.contents?.[0]) {
        // this.filterExpanded = false
      }
      else {
        this.no_result = this.$root.label('no_records')
        setTimeout(() => { this.no_result = '&ensp;' }, 4000)
      }
      // JK: Set Pagination
      this.pagination = {
        count: fetch.pagination.count,
        page: fetch.pagination.page,
        first: fetch.pagination.firstPage,
        previous: fetch.pagination.previousPage,
        next: fetch.pagination.nextPage,
        last: fetch.pagination.lastPage,
      }
      console.log(this.pagination)
      // JK: Set Items
      this.items = fetch.contents
      console.log(this.items)
    },

    BuildFetchURL () { // Constructor for API Call
      const self = this
      let url = this.api
      const params = []
      // Iterate over query Keys
      this.queryKeys.forEach((key) => {
        if (self.query[key]) {
          const value = self.query[key] === true ? 1 : encodeURI(self.query[key])
          params.push(key + '=' + value)
        }
      })
      if (params[0]) {
        url = url + '?' + params.join('&')
      }
      return url
    },

    async Navigate (url) { // Method for Navigation Elements
      this.itemsExpanded = []
      this.items = []
      this.itemsDetails = {}
      const fetch = await this.FetchData(url)
      // Set Pagination
      this.pagination = {
        count: fetch.pagination.count,
        page: fetch.pagination.page,
        first: fetch.pagination.firstPage,
        previous: fetch.pagination.previousPage,
        next: fetch.pagination.nextPage,
        last: fetch.pagination.lastPage,
      }
      console.log(this.pagination)
      // Set Result Items
      this.items = fetch.contents
      console.log(this.items)
    },

    async FetchData (url) { // Axios Fetch to given URL
      this.loading = true
      const fetch = await axios.get(url).catch((error) => { console.log(error) })
      this.loading = false
      return fetch ? fetch.data : {}
    },

    async ToggleItem (id, url) { // Toggle Details in Result List
      if (!this.itemsExpanded.includes(id)) {
        const fetch = await this.FetchData(url)
        //this.itemsDetails[id] = fetch?.contents ? fetch.contents[0] : {}
        this.itemsDetails[id] = fetch?.contents ? this.processSingleItem(fetch.contents[0]) : {}
        this.itemsExpanded.push(id)
      }
      else {
        this.itemsExpanded.splice(this.itemsExpanded.indexOf(id), 1)
        delete this.itemsDetails[id]
      }
    },

    ResetFilters () { // Reset Search Form Fields to empty
      const self = this
      //if (this.$route.name !== 'search') { this.$router.push({ name: 'search' }) }
      this.queryKeys.forEach((key) => { self.query[key] = null })
      ++this.queryRefresh
    },
  }
}
</script>

<style>
  a {
    text-decoration: none !important;
  }
</style>
