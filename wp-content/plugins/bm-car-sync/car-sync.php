<?php
/*
  Plugin Name: BetterMotion Car Sync
  Description: Synchronize car optimization data by file uploads.
  Author: Pierre Wahlberg
  Version: 1.0.0
  Author URI: https://www.linkedin.com/in/pierre-wahlberg/
*/

/**
 * Setup ACF fields if not present
 */
require_once('acf-fields.php');

class CarSync
{
    public const PLUGIN_NAME = 'BM Car sync';
    public const PLUGIN_MENU_LABEL = 'BM Car sync';
    public const INPUT_FIELD_NAME = 'car-sync-json-file';
    public const POST_TYPE = 'car';
}


/**
 * Plugin setup code
 */


add_action('admin_menu', 'car_sync_plugin_setup_menu');
function car_sync_plugin_setup_menu()
{
    add_menu_page(CarSync::PLUGIN_NAME, CarSync::PLUGIN_MENU_LABEL, 'manage_options', 'car-sync-plugin', 'car_sync_init');
}

function car_sync_init()
{
    car_sync_handle_post();
?>
    <h1>Sync inventory of cars and optimization stages</h1>
    <h2>Pick a file and upload to hydrate the site</h2>
    <!-- Form to handle the upload - The enctype value here is very important -->
    <form method="post" enctype="multipart/form-data">
        <input type='file' id='<?= CarSync::INPUT_FIELD_NAME; ?>' name='<?= CarSync::INPUT_FIELD_NAME; ?>'></input>
        <?php submit_button('Upload') ?>
    </form>
<?php
}


function car_sync_handle_post()
{
    // First check if the file appears on the _FILES array
    if (isset($_FILES[CarSync::INPUT_FIELD_NAME])) {
        $json = $_FILES[CarSync::INPUT_FIELD_NAME];

        $name = $json['name'];
        $type = $json['type'];
        $path = $json['tmp_name'];
        $error = $json['error'];
        $size = round($json['size'] / 1024);

        if ($error !== UPLOAD_ERR_OK) {
            return displayUploadErrorMessage($error);
        }

        if ($type != "application/json") {
            return displayError("Non-allowed file type sent: $type");
        }


        $data = json_decode(file_get_contents($path));
        if ($error = json_last_error()) {

            return displayError(json_last_error_msg());
        }

        $rowCount = count($data);
        echo "<h1>Parsed $name</h1>";
        echo "<h2>$size kb, $rowCount records, $type</h2>";

        // TODO this terms shit is not working wellopen
        $carBrands = get_terms(['taxonomy' => 'car_brand', 'hide_empty' => false]);
        $carModels = get_terms(['taxonomy' => 'car_model', 'hide_empty' => false]);
        $carModelYears = get_terms(['taxonomy' => 'car_model_year', 'hide_empty' => false]);
        $carEngines = get_terms(['taxonomy' => 'car_engine', 'hide_empty' => false]);

        $newPosts = 0;
        $updatedPosts = 0;

        foreach ($data as $rec) {

            /**
             * Update or create post
             */
            $title = fixPostTitle($rec->car->name);

            $postId = post_exists($title, '', '', CarSync::POST_TYPE);
            if ($postId === 0) {
                $postId = wp_insert_post([
                    'post_title' => $title,
                    'post_status' => 'publish',
                    'post_type' => CarSync::POST_TYPE
                ]);
                $newPosts++;
            } else {
                $updatedPosts++;
            }

            $term = findOrCreateTerm('car_brand', fixPostTitle($rec->car->brand), $carBrands);
            $res = wp_set_post_terms($postId, $term->name, 'car_brand', false);
            if ($res instanceof WP_Error) {
                return displayError($res->get_error_message());
            }

            $term = findOrCreateTerm('car_model', fixPostTitle($rec->car->model), $carModels);
            wp_set_post_terms($postId, $term->name, 'car_model', false);
            if ($res instanceof WP_Error) {
                return displayError($res->get_error_message());
            }

            $term = findOrCreateTerm('car_model_year', fixPostTitle($rec->car->modelYear), $carModelYears);
            wp_set_post_terms($postId, $term->name, 'car_model_year', false);
            if ($res instanceof WP_Error) {
                return displayError($res->get_error_message());
            }

            $term = findOrCreateTerm('car_engine', fixPostTitle($rec->car->engine), $carEngines);
            wp_set_post_terms($postId, $term->name, 'car_engine', false);
            if ($res instanceof WP_Error) {
                return displayError($res->get_error_message());
            }

            /**
             * Create Stage Repeater data
             */
            $postStages = [];
            foreach ($rec->stages as $stage) {

                $titles = [
                    'stage 1' => 'Steg 1',
                    'stage 2' => 'Steg 2',
                    'stage 3' => 'Steg 3',
                    'fallback' => 'OKÄNT',
                ];

                $prices = [
                    'stage 1' => '5995',
                    'stage 2' => '8995',
                    'fallback' => 'Förfrågan',
                ];

                $postStages[] = [
                    'tab_title' => $titles[$stage->stage] ?? $titles['fallback'],
                    'effect_before' => $stage->power->before,
                    'effect_after' => $stage->power->after,
                    'effect_difference' => $stage->power->difference,
                    'torque_before' => $stage->torque->before,
                    'torque_after' => $stage->torque->after,
                    'torque_difference' => $stage->torque->difference,
                    'price_inc_tax' => $prices[$stage->stage] ?? $prices['fallback'],
                    'e85_compatible' => false,
                    'extra_work_included' => $stage->extra_work ?? 'Ingen information tillgänglig',
                ];
            }
            // update the repeater
            update_field('optimeringssteg', $postStages, $postId);
        }

        // Punchline
        echo "$newPosts new posts was created, $updatedPosts posts was updated.";
        echo "<hr/>";
    }
}

