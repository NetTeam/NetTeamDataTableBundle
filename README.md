Travis-CI [![Build Status](https://travis-ci.org/NetTeam/NetTeamDataTableBundle.png?branch=1.0)](https://travis-ci.org/NetTeam/NetTeamDataTableBundle)
SensioLabsInsight [![SensioLabsInsight](https://insight.sensiolabs.com/projects/38041bbd-862c-49e9-a847-10fff41a5bc2/mini.png)](https://insight.sensiolabs.com/projects/38041bbd-862c-49e9-a847-10fff41a5bc2)


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

