<?php

namespace NetTeam\Bundle\DataTableBundle\Export;

/**
 * Kontener na eksportery
 *
 * @author Paweł Macyszyn <pawel.macyszyn@netteam.pl>
 */
class ExportContainer
{
    /**
     * Dostępne rozszerzenia
     *
     * @var array
     */
    protected $exporters = array();

    /**
     * Eksportuje dane wejściowe do wybranego formatu
     *
     * @param  string                    $exporter
     * @return ExporterInterface
     * @throws \InvalidArgumentException
     */
    public function get($exporter)
    {
        if (!array_key_exists($exporter, $this->exporters)) {
            throw new \InvalidArgumentException(sprintf('Exporter not found "%s"', $exporter));
        }

        return $this->exporters[$exporter];
    }

    /**
     * Dodaje rozszerzenie
     *
     * @param ExportInterface
     * @throws \InvalidArgumentException
     */
    public function add(ExportInterface $exporter)
    {
        $name = $exporter->getName();

        if (empty($name) || !is_string($name)) {
            throw new \InvalidArgumentException(sprintf('Exporter name must be non empty string'));
        }
        if (array_key_exists($name, $this->exporters)) {
            throw new \InvalidArgumentException(sprintf('Exporter already exists "%s"', $name));
        }

        $this->exporters[$name] = $exporter;
    }
}
