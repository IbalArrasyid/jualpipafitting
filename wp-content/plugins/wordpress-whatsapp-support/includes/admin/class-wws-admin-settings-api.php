<?php

class WWS_Admin_Settings_API {

	const PREFIX = 'wws';

	/**
	 * Settings sections array
	 *
	 * @var array
	 */
	protected $settings_sections = array();

	/**
	 * Settings fields array
	 *
	 * @var array
	 */
	protected $settings_fields = array();

	/**
	 * Settings fields type
	 */
	protected $fields_type;

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Set settings sections
	 *
	 * @param array $sections
	 */
	public function set_sections( $sections ) {
		$this->settings_sections = $sections;
		return $this;
	}

	/**
	 * Set settings fields
	 *
	 * @param array $fields
	 */
	public function set_fields( $fields, $fields_type = 'single' ) {
		$this->settings_fields = $fields;
		$this->fields_type     = $fields_type;
		return $this;
	}

	public function admin_init() {
		foreach ( $this->settings_sections as $section ) {
			$id          = isset( $section['id'] ) ? $section['id'] : '';
			$title       = isset( $section['title'] ) ? $section['title'] : '';
			$page        = isset( $section['page'] ) ? $section['page'] : $id;
			$custom_page = isset( $form['custom_page'] ) ? $form['custom_page'] : '';

			if ( $custom_page ) {
				continue;
			}

			if ( isset( $section['desc'] ) ) {
				$callback = function () use ( $section ) {
					echo sprintf( '<div class="inside">%s</div>', wp_kses_post( $section['desc'] ) );
				};
			} else if ( isset( $section['callback'] ) ) {
				$callback = $section['callback'];
			} else {
				$callback = null;
			}

			add_settings_section( $id, $title, $callback, $page );
		}

		foreach ( $this->settings_fields as $section => $field ) {
			foreach ( $field as $option ) {
				if ( isset( $option['name'] ) ) {
					$id = $option['name'];
				} else if ( isset( $option['id'] ) ) {
					$id = $option['id'];
				} else {
					$id = '';
				}

				$type              = isset( $option['type'] ) ? $option['type'] : 'text';
				$title             = isset( $option['title'] ) ? $option['title'] : '';
				$callback          = isset( $option['callback'] ) ? $option['callback'] : array( $this, 'callback_' . $type );
				$sanitize_callback = isset( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : array( $this, 'sanitize_' . $type );
				$section           = isset( $option['section'] ) ? $option['section'] : $section;
				$page              = isset( $option['page'] ) ? $option['page'] : $section;

				$args = wp_parse_args( $option, array(
					'type'              => $type,
					'label_for'         => $id,
					'id'                => $id,
					'classes'           => isset( $option['class'] ) ? $option['class'] : '',
					'css'               => '',
					'desc'              => '',
					'desc_tip'          => '',
					'default'           => '',
					'value'             => '',
					'custom_attributes' => array(),
					'name'              => '',
					'options'           => '',
					'section'           => $section,
				) );

				if ( isset( $args['class'] ) ) {
					unset( $args['class'] );
				}

				// Custom attributes
				if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
					$custom_attributes = array();
					foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
						$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
					}
					$args['custom_attributes'] = $custom_attributes;
				}

				// For select
				if ( 'select' === $type ) {
					$args['select'] = isset( $option['select'] ) ? $option['select'] : array();
				}

				// For dropdown_pages
				if ( 'dropdown_pages' === $type ) {
					$args['select'] = isset( $option['select'] ) ? $option['select'] : array();
					$args['pages']  = isset( $option['pages'] ) ? $option['pages'] : array();
				}

				// For dropdown_categories
				if ( 'dropdown_categories' === $type ) {
					$args['select']     = isset( $option['select'] ) ? $option['select'] : array();
					$args['categories'] = isset( $option['categories'] ) ? $option['categories'] : array();
				}

				// For dropdown_roles
				if ( 'dropdown_roles' === $type ) {
					$args['select'] = isset( $option['select'] ) ? $option['select'] : array();
					$args['roles']  = isset( $option['roles'] ) ? $option['roles'] : array();
				}

				// For wp_editor
				if ( 'wp_editor' === $type ) {
					$args['wp_editor'] = isset( $option['wp_editor'] ) ? $option['wp_editor'] : array();
				}

				// For links
				if ( 'link' === $type ) {
					$args['link']  = isset( $option['link'] ) ? $option['link'] : '';
				}

				// For custom
				if ( 'custom' === $type ) {
					$args['custom'] = isset( $option['custom'] ) ? $option['custom'] : '';
				}

				if ( 'single' === $this->fields_type ) {
					add_settings_field( $id, $title, $callback, $page, $section, $args );

					if ( isset( $option['id'] ) ) {
						register_setting( $section, $id, $sanitize_callback );
					}
				} else { // For multiple options
					add_settings_field( "{$section}[{$id}]", $title, $callback, $page, $section, $args );
				}
			}
		}

