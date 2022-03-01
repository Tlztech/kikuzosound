<?php

namespace App\Http\Common;

class UnivAnalytics
{

    /**
     * sum time
     *
    */
    public function sum_time($sendTime)
    {
        $s = 0;
        foreach ($sendTime as $time) {
            list($hr, $min, $sec) = explode(':', $time);
            $time = 0;
            $s += (((int)$hr) * 60 * 60) + (((int)$min) * 60) + ((int)$sec);
        }
        $srtTime = gmdate("H:i:s", $s);
        return $srtTime;
    }
    
    /**
     * average time
     *
    */
    public function average($sendTime)
    {
        $s = 0;
        $av = 0;
        foreach ($sendTime as $time) {
            list($min, $sec) = explode(':', $time);
            $time = 0;
            $s += (((int)$min) * 60) + (int)$sec;
        }
        if ($s > 0)
            $av = $s / count($sendTime);
        $av = round($av);
        $srtTime = gmdate("i:s", $av);
        return $srtTime;
    }
    
    /**
     * Object to array
     *
    */
    public function object_to_array($data) 
    {
        if (is_array($data) || is_object($data)) {
            $result = array();
            foreach ($data as $key => $value) {
                $result[] = $value;
            }
            return $result;
        }
        return $data;
    }


    public function object_to_array_all($data) {
        if (is_array($data) || is_object($data)) {
            $result = array();
            foreach ($data as $key => $value) {
                $result[] = $value;
            }
            return $result;
        }
        return $data;
    }

    /**
     * sum of array
     *
    */
    public function sum($items)
    {
        $carry = 0;
        foreach ($items as $item) {

            $carry += $item;
        }
        return $carry;
    }
    
    
     /**
     * sum time
     *
     */
    public function sum_time_hourly($sendTime) {
        $s = 0;
        foreach ($sendTime as $time) {
            list ($hr, $min, $sec) = explode(':',$time);
            $time = 0;
            $s += (((int)$hr) * 60 * 60) + (((int)$min) * 60) + ((int)$sec);
        }
        // $srtTime = gmdate("H:i:s", $s);
        
        $dtF = new \DateTime('@0');
        $dtT = new \DateTime("@$s");
        $day = $dtF->diff($dtT)->format('%a');
        $hour = $dtF->diff($dtT)->format('%h');
        $min = $dtF->diff($dtT)->format('%i');
        $sec = $dtF->diff($dtT)->format('%s');
        
        $day = $day != '0' ? $day*24 : 0;
        $hT = $hour == '0' ? '00' : $day+$hour < 10 ? '0'.$hour : $day+$hour;
        $mT = $min == '0' ? '00' : $min < 10 ? '0'.$min : $min;
        $sT = $sec == '0' ? '00' : $sec < 10 ? '0'.$sec : $sec;
            
        
        return $hT.':'.$mT.':'.$sT ;
    }
    
    /**
     * average time
     *
     */
    public function average_hourly($sendTime) {
        $s = 0;
        $av = 0;
        foreach ($sendTime as $time) {
            list ($hr, $min, $sec) = explode(':',$time);
            $time = 0;
            $s += (((int)$hr) * 60 * 60) + (((int)$min) * 60) + ((int)$sec);
        }
        if($s > 0)
            $av = $s/count($sendTime);
        $av = round($av);
        $srtTime = gmdate("H:i:s", $av);
        return $srtTime;
    }
    
    /**
     * Get Log Analitics Average Time
     *
     */
    public function getRate($aquaire, $total) {
        $rate = $aquaire > 0 ? round($aquaire/$total * 100) ."%"  : "0%";
        return $rate;
    }
    
}