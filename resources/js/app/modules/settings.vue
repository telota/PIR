<template></template>
<script>

export default {
    props: {
        data: { type: Object },
    },

    created () {
        this.getSettings()
    },

    methods: {
        getSettings () {
            if (!this.data) alert('Error: App didn\'t receive any backend data!')
            else {
                const data = this.data
                console.log('App instance created', data)

                this.$root.language = Object.keys(this.$localization).includes(data.language) ? data.language : 'en'
                this.$root.baseURL = data.baseURL
            }
        }
    }
}
</script>
