[![Build Status](https://travis-ci.org/NetTeam/NetTeamDataTableBundle.png?branch=master)](https://travis-ci.org/NetTeam/NetTeamDataTableBundle)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/53c4353f-0e5b-48f1-b2f9-310079938cd8/mini.png)](https://insight.sensiolabs.com/projects/53c4353f-0e5b-48f1-b2f9-310079938cd8)

[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/NetTeam/NetTeamDataTableBundle/badges/quality-score.png?s=4b14fcb2d08f6bf3fd444eb740db6af123dbb4a2)](https://scrutinizer-ci.com/g/NetTeam/NetTeamDataTableBundle/)

[![Code Coverage](https://scrutinizer-ci.com/g/NetTeam/NetTeamDataTableBundle/badges/coverage.png?s=7df45d479e6a8199ad83e4ffa29b9a65c291bdab)](https://scrutinizer-ci.com/g/NetTeam/NetTeamDataTableBundle/)

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

