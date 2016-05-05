<?php
/************************************************************
 * Copyright (C), 2014-2015, Everyoo Tech. Co., Ltd.
 * @FileName: SearchC.php
 * @Author: zzx       Version :   V.1.0.0       Date: 2015/5/15
 * @Description:     分词模块
 ***********************************************************/
namespace App\Custom\Coreseek;

class Coreseek
{

    /**
     * @param string $words
     * @return array
     */
    public function getWordByWords($words = '')
    {
        require ( App_path(). '/Custom/Coreseek/sphinxapi.php');
        $cl = new \SphinxClient ();
        $cl->SetServer ( '192.168.22.27', 9312);
        $cl->SetConnectTimeout ( 3 );
        $cl->SetArrayResult ( true );
        $cl->SetMatchMode ( SPH_MATCH_ANY);
        $res = $cl->Query ($words, "*" );
        return $res['words'];
    }
}

