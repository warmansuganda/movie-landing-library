<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Movie;
use App\Member;
use App\Lending;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('pages.home');
    }

    public function totalMovie()
    {
        return response()->json(Movie::count());
    }

    public function totalMember()
    {
        return response()->json(Member::count());
    }

    public function lendingChart()
    {
        $labels = [];
        $datas = [];

        for ($month=1; $month <= 12; $month++) {
            $labels[] = date('F', mktime(0, 0, 0, $month, 10));

            $current_month = str_pad($month, 2, "0", STR_PAD_LEFT);
            $start_date = "2019-{$current_month}-01";
            $end_date = \Carbon\Carbon::parse($start_date)->format('Y-m-t');

            $datas[] = Lending::where('lending_date', '>=', $start_date)->where('lending_date', '<=', $end_date)->count();
        }

        return response()->json([
            'labels' => $labels,
            'datas' => $datas
        ]);
    }
}
