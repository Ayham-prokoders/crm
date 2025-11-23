<?php

namespace App\Http\Helper;

use App\Services\FormsSyncService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Modules\RegisterManagement\Models\RegisterRequest;

class RegisterationRequestHelper
{
    protected $syncService;

    public function __construct(FormsSyncService $syncService)
    {
        $this->syncService = $syncService;
    }
    public function getMails($lastId = 0,$siteKey= 'L1')
    {
        try {
            $data = [
                'table_name' => 'mails',
                'next_step' => $lastId,

            ];
            // $url = env('API_URL') . '/api/data-integration/view';

            // $response = Http::withHeaders([
            //     'name' => config('app.CLIENT_NAME'),
            //     'secret' => config('app.CLIENT_SECRET'),
            // ])->post($url, $data);
             $site = config("services.sites.$siteKey");
            $url = $site['url'] . '/api/data-integration/view';
            $secret = $site['shared_secret'];
            $timestamp = time();

            $signature = hash_hmac('sha256', $timestamp . json_encode($data), $secret);

            $response = Http::withHeaders([
                'X-Timestamp' => $timestamp,
                'X-Signature' => $signature,
            ])->post($url, $data);

            if ($response->failed()) {
                throw new \Exception('Failed to fetch Cities from remote API.');
            }

            $response->throw();
            return $response->json();
        } catch (\Exception $e) {
            logger()->error('Failed to fetch Cities: ' . $e->getMessage());
            return [];
        }
    }

    public function syncRegisterRequests()
    {
        $lastId = 0;
        $mailCount = 0;

        while ($lastId !== -1) {
            $response = $this->getMails($lastId);

            if (!isset($response['table_data'])) {
                logger()->error('Failed to fetch mails from remote API.');
                return 1;
            }

            $mails = $response['table_data'];

            foreach ($mails as $mail) {
                try {
                    $allData = json_decode($mail['all_data'], true);
                    if (!$allData) {
                        logger()->warning("Skipping mail ID {$mail['id']} due to invalid JSON.");
                        continue;
                    }

                    // $existingRequest = RegisterRequest::where('external_id', $mail['id'])->first();

                    // if (!$existingRequest) {
                    $participants = collect($allData['participantName'] ?? [])
                        ->map(function ($name, $index) use ($allData) {
                            return [
                                'name' => $name,
                                'email' => $allData['participantEmail'][$index] ?? null,
                                'phone' => $allData['participantPhone'][$index] ?? null,
                                'position' => $allData['participantPosition'][$index] ?? null,
                            ];
                        })->toArray();

                    $record = RegisterRequest::updateOrCreate(
                ['external_id' => $mail['id']],
                [
                        'status' => 'waiting',
                        'course_name' => $allData['course_name'] ?? null,
                        'course_price' => $allData['course_price'] ?? null,
                        'course_date' => $allData['course_date'] ?? null,
                        'course_city' => $allData['course_city'] ?? null,
                        'salutation' => $allData['salutation'] ?? null,
                        'nationality' => $allData['nationality'] ?? null,
                        'company' => $allData['company'] ?? null,
                        'email' => $allData['email'] ?? null,
                        'full_name' => $allData['full_name'] ?? null,
                        'city' => $allData['city'] ?? null,
                        'type' => $mail['type'],
                        'mobile' => $allData['mobile'] ?? null,
                        'payment_mode' => $allData['payment_mode'] ?? null,
                        'payment_method' => $allData['payment_method'] ?? null,
                        'bill_to' => $allData['bill_to'] ?? null,
                        'course_id'=>$allData['course_id'] ?? null,
                        'participants' => $participants,
                        'participantName' => json_encode($allData['participantName'] ?? []),
                        'participantEmail' => json_encode($allData['participantEmail'] ?? []),
                        'participantPhone' => json_encode($allData['participantPhone'] ?? []),
                        'participantPosition' => json_encode($allData['participantPosition'] ?? []),
                        // 'created_at' => now(),
                    ]);

                    // $mailCount++;
                    if ($record->wasRecentlyCreated) {
                        $mailCount++;
                    }
                // }
                } catch (\Illuminate\Database\QueryException $dbEx) {
                    throw new \Exception("Database error for mail ID {$mail['id']}: " . $dbEx->getMessage());
                } catch (\Exception $ex) {
                    throw new \Exception("Unexpected error for mail ID {$mail['id']}: " . $ex->getMessage());
                }
            }

            $lastId = $response['next_step'];
        }

        return $mailCount;
    }

