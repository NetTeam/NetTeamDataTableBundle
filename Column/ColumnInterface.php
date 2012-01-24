<?php

namespace NetTeam\System\DataTableBundle\Column;

/**
 * ColumnInterface
 * Przyklad wykorzystania:
 * <code>
 * <?php
 *
 *   $c1 = new TextColumn('Imię i nazwisko', array('u.name', 'u.surname'));
 *   $c1->setSortable(array('u.surname', 'u.createdAt'), 'desc');
 *
 *   $c2 = new TextColumn('Data urodzenia', 'u.birthDate');
 *   $c2->setSortable()->setSearchable()->setPriority(2)->addClass('center');
 * ?>
 * </code>
 *
 * @author Wojciech Kulikowski <wojciech.kulikowski@carrywater.pl>
 */
interface ColumnInterface
{
    public static function create($name, $getter, array $parameters);

    /**
     * @return Value\ColumnValueInterface
     */
    public function getValue($objectOrArray);

    public function addGetter($getter);
    public function getter($getter);

    /**
     *  Priorytet sortowania (im wyższa liczba tym wyższy priorytet).
     *  Dopuszczalne są priorytety ujemne
     * @param int $priority
     */
    public function setPriority($priority);
    public function priority($priority);
    public function getPriority();

    /**
     * Dodaje klasę css.
     * @param string $class
     */
    public function addClass($class);
    public function class_($class);
    public function getClass();

    /**
     * Szerokość (definiowana tak samo jak w css np. 100px)
     *
     * @param string $width
     */
    public function setWidth($width);
    public function width($width);
    public function getWidth();
    public function hasWidth();

    /**
     * Określa po których kolumnach ma być sortowana tabela
     *
     * @param mixed $columns klucze sortowanych kolumn w kolejności sortowania
     * @param string $order kierunek sortowania
     */
    public function setSortable($columns);
    public function sortable($keys);
    public function addSortable($key);

    /**
     * Czy kolumna może być sortowana
     */
    public function isSortable();

    /**
     * Czy i jak kolumna ma być domyślnie sortowana
     */
    public function sortByDefault($order = 'ASC');
    public function isSortedByDefault();
    public function getDefaultSorting();

    /**
     * Pobiera domyślny kierunek sortowania
     */
    public function getSortableKeys();

    public function route($route, array $params, $routeClass = null);
    public function setRoute($route, array $params, $routeClass = null);

    public function getCaption();

    public function getTemplate();
}
