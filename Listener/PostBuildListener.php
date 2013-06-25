<?php

namespace NetTeam\Bundle\DataTableBundle\Listener;

use NetTeam\Bundle\DataTableBundle\StateStorage\DataTableStateStorageInterface;
use NetTeam\Bundle\DataTableBundle\Event\PostBuildEvent;

class PostBuildListener
{
    private $stateStorage;

    public function __construct(DataTableStateStorageInterface $stateStorage)
    {
        $this->stateStorage = $stateStorage;
    }

   public function onPostBuild(PostBuildEvent $event)
   {
       $this->stateStorage->set($event->getDataTableBuilder(), $event->getParameters());
   }
}
