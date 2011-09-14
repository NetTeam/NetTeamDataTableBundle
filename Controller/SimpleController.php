<?php

namespace NetTeam\System\DataTableBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use NetTeam\System\DataTableBundle\SimpleSource\SimpleSourceInterface;

/**
 * Obsługuje prostą tabelę
 *
 * @author Krzysztof Menżyk <krzysztof.menzyk@carrywater.pl>
 */
abstract class SimpleController extends Controller
{
    protected $rowTemplate;

    protected $source;

    protected abstract function getSource();

    public function __construct()
    {
        if ($this->rowTemplate === null) {
            throw new \InvalidArgumentException("Attribute 'rowTemplate' is not defined");
        }
    }

    private function source()
    {
        if ($this->source === null) {
            $this->source = $this->getSource();

            if (!$this->source instanceof SimpleSourceInterface) {
                throw new \InvalidArgumentException("getSource must return SimpleSourceInterface implementation");
            }
        }

        return $this->source;
    }

    public function dataAction()
    {
        $this->source();

        $data = $this->source->getData();

        return $this->render('NetTeamDataTableBundle::simple_data.json.twig', array(
            'data'     => $data,
            'template' => $this->rowTemplate
        ));
    }
}
