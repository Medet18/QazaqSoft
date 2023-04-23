<?php

namespace App\Http\Controllers\PreviousTask;

use App\Http\Controllers\user\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;


class CommentController extends Controller
{
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try{
            Comment::create([
                'comment_for_post' => $request->comment_for_post,
                'post_id' => $request->post_id,
            ]);

            return response()->json(['message' =>'Comment successfully stored!'], 200);

        } catch(\Exception $e){
            return response()->json(['Exception' => $e], 500);
        }
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        try{
            $com = Comment::find($id);

            if(!$com){
                return response()->json(['message'=>'No such post'], 404);
            }

            $com->comment_for_post = $request->comment_for_post;
            $com->save();
            return response()->json(['message' => 'Comment successfully updated!'], 200);


        } catch(\Exception $e){
            return response()->json(['message' => $e],500);
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $com = Comment::find($id);
        if(!$com){
            return response()->json(['message'=>'No such post'], 404);
        }
        $com->delete();

        return response()->json(['message' => 'Comment successfully deleted!'], 200);

    }
}
