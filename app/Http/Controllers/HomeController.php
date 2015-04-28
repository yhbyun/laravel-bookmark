<?php namespace App\Http\Controllers;

use App\Bookmark;

class HomeController extends Controller
{

    public function link($id)
    {
        $bookmark = Bookmark::findOrFail($id);
        $bookmark->hit_cnt = $bookmark->hit_cnt + 1;
        $bookmark->save();

        return redirect($bookmark->url);
    }
}
