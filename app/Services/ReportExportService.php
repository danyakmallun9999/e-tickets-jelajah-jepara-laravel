<?php

namespace App\Services;

use App\Models\Boundary;
use App\Models\Category;
use App\Models\Infrastructure;
use App\Models\LandUse;
use App\Models\Place;
use Illuminate\Support\Facades\Storage;

class ReportExportService
{
    /**
     * Export data to CSV format
     */
    public function exportToCsv(string $type, array $filters = []): string
    {
        $filename = 'report_'.$type.'_'.date('Y-m-d_His').'.csv';
        $path = 'reports/'.$filename;

        $data = $this->getDataForType($type, $filters);
        $headers = $this->getHeadersForType($type);

        $csvContent = $this->arrayToCsv($headers, $data);

        Storage::disk('public')->put($path, $csvContent);

        return $path;
    }

    /**
     * Generate HTML report for PDF
     */
    /**
     * Generate HTML report for PDF
     */
    public function generateHtmlReport(string $type = 'all', array $filters = []): string
    {
        $data = $this->getDataForType($type, $filters);
        $headers = $this->getHeadersForType($type);
        $stats = $this->getStatistics(); // Keep stats for summary if needed, or remove. Let's keep for header.

        // Enhance data with keys if needed, but array is fine for simple table.
        // Actually, let's pass clear context to view.

        $viewData = [
            'type' => $type,
            'filters' => $filters,
            'headers' => $headers,
            'data' => $data,
            'date' => date('d F Y'),
            'stats' => $stats,
        ];

        // Pass filter description for display
        $filterDesc = [];
        if (! empty($filters['start_date'])) {
            $filterDesc[] = 'Dari: '.$filters['start_date'];
        }
        if (! empty($filters['end_date'])) {
            $filterDesc[] = 'Sampai: '.$filters['end_date'];
        }
        if (! empty($filters['category_id'])) {
            $cat = Category::find($filters['category_id']);
            if ($cat) {
                $filterDesc[] = 'Kategori: '.$cat->name;
            }
        }
        $viewData['filterDesc'] = implode(', ', $filterDesc);

        $html = view('admin.reports.html', $viewData)->render();

        return $html;
    }

    /**
     * Get data based on type
     */
    protected function getDataForType(string $type, array $filters = []): array
    {
        return match ($type) {
            'places' => $this->getPlacesData($filters),
            'boundaries' => $this->getBoundariesData($filters),
            'infrastructures' => $this->getInfrastructuresData($filters),
            'land_uses' => $this->getLandUsesData($filters),
            'all' => $this->getAllData($filters),
            default => [],
        };
    }

    /**
     * Get headers based on type
     */
    protected function getHeadersForType(string $type): array
    {
        return match ($type) {
            'places' => ['ID', 'Nama', 'Kategori', 'Latitude', 'Longitude', 'Deskripsi'],
            'boundaries' => ['ID', 'Nama', 'Tipe', 'Luas (ha)', 'Deskripsi'],
            'infrastructures' => ['ID', 'Nama', 'Tipe', 'Panjang (m)', 'Lebar (m)', 'Kondisi', 'Deskripsi'],
            'land_uses' => ['ID', 'Nama', 'Tipe', 'Luas (ha)', 'Pemilik', 'Deskripsi'],
            'all' => ['Tipe', 'ID', 'Nama', 'Detail'],
            default => [],
        };
    }

