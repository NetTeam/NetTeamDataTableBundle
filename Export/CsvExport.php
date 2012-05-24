<?php

namespace NetTeam\Bundle\DataTableBundle\Export;

/**
 *
 * @author Wojciech Kulikowski <wojciech.kulikowski@netteam.pl>
 */
class CsvExport implements ExportInterface
{

    private $template;
    private $headers;
    private $options;

    public function __construct()
    {
        $this->template = 'NetTeamDataTableBundle:Export:export.csv.twig';
        $this->headers = array(
            'Content-Type' => 'application/csv',
            'Content-Disposition' => 'attachment; filename="export.' . date('YmdHis') . '.csv"'
        );
    }

    public function build()
    {
        $this->headers['Content-Disposition'] = 'attachment; filename="' . $this->options['filename'] . '.csv"';
        if (!count($this->options)) {
            $this->options = $this->getDefaultOptions();
        }
    }

    public function setOptions($options)
    {
        $defaultOptions = $this->getDefaultOptions();
        $this->options = array_replace($defaultOptions, $options);
    }

    public function setOption($key, $value)
    {
        $this->options[$key] = $value;
    }

    public function getDefaultOptions()
    {
        return array(
            'separator' => ';',
            'boundary' => '"',
            'label' => 'CSV',
            'filename' => 'export'
        );
    }

    public function getOptions($key)
    {
        return isset($this->options[$name]) ? $this->options[$name] : null;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function getLabel()
    {
        return $this->options['label'];
    }

    public function getSeparator()
    {
        return $this->options['separator'];
    }

    public function getBoundary()
    {
        return $this->options['boundary'];
    }

}
