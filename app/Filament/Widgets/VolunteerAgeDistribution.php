<?php
namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class VolunteerAgeDistribution extends ChartWidget
{
    protected static ?string $heading = 'Volunteer Age Distribution';

    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '300px';
     // Add cluster filter parameter
     public ?int $clusterFilter = null;
    
     public function mount(?int $clusterFilter = null): void
     {
         // If no cluster filter is passed, check if current user is External Partner
         if (!$clusterFilter) {
             $user = Auth::user();
             if ($user && $user->hasRole('External Partner') && $user->cluster_id) {
                 $this->clusterFilter = $user->cluster_id;
             }
         } else {
             $this->clusterFilter = $clusterFilter;
         }
     }

     protected function getData(): array
     {
         $query = User::query()
             ->whereNotNull('age_range')
             ->whereHas('eventAttendees', function($query) {
                 $query->where('encoding_type', '!=', 3);
             });
             
         // Apply cluster filter if it exists
         if ($this->clusterFilter) {
             $query->where('cluster_id', $this->clusterFilter);
         }
         
         $ageGroups = $query
             ->select('age_range', DB::raw('COUNT(*) as count'))
             ->groupBy('age_range')
             ->orderBy('age_range')
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
                         '#6366F1', // Indigo-500
                     ],
                 ],
             ],
             'labels' => $ageGroups->pluck('age_range')->toArray(),
         ];
     }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'display' => false,
                ],
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'right',
                ],
            ],
        ];
    }
}
