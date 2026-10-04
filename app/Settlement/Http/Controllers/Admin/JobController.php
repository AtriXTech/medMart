<?php

declare(strict_types=1);

namespace App\Settlement\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

final class JobController extends Controller
{
    private const JOBS = [
        'record-payments' => 'settlement:record-payments',
        'promote-eligible' => 'settlement:promote-eligible',
        'reconcile-transfers' => 'settlement:reconcile-transfers',
        'reconcile-ledger' => 'settlement:reconcile-ledger',
    ];

    public function __invoke(Request $request, string $job): JsonResponse
    {
        $command = self::JOBS[$job] ?? null;

        if ($command === null) {
            return response()->json(['message' => 'Unknown job.'], 404);
        }

        $arguments = [];

        if ($job === 'record-payments') {
            $data = $request->validate(['since' => ['sometimes', 'nullable', 'date']]);
            $arguments['--sync'] = true;

            if (! empty($data['since'])) {
                $arguments['--since'] = (string) $data['since'];
            }
        }

        $exitCode = Artisan::call($command, $arguments);

        return response()->json([
            'message' => 'Job finished.',
            'data' => [
                'job' => $job,
                'exit_code' => $exitCode,
                'output' => trim(Artisan::output()),
            ],
        ]);
    }
}
