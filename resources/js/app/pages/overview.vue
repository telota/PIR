<template>
<div :class="$vuetify.breakpoint.mdAndUp ? 'd-flex' : ''">

    <div :class="$vuetify.breakpoint.mdAndUp ? 'mr-5' : 'mb-5'" style="width: 100%">
        <v-card tile class="pa-5">

            <!--<template v-if="$root.language === 'de'">-->
                <p>
                    Die Prosopographia Imperii Romani (PIR) ist ein Personenlexikon.<br />
                    Es soll im wesentlichen die Führungsschicht des Römischen Reiches in der Frühen und Hohen Kaiserzeit erfassen.
                    Die untere zeitliche Grenze ist die Schlacht von Aktium 31 v. Chr., seit der die monarchische Herrschaftsstruktur für Rom endgültig geworden war;
                    die obere Grenze bildet die Herrschaft Diokletians (284-305),
                    mit der eine wesentliche Änderung im staatlichen und gesellschaftlichen Aufbau des Reiches durchgesetzt wurde.
                </p>

                <p>Der in dem Nachschlagewerk behandelte Personenkreis besteht im Einzelnen aus:</p>

                <ol>
                    <li>
                        den römischen Kaisern und ihren Familienmitgliedern
                    </li>
                    <li>
                        den Angehörigen des Senatorenstandes (wie etwa P. Quinctilius Varus, der in der Schlacht im Teutoburger Wald umgekommen ist,
                        oder dem Geschichtsschreiber Tacitus und seinem Schwiegervater, Cn. Iulius Agricola)
                    </li>
                    <li>
                        den Römern ritterlichen Ranges, soweit sie Aufgaben in der kaiserlichen Verwaltung übernommen hatten
                        (wie beispielsweise dem Präfekten von Iudaea Pontius Pilatus oder dem älteren Plinius,
                        der beim Vesuvausbruch des Jahres 79 n. Chr. als Präfekt der Flotte von Misenum umkam) und ihren Angehörigen
                    </li>
                    <li>
                        allen literarisch für die beschriebene Zeit belegten Personen, soweit sie nicht schon zu einer der bereits aufgeführten Personengruppen gehörten;
                        unter ihnen befinden sich natürlich auch die Dichter und Schriftsteller selbst wie etwa Vergil, Horaz, Ovid und Martial oder Pausanias, Plotin und Plutarch. <br />
                        Aufgenommen sind auch die Angehörigen der regierenden Familien in den von Rom abhängigen Klientelstaaten bzw. unabhängiger Reiche, soweit sie mit Rom in Kontakt standen,
                        so etwa Herodes d. Gr. in Iudaea oder die Könige des Partherreiches.
                    </li>
                </ol>
            <!--</template>-->

        </v-card>
    </div>

    <div :style="'width: ' + ($vuetify.breakpoint.mdAndUp ? '400px' : '100%')">
        <v-card tile class="pa-5">
            <v-img src="/img/mil_dipl.gif" />
            <div>
                <div class="font-weight-medium mb-1">Militärdiplom</div>
                <div class="caption">
                    Umzeichnung eines sogenannten Militärdiplom (Original: Bronze) mit  wichtigen prosopographischen Nachrichten zu den ersten Statthaltern der Provinz Dacia.
                </div>
            </div>
        </v-card>
    </div>

</div>
</template>


<script>

export default {}

</script>

