<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatusApiTest extends TestCase
{
    public function test_status_endpoint_returns_ok()
    {
        $response = $this->get('/api/status');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'api',
            'db',
            'timestamp'
        ]);
    }
}
