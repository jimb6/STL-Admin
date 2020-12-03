<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Requests\CollectionRecordStoreRequest;
use App\Http\Requests\CollectionRecordUpdateRequest;
use App\Models\Agent;
use App\Models\CollectionRecord;
use App\Models\CollectionStatus;
use Illuminate\Http\Request;

class CollectionRecordController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-any', CollectionRecord::class);

        $search = $request->get('search', '');

        $collectionRecords = CollectionRecord::search($search)
            ->latest()
            ->paginate();

        return view(
            'app.collection_records.index',
            compact('collectionRecords', 'search')
        );
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', CollectionRecord::class);

        $agents = Agent::pluck('name', 'id');
        $collectionStatuses = CollectionStatus::pluck('name', 'id');

        return view(
            'app.collection_records.create',
            compact('agents', 'collectionStatuses')
        );
    }

    /**
     * @param \App\Http\Requests\CollectionRecordStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CollectionRecordStoreRequest $request)
    {
        $this->authorize('create', CollectionRecord::class);

        $validated = $request->validated();

        $collectionRecord = CollectionRecord::create($validated);

        return redirect()->route('collection-records.edit', $collectionRecord);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\CollectionRecord $collectionRecord
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, CollectionRecord $collectionRecord)
    {
        $this->authorize('view', $collectionRecord);

        return view('app.collection_records.show', compact('collectionRecord'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\CollectionRecord $collectionRecord
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, CollectionRecord $collectionRecord)
    {
        $this->authorize('update', $collectionRecord);

        $agents = Agent::pluck('name', 'id');
        $collectionStatuses = CollectionStatus::pluck('name', 'id');

        return view(
            'app.collection_records.edit',
            compact('collectionRecord', 'agents', 'collectionStatuses')
        );
    }

    /**
     * @param \App\Http\Requests\CollectionRecordUpdateRequest $request
     * @param \App\Models\CollectionRecord $collectionRecord
     * @return \Illuminate\Http\Response
     */
    public function update(
        CollectionRecordUpdateRequest $request,
        CollectionRecord $collectionRecord
    ) {
        $this->authorize('update', $collectionRecord);

        $validated = $request->validated();

        $collectionRecord->update($validated);

        return redirect()->route('collection-records.edit', $collectionRecord);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\CollectionRecord $collectionRecord
     * @return \Illuminate\Http\Response
     */
    public function destroy(
        Request $request,
        CollectionRecord $collectionRecord
    ) {
        $this->authorize('delete', $collectionRecord);

        $collectionRecord->delete();

        return redirect()->route('collection-records.index');
    }
}
