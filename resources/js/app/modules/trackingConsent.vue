<template>
<div>

    <!-- Tracking Consent -->
    <v-card
        v-if="showDialog"
        tile
        style="position: fixed; bottom: 80px; right: 20px; width: 250px; z-index: 500"
    >
        <div class="mr-1 mt-1 d-flex justify-end">
            <v-icon small @click="decideTracking(false)">clear</v-icon>
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

        <div class="d-flex justify-center pa-2">
            <v-btn
                small
                text
                v-text="$root.label('decline')"
                class="bar_prim--text"
                @click="decideTracking(false)"
            />
            <v-btn
                small
                text
                v-text="$root.label('accept')"
                @click="decideTracking(true)"
            />
        </div>
    </v-card>

</div>
</template>


<script>

export default {

    data () {
        return {
            consentGiven: false,
            showDialog: false
        }
    },

    created () {
        this.checkConsent()
    },

    methods: {

        checkConsent () {
            const decodedCookie = decodeURIComponent(document.cookie)

            // Check if cookies contain matomo cooky, show dialog if not
            if (decodedCookie.includes('mtm_cookie_consent=')) {
                console.log('Tracking Consent given')
                this.consentGiven = true
            }
            else this.showDialog = true

            //this.showDialog = true
        },

        decideTracking (input) {
            // Set Matomo Cooky if use clicks on accept
            if (input) {
                //window._paq.push(['rememberCookieConsentGiven'])
                this.consentGiven = true
            }

            // Hide dialog anyway
            this.showDialog = false
        },
    }
}
</script>
