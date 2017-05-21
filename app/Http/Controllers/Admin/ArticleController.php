<?php

namespace App\Http\Controllers\Admin;

use App\Http\Model\Article;
use App\Http\Model\Category;
use Illuminate\Http\Request;

use App\Http\Requests;

class ArticleController extends CommonController
{
    public function index()
    {
        $data = Article::orderBy('art_id', 'desc')->paginate(1);
        return view('admin.article.index', compact('data'));
    }

    public function create()
    {
        $category = (new Category)->tree();
        $pids = [];
        return view('admin.article.create', compact('pids', 'category'));
    }
}
