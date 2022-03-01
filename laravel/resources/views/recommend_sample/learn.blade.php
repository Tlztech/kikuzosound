<!DOCTYPE html>
<html>
<head>
    <title>sample learn model</title>
    
    <!-- START: Learn Recommend  -->
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@2.0.0/dist/tf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs-vis@1.0.2/dist/tfjs-vis.umd.min.js"></script>
    <script src="js/recommend/common.js"></script>
    <script src="js/recommend/learn.js"></script>
    <script>
    window.addEventListener('load', async (event) =>  {
    var params = {
        'base_url'             : window.location.protocol + '//' + location.host,
        'learning_data_path'   : '/api/get_learning_data',
        'save_model'           : '/api/save_model'
    }
    await makeRecommendModel(params);
    });
    </script>
    <!-- END: Learn Recommend  -->


</head>

<body>
</body>
</html>