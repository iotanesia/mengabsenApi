<?php 

namespace App\Services;

use App\ApiHelper;
use Carbon\Carbon;
use App\Models\SalaryData;
use App\Models\Salary as Models;
use App\Models\User;

class Salary {

    public static function users($request){
        return User::whereNotNull('nama_lengkap')->get(['id', 'email', 'nama_lengkap as nama'])->sortBy('nama_lengkap');
    }

    public static function getAll($request){
        $salaries = Models::whereMonth('date', $request->month)
        ->whereYear('date', $request->year)
        ->get()
        ->groupBy('date')
        ->map(function($items, $date) {
            return [
                'date' => $date,
                'status' => $items->first()->status,
                'data' => SalaryData::join('auth.users', 'users.id', '=', 'salary_data.user_id')
                    ->whereIn('salary_data.salary_id', $items->pluck('id'))
                    ->get([
                        'salary_data.salary_id',
                        'users.id as user_id',
                        'users.email',
                        'users.nama_lengkap as nama',
                        'salary_data.path',
                        'salary_data.filename'
                    ])
            ];
        })
        ->values()
        ->sortByDesc('date')
        ->sortByDesc('salary_id'); 

        if ($salaries->isEmpty()) {
            return [];
        }

        $datadump = $salaries->reduce(function($carry, $item) {
        return array_merge($carry, $item['data']->toArray());
        }, []);
    
        $date = explode('-', $salaries->first()['date']);
        $datacollect = collect($datadump);

        $datacollectdump = $datacollect->transform(function($item){ 
            // $tanggal = Models::where('id', $item['salary_id'])->value('date');

            // $bulan = explode('-', $tanggal);

            // $item['tanggal'] = $tanggal;

            // $item['bulan'] = Carbon::createFromFormat('!m', $bulan[1])->locale('id_ID')->translatedFormat('F');
            return $item;
        });


        $data[] = collect([
            'date' => $date[2] . '/' . $date[1] . '/' . $date[0],
            'month' => Carbon::createFromFormat('m', $date[1])->locale('id')->translatedFormat('F'),
            'status' => $salaries->first()['status'],
            'data' => $datacollectdump->toArray()
        ]);

        return $data;
    }

    public static function get($request){
        $data = Models::join('public.salary_data', 'salary_data.salary_id', '=', 'salary.id')
        ->whereMonth('salary.date', $request->month)
        ->whereYear('salary.date', $request->year)
        ->where('salary_data.user_id', $request->current_user->id)->get(['salary.date']);

        $data->transform(function($item){
          
            $dateArr = explode('-', $item->date);
            $item->date = $dateArr[2] . '/' . $dateArr[1] . '/' . $dateArr[0];
            $item->month = Carbon::createFromFormat('m', $dateArr[1])->locale('id')->translatedFormat('F');

            return $item;
        });

        return $data;
    }

    public static function create($request){
        $file = $request->file('file');
        $dateArr = explode('/', $request->date);
        $dateCon = $dateArr[2] . '-' . $dateArr[1] . '-' . $dateArr[0];

        $salary = new Models();
        $salary->date = $dateCon;
        $salary->status = true;
        $salary->save();

        $user = $request->user_id;
        $date = explode('-',$dateCon);

        foreach($user as $index => $value){

            $test = SalaryData::join('salary', 'salary_data.salary_id', '=', 'salary.id')
            ->where('salary_data.user_id', $user[$index])
            ->whereYear('salary.date', $date[0])
            ->whereMonth('salary.date', $date[1]);

            if($test->exists()){
                $dump = $test->first(['salary_data.id']);
                $salary_data = SalaryData::find($dump->id);
                $salary_data->delete();
            }
    
        
            $data = new SalaryData();
            $data->user_id = $user[$index];
            $data->salary_id = $salary->id;
            $data->filename = $file[$index]->getClientOriginalName();

            $hash = $file[$index]->hashname();
            $data->path = $hash;
            $file[$index]->storeAs(ApiHelper::SALARY_PATH, $hash);
            $data->save();
        }

        return Models::where('id', $salary->id)->get()->transform(function($item) {
            $date = explode('-', $item->date);
            $item->date = $date[2] . '/' . $date[1] . '/' . $date[0];

            $item->data = SalaryData::join('auth.users', 'users.id', '=', 'salary_data.user_id')->where('salary_data.salary_id', $item->id)->get(['salary_data.salary_id', 'users.id as user_id', 'users.email','users.nama_lengkap as nama', 'salary_data.path', 'salary_data.filename']);

            return $item;
        });

    }
}