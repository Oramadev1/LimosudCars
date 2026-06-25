<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogPostModuleTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_list_update_show_and_delete_blog_post(): void
    {
        $this->seed();

        $token = $this->adminToken();

        $createResponse = $this->withToken($token)->postJson('/api/admin/blog-posts', [
            'title' => 'Nouveau article',
            'slug' => 'nouveau-article',
            'excerpt' => 'Résumé court de l\'article.',
            'content' => 'Contenu complet de l\'article de blog.',
            'cover_image' => '/cars/rush.png',
            'is_published' => true,
        ]);

        $createResponse
            ->assertCreated()
            ->assertJsonPath('data.slug', 'nouveau-article')
            ->assertJsonPath('data.is_published', true)
            ->assertJsonPath('data.content', 'Contenu complet de l\'article de blog.');

        $post = BlogPost::where('slug', 'nouveau-article')->firstOrFail();
        $this->assertNotNull($post->published_at);

        $this->withToken($token)
            ->getJson('/api/admin/blog-posts')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'nouveau-article');

        $this->withToken($token)
            ->patchJson("/api/admin/blog-posts/{$post->id}", [
                'title' => 'Article mis à jour',
                'is_published' => false,
            ])
            ->assertOk()
            ->assertJsonPath('data.title', 'Article mis à jour')
            ->assertJsonPath('data.is_published', false)
            ->assertJsonPath('data.published_at', null);

        $this->withToken($token)
            ->getJson("/api/admin/blog-posts/{$post->id}")
            ->assertOk()
            ->assertJsonPath('data.slug', 'nouveau-article');

        $this->withToken($token)
            ->deleteJson("/api/admin/blog-posts/{$post->id}")
            ->assertNoContent();

        $this->assertSoftDeleted('blog_posts', ['id' => $post->id]);
    }

    public function test_public_blog_endpoints_return_only_published_posts(): void
    {
        $this->seed();

        BlogPost::query()->create([
            'title' => 'Article publié',
            'slug' => 'article-publie',
            'excerpt' => 'Extrait public.',
            'content' => 'Contenu public complet.',
            'cover_image' => '/cars/terios.png',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        BlogPost::query()->create([
            'title' => 'Brouillon',
            'slug' => 'brouillon',
            'excerpt' => 'Non visible.',
            'content' => 'Contenu privé.',
            'is_published' => false,
            'published_at' => null,
        ]);

        $this->getJson('/api/public/blog-posts')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'article-publie')
            ->assertJsonMissingPath('data.0.content');

        $this->getJson('/api/public/blog-posts/article-publie')
            ->assertOk()
            ->assertJsonPath('data.content', 'Contenu public complet.');

        $this->getJson('/api/public/blog-posts/brouillon')
            ->assertNotFound();
    }

    private function adminToken(): string
    {
        $response = $this->postJson('/api/admin/auth/login', [
            'email' => env('ADMIN_EMAIL', 'admin@limosudcars.local'),
            'password' => env('ADMIN_PASSWORD', 'password'),
        ]);

        $response->assertOk();

        return (string) $response->json('access_token');
    }
}
