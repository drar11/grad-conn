<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

final class FacebookPageService
{
    public function import(string $postId): ?Event
    {
        $response = Http::timeout(15)->get($this->graphUrl($postId), [
            'fields' => 'id,message,full_picture,permalink_url,created_time,from',
            'access_token' => $this->token(),
        ]);

        if ($response->failed()) {
            throw new RuntimeException('Facebook post lookup failed: '.$response->body());
        }

        $post = $response->json();
        $message = trim((string) ($post['message'] ?? ''));
        if ($message === '') {
            return null;
        }

        $authorId = User::query()->where('role', 'admin')->orderBy('id')->value('id');
        if (! $authorId) {
            throw new RuntimeException('An admin account is required before Facebook posts can be imported.');
        }

        $firstLine = trim(Str::before($message, "\n"));

        return Event::query()->updateOrCreate(
            ['source_post_id' => (string) $post['id']],
            [
                'title' => Str::limit($firstLine, 180, '…'),
                'content' => $message,
                'category' => 'news',
                'posted_by' => $authorId,
                'source' => 'facebook',
                'source_name' => data_get($post, 'from.name', 'Facebook Page'),
                'source_url' => $post['permalink_url'] ?? null,
                'external_image_url' => $post['full_picture'] ?? null,
                'is_archived' => false,
                'created_at' => $post['created_time'] ?? now(),
            ]
        );
    }

    public function archive(string $postId): void
    {
        Event::query()->where('source_post_id', $postId)->update(['is_archived' => true, 'archived_at' => now()]);
        SocialFeedService::forgetEventCache();
    }

    private function graphUrl(string $path): string
    {
        return 'https://graph.facebook.com/'.config('services.facebook.graph_version').'/'.ltrim($path, '/');
    }

    private function token(): string
    {
        return (string) config('services.facebook.page_access_token');
    }
}
