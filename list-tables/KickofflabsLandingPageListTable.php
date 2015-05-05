<?php

class KickofflabsLandingPageListTable extends WP_List_Table
{
	private $kickofflabsLandingPages = null;
	
	public function __construct( $args = array(), $kickofflabsLandingPages )
	{
		$this->kickofflabsLandingPages = $kickofflabsLandingPages;
		$this->save();
		
		parent::__construct( array(
			'singular' => 'Landing Page',
			'plural' => 'Landing Pages'
		) );
	}

	/** ************************************************************************
	 * Recommended. This method is called when the parent class can't find a method
	 * specifically build for a given column. Generally, it's recommended to include
	 * one method for each column you want to render, keeping your package class
	 * neat and organized. For example, if the class needs to process a column
	 * named 'title', it would first see if a method named $this->column_title()
	 * exists - if it does, that method will be used. If it doesn't, this one will
	 * be used. Generally, you should try to use custom column methods as much as
	 * possible.
	 *
	 * Since we have defined a column_title() method later on, this method doesn't
	 * need to concern itself with any column with a name of 'title'. Instead, it
	 * needs to handle everything else.
	 *
	 * For more detailed insight into how columns are handled, take a look at
	 * WP_List_Table::single_row_columns()
	 *
	 * @param array $item A singular item (one full row's worth of data)
	 * @param array $column_name The name/slug of the column to be processed
	 * @return string Text or HTML to be placed inside the column <td>
	 **************************************************************************/
	public function column_default( $item, $columnName )
	{
		switch($columnName){
			case 'path': {
				return $item[ $columnName ];
				break;
			}
			default: {
			return print_r($item, true);
			}
		}
	}

	public function column_title( $item )
	{
		$action = array(
			'view' => sprintf( '<a href="%s">View Live</a>', get_permalink( $item[ 'wordpress_page_id' ] ) ),
			'edit' => sprintf( '<a target="_blank" href="https://app.kickofflabs.com/dashboard/campaigns/%s/landing_pages/%s">Edit in KickoffLabs</a>',$item[ 'list_id' ], $item[ 'page_id' ] ),
			'refresh' => sprintf( '<a href="?page=%s&action=refresh&hash=%s">Refresh Page from KickoffLabs</a>', $_REQUEST[ 'page' ], $item[ 'hash' ] ),
			'delete' => sprintf( '<a href="?page=%s&action=delete&hash=%s">Delete from Wordpress</a>', $_REQUEST[ 'page' ], $item[ 'hash' ] )
			
		);
		return sprintf( '%1$s <span style="color:silver;">(id:%2$s)</span>%3$s',
			$item[ 'title' ],
			$item[ 'page_id' ],
			$this->row_actions( $action )
		);
	}

	/** ************************************************************************
	 * REQUIRED if displaying checkboxes or using bulk actions! The 'cb' column
	 * is given special treatment when columns are processed. It ALWAYS needs to
	 * have it's own method.
	 *
	 * @see WP_List_Table::::single_row_columns()
	 * @param array $item A singular item (one full row's worth of data)
	 * @return string Text to be placed inside the column <td> (movie title only)
	 **************************************************************************/
	public function column_cb( $item )
	{
		return sprintf( '<input type="checkbox" name="kickofflabs_landing_page_hash" value="%1$s" />', $item[ 'hash' ] );
	}

	/** ************************************************************************
	 * REQUIRED! This method dictates the table's columns and titles. This should
	 * return an array where the key is the column slug (and class) and the value
	 * is the column's title text. If you need a checkbox for bulk actions, refer
	 * to the $columns array below.
	 *
	 * The 'cb' column is treated differently than the rest. If including a checkbox
	 * column in your table you must create a column_cb() method. If you don't need
	 * bulk actions or checkboxes, simply leave the 'cb' entry out of your array.
	 *
	 * @see WP_List_Table::::single_row_columns()
	 * @return array An associative array containing column information: 'slugs'=>'Visible Titles'
	 **************************************************************************/
	public function get_columns(){
		$columns = array(
			'title' => 'Title',
			'path' => 'Path'
		);
		return $columns;
	}

