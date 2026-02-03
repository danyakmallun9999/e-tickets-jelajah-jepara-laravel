<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Services\FileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function index(Request $request): View
    {
        $query = Event::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->search.'%')
                ->orWhere('location', 'like', '%'.$request->search.'%');
        }

        $events = $query->latest('start_date')->paginate(10);

        return view('admin.events.index', compact('events'));
    }

    public function create(): View
    {
        return view('admin.events.create');
    }

    public function store(StoreEventRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image'] = $this->fileService->upload($request->file('image'), 'events');
        }

        if (! isset($validated['is_published'])) {
            $validated['is_published'] = false;
        }

        // Handle checkbox specific behavior if needed (html checkboxes don't send anything if unchecked)
        // Check if is_published is in request? The validation rule 'sometimes|boolean' handles it if present.
        // If not checking specifically for presence here, it might be missed if unchecked in update?
        // store method handles new creation, default false if not present is correct.

        Event::create($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dibuat!');
    }

    public function edit(Event $event): View
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $this->fileService->delete($event->image);
            $validated['image'] = $this->fileService->upload($request->file('image'), 'events');
        }

        $validated['is_published'] = $request->has('is_published');

        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil diperbarui!');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $this->fileService->delete($event->image);

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus!');
    }
}