		if ( 'single' !== $this->fields_type ) { // For multiple options
			// creates our settings in the options table
			foreach ( $this->settings_sections as $section ) {
				$id = isset( $section['id'] ) ? $section['id'] : '';

				register_setting( $id, $id, array( $this, 'sanitize_options' ) );
			}
		}
	}

	/**
	 * Displays a text field for a settings field
	 *
	 * @param array   $args settings field args
	 */
	public function callback_text( $args, $echo = true ) {
		$value = $this->get_option( $args );
		$name  = $this->get_field_name( $args );

		$html = sprintf( '<input name="%1$s" type="%2$s" id="%3$s" value="%4$s" class="%5$s" style="%6$s" %7$s/>',
			esc_attr( $name ),
			esc_attr( $args['type'] ),
			esc_attr( $args['id'] ),
			esc_attr( $value ),
			esc_attr( $args['classes'] ),
			esc_attr( $args['css'] ),
			implode( ' ', $args['custom_attributes'] ) // WPCS: XSS ok.
		);
		$html .= $this->get_field_description( $args );

		if ( ! $echo ) {
			return $html;
		}

		echo $html;
	}

	/**
	 * Displays a hidden field for a settings field
	 *
	 * @param array   $args settings field args
	 */
	public function callback_hidden( $args, $echo = true ) {
		$args['type'] = 'hidden';
		return $this->callback_text( $args, $echo );
	}

	/**
	 * Displays a url field for a settings field
	 *
	 * @param array   $args settings field args
	 */
	public function callback_url( $args, $echo = true ) {
		return $this->callback_text( $args, $echo );
	}

	/**
	 * Displays a number field for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_number( $args, $echo = true ) {
		return $this->callback_text( $args, $echo );
	}

	/**
	 * Displays a color picker field for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_color( $args, $echo = true ) {
		$args['type']                = 'text';
		$args['custom_attributes'][] = 'data-color-picker="color-picker"';

		return $this->callback_text( $args, $echo );
	}

	/**
	 * Displays a email field for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_email( $args, $echo = true ) {
		return $this->callback_text( $args, $echo );
	}

	/**
	 * Displays a textarea for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_textarea( $args, $echo = true ) {
		$value = $this->get_option( $args );
		$name  = $this->get_field_name( $args );

		$html = sprintf( '<textarea name="%1$s" id="%2$s" class="%4$s" style="%5$s" %6$s>%3$s</textarea>',
			esc_attr( $name ),
			esc_attr( $args['id'] ),
			esc_textarea( $value ),
			esc_attr( $args['classes'] ),
			esc_attr( $args['css'] ),
			implode( ' ', $args['custom_attributes'] ) // WPCS: XSS ok.
		);
		$html .= $this->get_field_description( $args );

		if ( ! $echo ) {
			return $html;
		}

		echo $html;
	}

	/**
	 * Displays a selectbox for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_select( $args, $echo = true ) {
		$select_args = array(
			'multiple'          => false,
			'show_option_none'  => '',
			'option_none_value' => 0,
		);
		$select = wp_parse_args( $args['select'], $select_args );
		$value  = $this->get_option( $args );
		$name   = $this->get_field_name( $args );

		$html = sprintf( '<select name="%1$s%6$s" id="%2$s" class="%3$s" style="%4$s" %5$s %7$s>',
			esc_attr( $name ),
			esc_attr( $args['id'] ),
			esc_attr( $args['classes'] ),
			esc_attr( $args['css'] ),
			implode( ' ', $args['custom_attributes'] ), // WPCS: XSS ok.
			( true === $select['multiple'] ) ? '[]' : '',
			( true === $select['multiple'] ) ? 'multiple' : ''
		);

		if ( '' !== $select['show_option_none'] ) {
			$html .= sprintf( '<option value="%s">%s</option>',
				esc_attr( $select['option_none_value'] ),
				esc_html( $select['show_option_none'] )
			);
		}
		if ( true === $select['multiple'] ) {
			foreach ( $args['options'] as $key => $label ) {
				$selected = in_array( $key, (array) $value ) ? 'selected="selected"' : '';
				$html .= sprintf( '<option value="%s" %s>%s</option>',
					esc_attr( $key ),
					$selected, // WPCS: XSS ok.
					esc_html( $label )
				);
			}
		} else {
			foreach ( $args['options'] as $key => $label ) {
				$html .= sprintf( '<option value="%s" %s>%s</option>',
					esc_attr( $key ),
					selected( $value, $key, false ),
					esc_html( $label )
				);
			}
		}

		$html .= sprintf( '</select>' );
		$html .= $this->get_field_description( $args );

		if ( ! $echo ) {
			return $html;
		}

		echo $html;
	}

	/**
	 * Displays a file upload field for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_file( $args, $echo = true ) {
		$value = $this->get_option( $args );
		$name  = $this->get_field_name( $args );

		$html = sprintf( '<input name="%1$s" type="text" id="%3$s" value="%4$s" class="%5$s wpsa-url" style="%8$s %6$s" %7$s/>',
			esc_attr( $name ),
			esc_attr( $args['type'] ),
			esc_attr( $args['id'] ),
			esc_url( $value ),
			esc_attr( $args['classes'] ),
			esc_attr( $args['css'] ),
			implode( ' ', $args['custom_attributes'] ), // WPCS: XSS ok.
			'width:262px;max-width:100%;'
		);
		$html .= '<input type="button" class="button wpsa-browse" value="Upload File" />';
		$html .= $this->get_field_description( $args );

		if ( ! $echo ) {
			return $html;
		}

		echo $html;
	}

	/**
	 * Displays a checkbox for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_checkbox( $args, $echo = true ) {
		$value = $this->get_option( $args );
		$name  = $this->get_field_name( $args );

		$html = '<fieldset>';
		$html .= sprintf( '<label for="%1$s">', $args['id'] );
		$html .= sprintf( '<input name="%1$s" type="checkbox" id="%3$s" value="%4$s" class="%5$s" style="%6$s" %7$s %8$s/>',
			esc_attr( $name ),
			esc_attr( $args['type'] ),
			esc_attr( $args['id'] ),
			esc_attr( $value ), // WPCS: XSS ok.
			esc_attr( $args['classes'] ),
			esc_attr( $args['css'] ),
			implode( ' ', $args['custom_attributes'] ),
			checked( $value, 'yes', false )
		);
		$html .= sprintf( '%1$s</label>', $this->get_field_description( $args ) );
		$html .= '</fieldset>';

		if ( ! $echo ) {
			return $html;
		}

		echo $html;
	}

	/**
	 * Displays a checkboxgroup for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_checkboxgroup( $args, $echo = true ) {
		$value = $this->get_option( $args );
		$name  = $this->get_field_name( $args );

		$html = '<fieldset>';
		$html .= sprintf( '<input type="hidden" name="%1$s" value=""/>', esc_html( $name ) );
		foreach ( $args['options'] as $key => $label ) {
			$checked = checked( 'yes', isset( $value[$key] ) ? $value[$key] : '0', false );
			$html .= sprintf( '<input type="hidden" name="%1$s[temp][%2$s]" value="nothing"/>', esc_attr( $name ), esc_attr( $key ) );
			$html .= sprintf( '<label for="%1$s[%2$s]">', esc_attr( $args['id'] ), esc_attr( $key ) );
			$html .= sprintf( '<input name="%1$s[%2$s]" type="checkbox" id="%4$s[%2$s]" value="%2$s" class="checkbox" %3$s/>',
				esc_attr( $name ),
				esc_attr( $key ),
				$checked, // WPCS: XSS ok.
				esc_attr( $args['id'] )
			);
			$html .= sprintf( '%1$s</label><br>', esc_html( $label ) );
		}
		$html .= $this->get_field_description( $args );
		$html .= '</fieldset>';

		if ( ! $echo ) {
			return $html;
		}

		echo $html;
	}

	/**
	 * Displays a radio button for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_radio( $args, $echo = true ) {
		$value = $this->get_option( $args );
		$name  = $this->get_field_name( $args );

		$html = '<fieldset>';
		foreach ( $args['options'] as $key => $label ) {
			$html .= sprintf( '<label for="%1$s[%2$s]">', esc_attr( $args['id'] ), esc_attr( $key ) );
			$html .= sprintf( '<input name="%1$s" type="radio" id="%2$s[%3$s]" value="%3$s" class="radio" %4$s />',
				esc_attr( $name ),
				esc_html( $args['id'] ),
				esc_html( $key ),
				checked( $value, $key, false )
			);
			$html .= sprintf( '%1$s</label><br>', esc_html( $label ) );
		}
		$html .= $this->get_field_description( $args );
		$html .= '</fieldset>';

		if ( ! $echo ) {
			return $html;
		}

		echo $html;
	}

	/**
	 * Displays a rich text textarea for a settings field
	 *
	 * @param array   $args settings field args
	 */
	function callback_wp_editor( $args, $echo = true ) {
		$value = $this->get_option( $args );
		$name  = $this->get_field_name( $args );

		$editor_args = apply_filters( self::PREFIX . '_wp_editor', array(
			'editor_width'  => '550',
			'media_buttons' => false,
			'editor_height' => '120',
			'textarea_name' => $name,
		) );

		$editor = wp_parse_args( $args['wp_editor'], $editor_args );

		$html = '<div style="max-width: ' . esc_attr( $editor['editor_width'] ) . 'px;">';
		ob_start();
		wp_editor( $value, $args['id'], $editor );
		$html .= ob_get_clean();

		$html .= '</div>';
		$html .= $this->get_field_description( $args );

		if ( ! $echo ) {
			return $html;
		}

		echo $html;
	}

	/**
	 * Display pages dropdown
	 *
	 * @param array $args
	 */
	function callback_dropdown_pages( $args, $echo = true ) {
		$value = $this->get_option( $args );
		$name  = $this->get_field_name( $args );

		$pages_args = apply_filters( self::PREFIX . '_dropdown_pages', array(
			'posts_per_page' => -1,
			'post_type'      => 'page',
			'order'          => 'ASC',
			'orderby'        => 'title',
		) );
		$select_args = array(
			'multiple'          => false,
			'show_option_none'  => '',
			'option_none_value' => 0,
		);
		$pages  = wp_parse_args( $args['pages'], $pages_args );
		$select = wp_parse_args( $args['select'], $select_args );
		$query  = new WP_Query( $pages );

		$html = sprintf( '<select name="%1$s%6$s" id="%2$s" class="%3$s" style="%4$s" %5$s %7$s>',
			esc_attr( $name ),
			esc_attr( $args['id'] ),
			esc_attr( $args['classes'] ),
			esc_attr( $args['css'] ),
			implode( ' ', $args['custom_attributes'] ),
			( true === $select['multiple'] ) ? '[]' : '',
			( true === $select['multiple'] ) ? 'multiple' : ''
		);

		if ( '' !== $select['show_option_none'] ) {
			$html .= sprintf( '<option value="%s">%s</option>',
				esc_attr( $select['option_none_value'] ),
				esc_html( $select['show_option_none'] )
			);
		}

		if ( $query->have_posts() ) {
			if ( true === $select['multiple'] ) {
				while ( $query->have_posts() ) {$query->the_post();
					$page_id    = get_the_ID();
					$page_title = get_the_title();
					$selected   = in_array( $page_id, (array) $value ) ? 'selected="selected"' : '';
					$html .= sprintf( '<option value="%1$s" %3$s>%2$s</option>',
						esc_attr( $page_id ),
						esc_html( $page_title ),
						$selected // WPCS: XXS ok.
					);
				}
			} else {
				while ( $query->have_posts() ) {$query->the_post();
					$page_id    = get_the_ID();
					$page_title = get_the_title();
					$selected   = selected( $page_id, $value, false );
					$html .= sprintf( '<option value="%1$s" %3$s>%2$s</option>',
						esc_attr( $page_id ),
						esc_html( $page_title ),
						$selected // WPCS: XXS ok.
					);
				}
			}
		}
		$html .= sprintf( '</select>' );
		$html .= $this->get_field_description( $args );

		if ( ! $echo ) {
			return $html;
		}

		echo $html;
	}

	/**
	 * Display posts dropdown
	 *
	 * @param array $args
	 */
	function callback_dropdown_posts( $args, $echo = true ) {
		$value = $this->get_option( $args );
		$name  = $this->get_field_name( $args );

		$post_args = apply_filters( self::PREFIX . '_dropdown_posts', array(
			'posts_per_page' => -1,
			'post_type'      => 'post',
			'order'          => 'ASC',
			'orderby'        => 'title',
		) );
		$select_args = array(
			'multiple'          => false,
			'show_option_none'  => '',
			'option_none_value' => 0,
		);
		$posts  = wp_parse_args( $args['posts'], $post_args );
		$select = wp_parse_args( $args['select'], $select_args );
		$query  = new WP_Query( $posts );

		$html = sprintf( '<select name="%1$s%6$s" id="%2$s" class="%3$s" style="%4$s" %5$s %7$s>',
			esc_attr( $name ),
			esc_attr( $args['id'] ),
			esc_attr( $args['classes'] ),
			esc_attr( $args['css'] ),
			implode( ' ', $args['custom_attributes'] ),
			( true === $select['multiple'] ) ? '[]' : '',
			( true === $select['multiple'] ) ? 'multiple' : ''
		);

		if ( '' !== $select['show_option_none'] ) {
			$html .= sprintf( '<option value="%s">%s</option>',
				esc_attr( $select['option_none_value'] ),
				esc_html( $select['show_option_none'] )
			);
		}

		if ( $query->have_posts() ) {
			if ( true === $select['multiple'] ) {
				while ( $query->have_posts() ) { $query->the_post();
					$post_id    = get_the_ID();
					$post_title = get_the_title();
					$selected   = in_array( $post_id, (array) $value ) ? 'selected="selected"' : '';
					$html .= sprintf( '<option value="%1$s" %3$s>%2$s</option>',
						esc_attr( $post_id ),
						esc_html( $post_title ),
						$selected // WPCS: XXS ok.
					);
				}
			} else {
				while ( $query->have_posts() ) { $query->the_post();
					$post_id    = get_the_ID();
					$post_title = get_the_title();
					$selected   = selected( $post_id, $value, false );
					$html .= sprintf( '<option value="%1$s" %3$s>%2$s</option>',
						esc_attr( $post_id ),
						esc_html( $post_title ),
						$selected // WPCS: XXS ok.
					);
				}
			}
		}
		$html .= sprintf( '</select>' );
		$html .= $this->get_field_description( $args );

		if ( ! $echo ) {
			return $html;
		}

		echo $html;
	}

	/**
	 * Display categories dropdown
	 *
	 * @param array $args
	 */
	function callback_dropdown_categories( $args, $echo = true ) {
		$value = $this->get_option( $args );
		$name  = $this->get_field_name( $args );

		$categories_args = apply_filters( self::PREFIX . '_dropdown_categories', array(
			'taxonomy' => 'category',
		) );
		$select_args = array(
			'multiple'          => false,
			'show_option_none'  => '',
			'option_none_value' => 0,
		);

		$categories = wp_parse_args( $args['categories'], $categories_args );
		$select     = wp_parse_args( $args['select'], $select_args );
		$query      = get_terms( $categories );

		$html = sprintf( '<select name="%1$s%6$s" id="%2$s" class="%3$s" style="%4$s" %5$s %7$s>',
			esc_attr( $name ),
			esc_attr( $args['id'] ),
			esc_attr( $args['classes'] ),
			esc_attr( $args['css'] ),
			implode( ' ', $args['custom_attributes'] ),
			( true === $select['multiple'] ) ? '[]' : '',
			( true === $select['multiple'] ) ? 'multiple' : ''
		);

		if ( '' !== $select['show_option_none'] ) {
			$html .= sprintf( '<option value="%s">%s</option>',
				esc_attr( $select['option_none_value'] ),
				esc_html( $select['show_option_none'] )
			);
		}

		if ( true === $select['multiple'] ) {
			foreach ( $query as $c ) {
				$cat_id   = $c->term_id;
				$cat_name = $c->name;
				$selected = in_array( $cat_id, (array) $value ) ? 'selected="selected"' : '';
				$html .= sprintf( '<option value="%1$s" %3$s>%2$s</option>',
					esc_attr( $cat_id ),
					esc_html( $cat_name ),
					$selected // WPCS: XXS ok.
				);
			}
		} else {
			foreach ( $query as $c ) {
				$cat_id   = $c->term_id;
				$cat_name = $c->name;
				$selected = selected( $cat_id, $value, false );
				$html .= sprintf( '<option value="%1$s" %3$s>%2$s</option>',
					esc_attr( $cat_id ),
					esc_attr( $cat_name ),
					$selected // WPCS: XXS ok.
				);
			}
		}

		$html .= sprintf( '</select>' );
		$html .= $this->get_field_description( $args );

		if ( ! $echo ) {
			return $html;
		}

		echo $html;
	}

	/**
	 * Display roles dropdown
	 *
	 * @param array $args
	 */
	function callback_dropdown_roles( $args, $echo = true ) {
		$value = $this->get_option( $args );
		$name  = $this->get_field_name( $args );

		$roles_args  = apply_filters( self::PREFIX . '_dropdown_roles', array() );
		$select_args = array(
			'multiple'          => false,
			'show_option_none'  => '',
			'option_none_value' => 0,
		);

		$roles  = wp_parse_args( $args['roles'], $roles_args );
		$select = wp_parse_args( $args['select'], $select_args );
		$query  = array_reverse( get_editable_roles() );

		if ( '' !== $select['show_option_none'] ) {
			$html .= sprintf( '<option value="%s">%s</option>',
				esc_attr( $select['option_none_value'] ),
				esc_html( $select['show_option_none'] )
			);
		}

		$html = sprintf( '<select name="%1$s%6$s" id="%2$s" class="%3$s" style="%4$s" %5$s %7$s>',
			esc_attr( $name ),
			esc_attr( $args['id'] ),
			esc_attr( $args['classes'] ),
			esc_attr( $args['css'] ),
			implode( ' ', $args['custom_attributes'] ),
			( true === $select['multiple'] ) ? '[]' : '',
			( true === $select['multiple'] ) ? 'multiple' : ''
		);

		if ( true === $select['multiple'] ) {
			foreach ( $query as $r_slug => $r ) {
				$selected = in_array( $r_slug, (array) $value ) ? 'selected="selected"' : '';
				$html .= sprintf( '<option value="%1$s" %3$s>%2$s</option>',
					esc_attr( $r_slug ),
					esc_html( $r['name'] ),
					$selected // WPCS: XXS ok.
				);
			}
		} else {
			foreach ( $query as $r_slug => $r ) {
				$selected = selected( $r_slug, $value, false );
				$html .= sprintf( '<option value="%1$s" %3$s>%2$s</option>',
					esc_attr( $r_slug ),
					esc_html( $r['name'] ),
					$selected // WPCS: XXS ok.
				);
			}
		}

		$html .= sprintf( '</select>' );
		$html .= $this->get_field_description( $args );

		if ( ! $echo ) {
			return $html;
		}

		echo $html;
	}

	/**
	 * Display the link and buttons
	 *
	 * @param array $args
	 */
	function callback_link( $args, $echo = true ) {
		$html = sprintf( '<a href="%1$s" class="%2$s" %4$s>%3$s</a>',
			esc_url( $args['link'] ),
			esc_attr( $args['classes'] ),
			esc_html( $args['value'] ),
			implode( ' ', $args['custom_attributes'] )
		);

		if ( ! $echo ) {
			return $html;
		}

		echo $html;
	}

	/**
	 * Display the custom html
	 *
	 * @param array $args
	 */
	function callback_custom( $args, $echo = true ) {
		if ( ! $echo ) {
			return $args['custom'];
		}

		echo $args['custom'];
	}

	/**
	 * Sanitize callback for text
	 *
	 * @param string $input
	 */
	public function sanitize_text( $input ) {
		return sanitize_text_field( $input );
	}

	/**
	 * Sanitize callback for hidden
	 *
	 * @param string $input
	 */
	public function sanitize_hidden( $input ) {
		return sanitize_text_field( $input );
	}

	/**
	 * Sanitize callback for url
	 *
	 * @param string $input
	 */
	function sanitize_url( $input ) {
		return esc_url_raw( $input );
	}

	/**
	 * Sanitize callback for email
	 *
	 * @param string $input
	 */
	function sanitize_email( $input ) {
		return sanitize_email( $input );
	}

	/**
	 * Sanitize callback for textarea
	 *
	 * @param string $input
	 */
	function sanitize_textarea( $input ) {
		return sanitize_textarea_field( $input );
	}

	/**
	 * Sanitize callback for color
	 *
	 * @param string $input
	 */
	function sanitize_color( $input ) {
		return sanitize_hex_color( $input );
	}

	/**
	 * Sanitize callback for select dropdown
	 *
	 * @param string $input
	 */
	function sanitize_select( $input ) {
		if ( null === $input ) {
			return array();
		}
		if ( is_array( $input ) ) {
			foreach ( $input as $i ) {
				$new_input[] = sanitize_text_field( $i );
			}
			return $new_input;
		} else {
			return sanitize_text_field( $input );
		}
	}

	/**
	 * Sanitize callback for checkbox
	 *
	 * @param string $input
	 */
	function sanitize_checkbox( $input ) {
		return isset( $input ) ? 'yes' : 'no';
	}

	/**
	 * Sanitize callback for checkbox group
	 *
	 * @param array $input
	 */
	public function sanitize_checkboxgroup( $input ) {
		$new_input = array();
		if ( is_array( $input ) ) {
			foreach ( $input['temp'] as $t_k => $t_v ) {
				$new_input[$t_k] = isset( $input[$t_k] ) ? 'yes' : 'no';
			}
		}
		return $new_input;
	}

	/**
	 * Sanitize callback for wp_editor
	 *
	 * @param string $input
	 */
	function sanitize_wp_editor( $input ) {
		return wp_kses_post( $input );
	}

	/**
	 * Sanitize callback for pages dropdown
	 *
	 * @param string|array $input
	 */
	function sanitize_dropdown_pages( $input ) {
		if ( null === $input ) {
			return array();
		}
		if ( is_array( $input ) ) {
			foreach ( $input as $i ) {
				$new_input[] = intval( $i );
			}
			return $new_input;
		} else {
			return intval( $input );
		}
	}

	/**
	 * Sanitize callback for posts dropdown
	 *
	 * @param string|array $input
	 */
	function sanitize_dropdown_posts( $input ) {
		if ( null === $input ) {
			return array();
		}
		if ( is_array( $input ) ) {
			foreach ( $input as $i ) {
				$new_input[] = intval( $i );
			}
			return $new_input;
		} else {
			return intval( $input );
		}
	}

	/**
	 * Sanitize callback for categories dropdown
	 *
	 * @param string|array $input
	 */
	function sanitize_dropdown_categories( $input ) {
		if ( null === $input ) {
			return array();
		}
		if ( is_array( $input ) ) {
			foreach ( $input as $i ) {
				$new_input[] = intval( $i );
			}
			return $new_input;
		} else {
			return intval( $input );
		}
	}

	/**
	 * Sanitize callback for roles dropdown
	 *
	 * @param string}array $input
	 */
	function sanitize_dropdown_roles( $input ) {
		if ( null === $input ) {
			return array();
		}
		if ( is_array( $input ) ) {
			foreach ( $input as $i ) {
				$new_input[] = sanitize_text_field( $i );
			}
			return $new_input;
		} else {
			return sanitize_text_field( $input );
		}
	}

	/**
	 * Sanitize callback for Settings API
	 *
	 * @return mixed
	 */
	public function sanitize_options( $options ) {
		if ( ! $options ) {
			return $options;
		}
		foreach ( $options as $option_slug => $option_value ) {
			$sanitize_callback = $this->get_sanitize_callback( $option_slug );
			// If callback is set, call it
			if ( $sanitize_callback ) {
				$options[$option_slug] = call_user_func( $sanitize_callback, $option_value );
				continue;
			}
		}
		return $options;
	}

	/**
	 * Get sanitization callback for given option slug
	 *
	 * @param string $slug option slug
	 * @return mixed string or bool false
	 */
	function get_sanitize_callback( $slug = '' ) {
		if ( empty( $slug ) ) {
			return false;
		}
		// Iterate over registered fields and see if we can find proper callback
		foreach ( $this->settings_fields as $section => $options ) {
			foreach ( $options as $option ) {
				$option_id = isset( $option['id'] ) ? $option['id'] : '';
				if ( $option_id != $slug ) {
					continue;
				}

				// Return the callback name
				return isset( $option['sanitize_callback'] ) && is_callable( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : false;
			}
		}
		return false;
	}

	/**
	 * Get name field
	 *
	 * @param array $args
	 * @return string
	 */
	public function get_field_name( $args ) {
		if ( 'single' === $this->fields_type ) {
			$name = $args['name'] ? $args['name'] : $args['id'];
		} else {
			$name = $args['name'] ? $args['section'] . '[' . $args['name'] . ']' : $args['section'] . '[' . $args['id'] . ']';
		}

		return $name;
	}

	/**
	 * Get options
	 *
	 * @param array $args
	 * @return mixed
	 */
	public function get_option( $args ) {
		if ( '' !== $args['value'] ) {
			return $args['value'];
		}
		if ( $args['name'] ) {
			$option = get_option( $args['name'] );

			if ( '' !== $option ) {
				return $option;
			}
		}
		if ( $args['id'] ) {
			if ( 'single' === $this->fields_type ) {
				$option = get_option( $args['id'] );
				if ( $option ) {
					return $option;
				}
			} else {
				$option = get_option( $args['section'], $args['id'] );

				if ( isset( $option[$args['id']] ) ) {
					return $option[$args['id']];
				}
			}
		}

		return $args['default'];
	}

	/**
	 * Get field description for display
	 *
	 * @param array   $args settings field args
	 */
	public function get_field_description( $args ) {
		$desc = '';

		switch ( $args['type'] ) {
		case 'checkbox':
			// Checkbox
			if ( is_array( $args['desc'] ) ) {
				foreach ( $args['desc'] as $d_k => $d ) {
					if ( 'checkbox' === $args['type'] && 0 === $d_k ) {
						$desc .= wp_kses_post( $d );
					} else {
						$desc .= sprintf( '<p class="description">%s</p>', wp_kses_post( $d ) );
					}
				}
			} else if ( ! empty( $args['desc'] ) ) {
				$desc = wp_kses_post( $args['desc'] );
			}
			break;
		default:
			if ( is_array( $args['desc'] ) ) {
				foreach ( $args['desc'] as $d_k => $d ) {
					$desc .= sprintf( '<p class="description">%s</p>', wp_kses_post( $d ) );
				}
			} else if ( ! empty( $args['desc'] ) ) {
				$desc = sprintf( '<p class="description">%s</p>', wp_kses_post( $args['desc'] ) );
			}
			break;
		}

		if ( $args['desc_tip'] ) {
			$desc .= sprintf( '<div data-tooltip="%s"><span class="dashicons dashicons-warning"></span></div>', wp_kses_post( $args['desc_tip'] ) );
		}

		return $desc;
	}

	public function admin_enqueue_scripts() {
		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_media();
		wp_enqueue_script( 'jquery' );
	}

	/**
	 * Show navigations as tab
	 *
	 * Shows all the settings section labels as tab
	 */
	function show_navigation() {
		$html  = '<h2 class="nav-tab-wrapper">';
		$count = count( $this->settings_sections );
		// don't show the navigation if only one section exists
		if ( $count === 1 ) {
			return;
		}
		foreach ( $this->settings_sections as $tab ) {
			if ( isset( $tab['display'] ) && false === $tab['display'] ) {
				continue;
			}
			$html .= sprintf( '<a href="#%1$s" class="nav-tab" id="%1$s-tab">%2$s</a>', $tab['id'], $tab['title'] );
		}
		$html .= '</h2>';
		echo $html;
	}

	/**
	 * Show the section settings forms
	 *
	 * This function displays every sections in a different form
	 */
	function show_forms() {
		?>
		<div class="metabox-holder">
			<?php
				foreach ( $this->settings_sections as $form ) {
					if ( isset( $tab['display'] ) && false === $tab['display'] ) {
						continue;
					}
			?>
				<div id="<?php echo $form['id']; ?>" class="group" style="display: none;">
					<?php
						if ( isset( $form['custom_page'] ) ) {
							require $form['custom_page'];
						} else {
							?>
							<form method="post" action="options.php">
								<?php
								do_action( self::PREFIX . '_form_top_' . $form['id'], $form );
								settings_fields( $form['id'] );
								do_settings_sections( $form['id'] );
								do_action( self::PREFIX . '_form_bottom_' . $form['id'], $form );
								if ( isset( $this->settings_fields[$form['id']] ) ):
								?>
								<div style="padding-left: 10px">
									<?php submit_button();?>
								</div>
								<?php endif;?>
							</form>
							<?php
						}
					?>
				</div>
			<?php }?>
		</div>
		<?php
	}

	/**
	 * Show the section settings form
	 *
	 * This function displays every sections in a different form
	 */
	function show_form( $section ) {
		?>
		<div class="metabox-holder">
			<?php
				foreach ( $this->settings_sections as $form ) {
					if ( $section !== $form['id'] ) {
						continue;
					}
				?>
				<div id="<?php echo $form['id']; ?>">
					<form method="post" action="options.php">
						<?php
						do_action( self::PREFIX . '_form_top_' . $form['id'], $form );
						settings_fields( $form['id'] );
						do_settings_sections( $form['id'] );
						do_action( self::PREFIX . '_form_bottom_' . $form['id'], $form );
						if ( isset( $this->settings_fields[$form['id']] ) ):
						?>
						<div style="padding-left: 10px">
							<?php submit_button();?>
						</div>
						<?php endif;?>
					</form>
				</div>
			<?php }?>
		</div>
		<?php
	}

	/**
	 * --------------------------------------------------------------------
	 * Render form only
	 * --------------------------------------------------------------------
	*/
	public function render_tr( $field, $args = array() ) {
		$tr = '<tr>';
		$tr .= sprintf( '<th><label for="%s">%s</label></th>', esc_attr( $args['id'] ), esc_html( $args['title'] ) );
		$tr .= sprintf( '<td>%s</td>', $field );
		$tr .= '</th>';
		return $tr;
	}

	public function render_form( $fields, $args = array() ) {
		if ( ! $fields && ! is_array( $fields ) ) {
			return;
		}

		$args = wp_parse_args( $args, array(
			'class'       => '',
			'action'      => '#',
			'method'      => 'post',
			'button_text' => '',
			'button_name' => 'submit',
		) );

		$this->fields_type = 'single';

		$hidden_field = '';
		$form         = sprintf( '<form action="%s" method="%s" class="%s">', esc_html( $args['action'] ), esc_html( $args['method'] ), esc_attr( $args['class'] ) );
		$form        .= '<table class="form-table">';
		$form        .= '<tbody>';

		foreach( $fields as $field ) {
			if ( isset( $field['name'] ) ) {
				$id = $field['name'];
			} else if ( isset( $field['id'] ) ) {
				$id = $field['id'];
			} else {
				$id = '';
			}

			$type = isset( $field['type'] ) ? $field['type'] : 'text';

			$a = wp_parse_args( $field, array(
				'title'             => '',
				'id'                => $id,
				'type'              => $type,
				'name'              => '',
				'value'             => '',
				'default'           => '',
				'classes'           => isset( $field['class'] ) ? $field['class'] : '',
				'desc'              => '',
				'desc_tip'          => '',
				'custom_attributes' => array(),
				'options'           => '',
				'css'               => '',
				'section'           => '',
			) );

			// For select
			if ( 'select' === $type ) {
				$a['select'] = isset( $field['select'] ) ? $field['select'] : array();
			}

			// For dropdown_pages
			if ( 'dropdown_pages' === $type ) {
				$a['select'] = isset( $field['select'] ) ? $field['select'] : array();
				$a['pages']  = isset( $field['pages'] ) ? $field['pages'] : array();
			}

			// For dropdown_posts
			if ( 'dropdown_posts' === $type ) {
				$a['select'] = isset( $field['select'] ) ? $field['select'] : array();
				$a['posts']  = isset( $field['posts'] ) ? $field['posts'] : array();
			}

			// For dropdown_categories
			if ( 'dropdown_categories' === $type ) {
				$a['select']     = isset( $field['select'] ) ? $field['select'] : array();
				$a['categories'] = isset( $field['categories'] ) ? $field['categories'] : array();
			}

			// For dropdown_roles
			if ( 'dropdown_roles' === $type ) {
				$a['select'] = isset( $field['select'] ) ? $field['select'] : array();
				$a['roles']  = isset( $field['roles'] ) ? $field['roles'] : array();
			}

			// For wp_editor
			if ( 'wp_editor' === $type ) {
				$a['wp_editor'] = isset( $field['wp_editor'] ) ? $field['wp_editor'] : array();
			}

			// For links
			if ( 'link' === $type ) {
				$a['link']  = isset( $field['link'] ) ? $field['link'] : '';
			}

			// For custom
			if ( 'custom' === $type ) {
				$a['custom'] = isset( $field['custom'] ) ? $field['custom'] : '';
			}

			// Custom attributes
			if ( ! empty( $a['custom_attributes'] ) && is_array( $a['custom_attributes'] ) ) {
				$custom_attributes = array();
				foreach ( $a['custom_attributes'] as $attribute => $attribute_value ) {
					$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
				}
				$a['custom_attributes'] = $custom_attributes;
			}

			switch ( $a['type'] ) {
				case 'text':
					$form .= $this->render_tr( $this->callback_text( $a, false ), $a );
					break;

				case 'number':
					$form .= $this->render_tr( $this->callback_number( $a, false ), $a );
					break;

				case 'email':
					$form .= $this->render_tr( $this->callback_email( $a, false ), $a );
					break;

				case 'url':
					$form .= $this->render_tr( $this->callback_url( $a, false ), $a );
					break;

				case 'hidden':
					$hidden_field .= $this->callback_hidden( $a, false );
					break;

				case 'textarea':
					$form .= $this->render_tr( $this->callback_textarea( $a, false ), $a );
					break;

				case 'color':
					$form .= $this->render_tr( $this->callback_color( $a, false ), $a );
					break;

				case 'link':
					$form .= $this->render_tr( $this->callback_link( $a, false ), $a );
					break;

				case 'select':
					$form .= $this->render_tr( $this->callback_select( $a, false ), $a );
					break;

				case 'checkbox':
					$form .= $this->render_tr( $this->callback_checkbox( $a, false ), $a );
					break;

				case 'checkboxgroup':
					$form .= $this->render_tr( $this->callback_checkboxgroup( $a, false ), $a );
					break;

				case 'radio':
					$form .= $this->render_tr( $this->callback_radio( $a, false ), $a );
					break;

				case 'wp_editor':
					$form .= $this->render_tr( $this->callback_wp_editor( $a, false ), $a );
					break;

				case 'file':
					$form .= $this->render_tr( $this->callback_file( $a, false ), $a );
					break;

				case 'custom':
					$form .= $this->render_tr( $this->callback_custom( $a, false ), $a );
					break;

				case 'dropdown_pages':
					$form .= $this->render_tr( $this->callback_dropdown_pages( $a, false ), $a );
					break;

				case 'dropdown_posts':
					$form .= $this->render_tr( $this->callback_dropdown_posts( $a, false ), $a );
					break;

				case 'dropdown_categories':
					$form .= $this->render_tr( $this->callback_dropdown_categories( $a, false ), $a );
					break;

				case 'dropdown_roles':
					$form .= $this->render_tr( $this->callback_dropdown_roles( $a, false ), $a );
					break;

				case 'separator':
					$form .= '<tr><td colspan="2"><hr></td></tr>';
					break;

				default:
					$form .= $this->render_tr( $this->callback_text( $a, false ), $a );
					break;
			}
		}

		$form .= '</tbody>';
		$form .= '</table>';
		$form .= $hidden_field;
		$form .= get_submit_button( $args['button_text'], 'primary large', $args['button_name'] );
		$form .= '</form>';
		echo $form;

	}

}
