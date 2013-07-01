<?php

namespace NetTeam\Bundle\DataTableBundle\Filter;

/**
 * Dodatkowy button wyświetlany pod filtrami
 *
 * @author Paweł Macyszyn <pawel.macyszyn@netteam.pl>
 */
class Button
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $class;

    /**
     * @param string $id
     * @param string $name
     * @param string $class
     */
    public function __construct($id, $name, $class)
    {
        $this->id = $id;
        $this->name = $name;
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }
}
