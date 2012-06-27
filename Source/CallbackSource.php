<?php
namespace NetTeam\Bundle\DataTableBundle\Source;

/**
 * Źródło danych dla SimpleDataTable - callback
 *
 * Callback przyjmuje parametry -- $offset, $limit, $options np.:
 * function ($offset, $limit, array $options) {
 *      // ...
 * }
 *
 * @author Krzysztof Menżyk <krzysztof.menzyk@netteam.pl>
 */
class CallbackSource implements SourceInterface
{
    protected $callback;
    protected $count;

    protected $options = array();

    public function __construct(\Closure $callback)
    {
        $this->callback = $callback;
    }

    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    public function getOption($name)
    {
        if (!isset($this->options[$name])) {
            return null;
        }

        return $this->options[$name];
    }

    public function getData($offset, $limit)
    {
        $callback = $this->callback;
        $data = $callback($offset, $limit, $this->options);
        $this->count = count($data);

        return $data;
    }

    public function getDataAll()
    {
        $callback = $this->callback;

        return $callback(null, null, $this->options);
    }

    public function addSorting($column, $order)
    {
    }

    /**
     * Wyszukuje po zadanych kolumnach
     *
     * @param array        $keys
     * @param unknown_type $search
     */
    public function globalSearch(array $keys, $search)
    {
    }

    public function count()
    {
        return $this->count;
    }
}
