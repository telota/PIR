<template>
<div>

    <!--<template v-if="mode === 'keywords'">-->
        <div v-if="$root.language === 'de'">
            <p class="title">Stichwort-Suche</p>

            <p>
                Bitte geben sie die Suchbegriffe (oder Zeichenfolgen, "strings") in normaler
                Schrift und ohne die epigraphischen Klammern ein (also U als U und V als V).
            </p>
            <p>
                Die Ausgabe erfolgt dann wie in den gedruckten PIR-Bänden: Senatoren in Versalien,
                Ritter höheren Ranges in Fettdruck, nur teilweise erhaltene Namen mit den üblichen Klammern versehen.
            </p>
            <p>
                Trennen Sie die einzelnen Suchbegriffe ("strings") bitte durch Blank (= Leerzeichen) ab.
                Für die Suche können Sie auch Platzhalter-Zeichen ("Joker" oder "wildcards") verwenden.
            </p>

            <p class="title">Erläuterungen zu den Platzhalter–Zeichen</p>

            <p>
                Wenn Sie nur nach einem einzelnen Namensteil suchen, werden Sie die folgende Seite nicht benötigen;
                geben Sie den Namensteil in die Maske ein und starten Sie die Suche.
            </p>
            <p>
                Oft stellen sich aber wesentlich kompliziertere Fragen (jedenfalls geht es uns so).
                Daher wurde das Suchprogramm so eingerichtet, daß es möglichst viele Wünsche zufriedenstellt.
                Ein vielseitiges und flexibles Instrumentarium ist aber notwendigerweise ein bißchen kompliziert.
                Daher sollen zunächst die wichtigsten Suchmöglichkeiten vorgestellt werden.
                Es folgt dann eine Tabelle mit den (wichtigsten) Platzhalter–Zeichen, die sie verwenden dürfen.
            </p>
            <p>
                Wenn Sie einen einzelnen Begriff (d. h. einen Namensteil wie Rufus) suchen:
                Geben Sie einfach Ihr Suchwort ein und starten den Suchvorgang.
            </p>
            <p>
                Sie dürfen den Suchbegriff nach Belieben links und rechts trunkieren (d. h. abschneiden).
                Das Suchprogramm vergleicht nur die Buchstabenfolge, die Sie eingegeben haben, mit den Buchstabenfolgen, die es im Namensverzeichnis der PIR findet.
                Wenn sie also Quintil eingeben, findet das Programm Quintilius, Quintillus, Quintilia, Quintilianus usw.
                (und entsprechend bei pilius sowohl Rupilius als auch Popilius).
            </p>
            <p>
                Sie können wählen, ob das Suchprogramm Groß– und Kleinschreibung unterscheiden soll oder nicht.
                Wenn sie `Beachten` wählen, gibt das Programm beim Suchbegriff Pius nur die Einträge mit "Pius" aus, andernfalls aber etwa auch "Ulpius".
            </p>
            <p>
                Etwas weniger einfach ist es, wenn Sie innerhalb Ihres Suchworts Zeichen unbestimmt lassen wollen.
                 Denn hier kommt es auf Ihre genaue Fragestellung an.
            </p>
            <p>
                Wenn Sie einen einzelnen Buchstaben unbestimmt lassen wollen, geben Sie an dieser Stelle einfach . (einen Punkt) ein.
                Wenn Sie offenlassen wollen, ob an einer Stelle überhaupt ein Buchstabe steht, geben Sie .? (Punkt und Fragezeichen) ein.
                Wenn Sie den ganzen Mittelteil eines Wortes unbestimmt lassen wollen, geben Sie an der Stelle bitte [a–z]* ein;
                dies steht für beliebig viele Kleinbuchstaben. Beim Suchbegriff R[a–z]*ius werden alle Namen gefunden,
                die mit R beginnen und auf –ius enden (also im wesentlichen die Gentilicia mit R).
            </p>
            <p>
                Es gibt noch viele weitere Möglichkeiten, wie Sie sehr gezielte Abfragen formulieren können. Vergleichen Sie dazu bitte die Tabelle.
            </p>

            <table class="mt-10" style="width: 100%">
                <tr>
                    <td class="font-weight-bold" valign="top">ZEICHEN</td>
                    <td class="font-weight-bold" valign="top">BEDEUTUNG</td>
                    <td class="font-weight-bold" valign="top">BEISPIEL</td>
                </tr>
                <tr>
                    <td valign="top"><var>.</var></td>
                    <td valign="top">ein beliebiges Zeichen (das existieren muß)</td>
                    <td valign="top"><var>Ru.ilius</var> findet "Rutilius", "Rupilius" usw.; <var>ius.Ruf</var> findet "Cluvius Rufus" ebenso wie "Memmius Rufinus".</td>
                </tr>
                <tr>
                    <td valign="top"><var>?</var></td>
                    <td valign="top">Das davorstehende Zeichen darf fehlen.</td>
                    <td valign="top"><var>Aurell?ius</var> findet "Aurelius" und "Aurellius".</td>
                </tr>
                <tr>
                    <td valign="top"><var>+</var></td>
                    <td valign="top">Das davorstehende Zeichen darf beliebig oft wiederholt sein.</td>
                    <td valign="top"><var>Mes+al+a</var> findet "Mesala", "Messala", "Mesalla" und "Messalla".</td>
                </tr>
                <tr>
                    <td valign="top"><var>*</var></td>
                    <td valign="top">Das davorstehende Zeichen darf beliebig oft vorkommen; es darf auch fehlen.</td>
                    <td valign="top"><var>Mess*all*a</var> findet "Mesala", "Messala", "Mesalla" und "Messalla".</td>
                </tr>
                <tr>
                    <td valign="top"><var>.?</var></td>
                    <td valign="top">ein beliebiges Zeichen, das fehlen darf</td>
                    <td valign="top"><var>Rutil.?us</var> findet "Rutilius" ebenso wie "Rutilus".</td>
                </tr>
                <tr>
                    <td valign="top"><var>.+</var></td>
                    <td valign="top">beliebig viele Zeichen (mindestens aber eines)</td>
                    <td valign="top"><var>R.+ius</var> findet alle Einträge, in denen ein R vorkommt und irgendwo dahinter die Zeichenfolge "ius", also z. B. "Rutilius" ebenso wie "Aelius Rufus Ianuarius".</td>
                </tr>
                <tr>
                    <td valign="top"><var>.*</var></td>
                    <td valign="top">beliebig viele Zeichen (einschließlich den Fall, daß dort gar keines steht)</td>
                    <td valign="top">ähnlich</td>
                </tr>
                <tr>
                    <td valign="top"><var>[pt]</var></td>
                    <td valign="top">An dieser Stelle darf eines der in der Klammer angegebenen Zeichen vorkommenden.</td>
                    <td valign="top"><var>Ru[pt]ilius</var> findet sowohl "Rupilius" als auch "Rutilius" (aber sonst nichts).</td>
                </tr>
                <tr>
                    <td valign="top"><var>[a–z]</var></td>
                    <td valign="top">An dieser Stelle darf ein beliebiger Kleinbuchstabe vorkommen.</td>
                    <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td valign="top"><var>[a–zA–Z0–9]</var></td>
                    <td valign="top">An dieser Stelle darf ein beliebiger Kleinbuchstabe, Großbuchstabe oder eine Ziffer vorkommen (aber z. B. kein Blank).</td>
                    <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td valign="top"><var>[a–z]*</var></td>
                    <td valign="top">An dieser Stelle dürfen beliebig viele Kleinbuchstaben vorkommen (es muß dort aber keiner stehen).</td>
                    <td valign="top"><var>R[a–z]*ius</var> findet alle Namen, die mit R beginnen und mit –ius enden, aber nicht etwa "Aelius Rufus Ianuarius".</td>
                </tr>
                <tr>
                    <td valign="top"><var>[a–zA–Z0–9]*</var></td>
                    <td valign="top">An dieser Stelle dürfen beliebig viele Kleinbuchstaben, Großbuchstaben oder Ziffern vorkommen (aber z. B. keine Blanks).</td>
                    <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td valign="top"><var>[^u]</var></td>
                    <td valign="top">An dieser Stelle darf alles vorkommen außer dem Buchstaben in der Klammer (es dürfen auch verschiedene Buchstaben gleichzeitig negiert werden).</td>
                    <td valign="top"><var>R[^u]s</var> findet z. B. "Rosianus", aber nicht "Ruso".</td>
                </tr>
                <tr>
                    <td valign="top"><var>…\b</var></td>
                    <td valign="top">Die Zeichenfolge vor <var>\b</var> muß an einem Wortende stehen.</td>
                    <td valign="top"><var>Arria\b</var> findet "Arria", aber nicht "Arrianus".</td>
                </tr>
                <tr>
                    <td valign="top"><var>\b…</var></td>
                    <td valign="top">Die Zeichenfolge nach <var>\b</var> muß an einem Wortanfang stehen.</td>
                    <td valign="top"><var>\bAr</var> findet "Arrius" und "Arria", aber nicht "Marius".</td>
                </tr>
                <tr>
                    <td valign="top"><var>…\B</var></td>
                    <td valign="top">Die Zeichenfolge vor <var>\B</var> darf nicht an einem Wortende stehen.</td>
                    <td valign="top"><var>Arria\B</var> findet "Arrianus", aber nicht "Arria".</td>
                </tr>
                <tr>
                    <td valign="top"><var>\B…</var></td>
                    <td valign="top">Die Zeichenfolge vor \B darf nicht an einem Wortanfang stehen.</td>
                    <td valign="top"><var>\BAr</var> findet "Marius", aber nicht "Arrius".</td>
                </tr>
                <tr>
                    <td valign="top"><var>{1,2}</var></td>
                    <td valign="top">Das davorstehende Zeichen muß mindestens einmal und darf höchstens zweimal vorkommen. (Bei dieser Art von Angaben darf auch die Zahl 0 verwendet werden.)</td>
                    <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td valign="top"><var>{1,}</var></td>
                    <td valign="top">Das davorstehende Zeichen muß mindestens einmal vorkommen, darf aber beliebig oft wiederholt sein.</td>
                    <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td valign="top"><var>[a–z]{1,3}</var></td>
                    <td valign="top">Hier muß mindestendens ein Kleinbuchstabe stehen und es dürfen höchstens drei sein.</td>
                    <td valign="top"><var>R[a–z]{2,3}ius</var> findet "Raecius", aber nicht "Rutilius".</td>
                </tr>
                <tr>
                    <td valign="top"><var>…|…</var></td>
                    <td valign="top">Die Zeichen vor und hinter dem senkrechten Strich werden als Alternativen behandelt.</td>
                    <td valign="top"><var>Rupilius|Rutilius</var> findet alle Einträge mit "Rupilius" und alle mit "Rutilius".</td>
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
    props: {
        mode: { type: String, required: true }
    }
}
</script>
