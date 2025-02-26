<?php
namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class VolunteerAgeDistribution extends ChartWidget
{
    protected static ?string $heading = 'Volunteer Age Distribution';

    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $ageGroups = User::query()
            ->whereNotNull('birthday')
            ->whereHas('eventAttendees', function($query) {
                $query->where('encoding_type', '!=', 3);
            })
            ->select([
                DB::raw('
                    CASE
                        WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) < 20 THEN "Under 20"
                        WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 20 AND 29 THEN "20-29"
                        WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 30 AND 39 THEN "30-39"
                        WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 40 AND 49 THEN "40-49"
                        WHEN TIMESTAMPDIFF(YEAR, birthday, CURDATE()) BETWEEN 50 AND 59 THEN "50-59"
                        ELSE "60 and above"
                    END as age_group
                '),
                DB::raw('COUNT(*) as count')
            ])
            ->groupBy('age_group')
            ->orderBy('age_group')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Number of Volunteers',
                    'data' => $ageGroups->pluck('count')->toArray(),
                    'backgroundColor' => [
                        '#F59E0B', // Amber-500
                        '#10B981', // Emerald-500
                        '#3B82F6', // Blue-500
                        '#8B5CF6', // Violet-500
                        '#EC4899', // Pink-500
                        '#EF4444', // Red-500
                    ],
                ],
            ],
            'labels' => $ageGroups->pluck('age_group')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
