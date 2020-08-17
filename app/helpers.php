<?php 

function presentPrice($price) {
	if (! is_string($price)) {
    	$price = number_format($price, 2, '.', ',');
    }

	return '$' . $price;
}
