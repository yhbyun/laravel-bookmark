<?php

class TagController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		$sql = "SELECT tag, COUNT(*) AS `count` FROM tags INNER JOIN bookmarks ON bookmarks.id = tags.bookmark_id WHERE user_id = ? GROUP BY tag ORDER BY COUNT(*) DESC, tag ASC";
		$results = DB::select($sql, array(Auth::user()->id));

		return Response::json($results);
	}

	public function autocomplete() {
		$sql = "SELECT DISTINCT tag FROM tags INNER JOIN bookmarks ON bookmarks.id = tags.bookmark_id WHERE user_id = ? AND tag LIKE ?";
		$results = DB::select($sql, array(Auth::user()->id, Input::get('term') . '%'));

		$tags = array();
		foreach ($results as $result) {
			$tags[] = $result->tag;
		}
		return Response::json($tags);
	}

}