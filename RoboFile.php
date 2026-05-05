<?php

include '.tk/RoboFileBase.php';

class RoboFile extends RoboFileBase {

	public function directoriesStructure() {
		return array( 'images', 'includes', 'vendor' );
	}

	public function fileStructure() {
		return array( 'composer.json', 'composer.lock', 'loader.php', 'readme.txt' );
	}

	/**
	 * @return array List of relative paths from the root folder of the plugin
	 */
	public function cleanPhpDirectories() {
		return array( 'vendor' );
	}

	public function pluginMainFile() {
		return 'loader';
	}

	public function pluginFreemiusId() {
		return 9380;
	}

	public function minifyImagesDirectories() {
		return array();
	}

	public function minifyAssetsDirectories() {
		return array();
	}

	/**
	 * @return array Pair list of sass source directory and css target directory
	 */
	public function sassSourceTarget() {
		return array();
	}

	/**
	 * @return string Relative paths from the root folder of the plugin
	 */
	public function sassLibraryDirectory() {
		return array();
	}
}
