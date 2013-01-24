<?php

namespace NetTeam\Bundle\DataTableBundle\Column;

/**
 * Description of ColumnFactory
 *
 */
class ColumnFactory
{
    protected $columnTypes;

    public function create($type, $name, $getter, array $parameters = array())
    {
        if (!is_string($type)) {
            throw new \InvalidArgumentException('Wrong column type parameter, string expected');
        }
        
        if (!array_key_exists($type, $this->columnTypes)) {
            throw new \InvalidArgumentException('Wrong column type, "'.$type.'" given');
        }
        
        $columnClass = $this->columnTypes[$type];

        return $columnClass::create($name, $getter, $parameters);
    }
    
    public function addColumnType($name, $class)
    {
        if (!is_string($name))
        {
            throw new \InvalidArgumentException('Name must be column name as string, "'.gettype($name).'" given');
        }
        
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }
        
        $this->columnTypes[$name] = $class;
    }

}
