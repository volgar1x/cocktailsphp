<?php



/**
 * displays the application's layout
 * @param callable $body
 * @author Antoine Chauvin <antoine.chauvin@etu.univ-lehavre.fr>
 */
function layout($body) {
?>
<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cocktails</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <link rel="stylesheet" href="css/app.css" />
  </head>
  <body>

    <div class="row">
      <div class="large-12 columns">
        <h1>Cocktails</h1>
      </div>
    </div>

    <?php $body(); ?>

    <script src="js/vendor/jquery.min.js"></script>
    <script src="js/vendor/what-input.min.js"></script>
    <script src="js/foundation.min.js"></script>
    <script src="js/app.js"></script>
  </body>
</html>
<?php
}



/**
 * displays a table of {@link BoissonMetier}
 * @param array $boissons
 * @param int $current
 * @param int $count
 * @author Antoine Chauvin <antoine.chauvin@etu.univ-lehavre.fr>
 */
function boissons($boissons, $current, $count = 8) {
    $it = new LimitIterator(new ArrayIterator($boissons), $current * $count, $count);
?>

<?php pagination($boissons, $current, $count); ?>

<div class="row">
    <div class="columns small-12">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Degré</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($it as $boisson): ?>
                <tr>
                    <td><?php echo $boisson->getNom(); ?></td>
                    <td><?php echo $boisson->getDegreAlcool(); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php pagination($boissons, $current, $count); ?>

<?php
}



/**
 * displays a list of link helping the user to navigate through {@link BoissonMetier}
 * I guess this view is pretty generic and can be used with other models...
 *
 * @param array $boissons
 * @param int $current
 * @param int $count
 * @author Antoine Chauvin <antoine.chauvin@etu.univ-lehavre.fr>
 */
function pagination($boissons, $current, $count) {
    $items = count($boissons);
    $pages = intval($items / $count);

?>
<div class="row">
    <div class="columns small-12 text-center">
        <ul class="pagination" role="navigation" aria-label="Pagination">
            <?php echo content_tag("li", ["disabled" => ($current == 1)], function() use($current) { ?>
                <?php if ($current == 1): ?>
                    Previous
                <?php else: ?>
                    <a href="?page=<?php echo ($current - 1) ?>">Previous</a>
                <?php endif; ?>
            <?php }) ?>

            <?php
            for ($i = 1; $i <= $pages; $i++) {
                echo content_tag("li", ["current" => ($i == $current)], function() use($i, $current) { ?>
                    <?php if ($i == $current): ?>
                        <?php echo $i ?>
                    <?php else: ?>
                        <a href="?page=<?php echo $i ?>"><?php echo $i ?></a>
                    <?php endif; ?>
                <?php });
            }
            ?>

            <?php echo content_tag("li", ["disabled" => ($current == $pages)], function() use($current, $pages) { ?>
                <?php if ($current == $pages): ?>
                    Next
                <?php else: ?>
                    <a href="?page=<?php echo ($current + 1) ?>">Next</a>
                <?php endif; ?>
            <?php }) ?>
          </ul>
    </div>
</div>
<?php
}



/**
 * helper function helping to construct hard tags,
 * it is specially useful when dealing with classes
 *
 * @param string $name
 * @param array $classes
 * @param string|callable $body
 * @author Antoine Chauvin <antoine.chauvin@etu.univ-lehavre.fr>
 */
function content_tag($name, $classes, $body) {
    $classes_str = '';

    foreach ($classes as $key => $value) {
        if (is_string($key) && $value) {
            $classes_str .= $key . ' ';
        } else if (is_int($key) && is_string($value)) {
            $classes_str .= $value . ' ';
        }
    }

    $classes_str = trim($classes_str);

    $result = "<$name";

    if (strlen($classes_str) > 0) {
        $result .= ' class="' . $classes_str . '"';
    }

    $result .= '>';

    if (is_callable($body)) {
        ob_start();
        $body();
        $body = ob_get_contents();
        ob_end_clean();
    }

    $result .= $body;

    $result .= "</$name>";

    return $result;
}
