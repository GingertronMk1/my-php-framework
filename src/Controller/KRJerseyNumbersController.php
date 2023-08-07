<?php

declare(strict_types=1);

namespace App\Controller;

use App\Framework\Controller\AbstractController;
use App\Framework\Model\App;
use App\Framework\Model\Style\Style;
use Exception;

final class KRJerseyNumbersController extends AbstractController
{
    public function handleRequest(): App
    {
        $playersAndNumbers = $this->app->getDataFileContents(
            'jerseys.json',
            true
        );

        if (! is_array($playersAndNumbers)) {
            throw new Exception('Players and numbers not an array');
        } else {
            array_walk(
                $playersAndNumbers,
                fn (mixed $players, int $year) =>
                    is_array($players)
                    ? $players
                    : throw new Exception(
                        "Players for {$year} are not an array"
                    )
            );
        }

        $highestJersey = max(
            array_map(fn (array $arr) => count($arr), $playersAndNumbers)
        );

        $groupedPlayers = [];

        foreach ($playersAndNumbers as $year => $players) {
            if (! is_array($players)) {
                throw new Exception("Players for {$year} are not an array");
            }
            for ($i = 1; $i <= $highestJersey; $i++) {
                $player = $players[$i] ?? '';
                if (! isset($groupedPlayers[$i])) {
                    $groupedPlayers[$i] = [];
                }
                $groupedPlayers[$i][$year] = $player;
            }
        }

        $topRow = array_merge(['No.'], array_keys($groupedPlayers[1]));

        $str = $this->wrapInTags(
            $this->wrapInTags(
                implode(
                    '',
                    array_map(
                        fn (string $name) => $this->wrapInTags($name, 'td'),
                        $topRow
                    )
                ),
                'tr'
            ),
            'thead'
        );

        foreach ($groupedPlayers as $jerseyNumber => $players) {
            $str .= $this->wrapInTags(
                $this->wrapInTags(
                    (string) $jerseyNumber,
                    'th',
                    ['class="headcol"']
                )
                . implode(
                    '',
                    array_map(
                        fn (string $name) => $this->wrapInTags($name, 'td'),
                        $players
                    )
                ),
                'tr'
            );
        }

        $str = $this->wrapInTags($str, 'table');
        $this->app->view = $str;
        $this->app->setStyle(
            Style::create('td', [
                'white-space' => 'nowrap',
            ]),
            Style::create('thead', [
                'font-weight' => '700',
            ]),
            Style::create('tr:nth-of-type(14) > *', [
                'border-top-width' => '0.2rem;',
            ]),
            Style::create('tr:nth-of-type(18) > *', [
                'border-top-width' => '0.2rem;',
            ]),
            Style::create('tr', [
                'background-color' => 'white',
            ]),
            Style::create('tr:nth-of-type(2n)', [
                'background-color' => '#ffbbbb',
            ]),
        );
        return $this->app;
    }
}
