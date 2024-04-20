<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Unicamp_WP_Widget_Featured_Group_Activities' ) ) {
	class Unicamp_WP_Widget_Featured_Group_Activities extends Unicamp_WP_Widget_Base {

		public function __construct() {
			$this->widget_id          = 'unicamp-wp-widget-featured-group-activities';
			$this->widget_cssclass    = 'unicamp-wp-widget-featured-group-activities';
			$this->widget_name        = esc_html__( '[Unicamp] BP Featured Group Activities', 'unicamp' );
			$this->widget_description = esc_html__( 'Get last activities of chosen group.', 'unicamp' );
			$this->settings           = array(
				'title'                  => array(
					'type'  => 'text',
					'std'   => '',
					'label' => esc_html__( 'Title', 'unicamp' ),
				),
				'group_id'               => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Select Group', 'unicamp' ),
					'options' => [],
					'std'     => '',
				),
				'show_group_name'        => array(
					'type'  => 'checkbox',
					'std'   => 1,
					'label' => esc_html__( 'Show Group Name', 'unicamp' ),
				),
				'show_group_description' => array(
					'type'  => 'checkbox',
					'std'   => 1,
					'label' => esc_html__( 'Show Group Description', 'unicamp' ),
				),
				'number_activities'      => array(
					'type'  => 'number',
					'step'  => 1,
					'min'   => 1,
					'max'   => 40,
					'std'   => 3,
					'label' => esc_html__( 'Number activities', 'unicamp' ),
				),
			);

			parent::__construct();
		}

		public function set_form_settings() {
			$filter_by_options = array();

			if ( bp_has_groups() ) :
				while ( bp_groups() ) : bp_the_group();
					$filter_by_options[ bp_get_group_id() ] = bp_get_group_name(); // XSS OK.
				endwhile;
			endif;

			$this->settings['group_id']['options'] = $filter_by_options;
		}

		public function widget( $args, $instance ) {
			$group_id               = $this->get_value( $instance, 'group_id' );
			$number_activities      = intval( $this->get_value( $instance, 'number_activities' ) );
			$show_group_name        = $this->get_value( $instance, 'show_group_name' );
			$show_group_description = $this->get_value( $instance, 'show_group_description' );

			if ( '' === $group_id ) {
				return;
			}

			$group_id = intval( $group_id );

			$this->widget_start( $args, $instance );

			$query_string = "object=groups&max={$number_activities}&primary_id={$group_id}";

			if ( bp_has_activities( $query_string ) ) :
				?>
				<div class="featured-group-activity-section">
					<?php
					/**
					 * @var BP_Groups_Group $group
					 */
					$group = groups_get_group( $group_id );
					?>
					<?php if ( $show_group_name ) : ?>
						<h3 class="featured-group-activity-heading"><?php echo esc_html( $group->name ); ?></h3>
					<?php endif; ?>
					<?php if ( $show_group_description ) : ?>
						<p class="featured-group-activity-description"><?php echo esc_html( $group->description ); ?></p>
					<?php endif; ?>
					<ul class="featured-group-activity-list">
						<?php while ( bp_activities() ) : bp_the_activity(); ?>
							<?php bp_get_template_part( 'activity/custom/entry' ); ?>
						<?php endwhile; ?>
					</ul>
				</div>
			<?php
			endif;
			$this->widget_end( $args );
		}
	}
}
