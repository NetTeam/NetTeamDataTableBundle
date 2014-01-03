<?php

namespace NetTeam\Bundle\DataTableBundle\StateStorage;

use Symfony\Component\HttpFoundation\Session\Session;
use NetTeam\Bundle\DataTableBundle\DataTable\DataTableBuilder;

/**
 * Implementation of DataTableStateStorageInterface
 * @author Wojciech Kulikowski <wojciech.kulikowski@netteam.pl>
 */
class DataTableSessionStateStorage implements DataTableStateStorageInterface
{
    const SESSION_KEY = 'dataTableFilters';
    private $session;

    /**
     * @param \Symfony\Component\HttpFoundation\Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function set(DataTableBuilder $dataTableBuilder, array $parameters)
    {
       $key = $this->createKey($dataTableBuilder);

       $filters = $this->session->get(self::SESSION_KEY);

       if (null === $filters) {
           $filters = array(
               $key => $parameters
           );
       }

       if (is_array($filters)) {
           $filters[$key] = $parameters;
       }

       $this->session->set(self::SESSION_KEY, $filters);
    }

    /**
     * {@inheritdoc}
     */
    public function get(DataTableBuilder $dataTableBuilder)
    {
        $filters = $this->session->get(self::SESSION_KEY);
        $key = $this->createKey($dataTableBuilder);

        if (!$this->has($dataTableBuilder)) {
            return null;
        }

        return $filters[$key];

    }

    public function has(DataTableBuilder $dataTableBuilder)
    {
         $filters = $this->session->get(self::SESSION_KEY);
         $key = $this->createKey($dataTableBuilder);
         return isset($filters[$key]);
    }

    private function createKey(DataTableBuilder $dataTableBuilder)
    {
        $name = $dataTableBuilder->getName();
        $context = $this->normalizeContext($dataTableBuilder->getContext());

        $hash = md5(serialize($context));

        return $name.$hash;
    }

    private function normalizeContext(array $context)
    {
        $normalizedContext = array();
        foreach ($context as $key => $value) {
            if (is_array($value)) {
                $normalizedContext[$key] = $this->normalizeContext($value);

                continue;
            }

            if (!$this->canBeString($value)) {
                throw new \InvalidArgumentException('One of DataTable options cannot be converted to string');
            }

            $normalizedContext[$key] = (string) $value;
        }

        return $normalizedContext;

    }

    /**
     * Czy wartość może być stringiem
     *
     * @param $value
     *
     * @return boolean
     */
    private function canBeString($value)
    {
        if (!is_object($value)) {
            return settype($value, 'string');
        }

        if (is_object($value)) {
            return method_exists($value, '__toString');
        }

        return false;
    }
}
