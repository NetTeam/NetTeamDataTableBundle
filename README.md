[![Build Status](https://travis-ci.org/NetTeam/NetTeamDataTableBundle.png?branch=1.0)](https://travis-ci.org/NetTeam/NetTeamDataTableBundle)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/38041bbd-862c-49e9-a847-10fff41a5bc2/mini.png)](https://insight.sensiolabs.com/projects/38041bbd-862c-49e9-a847-10fff41a5bc2)

[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/NetTeam/NetTeamDataTableBundle/badges/quality-score.png?s=4b14fcb2d08f6bf3fd444eb740db6af123dbb4a2)](https://scrutinizer-ci.com/g/NetTeam/NetTeamDataTableBundle/)


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

