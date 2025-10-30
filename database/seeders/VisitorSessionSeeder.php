<?php

namespace Database\Seeders;

use App\Models\VisitorSession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VisitorSessionSeeder extends Seeder
{
    public function run(): void
    {
        $sessions = [
            [
                'ip_address' => '192.168.1.10',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'device_type' => 'desktop',
                'session_start' => now()->subHours(2),
                'session_end' => now()->subHour(),
            ],
            [
                'ip_address' => '172.16.0.5',
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_2 like Mac OS X)',
                'device_type' => 'mobile',
                'session_start' => now()->subDays(1),
                'session_end' => now()->subDays(1)->addHours(3),
            ],
            [
                'ip_address' => '10.0.0.8',
                'user_agent' => 'Mozilla/5.0 (Linux; Android 11; SM-A507FN)',
                'device_type' => 'mobile',
                'session_start' => now()->subMinutes(90),
                'session_end' => now(),
            ],
        ];

        foreach ($sessions as $session) {
            VisitorSession::create(array_merge($session, [
                'session_token' => Str::random(40),
            ]));
        }
    }
}
