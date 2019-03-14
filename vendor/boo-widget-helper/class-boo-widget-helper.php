<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Boo_Widget_Helper
 *
 * This is widget helper class to ease the process of creating widgets
 *
 * idea is taken from Woocommerce plugin.
 *
 * @version 2.0
 *
 * @author RaoAbid | BooSpot
 * @link https://github.com/boospot/boo-widget-helper
 */
if ( ! class_exists( 'Boo_Widget_Helper' ) ) :
	/**
	 * Class Boo_Widget_Helper
	 */
	abstract class Boo_Widget_Helper extends WP_Widget {

		/**
		 * CSS class.
		 *
		 * @var string
		 */
		protected $widget_cssclass;

		/**
		 * Widget description.
		 *
		 * @var string
		 */
		protected $widget_description;

		/**
		 * Widget ID.
		 *
		 * @var string
		 */
		protected $widget_id;

		/**
		 * Widget name.
		 *
		 * @var string
		 */
		protected $widget_name;

		/**
		 * Fields for widget settings.
		 *
		 * @var array
		 */
		protected $fields = array();

		/**
		 * Prefix for scripts handles
		 *
		 * @var array
		 */
		protected $prefix = "boo_widget_helper_";

		/**
		 * Field Types for widget settings.
		 *
		 * @var array
		 */
		protected $field_types = array();

		/**
		 * Constructor.
		 *
		 * @param null $config_array
		 */
		public function __construct( $config_array = null ) {

			// Extract widget configuration properties from the array
			if ( ! empty( $config_array ) && is_array( $config_array ) ) {
				$this->set_properties( $config_array );
			}


			$widget_ops = array(
				'classname'                   => $this->widget_cssclass,
				'description'                 => $this->widget_description,
				'customize_selective_refresh' => true,
			);

			// Call parent __construct
			parent::__construct( $this->widget_id, $this->widget_name, $widget_ops );

			add_action( 'save_post', array( $this, 'flush_widget_cache' ) );
			add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
			add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );

			// For fields like color, file, media
			add_action( 'load-widgets.php', array( $this, 'register_scripts' ) );
			add_action( 'load-widgets.php', array( $this, 'maybe_enqueue_scripts' ) );

		}

		/**
		 * Set Properties of the class
		 *
		 * @param array $config_array
		 */
		protected function set_properties( array $config_array ) {


			if (
				isset( $config_array['prefix'] )
				&&
				! empty( $config_array['prefix'] )
			) {
				$this->set_prefix( $config_array['prefix'] );
			}

			if ( isset( $config_array['id'] ) ) {
				$this->set_widget_id( $config_array['id'] );
			}

			if ( isset( $config_array['name'] ) ) {
				$this->set_widget_name( $config_array['name'] );
			}

			if ( isset( $config_array['desc'] ) ) {
				$this->set_widget_desc( $config_array['desc'] );
			}

			if ( isset( $config_array['class'] ) ) {
				$this->set_widget_cssclass( $config_array['class'] );
			}

			// Do we have fields config, if yes, call the method
			if ( isset( $config_array['fields'] ) ) {
				$this->set_fields( $config_array['fields'] );
			}

		}

		/**
		 * @param $prefix_string
		 */
		public function set_prefix( $prefix_string ) {
			$this->prefix = sanitize_key( $prefix_string );
		}

		/**
		 * Set Widget id
		 *
		 * @param $widget_id
		 */
		public function set_widget_id( $widget_id ) {

			$this->widget_id = $widget_id;
		}

		/**
		 * Set Widget name
		 *
		 * @param $widget_name
		 */
		public function set_widget_name( $widget_name ) {

			$this->widget_name = $widget_name;
		}

		/**
		 * Set Widget description
		 *
		 * @param $widget_desc
		 */
		public function set_widget_desc( $widget_desc ) {

			$this->widget_description = $widget_desc;
		}

		/**
		 * Set Widget css class
		 *
		 * @param $widget_cssclass
		 */
		public function set_widget_cssclass( $widget_cssclass ) {

			$this->widget_cssclass = $widget_cssclass;
		}

		/**
		 * Set settings fields
		 *
		 * @param array $fields settings fields array
		 */
		public function set_fields( $fields ) {
			$this->fields = array_merge_recursive( $this->fields, $fields );
			$this->normalize_fields();
		}

		/**
		 * Register Scripts for Admin section
		 */
		public function register_scripts() {

			wp_register_script(
				$this->prefix . 'admin_scripts',
				null,
				array(),
				false,
				true
			);
		}

		/**
		 * Get cached widget.
		 *
		 * @param  array $args Arguments.
		 *
		 * @return bool true if the widget is cached otherwise false
		 */
		public function get_cached_widget( $args ) {
			$cache = wp_cache_get( $this->get_widget_id_for_cache( $this->widget_id ), 'widget' );

			if ( ! is_array( $cache ) ) {
				$cache = array();
			}

			if ( isset( $cache[ $this->get_widget_id_for_cache( $args['widget_id'] ) ] ) ) {
				echo $cache[ $this->get_widget_id_for_cache( $args['widget_id'] ) ]; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped

				return true;
			}

			return false;
		}

		/**
		 * Cache the widget.
		 *
		 * @param  array $args Arguments.
		 * @param  string $content Content.
		 *
		 * @return string the content that was cached
		 */
		public function cache_widget( $args, $content ) {
			$cache = wp_cache_get( $this->get_widget_id_for_cache( $this->widget_id ), 'widget' );

			if ( ! is_array( $cache ) ) {
				$cache = array();
			}

			$cache[ $this->get_widget_id_for_cache( $args['widget_id'] ) ] = $content;

			wp_cache_set( $this->get_widget_id_for_cache( $this->widget_id ), $cache, 'widget' );

			return $content;
		}

		/**
		 * Flush the cache.
		 */
		public function flush_widget_cache() {
			foreach ( array( 'https', 'http' ) as $scheme ) {
				wp_cache_delete( $this->get_widget_id_for_cache( $this->widget_id, $scheme ), 'widget' );
			}
		}

		/**
		 * Output the html at the start of a widget.
		 *
		 * @param array $args Arguments.
		 * @param array $instance Instance.
		 */
		public function widget_start( $args, $instance ) {
			echo $args['before_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped

			if ( $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base ) ) { // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.Found, WordPress.CodeAnalysis.AssignmentInCondition.Found
				echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
			}
		}

		/**
		 * Output the html at the end of a widget.
		 *
		 * @param  array $args Arguments.
		 */
		public function widget_end( $args ) {
			echo $args['after_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
		}

		/**
		 * @param $type
		 *
		 * @return array
		 */
		public function get_sanitize_callback_method( $type ) {

			return ( method_exists( $this, "sanitize_{$type}" ) )
				? array( $this, "sanitize_{$type}" )
				: array( $this, "sanitize_text" );

		}

		//DEBUG

		/**
		 * @param $type
		 * @param $log_line
		 */
		public function write_log( $type, $log_line ) {

			$hash = '';
			$fn   = plugin_dir_path( __FILE__ ) . '/' . $type . '-' . $hash . '.log';
			file_put_contents( $fn, date( 'Y-m-d H:i:s' ) . ' - ' . $log_line . PHP_EOL, FILE_APPEND );

		}

		/**
		 * Updates a particular instance of a widget.
		 *
		 * @see    WP_Widget->update
		 *
		 * @param  array $new_instance New instance.
		 * @param  array $old_instance Old instance.
		 *
		 * @return array
		 */
		public function update( $new_instance, $old_instance ) {

			/**
			 * Check if current user have privilege
			 *
			 * https://codex.wordpress.org/Roles_and_Capabilities
			 * see 'edit_theme_options'
			 */
			if ( ! current_user_can( 'edit_theme_options' ) ) {

				// do nothing and just bailout with $old_instance
				return $old_instance;
			}


			$instance = $old_instance;

			if ( empty( $this->fields ) ) {
				return $instance;
			}


//			$this->write_log( 'instance', var_export( $new_instance, 'true' ) );
			// Loop settings and get values to save.
			foreach ( $this->fields as $field_id => $field ) {

				$field['sanitize_callback'] =
					( is_callable( $field['sanitize_callback'] ) )
						? $field['sanitize_callback']
						: $this->get_sanitize_callback_method( $field['type'] );

				$dirty_value =
					isset( $new_instance[ $field['id'] ] )
						? $new_instance[ $field['id'] ]
						: '';

				$instance[ $field['id'] ] = call_user_func_array(
					$field['sanitize_callback'],
					array( $dirty_value )
				);


			}

			$this->flush_widget_cache();

			return $instance;
		}

		/**
		 * Get Default Field args for options fields
		 *
		 * @param $field_id
		 * @param $field
		 *
		 * @return array
		 */
		public function get_default_field_args( $field_id, $field ) {

			$type = isset( $field['type'] ) ? $field['type'] : 'text';

			return array(
				'id'                => $field_id,
				'name'              => $field_id,
				'class'             => 'widefat',
				'label'             => '',
				'desc'              => '',
				'options'           => array(),
				'callback'          =>
					( isset( $field['callback'] ) )
						? $field['callback']
						: $this->get_field_markup_callback_name( $type ),
				'sanitize_callback' => '',
				'type'              => 'text',
				'placeholder'       => '',
				'value'             => '',
				'show_in_rest'      => true,
				'default'           => '',
			);

		}

		/**
		 * @param $type
		 *
		 * @return string callback method name
		 */
		public function get_field_markup_callback_name( $type ) {

			return ( method_exists( $this, "callback_{$type}" ) )
				? "callback_{$type}"
				: "callback_text";


		}

		/**
		 * Normalize Fields / settings for widget
		 */
		public function normalize_fields() {

			$normalized_fields = array();

			if ( is_array( $this->fields ) && ! empty( $this->fields ) ):

				$need_field_id = ( $this->isAssoc( $this->fields ) ) ? false : true;

				foreach ( $this->fields as $key => $field ) {

					if ( $need_field_id ) {
						$field_id =
							( isset( $field['id'] ) && ! empty( $field['id'] ) )
								? $field['id']
								: 'field_' . $key;
					} else {
						$field_id = $key;
					}


					$normalized_fields[ $field_id ] =
						wp_parse_args(
							$this->fields[ $key ],
							$this->get_default_field_args( $field_id, $field )
						);

				}


			endif;

			$this->fields = $normalized_fields;
		}

		/**
		 * check if a an array is associative or sequential
		 */
		public function isAssoc( array $arr ) {

			if ( array() === $arr ) {
				return false;
			}

			return array_keys( $arr ) !== range( 0, count( $arr ) - 1 );
		}

		/**
		 * Outputs the settings update form.
		 *
		 * @see   WP_Widget->form
		 *
		 * @param array $instance Instance.
		 */
		public function form( $instance ) {

			if ( empty( $this->fields ) ) {
				return;
			}

			// Loop through the fields to display form
			foreach ( $this->fields as $field_id => $args ) :

				$args['value'] =
					isset( $instance[ $field_id ] )
						? $instance[ $field_id ]
						: $args['default'];

				$this->field_start( $args );

				// check if the user has overridden callable?
				if ( is_callable( $args['callback'] ) ) {
					call_user_func_array(
						$args['callback'],
						array( $args )
					);
				} elseif ( is_callable( array( $this, $args['callback'] ) ) ) {
					call_user_func_array(
						array( $this, $args['callback'] ),
						array( $args )
					);
				} else {
					echo 'function is not callable';
				}

				$this->field_end( $args );
			endforeach;


		}

		/**
		 *
		 */
		protected function widget_instance_fields() {
			?>
            <input type="hidden" name="widget_id" value="<?php echo sanitize_key( $this->widget_id ); ?>">
            <input type="hidden" name="widget_number" value="<?php echo absint( $this->number ); ?>">
			<?php
		}


		/**
		 * @param array $args
		 * @param array $instance
		 */
		function widget( $args, $instance ) {

			if ( $this->get_cached_widget( $args ) ) {
				return;
			}
			ob_start();
			$this->widget_start( $args, $instance );

			/**
			 * Your Magic stats here.
			 */
			echo $this->widget_display( $args, $instance );
			/**
			 * Your magic ends here
			 */

			$this->widget_end( $args );
			echo $this->cache_widget( $args, ob_get_clean() ); // WPCS: XSS ok.

		}


		/*
		 * This Method is meant to be overridden by the class extending this class
		 */
		/**
		 * @param $args
		 * @param $instance
		 *
		 * @return string
		 */
		function widget_display( $args, $instance ) {
			return '';
		}


		/**
		 * @return array
		 */
		function get_default_instance() {

			$default_instance = array();

			foreach ( $this->fields as $key => $setting ) {
				$default_instance[ $key ] = isset( $setting['default'] ) ? $setting['default'] : '';
			}

			return $default_instance;
		}


		/**
		 * @param $instance
		 *
		 * @return array
		 */
		function normalize_instance( $instance ) {
			return wp_parse_args( $instance, $this->get_default_instance() );
		}

		/**
		 * Get widget id plus scheme/protocol to prevent serving mixed content from (persistently) cached widgets.
		 *
		 * @since  3.4.0
		 *
		 * @param  string $widget_id Id of the cached widget.
		 * @param  string $scheme Scheme for the widget id.
		 *
		 * @return string            Widget id including scheme/protocol.
		 */
		protected function get_widget_id_for_cache( $widget_id, $scheme = '' ) {
			if ( $scheme ) {
				$widget_id_for_cache = $widget_id . '-' . $scheme;
			} else {
				$widget_id_for_cache = $widget_id . '-' . ( is_ssl() ? 'https' : 'http' );
			}

			return $widget_id_for_cache;
		}

		/**
		 * @param $value
		 *
		 * @return string
		 */
		function sanitize_text( $value ) {
			return ( ! empty( $value ) ) ? sanitize_text_field( $value ) : '';
		}

		/**
		 * @param $value
		 *
		 * @return int|string
		 */
		function sanitize_number( $value ) {
			return ( is_numeric( $value ) ) ? $value : 0;
		}

		/**
		 * @param $value
		 *
		 * @return string
		 */
		function sanitize_textarea( $value ) {
			return sanitize_textarea_field( $value );
		}

		/**
		 * @param $value
		 *
		 * @return int
		 */
		function sanitize_checkbox( $value ) {
			return ( $value === '1' ) ? 1 : 0;
		}

		/**
		 * @param $value
		 *
		 * @return string
		 */
		function sanitize_select( $value ) {
			return $this->sanitize_text( $value );
		}

		/**
		 * @param $value
		 *
		 * @return string
		 */
		function sanitize_radio( $value ) {
			return $this->sanitize_text( $value );
		}

		/**
		 * @param $value
		 *
		 * @return array
		 */
		function sanitize_multicheck( $value ) {

			return
				( is_array( $value ) )
					? array_map( 'sanitize_text_field', $value )
					: array();
		}

		/**
		 * @param $value
		 *
		 * @return array|int
		 */
		function sanitize_taxonomy( $value ) {

			return
				( is_array( $value ) )
					? array_map( 'sanitize_text_field', $value ) // taxonomy terms object property
					: sanitize_text_field( $value ); //if select field is used

		}

		/**
		 * @param $value
		 *
		 * @return string
		 */
		function sanitize_color( $value ) {

			if ( false === strpos( $value, 'rgba' ) ) {
				return sanitize_hex_color( $value );
			} else {
				// By now we know the string is formatted as an rgba color so we need to further sanitize it.

				$value = trim( $value, ' ' );
				$red   = $green = $blue = $alpha = '';
				sscanf( $value, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );

				return 'rgba(' . $red . ',' . $green . ',' . $blue . ',' . $alpha . ')';
			}
		}

		/**
		 * @param $value
		 *
		 * @return string
		 */
		function sanitize_url( $value ) {
			return esc_url_raw( $value );
		}

		/**
		 * @param $value
		 *
		 * @return string
		 */
		function sanitize_file( $value ) {
//		    TODO: if the option to store file as file url
			return esc_url_raw( $value );
		}

		/**
		 * @param $value
		 *
		 * @return null
		 */
		function sanitize_html( $value ) {
			// nothing to save
			return null;
		}

		/**
		 * @param $value
		 *
		 * @return array|int
		 */
		function sanitize_posts( $value ) {

			return
				( is_array( $value ) )
					? array_map( 'absint', $value ) // post ids
					: absint( $value );

		}

		/**
		 * @param $value
		 *
		 * @return int
		 */
		function sanitize_pages( $value ) {
			// Only store page id
			return absint( $value );
		}

		/**
		 * @param $value
		 *
		 * @return int
		 */
		function sanitize_media( $value ) {
			// Only store media id
			return absint( $value );
		}

		/**
		 *
		 */
		public function field_start( $args ) {

			echo "<p>";

		}

		/**
		 * @param $args
		 */
		public function field_end( $args ) {

			echo $this->get_field_description( $args );
			echo "</p>";

		}

		/**
		 * Displays a text field for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_text( $args ) {

			printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_name( $args['name'] ) ),
				$args['label']
			);
			printf(
				'<input 
                        type="%1$s" 
                        id="%2$s" 
                        name="%3$s" 
                        value="%4$s"
                        %5$s
                        class="%6$s" 
                        />',
				$args['type'],
				esc_attr( $this->get_field_id( $args['id'] ) ),
				esc_attr( $this->get_field_name( $args['name'] ) ),
				$args['value'],
				$this->get_markup_placeholder( $args['placeholder'] ),
				sanitize_html_class( $args['class'] )
			);

		}

		/**
		 * Displays a url field for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_url( $args ) {
			$this->callback_text( $args );
		}

		/**
		 * Displays a number field for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_number( $args ) {
			$min  = ( isset( $args['min'] ) && ! empty( $args['min'] ) ) ? ' min="' . $args['min'] . '"' : '';
			$max  = ( isset( $args['max'] ) && ! empty( $args['max'] ) ) ? ' max="' . $args['max'] . '"' : '';
			$step = ( isset( $args['step'] ) && ! empty( $args['step'] ) ) ? ' step="' . $args['step'] . '"' : '';

			printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_name( $args['name'] ) ),
				$args['label']
			);
			printf(
				'<input 
                        type="%1$s" 
                        id="%2$s" 
                        name="%3$s" 
                        value="%4$s"
                        class="%5$s"
                        %6$s,
                        %7$s,
                        %8$s,
                        %9$s,
                        />',
				$args['type'],
				esc_attr( $this->get_field_id( $args['id'] ) ),
				esc_attr( $this->get_field_name( $args['name'] ) ),
				$args['value'],
				sanitize_html_class( $args['class'] ),
				$this->get_markup_placeholder( $args['placeholder'] ),
				$min,
				$max,
				$step
			);

			unset( $min, $max, $step );
		}

		/**
		 * Displays a checkbox for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_checkbox( $args ) {

			printf(
				'<input 
                    type="%1$s" 
                    id="%2$s" 
                    name="%3$s" 
                    class="checkbox %4$s" 
                    %5$s />',
				'checkbox',
				esc_attr( $this->get_field_id( $args['id'] ) ),
				esc_attr( $this->get_field_name( $args['name'] ) ),
				sanitize_html_class( $args['class'] ),
				checked( $args['value'], '1', false )
			);
			printf(
				'<label for="%s">%s</label><br/>',
				esc_attr( $this->get_field_name( $args['name'] ) ),
				$args['label']
			);

		}

		/**
		 * Displays a multicheckbox for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_multicheck( $args ) {

			$value = ! empty( $args['value'] ) ? $args['value'] : $args['default'];

			printf(
				'<fieldset class="%s">',
				sanitize_html_class( $args['class'] )
			);
			printf(
				'<label for="%s">%s</label><br />',
				esc_attr( $this->get_field_name( $args['name'] ) ),
				$args['label']
			);

			foreach ( $args['options'] as $key => $label ) {
				$checked = isset( $value[ $key ] ) ? $value[ $key ] : '0';
				printf(
					'<label for="%1$s[%2$s]">',
					$args['name'],
					$key
				);
				printf(
					'<input 
                            type="%1$s"
                            id="%2$s[%5$s]" 
                            name="%3$s[%5$s]" 
                            class="checkbox %4$s" 
                            value="%5$s" 
                            %6$s 
                            />',
					"checkbox",
					esc_attr( $this->get_field_id( $args['id'] ) ),
					esc_attr( $this->get_field_name( $args['name'] ) ),
					sanitize_html_class( $args['class'] ),
					$key,
					checked( $checked, $key, false )
				);
				printf( '%1$s</label><br />', $label );
			}

			echo '</fieldset>';
			unset( $value );
		}

		/**
		 * Displays a radio button for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_radio( $args ) {

			$value = $args['value'];

			if ( empty( $value ) ) {
				$value = is_array( $args['default'] ) ? $args['default'] : array();
			}

			printf(
				'<fieldset class="%s">',
				sanitize_html_class( $args['class'] )
			);
			printf(
				'<label for="%s">%s</label><br />',
				esc_attr( $this->get_field_name( $args['name'] ) ),
				$args['label']
			);

			foreach ( $args['options'] as $option_key => $label ) {

				printf(
					'<label for="%1$s[%2$s]">',
					$args['name'],
					$option_key
				);
				printf(
					'<input 
                            type="%1$s"
                            id="%2$s[%5$s]" 
                            name="%3$s" 
                            class="checkbox %4$s" 
                            value="%5$s" 
                            %6$s 
                            />',
					"radio",
					esc_attr( $this->get_field_id( $args['id'] ) ),
					esc_attr( $this->get_field_name( $args['name'] ) ),
					sanitize_html_class( $args['class'] ),
					$option_key,
					checked( $args['value'], $option_key, false )
				);

				printf( '%1$s</label><br />', $label );
			}

			echo '</fieldset>';

			unset( $value );
		}

		/**
		 * Displays a selectbox for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_select( $args ) {

			printf(
				'<label for="%s">%s',
				esc_attr( $this->get_field_name( $args['name'] ) ),
				$args['label']
			);
			printf( '<select 
                    id="%1$s" 
                    name="%2$s" 
                    class="select %3$s"
                    >',
				esc_attr( $this->get_field_id( $args['id'] ) ),
				esc_attr( $this->get_field_name( $args['name'] ) ),
				sanitize_html_class( $args['class'] )
			);

			foreach ( $args['options'] as $key => $label ) {
				printf( '<option value="%1s"%2s>%3s</option>',
					$key,
					selected( $args['value'], $key, false ),
					$label
				);
			}

			printf( '</select>' );

		}

		/**
		 * Displays a textarea for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_textarea( $args ) {

			printf(
				'<label for="%s">%s</label><br/>',
				esc_attr( $this->get_field_name( $args['name'] ) ),
				$args['label']
			);
			printf(
				'<textarea 
                        id="%1$s" 
                        name="%2$s" 
                        class="textarea %3$s" 
                        %5$s
                        rows="%6$s" 
                        cols="%7$s" 
                        >%4$s</textarea><br/>',
				esc_attr( $this->get_field_id( $args['id'] ) ),
				esc_attr( $this->get_field_name( $args['name'] ) ),
				sanitize_html_class( $args['class'] ),
				$args['value'],
				$this->get_markup_placeholder( $args['placeholder'] ),
				( isset( $args['rows'] ) ) ? absint( $args['rows'] ) : 5,
				( isset( $args['cols'] ) ) ? absint( $args['cols'] ) : 30
			);

		}

		/**
		 * Displays the html for a settings field
		 *
		 * @param array $args settings field args
		 *
		 */
		function callback_html( $args ) {
			echo $args['default'];
		}

		/**
		 * Displays a file upload field for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_file( $args ) {

			printf(
				'<label for="%s">%s</label><br/>',
				esc_attr( $this->get_field_name( $args['name'] ) ),
				$args['label']
			);

			printf( '<input 
                            type="text" 
                            id="%1$s" 
                            name="%2$s" 
                            class="%3$s boospot-file-url" 
                            value="%4$s"
                            />',
				esc_attr( $this->get_field_id( $args['id'] ) ),
				esc_attr( $this->get_field_name( $args['name'] ) ),
				sanitize_html_class( $args['class'] ),
				$args['value']
			);
			printf( '<input type="button" class="button boospot-file-browse-button" value="%s" /><br/>',
				isset( $args['options']['btn'] )
					? esc_html( $args['options']['btn'] )
					: __( 'Choose File', '' )
			);

		}

		/*
		 * @return array configured field types
		 */
		/**
		 * @return array
		 */
		public function get_field_types() {

			foreach ( $this->fields as $field ) {
				$this->field_types[] = isset( $field['type'] ) ? sanitize_key( $field['type'] ) : 'text';
			}

			return array_unique( $this->field_types );
		}

		/**
		 * Maybe Enque Scripts if required
		 */
		public function maybe_enqueue_scripts() {

			$scripts_required = false;
			$field_types      = $this->get_field_types();

			if ( in_array( 'color', $field_types ) ) {

				wp_enqueue_style( 'wp-color-picker' ); 
				wp_enqueue_script( 'wp-color-picker' );

				$script =
					"(function($){
	
                    var booColorPickerParams = { 
                        change: function(e, ui) {
                          $( e.target ).val( ui.color.toString() );
                          $( e.target ).trigger('change'); // enable widget 'Save' button
                        },
                      }
                     
                    var parent = $('body');
                    if ($('body').hasClass('widgets-php')){
                        parent = $('.widget-liquid-right');
                    }
                    jQuery(document).ready(function($) {
                        parent.find('.wp-color-picker').wpColorPicker(booColorPickerParams);
                    });
                    
                    jQuery(document).on('widget-added', function(e, widget){
                        widget.find('.wp-color-picker').wpColorPicker(booColorPickerParams);
                    });
                    
                    jQuery(document).on('widget-updated', function(e, widget){
                        widget.find('.wp-color-picker').wpColorPicker(booColorPickerParams);
                    });
                    
                })(jQuery);";

				wp_add_inline_script( $this->prefix . 'admin_scripts', $script );
				$scripts_required = true;
			}

			// Load scripts only if field type "file" is added
			if ( in_array( 'file', $field_types ) ) {

				$script =
					"jQuery(document).ready(function ($) {
                    // For Files Upload
                    if ($('.boospot-file-browse-button').length > 0) {
                        $('.boospot-file-browse-button').off('click');
                        $('.boospot-file-browse-button').on('click', function (event) {
                            event.preventDefault();

                            var self = $(this);

                            // Create the media frame.
                            var file_frame = wp.media.frames.file_frame = wp.media({
                                title: self.data('uploader_title'),
                                button: {
                                    text: self.data('uploader_button_text'),
                                },
                                multiple: false
                            });

                            file_frame.on('select', function () {
                                attachment = file_frame.state().get('selection').first().toJSON();
                                self.prev('.boospot-file-url').val(attachment.url).change();
                            });

                            // Finally, open the modal
                            file_frame.open();
                        });
                    }
                    });";

				wp_add_inline_script( $this->prefix . 'admin_scripts', $script );
				$scripts_required = true;
			}

			if ( in_array( 'media', $field_types ) ) {

				$script =
					"// The 'Upload' button
                    if ($('.boospot-image-upload').length > 0) {
                        $('.boospot-image-upload').off('click');
                        $('.boospot-image-upload').click(function () {
                            var send_attachment_bkp = wp.media.editor.send.attachment;
                            var button = $(this);
                            wp.media.editor.send.attachment = function (props, attachment) {
                                $(button).parent().prev().attr('src', attachment.url);
                                if (attachment.id) {
                                    $(button).prev().val(attachment.id);
                                    // Activate Save button
                                    $(button)
                                        .parents('.widget-content')
                                        .siblings('.widget-control-actions')
                                        .find('.widget-control-save')
                                        .removeAttr('disabled');
                                }
                                wp.media.editor.send.attachment = send_attachment_bkp;
                            }
                            wp.media.editor.open(button);
                            return false;
                        });
                    }

                    // The 'Remove' button (remove the value from input type='hidden')
                    if ($('.boospot-image-remove').length > 0) {
                        $('.boospot-image-remove').off('click');
                        $('.boospot-image-remove').click(function () {
                            var answer = confirm('Are you sure?');
                            if (answer == true) {
                                var src = $(this).parent().prev().attr('data-src');
                                $(this).parent().prev().attr('src', src);
                                $(this).prev().prev().val('');
                                $(this)
                                    .parents('.widget-content')
                                    .siblings('.widget-control-actions')
                                    .find('.widget-control-save')
                                    .removeAttr('disabled');
                            }
                            return false;
                        });
                    }";

				wp_add_inline_script( $this->prefix . 'admin_scripts', $script );
				$scripts_required = true;
			}

			//if flag is set, then enqueue the script
			if ( $scripts_required ) {
				wp_enqueue_script( $this->prefix . 'admin_scripts' );
			}

		}


		/**
		 * Generate: Uploader field
		 *
		 * @param array $args
		 *
		 * @source: https://mycyberuniverse.com/integration-wordpress-media-uploader-plugin-options-page.html
		 */
		public function callback_media( $args ) {

			// Set variables
			$default_image = ! empty( $args['default'] ) ? esc_url_raw( $args['default'] ) : 'https://www.placehold.it/115x115';
			$max_width     = isset( $args['options']['max_width'] ) ? absint( $args['options']['max_width'] ) : 150;
			$width         = isset( $args['options']['width'] ) ? absint( $args['options']['width'] ) : '';
			$height        = isset( $args['options']['height'] ) ? absint( $args['options']['height'] ) : '';
			$text          = isset( $args['options']['btn'] ) ? sanitize_text_field( $args['options']['btn'] ) : __( 'Upload', '' );
//			$name          = $this->get_field_name( $args['options_id'], $args['section'], $args['id'] );
//			$args['value'] = esc_attr( $this->get_option( $args['id'], $args['section'], $args['default'] ) );


			$image_size = ( ! empty( $width ) && ! empty( $height ) ) ? array( $width, $height ) : 'thumbnail';

			if ( ! empty( $args['value'] ) ) {
				$image_attributes = wp_get_attachment_image_src( $args['value'], $image_size );
				$src              = $image_attributes[0];
				$value            = $args['value'];
			} else {
				$src   = $default_image;
				$value = '';
			}

			$image_style = ! is_array( $image_size ) ? "style='max-width:100%; height:auto;'" : "style='width:{$width}px; height:{$height}px;'";

			$max_width = $max_width . "px";
			// Print HTML field
			echo '
                <div class="upload" style="max-width:' . $max_width . ';">
                    <img data-src="' . $default_image . '" src="' . $src . '" ' . $image_style . '/>
                    <div>
                        <input 
                        type="hidden" 
                        name="' . esc_attr( $this->get_field_name( $args['name'] ) ) . '" 
                        id="' . $this->get_field_id( $args['id'] ) . '" 
                        value="' . $value . '" 
                        class="boospot-media-input"
                        />
                        <button type="submit" class="boospot-image-upload button">' . $text . '</button>
                        <button type="submit" class="boospot-image-remove button">&times;</button>
                    </div>
                </div>
            ';

			$this->get_field_description( $args );

			// free memory
			unset( $default_image, $max_width, $width, $height, $text, $image_size, $image_style, $value );

		}


		/**
		 * Displays a color picker field for a settings field
		 *
		 * @param array $args settings field args
		 */
		function callback_color( $args ) {

			printf(
				'<label for="%s">%s</label>',
				esc_attr( $this->get_field_name( $args['name'] ) ),
				$args['label']
			);
			printf(
				'<input 
                            id="%1$s" 
                            name="%2$s" 
                            class="%3$s wp-color-picker" 
                            value="%4$s" 
                            data-alpha="true" 
                            data-default-color="%5$s" 
                            />',
				esc_attr( $this->get_field_id( $args['id'] ) ),
				esc_attr( $this->get_field_name( $args['name'] ) ),
				sanitize_html_class( $args['class'] ),
				$args['value'],
				$args['default']
			);


		}


		/**
		 * Displays a select box for creating the pages select box
		 *
		 * @param array $args settings field args
		 */
		function callback_pages( $args ) {

			printf(
				'<label for="%s">%s</label><br/>',
				esc_attr( $this->get_field_name( $args['name'] ) ),
				$args['label']
			);
			$dropdown_args = array(
				'selected' => $args['value'],
				'name'     => esc_attr( $this->get_field_name( $args['name'] ) ),
				'id'       => esc_attr( $this->get_field_id( $args['id'] ) ),
				'echo'     => 1,
			);
			wp_dropdown_pages( $dropdown_args );
			echo "<br/>";

		}

		/**
		 * @param $args
		 */
		function callback_posts( $args ) {
			$default_args = array(
				'post_type'      => 'post',
				'posts_per_page' => - 1
			);

			$posts_args = wp_parse_args( $args['options'], $default_args );

			$posts = get_posts( $posts_args );

			$options = array();

			foreach ( $posts as $post ) :
				setup_postdata( $post );
				$options[ $post->ID ] = esc_html( $post->post_title );
				wp_reset_postdata();
			endforeach;

			// free memory
			unset( $posts, $posts_args, $default_args );

			//$args['options'] is required by callback_select()
			$args['options'] = $options;

			$display_as =
				isset( $args['display'] )
				&&
				in_array( $args['display'], array( 'select', 'multicheck' ) )
					? $args['display']
					: 'select';

			if ( is_callable( array( $this, 'callback_' . $display_as ) ) ) {
				call_user_func_array(
					array( $this, 'callback_' . $display_as ),
					array( $args )
				);

//			        $this->callback_select( $args );
			};


		}


		/**
		 * @param $args
		 */
		function callback_taxonomy( $args ) {

			$taxonomy_args = array(
				'taxonomy'   => 'category',
				'hide_empty' => true,
				'save'       => 'slug',
			);

			$taxonomy_args = wp_parse_args( $args['options'], $taxonomy_args );

			$term_property_to_save = sanitize_key( $taxonomy_args['save'] );
			unset( $taxonomy_args['save'] );

			/**
			 * Check:
			 * https://developer.wordpress.org/reference/functions/get_terms/
			 * https://developer.wordpress.org/reference/classes/wp_term_query/__construct/
			 */
			$taxonomy_terms = get_terms( $taxonomy_args );

			$options = array();
			if ( ! empty( $taxonomy_terms ) ) :
				foreach ( $taxonomy_terms as $term ) :
					$properties = get_object_vars( $term );

					$option_key =
						( isset( $properties[ $term_property_to_save ] ) )
							? $properties[ $term_property_to_save ]
							: $properties['term_id'];

					$options[ $option_key ] = esc_html( $term->name );
				endforeach;

			endif;


			//$args['options'] is required by callback_select()
			$args['options'] = $options;

			$display_as =
				isset( $args['display'] )
				&&
				in_array( $args['display'], array( 'select', 'multicheck' ) )
					? $args['display']
					: 'select';

			if ( is_callable( array( $this, 'callback_' . $display_as ) ) ) {
				call_user_func_array(
					array( $this, 'callback_' . $display_as ),
					array( $args )
				);
				// free memory
				unset( $taxonomy_args, $taxonomy_terms, $options, $display_as );
			};


		}


		/**
		 * @param $placeholder
		 *
		 * @return string
		 */
		public function get_markup_placeholder( $placeholder ) {
			return ' placeholder="' . esc_html( $placeholder ) . '" ';
		}

		/**
		 * Get field description for display
		 *
		 * @param array $args settings field args
		 *
		 * @return null|string
		 */
		public function get_field_description( $args ) {
			if ( empty( $args['desc'] ) ) {
				return null;
			}

			return
				sprintf(
					'<small class="description">%s</small>',
					$args['desc']
				);

		}
	}
endif;