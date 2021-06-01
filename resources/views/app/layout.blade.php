<!DOCTYPE html>
<html lang="{{ $settings['language'] }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Prosopographia Imperii Romani</title>

    <!-- Styles -->
    {{--<link rel="shortcut icon" href="favicon.ico">--}}
    <link rel="icon" type="image/png" href="/favicon.png" sizes="96x96">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons" rel="stylesheet">

    <!-- App JS -->
    <script type="application/javascript">
        var LSK_APP = {};
        LSK_APP.APP_URL = '{{env('APP_URL')}}';
    </script>

<!-- Matomo -->
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', (event) => {
        var decodedCookie = decodeURIComponent(document.cookie);
        // Check if cookies contain matomo cookie, remove dialog if yes
        if (decodedCookie && decodedCookie.includes('mtm_cookie_consent=')) {
            console.log('Motomo Cookie detected: Tracking Consent given');
            removeTrackingDialog();
            enableTracking();
        }
        // Add Clickevents to Dialog
        else {
            console.log('No Motomo Cookie detected: Tracking Consent required');
            var btnDecline = document.getElementById("tracking-consent-decline")
            if (btnDecline) btnDecline.onclick = removeTrackingDialog;
            var btnAccept = document.getElementById("tracking-consent-accept")
            if (btnAccept) btnAccept.onclick = enableTracking;
        }

        function removeTrackingDialog () {
            var dialog = document.getElementById("tracking-consent-banner")
            if (dialog) dialog.remove();
        }

        function enableTracking () {
            console.log('Tracking Consent given')
            var _paq = window._paq = window._paq || [];
            _paq.push(['trackPageView']);
            _paq.push(['enableLinkTracking']);
            _paq.push(['rememberCookieConsentGiven']);
            (function() {
                var u="https://piwik.bbaw.de/";
                _paq.push(['setTrackerUrl', u+'matomo.php']);
                _paq.push(['setSiteId', '46']);
                var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
                g.type='text/javascript'; g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
            })();
            removeTrackingDialog();
        }
    })
</script>
<noscript><p><img src="https://piwik.bbaw.de/matomo.php?idsite=46&amp;rec=1" style="border:0;" alt="" /></p></noscript>
<!-- End Matomo Code -->

</head>

<body>

    <!-- Initial Loading Screen -->
    <div class="loader">
        <div v-if="loading" class="loader">
            <div class="loader-header">PIR</div>
            <div class="loader-bar"></div>
            <div class="loader-subheader">PROSOPGRAPHIA IMPERII ROMANI</div>
        </div>
        <div class="loader-footer">
            <p>
                PIR&ensp;|&ensp;TELOTA - IT/DH<br/>
                Berlin-Brandenburg Academy of Sciences and Humanities<br/>
                2021{!! date('y') > 21 ? ('&ndash;'.date('y')) : ('') !!}
            </p>
        </div>
    </div>

    <!-- Vue App -->
    <div id="app">
        @yield('template')
    </div>

    <!-- App JS -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Tracking Consent -->
    <div id="tracking-consent-banner">
        <div id="tracking-consent-text">
            Zur Verbesserung unseres Webangebots nutzen wir <a href="https://matomo.org/tracking-personal-data-with-matomo/" target="_blank">Matomo</a>.
            Bitten gestatten Sie uns, einen entsprechenden Cookie zu setzen.
            Nähreres entnehmen Sie bitte unserer <a href="https://dschutz.bbaw.de/" target="_blank">Datenschutzerklärung</a>.
        </div>
        <div id="tracking-consent-btns">
            <button id="tracking-consent-decline" class="tracking-consent-btn">
                Ablehnen
            </button>
            <button id="tracking-consent-accept" class="tracking-consent-btn">
                Akzeptieren
            </button>
        </div>
    </div>

</body>

</html>
