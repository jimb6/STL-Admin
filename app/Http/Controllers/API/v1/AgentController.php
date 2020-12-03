<?php

namespace App\Http\Controllers\API\v1;


use App\Http\Requests\AgentStoreRequest;
use App\Models\Agent;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class AgentController extends Controller
{
    /**
     * @param Request $request
     * @return Application|Factory|View|JsonResponse|Response
     */
    public function index(Request $request)
    {
        $this->authorize('view agents', Agent::class);
        $search = $request->get('search', '');
        $agents = Agent::search($search)
            ->latest()
            ->paginate();
        $activeAgents = Agent::where('session_status', 1)->count();

        return $request->wantsJson() ?
            new JsonResponse([$agents], 200)
            : view('agent.agents', compact('agents', 'search', 'activeAgents'));
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|JsonResponse|Response
     */
    public function create(Request $request)
    {
        $this->authorize('create agents', Agent::class);

        return $request->wantsJson() ?
            new JsonResponse([], 200)
            : view('app.agents.create');
    }

    /**
     * @param \App\Http\Requests\AgentStoreRequest $request
     * @return JsonResponse|RedirectResponse|Response
     */
    public function store(AgentStoreRequest $request)
    {
        $this->authorize('create agents', Agent::class);
    }

    /**
     * @param Request $request
     * @param Agent $agent
     * @return Application|Factory|View|JsonResponse|Response
     */
    public function show(Request $request, Agent $agent)
    {
        $this->authorize('view agents', $agent);

        return $request->wantsJson() ?
            new JsonResponse([], 302)
            : view('app.agents.show', compact('agent'));
    }

    /**
     * @param Request $request
     * @param Agent $agent
     * @return Application|Factory|View|JsonResponse|Response
     */
    public function edit(Request $request, Agent $agent)
    {
        $this->authorize('update agents', $agent);

        return $request->wantsJson() ?
            new JsonResponse([], 200)
            : view('app.agents.edit', compact('agent'));

        return redirect()->route('agents.edit', $agent);
    }

    public function update(AgentUpdateRequest $request, Agent $agent)
    {
        $this->authorize('update', $agent);


        $validated = $request->validated();

        $agent->update($validated);

        return $request->wantsJson() ?
            new JsonResponse([], 202)
            : redirect()->route('agents.edit', $agent);
    }

    /**
     * @param Request $request
     * @param Agent $agent
     * @return JsonResponse|RedirectResponse|Response
     */
    public function destroy(Request $request, Agent $agent)
    {
        $this->authorize('delete agents', $agent);

        $agent->delete();

        return $request->wantsJson() ?
            new JsonResponse($agent, 200)
            : redirect()->route('agents.index');

        return redirect()->route('agents.edit', $agent);
    }

    public function activeAgents()
    {
//        $activeAgents = Agent::all()->where(['session_status' => true, 'update_at' => Carbon::now()]);
        return new JsonResponse([15], 200);
    }

}
