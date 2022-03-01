<?php

namespace App\Lib;

/* iniファイルを扱うクラス */
class IniFile
{
    private $iniFileName;   /* iniファイル */
    private $iniFile;       /* iniファイルの中身 */

    /* コンストラクタ */
    public function __construct()
    {
        $this->iniFileName = "advertorial.ini";

        $this->iniFile = parse_ini_file($this->iniFileName,true);
    }

    /* ゲッター */
    /* $defaultはセクションやキーがなかった場合のデフォルト値 */
    public function getConfig($key,$section,$default)
    {
        $ret = $default;

        if(array_key_exists($section,$this->iniFile)) {
            $secAry = $this->iniFile[$section];
            if(array_key_exists($key,$secAry)) {
                $ret = $secAry[$key];
            }
        }

        return $ret;
    }
}

