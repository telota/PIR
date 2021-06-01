<template>
<div>

    <!--<template v-if="$root.language === 'de'">-->
        <h2 class="mb-3">Suche</h2>

        <h3>Allgemeine Hinweise</h3>

        <p>
            Bitte wählen Sie zunächst, welche <a href="/#/resources">Ressource</a> Sie durchsuchen möchten. Die Stichwortliste umfasst alle
            {{ $root.statistics.total }} Datensätze,
            berücksichtigt bei der Suche aber nur den Namen. Bei der Addenda-Suche handelt es sich hingegen um eine Volltext-Suche,
            die alle Angaben einbezieht. Sie ist aber nur für {{ $root.statistics.addenda }} Datensätze verfügbar.
        </p>
        <p>
            Bitte geben sie die Suchbegriffe (oder Zeichenfolgen) in normaler
            Schrift (also U als U und V als V) und ohne die epigraphischen Klammern ein.<br/>
            Trennen Sie die einzelnen Suchbegriffe bitte durch Blank (= Leerzeichen) ab.
            Für die Suche können Sie auch Platzhalter-Zeichen bzw. reguläre Ausdrücke (REGEX) verwenden.
        </p>
        <p>
            Sie können Ihre Suche zusätzlich mithilfe der Filter auf das Geschlecht bzw. die soziale Gruppenzugehörigkeit einschränken.
            Bitte beachten Sie, dass die höchste soziale Gruppe, die die Person in ihrem Leben erreichte, gilt: z.B. ein Mitglied des equester ordo,
            der in den ordo senatorius aufstieg, wird in der Suche nicht mehr unter equester ordo, sondern nur unter den Senatoren erfasst.
        <p>
            Die Ausgabe der Datensätze erfolgt wie in den gedruckten PIR-Bänden: Senatoren in Versalien,
            Ritter höheren Ranges in Fettdruck, nur teilweise erhaltene Namen mit den üblichen Klammern versehen.<br/>
            Sie können jeden Datensatz im TXT- oder JSON-Format herunterladen.
            Zusätzlich wird ein Link nach dem Schema <code>{{ $root.baseURL }}/id/{id}</code> generiert, z.B.
            <a :href="$root.baseURL + '/id/1'" v-text="$root.baseURL + '/id/1'" />. Bitte verwenden Sie nur diesen Link für die Zitation und nicht die URL,
            wie Sie in Ihrem Browser angezeigt wird, weil sich letztere bedingt durch die technische Weiterentwicklung verändern kann.<br/>
            Analog dazu wird zu jedem Suchvorgang, der mindestens einen Treffer erbrachte, ein Zitierlink erzeugt, der ebenfalls der Browser-URL vorzuziehen ist.
        </p>
        <p>
            Wenn Sie den Datenbestand der PIR maschinell durchsuchen möchten, nutzen Sie bitte die <a href="/#/api">API</a>.
        </p>

        <h3 class="mt-5">Beispiele für reguläre Ausdrücke</h3>

        <table class="mt-5" style="width: 100%">
            <tr>
                <td class="font-weight-bold pb-1" valign="top">ZEICHEN</td>
                <td class="font-weight-bold pb-1" valign="top">BEDEUTUNG</td>
                <td class="font-weight-bold pb-1" valign="top">BEISPIEL</td>
            </tr>
            <tr>
                <td valign="top"><code>.</code></td>
                <td valign="top">ein beliebiges Zeichen (das existieren muß)</td>
                <td valign="top"><code>Ru.ilius</code> findet "Rutilius", "Rupilius" usw.; <code>ius.Ruf</code> findet "Cluvius Rufus" ebenso wie "Memmius Rufinus".</td>
            </tr>
            <tr>
                <td valign="top"><code>?</code></td>
                <td valign="top">Das davorstehende Zeichen darf fehlen.</td>
                <td valign="top"><code>Aurell?ius</code> findet "Aurelius" und "Aurellius".</td>
            </tr>
            <tr>
                <td valign="top"><code>+</code></td>
                <td valign="top">Das davorstehende Zeichen darf beliebig oft wiederholt sein.</td>
                <td valign="top"><code>Mes+al+a</code> findet "Mesala", "Messala", "Mesalla" und "Messalla".</td>
            </tr>
            <tr>
                <td valign="top"><code>*</code></td>
                <td valign="top">Das davorstehende Zeichen darf beliebig oft vorkommen; es darf auch fehlen.</td>
                <td valign="top"><code>Mess*all*a</code> findet "Mesala", "Messala", "Mesalla" und "Messalla".</td>
            </tr>
            <tr>
                <td valign="top"><code>.?</code></td>
                <td valign="top">ein beliebiges Zeichen, das fehlen darf</td>
                <td valign="top"><code>Rutil.?us</code> findet "Rutilius" ebenso wie "Rutilus".</td>
            </tr>
            <tr>
                <td valign="top"><code>.+</code></td>
                <td valign="top">beliebig viele Zeichen (mindestens aber eines)</td>
                <td valign="top"><code>R.+ius</code> findet alle Einträge, in denen ein R vorkommt und irgendwo dahinter die Zeichenfolge "ius", also z. B. "Rutilius" ebenso wie "Aelius Rufus Ianuarius".</td>
            </tr>
            <tr>
                <td valign="top"><code>.*</code></td>
                <td valign="top">beliebig viele Zeichen (einschließlich den Fall, daß dort gar keines steht)</td>
                <td valign="top">ähnlich</td>
            </tr>
            <tr>
                <td valign="top"><code>[pt]</code></td>
                <td valign="top">An dieser Stelle darf eines der in der Klammer angegebenen Zeichen vorkommenden.</td>
                <td valign="top"><code>Ru[pt]ilius</code> findet sowohl "Rupilius" als auch "Rutilius" (aber sonst nichts).</td>
            </tr>
            <tr>
                <td valign="top"><code>[a–z]</code></td>
                <td valign="top">An dieser Stelle darf ein beliebiger Kleinbuchstabe vorkommen.</td>
                <td valign="top">&nbsp;</td>
            </tr>
            <tr>
                <td valign="top"><code>[a–zA–Z0–9]</code></td>
                <td valign="top">An dieser Stelle darf ein beliebiger Kleinbuchstabe, Großbuchstabe oder eine Ziffer vorkommen (aber z. B. kein Blank).</td>
                <td valign="top">&nbsp;</td>
            </tr>
            <tr>
                <td valign="top"><code>[a–z]*</code></td>
                <td valign="top">An dieser Stelle dürfen beliebig viele Kleinbuchstaben vorkommen (es muß dort aber keiner stehen).</td>
                <td valign="top"><code>R[a–z]*ius</code> findet alle Namen, die mit R beginnen und mit –ius enden, aber nicht etwa "Aelius Rufus Ianuarius".</td>
            </tr>
            <tr>
                <td valign="top" class="pr-5" style="white-space: nowrap;"><code>[a–zA–Z0–9]*</code></td>
                <td valign="top">An dieser Stelle dürfen beliebig viele Kleinbuchstaben, Großbuchstaben oder Ziffern vorkommen (aber z. B. keine Blanks).</td>
                <td valign="top">&nbsp;</td>
            </tr>
            <tr>
                <td valign="top"><code>[^u]</code></td>
                <td valign="top">An dieser Stelle darf alles vorkommen außer dem Buchstaben in der Klammer (es dürfen auch verschiedene Buchstaben gleichzeitig negiert werden).</td>
                <td valign="top"><code>R[^u]s</code> findet z. B. "Rosianus", aber nicht "Ruso".</td>
            </tr>
            <tr>
                <td valign="top"><code>…\b</code></td>
                <td valign="top">Die Zeichenfolge vor <code>\b</code> muß an einem Wortende stehen.</td>
                <td valign="top"><code>Arria\b</code> findet "Arria", aber nicht "Arrianus".</td>
            </tr>
            <tr>
                <td valign="top"><code>\b…</code></td>
                <td valign="top">Die Zeichenfolge nach <code>\b</code> muß an einem Wortanfang stehen.</td>
                <td valign="top"><code>\bAr</code> findet "Arrius" und "Arria", aber nicht "Marius".</td>
            </tr>
            <tr>
                <td valign="top"><code>…\B</code></td>
                <td valign="top">Die Zeichenfolge vor <code>\B</code> darf nicht an einem Wortende stehen.</td>
                <td valign="top"><code>Arria\B</code> findet "Arrianus", aber nicht "Arria".</td>
            </tr>
            <tr>
                <td valign="top"><code>\B…</code></td>
                <td valign="top">Die Zeichenfolge vor \B darf nicht an einem Wortanfang stehen.</td>
                <td valign="top"><code>\BAr</code> findet "Marius", aber nicht "Arrius".</td>
            </tr>
            <tr>
                <td valign="top"><code>{1,2}</code></td>
                <td valign="top">Das davorstehende Zeichen muß mindestens einmal und darf höchstens zweimal vorkommen. (Bei dieser Art von Angaben darf auch die Zahl 0 verwendet werden.)</td>
                <td valign="top">&nbsp;</td>
            </tr>
            <tr>
                <td valign="top"><code>{1,}</code></td>
                <td valign="top">Das davorstehende Zeichen muß mindestens einmal vorkommen, darf aber beliebig oft wiederholt sein.</td>
                <td valign="top">&nbsp;</td>
            </tr>
            <tr>
                <td valign="top"><code>[a–z]{1,3}</code></td>
                <td valign="top">Hier muß mindestendens ein Kleinbuchstabe stehen und es dürfen höchstens drei sein.</td>
                <td valign="top"><code>R[a–z]{2,3}ius</code> findet "Raecius", aber nicht "Rutilius".</td>
            </tr>
            <tr>
                <td valign="top"><code>…|…</code></td>
                <td valign="top">Die Zeichen vor und hinter dem senkrechten Strich werden als Alternativen behandelt.</td>
                <td valign="top"><code>Rupilius|Rutilius</code> findet alle Einträge mit "Rupilius" und alle mit "Rutilius".</td>
            </tr>
        </table>
    <!--</template> -->

</div>
</template>

<script>

export default {
}
</script>
