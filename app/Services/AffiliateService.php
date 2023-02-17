<?php

namespace App\Services;

use App\Models\Affiliate;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class AffiliateService
{
    function readFileAndFilter(string $filename): Collection {
        $closeAffiliates = collect([]);

        File::lines(storage_path($filename))->each(function ($line) use ($closeAffiliates) {
            $affiliate = new Affiliate(json_decode($line, true));

            $distance = $this->calculateDistanceFromOffice($affiliate->latitude, $affiliate->longitude);
            
            if ($distance <= 100) {
                $closeAffiliates->add($affiliate);
            }
        });

        return $closeAffiliates;
    }

    function calculateDistanceFromOffice(float $latitudeTo, float $longitudeTo): float {
        $earthRadius = 6371;
        
        // office coords
        $latFrom = deg2rad(53.3340285);
        $lonFrom = deg2rad(-6.2535495);

        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);
    
        $lonDelta = $lonTo - $lonFrom;
    
        $angle = acos((sin($latFrom) * sin($latTo)) + (cos($latFrom) * cos($latTo) *  cos($lonDelta)));
    
        return $angle * $earthRadius;
    }

    function sort(Collection $affiliates): Collection
    {
        return $affiliates->sortBy('affiliate_id');
    }
}