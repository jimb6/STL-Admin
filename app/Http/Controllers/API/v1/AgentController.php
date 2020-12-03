<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;
use App\Http\Requests\AgentStoreRequest;
use App\Http\Requests\AgentUpdateRequest;

class AgentController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', Agent::class);

        $search = $request->get('search', '');

        $agents = Agent::search($search)
            ->latest()
            ->paginate();

        return view('app.agents.index', compact('agents', 'search'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', Agent::class);

        return view('app.agents.create');
    }

    /**
     * @param \App\Http\Requests\AgentStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AgentStoreRequest $request)
    {
        $this->authorize('create', Agent::class);

        $validated = $request->validated();

        $agent = Agent::create($validated);

        return redirect()->route('agents.edit', $agent);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Agent $agent
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Agent $agent)
    {
        $this->authorize('view', $agent);

        return view('app.agents.show', compact('agent'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Agent $agent
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Agent $agent)
    {
        $this->authorize('update', $agent);

        return view('app.agents.edit', compact('agent'));
    }

    /**
     * @param \App\Http\Requests\AgentUpdateRequest $request
     * @param \App\Models\Agent $agent
     * @return \Illuminate\Http\Response
     */
    public function update(AgentUpdateRequest $request, Agent $agent)
    {
        $this->authorize('update', $agent);

        $validated = $request->validated();

        $agent->update($validated);

        return redirect()->route('agents.edit', $agent);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Agent $agent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Agent $agent)
    {
        $this->authorize('delete', $agent);

        $agent->delete();

        return redirect()->route('agents.index');
    }
}
