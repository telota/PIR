@extends('app.layout')

@section('template')

<!-- Settings -->
<settings
    :data='{!! json_encode($settings) !!}'
></settings>

<!-- Template -->
<template>
    <v-app id="inspire">

        <!-- Loading Screen -->
        <div v-if="loading" class="loader">
            <div class="loader-header">PIR</div>
            <div class="loader-bar"></div>
            <div class="loader-subheader">PROSOPOGRAPHIA IMPERII ROMANI</div>
        </div>

        <!-- Appbar -->
        <div id="pir-header">
            <v-row justify="center" align="center">
                <v-col cols="12" sm="10">
                    <div class="pt-1 d-flex justify-space-between align-center" style="width: 100%">
                        <!-- Navigation -->
                        <div style="width: 100%">
                            <!-- Title -->
                            <div
                                class="text-h4"
                                v-text="$vuetify.breakpoint.mdAndUp ? 'PROSOPOGRAPHIA IMPERII ROMANI' : 'PIR'"
                                style="
                                    font-family: palatio, serif !important;
                                    letter-spacing: .1rem !important;
                                "
                            ></div>
                            <!-- Nav-Items -->
                            <div class="d-flex mt-1" style="overflow-y: auto">
                                <v-hover
                                    v-for="(nav) in navigation"
                                    :key="nav"
                                    v-slot="{ hover }"
                                >
                                    <a
                                        :href="'/#/' + nav"
                                        v-text="label('nav_' + nav)"
                                        class="mr-5"
                                        :class="($route.name === nav ? 'accent--text' : (hover ? 'primary--text' : 'black--text')) + (nav === 'search' ? ' font-weight-bold' : '')"
                                        :disabled="$route.name === nav"
                                        style="
                                            letter-spacing: .05rem !important;
                                        "
                                    ></a>
                                </v-hover>
                            </div>
                        </div>

                        <!-- BBAW Logo -->
                        <div>
                            <a href="https://www.bbaw.de" alte="BBAW Homepage" target="_blank">
                                <v-img
                                    v-if="$vuetify.breakpoint.mdAndUp"
                                    src="/bbaw-logo.svg"
                                    max-height="60"
                                    max-width="200"
                                    contain
                                    style="margin-right: -30px"
                                ></v-img>
                                <div v-else class="title accent--text" v-text="'BBAW'"></div>
                            </a>
                        </div>
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

        <!-- Routed Component -->
        <v-main class="app_bg" style="margin-top: 80px; background: url('/background.jpg')">
            <v-row justify="center">
                <v-col cols="12" sm="10">
                    <v-fade-transition>
                        <router-view class="mt-5 mb-5"></router-view>
                    </v-fade-transition>
                </v-col>
            </v-row>
        </v-main>

        <!-- Tracking Consent -->
        <trackingconsent></trackingconsent>

        <!-- Footer -->
        <v-footer app fixed class="primary d-flex justify-space-between white--text caption">
            <div class="text-truncate">
                <a href="https://www.bbaw.de" target="_blank" class="white--text" v-text="$root.label('bbaw')"></a>&ensp;2020&ndash;{!! date('y') !!}
            </div>
            <div class="d-flex flex-wrap justify-end">
                <div class="mr-5" style="cursor: pointer" v-text="$root.label('about_header')" @click="dialog.about = true"></div>
                <div class="mr-5" style="cursor: pointer" v-text="$root.label('license_header')" @click="dialog.license = true"></div>
                <div><a href="" target="_blank" class="white--text" v-text="$root.label('consent_declaration')"></a></div>
            </div>
        </v-footer>

    </v-app>


    <!-- Legal Notice -->
    <v-dialog v-model="dialog.about" max-width="500">
        <v-card tile>
            <v-card-text>
                <div class="pa-5 title d-flex justify-center" v-text="$root.label('about_header')"></div>
                <div>
                    <p>
                        <b v-text="$root.label('about_published_by')"></b><br />
                        <a
                            href="https://www.bbaw.de"
                            target="_blank"
                            v-text="$root.label('bbaw')"
                        ></a><br />
                        Jägerstraße 22/23<br />
                        DE-10117 Berlin
                    </p><p>
                        <b v-text="$root.label('about_represented_by')"></b><br />
                        Professor Dr. Dr. h. c. mult. Christoph Markschies<br />
                        Tel.: +49 30 20 37 06 45/-20, E-Mail: bbaw(at)bbaw.de
                    </p><p>
                        <b v-text="$root.label('about_legal_status')"></b><br />
                        <span v-text="$root.label('about_legal_entity')"></span>
                    </p><p>
                        <b v-text="$root.label('about_vat')"></b><br />
                        DE 167 449 058 (<span v-text="$root.label('about_vat_note')"></span>)
                    </p><p>
                        <b v-text="$root.label('about_technical')"></b><br />
                        Telota - IT/DH<br />
                        <span v-text="$root.label('bbaw')"></span><br />
                        Jägerstraße 22/23<br />
                        DE-10117 Berlin
                    </p><p>
                        <div class="primary--text" style="cursor: pointer" v-text="$root.label('license_note')" @click="dialog = { about: false, license: true }"></div>
                    </p>
                </div>
                <div class="mb-n3 d-flex justify-center"><v-btn text @click="dialog.about = false">Close</v-btn></div>
            </v-card-text>
        </v-card>
    </v-dialog>


    <!-- Copyright -->
    <v-dialog v-model="dialog.license" max-width="500">
        <v-card tile>
            <v-card-text>
                <div class="pa-5 title d-flex justify-center" v-text="$root.label('license_header')"></div>
                <div>
                    <p>
                        <b v-text="$root.label('license_rd')"></b><br/>
                    </p>
                    <p>
                        <b v-text="$root.label('license_sw')"></b><br />
                        <span v-text="$root.label('license_text')"></span>
                        <a href="http://www.gnu.org/licenses/gpl-3.0.en.html" target="_blank">GPLv3</a>.<br />
                        <span v-text="$root.label('license_author')"></span>:
                        <a href="https://orcid.org/0000-0003-2713-5207" target="_blank">Jan Köster</a><br />
                        <span v-text="$root.label('license_available')"></span>
                        <a href="https://github.com/telota/PIR" target="_blank">Github</a>.
                    </p>
                </div>
                <div class="mb-n3 d-flex justify-center"><v-btn text @click="dialog.license=false">Close</v-btn></div>
            </v-card-text>
        </v-card>
    </v-dialog>

</template>

@endsection
