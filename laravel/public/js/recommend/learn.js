async function makeRecommendModel(params) {
    
    //******************************************************************
    // getData
    //******************************************************************
    const data = await getRecommendForTensorflow(params.base_url + params.learning_data_path);
    const values = data.map(d => ({
        x: d.lib_id,
        y: d.correct_rate,
    }));
    tfvis.render.scatterplot(
        {name: 'lib_id vs correct_rate'},
        {values}, 
        {
            xLabel: 'lib_id',
            yLabel: 'correct_rate',
            height: 300
        }
    );
    
    //******************************************************************
    // createModel
    //******************************************************************
    const model = createModel();
    tfvis.show.modelSummary({name: 'Model Summary'}, model);
    function createModel() {
        const model = tf.sequential();
        model.add(tf.layers.dense({
            inputShape: [1],
            units: 1,
            useBias: true
        }));
        model.add(tf.layers.dense({
            units: 16,
            useBias: true
        }));
        model.add(tf.layers.dense({
            units: 1,
            useBias: true,
            activation: 'softplus'
        }));
        return model;
    }

    const tensorData = convertToTensor(data);
    const {inputs, labels} = tensorData;
    
    //console.log('Start Training');
    
    //******************************************************************
    // trainModel
    //******************************************************************
    await trainModel(model, inputs, labels);
    async function trainModel(model, inputs, labels) {
        model.compile({
            optimizer: tf.train.adam(),
            loss: tf.losses.meanSquaredError,
            metrics: ['mse'],
        });
        const batchSize = 28;
        const epochs = 10;
        return await model.fit(inputs, labels, {
            batchSize,
            epochs,
            shuffle: true,
            callbacks: tfvis.show.fitCallbacks(
                { name: 'Training Performance' },
                ['loss', 'mse'], 
                { 
                    height: 200, 
                    callbacks: ['onEpochEnd'] 
                }
            )
        });
    }
    //console.log('Done Training');
    const save_result = await model.save(params.base_url + params.save_model)
    //console.log(save_result);

    //******************************************************************
    // testModel
    //******************************************************************
    testModel(model, data, tensorData);
    function testModel(model, inputData, normalizationData) {
        const {inputMax, inputMin, labelMin, labelMax} = normalizationData;  
        const [xs, preds] = tf.tidy(() => {
            const xs = tf.linspace(0, 1, 100);
            const preds = model.predict(xs.reshape([100, 1]));
            const unNormXs = xs.mul(inputMax.sub(inputMin)).add(inputMin);
            const unNormPreds = preds.mul(labelMax.sub(labelMin)).add(labelMin);
            return [unNormXs.dataSync(), unNormPreds.dataSync()];
        });
        const originalPoints = inputData.map(d => (
            {
                x: d.lib_id,
                y: d.correct_rate,
            }
        ));
        const predictedPoints = Array.from(xs).map((val, i) => {
            return {
                x: val,
                y: preds[i]
            }
        });
        tfvis.render.scatterplot(
            {
                name: 'Model Predictions vs Original Data'
            },{
                values: [originalPoints, predictedPoints],
                series: ['original', 'predicted']
            },{
                xLabel: 'lib_id',
                yLabel: 'correct_rate',
                height: 300
            }
        );
    }
}

