# Boo Widgets Helper
This is a helper class to create widgets easily and effectively using WordPress Widgets API

- Sanitization is done automatically.
- Ability to override callback 
- Ability to override sanitize_callback for fields

## Sample Widget Class using Helper Class

```
/**
 * Sample Widget using Helper Class
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author     Rao <rao@booskills.com>
 */
if ( ! class_exists( 'Plugin_Name_My_Widget' ) ) {

	class Plugin_Name_My_Widget extends Boo_Widget_Helper {

		public function __construct() {

			$configuration = array(
			
				// widget id
				'id'     => 'my_awesome_widget',
				
				// widget-name				
				'name'   => __( 'Awesome Widget', 'plugin-name' ), 				
				
				// widget description
				'desc'   => __( 'Widget Using Helper Class', 'plugin-name' ), 	
				
				// widget css class
				'class'  => 'my_awesome_css_class_for_widget', 		

				// widget fields array
				'fields' => $this->get_fields_array() 	
				
			);

			parent::__construct( $configuration );

		}

		/**
		* Fields for widget settings array
		*
		* @return array $fields_array
		*/
		public function get_fields_array() {

			$fields_array = array(
				'title'       => array(
					'label' => __( 'Title:', 'plugin-name' ),
					'desc'  => 'this is title description',
				),
				'limit'       => array(
					'type'  => 'number',
					'label' => __( 'Limit:', 'plugin-name' ),
					'desc'  => 'this is number field description',
				),
				'color1'      => array(
					'label'   => __( 'Color Field', 'plugin-name' ),
					'type'    => 'color',
					'default' => '#ff0000'
				),
				'format'      => array(
					'type'    => 'select',
					'label'   => __( 'Format', 'plugin-name' ),
					'options' => array(
						''          => __( 'All', 'plugin-name' ),
						'hardcover' => __( 'Hardcover', 'plugin-name' ),
						'audio'     => __( 'Audio', 'plugin-name' ),
						'pdf'       => __( 'PDF', 'plugin-name' ),
					),
					'desc'    => 'this is description of select'

				),
				'checkbox1'   => array(
					'type'  => 'checkbox',
					'label' => __( 'Checkbox', 'plugin-name' ),
					'desc'  => 'this is description of checkbox'
				),
				'multicheck1' => array(
					'type'    => 'multicheck',
					'label'   => __( 'Multicheck Options', 'plugin-name' ),
					'options' => array(
						'multi1' => __( 'Multi Choice 1', 'plugin-name' ),
						'multi2' => __( 'Multi Choice no. 2', 'plugin-name' ),
						'multi3' => __( '3rd Multi Choice', 'plugin-name' ),
					),
					'class'   => 'super-class',
					'desc'    => 'this is description of multicheck'
				),
				'radio1'      => array(
					'type'    => 'radio',
					'label'   => __( 'Radio Options', 'plugin-name' ),
					'options' => array(
						'radio1' => __( 'Radio 1', 'plugin-name' ),
						'radio2' => __( 'Radio Choice no. 2', 'plugin-name' ),
						'radio3' => __( '3rd Radio', 'plugin-name' ),
					),
					'class'   => 'super-class',
					'desc'    => 'this is description of radio'
				),
				'textarea1'   => array(
					'type'        => 'textarea',
					'label'       => __( 'Textarea Option', 'plugin-name' ),
					'class'       => 'super-class-textarea',
					'desc'        => 'this is description of textarea',
					'placeholder' => 'placeholder of textarea',
					'rows'        => 2,
					'cols'        => 25,
					'default'     => 'i am default'

				),
				'html1'       => array(
					'type'    => 'html',
					'default' => 'This field is plain html before hr tag<br/><strong>This is Strong</strong> and <em>This is italic</em><hr/>',

				),
				'pages1'      => array(
					'type'  => 'pages',
					'label' => __( 'Pages List', 'plugin-name' ),
					'desc'  => __( 'Pages Options description', 'plugin-name' ),
				),

				'posts1' => array(
					'type'    => 'posts',
					'label'   => __( 'Posts List (Select)', 'plugin-name' ),
					'desc'    => __( 'Posts Options description', 'plugin-name' ),
					'display' => 'select',
					'options' => array(
						'post_type' => 'book'
					)
				),
				'posts2' => array(
					'type'    => 'posts',
					'label'   => __( 'Posts List (Multicheck)', 'plugin-name' ),
					'desc'    => __( 'Posts Options description', 'plugin-name' ),
					'display' => 'multicheck',
					'options' => array(
						'post_type' => 'book'
					)
				),
				
				'taxonomy_field1' => array(
					'type'    => 'taxonomy',
					'label'   => __( 'Taxonomy (select)', 'plugin-name' ),
					'desc'    => __( 'description for Taxonomy Select', 'plugin-name' ),
					'display' => 'select',
					'options' => array(
						'taxonomy' => 'genre',
						'save'     => 'slug', // or term_id or any term object property
					)
				),
				
				'taxonomy_field2' => array(
					'type'    => 'taxonomy',
					'label'   => __( 'Taxonomy (multicheck)', 'plugin-name' ),
					'desc'    => __( 'description for Taxonomy', 'plugin-name' ),
					'display' => 'multicheck',
					'options' => array(
						'taxonomy' => 'genre',
						'save'     => 'slug', // or term_id or any term object property
					)
				),

				'file_field_id' => array(
					'type'    => 'file',
					'label'   => __( 'File Option', 'plugin-name' ),
					'desc'    => __( 'File description', 'plugin-name' ),
					'options' => array(
						'btn' => 'Get it'
					)
				),

				'media_field1'      => array(
					'type'    => 'media',
					'label'   => __( 'Media', 'plugin-name' ),
					'desc'    => __( 'description for Media', 'plugin-name' ),
					'default' => '',
					'options' => array(
						'btn' => 'Get the image',
					)

				),
				'my_field_override' => array(
					'label'             => __( 'Field with Override Callbacks', 'plugin-name' ),
					'callback'          => function ( $args ) {

						printf(
							'<label for="%s">%s</label>',
							$this->get_field_name( 'my_field_override' ),
							$args['label']
						);

						printf( '<input type="text" id="%s" name="%s" value="%s" />',
							$this->get_field_id( 'my_field_override' ),
							$this->get_field_name( 'my_field_override' ),
							$args['value'] // You will get the value in $args param
						);
					},
					'sanitize_callback' => 'sanitize_url'
				),
			);

			return $fields_array;

		}

		/**
		 * Outputs the content of the widget
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget_display( $args, $instance ) {

			// Display our widget output
			echo "<pre>";
			var_export( $instance );
			echo "</pre>";

		}

	}
}

```