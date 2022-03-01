<?php

namespace App\Http\Services\Trial;

use DB;
use Exception;
use Carbon\Carbon;
use App\TrialMembers;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TrialService
{
    /**
     * @var TrialMembers
     */
    protected $trialMembers;

    /**
     * Injecting dependencies.
     *
     * @param TrialMembers $trialMembers
     */
    public function __construct(TrialMembers $trialMembers)
    {
        $this->trialMembers = $trialMembers;
    }

    /**
     * Retrieve All trialMemberss With Companies.
     *
     * @return obj
     */
    public function listAll()
    {
        return $this->trialMembers->all();
    }

    /**
     * Find trialMembers by id.
     *
     * @param int $id
     *
     * @return obj
     */
    public function findById($id)
    {
        return $this->trialMembers->find($id);
    }

    public function destroy($trial)
    {
        try {
            DB::beginTransaction();
            if ($trial->groups) {
                $trial->groups->delete();
            }
            if ($trial->identifications) {
                $trial->identifications->delete();
            }
            DB::table('trialmembers_professions')
                ->where('trialmember_id', $trial->id)
                ->update(['deleted_at'=> Carbon::now()->toDateTimeString()])
            ;
            DB::table('trialmembers_services')
                ->where('trialmember_id', $trial->id)
                ->update(['deleted_at'=> Carbon::now()->toDateTimeString()])
            ;
            $trial->delete();
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollback();
            return false;
        }
    }

    /**
     * Create Trial Members.
     *
     * @param array $data
     *
     * @return obj
     */
    public function createTrialMembers($data)
    {
        $storeData = [
            'mail' => $data['mail'],
            'mail_backup' => $data['mail'],
            'password' => bcrypt(config('trial.password')),
            'urltoken_at' => Carbon::now()->toDateTimeString(),
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ] + config('trial.keys');
        return $this->trialMembers->create($storeData);
    }

    /**
     * trial Members With All Relations.
     *
     * @return obj
     */
    public function trialMembersWithAllRelations()
    {
        return $this->trialMembers
            ->with('groups')
            ->with('professions')
            ->with('identifications')
            ->get()
        ;
    }

    /**
     * Trial Csv.
     *
     * @param mixed $value
     *
     * @return obj
     */
    public function trialCsvDownload()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Cache-Control' => 'no-cache ',
            'Content-Disposition' => 'attachment; filename=trialmembers_'.Carbon::now()->timestamp.'.csv',
            'Expires' => '0',
            'Pragma' => 'public',
        ];
        $collection = $this->trialMembersWithAllRelations();
        return $response = new StreamedResponse(function () use ($collection) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                '番号', 'メールアドレス', '氏名', '氏名(カナ)', '生年月日', '性別', '職種', '勤務先', '登録日', '登録時刻', '登録', '本人確認', '電話番号', '画像名',
            ]);
            foreach ($collection as $trial) {
                fputcsv($handle, [
                    $trial->id,
                    $trial->mail,
                    $trial->name1.' '.$trial->name2,
                    $trial->kana1.' '.$trial->kana2,
                    $trial->yearSelect.'-'.sprintf('%02d', $trial->monthSelect).'-'.sprintf('%02d', $trial->daySelect),
                    ('male' == $trial->gender) ? '男' : '女',
                    $trial->professions->first()->profession_name,
                    $trial->groups->group_name,
                    date_format(date_create($trial->created_at), 'Y-m-d'),
                    date_format(date_create($trial->created_at), 'H:i:s'),
                    (1 == $trial->status_flag) ? '本' : '仮',
                    ('none' == $trial->type) ? '後で' : (('tel' == $trial->type) ? '電話' : '画像'),
                    (is_null($trial->identifications->tel)) ? '--' : $trial->identifications->tel,
                    (is_null($trial->identifications->image_name)) ? '--' : url('/').$trial->identifications->image_path.$trial->identifications->image_name,
                ]);
            }
            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Trial Xlsx.
     *
     * @param mixed $value
     *
     * @return obj
     */
    public function trialXlsxDownload()
    {
        $collection = $this->trialMembersWithAllRelations();
        foreach ($collection as $item) {
            $xlsx_file[] = [
                '番号' => $item->id,
                'メールアドレス' => $item->mail,
                '氏名' => $item->name1.' '.$item->name2,
                '氏名(カナ)' => $item->kana1.' '.$item->kana2,
                '生年月日' => $item->yearSelect.'-'.sprintf('%02d', $item->monthSelect).'-'.sprintf('%02d', $item->daySelect),
                '性別' => ('male' == $item->gender) ? '男' : '女',
                '職種' => $item->professions->first()->profession_name,
                '勤務先' =>  $item->groups->group_name,
                '登録日' => date_format(date_create($item->created_at), 'Y-m-d'),
                '登録時刻' => date_format(date_create($item->created_at), 'H:i:s'),
                '登録' => (1 == $item->status_flag) ? '本' : '仮',
                '本人確認' => ('none' == $item->type) ? '後で' : (('tel' == $item->type) ? '電話' : '画像'),
                '電話番号' => (is_null($item->identifications->tel)) ? '--' : $item->identifications->tel,
                '画像名' => (is_null($item->identifications->image_name)) ? '--' : url('/').$item->identifications->image_path.$item->identifications->image_name,
            ];
        }
        return \Excel::create('trialmembers_'.Carbon::now()->timestamp, function ($excel) use ($xlsx_file) {
            $excel->sheet('trialmember', function ($sheet) use ($xlsx_file) {
                $sheet->setAutoSize(true);
                $sheet->fromArray($xlsx_file);
            });
        })->download('xlsx');
    }

    /**
     * Register Trial Members.
     *
     * @param array $data
     *
     * @return obj
     */
    public function registerTrialMembers($data)
    {
        try {
            DB::beginTransaction();

            $now = Carbon::now()->toDateTimeString();
            $trialMember = $this->createTrialMembers($data);
            $trialMember->professions()->attach(config('trial.profession'), [
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $trialMember->services()->attach(config('trial.service'), [
                'disp_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            DB::table('groups')->insert([
                'trialmember_id' => $trialMember->id,
                'group_name' => config('trial.group_name'),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            DB::table('identification')->insert([
                'trialmember_id' => $trialMember->id,
                'type' => 'none',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::commit();
            return $trialMember;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }
}
