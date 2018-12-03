<?php

namespace AnyComment\Options;

/**
 * Class AnyCommentField is used to hold information regarding single field item in the form.
 *
 * @author Alexander Teshabaev <sasha.tesh@gmail.com>
 * @package AnyComment\Admin
 */
class AnyCommentField {

	const TYPE_TEXTAREA = 'textarea';
	const TYPE_SELECT = 'select';
	const TYPE_CHECKBOX = 'checkbox';
	const TYPE_TEXT = 'text';
	const TYPE_NUMBER = 'number';

	/**
	 * @var string Field id.
	 */
	public $id;

	/**
	 * @var string Field title.
	 */
	public $title;

	/**
	 * @var string|null For attribute type pointing to the form field itself.
	 */
	public $label_for;

	/**
	 * @var string Field type.
	 */
	public $type;

	/**
	 * @var string|null Description displayed below the field.
	 */
	public $description = null;

	/**
	 * @var null|string|callable Custom content displayed before field.
	 */
	public $before = null;

	/**
	 * @var null|string|callable Custom content displayed after field.
	 */
	public $after = null;

	/**
	 * @var string|null
	 */
	public $hint = null;

	/**
	 * @var array List of additional arguments.
	 */
	public $args = [];

	/**
	 * @var null|string Page slug to which option belongs.
	 */
	public $option_name = null;

	/**
	 * @var string Field wrapping element.
	 */
	public $wrapper = '<div class="woption-field">{content}</div>';

	/**
	 * AnyCommentField constructor.
	 *
	 * @param array $options Associative list of options to set object properties.
	 */
	public function __construct( array $options = [] ) {
		if ( ! empty( $options ) ) {
			foreach ( $options as $key => $value ) {
				if ( property_exists( $this, $key ) ) {
					$this->$key = $value;
				}
			}
		}
	}

