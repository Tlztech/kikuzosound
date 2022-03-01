
function setDataPoints(data,case_type){
    AUSCULAIDE_DATA = data;
	CASE_TYPE = case_type;
    const configuration = checkNullConfig(JSON.parse(AUSCULAIDE_DATA.configuration));
    const soundPath = JSON.parse(AUSCULAIDE_DATA.sound_path)
    CONFIGURATION_SETTINGS = configuration;
    STETHO_POSITIONS = {
        //heart
        cardiacsoundA: {cx: configuration.a.x, cy: configuration.a.y, r: configuration.a.r, type:"heart", setting_sound_point:"a_sound_path"},
        cardiacsoundP: {cx: configuration.p.x, cy: configuration.p.y, r: configuration.p.r, type:"heart", setting_sound_point:"p_sound_path"},
        cardiacsoundT: {cx: configuration.t.x, cy: configuration.t.y, r: configuration.t.r, type:"heart", setting_sound_point:"t_sound_path"},
        cardiacsoundM: {cx: configuration.m.x, cy: configuration.m.y, r: configuration.m.r, type:"heart", setting_sound_point:"m_sound_path"},

        heartsoundh1: {cx: configuration.h1.x, cy: configuration.h1.y, r: configuration.h1.r, type:"heart", setting_sound_point:"h1_sound_path"},
        heartsoundh2: {cx: configuration.h2.x, cy: configuration.h2.y, r: configuration.h2.r, type:"heart", setting_sound_point:"h2_sound_path"},
        heartsoundh3: {cx: configuration.h3.x, cy: configuration.h3.y, r: configuration.h3.r, type:"heart", setting_sound_point:"h3_sound_path"},
        heartsoundh4: {cx: configuration.h4.x, cy: configuration.h4.y, r: configuration.h4.r, type:"heart", setting_sound_point:"h4_sound_path"},

        //pulse
        cardiacsoundPA: {cx: configuration.a.x, cy: configuration.a.y, r: configuration.a.r, type:"pulse", setting_sound_point:"pa_sound_path"},
        cardiacsoundPP: {cx: configuration.p.x, cy: configuration.p.y, r: configuration.p.r, type:"pulse", setting_sound_point:"pp_sound_path"},
        cardiacsoundPT: {cx: configuration.t.x, cy: configuration.t.y, r: configuration.t.r, type:"pulse", setting_sound_point:"pt_sound_path"},
        cardiacsoundPM: {cx: configuration.m.x, cy: configuration.m.y, r: configuration.m.r, type:"pulse", setting_sound_point:"pm_sound_path"},

        pulse_waveP1: {cx: configuration.h1.x, cy: configuration.h1.y, r: configuration.h1.r, type:"pulse", setting_sound_point:"p1_sound_path"},
        pulse_waveP2: {cx: configuration.h2.x, cy: configuration.h2.y, r: configuration.h2.r, type:"pulse", setting_sound_point:"p2_sound_path"},
        pulse_waveP3: {cx: configuration.h3.x, cy: configuration.h3.y, r: configuration.h3.r, type:"pulse", setting_sound_point:"p3_sound_path"},
        pulse_waveP4: {cx: configuration.h4.x, cy: configuration.h4.y, r: configuration.h4.r, type:"pulse", setting_sound_point:"p4_sound_path"},

        //lungs
        trachealtr1: {cx: configuration.tr1.x, cy: configuration.tr1.y, r: configuration.tr1.r, type:"lung", setting_sound_point:"tr1_sound_path", body:"front"},
        trachealtr2: {cx: configuration.tr2.x, cy: configuration.tr2.y, r: configuration.tr2.r, type:"lung", setting_sound_point:"tr2_sound_path", body:"front"},
        // trachealtr3: {cx: configuration.tr3.x, cy: configuration.tr3.y},
        // trachealtr4: {cx: configuration.tr4.x, cy: configuration.tr4.y},
        
        bronchialbr1: {cx: configuration.br1.x, cy: configuration.br1.y, r: configuration.br1.r, type:"lung", setting_sound_point:"br1_sound_path", body:"front"},
        bronchialbr2: {cx: configuration.br2.x, cy: configuration.br2.y, r: configuration.br2.r, type:"lung", setting_sound_point:"br2_sound_path", body:"front"},
        bronchialbr3: {cx: configuration.br3.x, cy: configuration.br3.y, r: configuration.br3.r, type:"lung", setting_sound_point:"br3_sound_path", body:"back"},
        bronchialbr4: {cx: configuration.br4.x, cy: configuration.br4.y, r: configuration.br4.r, type:"lung", setting_sound_point:"br4_sound_path", body:"back"},

        alveolarve1: {cx: configuration.ve1.x, cy: configuration.ve1.y, r: configuration.ve1.r, type:"lung", setting_sound_point:"ve1_sound_path", body:"front"},
        alveolarve2: {cx: configuration.ve2.x, cy: configuration.ve2.y, r: configuration.ve2.r, type:"lung", setting_sound_point:"ve2_sound_path", body:"front"},
        alveolarve3: {cx: configuration.ve3.x, cy: configuration.ve3.y, r: configuration.ve3.r, type:"lung", setting_sound_point:"ve3_sound_path", body:"front"},
        alveolarve4: {cx: configuration.ve4.x, cy: configuration.ve4.y, r: configuration.ve4.r, type:"lung", setting_sound_point:"ve4_sound_path", body:"front"},
        alveolarve5: {cx: configuration.ve5.x, cy: configuration.ve5.y, r: configuration.ve5.r, type:"lung", setting_sound_point:"ve5_sound_path", body:"front"},
        alveolarve6: {cx: configuration.ve6.x, cy: configuration.ve6.y, r: configuration.ve6.r, type:"lung", setting_sound_point:"ve6_sound_path", body:"front"},

        alveolarve7: {cx: configuration.ve7.x, cy: configuration.ve7.y, r: configuration.ve7.r, type:"lung", setting_sound_point:"ve7_sound_path", body:"back"},
        alveolarve8: {cx: configuration.ve8.x, cy: configuration.ve8.y, r: configuration.ve8.r, type:"lung", setting_sound_point:"ve8_sound_path", body:"back"},
        alveolarve9: {cx: configuration.ve9.x, cy: configuration.ve9.y, r: configuration.ve9.r, type:"lung", setting_sound_point:"ve9_sound_path", body:"back"},
        alveolarve10: {cx: configuration.ve10.x, cy: configuration.ve10.y, r: configuration.ve10.r, type:"lung", setting_sound_point:"ve10_sound_path", body:"back"},
        alveolarve11: {cx: configuration.ve11.x, cy: configuration.ve11.y, r: configuration.ve11.r, type:"lung", setting_sound_point:"ve11_sound_path", body:"back"},
        alveolarve12: {cx: configuration.ve12.x, cy: configuration.ve12.y, r: configuration.ve12.r, type:"lung", setting_sound_point:"ve12_sound_path", body:"back"},
    };

    var sound_data = setSoundByCase(soundPath);
    AUS_SOUND = [];
    AUS_SOUND = sound_data.soundPath;
    ALL_SOUND = [];
    ALL_SOUND = sound_data.allSound;
}

