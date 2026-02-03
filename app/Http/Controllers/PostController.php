<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Services\FileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function index(Request $request): View
    {
        $query = Post::latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $posts = $query->paginate(10);

        return view('admin.posts.index', compact('posts'));
    }

    public function create(): View
    {
        return view('admin.posts.create');
    }

    public function store(StorePostRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $validated['slug'] = Str::slug($validated['title']).'-'.time();
        $validated['is_published'] = $request->has('is_published');

        if (! $validated['published_at'] && $validated['is_published']) {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('image')) {
            $validated['image_path'] = $this->fileService->upload($request->file('image'), 'posts');
        }

        Post::create($validated);

        return redirect()->route('admin.posts.index')
            ->with('status', 'Berita/Event berhasil ditambahkan.');
    }

    public function show(Post $post): RedirectResponse
    {
        return redirect()->route('admin.posts.edit', $post);
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        $validated = $request->validated();

        if ($post->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']).'-'.time();
        }

        $validated['is_published'] = $request->has('is_published');

        if ($request->hasFile('image')) {
            $this->fileService->delete($post->image_path);
            $validated['image_path'] = $this->fileService->upload($request->file('image'), 'posts');
        }

        $post->update($validated);

        return redirect()->route('admin.posts.index')
            ->with('status', 'Berita/Event berhasil diperbarui.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->fileService->delete($post->image_path);

        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('status', 'Post successfully deleted!');
    }

    public function uploadImage(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($request->hasFile('file')) {
            $url = $this->fileService->upload($request->file('file'), 'uploads/content');

            return response()->json(['location' => $url]);
        }

        return response()->json(['error' => 'No file uploaded'], 400);
    }
}
