<?php
/**
 * Post type facet type
 *
 * @package ES Admin
 */

namespace ES_Admin\Facets;
use \ES_Admin\DSL as DSL;

/**
 * Post type facet type
 */
class Post_Type extends Facet_Type {
	/**
	 * The query var this facet should use.
	 *
	 * @var string
	 */
	protected $query_var = 'post_type';

	/**
	 * Build the facet type object.
	 *
	 * @see Facet_Type::__construct().
	 */
	public function __construct( $args = [] ) {
		$args = wp_parse_args( $args, [
			'key' => 'post_type',
			'title' => __( 'Content Type', 'es-admin' ),
		] );
		parent::__construct( $args );
	}

	/**
	 * Build the facet request.
	 *
	 * @return array
	 */
	public function request_dsl() {
		return [
			'terms' => [
				'field' => $this->es->map_field( 'post_type' ),
			],
		];
	}

	/**
	 * Customize the bucket label for this facet type.
	 *
	 * @param  string $label  Bucket label.
	 * @param  array  $bucket Bucket from ES.
	 * @return string
	 */
	public function bucket_label( $bucket ) {
		$post_type_obj = get_post_type_object( $bucket['key'] );
		if ( ! empty( $post_type_obj->labels->name ) ) {
			return $post_type_obj->labels->name;
		}
		return $bucket['key'];
	}

	/**
	 * Get the request filter DSL clause.
	 *
	 * @param  array $values Values to pass to filter.
	 * @return array
	 */
	public function filter( $values ) {
		$field = $this->es->map_field( 'post_type' );
		if ( 'and' === $this->logic() ) {
			return DSL::all_terms( $field, $values );
		} else {
			return DSL::terms( $field, $values );
		}
	}
}
