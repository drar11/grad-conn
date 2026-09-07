<?php

namespace App\Http\Controllers;

use App\Services\FacebookPageService;
use App\Services\SocialFeedService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class FacebookWebhookController extends Controller
{
    public function verify(Request $request): Response
    {
        abort_unless($request->query('hub_mode') === 'subscribe', 403);
        abort_unless(hash_equals((string) config('services.facebook.webhook_verify_token'), (string) $request->query('hub_verify_token')), 403);

        return response((string) $request->query('hub_challenge'), 200)->header('Content-Type', 'text/plain');
    }

    public function receive(Request $request, FacebookPageService $facebook): Response
    {
        $secret = (string) config('services.facebook.app_secret');
        $signature = (string) $request->header('X-Hub-Signature-256');
        abort_unless($secret !== '' && hash_equals('sha256='.hash_hmac('sha256', $request->getContent(), $secret), $signature), 403);

        foreach ($request->input('entry', []) as $entry) {
            foreach ($entry['changes'] ?? [] as $change) {
                if (($change['field'] ?? '') !== 'feed') {
                    continue;
                }
                $value = $change['value'] ?? [];
                $postId = (string) ($value['post_id'] ?? '');
                if ($postId === '') {
                    continue;
                }
                if (($value['verb'] ?? '') === 'remove') {
                    $facebook->archive($postId);
                } elseif (in_array(($value['item'] ?? ''), ['post', 'status', 'photo', 'video', 'share'], true)) {
                    $facebook->import($postId);
                }
            }
        }
        SocialFeedService::forgetEventCache();

        return response('EVENT_RECEIVED', 200);
    }
}
