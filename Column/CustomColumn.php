<?php

namespace NetTeam\Bundle\DataTableBundle\Column;

use NetTeam\Bundle\DataTableBundle\Column\Column;

/**
 * Allows setting template of standard column.
 *
 * @author Paweł A. Wacławczyk <p.a.waclawczyk@gmail.com>
 */
class CustomColumn extends Column
{
    private $customTemplate;

    /**
     * {@inheritdoc}
     */
    public function __construct($caption, $getters, $parameters = array())
    {
        parent::__construct($caption, $getters, $parameters);

        $this->customTemplate = false;
    }

    /**
     * @param string $template
     *
     * @return \NetTeam\Bundle\DataTableBundle\Column\CustomColumn
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        $this->customTemplate = true;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        if ($this->customTemplate) {
            return $this->template;
        }

        return parent::getTemplate();
    }
}
