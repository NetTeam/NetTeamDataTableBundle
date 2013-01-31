<?php

namespace NetTeam\Bundle\DataTableBundle\Factory;

use NetTeam\Bundle\DataTableBundle\Column\ColumnInterface;

/**
 * Serwis dla tworzenia kolumn
 *
 */
class ColumnFactory
{
    /**
     * Dostępne kolumny
     *
     * @var string[]
     */
    protected $columnTypes = array();

    /**
     * Utworzenie kolumny
     *
     * @param \NetTeam\Bundle\DataTableBundle\Column\ColumnInterface $type
     * @param string                                                 $name
     * @param string                                                 $getter
     * @param array                                                  $parameters
     *
     * @return \NetTeam\Bundle\DataTableBundle\Column\ColumnInterface|\NetTeam\Bundle\DataTableBundle\Factory\ColumnFactoryAwareInterface
     *
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
     * @param  string                                $name  Unikalna nazwa kolumny
     * @param  class                                 $class Scieżka klasy kolumny
     * @throws Exception\WrongColumnTypeException
     * @throws Exception\InvalidColumnClassException
     * @throws Exception\ColumnExistException
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
