<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BevyEventService
{
    protected string $chapterId = '2886';
    protected string $chapterSlug = 'gdg-on-campus-multimedia-university-selangor-malaysia';

    /**
     * Synchronize events from the official GDG Bevy API.
     *
     * @param bool $fetchAllDetails Whether to fetch individual event endpoints for full banner/description
     * @return array
     */
    public function syncEvents(bool $fetchAllDetails = true): array
    {
        $url = "https://gdg.community.dev/api/chapter/{$this->chapterId}/event/";

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'GDGoC-MMU-Portal/1.0 (https://gdgmmu.com)',
                'Accept' => 'application/json',
            ])->timeout(25)->get($url);

            if (!$response->successful()) {
                Log::error('BevyEventService: Failed to fetch chapter events', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return [
                    'success' => false,
                    'message' => 'Failed to connect to Bevy API (HTTP ' . $response->status() . ').',
                    'count' => 0,
                ];
            }

            $data = $response->json();
            $events = $data['results'] ?? [];

            $defaultUserId = User::first()?->id ?? 1;
            $created = 0;
            $updated = 0;

            foreach ($events as $item) {
                $bevyId = $item['id'] ?? null;
                if (!$bevyId) continue;

                $startDate = isset($item['start_date']) ? Carbon::parse($item['start_date']) : now();
                $endDate = isset($item['end_date']) ? Carbon::parse($item['end_date']) : null;

                $existing = Event::where('bevy_id', $bevyId)->first();

                // Detailed fields defaults
                $description = $item['description_short'] ?? $item['description'] ?? null;
                $image = null;
                $location = 'MMU Cyberjaya';
                $eventType = 'Event';
                $tags = [];

                // Check if rich details should be fetched from individual event API
                $needsDetail = $fetchAllDetails && (
                    !$existing
                    || empty($existing->image)
                    || empty($existing->description)
                    || $existing->description === $existing->title
                    || $existing->description === 'Official GDGoC MMU Event'
                    || $startDate->isFuture()
                    || $startDate->isToday()
                );

                if ($needsDetail) {
                    try {
                        $detailRes = Http::withHeaders([
                            'User-Agent' => 'GDGoC-MMU-Portal/1.0',
                            'Accept' => 'application/json',
                        ])->timeout(12)->get("https://gdg.community.dev/api/event/{$bevyId}/");

                        if ($detailRes->successful()) {
                            $detail = $detailRes->json();
                            $description = !empty($detail['description']) ? $detail['description'] : ($detail['description_short'] ?? $item['title']);
                            $image = $detail['cropped_banner_url'] ?? $detail['picture']['url'] ?? $detail['banner']['url'] ?? null;
                            $location = $detail['venue_name'] ?? ($detail['venue_city'] ?? $location);
                            $eventType = $detail['event_type_title'] ?? $eventType;
                            $tags = $detail['tags'] ?? [];
                        }
                    } catch (\Exception $detailEx) {
                        Log::warning("BevyEventService: Could not fetch details for event {$bevyId}: " . $detailEx->getMessage());
                    }
                } elseif ($existing) {
                    $description = $existing->description ?: ($item['description_short'] ?? $item['title']);
                    $image = $existing->image;
                    $location = $existing->location;
                    $eventType = $existing->event_type;
                    $tags = $existing->tags;
                }

                if (empty($description)) {
                    $description = $item['description_short'] ?? $item['title'] ?? 'Official GDGoC MMU Event';
                }

                $eventData = [
                    'title' => $item['title'] ?? 'Untitled Event',
                    'description' => $description,
                    'date' => $startDate->toDateString(),
                    'time' => $startDate->toTimeString(),
                    'end_date' => $endDate,
                    'bevy_url' => $item['url'] ?? "https://gdg.community.dev/events/details/{$bevyId}/",
                    'image' => $image,
                    'location' => $location,
                    'event_type' => $eventType,
                    'tags' => $tags,
                    'user_id' => $defaultUserId,
                ];

                if ($existing) {
                    $existing->update($eventData);
                    $updated++;
                } else {
                    Event::create(array_merge(['bevy_id' => $bevyId], $eventData));
                    $created++;
                }
            }

            return [
                'success' => true,
                'message' => "Successfully synced " . count($events) . " events from Bevy ({$created} new, {$updated} updated with descriptions & banners).",
                'count' => count($events),
                'created' => $created,
                'updated' => $updated,
            ];

        } catch (\Exception $e) {
            Log::error('BevyEventService: Exception during sync: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error syncing events: ' . $e->getMessage(),
                'count' => 0,
            ];
        }
    }
}
