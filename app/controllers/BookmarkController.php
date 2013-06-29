<?php

class BookmarkController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {

		if (Input::get('tag')) {
			$params = array(Auth::user()->id, Input::get('tag'));
			$sql = "SELECT id, url, title, description, pin, public, hit_cnt, UNIX_TIMESTAMP(created_at) AS `timestamp`, GROUP_CONCAT(DISTINCT tag ORDER BY tag ASC SEPARATOR ',') AS tags FROM bookmarks LEFT OUTER JOIN tags ON bookmarks.id = tags.bookmark_id WHERE user_id = ? AND id IN (SELECT bookmark_id FROM tags WHERE tag = ?) GROUP BY id ORDER BY pin DESC, created_at DESC";
		} elseif (Input::get('search')) {
			$params = array(Auth::user()->id, '%' . Input::get('search') . '%',
				'%' . Input::get('search') . '%', '%' . Input::get('search') . '%');
			$sql = "SELECT id, url, title, description, pin, public, hit_cnt, UNIX_TIMESTAMP(created_at) AS `timestamp`, GROUP_CONCAT(DISTINCT tag ORDER BY tag ASC SEPARATOR ',') AS tags FROM bookmarks LEFT OUTER JOIN tags ON bookmarks.id = tags.bookmark_id WHERE user_id = ? AND ((title LIKE ? OR description LIKE ?) OR id IN (SELECT bookmark_id FROM tags WHERE tag LIKE ?))GROUP BY id ORDER BY pin DESC, created_at DESC";
		} elseif (Input::get('public')) {
			$params = array();
			$offset = 0;
			$sql = "SELECT id, url, title, description, pin, public, hit_cnt, UNIX_TIMESTAMP(created_at) AS `timestamp`, GROUP_CONCAT(DISTINCT tag ORDER BY tag ASC SEPARATOR ',') AS tags FROM bookmarks LEFT OUTER JOIN tags ON bookmarks.id = tags.bookmark_id WHERE public = 1 GROUP BY id ORDER BY created_at DESC LIMIT " . $offset . ",50";
		} else {
			$params = array(Auth::user()->id);
			$offset = 0;
			if (Input::get('offset')) $offset = Input::get('offset');
			$sql = "SELECT id, url, title, description, pin, public, hit_cnt, UNIX_TIMESTAMP(created_at) AS `timestamp`, GROUP_CONCAT(DISTINCT tag ORDER BY tag ASC SEPARATOR ',') AS tags FROM bookmarks LEFT OUTER JOIN tags ON bookmarks.id = tags.bookmark_id WHERE user_id = ? GROUP BY id ORDER BY pin DESC, created_at DESC LIMIT " . $offset . ",50";
		}

		$results = DB::select($sql, $params);
		foreach($results as &$result) {
			if ($result->tags && str_contains($result->tags, ',')) {
				$result->tags = explode(',', $result->tags);
			} elseif ($result->tags) {
				$result->tags = array($result->tags);
			} else {
				$result->tags = array();
			}
		}

		return Response::json($results);
	}

	public function index_public() {

		$params = array();
		$offset = 0;
		$sql = "SELECT id, url, title, description, pin, public, hit_cnt, UNIX_TIMESTAMP(created_at) AS `timestamp`, GROUP_CONCAT(DISTINCT tag ORDER BY tag ASC SEPARATOR ',') AS tags FROM bookmarks LEFT OUTER JOIN tags ON bookmarks.id = tags.bookmark_id WHERE public = 1 GROUP BY id ORDER BY created_at DESC LIMIT " . $offset . ",50";

		$results = DB::select($sql, $params);
		foreach($results as &$result) {
			if ($result->tags && str_contains($result->tags, ',')) {
				$result->tags = explode(',', $result->tags);
			} elseif ($result->tags) {
				$result->tags = array($result->tags);
			} else {
				$result->tags = array();
			}
		}

		return Response::json($results);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store() {
		$bookmark = new Bookmark;
		$bookmark->user_id = Auth::user()->id;
		$bookmark->url = Input::get('url');
		$bookmark->title = Input::get('title');
		$bookmark->description = Input::get('description');
		$bookmark->public = Input::get('public');
		if (Input::get('pin')) $bookmark->pin = Input::get('pin');
		$bookmark->save();

		foreach(Input::get('tags') as $keyword) {
			$tag = new Tag(array('tag' => $keyword));
			$bookmark->tags()->save($tag);
			//var_dump(DB::getQueryLog());
		}

		return Response::json($bookmark);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id) {
		$bookmark = Bookmark::find($id);
		$bookmark->url = Input::get('url');
		$bookmark->title = Input::get('title');
		$bookmark->description = Input::get('description');
		$bookmark->public = Input::get('public');
		$bookmark->pin = Input::get('pin');
		$bookmark->save();

		Tag::where('bookmark_id', $id)->delete();

		foreach(Input::get('tags') as $keyword) {
			$tag = new Tag(array('tag' => $keyword));
			$bookmark->tags()->save($tag);
		}

		return Response::json($bookmark);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id) {
		Bookmark::where('id', $id)->where('user_id', Auth::user()->id)->delete();
		Tag::where('bookmark_id', $id)->delete();
	}

}