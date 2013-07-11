<?php

namespace NetTeam\Bundle\DataTableBundle\Export;

use Symfony\Component\DependencyInjection\ContainerInterface;
use NetTeam\Bundle\DataTableBundle\Export\Exception\ExportContainerInvalidResultException;
use NetTeam\Bundle\DataTableBundle\Export\Exception\ExportNotFoundException;

/**
 * Kontener na eksportery
 *
 * @author Paweł Macyszyn <pawel.macyszyn@netteam.pl>
 */
class ExportContainer
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array
     */
    private $types = array();

    /**
     * @param ContainerInterface $container
     * @param array              $types
     */
    public function __construct(ContainerInterface $container, array $types = array())
    {
        $this->container = $container;
        $this->types = $types;
    }

    /**
     * Pobiera typ eksportu o zadanej nazwie
     *
     * @param  string                    $name
     * @return ExporterInterface
     * @throws \InvalidArgumentException
     */
    public function get($name)
    {
        if (!is_string($name)) {
            throw new \InvalidArgumentException(sprintf('Expected argument of type "string", "%s" given', is_object($name) ? get_class($name) : gettype($name)));
        }
        if (!isset($this->types[$name])) {
            throw new ExportNotFoundException(sprintf('The DataTable export "%s" is not defined.', $name));
        }

        $type = $this->container->get($this->types[$name]);

        if (!$type instanceof ExportInterface) {
            throw new ExportContainerInvalidResultException(sprintf('The service "%s" must implement ExportInterface.', $name));
        }

        return $type;
    }
}
