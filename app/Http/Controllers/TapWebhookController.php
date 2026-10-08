<?php
namespace App\Http\Controllers;

use App\Jobs\ProcessTapWebhook;
use App\Services\Payments\TapWebhookService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TapWebhookController extends Controller {
    public function handle(Request $request,
        TapWebhookService $TapWebhookService) {
        // 1. Verify webhook signature
        // 2. Extract event ID
        // 3. Store webhook event
        // 4. Dispatch processing job
        Log::info('Tap Webhook received', ($request->all()));
        Log::info('Tap Webhook Headers', [
            'hashstring' => $request->header('hashstring'),
            'hash'       => $request->header('hash'),
        ]);
        $payload = $request->all();

        $hashString = $request->header('hashstring');

        if (! $hashString) {
            Log::info('Missing webhook signature.');
            return response()->json([
                'message' => 'Missing webhook signature.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (! $TapWebhookService->verifySignature(
            $payload,
            $hashString
        )) {
            Log::info('Invalid webhook signature.');
            return response()->json([
                'message' => 'Invalid webhook signature.',
            ], Response::HTTP_UNAUTHORIZED);
        }
        Log::info('Tap Webhook signature verified.');
        $event = $TapWebhookService->receive($payload, $hashString);
        if ($event['is_new']) {
            ProcessTapWebhook::dispatch($event['event']->id);
            Log::info('ProcessTapWebhook Dispatched .');
        }
        Log::info('Tap Webhook processed successfully.');
        return response()->json([
            'received' => true,
        ]);

    }
}
