<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TagController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $sql = "SELECT tag, COUNT(*) AS `count` FROM tags INNER JOIN bookmarks ON bookmarks.id = tags.bookmark_id WHERE user_id = ? GROUP BY tag ORDER BY COUNT(*) DESC, tag ASC";
        $results = DB::select($sql, [Auth::user()->id]);

        return response()->json($results);
    }

    public function autocomplete(Request $request) {
        $sql = "SELECT DISTINCT tag FROM tags INNER JOIN bookmarks ON bookmarks.id = tags.bookmark_id WHERE user_id = ? AND tag LIKE ?";
        $results = DB::select($sql, [Auth::user()->id, $request->input('term') . '%']);

        $tags = [];
        foreach ($results as $result) {
            $tags[] = $result->tag;
        }
        return response()->json($tags);
    }

}
