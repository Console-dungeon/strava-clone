<?php

namespace App\Services;

class GpxParser
{
    public function parse(string $filePath): array
    {
        $xml = simplexml_load_file($filePath);

        if ($xml === false) {
            throw new \RuntimeException('Nieprawidłowy plik GPX.');
        }

        $points = [];
        $times = [];

        foreach ($xml->trk as $track) {
            foreach ($track->trkseg as $segment) {
                foreach ($segment->trkpt as $point) {
                    $lat = (float) $point['lat'];
                    $lng = (float) $point['lon'];
                    $ele = isset($point->ele) ? (float) $point->ele : null;
                    $time = isset($point->time) ? (string) $point->time : null;

                    $entry = ['lat' => $lat, 'lng' => $lng];
                    if ($ele !== null) {
                        $entry['ele'] = round($ele, 1);
                    }

                    $points[] = $entry;

                    if ($time !== null) {
                        $times[] = $time;
                    }
                }
            }
        }

        if (empty($points)) {
            throw new \RuntimeException('Brak punktów trasy w pliku GPX.');
        }

        return [
            'route_points' => $this->simplify($points, 500),
            'elevation_gain' => $this->elevationGain($points),
            'distance' => $this->distance($points),
            'duration' => $this->duration($times),
            'date' => $this->date($times, $xml),
            'type' => $this->type($xml),
        ];
    }

    private function distance(array $points): float
    {
        $total = 0.0;
        for ($i = 1; $i < count($points); $i++) {
            $total += $this->haversine(
                $points[$i - 1]['lat'], $points[$i - 1]['lng'],
                $points[$i]['lat'], $points[$i]['lng']
            );
        }

        return round($total, 2);
    }

    private function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $R = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        return $R * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

    private function elevationGain(array $points): float
    {
        $gain = 0.0;
        for ($i = 1; $i < count($points); $i++) {
            if (isset($points[$i]['ele'], $points[$i - 1]['ele'])) {
                $diff = $points[$i]['ele'] - $points[$i - 1]['ele'];
                if ($diff > 0) {
                    $gain += $diff;
                }
            }
        }

        return round($gain, 1);
    }

    private function duration(array $times): int
    {
        if (count($times) < 2) {
            return 0;
        }
        $start = new \DateTime($times[0]);
        $end = new \DateTime($times[count($times) - 1]);
        $diff = $end->diff($start);

        return (int) round($diff->h * 60 + $diff->i + $diff->s / 60);
    }

    private function date(array $times, \SimpleXMLElement $xml): string
    {
        if (! empty($times)) {
            return (new \DateTime($times[0]))->format('Y-m-d');
        }
        if (isset($xml->metadata->time)) {
            return (new \DateTime((string) $xml->metadata->time))->format('Y-m-d');
        }

        return now()->format('Y-m-d');
    }

    private function type(\SimpleXMLElement $xml): ?string
    {
        $raw = null;

        foreach ($xml->trk as $track) {
            if (isset($track->type)) {
                $raw = strtolower(trim((string) $track->type));
                break;
            }
        }

        if ($raw === null) {
            return null;
        }

        // Garmin numeric codes
        $garminMap = [
            '1' => 'cycling',   // cycling
            '2' => 'cycling',   // cycling (road)
            '5' => 'swimming',  // open water swimming
            '9' => 'running',   // running
            '10' => 'running',  // trail running
            '11' => 'running',  // treadmill running
            '13' => 'cycling',  // mountain biking
            '17' => 'cycling',  // e-biking
            '26' => 'swimming', // pool swimming
        ];

        if (isset($garminMap[$raw])) {
            return $garminMap[$raw];
        }

        // String matching
        if (str_contains($raw, 'run')) {
            return 'running';
        }
        if (str_contains($raw, 'cycl') || str_contains($raw, 'bik') || str_contains($raw, 'ride')) {
            return 'cycling';
        }
        if (str_contains($raw, 'swim')) {
            return 'swimming';
        }

        return null;
    }

    private function simplify(array $points, int $max): array
    {
        $count = count($points);
        if ($count <= $max) {
            return $points;
        }
        $step = (int) ceil($count / $max);
        $out = [];
        for ($i = 0; $i < $count; $i += $step) {
            $out[] = $points[$i];
        }
        if (end($out) !== $points[$count - 1]) {
            $out[] = $points[$count - 1];
        }

        return $out;
    }
}
