<?php

/**
 * Plugin Name: Data-Table
 * Plugin URI:  www.facebook.com
 * Description: This is a demo plugin
 * Version:     1.0.0
 * Author:      Cupid Chakma
 * Author URI:  www.facebook.com
 * Text Domain: data-table
 * Domain Path: /languages
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package     Data-Table
 * @author      Cupid Chakma
 * @copyright   2020
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 *
 * Prefix:      Plugin Functions Prefix
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

class Data_Table {

	/**
	 * Dummy Data
	 *
	 * @var array
	 */
	public $data = array(
		array(
			'id'    => 1,
			'name'  => 'John Doe',
			'email' => 'john@doe.com',
			'age'   => 23,
			'sex'   => 'M',
		),
		array(
			'id'    => 2,
			'name'  => 'Jane Doe',
			'email' => 'jane@doe.com',
			'age'   => 24,
			'sex'   => 'F',
		),
		array(
			'id'    => 3,
			'name'  => 'Jimmy Doe',
			'email' => 'jimmy@doe.com',
			'age'   => 21,
			'sex'   => 'M',
		),
		array(
			'id'    => 4,
			'name'  => 'Jessy Doe',
			'email' => 'jessy@doe.com',
			'age'   => 26,
			'sex'   => 'F',
		),
		array(
			'id'    => 5,
			'name'  => 'Jack Doe',
			'email' => 'jack@doe.com',
			'age'   => 27,
			'sex'   => 'M',
		),
		array(
			'id'    => 6,
			'name'  => 'Jason Doe',
			'email' => 'jason@doe.com',
			'age'   => 28,
			'sex'   => 'M',
		),
		array(
			'id'    => 7,
			'name'  => 'John Wick',
			'email' => 'john@wick.com',
			'age'   => 26,
			'sex'   => 'M',
		),
		array(
			'id'    => 8,
			'name'  => 'Tony Stark',
			'email' => 'tony@stark.com',
			'age'   => 26,
			'sex'   => 'M',
		),
	);

	/**
	 * Load hooks
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'data_table_admin_page' ) );
	}

	/**
	 * Add menu
	 *
	 * @return void
	 */
	public function data_table_admin_page() {
		add_menu_page(
			__( 'Data Table', 'data-table' ),
			__( 'Data Table', 'data-table' ),
			'manage_options',
			'data-table',
			array( $this, 'datatable_display_table' )
		);
	}

	public function datatable_search_by_name( $item ) {
		$names       = strtolower( $item['name'] );
		$search_name = wp_unslash( $_REQUEST['s'] );
		if ( strpos( $names, $search_name ) !== false ) {
			return true;
		}
		return false;
	}

	public function datatable_filter_sex( $item ) {
		$sex = $_REQUEST['filter_s'] ?? 'all';
		if ( 'all' === $sex ) {
			return true;
		} elseif ( $sex == $item['sex'] ) {
			return true;
		}
		return false;
	}

	public function datatable_display_table() {
		include_once 'list-tables.php';
		$orderby = $_REQUEST['orderby'] ?? '';
		$order   = $_REQUEST['order'] ?? '';
		if ( isset( $_REQUEST['s'] ) && ! empty( $_REQUEST['s'] ) ) {
			$search_name = $_REQUEST['s'];
			$this->data  = array_filter( $this->data, array( $this, 'datatable_search_by_name' ) );
		}

		if ( isset( $_REQUEST['filter_s'] ) && ! empty( $_REQUEST['filter_s'] ) ) {
			$this->data = array_filter( $this->data, array( $this, 'datatable_filter_sex' ) );
		}
        
		$table = new List_Table();
		if ( 'age' === $orderby && 'asc' === $order ) {
			sort( $this->data );
		} elseif ( 'age' !== $orderby && 'asc' !== $order ) {
			rsort( $this->data );
		} elseif ( 'name' === $orderby && 'asc' === $order ) {
			sort( $this->data );
		} else {
			rsort( $this->data );
		}
		$table->set_data( $this->data );
		$table->prepare_items();

		?>
			<div class="warp">
			<h2><?php echo _e( 'Persons', 'data-table' ); ?></h2>
				<form method="get" action="">
					<?php
						$table->search_box( 'Seach', 'search_value' );
						$table->display();
					?>
					<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">
				</form>
			</div>
		<?php

	}
}

new Data_Table();
