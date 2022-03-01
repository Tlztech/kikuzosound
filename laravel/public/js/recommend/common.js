
async function getRecommendForTensorflow(learning_data_url) {
    const DataReq = await fetch(learning_data_url);
    const Data = await DataReq.json();
    const cleaned = Data.map(v => ({
        correct_rate: parseInt(v.correct_rate),
        lib_id: parseInt(v.lib_id),
    }))
    .filter(v => (v.correct_rate != null && v.lib_id != null));
    return cleaned;
}


function execPredict(model, inputs, normalizationData) {
    const {inputMax, inputMin, labelMin, labelMax} = normalizationData;  
    const inputTensor = tf.tensor2d(inputs, [inputs.length, 1]);
    const normalizedInputs = inputTensor.sub(inputMin).div(inputMax.sub(inputMin));
    const [xs, preds] = tf.tidy(() => {
        const preds = model.predict(normalizedInputs);
        const unNormXs = normalizedInputs.mul(inputMax.sub(inputMin)).add(inputMin);
        const unNormPreds = preds.mul(labelMax.sub(labelMin)).add(labelMin);
        return [unNormXs.dataSync(), unNormPreds.dataSync()];
    });
    
    const predictedPoints = Array.from(xs).map((val, i) => {
        return {
            x: val,
            y: preds[i]
        }
    });
	return predictedPoints;
}

function convertToTensor(data) {
    return tf.tidy(() => {
        tf.util.shuffle(data);
        const inputs = data.map(d => d.lib_id)
        const labels = data.map(d => d.correct_rate);
        const inputTensor = tf.tensor2d(inputs, [inputs.length, 1]);
        const labelTensor = tf.tensor2d(labels, [labels.length, 1]);
        const inputMax = inputTensor.max();
        const inputMin = inputTensor.min(); 
        const labelMax = labelTensor.max();
        const labelMin = labelTensor.min();
        const normalizedInputs = inputTensor.sub(inputMin).div(inputMax.sub(inputMin));
        const normalizedLabels = labelTensor.sub(labelMin).div(labelMax.sub(labelMin));
        return {
            inputs: normalizedInputs,
            labels: normalizedLabels,
            inputMax,
            inputMin,
            labelMax,
            labelMin,
        }
    });
}