<?php

namespace NetTeam\Bundle\DataTableBundle\Tests\Functional;

/**
 * Wartości filtrów w datatable
 *
 * Datatable tworzy pola wg schematu <i>filter-<b>datatableName</b>[type-<b>index</b>][<b>field</b>]</i>:
 *
 * - <i>datatableName</i> - nazwa datatable
 * - <i>index</i> - numer kolejny liczony od zera, numeracja nadawana przez datatable!
 * - <i>field</i> - nazwa pola:
 *    - <i>value</i> - pole tekstowe
 *    - <i>choice</i> - wartość wyboru
 *
 * Metody @see setValue i @see setChoice pozwalają ustawić <b>index</b> oraz wartości pól.
 * Metoda @see getQueryString zwraca argumenty urla zawierające wartości filtrów.
 *
 * <code>
 *   $filter = new DatatableFilterClass();
 *   $query = $filter->getQueryString();
 *   $url = '...';
 *
 *   if ('' !== $query) {
 *      $url = $url . '?' . $query;
 *   }
 * </code>
 *
 */
interface DatatableFilterInterface
{
    /**
     * Zwraca listę argumentów url zawierającą parametry dla filtra.
     *
     * @param string $datatableName nazwa
     *
     * @return string
     */
    public function getQueryString($datatableName);

    /**
     * Ustawia wartość dla pola tekstowego.
     *
     * @param string $index indeks pola w filtrach
     * @param string $value warość pola
     *
     * @return self
     */
    public function setValue($index, $value);

    /**
     * Ustawia wartość dla pola tekstowego.
     *
     * @param string $index indeks pola w filtrach
     * @param string $value warość pola
     *
     * @return self
     */
    public function setChoice($index, $choice);

}