    /**
     * Get places data
     */
    protected function getPlacesData(array $filters = []): array
    {
        $query = Place::with('category');

        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (! empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (! empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        return $query->get()->map(function ($place) {
            return [
                $place->id,
                $place->name,
                $place->category?->name ?? '-',
                $place->latitude,
                $place->longitude,
                $place->description ?? '-',
            ];
        })->toArray();
    }

    /**
     * Get boundaries data
     */
    protected function getBoundariesData(array $filters = []): array
    {
        $query = Boundary::query();

        if (! empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (! empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        return $query->get()->map(function ($boundary) {
            return [
                $boundary->id,
                $boundary->name,
                $boundary->type,
                $boundary->area_hectares ?? '-',
                $boundary->description ?? '-',
            ];
        })->toArray();
    }

    /**
     * Get infrastructures data
     */
    protected function getInfrastructuresData(array $filters = []): array
    {
        $query = Infrastructure::query();

        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (! empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (! empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        return $query->get()->map(function ($infrastructure) {
            return [
                $infrastructure->id,
                $infrastructure->name,
                $infrastructure->type,
                $infrastructure->length_meters ?? '-',
                $infrastructure->width_meters ?? '-',
                $infrastructure->condition ?? '-',
                $infrastructure->description ?? '-',
            ];
        })->toArray();
    }

    /**
     * Get land uses data
     */
    protected function getLandUsesData(array $filters = []): array
    {
        $query = LandUse::query();

        if (! empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (! empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        return $query->get()->map(function ($landUse) {
            return [
                $landUse->id,
                $landUse->name,
                $landUse->type,
                $landUse->area_hectares ?? '-',
                $landUse->owner ?? '-',
                $landUse->description ?? '-',
            ];
        })->toArray();
    }

    /**
     * Get all data summary
     */
    protected function getAllData(array $filters = []): array
    {
        $data = [];

        $placesQuery = Place::with('category');
        if (! empty($filters['category_id'])) {
            $placesQuery->where('category_id', $filters['category_id']);
        }
        if (! empty($filters['start_date'])) {
            $placesQuery->whereDate('created_at', '>=', $filters['start_date']);
        }
        if (! empty($filters['end_date'])) {
            $placesQuery->whereDate('created_at', '<=', $filters['end_date']);
        }

        $placesQuery->get()->each(function ($place) use (&$data) {
            $data[] = ['Titik Lokasi', $place->id, $place->name, $place->category?->name ?? '-'];
        });

        $boundariesQuery = Boundary::query();
        if (! empty($filters['start_date'])) {
            $boundariesQuery->whereDate('created_at', '>=', $filters['start_date']);
        }
        if (! empty($filters['end_date'])) {
            $boundariesQuery->whereDate('created_at', '<=', $filters['end_date']);
        }

        $boundariesQuery->get()->each(function ($boundary) use (&$data) {
            $data[] = ['Batas Wilayah', $boundary->id, $boundary->name, $boundary->type];
        });

        $infraQuery = Infrastructure::query();
        if (! empty($filters['category_id'])) {
            $infraQuery->where('category_id', $filters['category_id']);
        }
        if (! empty($filters['start_date'])) {
            $infraQuery->whereDate('created_at', '>=', $filters['start_date']);
        }
        if (! empty($filters['end_date'])) {
            $infraQuery->whereDate('created_at', '<=', $filters['end_date']);
        }

        $infraQuery->get()->each(function ($infrastructure) use (&$data) {
            $data[] = ['Infrastruktur', $infrastructure->id, $infrastructure->name, $infrastructure->type];
        });

        $landUsesQuery = LandUse::query();
        if (! empty($filters['start_date'])) {
            $landUsesQuery->whereDate('created_at', '>=', $filters['start_date']);
        }
        if (! empty($filters['end_date'])) {
            $landUsesQuery->whereDate('created_at', '<=', $filters['end_date']);
        }

        $landUsesQuery->get()->each(function ($landUse) use (&$data) {
            $data[] = ['Penggunaan Lahan', $landUse->id, $landUse->name, $landUse->type];
        });

        return $data;
    }

    /**
     * Convert array to CSV string
     */
    protected function arrayToCsv(array $headers, array $data): string
    {
        $output = fopen('php://temp', 'r+');

        // Add BOM for Excel UTF-8 support
        fwrite($output, "\xEF\xBB\xBF");

        // Write headers
        fputcsv($output, $headers);

        // Write data
        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);

        return $csv;
    }

    /**
     * Get statistics for report
     */
    public function getStatistics(): array
    {
        return [
            'places_count' => Place::count(),
            'boundaries_count' => Boundary::count(),
            'infrastructures_count' => Infrastructure::count(),
            'land_uses_count' => LandUse::count(),
            'categories' => Category::withCount('places')->get(),
            'infrastructure_types' => Infrastructure::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->get(),
            'land_use_types' => LandUse::selectRaw('type, COUNT(*) as count, SUM(area_hectares) as total_area')
                ->groupBy('type')
                ->get(),
            'total_infrastructure_length' => Infrastructure::sum('length_meters'),
            'total_land_area' => LandUse::sum('area_hectares'),
        ];
    }
}
