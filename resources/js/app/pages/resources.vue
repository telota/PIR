<template>
<v-card tile class="pa-5">

    <!--<template v-if="$root.language === 'de'">-->
        <h2 class="mb-3">Ressourcen</h2>

        <h3>Stichwortliste</h3>
        <p>
            Zur besseren Strukturierung des gesammelten Materials führt das Unternehmen eine Stichwortliste mit den Namen aller {{ stats.total }} Personen,
            die in der PIR Aufnahme finden (vgl. dazu Überblick; das bedeutet aber beispielsweise:
            Angehörige der munizipalen und provinzialen Oberschicht sind in der Regel nur berücksichtigt,
            wenn familiäre Verbindungen zu römischen Senatoren belegt oder die Bekleidung von Reichsämtern bekannt ist).
            Dieses Personenverzeichnis der Oberschicht des Römischen Reiches (mit den genannten Einschränkungen)
            soll hiermit der Forschung zu einer ersten informierenden Recherche zur Verfügung gestellt werden.
        </p>
        <p>
            Die Eintragungen bestehen jeweils aus den Personennamen und einer Referenz zur eindeutigen Identifizierung der Personen:
            Nummer in PIR2 bzw. PIR1, RE-Artikeln, Quellenpublikation oder anderen Standardwerken.
        </p>
        <p>
            Derzeit setzt sich der Personenkreis der PIR wie folgt zusammen:
        </p>
        <table class="mb-5">
            <tr>
                <td />
                <td
                    v-for="(key) in ['normal', 'eques', 'senator', 'total']"
                    :key="'th' + key"
                    class="font-weight-bold pb-1 pl-5 text-right"
                    v-text="$root.label('search_class_' + key)"
                />
            </tr>
            <tr v-for="(row, key, tr) in stats.dist" :key="'d' + tr">
                <td class="font-weight-bold" v-text="$root.label('search_gender_' + key)" />
                <td
                    v-for="(cell, td) in row"
                    :key="'d' + tr + td"
                    class="pl-5 text-right"
                    v-text="cell"
                />
            </tr>
        </table>
        <p>
            Diese Stichwortliste beansprucht nicht, eine eigenständige und bereits fertige wissenschaftliche Leistung zu sein;
            sie ist primär ein internes Arbeitsmittel, das lediglich der Orientierung dienen soll.
            Fehler, die sich bei einer solchen Dokumentation nie ganz vermeiden lassen, werden korrigiert, wo immer sie bemerkt werden.
            Wenn Sie einen Fehler entdecken oder feststellen, daß eine Person fehlt, schicken Sie der PIR-Arbeitsstelle bitte eine kurze Nachricht.
            Wir sind unsererseits jederzeit bereit, Ihre Fragen zu beantworten, so gut es unser PIR-Material und unsere personelle Situation erlauben.
        </p>
        <p>
            Da das Forschungshaben offiziell als abgeschlossen gilt, erfolgen Aktualisierungen nur in unregelmäßigen Abständen.
            Das letzte Update erfolgte am {{ stats.date }}.
        </p><br/>

        <h3>Addenda</h3>
        <p>
            Die PIR hat eine lange Entstehungszeit. Der erste Band erschien bereits im Jahr 1933, die anderen folgten in mehr oder weniger langem Abstand.
            Vor allem für die ersten Bände ist seit der Veröffentlichung viel neues Quellenmaterial erschienen,
            aber auch die Auseinandersetzung mit dem schon bekannten Material hat zu vielen neuen Erkenntnissen geführt.
            Um die PIR insgesamt für den Benutzer näher an den gegenwärtigen Stand der Forschung heranzuführen,
            werden hier nach der Stichwortliste von nun an in regelmäßigen Abständen addenda zu den gedruckten Bänden der zweiten Auflage veröffentlicht
            und sukzessive dem interessierten Kreis von Fachkollegen zugänglich gemacht.
            Aufgenommen wird im wesentlichen neues Quellenmaterial, und zwar sowohl zu bereits bekannten Personen wie vor allem zu neuen Personen.
            Sekundärliteratur wird nur begrenzt ausgewertet, um den Fortgang der Präsentation nicht zu sehr zu verzögern.
            Aus dem gleichen Grund werden Standardwerke (wie beispielsweise Pflaum Carrières, Devijver PME oder Raepsaet FOS) nur im Einzelfall zitiert,
            sie müssen also prinzipiell unabhängig davon, ob hier zitiert oder nicht, herangezogen werden. Die Einträge bestehen aus
        </p>
        <ul>
            <li>Namen + PIR-Nr. der 2. Auflage</li>
            <li>neuen Quellen. Konkordanzen beschränken sich auf notwendige Fälle.</li>
            <li>Auswahl der substantiell wichtigen Literatur</li>
            <li>knapper kommentierter Auswertung der neuen Quellen</li>
            <li>Bearbeitungsdatum. Dieses sollte vom Benutzer als Hinweis auf den Forschungsstand stets berücksichtigt werden.</li>
        </ul><br/>
        <p>
            Zitiert wird nach den Prinzipien der PIR unter Verwendung des Abkürzungsverzeichnisses des jeweils neuesten Bandes.
            Aus praktischen Gründen hat im laufenden Text das Latein der deutschen Sprache weichen müssen außer bei den termini,
            bei denen aber auf das Griechische verzichtet werden mußte. Der Ausgangspunkt für den Benutzer ist die Stichwortliste,
            von der man mittels link von der einzelnen Person zu den addenda gelangt. Darüber hinaus soll es eine begrenzte Freitextsuche ermöglichen,
            die addenda nach bestimmten Begriffen zu durchsuchen. Es sollte aber immer nach Varianten eines Begriffes
            (etwa Prokonsul - proconsul; praefectus - Präfekt; Moesia inferior - Untermösien - Niedermoesien;
            praefectus Aegypti - Praefekt von Ägypten; Statthalter - legatus Augusti pro praetore - Präses - praeses usw.) gesucht werden.
            Klammern spielen keine Rolle, sie werden bei der Suche unterdrückt.
            Bei der Suche können die selben Platzhalterzeichen wie bei der Stichwortliste verwendet werden.
        </p>
        <p>
            Die Präsentation dieser addenda wird als Versuch gestartet; deshalb wird um möglichst zahlreiche - positive wie negative - Reaktionen gebeten:
            Berichtigung von Fehlern, Verbesserungsvorschläge, Hinweise auf Lücken usw. Die Adresse ist bei jeder Datei angegeben.
            Die Netzeingabe der Einträge erfolgt sukzessiv in nicht zu langen Abständen;
            gleichzeitig werden kontinuierlich Verbesserungen und Zusätze an den bereits zugänglichen Texten vorgenommen.
            Die Zusammenstellung des Materials und Abfassung der einzelnen Einträge erfolgen durch A. Strobach.
            Die Programmierarbeiten und laufende technische Betreuung liegen in der Hand von M. Heil.
            Für die Zitierung dieser addenda in Veröffentlichungen wird folgende Form empfohlen: (Personenname) PIR addenda electronica (Datum).
            Diese addenda stehen als Parergon neben der Fertigstellung der zweiten Auflage der PIR und
            können nur entsprechend den zur Verfügung stehenden zusätzlichen personellen wie finanziellen Mitteln vorangebracht werden.
            Der Anfang wurde durch eine großzügige finanzielle Unterstützung der Heckmann Wenzel-Stiftung ermöglicht.
            Dafür sei an dieser Stelle herzlich gedankt.
        </p>
        <p>
            Berlin, im Juli 2001<br/>
            Werner Eck; Klaus Wachtel
        </p>
        <p>
            <a href="/#/search"><strong>Suche starten</strong></a>
        </p>

    <!--</template>-->

</v-card>
</template>


<script>

export default {
    computed: {
        stats () {
            return this.$root.stats
        }
    }
}

</script>

