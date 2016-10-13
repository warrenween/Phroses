<?php

namespace Phroses;

final class Theme extends Template {
	private $root;
	
	public function __construct(string $name) {
		$this->root = INCLUDES["THEMES"]."/".strtolower($name);		
		if(!file_exists($this->root)) throw new \Exception("Theme doesn't exist");
		if(!file_exists("{$this->root}/page.tpl")) throw new \Exception("Theme template doesn't exist");
		
		parent::__construct("{$this->root}/page.tpl");
	}
	
	public function AssetExists(string $asset) : bool {
		return (file_exists("{$this->root}/assets{$asset}"));
	}
	
	public function AssetRead(string $asset) {
		if($this->AssetExists($asset)) readfile("{$this->root}/assets{$asset}");
	}
	
	public function ErrorExists(string $error) : bool {
		return (file_exists("{$this->root}/errors/{$error}.php"));
	}
	
	public function ErrorRead(string $error) {
		if($this->ErrorExists($error)) {
			ob_start();
			include "{$this->root}/errors/{$error}.php";
			$this->title = $title ?? "404 Not Found";
			$this->content = trim(ob_get_clean());
			echo $this;
			ob_end_flush();
		}
	}
}


Theme::$defaultFilters["theme.file"] = function($file) {
	if(file_exists("{$this->root}/{$file}.php")) include "{$this->root}/{$file}.php";
};