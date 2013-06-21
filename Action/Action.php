<?php

namespace NetTeam\Bundle\DataTableBundle\Action;

/**
 * Akcja dostępna na DataTable wyświetlana w postaci pola wyboru
 */
class Action
{
    protected $caption;
    protected $route;
    protected $params = array();

    /**
     * @param string $caption
     * @param string $route
     * @param array  $params
     */
    public function __construct($caption, $route, array $params = array())
    {
        $this->route = $route;
        $this->params = $params;
        $this->caption = $caption;
    }

    /**
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

   /**
    *
    * @param string $caption
    * @return \NetTeam\Bundle\DataTableBundle\Action\Action
    */
    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     *
     * @param  string                                        $route
     * @return \NetTeam\Bundle\DataTableBundle\Action\Action
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     *
     * @param  array                                         $params
     * @return \NetTeam\Bundle\DataTableBundle\Action\Action
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }
}
