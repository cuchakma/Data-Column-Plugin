<?php

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class List_Table extends WP_List_Table {

	private $temp_items;

	function __construct( $args = array() ) {
		parent::__construct( $args );
	}

	/**
	 * Data to be initialized or set
	 *
	 * @param array $data
	 * @return void
	 */
	public function set_data( $data ) {
		$this->temp_items = $data;
	}

	/**
	 * Define columns or show columns on the front-end
	 *
	 * @return void
	 */
	public function get_columns() {
		return array(
			'cb'    => '<input type="checkbox">',
			'name'  => __( 'Name', 'data-table' ),
			'sex'   => __( 'Gender', 'data-table' ),
			'email' => __( 'Email', 'data-table' ),
			'age'   => __( 'Age', 'data-table' ),

		);
	}

	/**
	 * Sortable columns
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		return array(
			'age'  => array( 'age', true ),
			'name' => array( 'name', true ),
		);
	}

	/**
	 * Added checkbox to a column
	 *
	 * @param array $item returns items.
	 * @return string
	 */
	public function column_cb( $item ) {
		return "<input type='checkbox' value = '{$item['id']}' />";
	}

	/**
	 * Adding strong text email column
	 *
	 * @param  array $item
	 * @return string
	 */
	public function column_email( $item ) {
		return "<strong>{$item['email']}</strong>";
	}

	public function column_age( $item ) {
		return "<em>{$item['age']}</em>";
	}

	public function extra_tablenav( $which ) {
		if ( 'top' === $which ) :
			?>
			<div class="actions align-left">
				<select name="filter_s" id="filter_s">
					<option value="all">All</option>
					<option value="M">Males</option>
					<option value="F">Females</option>
				</select>
				<?php submit_button( __( 'Filter', 'data-table' ), 'primary', 'submit', false ); ?>
			</div>
			<?php
		endif;
	}

	/**
	 * Prepare items loads/renders the columns graphically set in get_columns method
	 *
	 * @return void
	 */
	public function prepare_items() {
		$paged                 = $_REQUEST['paged'] ?? 1;
		$per_page              = 4;
		$total_items           = count( $this->temp_items );
		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );
		$data_chunks           = array_chunk( $this->temp_items, $per_page );
		$this->items           = $data_chunks[ $paged - 1 ];
		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
				'total_pages' => ceil( count( $this->temp_items ) / $per_page ),
			)
		);
	}

	function column_default( $item, $column_name ) {
		return $item[ $column_name ];
	}
}
