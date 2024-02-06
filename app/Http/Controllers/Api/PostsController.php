<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;

use App\Models\PostModel;

use Tymon\JWTAuth\Exceptions\UserNotDefinedException;
use Validator;

class PostsController extends Controller
{
    public function getAllPosts()
    {
        try {
            $user = auth()->userOrFail();
        } catch (UserNotDefinedException $ex) {
            return response()
                ->json(['error' => true, 'message' => $ex->getMessage()], 401);
        }
        return response()->json(PostModel::get(), 200);
    }

    public function getPostById($id)
    {
        try {
            $user = auth()->userOrFail();
        } catch (UserNotDefinedException $ex) {
            return response()
                ->json(['error' => true, 'message' => $ex->getMessage()], 401);
        }

        $post = PostModel::find($id);
        if(is_null($post)){
            return response()
                ->json([
                        'error' => true,
                        'message'=> "post not found"
                    ], 404);
        }
        return response()->json($post, 200);
    }

    public function deletePost($id)
    {
        try {
            $user = auth()->userOrFail();
        } catch (UserNotDefinedException $ex) {
            return response()
                ->json(['error' => true, 'message' => $ex->getMessage()]);
        }

        $post = PostModel::find($id);
        if(is_null($post)){
            return response()
                ->json([
                        'error' => true,
                        'message'=> "post not found"
                    ], 404);
        }
        $titlePost = $post->title;

        $post->delete();

        return response()->json([$titlePost => "success delete"], 202);
    }

    public function addNewPost(Request $req)
    {
        try {
            $user = auth()->userOrFail();
        } catch (UserNotDefinedException $ex) {
            return response()
                ->json(['error' => true, 'message' => $ex->getMessage()]);
        }

        $rules = [
            'title' => 'required|min:3|max:20',
            'content' => 'required|min:10|max:100'
        ];
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $post = PostModel::create($req->all());

        return response()->json($post, 201);
    }

    public function updatePost(Request $req, $id)
    {
        try {
            $user = auth()->userOrFail();
        } catch (UserNotDefinedException $ex) {
            return response()
                ->json(['error' => true, 'message' => $ex->getMessage()], 401);
        }

        $rules = [
            'title' => 'required|min:3|max:20',
            'content' => 'min:10|max:100'
        ];
        $validator = Validator::make($req->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $post = PostModel::find($id);
        if(is_null($post)){
            return response()
                ->json([
                    'error' => true,
                    'message'=> "post not found"
                ], 404);
        }
        $post->update($req->all());

        return response()->json($post, 200);
    }
}
