<?php

namespace NetTeam\Bundle\DataTableBundle\Factory;

use NetTeam\Bundle\DataTableBundle\Column\ColumnInterface;

/**
 * Serwis dla tworzenia kolumn
 *
 */
class ColumnFactory
{
    protected $columnTypes = array();

    /**
     * Metoda do utworzenia kolumny
     *
     * @param  \NetTeam\Bundle\DataTableBundle\Column\ColumnInterface|string $type
     * @param  string                                                        $name
     * @param  string|array                                                  $getter
     * @param  array                                                         $parameters
     * @return \NetTeam\Bundle\DataTableBundle\Column\ColumnInterface
     * @throws Exception\WrongColumnTypeException
     * @throws Exception\WrongColumnTypeParameterException
     */
    public function create($type, $name, $getter, array $parameters = array())
    {
        if (is_object($type) && $type instanceof ColumnInterface) {
            return $type;
        }

        if (!is_string($type)) {
            throw new Exception\WrongColumnTypeException('Wrong column type parameter, string expected');
        }

        if (!array_key_exists($type, $this->columnTypes)) {
            throw new Exception\WrongColumnTypeParameterException('Column type not found, "'.$type.'" given');
        }

        $columnClass = $this->columnTypes[$type];

        $column = $columnClass::create($name, $getter, $parameters);

        if ($column instanceof ColumnFactoryAwareInterface) {
            $column->setColumnFactory($this);
        }

        return $column;
    }

    /**
     * Metoda pozwalająca dodać nową kolumnę do serwisu
     *
     * @param  string                             $name  Unikalna nazwa kolumny
     * @param  class                              $class Scieżka klasy kolumny
     * @throws Exception\WrongColumnTypeException
     * @throws \InvalidArgumentException
     */
    public function addColumnType($name, $class)
    {
        if (!is_string($name)) {
            throw new Exception\WrongColumnTypeException(sprintf('Name must be column name as string, "%s" given', gettype($name)));
        }

        if (!class_exists($class)) {
            throw new Exception\InvalidColumnClassException(sprintf('Class "%s" does not exist.', $class));
        }

        if (isset($this->columnTypes[$name])) {
            throw new Exception\ColumnExistException(sprintf('Column type "%s" already exists.', $name));
        }

        $this->columnTypes[$name] = $class;
    }

}
