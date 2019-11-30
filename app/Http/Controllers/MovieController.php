<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Movie;

class MovieController extends Controller
{
    public function __construct()
    {
        view()->share([
            'module' => 'movie'
        ]);
    }

    public function index()
    {
        return view('pages.movie.index', []);
    }

    public function data(Request $request)
    {
        $query = Movie::data();
        $results = \DataTables::of($query)
            ->filter(function($query) use ($request) {
                if ($request['release_date'] != '') {
                    $query->where(function($query) use ($request){
                        list($date_start, $date_end) = explode(' - ', $request['release_date']);
                        $query->where('release_date', '>=' , \Carbon\Carbon::createFromFormat('d/m/Y', $date_start)->format('Y-m-d'));
                        $query->where('release_date', '<=' , \Carbon\Carbon::createFromFormat('d/m/Y', $date_end)->format('Y-m-d'));
                    });
                }
                if ($request['title'] != '')
                    $query->whereLike('title', $request['title']);
                if ($request['genre'] != '')
                    $query->whereLike('genre', $request['genre']);
            })
            ->addColumn('id', function($query) {
                return encrypt($query->id);
            })
            ->addColumn('checkbox', function($query) {
                return true;
            })
            ->addColumn('release_date', function($query) {
                return $query->release_date ? $query->release_date->format('d/m/Y') : '-' ;
            })
            ->addColumn('created_at', function($query) {
                return $query->created_at ? $query->created_at->format('d/m/Y') : '-' ;
            })
            ->make(true)
            ->getData(true);
        return response()->json($results);
    }

    public function create()
    {
        return view('pages.movie.create', []);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = \Validator::make($data, [
            'title' => 'required|max:255',
            'genre' => 'required',
            'release_date' => 'required|date_format:d/m/Y',
        ]);

        if ($validator->fails()) {
            return $this->responseError($validator->errors(), 422, 'Unprocessable Entity');
        } else {
            return Movie::createOne([
                'title'        => $data['title'],
                'genre'        => $data['genre'],
                'release_date' => \Carbon\Carbon::createFromFormat('d/m/Y', $data['release_date'])->format('Y-m-d')
            ]);
        }
    }

    public function edit($id)
    {
        $data = Movie::find(decrypt($id));
        if ($data) {
            return view('pages.movie.edit', ['data' => $data]);
        }

        return abort(404);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $validator = \Validator::make($data, [
            'title' => 'required|max:255',
            'genre' => 'required',
            'release_date' => 'required|date_format:d/m/Y',
        ]);

        if ($validator->fails()) {
            return $this->responseError($validator->errors(), 422, 'Unprocessable Entity');
        } else {
            return Movie::updateOne(decrypt($id), [
                'title'        => $data['title'],
                'genre'        => $data['genre'],
                'release_date' => \Carbon\Carbon::createFromFormat('d/m/Y', $data['release_date'])->format('Y-m-d')
            ]);
        }
    }

    public function destroy(Request $request, $id)
    {
        return Movie::deleteOne(decrypt($id));
    }

    public function destroys(Request $request)
    {
        $data = $request->all();

        $id = [];
        foreach ($data['_id'] as $value) {
            $id[] = decrypt($value);
        }

        return Movie::transaction(function() use ($id) {
            return Movie::deleteBatch($id);
        });
    }
}
