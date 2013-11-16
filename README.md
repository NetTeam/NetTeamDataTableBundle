[![Build Status](https://travis-ci.org/NetTeam/NetTeamDataTableBundle.png?branch=1.0)](https://travis-ci.org/NetTeam/NetTeamDataTableBundle)


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

