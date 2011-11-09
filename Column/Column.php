<?php

namespace NetTeam\System\DataTableBundle\Column;

use NetTeam\System\DataTableBundle\Column\ValueGetter\ClosureValueGetter;

use NetTeam\System\CoreBundle\Exception\NetTeamException;
use NetTeam\System\DataTableBundle\Column\ValueGetter\PropertyValueGetter;

/**
 * Column
 *
 * @author Wojciech Kulikowski <wojciech.kulikowski@carrywater.pl>
 */
abstract class Column implements ColumnInterface
{
    const TEMPLATE_PATTERN = 'NetTeamDataTableBundle:Column:%s.html.twig';
    
    protected $caption;
    protected $width = null;
    protected $getters = array();
    protected $sortableKeys = array();
    protected $defaultSorting;
    protected $priority = 0;
    protected $class = array();
    protected $template = 'column';
    protected $translate = true;

    public function __construct($caption, $getters)
    {
        $this->caption = $caption;

        if (is_array($getters)) {
            foreach ($getters as $getter) {
                $this->addGetter($getter);
            }
        } else {
            $this->addGetter($getters);
        }
    }

    public static function create($caption, $keys)
    {
        return new static($caption, $keys);
    }

    public function getValue($objectOrArray)
    {
        $values = array();
        foreach ($this->getters as $getter) {
            $values[] = $getter->get($objectOrArray);
        }

        return count($values) === 1 ? $values[0] : $values;
    }

    public function addGetter($getterKey)
    {
        $this->getters[] = $this->createValueGetter($getterKey);

        return $this;
    }

    private function createValueGetter($getterKey)
    {
        if (is_string($getterKey)) {
            return new PropertyValueGetter($getterKey);
        }

        if ($getterKey instanceof \Closure) {
            return new ClosureValueGetter($getterKey);
        }

        throw new \InvalidArgumentException('Niepoprawna definicja klucza kolumny');
    }

    public function getter($key)
    {
        return $this->addGetter($key);
    }

    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    public function priority($priority)
    {
        return $this->setPriority($priority);
    }

    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Dodaje klasę CSS do wyglądu kolumny
     * @param string $class
     */
    public function addClass($class)
    {
        if (!is_string($class)) {
            throw new \InvalidArgumentException('Niepoprawna definicja klasy CSS kolumny');
        }

        if (!in_array($class, $this->class)) {
            $this->class[] = $class;
        }

        return $this;
    }

    public function class_($class)
    {
        return $this->addClass($class);
    }

    public function getClass()
    {
        return implode(" ", $this->class);
    }


    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    public function width($width)
    {
        return $this->setWidth($width);
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function hasWidth()
    {
        return $this->width !== null;
    }

    public function setSortable($keys)
    {
        if (is_array($keys)) {
            foreach ($keys as $key) {
                $this->addSortable($key);
            }
        } else if (is_string($keys)) {
            $this->addSortable($keys);
        }

        return $this;
    }

    public function sortable($keys)
    {
        return $this->setSortable($keys);
    }

    public function addSortable($key)
    {
        if (!is_string($key)) {
            throw new \InvalidArgumentException('Niepoprawna definicja klucza sortowanej kolumny');
        }
        if (!in_array($key, $this->sortableKeys)) {
            $this->sortableKeys[] = $key;
        }

        return $this;
    }

    public function getSortableKeys()
    {
        return $this->sortableKeys;
    }

    public function isSortable()
    {
        return !empty($this->sortableKeys);
    }

    public function sortByDefault($order = 'ASC')
    {
        $this->defaultSorting = $order;
        return $this;
    }

    public function isSortedByDefault()
    {
        return $this->defaultSorting !== null;
    }

    public function getDefaultSorting()
    {
        return $this->defaultSorting;
    }

    public function getCaption()
    {
        return $this->caption;
    }

    public function getTemplate()
    {
        return sprintf(self::TEMPLATE_PATTERN, $this->template);
    }

    public function getTranslate()
    {
        return $this->translate;
    }

    public function setTranslate($translate)
    {
        $this->translate = $translate;
        return $this;
    }
}
