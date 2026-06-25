<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBlogPostRequest;
use App\Http\Requests\Admin\UpdateBlogPostRequest;
use App\Http\Resources\BlogPostResource;
use App\Models\BlogPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * @group Blog posts
 *
 * Admin blog post endpoints. Requires the matching `site_pages.*` permission listed on each endpoint.
 */
class BlogPostController extends Controller
{
    /**
     * List blog posts for the admin dashboard.
     *
     * Requires permission: `site_pages.view`.
     */
    public function index(): AnonymousResourceCollection
    {
        $posts = BlogPost::query()
            ->latest('published_at')
            ->latest()
            ->paginate(15);

        return BlogPostResource::collection($posts);
    }

    /**
     * Store a new blog post.
     *
     * Requires permission: `site_pages.create`.
     */
    public function store(StoreBlogPostRequest $request): JsonResponse
    {
        $data = $this->normalizePublishingData($request->validated());

        $post = BlogPost::query()->create($data);

        return (new BlogPostResource($post))
            ->withContent()
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Show a blog post.
     *
     * Requires permission: `site_pages.view`.
     */
    public function show(BlogPost $blogPost): BlogPostResource
    {
        return (new BlogPostResource($blogPost))->withContent();
    }

    /**
     * Update a blog post.
     *
     * Requires permission: `site_pages.update`.
     */
    public function update(UpdateBlogPostRequest $request, BlogPost $blogPost): BlogPostResource
    {
        $data = $this->normalizePublishingData($request->validated(), $blogPost);
        $blogPost->update($data);

        return (new BlogPostResource($blogPost->refresh()))->withContent();
    }

    /**
     * Delete a blog post.
     *
     * Requires permission: `site_pages.delete`.
     */
    public function destroy(BlogPost $blogPost): Response
    {
        $blogPost->delete();

        return response()->noContent();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function normalizePublishingData(array $data, ?BlogPost $existing = null): array
    {
        $isPublished = array_key_exists('is_published', $data)
            ? (bool) $data['is_published']
            : ($existing?->is_published ?? false);

        if ($isPublished) {
            $data['is_published'] = true;

            if (empty($data['published_at']) && empty($existing?->published_at)) {
                $data['published_at'] = now();
            }
        }

        if (array_key_exists('is_published', $data) && ! $data['is_published']) {
            $data['published_at'] = null;
        }

        return $data;
    }
}