    public function syncFormsRequests($type = null, $filters = [], $fromDate = null, $search = null, $limit = 10, $page = 1)
    {
        $queryFilters = [
            'type' => $type,
            'fromDate' => $fromDate,
            'month'    => $filters['month'] ?? null,
            'search' => $search,
            'custom_filters' => $filters
        ];

        $query = $this->syncService->getForms($queryFilters);

        $statsQuery = clone $query;
        $typeStats = $statsQuery->selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        $total = $query->count();

        $forms = [];
        if ($limit !== null) {
            $forms = $query->orderBy('created_at', 'desc')
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get()
                ->map(function($form) {
                    return [
                        'id' => $form->remote_id,
                        'type' => $form->type,
                        'original_type' => $form->original_type,
                        'created_at' => $form->form_created_at,
                        'data' => $form->data
                    ];
                })->toArray();
        }

        return [
            'total' => $total,
            'current_page' => $page,
            'per_page' => $limit,
            'last_page' => $limit > 0 ? ceil($total / $limit) : 1,
            'forms' => $forms,
            'stats' => $typeStats,
        ];
    }

    public function getAllFormsForExport($type = null, $filters = [], $fromDate = null, $search = null)
    {
        $queryFilters = [
            'type' => $type,
            'fromDate' => $fromDate,
            'month'    => $filters['month'] ?? null,
            'search' => $search,
            'custom_filters' => $filters
        ];

        $query = $this->syncService->getForms($queryFilters);

        $forms = $query->orderBy('created_at', 'desc')
            ->get()
            ->map(function($form) {
                return [
                    'id' => $form->remote_id,
                    'type' => $form->type,
                    'original_type' => $form->original_type,
                    'created_at' => $form->form_created_at,
                    'data' => $form->data
                ];
            })->toArray();

        // $typeStats = $query->selectRaw('type, count(*) as count')
        //     ->pluck('count', 'type')
        //     ->toArray();

        return [
            'total' => count($forms),
            'forms' => $forms,
            // 'stats' => $typeStats
        ];
    }


    public function triggerAutoSync($siteKey , $type = 'incremental')
    {
        if ($type === 'full') {
            return $this->syncService->fullSync($siteKey);
        } else {
            return $this->syncService->incrementalSync($siteKey);
        }
    }

    // public function getForms($lastId = 0,$siteKey='L1')
    // {
    //     try {
    //         $data = [
    //             'table_name' => 'forms',
    //             'next_step' => $lastId,
    //         ];

    //         // $url = env('API_URL') . '/api/data-integration/view';

    //         // $response = Http::withHeaders([
    //         //     'name' => config('app.CLIENT_NAME'),
    //         //     'secret' => config('app.CLIENT_SECRET'),
    //         // ])->post($url, $data);
    //          $site = config("services.sites.$siteKey");
    //         $url = $site['url'] . '/api/data-integration/view';
    //         $secret = $site['shared_secret'];
    //         $timestamp = time();

    //         $signature = hash_hmac('sha256', $timestamp . json_encode($data), $secret);

    //         $response = Http::withHeaders([
    //             'X-Timestamp' => $timestamp,
    //             'X-Signature' => $signature,
    //         ])->post($url, $data);

    //         if ($response->failed()) {
    //             throw new \Exception('Failed to fetch forms from remote API.');
    //         }

    //         $response->throw();
    //         return $response->json();
    //     } catch (\Exception $e) {
    //         logger()->error('Failed to fetch forms: ' . $e->getMessage());
    //         return [];
    //     }
    // }

    // public function syncFormsRequests($type = null,$filters = [],$fromDate = null,$search = null,$limit = 10,$page = 1,$month = null)
    // {
    //     $lastId = 0;
    //     $matchedForms = [];

    //     $allowedTypes = [
    //         'brochure-form',
    //         'event-brochure-form',
    //         'customize_course_request-Form',
    //         'event-register-form',
    //         'contact-form',
    //         'newsletter-form',
    //         'become_an_instructor-Form',
    //         'register-form',
    //         'multiple-register-form',
    //     ];

