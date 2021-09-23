<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // get all post
    public function index(){
        return response([
            'posts' => Post::orderBy('created_at', 'desc')->with('user:id,name,image')->withCount('comments', 'likes')->with('likes', function($like){
                return $like->where('user_id', auth()->user()->id)->select('id', 'user_id', 'post_id')->get();
            })->get()
        ], 200);
    }
    
    // get single post
    public function show($id){
        return response([
            'post' => Post::where('id', $id)->withCount('comments', 'likes')->get()
        ], 200);
    }

    // create a post
    public function store(Request $request){
        // validate fields
        $attrs = $request->validate([
            'body' => 'required|string'
        ]);

        $image = $this->saveImage($request->image, 'posts');

        $post = Post::create([
            'body' => $attrs['body'],
            'user_id' => auth()->user()->id,
            'image' => $image
        ]);
        // for now skip for post image

        return response([
            'message' => 'Post created',
            'post' => $post
        ], 200);
    }

    // update a post
    public function update(Request $request, $id){

        $post = Post::find($id);

        if (!$post) {
            return response([
                'message' => 'Post not found'
            ], 403);
        }

        if ($post->user_id != auth()->user()->id) {
            return response([
                'message' => 'Access Denied'
            ], 403);
        }

        // validate fields
        $attrs = $request->validate([
            'body' => 'required|string'
        ]);

        $post->update([
            'body' => $attrs['body'],
        ]);


        // for now skip for post image

        return response([
            'message' => 'Post Updated',
            'post' => $post
        ], 200);
    }

    // delete a post
    public function destroy($id){
        $post = Post::find($id);

        if (!$post) {
            return response([
                'message' => 'Post not found'
            ], 403);
        }

        if ($post->user_id != auth()->user()->id) {
            return response([
                'message' => 'Access Denied'
            ], 403);
        }

        if ($post->comments) {
            $post->comments->each->delete();
        }
        if ($post->likes) {
            $post->likes->each->delete();
        }


        // $post->comments->each->delete();
        // $post->likes->each->delete();

        if ($post) {
            $post->delete();
        }
        
        return response([
            'message' => 'Post Deleted'
          
        ], 200);
    }
}
