<?php 

function presentPrice($price) {
	return '$' . $price;
}

function productImgPath($slug) {
	return asset('/img/' . $slug);
}