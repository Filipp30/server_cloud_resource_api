<?php

namespace Tests\Feature;

use App\ExternalServices\Contracts\GeoLocationContract;
use App\Http\Controllers\GeoIp2\GeoIp2Controller;
use App\Models\GeoIp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestCase;

class GeoIp2ControllerTest extends TestCase
{
    use RefreshDatabase;

    private $geoIp2Controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->geoIp2Controller = new GeoIp2Controller();
    }

    public function test_route_exists()
    {
        $response = $this->get('/api/ip/run/000');
        $response->assertStatus(200);
    }

    public function test_validation_ip_address()
    {
        $this->assertFalse($this->geoIp2Controller->handleIp(null));
        $this->assertFalse($this->geoIp2Controller->handleIp('abcd'));
        $this->assertFalse($this->geoIp2Controller->handleIp(12345678));
    }

    public function test_save_ip_address_if_not_exists()
    {
        $this->assertTrue($this->geoIp2Controller->handleIp('81.11.139.169'));
    }

    public function test_ip_address_not_found()
    {
        $this->assertFalse($this->geoIp2Controller->handleIp('11.11.111.111'));
    }

    public function test_update_visiting_count()
    {
        for ($i = 0; $i <= 1; $i++) {
            $this->geoIp2Controller->handleIp('81.11.139.169');
        }
        $visitingCount = GeoIp::query()
            ->where('visitor_ip_address', '=', '81.11.139.169')
            ->pluck('visiting_count')->toArray();
        $this->assertTrue(intval($visitingCount[0]) == 2);
    }

    public function test_save_geo_data_visitor()
    {
        $geoIp = GeoIp::factory()->create();
        $this->assertModelExists($geoIp);
    }

    public function test_geo_location_contract()
    {
        $geo_ip_data = app(GeoLocationContract::class)->get_location('81.11.139.169');
        $this->assertTrue($geo_ip_data['continent']['name'] == 'Europe');
    }
}
