<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Sanctum\Sanctum;

class AgentController extends Controller
{
    /**
     * AgentController constructor.
     */
    public function __construct()
    {

    }


    public function index()
    {
        return \Auth::user();
    }

    public function create()
    {
        //

    }

    public function store(Request $request)
    {
        //
        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect()->get(['view.agents']);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }





}
