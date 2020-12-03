<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use App\Models\CollectionStatus;
use App\Http\Requests\CollectionStatusStoreRequest;
use App\Http\Requests\CollectionStatusUpdateRequest;

class CollectionStatusController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', CollectionStatus::class);

        $search = $request->get('search', '');

        $collectionStatuses = CollectionStatus::search($search)
            ->latest()
            ->paginate();

        return view(
            'app.collection_statuses.index',
            compact('collectionStatuses', 'search')
        );
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', CollectionStatus::class);

        return view('app.collection_statuses.create');
    }

    /**
     * @param \App\Http\Requests\CollectionStatusStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CollectionStatusStoreRequest $request)
    {
        $this->authorize('create', CollectionStatus::class);

        $validated = $request->validated();

        $collectionStatus = CollectionStatus::create($validated);

        return redirect()->route('collection-statuses.edit', $collectionStatus);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\CollectionStatus $collectionStatus
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, CollectionStatus $collectionStatus)
    {
        $this->authorize('view', $collectionStatus);

        return view(
            'app.collection_statuses.show',
            compact('collectionStatus')
        );
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\CollectionStatus $collectionStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, CollectionStatus $collectionStatus)
    {
        $this->authorize('update', $collectionStatus);

        return view(
            'app.collection_statuses.edit',
            compact('collectionStatus')
        );
    }

    /**
     * @param \App\Http\Requests\CollectionStatusUpdateRequest $request
     * @param \App\Models\CollectionStatus $collectionStatus
     * @return \Illuminate\Http\Response
     */
    public function update(
        CollectionStatusUpdateRequest $request,
        CollectionStatus $collectionStatus
    ) {
        $this->authorize('update', $collectionStatus);

        $validated = $request->validated();

        $collectionStatus->update($validated);

        return redirect()->route('collection-statuses.edit', $collectionStatus);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\CollectionStatus $collectionStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(
        Request $request,
        CollectionStatus $collectionStatus
    ) {
        $this->authorize('delete', $collectionStatus);

        $collectionStatus->delete();

        return redirect()->route('collection-statuses.index');
    }
}
