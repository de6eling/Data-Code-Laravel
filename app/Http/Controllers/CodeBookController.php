<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Response;

class CodeBookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return 'hey';
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd($request->all());
        DB::table('code')
            ->insertGetId([
                'sheetId' => $request->codeSheet,
                'name' => $request->codeName,
                'description' => $request->codeDescription
            ]);
        return back()->with('success', 'Your code has been added to this sheet');
    }

    public function codeToPoint(Request $request)
    {
        DB::table('datapoint_has_code')
            ->insert([
                'codeId' => $request->codeId,
                'datapointId' => $request->pointId,
                'userId' => Auth::id()
            ]);


        return Response::json('worked');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function codeFromPoint($codeId, $pointId)
    {
        DB::table('datapoint_has_code')->where([
            ['codeId', '=', $codeId],
            ['datapointId', '=', $pointId],
            ['userId', '=', Auth::id()]
        ])->delete();

        return Response::json($codeId);
    }
}
