<?php
/**
 * WPSEO plugin test file.
 *
 * @package WPSEO\Tests\Admin
 */

/**
 * Unit Test Class.
 */
class WPSEO_Admin_Recommended_Replace_Vars_Test extends WPSEO_UnitTestCase {

	/** @var WPSEO_Admin_Recommended_Replace_Vars */
	protected $class_instance;

	/**
	 * Set up the class which will be tested.
	 */
	public function setUp() {
		parent::setUp();

		$this->class_instance = new WPSEO_Admin_Recommended_Replace_Vars();
	}

	/**
	 * Test that determine_for_term can detect a category.
	 *
	 * @covers WPSEO_Admin_Recommended_Replace_Vars::determine_for_term
	 */
	public function test_determine_for_term_with_a_category() {
		$this->assertEquals( 'category', $this->class_instance->determine_for_term( 'category' ) );
	}

	/**
	 * Test that determine_for_term can detect a tag.
	 *
	 * @covers WPSEO_Admin_Recommended_Replace_Vars::determine_for_term
	 */
	public function test_determine_for_term_with_a_tag() {
		$this->assertEquals( 'tag', $this->class_instance->determine_for_term( 'tag' ) );
	}

	/**
	 * Test that determine_for_term can detect a post_format.
	 *
	 * @covers WPSEO_Admin_Recommended_Replace_Vars::determine_for_term
	 */
	public function test_determine_for_term_with_a_post_format() {
		$this->assertEquals( 'post_format', $this->class_instance->determine_for_term( 'post_format' ) );
	}

	/**
	 * Test that determine_for_term can detect a WooCommerce product category.
	 *
	 * @covers WPSEO_Admin_Recommended_Replace_Vars::is_woocommerce_active
	 * @covers WPSEO_Admin_Recommended_Replace_Vars::determine_for_term
	 */
	public function test_determine_for_term_with_a_woocommerce_product_category() {
		$class_instance = $this->mock_is_woocommerce_active( true );

		$this->assertEquals( 'product_cat', $class_instance->determine_for_term( 'product_cat' ) );
	}

	/**
	 * Test that determine_for_term can not detect a WooCommerce product category.
	 *
	 * @covers WPSEO_Admin_Recommended_Replace_Vars::is_woocommerce_active
	 * @covers WPSEO_Admin_Recommended_Replace_Vars::determine_for_term
	 */
	public function test_determine_for_term_with_a_woocommerce_product_category_without_woocommerce() {
		$class_instance = $this->mock_is_woocommerce_active( false );

		$this->assertEquals( 'term-in-custom-taxomomy', $class_instance->determine_for_term( 'product_cat' ) );
	}

	/**
	 * Test that determine_for_term can detect a WooCommerce product tag.
	 *
	 * @covers WPSEO_Admin_Recommended_Replace_Vars::is_woocommerce_active
	 * @covers WPSEO_Admin_Recommended_Replace_Vars::determine_for_term
	 */
	public function test_determine_for_term_with_a_woocommerce_product_tag() {
		$class_instance = $this->mock_is_woocommerce_active( true );

		$this->assertEquals( 'product_tag', $class_instance->determine_for_term( 'product_tag' ) );
	}

	/**
	 * Test that determine_for_term can not detect a WooCommerce product tag.
	 *
	 * @covers WPSEO_Admin_Recommended_Replace_Vars::is_woocommerce_active
	 * @covers WPSEO_Admin_Recommended_Replace_Vars::determine_for_term
	 */
	public function test_determine_for_term_with_a_woocommerce_product_tag_without_woocommerce() {
		$class_instance = $this->mock_is_woocommerce_active( false );

		$this->assertEquals( 'term-in-custom-taxomomy', $class_instance->determine_for_term( 'product_tag' ) );
	}

	/**
	 * Test that determine_for_post defaults to post when no actual post variable is passed along.
	 *
	 * @covers WPSEO_Admin_Recommended_Replace_Vars::determine_for_post
	 */
	public function test_determine_for_post_without_a_wp_post_instance() {
		$this->assertEquals( 'post', $this->class_instance->determine_for_post( null ) );
	}