    //     while ($lastId !== -1) {
    //         $response = $this->getForms($lastId);

    //         if (!isset($response['table_data'])) {
    //             logger()->error('Failed to fetch forms from remote API.');
    //             break;
    //         }

    //         foreach ($response['table_data'] as $form) {
    //             $originalType = $form['type'];

    //             // Skip disallowed types
    //             if (!in_array($originalType, $allowedTypes)) {
    //                 continue;
    //             }

    //             // Normalize type for unified handling
    //             $normalizedType = $originalType === 'multiple-register-form' ? 'register-form' : $originalType;

    //             // Apply type filter if needed
    //             if ($type) {
    //                 if ($type === 'register-form' && !in_array($originalType, ['register-form', 'multiple-register-form'])) {
    //                     continue;
    //                 } elseif ($type !== 'register-form' && $normalizedType !== $type) {
    //                     continue;
    //                 }
    //             }

    //             // Date filtering
    //             if (isset($form['date'])) {
    //                 $formDate = \Carbon\Carbon::parse($form['date']);

    //                 if ($fromDate && $formDate->lt(\Carbon\Carbon::parse($fromDate))) {
    //                     continue;
    //                 }

    //                 if ($month && $formDate->format('Y-m') !== $month) {
    //                     continue;
    //                 }
    //             }

    //             // Decode JSON safely
    //             $allData = json_decode($form['all_data'], true);
    //             if (!$allData) {
    //                 logger()->warning("Skipping form ID {$form['id']} due to invalid JSON.");
    //                 continue;
    //             }

    //             // Apply search
    //             if ($search) {
    //                 $searchLower = strtolower($search);
    //                 $nameMatch = isset($allData['full_name']) && str_contains(strtolower($allData['full_name']), $searchLower);
    //                 $emailMatch = isset($allData['email']) && str_contains(strtolower($allData['email']), $searchLower);

    //                 if (!$nameMatch && !$emailMatch) {
    //                     continue;
    //                 }
    //             }

    //             // Apply filters
    //             // foreach ($filters as $key => $value) {
    //             //     if (empty($value)) continue;

    //             //     if (!isset($allData[$key]) || stripos($allData[$key], $value) === false) {
    //             //         $matched = false;
    //             //         break;
    //             //     }
    //             // }

    //             $matched = true;
    //             foreach ($filters as $key => $value) {
    //                 if ($value === '' || $value === null) continue;

    //                 if (!array_key_exists($key, $allData)) {
    //                     $matched = false;
    //                     break;
    //                 }

    //                 $dataValue = $allData[$key];

    //                 if (is_bool($dataValue)) {
    //                     $dataValue = $dataValue ? 1 : 0;
    //                 }
    //                 if (is_bool($value)) {
    //                     $value = $value ? 1 : 0;
    //                 }

    //                 if (is_numeric($dataValue) && is_numeric($value)) {
    //                     if ((int)$dataValue !== (int)$value) {
    //                         $matched = false;
    //                         break;
    //                     }
    //                 } else {
    //                     if (strtolower((string)$dataValue) !== strtolower((string)$value)) {
    //                         $matched = false;
    //                         break;
    //                     }
    //                 }
    //             }


    //             if (!$matched) continue;

    //             // Add to results
    //             $matchedForms[] = [
    //                 'id' => $form['id'],
    //                 'type' => $normalizedType,
    //                 'original_type' => $originalType,
    //                 'created_at' => $form['date'] ?? null,
    //                 'data' => $allData,
    //             ];
    //             }

    //             $lastId = $response['next_step'];
    //     }

    //         // Paginate
    //         $paginatedForms = $limit ? array_slice($matchedForms, ($page - 1) * $limit, $limit) : $matchedForms;

    //         $typeStats = [];
    //         foreach ($matchedForms as $form) {
    //             $type = $form['type'];
    //             if (!isset($typeStats[$type])) {
    //                 $typeStats[$type] = 0;
    //             }
    //             $typeStats[$type]++;
    //         }
    //         return [
    //             'total' => count($matchedForms),
    //             'current_page' => $page,
    //             'per_page' => $limit,
    //             'last_page' => ($limit > 0) ? ceil(count($matchedForms) / $limit) : 1,
    //             'forms' => $paginatedForms,
    //             'stats' => $typeStats,
    //         ];
    // }


}