function fixPostTitle($title)
{
    return wp_trim_words($title, 200);
}

function findOrCreateTerm($taxonomy, $termName, &$terms = []): WP_Term
{
    $term = current(array_filter($terms, function ($t) use ($termName) {
        return strcasecmp($termName, $t->name) === 0;
    }));

    if ($term) {
        return $term;
    }

    $newTerm = wp_insert_term($termName, $taxonomy);
    if ($newTerm instanceof WP_Error) {
        dd([$termName, $taxonomy, $term, $terms]);
        die("Attempted to create term $termName in taxonomy $taxonomy, error: " . $newTerm->get_error_message());
    }
    $newTerm = get_term(current($newTerm), $taxonomy);
    array_push($terms, $newTerm);
    return $newTerm;
}

function displayError(string $error)
{
    echo '<div style="padding: 1em; margin: 1em 0; background: rgba(255, 0, 0, 0.12); border: 1px solid red;">';
    echo "<h1>Error occured</h1><p>$error</p>";
    echo "</div>";
}

function displayUploadErrorMessage(int $uploadErrorCode): void
{
    switch ($uploadErrorCode) {
        case UPLOAD_ERR_INI_SIZE:
            $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
            break;
        case UPLOAD_ERR_FORM_SIZE:
            $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
            break;
        case UPLOAD_ERR_PARTIAL:
            $message = "The uploaded file was only partially uploaded";
            break;
        case UPLOAD_ERR_NO_FILE:
            $message = "No file was uploaded";
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            $message = "Missing a temporary folder";
            break;
        case UPLOAD_ERR_CANT_WRITE:
            $message = "Failed to write file to disk";
            break;
        case UPLOAD_ERR_EXTENSION:
            $message = "File upload stopped by extension";
            break;

        default:
            $message = "Unknown upload error";
            break;
    }

    displayError($message);
}

function dd($val)
{
    ob_start();
    echo "<pre>";
    echo json_encode($val, JSON_PRETTY_PRINT);
    echo "</pre>";
    echo ob_get_clean();
}
/**
 * Allow the use of JSON file types in wordpress media filters
 */
function cc_mime_types($mimes)
{
    $mimes['json'] = 'application/json';
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}

add_filter('upload_mimes', 'cc_mime_types');

?>