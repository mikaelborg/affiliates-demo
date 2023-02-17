<?php

namespace Tests\Unit;

use App\Models\Affiliate;
use App\Services\AffiliateService;
use Tests\TestCase;

class AffiliateTest extends TestCase
{
    protected $service;

    public function setUp(): void
    {
        $this->service = new AffiliateService();
    }

    public function test_check_formula_same_point(): void
    {
        $distance = $this->service->calculateDistanceFromOffice(53.3340285, -6.2535495);
        $this->assertEquals(0, $distance);
    }

    public function test_check_formula_different_point(): void
    {
        // checked formula via online checker https://www.nhc.noaa.gov/gccalc.shtml
        $distance = $this->service->calculateDistanceFromOffice(52.986375, -6.043701);
        $this->assertEquals(41, round($distance));
    }

    public function test_affiliates_sorted(): void
    {
        $affiliates = collect([
            new Affiliate([
                'affiliate_id' => 20,
                'name' => 'John',
                'longitude' => 50.0000000,
                'latitude' => 50.0000000
            ]),
            new Affiliate([
                'affiliate_id' => 1,
                'name' => 'Mark',
                'longitude' => 60.0000000,
                'latitude' => 60.0000000
            ])
        ]);

        $this->assertEquals(20, $affiliates->first()->affiliate_id);
        $this->assertEquals("John", $affiliates->first()->name);

        $sortedAffiliates = $this->service->sort($affiliates);

        $this->assertEquals(1, $sortedAffiliates->first()->affiliate_id);
        $this->assertEquals("Mark", $sortedAffiliates->first()->name);
    }
}
