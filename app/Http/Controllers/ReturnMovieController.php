<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lending;
use Illuminate\Validation\Rule;

class ReturnMovieController extends Controller
{
    public function __construct()
    {
        view()->share([
            'module' => 'return-movie'
        ]);
    }

    public function index()
    {
        return view('pages.return-movie.index', []);
    }

    public function data(Request $request)
    {
        $query = Lending::data()->with(['member', 'movie']);

        $results = \DataTables::of($query)
            ->filter(function($query) use ($request) {
                if ($request['lending_date'] != '') {
                    $query->where(function($query) use ($request){
                        list($date_start, $date_end) = explode(' - ', $request['lending_date']);
                        $query->where('lending_date', '>=' , \Carbon\Carbon::createFromFormat('d/m/Y', $date_start)->format('Y-m-d'));
                        $query->where('lending_date', '<=' , \Carbon\Carbon::createFromFormat('d/m/Y', $date_end)->format('Y-m-d'));
                    });
                }
                if ($request['title'] != ''){
                    $query->whereHas('movie', function($query) use ($request){
                        $query->whereLike('title', $request['title']);
                    });
                }
                if ($request['member_name'] != ''){
                    $query->whereHas('member', function($query) use ($request){
                        $query->whereLike('name', $request['member_name']);
                    });
                }
                if ($request['return_date'] != '') {
                    $query->where(function($query) use ($request){
                        list($date_start, $date_end) = explode(' - ', $request['return_date']);
                        $query->where('return_date', '>=' , \Carbon\Carbon::createFromFormat('d/m/Y', $date_start)->format('Y-m-d'));
                        $query->where('return_date', '<=' , \Carbon\Carbon::createFromFormat('d/m/Y', $date_end)->format('Y-m-d'));
                    });
                }
                if ($request['status'] != ''){
                    switch ($request['status']) {
                        case '1':
                            $query->where(function($query){
                                $query->whereNotNull('returned_date');
                            });
                            break;
                        case '2':
                            $query->where(function($query){
                                $query->whereNull('returned_date');
                                $query->where('return_date', '>', date('Y-m-d'));
                            });
                            break;
                        case '3':
                            $query->where(function($query){
                                $query->whereNull('returned_date');
                                $query->where('return_date', '<=', date('Y-m-d'));
                            });
                            break;
                    }
                }
            })
            ->addColumn('id', function($query) {
                return encrypt($query->id);
            })
            ->addColumn('lending_date', function($query) {
                return $query->lending_date ? $query->lending_date->format('d/m/Y') : '-' ;
            })
            ->addColumn('return_date', function($query) {
                return $query->return_date ? $query->return_date->format('d/m/Y') : '-' ;
            })
            ->addColumn('returned_date', function($query) {
                return $query->returned_date ? $query->returned_date->format('d/m/Y') : '-' ;
            })
            ->addColumn('lateness_charge', function($query) {
                return $query->lateness_charge ? number_format($query->lateness_charge,2,",",".") : '-' ;
            })
            ->make(true)
            ->getData(true);
        return response()->json($results);
    }

    public function edit($id)
    {
        $data = Lending::find(decrypt($id));
        if ($data) {
            return view('pages.return-movie.edit', ['data' => $data]);
        }

        return abort(404);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $validator = \Validator::make($data, [
            'lateness_charge' => Rule::requiredIf(function () use ($data) {
                return $data['notes'] == 'late';
            })
        ]);

        if ($validator->fails()) {
            return $this->responseError($validator->errors(), 422, 'Unprocessable Entity');
        } else {
            return Lending::updateOne(decrypt($id), [
                'lateness_charge' => $data['lateness_charge'] ? $this->toNumber($data['lateness_charge']) : null,
                'returned_date'   => date('Y-m-d')
            ]);
        }
    }

    private function toNumber($value)
    {
        $str = preg_replace('/\./', '', $value);
        return str_replace(',', '.', $str);
    }

}
