<?php

declare(strict_types=1);

namespace App\Framework\Model\Style;

final readonly class BaseStyles
{
    /**
     * @var array<Style>
     */
    public array $styles;

    public function __construct()
    {
        $styles = [
            Style::create(':root', [
                '--color-primary' => '#ff8800',
                '--color-secondary' => '#5dbaff',
                '--color-white' => '#ffffff',
            ]),
            Style::create('*', [
                'box-sizing' => 'border-box',
                'font-family' => 'sans-serif',
            ]),
            Style::create('html,body', [
                'margin' => '0',
                'width' => '100%',
                'height' => '100%',
                'display' => 'flex',
                'flex-direction' => 'column',
                'align-items' => 'stretch',
                'background-color' => 'var(--color-secondary)',
            ]),

            Style::create('.body', [
                'flex' => '1',
                'width' => '95%',
                'max-width' => '1600px',
                'margin' => '0 auto',
                'padding' => '0.5rem',
                'display' => 'flex',
                'flex-direction' => 'column',
                'background-color' => 'var(--color-white)',
                'overflow-x' => 'auto',
            ]),

            Style::create(
                '.header',
                [
                    'padding' => '1rem',
                    'display' => 'flex',
                    'flex-direction' => 'row',
                    'justify-content' => 'stretch',
                    'align-items' => 'center',
                    'background' => 'linear-gradient(var(--color-primary) 0%, var(--color-white) 250%)',
                ],
                Style::create(
                    '.header__links',
                    [
                        'margin-left' => 'auto',
                    ],
                    Style::create(
                        '> a',
                        [],
                        Style::create(
                            '& + &',
                            [
                                'margin-left' => '0.5rem',
                            ],
                        ),
                        Style::create('&.light', [
                            'color' => 'white',
                        ])
                    )
                )
            ),

            Style::create('table, th, td', [
                'border-collapse' => 'collapse',
                'border' => '1px solid black',
            ]),
            Style::create('td', [
                'padding' => '0.5rem',
            ]),

            Style::create('pre', [
                'margin' => '0',
                'padding' => '0',
                'font-family' => 'monospace',
            ]),

            Style::create('#debug-footer__value', [
                'display' => 'none',
                'padding' => '1rem',
                'font-family' => 'monospace',
            ]),

            Style::create(
                '#debug-footer__checkbox:checked ~ #debug-footer__value',
                [
                    'display' => 'block',

                ]
            ),
        ];

        foreach (range(1, 6) as $h) {
            $styles[] =
            Style::create("h{$h}", [
                'margin' => '0',
            ]);
        }

        $this->styles = array_map(
            fn (Style $style) => $style->normaliseChildren(),
            $styles
        );
    }
}
