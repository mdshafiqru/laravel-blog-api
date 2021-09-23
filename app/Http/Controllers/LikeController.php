<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    // like or unlike
    public function likeOrUnLike($id){
        $post = Post::find($id);

        if (!$post) {
            return response([
                'message' => 'Post not found'
            ], 403);
        }
        $like = $post->likes()->where('user_id', auth()->user()->id)->first();
        
        // if not liked then like
        if (!$like) {
            Like::create([
                'post_id' => $id,
                'user_id' => auth()->user()->id,
            ]);
            return response([
                'message' => 'liked'
            ], 200);
        }
        // else dislike it
        $like->delete();
        return response([
            'message' => 'disliked',
        ], 200);


    }
}