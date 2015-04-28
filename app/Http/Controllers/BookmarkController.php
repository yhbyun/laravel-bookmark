<?php namespace App\Http\Controllers;

use App\Bookmark;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookmarkController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {

        if ($request->input('tag')) {
            $params = [Auth::user()->id, $request->input('tag')];
            $sql = "SELECT id, url, title, description, pin, public, hit_cnt, created_at, GROUP_CONCAT(DISTINCT tag) AS tags FROM bookmarks LEFT OUTER JOIN tags ON bookmarks.id = tags.bookmark_id WHERE user_id = ? AND id IN (SELECT bookmark_id FROM tags WHERE tag = ?) GROUP BY id ORDER BY pin DESC, created_at DESC";
        } elseif ($request->input('search')) {
            $params = [Auth::user()->id, '%' . $request->input('search') . '%',
                '%' . $request->input('search') . '%', '%' . $request->input('search') . '%'];
            $sql = "SELECT id, url, title, description, pin, public, hit_cnt, created_at, GROUP_CONCAT(DISTINCT tag) AS tags FROM bookmarks LEFT OUTER JOIN tags ON bookmarks.id = tags.bookmark_id WHERE user_id = ? AND ((title LIKE ? OR description LIKE ?) OR id IN (SELECT bookmark_id FROM tags WHERE tag LIKE ?))GROUP BY id ORDER BY pin DESC, created_at DESC";
        } elseif ($request->input('public')) {
            $params = [];
            $offset = 0;
            $sql = "SELECT id, url, title, description, pin, public, hit_cnt, created_at, GROUP_CONCAT(DISTINCT tag) AS tags FROM bookmarks LEFT OUTER JOIN tags ON bookmarks.id = tags.bookmark_id WHERE public = 1 GROUP BY id ORDER BY created_at DESC LIMIT " . $offset . ",50";
        } else {
            $params = [Auth::user()->id];
            $offset = 0;
            if ($request->input('offset'))
                $offset = $request->input('offset');
            $sql = "SELECT id, url, title, description, pin, public, hit_cnt, created_at, GROUP_CONCAT(DISTINCT tag) AS tags FROM bookmarks LEFT OUTER JOIN tags ON bookmarks.id = tags.bookmark_id WHERE user_id = ? GROUP BY id ORDER BY pin DESC, created_at DESC LIMIT " . $offset . ",50";
        }

        $results = DB::select($sql, $params);
        foreach ($results as &$result) {
            if ($result->tags && str_contains($result->tags, ',')) {
                $result->tags = explode(',', $result->tags);
            } elseif ($result->tags) {
                $result->tags = [$result->tags];
            } else {
                $result->tags = [];
            }
        }

        return response()->json($results);
    }

    public function index_public()
    {

        $params = [];
        $offset = 0;
        $sql = "SELECT id, url, title, description, pin, public, hit_cnt, GROUP_CONCAT(DISTINCT tag) AS tags FROM bookmarks LEFT OUTER JOIN tags ON bookmarks.id = tags.bookmark_id WHERE public = 1 GROUP BY id ORDER BY created_at DESC LIMIT " . $offset . ",50";

        $results = DB::select($sql, $params);
        foreach ($results as &$result) {
            if ($result->tags && str_contains($result->tags, ',')) {
                $result->tags = explode(',', $result->tags);
            } elseif ($result->tags) {
                $result->tags = [$result->tags];
            } else {
                $result->tags = [];
            }
        }

        return response()->json($results);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $bookmark = new Bookmark;
        $bookmark->user_id = Auth::user()->id;
        $bookmark->url = $request->input('url');
        $bookmark->title = $request->input('title');
        $bookmark->description = $request->input('description');
        $bookmark->public = $request->input('public');
        if ($request->input('pin'))
            $bookmark->pin = $request->input('pin');
        $bookmark->save();

        foreach ($request->input('tags') as $keyword) {
            $tag = new Tag(['tag' => $keyword]);
            $bookmark->tags()->save($tag);
            //var_dump(DB::getQueryLog());
        }

        return response()->json($bookmark);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $bookmark = Bookmark::findOrFail($id);
        $bookmark->url = $request->input('url');
        $bookmark->title = $request->input('title');
        $bookmark->description = $request->input('description');
        $bookmark->public = $request->input('public');
        $bookmark->pin = $request->input('pin');
        $bookmark->save();

        Tag::where('bookmark_id', $id)->delete();

        foreach ($request->input('tags') as $keyword) {
            $tag = new Tag(['tag' => $keyword]);
            $bookmark->tags()->save($tag);
        }

        return response()->json($bookmark);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        Bookmark::where('id', $id)->where('user_id', Auth::user()->id)->delete();
        Tag::where('bookmark_id', $id)->delete();
    }
}
