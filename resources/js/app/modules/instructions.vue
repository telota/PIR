<template>
<div>

    <!--<template v-if="mode === 'keywords'">-->
        <div v-if="$root.language === 'de'">
            <h3>Erläuterungen zur Suche</h3>

            <p>
                Bitte geben sie die Suchbegriffe (oder Zeichenfolgen) in normaler
                Schrift und ohne die epigraphischen Klammern ein (also U als U und V als V).
            </p>
            <p>
                Die Ausgabe erfolgt dann wie in den gedruckten PIR-Bänden: Senatoren in Versalien,
                Ritter höheren Ranges in Fettdruck, nur teilweise erhaltene Namen mit den üblichen Klammern versehen.
            </p>
            <p>
                Trennen Sie die einzelnen Suchbegriffe bitte durch Blank (= Leerzeichen) ab.
                Für die Suche können Sie auch Platzhalter-Zeichen ("Joker" oder "wildcards") verwenden.
            </p>

            <h3>Platzhalter–Zeichen</h3>

            <p>
                Wenn Sie einen einzelnen Begriff (d. h. einen Namensteil wie <code>Rufus</code>) suchen,
                geben Sie einfach Ihr Suchwort ein und starten den Suchvorgang.
            </p>
            <p>
                Sie dürfen den Suchbegriff nach Belieben links und rechts trunkieren (d. h. abschneiden).
                Die Suche vergleicht nur die Buchstabenfolge, die Sie eingegeben haben, mit den Buchstabenfolgen,
                die es im Namensverzeichnis der PIR findet.
                Wenn sie also <code>Quintil</code> eingeben, findet das Programm "Quintilius", "Quintillus", "Quintilia", "Quintilianus" usw.
                (und entsprechend bei <code>pilius</code> sowohl "Rupilius" als auch "Popilius").
            </p>
            <p>
                Sie können wählen, ob die Suche Groß– und Kleinschreibung beachten oder ignorieren soll.
                Im ersten Fall gibt das Programm für den Suchbegriff <code>Pius</code> nur die Einträge mit "Pius" aus, andernfalls aber beispielsweise auch "Ulpius".
            </p>
            <p>
                Etwas weniger einfach ist es, wenn Sie innerhalb Ihres Suchworts Zeichen unbestimmt lassen wollen.
                Denn hier kommt es auf Ihre genaue Fragestellung an.
            </p>
            <p>
                Wenn Sie einen einzelnen Buchstaben unbestimmt lassen wollen, geben Sie an dieser Stelle einfach <code>.</code> (einen Punkt) ein.
                Wenn Sie offenlassen wollen, ob an einer Stelle überhaupt ein Buchstabe steht, geben Sie <code>.?</code> (Punkt und Fragezeichen) ein.
                Wenn Sie den ganzen Mittelteil eines Wortes unbestimmt lassen wollen, geben Sie an der Stelle bitte <code>[a–z]*</code> ein;
                dies steht für beliebig viele Kleinbuchstaben. Beim Suchbegriff <code>R[a–z]*ius</code> werden alle Namen gefunden,
                die mit R beginnen und auf –ius enden (also im wesentlichen die Gentilicia mit R).
            </p>
            <p>
                Es gibt noch viele weitere Möglichkeiten, wie Sie sehr gezielte Abfragen formulieren können.
                Hierzu finden Sie im Folgenden eine tabelarische Übersicht.
            </p>

            <table class="mt-10" style="width: 100%">
                <tr>
                    <td class="font-weight-bold" valign="top">ZEICHEN</td>
                    <td class="font-weight-bold" valign="top">BEDEUTUNG</td>
                    <td class="font-weight-bold" valign="top">BEISPIEL</td>
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
                    <td valign="top"><code>[a–zA–Z0–9]*</code></td>
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
        </div>
    <!--</template>

    <template v-else-if="mode === 'addenda'">
    </template>-->

</div>
</template>

<script>

export default {
}
</script>
