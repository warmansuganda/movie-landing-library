<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Member;

class MemberController extends Controller
{
    public function __construct()
    {
        view()->share([
            'module' => 'member'
        ]);
    }

    public function index()
    {
        return view('pages.member.index', []);
    }

    public function data(Request $request)
    {
        $query = Member::data();
        $results = \DataTables::of($query)
            ->filter(function($query) use ($request) {
                if ($request['join_date'] != '') {
                    $query->where(function($query) use ($request){
                        list($date_start, $date_end) = explode(' - ', $request['join_date']);
                        $query->where('join_date', '>=' , \Carbon\Carbon::createFromFormat('d/m/Y', $date_start)->format('Y-m-d'));
                        $query->where('join_date', '<=' , \Carbon\Carbon::createFromFormat('d/m/Y', $date_end)->format('Y-m-d'));
                    });
                }
                if ($request['name'] != '')
                    $query->whereLike('name', $request['name']);
                if ($request['is_active'] != '')
                    $query->whereLike('is_active', $request['is_active']);
            })
            ->addColumn('id', function($query) {
                return encrypt($query->id);
            })
            ->addColumn('checkbox', function($query) {
                return true;
            })
            ->addColumn('join_date', function($query) {
                return $query->join_date ? $query->join_date->format('d/m/Y') : '-' ;
            })
            ->addColumn('age', function($query) {
                return $query->dob ? $query->dob->age : '-' ;
            })
            ->make(true)
            ->getData(true);
        return response()->json($results);
    }

    public function create()
    {
        return view('pages.member.create', []);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = \Validator::make($data, [
            'name'      => 'required|max:50',
            'dob'       => 'required|date_format:d/m/Y',
            'address'   => 'required',
            'telephone' => 'max:20',
            'identity'  => 'max:30',
            'join_date' => 'required|date_format:d/m/Y',
        ]);

        if ($validator->fails()) {
            return $this->responseError($validator->errors(), 422, 'Unprocessable Entity');
        } else {
            return Member::createOne([
                'name'      => $data['name'],
                'dob'       => \Carbon\Carbon::createFromFormat('d/m/Y', $data['dob'])->format('Y-m-d'),
                'address'   => $data['address'],
                'telephone' => $data['telephone'],
                'identity'  => $data['identity'],
                'join_date' => \Carbon\Carbon::createFromFormat('d/m/Y', $data['join_date'])->format('Y-m-d'),
                'is_active' => !empty($data['is_active']) ? 1 : 0,
            ]);
        }
    }

    public function edit($id)
    {
        $data = Member::find(decrypt($id));
        if ($data) {
            return view('pages.member.edit', ['data' => $data]);
        }

        return abort(404);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $validator = \Validator::make($data, [
            'name'      => 'required|max:50',
            'dob'       => 'required|date_format:d/m/Y',
            'address'   => 'required',
            'telephone' => 'max:20',
            'identity'  => 'max:30',
            'join_date' => 'required|date_format:d/m/Y',
        ]);

        if ($validator->fails()) {
            return $this->responseError($validator->errors(), 422, 'Unprocessable Entity');
        } else {
            return Member::updateOne(decrypt($id), [
                'name'      => $data['name'],
                'dob'       => \Carbon\Carbon::createFromFormat('d/m/Y', $data['dob'])->format('Y-m-d'),
                'address'   => $data['address'],
                'telephone' => $data['telephone'],
                'identity'  => $data['identity'],
                'join_date' => \Carbon\Carbon::createFromFormat('d/m/Y', $data['join_date'])->format('Y-m-d'),
                'is_active' => !empty($data['is_active']) ? 1 : 0,
            ]);
        }
    }

    public function destroy(Request $request, $id)
    {
        return Member::deleteOne(decrypt($id));
    }

    public function destroys(Request $request)
    {
        $data = $request->all();

        $id = [];
        foreach ($data['_id'] as $value) {
            $id[] = decrypt($value);
        }

        return Member::transaction(function() use ($id) {
            return Member::deleteBatch($id);
        });
    }
}
