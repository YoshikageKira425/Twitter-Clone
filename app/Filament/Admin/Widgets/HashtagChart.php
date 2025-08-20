<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Hashtag;
use Filament\Widgets\ChartWidget;

class HashtagChart extends ChartWidget
{
    protected ?string $heading = 'Hashtag Chart';

    protected bool $isCollapsible = true;

    protected function getData(): array
    {
        $hashtags = Hashtag::withCount('tweets')
            ->orderBy('tweets_count', 'desc')
            ->limit(6)
            ->get();

        return [
            'datasets' => [
                [
                    'data' => $hashtags->pluck('tweets_count')->toArray(),
                    'backgroundColor' => [
                        '#FF6384',
                        '#36A2EB',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40',
                    ],
                ],
            ],
            'labels' => $hashtags->pluck('name')->toArray()
        ];
    }

    protected function getType(): string
    {
        return 'polarArea';
    }
}