	/**
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * @param string $id
	 *
	 * @return $this
	 */
	public function set_id( $id ) {
		$this->id = $id;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_title() {
		return $this->title;
	}

	/**
	 * @param string $title
	 *
	 * @return $this
	 */
	public function set_title( $title ) {
		$this->title = $title;

		return $this;
	}


	/**
	 * @return string
	 */
	public function get_label_for() {

		$label_for = $this->label_for;

		if ( ! empty( $label_for ) ) {
			return $label_for;
		}

		return $this->get_id();
	}

	/**
	 * @param string $label_for
	 *
	 * @return $this
	 */
	public function set_label_for( $label_for ) {
		$this->label_for = $label_for;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * @param string $type
	 *
	 * @return $this
	 */
	public function set_type( $type ) {
		$this->type = $type;

		return $this;
	}

	/**
	 * @return null|string
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * @param null|string $description
	 *
	 * @return $this
	 */
	public function set_description( $description ) {
		$this->description = $description;

		return $this;
	}

	/**
	 * @return null|callable|string
	 */
	public function get_before() {
		return $this->before;
	}

	/**
	 * @param null|callable|string $before
	 *
	 * @return $this
	 */
	public function set_before( $before ) {
		$this->before = $before;

		return $this;
	}

	/**
	 * @return callable|string
	 */
	public function get_after() {
		return $this->after;
	}

	/**
	 * @param callable|string $after
	 *
	 * @return $this
	 */
	public function set_after( $after ) {

		if ( is_callable( $after ) ) {
			$this->after = call_user_func( $after );
		} else {
			$this->after = $after;
		}


		return $this;
	}

	/**
	 * @return null|string
	 */
	public function get_hint() {
		return $this->hint;
	}

	/**
	 * @param null|string $hint
	 *
	 * @return $this
	 */
	public function set_hint( $hint ) {
		$this->hint = $hint;

		return $this;
	}

	/**
	 * @return array
	 */
	public function get_args() {
		return $this->args;
	}

	/**
	 * @param array $args
	 *
	 * @return $this
	 */
	public function set_args( $args ) {
		$this->args = $args;

		return $this;
	}

	/**
	 * Set text type.
	 *
	 * @return $this
	 */
	public function text() {
		$this->set_type( self::TYPE_TEXT );

		return $this;
	}

	/**
	 * Set checkbox type.
	 *
	 * @return $this
	 */
	public function checkbox() {
		$this->set_type( self::TYPE_CHECKBOX );

		return $this;
	}

	/**
	 * Set select type.
	 * @return $this
	 */
	public function select() {
		$this->set_type( self::TYPE_SELECT );

		return $this;
	}

	/**
	 * Set textarea type.
	 * @return $this
	 */
	public function textarea() {
		$this->set_type( self::TYPE_TEXTAREA );

		return $this;
	}

	/**
	 * Get field value is was previously set.
	 *
	 * @return null|string
	 */
	public function get_value() {
		$options = get_option( $this->option_name, null );

		if ( null === $options ) {
			return null;
		}

		$value = isset( $options[ $this->get_id() ] ) ? $options[ $this->get_id() ] : null;

		if ( $value === null ) {
			return null;
		}

		switch ( $value ) {
			case '1':
			case 'true':
			case 'on':
				return true;
			case '0':
			case 'false':
			case 'off':
				return false;
		}

		return $value;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->run();
	}

	/**
	 * Renders field into HMTL.
	 *
	 * @return string
	 */
	public function run() {

		$type        = $this->get_type();
		$label_for   = $this->get_label_for();
		$label_title = $this->get_title();
		$description = $this->get_description();

		$label       = '<label for="' . $label_for . '">' . $label_title . '</label>';
		$description = ! empty( $description ) ?
			'<p class="description">' . $description . '</p>' :
			'';

		$html       = '';
		$field_html = '';

		$html .= $label;

		switch ( $type ) {
			case 'text':
			case 'number':
			case 'numeric':
				$field_html = $this->input_text();
				break;
			case 'toggle':
			case 'checkbox':
				$field_html = $this->input_checkbox();
				break;
			case 'dropdown':
			case 'list':
			case 'select':
				$field_html = $this->input_select();
				break;
			case 'textarea':
				$field_html = $this->input_textarea();
				break;
			case 'color':
				$field_html = $this->input_color();
				break;
			default:
		}

		$html .= $this->get_before();

		$html .= $field_html;
		$html .= $description;

		$html .= $this->get_after();

		return str_replace( '{content}', $html, $this->wrapper );
	}

	/**
	 * Helper to render select.
	 *
	 * @return string
	 */
	public function input_select() {
//		$for     = $args['for'];
//		$name    = $args['name'];
//		$options = $args['options'];
//

		$options_html = '';
		foreach ( $options as $key => $value ) {
			$option_value = $this->get_option( $for );
			$selected     = isset( $this->get_options()[ $for ] ) ? ( selected( $option_value, $key, false ) ) : ( '' );
			$options_html .= <<<EOL
                <option value="$key" $selected>$value</option>
EOL;
		}

		return <<<EOL
            <select name="$name"
                    class="anycomment-select2"
                    id="$for">$options_html</select>
EOL;
	}

	/**
	 * Helper to render checkbox.
	 *
	 * @return string
	 */
	public function input_checkbox() {
		$for               = $this->get_label_for();
		$name              = $this->get_id();
		$value             = $this->get_value();
		$checked_attribute = $value !== null ? 'checked="checked"' : '';

		return <<<EOL
<div class="switch tiny">
    <input class="switch-input" id="$for" type="checkbox"
           name="$name"
        $checked_attribute>
    <label class="switch-paddle" for="$for"></label>
</div>
EOL;
	}

	/**
	 * Helper to render input color.
	 *
	 *
	 * @return string
	 */
	public function input_color() {
		$for   = $this->get_label_for();
		$name  = $this->get_id();
		$value = $this->get_value();

		return <<<EOL
            <input type="text" id="$for"
                   name="$name"
                   value="$value"
                   class="anycomment-input-color"
                   pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$"
            >
EOL;
	}

	/**
	 * Helper to render input text.
	 *
	 * @param array $args List of passed arguments.
	 *
	 * @return string
	 */
	public function input_text() {
		$type  = $this->get_type();
		$for   = $this->get_label_for();
		$name  = $this->get_id();
		$value = $this->get_value();

		return <<<EOT
            <input type="$type" id="$for" name="$name" value="$value">
EOT;
	}

	/**
	 * Helper to render textarea.
	 *
	 * @return string
	 */
	public function input_textarea() {
		$for   = $this->get_label_for();
		$name  = $this->get_id();
		$value = $this->get_value();

		return <<<EOT
            <textarea rows="5" id="$for" name="$name">$value</textarea>
EOT;
	}
}
