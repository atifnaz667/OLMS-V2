<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		$questions =  Question::inRandomOrder()->mcq()->limit(5)->get();
        $test = new Test();
        $test->created_by = Auth::id();
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Test $test)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Test $test)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Test $test)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Test $test)
	{
		//
	}
}
