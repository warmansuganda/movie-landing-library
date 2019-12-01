<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Movie;
use App\Member;
use App\Lending;

class LendingMovieController extends Controller
{
    public function __construct()
    {
        view()->share([
            'module' => 'lending-movie'
        ]);
    }

    public function index()
    {
        return view('pages.lending-movie.index', []);
    }

    public function data(Request $request)
    {
        $query = Movie::data();

        $query->whereDoesntHave('lendings', function ($query) {
            $query->whereNull('returned_date');
        });

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

    public function members(Request $request)
    {
        $keyword = $request->input('q');
        $page = $request->input('page');

        $members = Member::isActive()->whereLike('name', $keyword);
        $total_count = $members->count();

        $offset = 0;
        if (!empty($page) && $page > 1) {
            $offset = ($page - 1) * 30;
        }

        $items = $members->offset($offset)->limit(30)->get();

        return response()->json([
            'total_count' => $total_count,
            'items' => $items
        ]);
    }

    public function create($id)
    {
        $data = Movie::find(decrypt($id));
        if ($data) {
            return view('pages.lending-movie.create', ['data' => $data]);
        }

        return abort(404);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = \Validator::make($data, [
            'movie_id'      => 'required',
            'member_id'     => 'required',
            'return_date' => 'required|date_format:d/m/Y',
        ]);

        if ($validator->fails()) {
            return $this->responseError($validator->errors(), 422, 'Unprocessable Entity');
        } else {
            return Lending::createOne([
                'movie_id'      => $data['movie_id'],
                'member_id'     => $data['member_id'],
                'lending_date'  => date('Y-m-d'),
                'return_date' => \Carbon\Carbon::createFromFormat('d/m/Y', $data['return_date'])->format('Y-m-d')
            ]);
        }
    }

}
