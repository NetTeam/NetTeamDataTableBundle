<?php

namespace NetTeam\Bundle\DataTableBundle\Tests\Functional;

/**
 * Wartości filtrów w datatable
 */
class DatatableFilter implements DatatableFilterInterface
{
    const PARAM_PREFIX = 'filter-';
    const FIELD_PREFIX = 'type-';

    /**
     * Wartości pól
     *
     * @var array
     */
    protected $fields = array();

    /**
     * {@inheritdoc}
     */
    public function setValue($index, $value)
    {
        $this->fields[$index] = array('value' => $value);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setValues($params)
    {
        foreach ($params as $index => $values) {
            $this->fields[$index] = $values;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setChoice($index, $choice)
    {
        $this->fields[$index] = array('choice' => $choice);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryString($datatableName)
    {
        $result = array();
        foreach ($this->fields as $index => $item) {
            foreach ($item as $field => $value) {
                // np. filter-datatable[type-0][field]=value
                $result[] = sprintf("%s%s[%s%d][%s]=%s", self::PARAM_PREFIX, $datatableName, self::FIELD_PREFIX , $index, $field, urlencode($value));
            }
        }

        return implode('&', $result);
    }
}
