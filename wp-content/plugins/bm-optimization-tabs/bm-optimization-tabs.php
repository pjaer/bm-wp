<?php
/*
  Plugin Name: BetterMotion Optimization tabs
  Description: Display optimization steps as SEO friendly tabs on page
  Author: Pierre Wahlberg
  Version: 1.0.0
  Author URI: https://www.linkedin.com/in/pierre-wahlberg/
*/

/**
 * Load the jQuery plugin $().mariTabs() for all pages
 */
add_action('wp_enqueue_scripts', function () {

    wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-3.6.0.slim.min.js');
    wp_enqueue_script('optisteps_tabs_script', plugin_dir_url(__FILE__) . 'scripts/mari-tabs.js', ['jquery'], false, true);
    wp_enqueue_style('optisteps_tabs_css', plugin_dir_url(__FILE__) . 'scripts/mari-tabs.css');
});

/**
 * Shortcode to render an ACF Repeater subfield wherever user feels plausible
 */
add_shortcode('acf_sub_field', function ($atts = []) {
    global $post;
    $pid = $post->id;
    $subfield = $atts['field'];

    $sf_object = get_sub_field_object($subfield);

    return $sf_object['value'] . " " . $sf_object['append'];
});

/**
 * Shortcode to render the Optimization Steps tabs and effect table
 */
add_shortcode('opti_stage_table', function () {
    ob_start();

    // *Repeater
    // tabs_repeater
    // *Sub-Fields
    // tab_header
    // tab_content

    // check if the repeater field has rows of data
    if (have_rows('optimeringssteg')) {
        $i = 1; // Set the increment variable
        $tabs = []; // list of all the tabs-object in repeater

        // Tabs wrapper
        echo '<div class="tab-wrap">';

        // loop through the rows of data for the tab header
        while (have_rows('optimeringssteg')) {

            the_row();

            $tab = [
                'tab_title' => get_sub_field_object('tab_title'),
                'effect_before' => get_sub_field_object('effect_before'),
                'effect_after' => get_sub_field_object('effect_after'),
                'effect_difference' => get_sub_field_object('effect_difference'),
                'torque_before' => get_sub_field_object('torque_before'),
                'torque_after' => get_sub_field_object('torque_after'),
                'torque_difference' => get_sub_field_object('torque_difference'),
                'price_inc_tax' => get_sub_field_object('price_inc_tax'),
                'e85_compatible' => get_sub_field_object('e85_compatible'),
                'extra_work_included'   => get_sub_field_object('extra_work_included'),
            ];
            array_push($tabs, $tab);
        }

        // Start list of tabs
        echo '<ul class="tabs-nav">';

        for ($x = 1; $x <= count($tabs); $x++) {
            $tab = &$tabs[$x - 1];
            $heading = $tab['tab_title']['value'];
            $activeClass = $x === 1 ? 'active' : '';
            echo "<li class='{$activeClass}' title=\"{$heading}\">{$heading}</li>";
        }

        // End list of tabs
        echo "</ul>";

        // tabs content
        echo "<ul class=\"tabs-content\">";

        for ($x = 1; $x <= count($tabs); $x++) {
            $tab = &$tabs[$x - 1];
            $activeClass = $x === 1 ? 'active-content' : '';

?>
            <li class="<?php echo $activeClass; ?>">
                <section>
                    <h2><?php echo $tab['tab_title']['value']; ?></h2>
                    <table class="stage-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Original</th>
                                <th>Efter</th>
                                <th>Skillnad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Effekt</td>
                                <td><?php echo $tab['effect_before']['value']; ?> hk</td>
                                <td><?php echo $tab['effect_after']['value']; ?> hk</td>
                                <td><?php echo $tab['effect_difference']['value']; ?> hk</td>
                            </tr>

                            <tr>
                                <td>Vridmoment</td>
                                <td><?php echo $tab['torque_before']['value']; ?> Nm</td>
                                <td><?php echo $tab['torque_after']['value']; ?> Nm</td>
                                <td><?php echo $tab['torque_difference']['value']; ?> Nm</td>
                            </tr>

                            <tr>
                                <td colspan="3">Pris ink. moms</td>
                                <td><?php echo $tab['price_inc_tax']['value']; ?> kr</td>
                            </tr>
                        </tbody>
                    </table>

                    <p>
                        <i>
                            <?php if ($tab['e85_compatible']['value']) : ?>
                                * Denna optimering stödjer E85
                            <?php else : ?>
                                * Denna opimering stödjer inte E85
                            <?php endif; ?>
                        </i>
                    </p>

                    <?php if (strlen($tab['extra_work_included']['value'])) : ?>
                        <p>
                            <?= $tab['extra_work_included']['value']; ?>
                        </p>
                    <?php endif; ?>

                </section>
            </li>
<?php
        }

        // close ul.tabs-content
        echo "</ul>";

        // close div.tab-wrap
        echo "</div>";
    } else {
        echo "<h2>Vi ber om ursäkt</h2><p>Inga optimeringar har lagts till för denna bil.</p>";
    }

    // Return any generated content
    return ob_get_clean();
});
