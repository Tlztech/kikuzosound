/*
*normal
*/
function setAusCase(){
    const bodyImage = AUSCULAIDE_DATA.body_image ? AUSCULAIDE_DATA.body_image.replace(/^.*[\\\/]/, '') : null
    var node_data_list = {
        /*** cardio-normal ***/
            "001":
            {
                hide_step_buttons: true/*false*/,
                images : [
                    {type: "image", src: SITEURL+"/img/stetho_sound_images/description.jpg"},
                    {type: "image", src:  bodyImage ? SITEURL+"/img/library_images/"+bodyImage : SITEURL+"/img/no_image.png" },
                ],
        
                text: "",
        
                back: null,
                forward:  null,
        
                menu: [
                    { text: "Heart", action:"bodymap", param:{
                            type: "cardio",
                            sound: {
                            }
                        }
                    },
        
                ],
            },
        
        }
        
        CASE_LIST["ausculaide"] = {
            content_id : AUSCULAIDE_DATA.id,
            type : AUSCULAIDE_DATA.content_group,
            title : AUSCULAIDE_DATA.title,
    
            has_question: false,
            enable_memo: false,
            nodes: node_data_list
        
        };        
}
    