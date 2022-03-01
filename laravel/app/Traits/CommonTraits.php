<?php

namespace App\Traits;

use Intervention\Image\Facades\Image;
use App\QuizChoice;

trait CommonTraits
{

	/**
	 * saveData
	 *
	 * @param  mixed $Model
	 * @param  mixed $params
	 * @param  mixed $request
	 * @param  mixed $type
	 * @return void
	 */
	public function saveData($Model, $params, $request, $type)
	{
		$data = $params;
		unset($params['group_attr']);
		foreach ($params as $key => $value) {
			$Model->$key = $value;
		}
		// $Model->lib_type = $type === 'xray' ? 5 : '';
		switch ($type) {
			case 'xray':
				$Model->lib_type = 5;
				break;
			case 'echo':
				$Model->lib_type = 6;
				break;
			default:
				break;
		}
		$Model->is_public = (2 == $params['status'] || 3 == $params['status']) ? 1 : 0;
		$Model->university_id = $this->university_id;
		$Model->save();

		if ($data['group_attr'] == 0) {
			$Model->exam_groups()->attach($Model->id, ['exam_group_id' => $params['exam_group']]);
		}
		$Model->save();


		if ($request->hasFile('image_path')) {

			$image_path = $this->moveFile($request, '/img/library_images/', $Model->id, 'image_path', 'tmp_img_image_path');

			// if ( is_null($image_path) ) {
			//     DB::rollback();
			//     Log::error('ファイルの移動に失敗しました: 元ファイルのパス: ' . public_path() . $image_path);
			//     $this->message = 'ファイルの移動に失敗しました: 元ファイルのパス: ';
			//     return $this->response();
			// }

			//save path
			$Model->image_path = $image_path;
			$Model->save();

			//resize large image
			list($w, $t) = getimagesize(public_path($image_path));
			if ($t > 512) {
				// resize
				$img = Image::make(public_path($image_path))->resize(null, 512, function ($constraint) {
					$constraint->aspectRatio();
				});
				// overwrite
				$img->save(public_path($image_path));
			}
			// remove the temp file and session as well
			$this->removeTmpFile($request->input('tmp_img_image_path'));
			\Session::forget('tmp_img_image_path');
		}
	}

	/**
	 * saveImagePath
	 *
	 * @param  mixed $request
	 * @param  mixed $Model
	 * @param  mixed $path
	 * @return void
	 */
	public function saveImagePath($request, $Model, $path)
	{
		if ($request->hasFile('image_path')) {

			$image_path = $this->moveFile($request, "/img/{$path}/", $Model->id, 'image_path', "tmp_{$path}_path");

			// if ( is_null($image_path) ) {
			//     DB::rollback();
			//     Log::error('ファイルの移動に失敗しました: 元ファイルのパス: ' . public_path() . $image_path);
			//     $this->message = 'ファイルの移動に失敗しました: 元ファイルのパス: ';
			//     return $this->response();
			// }

			//save path
			$Model->image_path = $image_path;
			$Model->save();

			//resize large image
			list($w, $t) = getimagesize(public_path($image_path));
			if ($t > 512) {
				// resize
				$img = Image::make(public_path($image_path))->resize(null, 512, function ($constraint) {
					$constraint->aspectRatio();
				});
				// overwrite
				$img->save(public_path($image_path));
			}
			// remove the temp file and session as well
			$this->removeTmpFile($request->input("tmp_{$path}_path"));
			\Session::forget("tmp_{$path}_path");
		} else {
			if (!$request->image_path) {
				$Model->image_path = '';
				$Model->save();
			}
		}
	}

	/**
	 * saveChoices
	 *
	 * @param  mixed $quiz_choice
	 * @param  mixed $quiz_id
	 * @param  mixed $k
	 * @param  mixed $v
	 * @param  mixed $params
	 * @param  mixed $answer_key
	 * @return void
	 */
	public function saveChoices($quiz_id, $k, $v, $params)
	{
		$quiz_choice = QuizChoice::firstOrNew(['id' => $v->key]);
		$quiz_choice->quiz_id = $quiz_id;
		$quiz_choice->title = $v->title;
		$quiz_choice->title_en = $v->title_en;
		$quiz_choice->disp_order = $k;
		$quiz_choice->is_correct = $v->checked;
		$quiz_choice->lib_type = $v->lib_key  == "final_answer" ? NULL : $v->lib_key;
		return $quiz_choice->save();
	}

	public function saveFillIn($type, $quiz_id, $params)
	{
		$lib_type = explode("_", $type)[0] . "_library";
		$quiz_choice = QuizChoice::firstOrNew(['id' => json_decode($params[$type])->key]);
		$quiz_choice->quiz_id = $quiz_id;
		$quiz_choice->lib_type = $this->getLibType($lib_type);
		$quiz_choice->disp_order = 0;
		$quiz_choice->is_correct = 1;
		$quiz_choice->is_fill_in = 1;
		$quiz_choice->title = json_decode($params[$type])->value;
		$quiz_choice->save();
	}

	public function saveFillInFinal($quiz_id, $params)
	{
		$quiz_choice = QuizChoice::firstOrNew(['id' => json_decode($params['final_fill_in'])->key]);
		$quiz_choice->quiz_id = $quiz_id;
		$quiz_choice->lib_type = NULL;
		$quiz_choice->disp_order = 0;
		$quiz_choice->is_correct = 1;
		$quiz_choice->is_fill_in = 1;
		$quiz_choice->title = json_decode($params['final_fill_in'])->value;
		$quiz_choice->save();
	}

	/**
	 * getLibType
	 *
	 * @param  mixed $lib
	 * @return void
	 */
	private function getLibType($lib)
	{
		switch ($lib) {
			case "iPax":
				return 1;
			case "palpation_library":
				return 2;
			case "ecg_library":
				return 3;
			case "inspection_library":
				return 4;
			case "xray_library":
				return 5;
			case "ucg_library":
				return 6;
			default:
				return 0;
		}
	}

	/**
	 * getLibType key for msg
	 *
	 * @param  mixed $lib
	 * @return void
	 */
	private function getLibMessageKey($lib)
	{
		switch ($lib) {
			case 1:
				return "ausculaide_success";
			case 2:
				return "palpation_success";
			case 3:
				return "ECG_success";
			case 4:
				return "inspection_success";
			case 5:
				return "xray_success";
			case 6:
				return "ucg_success";
			default:
				return "stetho_success";
		}
	}
}
