<?php declare(strict_types=1);
/** @var Exception $exception */ ?>
<h1><?= $exception->getMessage(); ?></h1>

<table>
    <thead>
        <tr>
            <th>Number</th>
            <th>Class</th>
            <th>Function</th>
            <th>Line</th>
            <th>Type</th>
            <th>Args</th>
        </tr>
    </thead>
    <?php foreach($exception->getTrace() as $key => $value):
        $class = $value['class'] ?? null;
        $function = $value['function'] ?? null;
        $line = $value['line'] ?? null;
        $type = $value['type'] ?? null;
        $args = $value['args'] ?? null;
        ?>
        <tr>
            <td><?= $key; ?></td>
            <td><?= $class; ?></td>
            <td><?= $function; ?></td>
            <td><?= $line; ?></td>
            <td><?= $type; ?></td>
            <td><pre><?= var_export($args); ?></pre></td>
        </tr>
    <?php endforeach; ?>
</table>
<pre>
<?php print_r($exception); ?>
</pre>
