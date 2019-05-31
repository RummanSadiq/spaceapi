<?php

namespace App\Http\Controllers\Api;

use App\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Report::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    public function productStore(Request $request)
    {
        $request['type'] = "product";
        $this->store($request);
    }

    public function postStore(Request $request)
    {
        $request['type'] = "post";
        $this->store($request);
    }

    public function userStore(Request $request)
    {
        $request['type'] = "user";
        $this->store($request);
    }

    public function shopStore(Request $request)
    {
        $request['type'] = "shop";
        $this->store($request);
    }

    public function faqStore(Request $request)
    {
        $request['type'] = "faq";
        $this->store($request);
    }

    public function conversationStore(Request $request)
    {
        $request['type'] = "conversation";
        $this->store($request);
    }

    public function reviewStore(Request $request)
    {
        $request['type'] = "review";
        $this->store($request);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $request['user_id'] = $user->id;

        $report = Report::create($request->all());

        return response()->json($report, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        //
    }
}
