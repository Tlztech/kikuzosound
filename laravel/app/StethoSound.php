<?php

namespace App;

use File;
use Log;
use Illuminate\Support\Facades\Config;

class StethoSound extends Model
{
    // 0:監修中
    public static $STATUS_SUPERVISING = 0;
    // 1:監修済
    public static $STATUS_SUPERVISED = 1;
    // 2:公開中
    public static $STATUS_PUBLIC = 2;
    // 3:公開中（New）
    public static $STATUS_PUBLIC_NEW = 3;
    protected $dates = ['created_at', 'updated_at'];

    protected $fillable = [
        'sound_path',
        'title',
        'type',
        'area',
        'conversion_type',
        'is_normal',
        'disease',
        'description',
        'status',
        'image_name',
        'is_public',
        'disip_order',
        'user_id',
        'updated_at',
    ];

    /**
     * 聴診音に紐付いたユーザ（監修者）を取得します.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * 聴診音に紐付いたクイズ一覧を取得します.
     */
    public function quizzes()
    {
        return $this->belongsToMany('App\Quiz');
    }

    /**
     * ステータスが『公開』の聴診音を取得します.
     *
     * @param mixed $query
     */
    public function scopePublicAll($query)
    {
        return $query->where('is_public', '=', true);
    }

    /**
     * ステータスが『公開（New）』の聴診音を取得します.
     *
     * @param mixed $query
     */
    public function scopePublicNewAll($query)
    {
        return $query->where('status', '=', StethoSound::$STATUS_PUBLIC_NEW);
    }

    /**
     * 指定IDの聴診音を取得します.
     *
     * @param mixed $query
     * @param mixed $ids
     */
    public function scopeSelectID($query, $ids)
    {
        return $query->where(function ($query) use ($ids) {
            foreach ($ids as $id) {
                $query->orWhere('id', '=', $id);
            }
        });

        // 1つだけの時の例
    // return $query->where('id', '=', $id);
    }

    public function scopeSearch($query, $keywords)
    {
        if (empty($keywords)) {
            return $query;
        }
        return $query->where(function ($q) use ($keywords) {
            $no_space_keywords = preg_replace("/\s\s+/","",$keywords);
            $where_raw_like_string = "%{$no_space_keywords}%";
            if(Config::get('app.locale') == "en"){
                $q->orWhere('title_en', 'like', "%{$keywords}%")
                  ->orWhere('description_en', 'like', "%{$keywords}%")
                  ->orWhere('sub_description_en', 'like', "%{$keywords}%")
                  ->orWhere('image_description_en', 'like', "%{$keywords}%")
                  ->orWhere('disease_en', 'like', "%{$keywords}%")
                  ->orWhere('area_en', 'like', "%{$keywords}%")
                  //strips html tags on db
                  ->orWhereRaw('regexp_replace(description_en, "<[^>]+>|\r|\n|\&(nbsp;)|(amp;)|\s\s+/|\r/g", "") like ?', [$where_raw_like_string])
                  ->orWhereRaw('regexp_replace(sub_description_en, "<[^>]+>|\r|\n|\&(nbsp;)|(amp;)|\s\s+/|\r/g", "") like ?', [$where_raw_like_string])
                  ->orWhereRaw('regexp_replace(image_description_en, "<[^>]+>|\r|\n|\&(nbsp;)|(amp;)|\s\s+/|\r/g", "") like ?', [$where_raw_like_string]);
            }else{
                $q->orWhere('title', 'like', "%{$keywords}%")
                  ->orWhere('description', 'like', "%{$keywords}%")
                  ->orWhere('sub_description', 'like', "%{$keywords}%")
                  ->orWhere('image_description', 'like', "%{$keywords}%")
                  ->orWhere('disease', 'like', "%{$keywords}%")
                  ->orWhere('area', 'like', "%{$keywords}%")
                  //strips html tags on db
                  ->orWhereRaw('regexp_replace(description, "<[^>]+>|\r|\n|\&(nbsp;)|(amp;)|\s\s+/|\r/g", "") like ?', [$where_raw_like_string])
                  ->orWhereRaw('regexp_replace(sub_description, "<[^>]+>|\r|\n|\&(nbsp;)|(amp;)|\s\s+/|\r/g", "") like ?', [$where_raw_like_string])
                  ->orWhereRaw('regexp_replace(image_description, "<[^>]+>|\r|\n|\&(nbsp;)|(amp;)|\s\s+/|\r/g", "") like ?', [$where_raw_like_string]);
            }


            //search type
            if ('肺音' == $keywords) {
                $q->orWhere('type', 1);
            } elseif ('心音' == $keywords) {
                $q->orWhere('type', 2);
            } elseif ('腸音' == $keywords) {
                $q->orWhere('type', 3);
            } elseif ('その他' == $keywords || 'そのた' == $keywords) {
                $q->orWhere('type', 9);
            }

            //search sound type
            if ('正常音' == $keywords || '正常' == $keywords) {
                $q->orWhere('is_normal', 1);
            } elseif ('異常音' == $keywords || '異常' == $keywords) {
                $q->orWhere('is_normal', 0);
            }
        });
    }

