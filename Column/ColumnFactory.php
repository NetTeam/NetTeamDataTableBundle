<?php

namespace NetTeam\Bundle\DataTableBundle\Column;

/**
 * Description of ColumnFactory
 *
 */
class ColumnFactory
{
    protected $columnTypes = array();

    public function create($type, $name, $getter, array $parameters = array())
    {
        if (!is_string($type)) {
            throw new \InvalidArgumentException('Wrong column type parameter, string expected');
        }

        if (!array_key_exists($type, $this->columnTypes)) {
            throw new \InvalidArgumentException('Wrong column type, "'.$type.'" given');
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
     * @param  string                    $name  Unikalna nazwa kolumny
     * @param  class                     $class Scieżka klasy kolumny
     * @throws \InvalidArgumentException
     */
    public function addColumnType($name, $class)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException(sprintf('Name must be column name as string, "%s" given', gettype($name)));
        }

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }

        if (isset($this->columnTypes[$name])) {
            throw new \InvalidArgumentException(sprintf('Column type "%s" already exists.', $name));
        }

        $this->columnTypes[$name] = $class;
    }

}
