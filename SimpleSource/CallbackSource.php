<?php
namespace NetTeam\Bundle\DataTableBundle\SimpleSource;

/**
 * Źródło danych dla SimpleDataTable - callback
 * 
 * Callback przyjmuje jeden parametr -- $options np.:
 * function (array $options) {
 *      // ...
 * }
 *
 * @author Krzysztof Menżyk <krzysztof.menzyk@netteam.pl>
 */
class CallbackSource implements SimpleSourceInterface
{
    protected $callback;
    
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

    public function getData()
    {
        $callback = $this->callback;
        
        return $callback($this->options); 
    }
}