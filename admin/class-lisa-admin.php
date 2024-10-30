<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://miniup.gl
 * @since      1.0.0
 *
 * @package    Lisa
 * @subpackage Lisa/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Lisa
 * @subpackage Lisa/admin
 * @author     Pierre Minik Lynge <hello@miniup.gl>
 */
class Lisa_Admin {

	private $plugin_name;
	private $version;

	public $conditions_reference = array();
	public $conditions_headings = array();

	public $upper_limit;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->upper_limit = intval( apply_filters( 'lisa_upper_limit', 200 ) );

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		global $post_type;
    if( 'lisa_template' == $post_type )
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/lisa-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		global $post_type;
    if( 'lisa_template' == $post_type ) {
			wp_enqueue_script( 'vue', plugin_dir_url( __FILE__ ) . 'js/vue.min.js', array(), $this->version, false );

			wp_enqueue_script( 'ace', plugin_dir_url( __FILE__ ) . 'js/ace/ace.js', array(), $this->version, false );

	    wp_enqueue_script( 'ace-mode-twig', plugin_dir_url( __FILE__ ) . 'js/ace/mode-twig.js', array( 'ace' ), $this->version, false );

			wp_enqueue_script( 'ace-mode-json', plugin_dir_url( __FILE__ ) . 'js/ace/mode-json.js', array( 'ace' ), $this->version, false );

			wp_enqueue_script( 'ace-ext-langtools', plugin_dir_url( __FILE__ ) . 'js/ace/ext-language_tools.js', array( 'ace' ), $this->version, false );

			wp_enqueue_script( 'ace-theme-monokai', plugin_dir_url( __FILE__ ) . 'js/ace/theme-monokai.js', array( 'ace' ), $this->version, false );

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/lisa-admin.js', array( 'vue', 'ace', 'ace-mode-twig', 'ace-theme-monokai', 'ace-mode-json' ), $this->version, false );
		}

	}

	public function add_metaboxes() {
		add_meta_box(
	    'lisa_template_code-mb',
	    __( 'Template Code', 'lisa' ),
	    array( $this, 'render_code_mb' ),
	    'lisa_template',
	    'advanced',
	    'high'
    );

    add_meta_box(
	    'lisa-template-attributes-mb',
	    __( 'Conditions', 'lisa' ),
	    array( $this, 'render_attributes_mb' ),
	    'lisa_template',
	    'side',
	    'default'
    );

		add_meta_box(
	    'lisa-template-data-mb',
	    __( 'Data', 'lisa' ),
	    array( $this, 'render_data_mb' ),
	    'lisa_template',
	    'advanced',
	    'default'
    );
	}

	public function render_code_mb( $post, $metabox ) {
		wp_nonce_field( 'lisa_template_code_nonce', 'lisa_template_code_nonce_field' );
    $code_template_value = lisa_kses( get_post_meta( $post->ID, '_lisa_template_code', true ) );
    ?>
		<div id="lisa-editor">
    	<div id="lisa_code_editor">{{ editorCode }}</div>
			<input type="hidden" name="lisa_template_code" id="lisa_template_code" v-model="code">
			<p>
				<?php printf( __( 'The above code is rendered with Timber. Please read the <a href="%1$s" target="_blank">Timber documentation</a> for further information.', 'lisa' ), 'https://timber.github.io/docs/' ); ?>
				<?php _e( 'Also no &lt;script&gt; tags are allowed.', 'lisa' ); ?>
			</p>
		</div>
		<script>
		window.lisaEditorCode = `<?php echo $code_template_value; ?>`;
		</script>
    <?php
	}

  public function render_attributes_mb( $post, $metabox ) {
    wp_nonce_field( 'lisa_attribute_nonce', 'lisa_attribute_nonce_field' );

    $available_placements = array(
      array(
        'value' => 'prepend',
        'label' => __( 'Above content', 'lisa' )
      ),
      array(
        'value' => 'append',
        'label' => __( 'Below content', 'lisa' )
      ),
      array(
        'value' => 'replace',
        'label' => __( 'Replace content', 'lisa' )
      )
    );

    $attr_placement_value = esc_js( get_post_meta( $post->ID, '_lisa_attribute_placement', true ) );

		if ( empty( $attr_placement_value ) ) {
			$attr_placement_value = 'replace';
		}
		?>
		<div id="lisa-conditions">
			<p class="lisa_attribute_placement_wrapper">
				<label for="lisa_attribute_placement" class="lisa_attribute_placement_label"><?php _e( 'Placement', 'lisa' ); ?></label>
			</p>
			<select name="lisa_attribute_placement" id="lisa_attribute_placement" v-model="placement">
				<?php foreach( $available_placements as $placement ) : ?>
					<option value="<?php echo esc_attr( $placement['value'] ); ?>">
							<?php echo esc_attr( $placement['label'] ); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<p class="lisa_attribute_autoload_wrapper">
	      <label class="lisa_attribute_autoload_label"><input type="checkbox" disabled><?php _e( 'Autoload', 'lisa' ); ?></label>
	    </p>
			<p>
				Autoload requires <b>Lisa Templates Pro</b>.
			</p>
			<div class="lisa_shortcode">
				<p>Use this shortcode where you want this template: <b>[lisa_template id=&quot;<?php echo $post->ID; ?>&quot;][/lisa_template]</b></p>
			</div>
		</div>
		<script>
			window.lisaConditions = {
				placement: '<?php echo $attr_placement_value; ?>'
			};
		</script>
    <?php
  }

	public function render_data_mb( $post, $metabox ) {
    wp_nonce_field( 'lisa_data_nonce', 'lisa_data_nonce_field' );

		$data_sources = (array) apply_filters( 'lisa_data_sources', array(
			'single'	=> __( 'Single', 'lisa' ),
			'query'		=> __( 'Query', 'lisa' )
		) );

    $data_source_value = esc_js( get_post_meta( $post->ID, '_lisa_data_source', true ) );

		if( empty( $data_source_value ) ) {
			$data_source_value = 'single';
		}

		$data_query_value = json_encode( get_post_meta( $post->ID, '_lisa_data_query', true ), JSON_PRETTY_PRINT );

		if ( $data_query_value == '""' ) {
			$data_query_value = json_encode(
				array(
					'post_type'              => array( 'post' ),
					'post_status'            => array( 'publish' ),
					'posts_per_page'         => '10',
					'order'                  => 'DESC',
					'orderby'                => 'date',
				),
				JSON_PRETTY_PRINT
			);
		}
		?>
		<div id="lisa-data">
			<p class="lisa_data_sources_wrapper">
				<label for="lisa_data_sources" class="lisa_data_sources_label"><?php _e( 'Source', 'lisa' ); ?></label>
			</p>
			<select name="lisa_data_sources" id="lisa_data_sources" v-model="source">
				<?php foreach( $data_sources as $key => $source ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>">
							<?php echo esc_attr( $source ); ?>
					</option>
				<?php endforeach; ?>
			</select>
			<p v-if="source === 'single'"><?php _e( 'This exposes a <b>post</b> object referencing the current post object in WordPress.', 'lisa' ); ?></p>
			<div id="lisa_query" v-show="source === 'query'">
				<p><?php _e( 'This exposes a <b>posts</b> array in your code referencing the post objects as a result from the query below.', 'lisa' ); ?></p>
				<p class="lisa_data_query_wrapper">
					<label for="lisa_data_query" class="lisa_data_query_label"><?php _e( 'Query (in JSON)', 'lisa' ); ?></label>
				</p>
				<div id="lisa_query_editor">{{ query }}</div>
				<input type="hidden" name="lisa_template_query" id="lisa_template_query" v-model="query">
			</div>
		</div>
		<script>
			window.lisaData = {
				source: '<?php echo $data_source_value; ?>',
				query: `<?php echo $data_query_value; ?>`
			};
		</script>
    <?php
  }

  public function save_data( $post_id, $post, $update ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
      return $post_id;
    }

    if ( ! isset( $_POST['post_type'] ) || 'lisa_template' !== $_POST['post_type'] ) {
      return $post_id;
    }

		$capabilities = apply_filters( 'lisa_capabilites', 'switch_themes' );

    if ( ! current_user_can( $capabilities ) ) {
      return $post_id;
    }

    if ( isset( $_POST['lisa_attribute_nonce_field'] ) && wp_verify_nonce( $_POST['lisa_attribute_nonce_field'], 'lisa_attribute_nonce' ) ) {

			if ( isset( $_POST['lisa_attribute_placement'] ) ) {
				update_post_meta( $post_id, '_lisa_attribute_placement', sanitize_text_field( $_POST['lisa_attribute_placement'] ) );
			}

    }

    if ( isset( $_POST['lisa_template_code_nonce_field'] ) && wp_verify_nonce( $_POST['lisa_template_code_nonce_field'], 'lisa_template_code_nonce' ) ) {
      update_post_meta( $post_id, '_lisa_template_code',  lisa_kses( $_POST['lisa_template_code'] ) );
    }

		if ( isset( $_POST['lisa_data_nonce_field'] ) && wp_verify_nonce( $_POST['lisa_data_nonce_field'], 'lisa_data_nonce' ) ) {
      update_post_meta( $post_id, '_lisa_data_source',  sanitize_text_field( $_POST['lisa_data_sources'] ) );

			update_post_meta( $post_id, '_lisa_data_query', (array) json_decode( stripslashes( $_POST['lisa_template_query'] ) ) );
    }

  }

}
