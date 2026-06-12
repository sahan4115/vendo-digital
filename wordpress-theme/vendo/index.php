<?php
/**
 * Fallback template.
 *
 * The landing page is rendered by front-page.php, which WordPress always
 * uses for the site's front page. This index.php exists because every
 * theme must have one; it loads the same front-page content so the theme
 * still renders correctly if the front page is ever reassigned.
 *
 * @package Vendo
 */

// Reuse the landing page so there's a single source of truth.
get_template_part( 'front-page' );
