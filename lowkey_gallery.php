<?php  
/* 
Plugin Name: Low-Key Gallery
Plugin URI: http://noahjobsgray.com
Version: 0.1 
Author: Noah Gray
Description: A simple replacement gallery with no fancy stuff.
*/ 

add_shortcode('gallery', 'lk_gallery_func');
add_action('wp_enqueue_scripts', 'lk_add_css');

function lk_add_css() {
	$lk_css = plugins_url('lk-style.css', __FILE__);
	$lk_js = plugins_url('lowkey.js', __FILE__);

	wp_enqueue_style('lk-css', $lk_css);
	wp_enqueue_script('lk_js', $lk_js, array(), '0.1.0', true);
}

function lk_gallery_func( $atts ) {
	$shortcodeAttribute = shortcode_atts( array('ids' => 0), $atts, 'gallery');
	$shortcodeAttributeString = implode($shortcodeAttribute);
	$attachmentIds = split(",", $shortcodeAttributeString);

	$lk_gallery = new Lowkey($attachmentIds);

	$lk_css = plugins_url() . "/lowkey_gallery/style.css";
	echo "<style>" . $lk_css .  "</style>";


	echo $lk_gallery->display();
}

class Lowkey {

	public $attachmentUrlsFull;
	public $attachmentUrlsThumb;
	public $headerUrl;
	public $count;

	function __construct($attachmentIds) {
		$this->attachmentUrlsFull =  $this->getAllUrls($attachmentIds, 'full');
		$this->attachmentUrlsThumb =  $this->getAllUrls($attachmentIds, 'thumbnail');
		$this->headerUrl = $this->attachmentUrlsFull[0][0];
		$this->count = count($attachmentIds);
	}

	function getAllUrls($attachmentIds, $size) {
		$allUrls = Array();
		foreach($attachmentIds as $id) {
			$url = wp_get_attachment_image_src($id, $size);
			array_push($allUrls, $url);
		} 
		return $allUrls;
	}
	
	function showHeader() {
		echo "<div class='lk-canvas'><span class='lk-helper'></span>";
		echo "<img id='lk-bigscreen' class='reset-this' src='" . $this->headerUrl . "' />";
		echo "</div>";
	}

	function showThumbnail($url) {
		echo "<img class='lk-thumbnail' src='" . $url . "' />";
	}

	function showThumbnails() {
		if ($this->count > 1) {
			echo "<div class='lk-row'>";
			foreach ($this->attachmentUrlsThumb as $thumbUrl) {
				$this->showThumbnail($thumbUrl[0]);
			}
			echo "</div>";
		}
	}

	function display() {
		$this->showHeader();
		$this->showThumbnails();
	}
}