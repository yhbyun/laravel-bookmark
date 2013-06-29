<?php

class HomeController extends BaseController {

	public function link($id) {
		$bookmark = Bookmark::find($id);
		$bookmark->hit_cnt = $bookmark->hit_cnt + 1;
		$bookmark->save();

		return Redirect::to($bookmark->url);
	}
}