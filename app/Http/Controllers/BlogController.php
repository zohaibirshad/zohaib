<?php

namespace App\Http\Controllers;

use App\Models\BlogTag;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $category = $request->category;
        $tag = $request->tag;

        $blog_posts = BlogPost::with('categories', 'tags')->latest()
            ->when(!empty($category), function ($query) use ($category) {
                $query->whereHas('categories', function ($q) use ($category) {
                    $q->where('id', $category)->orwhere('slug', $category);
                });
            })
            ->when(!empty($tag), function ($query) use ($tag) {
                $query->whereHas('tags', function ($q) use ($tag) {
                    $q->where('id', $tag)->orwhere('slug', $tag);
                });
            })
            ->paginate(4);
            $blog_posts->appends(['category' => $category]);
            $blog_posts->appends(['tag' => $tag]);

        $featured_posts = BlogPost::where('featured', 'yes')->with('categories', 'tags')->latest()->limit(5)->get();

        $trending_posts = BlogPost::with('categories', 'tags')->orderBy('count', 'desc')->latest()->limit(5)->get();

        $categories = BlogCategory::limit(10)->get();

        $tags = BlogTag::limit(15)->get();

        return view('blog.index', compact(
            'blog_posts',
            'featured_posts',
            'trending_posts',
            'categories',
            'tags'
        ));
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = BlogPost::where('id', $id)
            ->orWhere('slug', $id)
            ->with('categories', 'tags')
            ->first();

        $trending_posts = BlogPost::with('categories', 'tags')->orderBy('count', 'desc')->latest()->limit(5)->get();

        $categories = BlogCategory::limit(10)->get();

        $tags = BlogTag::limit(15)->get();

        return view('blog.show', compact(
            'post',
            'trending_posts',
            'categories',
            'tags'
        ));
    }


    /**
     * show a specified blog category posts.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function category($id)
    {
        $trending_posts = BlogPost::with('categories', 'tags')->orderBy('count', 'desc')->latest()->paginate(4);

        $categories = BlogCategory::limit(10)->get();

        $tags = BlogTag::limit(15)->get();

        $blog_posts = BlogPost::whereHas('categories', function (Builder $query) use ($id) {
            $query->where('id', $id);
        })
            ->with('categories', 'tags')
            ->latest()
            ->paginate(4);

        return view('blog.category', compact(
            'blog_post',
            'trending_posts',
            'categories',
            'tags'
        ));
    }

    /**
     * show a specified blog tag posts.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function tag($id)
    {
        $trending_posts = BlogPost::with('categories', 'tags')->orderBy('count', 'desc')->latest()->paginate(4);

        $categories = BlogCategory::limit(10)->get();

        $tags = BlogTag::limit(15)->get();

        $blog_posts = BlogPost::whereHas('tags', function (Builder $query) use ($id) {
            $query->where('id', $id);
        })
            ->with('categories', 'tags')
            ->latest()
            ->paginate(4);

        return view('blog.category', compact(
            'blog_post',
            'trending_posts',
            'categories',
            'tags'
        ));
    }
}