    public function scopeSearchTitle($query, $keywords)
    {
        if (empty($keywords)) {
            return $query;
        }
        return $query->where(function ($q) use ($keywords) {
            //search Title
            if(Config::get('app.locale') == "en"){
                $q->orWhere('title_en', 'like', "%{$keywords}%");
            }else{
                $q->orWhere('title', 'like', "%{$keywords}%");
            }

            //search type
            if ('肺音' == $keywords) {
                $q->orWhere('type', 1);
            } elseif ('心音' == $keywords) {
                $q->orWhere('type', 2);
            } elseif ('腸音' == $keywords) {
                $q->orWhere('type', 3);
            } elseif ('その他' == $keywords || 'そのた' == $keywords) {
                $q->orWhere('type', 9);
            }

            //search sound type
            if ('正常音' == $keywords || '正常' == $keywords) {
                $q->orWhere('is_normal', 1);
            } elseif ('異常音' == $keywords || '異常' == $keywords) {
                $q->orWhere('is_normal', 0);
            }

            // foreach ($keywords as $v) {
            //     $q->orWhere('title', 'like', "%{$v}%")
            //        ->orWhere('sub_description', 'like', "%{$v}%" ); //subdescription
            // }
            // foreach ($keywords as $v) {
            //     $q->orWhere('title_en', 'like', "%{$v}%")
            //        ->orWhere('sub_description', 'like', "%{$v}%" ); //subdescription
            // }
            // foreach ($keywords as $v) {
            //     if ('肺音' == $v) {
            //         $q->orWhere('type', 1);
            //     } elseif ('心音' == $v) {
            //         $q->orWhere('type', 2);
            //     } elseif ('腸音' == $v) {
            //         $q->orWhere('type', 3);
            //     } elseif ('その他' == $v || 'そのた' == $v) {
            //         $q->orWhere('type', 9);
            //     }
            // }
            // foreach ($keywords as $v) {
            //     if ('正常音' == $v || '正常' == $v) {
            //         $q->orWhere('is_normal', 1);
            //     } elseif ('異常音' == $v || '異常' == $v) {
            //         $q->orWhere('is_normal', 0);
            //     }
            // }
        });
    }

    public function scopeType($query, $type)
    {
        if (empty($type)) {
            return $query;
        }
        return $query->where('type', '=', $type);
    }

    public function scopeIsNormal($query, $isNormal) 
    {
        if (!is_null($isNormal) && ($isNormal!="")) {
            return $query->where('is_normal', $isNormal);
        }
    }

    public function scopeLibType($query, $lib_type)
    {
        if (is_null($lib_type) || ($lib_type=="")) {
            $lib_type = 0 ;
        }
        return $query->where('lib_type', $lib_type);
    }

    public function scopeGroupAttribute($query, $university_id)
    {
        return $query->where(function($groups) use ($university_id) {
            $groups->whereHas('exam_groups', function($query1) use ($university_id){
                $query1->where('exam_group_id',$university_id);//has group attribute
            })->orHas('exam_groups', '<', 1);//has no group attribute
        });
    }

    public function images()
    {
        return $this->hasMany('App\StethoSoundImage')->orderBy('disp_order');
    }

    public function images_ja()
    {
        return $this->hasMany('App\StethoSoundImage')->where("lang", "ja")->orderBy('disp_order');
    }

    public function images_en()
    {
        return $this->hasMany('App\StethoSoundImage')->where("lang", "en")->orderBy('disp_order');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    /**
     * レコードを削除する際に聴診音ファイルも削除します。
     */
    public function delete()
    {
        if ($this->attributes['sound_path']) {
            $path = $this->attributes['sound_path'];
            $full_path = public_path($path);
            if (File::isFile($full_path) && File::delete($full_path)) {
                Log::info('聴診音を削除しました　path='.$full_path);
            } else {
                Log::error('聴診音の削除に失敗しました　path='.$full_path);
            }
        }
        parent::delete();
    }

    public function exam_groups()
    {
        return $this->belongsToMany("App\ExamGroup", 'pivot_exam_group_stetho_sound');
    }

}
