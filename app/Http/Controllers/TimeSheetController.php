<?php

namespace App\Http\Controllers;

use App\Models\TimeSheet;
use App\Http\Requests\StoreTimeSheetRequest;
use App\Http\Requests\UpdateTimeSheetRequest;

class TimeSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreTimeSheetRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTimeSheetRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TimeSheet  $timeSheet
     * @return \Illuminate\Http\Response
     */
    public function show(TimeSheet $timeSheet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TimeSheet  $timeSheet
     * @return \Illuminate\Http\Response
     */
    public function edit(TimeSheet $timeSheet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTimeSheetRequest  $request
     * @param  \App\Models\TimeSheet  $timeSheet
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTimeSheetRequest $request, TimeSheet $timeSheet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TimeSheet  $timeSheet
     * @return \Illuminate\Http\Response
     */
    public function destroy(TimeSheet $timeSheet)
    {
        //
    }
}
