<?php

namespace App\Filament\Widgets;

use App\Models\JobCircular;
use Filament\Widgets\ChartWidget;

class RecruitmentOverviewChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Vacancies by Destination Country';

    protected function getData(): array
    {
        $circularsByCountry = JobCircular::query()
            ->selectRaw('country, SUM(vacancy) as total_vacancies')
            ->groupBy('country')
            ->pluck('total_vacancies', 'country')
            ->toArray();

        $labels = array_keys($circularsByCountry);
        $data = array_values($circularsByCountry);

        if (empty($labels)) {
            $labels = ['Saudi Arabia', 'UAE', 'Qatar', 'Malaysia', 'Kuwait', 'Romania'];
            $data = [450, 300, 200, 150, 100, 80];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Vacancies',
                    'data' => $data,
                    'backgroundColor' => [
                        '#3b82f6',
                        '#10b981',
                        '#f59e0b',
                        '#8b5cf6',
                        '#ec4899',
                        '#6366f1',
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
