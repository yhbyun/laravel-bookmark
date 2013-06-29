<?php

class Bookmark extends Eloquent {

	public function tags() {
		return $this->hasMany('Tag');
	}
}
