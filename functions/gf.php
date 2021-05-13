<?php
add_filter( 'gform_validation_message', 'change_message', 10, 2 );
function change_message( $message, $form ) {

  $error = '';
  $message = '';
  foreach ( $form['fields'] as $field ) {
				$failed_field = $field->failed_validation;
				$failed[] = $failed_field;
				$failed_message = strip_tags( $field->validation_message );
				if ( $failed_field ) {
					$error .= '<li><a href="' . $referrer . '#field_' . $form['id'] . '_' . $field['id'] .'">' . $field['label'] . ' - ' . ( ( "" == $field['errorMessage'] ) ? $failed_message:$field['errorMessage'] ) . '</a></li>';
				}
			}

      $length  = count( array_keys( $failed, true ) );
  		$prompt  = sprintf( _n( 'There was %s error in your submission. Errors have been highlighted&nbsp;below.', 'There were %s errors found in your submission. Errors have been highlighted&nbsp;below.', $length, 'gravity-forms-wcag-20-form-fields' ), $length );

  			$message .= "<div id='error' class='alert alert-danger rounded-0' aria-live='assertive' role='alert'>";
  			$message .= "<div class='validation_error' ";
  			if( ! has_action( 'itsg_gf_wcag_disable_tabindex' ) ) {
  				$message .= "tabindex='-1'";
  			}
  			$message .= ">";
  			$message .= $prompt;
  			$message .= "</div>";
  			$message .= "<ol class='validation_list sr-only mb-0'>";
  			$message .= $error;
  			$message .= "</ol>";
  			$message .= "</div>";
  			return $message;

}


add_filter( 'gform_form_tag', 'form_tag', 10, 2 );
function form_tag( $form_tag, $form ) {
// Turn off autocompletion as described here https://developer.mozilla.org/en-US/docs/Web/Security/Securing_your_site/Turning_off_form_autocompletion
$form_tag = preg_replace( "|action='|", "autocomplete='off' action='", $form_tag );
return $form_tag;
}

/**
 * Filters the next, previous and submit buttons.
 * Replaces the forms <input> buttons with <button> while maintaining attributes from original <input>.
 *
 * @param string $button Contains the <input> tag to be filtered.
 * @param object $form Contains all the properties of the current form.
 *
 * @return string The filtered button.
 */


add_filter( 'gform_submit_button', 'input_to_button', 10, 2 );
function input_to_button( $button, $form ) {

    $dom = new DOMDocument();
    $dom->loadHTML( $button );
    $input = $dom->getElementsByTagName( 'input' )->item(0);

    // Skip for all except submit button
    if ( 'submit' !== $input->getAttribute('type') )
    {
        return $button;
    }

    $input->removeAttribute( 'class' );
		$input->setAttribute( 'class', 'gform_button formBtn customBtn' );

    //$input->removeAttribute( 'value' );
    //$input->setAttribute( 'value', 'SUBMIT' );

    return sprintf("<button type='%s' id='%s' tabindex='%s' onclick='%s' onkeypress='%s' class='%s'>%s</button>",
        $input->getAttribute('type'),
        $input->getAttribute('id'),
        $input->getAttribute('tabindex'),
        $input->getAttribute('onclick'),
        $input->getAttribute('onkeypress'),
        $input->getAttribute('class'),
        $input->getAttribute('value')
    );
}



add_filter( 'gform_confirmation_anchor', '__return_true' );

add_filter( 'gform_display_add_form_button', '__return_false' );


add_filter( 'gform_field_content', function ( $field_content, $field ) {
    if ( $field->isRequired == 'true' ) {
        return str_replace( 'type=', "required type=", $field_content );
    }


    return $field_content;
}, 10, 2 );


add_filter( 'gform_field_content_1', function( $field_content, $field ) {
    if ( $field->id == 9 ) {
        return str_replace( '</select>', "</select><i class='fas fa-caret-down fa-lg' aria-hidden='true'></i>", $field_content );
    }
    return $field_content;
}, 10, 2 );
