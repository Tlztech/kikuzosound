<!DOCTYPE html>
<html>
<head>
    <title>sample get recommend</title>
    <!-- START: Get Recommend  -->
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@2.0.0/dist/tf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs-vis@1.0.2/dist/tfjs-vis.umd.min.js"></script>
    <script src="{{asset('js/recommend/common.js')}}"></script>
    <script src="{{asset('js/recommend/get.js')}}"></script>
    <script>
    window.addEventListener('load', async (event) =>  {
    var params = {
        'user_id'              : 346,
        'base_url'             : window.location.protocol + '//' + location.host,
        'model_path'           : '/tf_model/model.json',
        'learning_data_path'   : '/api/get_learning_data',
        'recommended_data_path': '/api/get_recommended_data'
    }
    var libdata = await getRecommendWithTensorflow(params);
    //console.log(libdata);
    });
    </script>
    <!-- END: Get Recommend  -->
</head>
<body>

</body>

</html>