	/**
	 * Test that a homepage is succesfully determined.
	 *
	 * @covers WPSEO_Admin_Recommended_Replace_Vars::is_homepage
	 * @covers WPSEO_Admin_Recommended_Replace_Vars::determine_for_post
	 */
	public function test_determine_for_post_with_a_homepage() {
		$show_on_front = get_option( 'show_on_front' );
		$page_on_front = get_option( 'page_on_front' );

		$post = $this->create_and_get_with_post_type( 'page' );

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $post->ID );

		$this->assertEquals( 'homepage', $this->class_instance->determine_for_post( $post ) );

		update_option( 'show_on_front', $show_on_front );
		update_option( 'page_on_front', $page_on_front );
	}

	/**
	 * Test that determine_for_post can detect a page.
	 *
	 * @covers WPSEO_Admin_Recommended_Replace_Vars::determine_for_post
	 */
	public function test_determine_for_post_with_a_page() {
		$post = $this->create_and_get_with_post_type( 'page' );

		$this->assertEquals( 'page', $this->class_instance->determine_for_post( $post ) );
	}

	/**
	 * Test that determine_for_post can detect a post.
	 *
	 * @covers WPSEO_Admin_Recommended_Replace_Vars::determine_for_post
	 */
	public function test_determine_for_post_with_a_post() {
		$post = $this->create_and_get_with_post_type( 'post' );

		$this->assertEquals( 'post', $this->class_instance->determine_for_post( $post ) );
	}

	/**
	 * Test that determine_for_post can detect a WooCommerce product.
	 *
	 * @covers WPSEO_Admin_Recommended_Replace_Vars::is_woocommerce_active
	 * @covers WPSEO_Admin_Recommended_Replace_Vars::determine_for_post
	 */
	public function test_determine_for_post_with_a_woocommerce_product() {
		$class_instance = $this->mock_is_woocommerce_active( true );
		$post = $this->create_and_get_with_post_type( 'product' );

		$this->assertEquals( 'product', $class_instance->determine_for_post( $post ) );
	}

	/**
	 * Test that determine_for_post can not detect a WooCommerce product.
	 *
	 * @covers WPSEO_Admin_Recommended_Replace_Vars::is_woocommerce_active
	 * @covers WPSEO_Admin_Recommended_Replace_Vars::determine_for_post
	 */
	public function test_determine_for_post_with_a_woocommerce_product_without_woocommerce() {
		$class_instance = $this->mock_is_woocommerce_active( false );
		$post = $this->create_and_get_with_post_type( 'product' );

		$this->assertEquals( 'custom_post_type', $class_instance->determine_for_post( $post ) );
	}

	/**
	 * Test that determine_for_post can detect a custom post type.
	 *
	 * @covers WPSEO_Admin_Recommended_Replace_Vars::determine_for_post
	 */
	public function test_determine_for_post_with_a_custom_post_type() {
		$post = $this->create_and_get_with_post_type( 'some_plugin_post' );

		$this->assertEquals( 'custom_post_type', $this->class_instance->determine_for_post( $post ) );
	}

	/**
	 * Create and get a mocked WP_Post with a certain post_type.
	 *
	 * @param string $post_type The post type to give to the post.
	 *
	 * @return WP_Post A mocked post with the specified post_type.
	 */
	private function create_and_get_with_post_type( $post_type = 'post' ) {
		return self::factory()->post->create_and_get(
			array(
				'post_type' => $post_type
			)
		);
	}

	/**
	 * Mock helper: WPSEO_Admin_Recommended_Replace_Vars mocked for is_woocommerce_active.
	 *
	 * @param bool $is_active Whether WooCommerce is active or not
	 *
	 * @return WPSEO_Admin_Recommended_Replace_Vars A mocked
	 *                                              WPSEO_Admin_Recommended_Replace_Vars
	 *                                              with the requested state of
	 *                                              is_woocommerce_active
	 */
	private function mock_is_woocommerce_active( $is_active = true ) {
		$class_instance = $this
			->getMockBuilder( 'WPSEO_Admin_Recommended_Replace_Vars' )
			->setMethods( array( 'is_woocommerce_active' ) )
			->getMock();

		$class_instance
			->expects( $this->once() )
			->method( 'is_woocommerce_active' )
			->will( $this->returnValue( $is_active ) );

		return $class_instance;
	}
}
