Instalacja
----------

W `AppKernel` należy dodać dwa wpisy:

    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Liuggio\ExcelBundle\LiuggioExcelBundle(),
            new NetTeam\Bundle\DataTableBundle\NetTeamDataTableBundle(),
            // ...
        );
    }

