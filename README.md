# contao-concerto-bundle
Entwicklung Concerto
Verwaltungstool zur Erfassung von Konzerten (Bereich Klassik)

Orchestermanager können damit kommende Konzerte erfassen. Falls am gleichen Datum in derselben Stadt ein anderes Konzert stattfindet, wird eine Warnung ausgegeben.

Gleichzeitig entsteht damit ein Katalog von Werken, die von Laienorchestern gespielt wurden. Und es entsteht ein Verzeichnis der Laienorchester in einer bestimmten Region. Die Besetzung wird im Moment als Textstring eingegeben. Angedacht ist hier eine Eingabe aufgeteilt nach Instrument. Die Eingabe wird bei der Ausgabe in den standardisierten Textstring geparst. Damit können später Werke mit einer bestimmten Besetzung (zB 2 Hörner, kein Englischhorn, etc.) gesucht werden. Werke werden separat erfasst. Vor der Eingabe eines neuen Konzertprogramms muss deshalb geprüft werden, ob alle Werke im Katalog bereits vorhanden sind. Andernfalls werden sie im Katalog erfasst.

Die Erfassung eines neuen Konzerts beinhaltet
- die Eingabe des Konzerttitels, Auswahl des Ensembles, Dirigent, Solisten, Auswahl der Werke mit Select aus vorhandenem Werkkatalog (mehrere Werke möglich, ergibt ein Varcharfeld mit mehreren Einträgen (tl_concerto)
- Eingabe von Konzertdatum, Ort

Concerto ist eigentlich ein Frontend für eine relationale Datenbank mit folgenden Tabellen:
- tl_werke
- tl_ensemble
- tl_concert
