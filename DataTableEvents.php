<?php

namespace NetTeam\Bundle\DataTableBundle;

final class DataTableEvents
{
    /**
     * POST_BUILD event wywoływany w momencie pobrania danych przez buildera DataTable z uwzględnieniem wybrancyh filtrów
     *
     * Ten event pozwala pobrać buildera DataTable
     * Event listener przyjmuje NetTeam\Bundle\DataTableBundle\Event\PostBuildEvent
     */
    const POST_BUILD = 'datatable.post.build';

}
