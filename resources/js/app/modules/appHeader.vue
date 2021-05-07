<template>
<div
    class="pl-1 pr-1"
    style ="
        position: fixed;
        height: 80px;
        width: 100%;
        background-color: #fefefe;
        border-bottom: 5px solid #b51212;
        z-index: 100;"
>
    <v-row justify="center" align="center">
        <v-col cols="12" sm="10">
            <div class="pt-1" style="position: relative; width: 100%">

                <!-- BBAW Logo -->
                <div style="position: absolute; right: 0">
                    <a href="https://www.bbaw.de" alt="BBAW Homepage" target="_blank">
                        <v-img
                            src="/bbaw-logo.svg"
                            max-height="60"
                            max-width="200"
                            contain
                            style="margin-right: -30px"
                        ></v-img>
                    </a>
                </div>

                <!-- Title -->
                <div
                    class="text-h4 text-truncate"
                    style="font-family: palatio, serif !important; letter-spacing: .1rem !important; cursor: pointer"
                    v-text="$vuetify.breakpoint.mdAndUp ? 'PROSOPOGRAPHIA IMPERII ROMANI' : 'PIR'"
                    @click="$router.push($route.name === 'overview' ? 'search' : 'overview')"
                />

                <!-- Nav-Items -->
                <div v-if="$vuetify.breakpoint.mdAndUp" class="d-flex mt-1" style="z-index: 101">
                    <v-hover
                        v-for="(nav) in navigation"
                        :key="nav"
                        v-slot="{ hover }"
                    >
                        <a
                            :href="'/#/' + nav"
                            class="mr-5"
                            :class="($route.name === nav || hover ? 'accent--text' : 'black--text') + (nav === 'search' ? ' font-weight-bold' : '')"
                            :disabled="$route.name === nav"
                            style="letter-spacing: .05rem !important;"
                            v-text="$root.label('nav_' + nav)"
                        />
                    </v-hover>
                </div>

                <v-menu
                    v-else
                    v-model="navMenu"
                    offset-y
                    open-on-hover
                    close-on-click
                    class="mt-1"
                >
                    <template v-slot:activator="{ on, attrs }">
                        <div
                            v-bind="attrs"
                            v-on="on"
                            class="font-weight-bold"
                            v-text="'Navigation'"
                        />
                    </template>
                    <v-list>
                        <v-list-item v-for="(nav) in navigation" :key="nav">
                            <v-list-item-title
                                :class="($route.name === nav ? 'accent--text' : 'black--text')"
                                style="cursor: pointer"
                                :disabled="$route.name === nav"
                                v-text="$root.label('nav_' + nav)"
                                @click="$router.push(nav); navMenu = false;"
                            />
                        </v-list-item>
                    </v-list>
                </v-menu>

            </div>

        </v-col>
    </v-row>

    <!-- Language
    <div class="d-flex justify-end align-center" style="position: absolute; right: 5px; z-index: 3">
        <v-btn
            icon
            class="title pa-2"
            v-text="$root.language === 'de' ? 'EN' : 'DE'"
            @click="$root.language = $root.language === 'de' ? 'en' : 'de'"
        ></v-btn>
    </div> -->

</div>
</template>

<script>

export default {
    data () {
        return {
            navigation: [
                'search',
                'overview',
                'publications',
                'methods',
                'history',
                'resources',
                'api',
            ],
            navMenu: false
        }
    }
}
</script>
