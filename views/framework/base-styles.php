
<style>
    <?php
    use App\Framework\Model\Style\Style;

    echo Style::create('*', [
        'box-sizing' => 'border-box',
        'font-family' => 'sans-serif',
    ]);
    echo Style::create('html,body', [
        'margin' => '0',
        'width' => '100%',
        'height' => '100%',
        'display' => 'flex',
        'flex-direction' => 'column',
        'align-items' => 'stretch',
        'background-color' => 'var(--color-secondary)',
    ]);

    foreach (range(1, 6) as $h) {
        echo Style::create("h{$h}", [
            'margin' => '0',
        ]);
    }

    echo Style::create('.header', [
        'padding' => '1rem',
        'display' => 'flex',
        'flex-direction' => 'row',
        'justify-content' => 'stretch',
        'background' => 'linear-gradient(var(--color-primary) 0%, var(--color-white) 250%)',
    ]);

    echo Style::create('.body', [
        'flex' => '1',
        'width' => '95%',
        'max-width' => '1600px',
        'margin' => '0 auto',
        'padding' => '0.5rem',
        'display' => 'flex',
        'flex-direction' => 'column',
        'background-color' => 'var(--color-white)',
        'overflow-x' => 'auto',
    ]);

    echo Style::create('table, th, td', [
        'border-collapse' => 'collapse',
        'border' => '1px solid black',
    ]);
    echo Style::create('td', [
        'padding' => '0.5rem',
    ]);

    echo Style::create('pre', [
        'margin' => '0',
        'padding' => '0',
        'font-family' => 'monospace',
    ]);

    echo Style::create('#debug-footer__value', [
        'display' => 'none',
        'padding' => '1rem',
        'font-family' => 'monospace',
    ]);

    echo Style::create(
        '#debug-footer__checkbox:checked ~ #debug-footer__value',
        [
            'display' => 'block',

        ]
    );

    ?>

</style>
