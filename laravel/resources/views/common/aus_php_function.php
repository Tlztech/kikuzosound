<?php 
    function checkAusculaideAtrribute($sound){
            $sound_path = json_decode($sound->sound_path);
            $config = json_decode($sound->configuration);
            // aptm heart
            $a = isset($sound_path->a_sound_path) && isset($config->a) && $config->a->x && $config->a->y && $config->a->r ? $sound_path->a_sound_path : "";
            $p = isset($sound_path->p_sound_path) && isset($config->p) && $config->p->x && $config->p->y && $config->p->r ? $sound_path->p_sound_path : "";
            $t = isset($sound_path->t_sound_path) && isset($config->t) && $config->t->x && $config->t->y && $config->t->r ? $sound_path->t_sound_path : "";
            $m = isset($sound_path->m_sound_path) && isset($config->m) && $config->m->x && $config->m->y && $config->m->r ? $sound_path->m_sound_path : "";
        
            $isHeart = 0;
            if ($a || $p || $t || $m) {
            $isHeart = 1;
            } else {
            // h1-h4
            for ($i = 1; $i <= 4; $i++) {
                $h = isset($sound_path->{"h" . $i . "_sound_path"}) && isset($config->{"h" . $i}) && $config->{"h" . $i}->x && $config->{"h" . $i}->y && $config->{"h" . $i}->r ? $sound_path->{"h" . $i . "_sound_path"} : "";
                if ($h) {
                $isHeart = 1;
                }
            }
            }
            // aptm pulse
            $pa = isset($sound_path->pa_sound_path) && isset($config->a) && $config->a->x && $config->a->y && $config->a->r ? $sound_path->pa_sound_path : "";
            $pp = isset($sound_path->pp_sound_path) && isset($config->p) && $config->p->x && $config->p->y && $config->p->r ? $sound_path->pp_sound_path : "";
            $pt = isset($sound_path->pt_sound_path) && isset($config->t) && $config->t->x && $config->t->y && $config->t->r ? $sound_path->pt_sound_path : "";
            $pm = isset($sound_path->pm_sound_path) && isset($config->m) && $config->m->x && $config->m->y && $config->m->r ? $sound_path->pm_sound_path : "";
        
            $isPulse = 0;
            if ($pa || $pp || $pt || $pm) {
            $isPulse = 1;
            } else {
            // p1-p4
            for ($i = 1; $i <= 4; $i++) {
                $p = isset($sound_path->{"p" . $i . "_sound_path"}) && isset($config->{"h" . $i}) && $config->{"h" . $i}->x && $config->{"h" . $i}->y && $config->{"h" . $i}->r ? $sound_path->{"p" . $i . "_sound_path"} : "";
                if ($p) {
                $isPulse = 1;
                }
            }
            }
            // front lung
            $tr1 = isset($sound_path->tr1_sound_path) && isset($config->tr1) && $config->tr1->x && $config->tr1->y && $config->tr1->r ? $sound_path->tr1_sound_path : "";
            $tr2 = isset($sound_path->tr2_sound_path) && isset($config->tr2) && $config->tr2->x && $config->tr2->y && $config->tr2->r ? $sound_path->tr2_sound_path : "";
            $br1 = isset($sound_path->br1_sound_path) && isset($config->br1) && $config->br1->x && $config->br1->y && $config->br1->r ? $sound_path->br1_sound_path : "";
            $br2 = isset($sound_path->br2_sound_path) && isset($config->br2) && $config->br2->x && $config->br2->y && $config->br2->r ? $sound_path->br2_sound_path : "";
        
            $isLung = 0;
            if ($tr1 || $tr2 || $br1 || $br2) {
            $isLung = 1;
            } else {
            for ($i = 1; $i <= 6; $i++) {
                $ve = isset($sound_path->{"ve" . $i . "_sound_path"}) && isset($config->{"ve" . $i}) && $config->{"ve" . $i}->x && $config->{"ve" . $i}->y && $config->{"ve" . $i}->r ? $sound_path->{"ve" . $i . "_sound_path"} : "";
                if ($ve) {
                $isLung = 1;
                }
            };
            }
        
            if($sound->type==1 & !$isLung){
            $isHeart = 0;
            }
        
            // back lung
            $br3 = isset($sound_path->br3_sound_path) && isset($config->br3) && $config->br3->x && $config->br3->y && $config->br3->r ? $sound_path->br3_sound_path : "";
            $br4 = isset($sound_path->br4_sound_path) && isset($config->br4) && $config->br4->x && $config->br4->y && $config->br4->r ? $sound_path->br4_sound_path : "";
            
            $isLungBack = 0;
            if ($br3 || $br4) {
            $isLungBack = 1;
            } else {
            for ($i = 7; $i <= 12; $i++) {
                $ve = isset($sound_path->{"ve" . $i . "_sound_path"}) && isset($config->{"ve" . $i}) && $config->{"ve" . $i}->x && $config->{"ve" . $i}->y && $config->{"ve" . $i}->r ? $sound_path->{"ve" . $i . "_sound_path"} : "";
                if ($ve) {
                $isLungBack = 1;
                }
            };
            }

            return (object) array(
                "isHeart" => $isHeart,
                "isPulse" => $isPulse,
                "isLung" => $isLung,
                "isLungBack" => $isLungBack
            );
    }
    
?>