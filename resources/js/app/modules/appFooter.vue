<template>
<div>

    <!-- Footer -->
    <v-footer
        app
        fixed
        class="primary d-flex white--text caption"
        :class="$vuetify.breakpoint.smAndUp ? 'justify-space-between' : 'justify-center'"
    >
        <div class="text-truncate">
            <a href="https://www.bbaw.de" target="_blank" class="white--text" v-text="$root.label('bbaw')"></a>&ensp;2020&ndash;{{ year }}
        </div>
        <div class="d-flex flex-wrap justify-end">
            <div class="mr-2" style="cursor: pointer" v-text="$root.label('about_header')" @click="dialog.imprint = true"></div>
            <div class="ml-2 mr-2" style="cursor: pointer" v-text="$root.label('license_header')" @click="dialog.license = true"></div>
            <div class="ml-2"><a href="" target="_blank" class="white--text" v-text="$root.label('consent_declaration')"></a></div>
        </div>
    </v-footer>

    <!-- Legal Notice -->
    <v-dialog v-model="dialog.imprint" max-width="500">
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
                    </p>
                    <div
                        class="primary--text"
                        style="cursor: pointer"
                        v-text="$root.label('license_note')"
                        @click="dialog = { imprint: false, license: true }"
                    />
                </div>
                <div class="mb-n3 d-flex justify-center"><v-btn text @click="dialog.imprint = false">Close</v-btn></div>
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
                <div class="mb-n3 d-flex justify-center"><v-btn text @click="dialog.license = false">Close</v-btn></div>
            </v-card-text>
        </v-card>
    </v-dialog>

</div>
</template>

<script>

export default {
    data () {
        return {
            year: new Date().getFullYear(),
            dialog: {
                imprint: false,
                license: false
            },
        }
    }
}
</script>
