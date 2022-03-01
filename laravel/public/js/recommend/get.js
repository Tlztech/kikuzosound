
async function getRecommendWithTensorflow(params) {
    const data = await getRecommendForTensorflow(params.base_url + params.learning_data_path);
    const model = await tf.loadLayersModel(params.base_url + params.model_path);
    const libDataReq = await fetch(params.base_url + params.recommended_data_path + '/' + params.user_id);
    const libData = await libDataReq.json();
    let lib_lds = [];
    for (let i = 0; i < Object.keys(libData).length; i++) {
        lib_lds.push(libData[i].lib_id);
    }
    const tensorData = convertToTensor(data);
    const predictedData = execPredict(model, lib_lds, tensorData);
    for (let i = 0; i < Object.keys(libData).length; i++) {
        let predict = predictedData.find(v=> v.x == libData[i].lib_id)
        if (!predict) { continue; }
        libData[i]['predict'] = predict.y;
    }
    libData.sort((a, b) => {
        // correct_ratio
        if(a.correct_ratio !== b.correct_ratio){
            return (a.correct_ratio - b.correct_ratio) * 1
        }
        // diff
        if(a.diff !== b.diff){
            return (a.diff - b.diff) * 1
        }
        // predict
        if(a.predict !== b.predict){
            return (a.predict - b.predict) * -1
        }
        return 0
    })

    return libData;
    // get data
    // var show_data = {};
    // for (let i = 0; i < Object.keys(libData).length; i++) {
    //     let libRow = libData[i];
    //     if (!('lib_type' in libRow)) { continue; }
    //     if (show_data[libRow.lib_type]) { continue; };
    //     show_data[libRow.lib_type] = libRow;
    //     // break
    //     if (show_data.length >= 7 ) { break; }
    // }
    // return show_data;
}



