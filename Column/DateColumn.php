<?php

namespace NetTeam\System\DataTableBundle\Column;

class DateColumn extends Column
{
    protected $template = 'date_column';
    protected $format = 'Y-m-d';

    public static function create($caption, $getter, array $parameters)
    {
        $column = parent::create($caption, $getter, $parameters);

        if (isset($parameters['format'])) {
            $column->setFormat($parameters['format']);
        }

        return $column;
    }
    
    public function getValue($objectOrArray)
    {
        $value = parent::getValue($objectOrArray);
        $value->add('format', $this->format);

        return $value;
    }
    
    public function getFormat()
    {
        return $this->format;
    }

    public function setFormat($format)
    {
        $this->format = $format;
    }

}