	public function display_tablenav( $which ) {
		if ( 'top' == $which )
			wp_nonce_field( 'bulk-' . $this->_args['plural'] );
		?>
		<div class="tablenav <?php echo esc_attr( $which ); ?>">
			<?php
			$this->extra_tablenav( $which );
			$this->pagination( $which );
			?>

			<br class="clear" />
		</div>
	<?php
	}

	public function extra_tablenav( $which ) {
		if ( 'top' == $which ) {
			
			require KICKOFFLABS_TEMPLATES . 'admin/landingpage-add.php';
		}
	}

	/** ************************************************************************
	 * REQUIRED! This is where you prepare your data for display. This method will
	 * usually be used to query the database, sort and filter the data, and generally
	 * get it ready to be displayed. At a minimum, we should set $this->items and
	 * $this->set_pagination_args(), although the following properties and methods
	 * are frequently interacted with here...
	 *
	 * @uses $this->_column_headers
	 * @uses $this->items
	 * @uses $this->get_columns()
	 * @uses $this->get_sortable_columns()
	 * @uses $this->get_pagenum()
	 * @uses $this->set_pagination_args()
	 **************************************************************************/
	public function prepare_items() {
		/**
		 * First, lets decide how many records per page to show
		 */
		$per_page = 10;

		/**
		 * REQUIRED. Now we need to define our column headers. This includes a complete
		 * array of columns to be displayed (slugs & titles), a list of columns
		 * to keep hidden, and a list of columns that are sortable. Each of these
		 * can be defined in another method (as we've done here) before being
		 * used to build the value for our _column_headers property.
		 */
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();


		/**
		 * REQUIRED. Finally, we build an array to be used by the class for column
		 * headers. The $this->_column_headers property takes an array which contains
		 * 3 other arrays. One for all columns, one for hidden columns, and one
		 * for sortable columns.
		 */
		$this->_column_headers = array($columns, $hidden, $sortable);

		/**
		 * REQUIRED for pagination. Let's figure out what page the user is currently
		 * looking at. We'll need this later, so you should always include it in
		 * your own package classes.
		 */
		$current_page = $this->get_pagenum();

		// Retrieve our data
		$storedLandingPages = get_option( 'kickofflabs_landing_pages', array() );

		/**
		 * REQUIRED for pagination. Let's check how many items are in our data array.
		 * In real-world use, this would be the total number of items in your database,
		 * without filtering. We'll need this later, so you should always include it
		 * in your own package classes.
		 */
		$total_items = count($storedLandingPages);

		/**
		 * The WP_List_Table class does not handle pagination for us, so we need
		 * to ensure that the data is trimmed to only the current page. We can use
		 * array_slice() to
		 */
		$landingPages = array_slice($storedLandingPages,(($current_page-1)*$per_page),$per_page);

		// Add additional data to each item
		foreach( $landingPages AS $id => $landingPage ) {
			// Add the $id as the 'hash' column
			$landingPages[ $id ][ 'hash' ] = $id;

			$foundPage = $this->kickofflabsLandingPages->findPageId( $landingPage[ 'page_id' ] );
			if( is_null( $foundPage ) ){
				// Add in default columns
				$landingPages[ $id ][ 'title' ] = '';
			} else {
				$landingPages[ $id ][ 'title' ] = $foundPage->page_title;
			}
		}

		/**
		 * REQUIRED. Now we can add our *sorted* data to the items property, where
		 * it can be used by the rest of the class.
		 */
		$this->items = $landingPages;

		/**
		 * REQUIRED. We also have to register our pagination options & calculations.
		 */
		$this->set_pagination_args( array(
			'total_items' => $total_items,                  //WE have to calculate the total number of items
			'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
			'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
		) );
	}

}
