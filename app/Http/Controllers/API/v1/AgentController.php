<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Http\Requests\AgentStoreRequest;
use App\Http\Requests\AgentUpdateRequest;
use App\Models\Agent;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
=======
use App\Models\Agent;
use Illuminate\Http\Request;
use App\Http\Requests\AgentStoreRequest;
use App\Http\Requests\AgentUpdateRequest;
>>>>>>> develop

class AgentController extends Controller
{
    /**
<<<<<<< HEAD
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

        return $request->wantsJson()?
            new JsonResponse([$agents], 200)
            : view('agent.agents', compact('agents', 'search'));
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|JsonResponse|Response
     */
    public function create(Request $request)
    {
        $this->authorize('create agents', Agent::class);

        return $request->wantsJson()?
            new JsonResponse([],200)
            :  view('app.agents.create');
    }

    /**
     * @param \App\Http\Requests\AgentStoreRequest $request
     * @return JsonResponse|RedirectResponse|Response
     */
    public function store(AgentStoreRequest $request)
    {
        $this->authorize('create agents', Agent::class);

=======
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

>>>>>>> develop
        $validated = $request->validated();

        $agent = Agent::create($validated);

<<<<<<< HEAD
        return $request->wantsJson()?
            new JsonResponse($agent,201)
            : redirect()->route('agents.edit', $agent);
    }

    /**
     * @param Request $request
     * @param Agent $agent
     * @return Application|Factory|View|JsonResponse|Response
     */
    public function show(Request $request, Agent $agent)
    {
        $this->authorize('view agents', $agent);

        return $request->wantsJson()?
            new JsonResponse([],302)
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

        return $request->wantsJson()?
            new JsonResponse([],200)
            : view('app.agents.edit', compact('agent'));
=======
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
>>>>>>> develop
    }

    /**
     * @param \App\Http\Requests\AgentUpdateRequest $request
<<<<<<< HEAD
     * @param Agent $agent
     * @return JsonResponse|RedirectResponse|Response
     */
    public function update(AgentUpdateRequest $request, Agent $agent)
    {
        $this->authorize('update agents', $agent);
=======
     * @param \App\Models\Agent $agent
     * @return \Illuminate\Http\Response
     */
    public function update(AgentUpdateRequest $request, Agent $agent)
    {
        $this->authorize('update', $agent);
>>>>>>> develop

        $validated = $request->validated();

        $agent->update($validated);

<<<<<<< HEAD
        return $request->wantsJson()?
            new JsonResponse([],202)
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

        return $request->wantsJson()?
            new JsonResponse($agent,200)
            : redirect()->route('agents.index');
=======
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
>>>>>>> develop
    }
}
