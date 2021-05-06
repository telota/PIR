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

</body>

</html>
