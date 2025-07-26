<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DBproftest;
use Illuminate\Support\Str;

class DBprofTestController extends Controller
{
    /**
     * Display a listing of the resource with timing.
     */
    public function index(Request $request)
    {
        $start = microtime(true);

        // You can simulate heavy DB load with huge pagination
        $records = DBprofTest::latest()->paginate(100);
        $duration = microtime(true) - $start;

        return response()->json([
            'records' => $records,
            'load_time' => $duration,
        ]);
    }

    /**
     * Generate and insert test records.
     */
    public function generate(Request $request)
    {
        $count = $request->get('count', 1000);
        $start = microtime(true);

        $data = [];

        for ($i = 0; $i < $count; $i++) {
            $data[] = [
                'title' => 'Record #' . $i,
                'slug' => Str::slug('Record ' . $i) . '-' . Str::random(6),
                'description' => 'This is a description for record #' . $i,
                'type' => ['simple', 'advanced', 'premium'][rand(0, 2)],
                'status' => (bool)rand(0, 1),
                'user_id' => null, // Or you can randomize a real user ID
                'starts_at' => now()->subDays(rand(0, 30)),
                'ends_at' => now()->addDays(rand(0, 30)),
                'metadata' => json_encode([
                    'ip' => request()->ip(),
                    'browser' => $request->header('User-Agent'),
                    'tag' => Str::random(10),
                ]),
                'content' => Str::repeat("This is long content for record $i. ", rand(10, 30)),
                'view_count' => rand(0, 1000),
                'rating' => number_format(rand(0, 500) / 100, 2),
                'reference_id' => (string) Str::uuid(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DBproftest::insert($data);

        $duration = microtime(true) - $start;

        return response()->json([
            'message' => "Inserted $count complex records",
            'insert_time' => $duration,
        ]);
    }
}