function checkNullConfig(config){
    var configuration = {};
    for (var key in config) {
        var settings = {
            x: config[key].x ? Number(config[key]).x : 0,
            y: config[key].y ? Number(config[key]).y : 0,
            r: config[key].r ? Number(config[key]).r : 0
        }
        configuration[key]= settings;
    }
    return configuration;
}

function setSoundByCase(soundPath){
    var sound_path = {};
    var all_sound = [];
    for (var key in STETHO_POSITIONS) {
        var sound = "";
        var type =  STETHO_POSITIONS[key].type;
        var body = STETHO_POSITIONS[key].body;
        var setting_sound_key =  STETHO_POSITIONS[key].setting_sound_point;
        var version = new Date(AUSCULAIDE_DATA.updated_at);
        if(soundPath[setting_sound_key]){
        sound = soundPath[setting_sound_key]+"?v="+Date.parse(version);
        all_sound.push(sound);
            if(CASE_TYPE == "heart" && type==="heart"){
                sound_path[key] = sound;
            }
            else if(CASE_TYPE == "heart_pulse" && type=="pulse"){
                sound_path[key] = sound;
            }
            else if(CASE_TYPE == "heart_lungs" && (type=="heart" || (type=="lung" && body=="front")) ){
                sound_path[key] = sound;
            }
            else if(CASE_TYPE == "heart_pulse_lungs" && (type=="pulse" || (type=="lung" && body=="front")) ){
                sound_path[key] = sound;
            }
            else if(CASE_TYPE == "lungs" && (type=="lung" && body=="front")){
                sound_path[key] = sound;
            }
            else if(CASE_TYPE == "lungs_back" && (type=="lung" && body=="back")){
                sound_path[key] = sound;
            }else{

            }
        }

    }
    return {
        soundPath : sound_path,
        allSound : all_sound
    }
}

