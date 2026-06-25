<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogPostResource;
use App\Models\BlogPost;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Public
 *
 * Public blog endpoints for the marketing website.
 */
class PublicBlogPostController extends Controller
{
    /**
     * List published blog posts.
     *
     * @unauthenticated
     */
    public function index(): AnonymousResourceCollection
    {
        $posts = BlogPost::query()
            ->published()
            ->latest('published_at')
            ->paginate(9);

        return BlogPostResource::collection($posts);
    }

    /**
     * Show a published blog post by slug.
     *
     * @unauthenticated
     */
    public function show(string $slug): BlogPostResource
    {
        $post = BlogPost::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return (new BlogPostResource($post))->withContent();
    }
}
