## CHANGELOG ##

#### v1.1.16 (2014-07-14) ####
- Merge 1.0 (tag v1.0.17)

#### v1.1.15 (2014-01-07) ####
- Fix StatestorageTest
- Fix sorting in DoctrineSQLSource

#### v1.1.14 (2013-12-20)
- Merge 1.0 (tag v1.0.15)

#### v1.1.13 (2013-12-11) ####
- Fix after unproper merge
- Fix translations for boolean column 
- Added JSON response check for filters

#### v1.1.12 (2013-09-18) ####
- Merge z 1.0 (tag v1.0.14)

#### v1.1.11 (2013-08-28) ####
- Merge z 1.0 (tag v1.0.13)
- Dodanie obsługi parametru domeny tłumaczeń do kolumn tekstowych oraz tablicowych
- Dodanie możliwości zmiany tłumaczeń dla kolumny Bool
- Dodanie getterów i setterów odpowiedzialnych za sortowanie w DataTableBuilder

#### v1.1.10 (2013-07-25) ####
- Zmiana zależności od Symfony2 na wersje >=2.2.

#### v1.1.9 (2013-07-22)
- Merge z 1.0 (tag v1.0.10)

#### v1.1.8 (2013-07-18)
- Merge z 1.0 (tag v1.0.9)

#### v1.1.7 (2013-07-11)
- Merge z 1.0 (tag v1.0.8)

#### v1.1.6 (2013-07-05)
- Merge z 1.0 (tag v1.0.7)

#### v1.1.5 (2013-07-05)
- Merge z 1.0 (tag v1.0.6)

#### v1.1.4 (2013-07-04)
- Merge z 1.0 (tag v1.0.5)

#### v1.1.3 (2013-07-04)
- Hotfix BulkAction po merge 1.0

#### v1.1.2 (2013-07-01)
- Merge z branchem 1.0 (do tagu v1.0.3)

#### v1.1.1 (2013-06-07) ####
- Dodanie edit in place

#### v1.0.17 (2014-07-14) ####
- Lower template for bulk actions

#### v1.0.16 (2014-01-09) ####
- Fix StatestorageTest
- Fix sorting in DoctrineSQLSource

#### v1.0.15 (2013-12-20) ####
- Changes in sorting data in DoctrineSQLSource
- Remove unused js files: TableTools.js, ZeroClipboard.js

#### v1.0.14 (2013-09-03) ####
- Translacje dla kolumny typu bool

#### v1.0.13 (2013-08-28) ####
- Czyszczenie bulk action w nagłówku tabeli w momencie przeładowania danych

#### v1.0.12 (2013-08-27) ####
- Dodanie `CustomColumn`
- Pominięcie testu z powodu nie działajacej zmiany locale
- Dodanie getterów i setterów odpowiedzialnych za sortowanie w DataTableBuilder

#### v1.0.11 (2013-08-05) ####
- Dołożony collapse, gdy elementy nie zapełniają minHeight datatable'a

#### v1.0.10 (2013-07-22) ####
- Możliwe ustawienie szerokości i wysokości widocznej części tabeli, oraz jej minimalnej szerokości
- Dodanie README.md

#### v1.0.9 (2013-07-12) ####
- Poprawienie Dependency

#### v1.0.8 (2013-07-11) ####
- Poprawka resetowania filtrów
- Dodanie EnumFiltra

#### v1.0.7 (2013-07-05) ####
- Helper dla testów funkcjonalnych `DatatableTest`

#### v1.0.6 (2013-07-05) ####
- Zmiana rzutowania klucza wyszukiwania w globalSearch na string w php, a nie w SQL

#### v1.0.6 (YYYY-MM-DD) ####
- Usunięta niedziałająca funkcjonalność eksportu do CSV
- Dodane eksportowanie do XLS

#### v1.0.5 (2013-07-04) ####
- Dodanie setlocale w testach StateStorage
- Dodanie domyslnej wartosci do choice

#### v1.0.4 (2013-06-27) ####
- Hotfix zapisywania stanu filtrów

#### v1.0.3 (2013-06-26) ####
- Dodanie zapisywania stanu fitrów
- Dodane dodatkowe przyciski w filtrach
- Dodanie eventu postBuildEvent
- Dodanie możliwości dodawania akcji

#### v1.0.2 (2013-04-22) ####
- Poprawa błędu w bulk actions

#### v1.0.1 (2013-03-08) ####
- Aktualizacja do Doctrine 2.2
- Refaktoring `DoctrineORMSource`
