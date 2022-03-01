var myTabs=[];
var myWindow = null;
$(document).ready(function(){
    if(localStorage.getItem('offline')){
        let all_active_tabs = JSON.parse(localStorage.getItem('offline'));
        all_active_tabs.forEach(function(activeId){
            if(document.getElementById('offline_switch_'+activeId)){
                document.getElementById('offline_switch_'+activeId).switchButton('on', true);
            }
        });
    }
    
     
    $('.offline-btn-switch').on('click', function () {
        let offline_storage = localStorage.getItem('offline');
        let active_tabs = [];
        if(offline_storage){
            active_tabs = JSON.parse(offline_storage);
        }
        var id = $(this).data("id");
        var data = $("#aus_"+id).attr("data-result");
        var sound = JSON.parse(data);
        var tab_name ="aus"+id;
        var offline_switch = $("#offline_switch_"+id);
        var switch_stats = offline_switch[0].checked;
        if(!switch_stats){
            document.getElementById('offline_switch_'+id).switchButton('on', true);
            var version = new Date(sound.updated_at);
            var new_tab = SITEURL + "/ausculaide_offline/"+id+"?v="+Date.parse(version)+"&asset_ver="+Date.parse(new Date());
            myTabs[tab_name] = window.open(new_tab, tab_name);
            active_tabs.push(id);
            localStorage.setItem('offline',JSON.stringify(active_tabs));
        }else{
            document.getElementById('offline_switch_'+id).switchButton('off', true);
            if(myTabs[tab_name]){
                const del_index = active_tabs.indexOf(id);
                if (del_index > -1) {
                    active_tabs.splice(del_index, 1);
                }
                localStorage.setItem('offline',JSON.stringify(active_tabs));
                myTabs[tab_name].close();
            }
        }
    });

});