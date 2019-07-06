<?php

namespace App\Http\Controllers\Api;

use App\Report;
use App\Notification;


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


    public function productStore(Request $request, $id)
    {
        $request['type'] = "product";
        $this->store($request, $id);
    }

    public function postStore(Request $request, $id)
    {
        $request['type'] = "post";
        $this->store($request, $id);
    }

    public function userStore(Request $request, $id)
    {
        $request['type'] = "user";
        $this->store($request, $id);
    }

    public function shopStore(Request $request, $id)
    {
        $request['type'] = "shop";
        $this->store($request, $id);
    }

    public function faqStore(Request $request, $id)
    {
        $request['type'] = "faq";
        $this->store($request, $id);
    }

    public function conversationStore(Request $request, $id)
    {
        $request['type'] = "conversation";
        $this->store($request, $id);
    }

    public function reviewStore(Request $request, $id)
    {
        $request['type'] = "review";
        $this->store($request, $id);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $user = Auth::user();
        $request['user_id'] = $user->id;
        $request['parent_id'] = $id;

        $report = Report::create($request->all());

        Notification::create([
            "receiver_id" => 0,
            "receiver_type" => "admin",
            "parent_id" => $report->id,
            "parent_type" => "report",
            "description" => "A new report has been generated for a " . $request['type'] . " by " . $user->name,
            'created_at' => now(),
            'updated_at' => now()
        ]);

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

    //Super Admin
    public function all()
    {
        if (Auth::user()->is_super_admin) {
            return response()->json(Report::all());
        } else {
            return response()->json(401);
        }
    }

    public function setInActive($id)
    {
        $this->setStatus($id, '0');
    }

    public function setActive($id)
    {
        $this->setStatus($id, '1');
    }

    private function setStatus($id, $status)
    {
        if (Auth::user()->is_super_admin) {

            $report = Report::find($id)->update([
                "is_active" => $status
            ]);
            return response()->json($report);
        } else {
            return response()->json(401);
        }
    }
}
