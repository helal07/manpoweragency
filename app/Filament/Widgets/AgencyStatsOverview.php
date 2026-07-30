<?php

namespace App\Filament\Widgets;

use App\Models\Applicant;
use App\Models\Client;
use App\Models\JobCircular;
use App\Models\Notice;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AgencyStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $openCircularsCount = JobCircular::where('status', 'open')->count();
        $totalVacancies = JobCircular::where('status', 'open')->sum('vacancy');
        $applicantsCount = Applicant::count();
        $noticesCount = Notice::count();
        $clientsCount = Client::count();

        return [
            Stat::make('Active Job Openings', $openCircularsCount)
                ->description($totalVacancies . ' Total Vacancies Available')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('success'),
            Stat::make('Registered Applicants', $applicantsCount)
                ->description('Job seekers in database')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
            Stat::make('Published Notices', $noticesCount)
                ->description('Interview & flight schedules')
                ->descriptionIcon('heroicon-m-megaphone')
                ->color('warning'),
            Stat::make('Client Employers', $clientsCount)
                ->description('International partner companies')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->color('primary'),
        ];
    }
}
