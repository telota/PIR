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

        <!-- Header -->
        <appheader></appheader>

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
        <appfooter></appfooter>

    </v-app>
</template>

@endsection
