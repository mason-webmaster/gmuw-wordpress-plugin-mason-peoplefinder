<?php

/**
 * Summary: php file which implements wpforms customizations
 */


/**
 * Disable the email address suggestion.
 *
 * @link  https://wpforms.com/developers/how-to-disable-the-email-suggestion-on-the-email-form-field/
 */
add_filter( 'wpforms_mailcheck_enabled', '__return_false' );
