@extends('app.layout')

@section('template')

<!-- Session -->
<sessioninfo :data='{!! json_encode(['language' => $language]) !!}'></sessioninfo>

<!-- Template -->
<template>
    <v-app id="inspire">

        <!-- Loading Screen -->
        <div v-if="loading" class="loader">
            <div class="loader-header">PIR</div>
            <div class="loader-bar"></div>
            <div class="loader-subheader">PROSOPGRAPHIA IMPERII ROMANI</div>
        </div>

        <!-- Appbar -->
        <v-app-bar
            app
            fixed
            style="background-color: #fefefe"
        >
            <v-row justify="center" align="center">
                <v-col cols="12" sm="10">
                    <!-- PIR -->
                    <div
                        class="title text-center pt-2"
                        v-text="$vuetify.breakpoint.mdAndUp ? 'PROSOPGRAPHIA IMPERII ROMANI' : 'PIR'"
                        style="position: absolute; left: 0; right: 0;"
                    ></div>
                    <div class="d-flex justify-space-between" style="width: 100%;">
                        <!-- PIR Logo -->
                        <div>

                        </div>
                        <!-- BBAW Logo -->
                        <div>
                            <a href="https://www.bbaw.de" alte="BBAW Homepage" target="_blank">
                                <v-img
                                    v-if="$vuetify.breakpoint.mdAndUp"
                                    src="/bbaw-logo.svg"
                                    max-height="45"
                                    max-width="150"
                                    contain
                                ></v-img>
                                <div v-else class="title accent--text pr-8 pt-1" v-text="'BBAW'"></div>
                            </a>
                        </div>
                    </div>
                </v-col>
            </v-row>
            <!-- Language -->
            <div class="d-flex justify-end align-center" style="position: absolute; right: 5px; z-index: 3">
                <v-btn
                    icon
                    class="title pa-2"
                    v-text="$root.language === 'de' ? 'EN' : 'DE'"
                    @click="$root.language = $root.language === 'de' ? 'en' : 'de'"
                ></v-btn>
            </div>
        </v-app-bar>

        <!-- Routed Component -->
        <v-main class="app_bg" style="background: url('/background.jpg')">
            <div style="border-top: 5px solid #b51212;">
                <v-fade-transition>
                    <router-view class="mt-3"></router-view>
                </v-fade-transition>
            </div>
        </v-main>

        <!-- Tracking Consent -->
        <v-card
            v-if="consent === null"
            tile
            style="position: fixed; bottom: 50px; right: 20px; width: 250px"
        >
            <div class="mr-1 mt-1 d-flex justify-end">
                <v-icon small @click="consent = false">clear</v-icon>
            </div>
            <v-card-text class="caption text-justify mt-n3">
                <span v-text="$root.label('consent_note')"></span>
                <a
                    href=""
                    target="_blank"
                    class="font-weight-bold"
                    style="text-decoration: none"
                    v-text="$root.label('consent_declaration')"
                ></a>.
            </v-card-text>
            <v-card-actions class="pt-0">
                <v-spacer></v-spacer>
                <v-btn small text v-text="$root.label('decline')" class="bar_prim--text" @click="consent = false"></v-btn>
                <v-spacer></v-spacer>
                <v-btn small text v-text="$root.label('accept')" @click="consent = true"></v-btn>
                <v-spacer></v-spacer>
            </v-card-actions>
        </v-card>


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
                       PIR App<br />
                        <a href="https://www.apache.org/licenses/LICENSE-2.0.html" target="_blank">Apache&nbsp;Software, License Version 2.0</a><br />
                        <span v-text="$root.label('license_available')"></span>
                        <a href="https://github.com/telota/PIR" target="_blank">Github</a><br />
                        <span v-text="$root.label('license_author')"></span>: <a href="https://orcid.org/0000-0003-2713-5207" target="_blank">Jan Köster</a>
                    </p>
                </div>
                <div class="mb-n3 d-flex justify-center"><v-btn text @click="dialog.license=false">Close</v-btn></div>
            </v-card-text>
        </v-card>
    </v-dialog>

</template>

@endsection
