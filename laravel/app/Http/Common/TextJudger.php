<?php

namespace App\Http\Common;

use Normalizer;

class TextJudger
{
    // correct
    public $origin_correct_txt = "";
    public $origin_correct_ary = [];
    public $correct_txt = "";
    public $correct_ary = [];

    // answer
    public $origin_answer_txt = "";
    public $answer_txt = "";

    // matches
    public $matches = [];

    /**
     * Set correct
     *
     * @param string,array $correct
     */
    public function setCorrect($correct)
    {
        if (!is_array($correct)) {
            $this->origin_correct_txt = $correct;
            $correct = explode(",", $correct);
        }
        $this->origin_correct_ary = $correct;
        $correct = self::convertMatchTextAry($correct);
        $this->correct_ary = $correct;
        $this->correct_txt = implode(",", $correct);
    }

    /**
     * Set Answer
     *
     * @param string $txt
     */
    public function setAnswer($txt)
    {
        $this->origin_answer_txt = $txt;
        $this->answer_txt = self::convertMatchText($txt);
    }

    /**
     * Check
     * @return int match count
     */
    public function check()
    {
        $matches = [];
        foreach ($this->correct_ary as $correct) {
            if (!preg_match("/$correct/i", $this->answer_txt, $m)) {
                continue;
            }
            $matches[] = $m[0];
        }
        $this->matches = $matches;
        return count($matches);
    }

    private static function convertMatchTextAry($array)
    {
        foreach ($array as $key => $txt) {
            $array[$key] = self::convertMatchText($txt);
        }
        $array = array_unique($array);
        return $array;
    }

    private static function convertMatchText($txt)
    {
        $txt = Normalizer::normalize($txt, Normalizer::FORM_C);
        $txt = ltrim($txt," ");
        $txt = rtrim($txt," ");
        return trim(mb_convert_kana($txt, "ASKV", "utf-8"));
    }
}
