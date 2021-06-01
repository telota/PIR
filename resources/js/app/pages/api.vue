<template>
<v-card tile class="pa-5">

    <!--<template v-if="$root.language === 'de'">-->
        <h3>API</h3>
        <p>
            Die API bietet Ihnen die Möglichkeit, die PIR-Datensätze machinell zu durchsuchen und auszulesen.<br/>
            Es stehen die gleichen Kritieren wie in der Suchemaske zur Verfügung. Sie müssen sie lediglich als GET-Parameter übergeben:<br/>
            Wenn sie z.B. den Parameter <code>string</code> mit dem Wert <code>caesar</code> an die API-URL
            (<code>{{ $root.baseURL }}/api?string=caesar</code>)
            übergeben,
            erhalten Sie alle Datensätze, deren Name diesen String enthält.
        </p>
        <p>Sie können die folgenden Parameter übergeben:</p>
        <table v-if="!loading && params" class="ml-5 mb-5">
            <tr>
                <td class="font-weight-medium pr-5 pb-1" v-text="'Parameter'" />
                <td class="font-weight-medium pr-5 pb-1" v-text="'Erlaubte Werte'" />
            </tr>
            <tr v-for="(val, key) in params" :key="key">
                <td class="pr-5 pb-1" v-html="'<code>' + key + '</code>'" />
                <td class="pr-5 pb-1" v-html="val" />
            </tr>
        </table>
        <p>Die API gibt ein JSON-Object mit 3 Keys zurück:</p>
        <ul>
            <li>
                <code>meta</code> enthält Angaben zum Service.
            </li>
            <li>
                <code>pagination</code> enthält Angaben zur Orientierung für den Crawler, wie <code>offset</code>, <code>limit</code>
                oder <code>count</code> für die Anzahl der Treffer.
                Mit <code>firstPage</code>, <code>previousPage</code>, <code>nextPage</code> und <code>lastPage</code>,
                können Sie direkt zwischen den Seiten wechseln, ohne selbst Offset und Limit berechnen zu müssen.
            </li>
            <li>
                Die gefundenen Datensätze finden sie schließlich als einfaches Array in <code>contents</code>.
                Es handelt sich um Links auf die Datensätze im JSON-Format.
            </li><br/>
        </ul>
        <p>
            Möchten Sie einen Datensatz direkt über seine ID aufrufen, müssen Sie nicht den Umweg über die API nehmen.<br/>
            Sie können ihn direkt mit <code>{{ $root.baseURL }}/id/{id}.json</code> aufrufen, z.B.
            <a :href="$root.baseURL + '/id/1.json'" target="_blank">{{ $root.baseURL }}/id/1.json</a>.
        </p>
        <p>
            <a href="/api" target="_blank"><strong>Zur API wechseln</strong></a>
        </p>

    <!--</template>-->

</v-card>
</template>


<script>

export default {
    data () {
        return {
            loading: false,
            params: {}
        }
    },

    async created () {
        this.loading = true
        const fetch = await axios.get('/api').catch((error) => { console.log(error) })
        const params = fetch?.data?.meta?.operatingInstructions?.availableParameters

        if (params) {
            Object.keys(params).forEach((key) => {
                if (key === 'string') this.params[key] = 'Beliebiger String (mehrere Strings mit Leerzeichen getrennt)'
                else this.params[key] = params[key].replaceAll('[', '').replaceAll(']', '').split(', ').map((item) => '<code>' + item + '</code>').join('&ensp;')
            })
        }

        this.loading = false
    }
}

</script>

