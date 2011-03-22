<?php
/**
 * Based on http://www.webforcecart.com/ for updates to this script
 */
class Cart_Library {
	public $total_price;
	public $items = array();
	public $item_prices = array();
	public $item_quantities = array();
	public $item_info = array();

	public function get_cart_data() { 
		$cart_data = array();
		foreach ($this->items as $tmp_item) {
		    $item = array();
			$item['id'] = $tmp_item;
            $item['quantity'] = $this->item_quantities[$tmp_item];
			$item['price'] = $this->item_prices[$tmp_item];
			$item['info'] = $this->item_info[$tmp_item];
			$item['subtotal_price'] = $item['quantity'] * $item['price'];
            $cart_data[] = $item;
		}
		return $cart_data;
	}


	public function add_item($id, $quantity, $price, $info) {
		if (array_key_exists($id, $this->item_quantities) && $this->item_quantities[$id] > 0) { 
			// If the item is already in the cart, just increase the quantity
			$this->item_quantities[$id] = $quantity + $this->item_quantities[$id];
			$this->_update_total_price();
		} else {
			$this->items[] = $id;
			$this->item_quantities[$id] = $quantity;
			$this->item_prices[$id] = $price;
			$this->item_info[$id] = $info;
		}
		$this->_update_total_price();
	} 


	public function edit_item($id, $new_quantity) {
		if ($new_quantity < 1) {
			$this->del_item($id);
		} else {
			$this->item_quantities[$id] = $new_quantity;
		}
		$this->_update_total_price();
	} 


	public function remove_item($id) { 
		$new_items = array();
		$this->item_quantities[$id] = 0;
		foreach ($this->items as $item_id) {
			if ($item_id != $id) {
				$new_items[] = $item_id;
			}
		}
		$this->items = $new_items;
		$this->_update_total_price();
	} 


	public function empty_cart() {
		$this->items = array();
		$this->item_prices = array();
		$this->item_quantities = array();
		$this->item_info = array();
	} 


	private function _update_total_price() {
		$this->total_price = 0;
        if (count($this->items) > 0) {
            foreach ($this->items as $item) {
                $this->total_price = $this->total_price + ($this->item_prices[$item] * $this->item_quantities[$item]);
			}
		}
	} 
	
}

/* End of file: ./system/libraries/cart/cart_library.php */
