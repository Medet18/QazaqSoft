<?php

namespace App\Http\Controllers\PreviousTask;

use App\Http\Controllers\user\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{

    public function show(): \Illuminate\Http\JsonResponse
    {

        $p = Post::all();
        return response()->json(['posts'=>$p],200);
    }
    public function getPost(): \Illuminate\Http\JsonResponse
    {

        $p = Post::with('comments.posts')->get();

        return response()->json($p,200);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try{
            $photo = Str::random(32).".".$request->photo_for_post->getClientOriginalExtension();

            Post::create([
                'title' => $request->title,
                'comment' => $request->comment,
                'photo_for_post' => $photo,
            ]);

            Storage::disk('public')->put($photo, file_get_contents($request->photo_for_post));
            return response()->json(['message' =>'Post successfully stored!'], 200);

        } catch(\Exception $e){
           // return response()->json(['message' => 'Something went wrong!'], 500);
            return response()->json(['Exception' => $e], 500);
        }
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        try{
            $post = Post::find($id);

            if(!$post){
                return response()->json(['message'=>'No such post'], 404);
            }

            $post->title = $request->title;
            $post->comment = $request->comment;

            if($request->photo_for_post){
                $storage = Storage::disk('public'); //public storage

                //old image delete
                if($storage->exists($post->photo_for_post))
                    $storage->delete($post->photo_for_post);

                //Image name
                $photo = Str::random(32).".".$request->photo_for_post->getClientOriginalExtension();
                $post->photo_for_post = $photo;

                //Image save in public folder
                $storage->put($photo, file_get_contents($request->photo_for_post));
            }

            $post->save();
            return response()->json(['message' => 'Post successfully updated!'], 200);


        } catch(\Exception $e){
            return response()->json(['message' => $e],500);
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $post = Post::find($id);
        if(!$post){
            return response()->json(['message'=>'No such post'], 404);
        }

        $storage = Storage::disk('public');
        if($storage->exists($post->photo_for_post))
            $storage->delete($post->photo_for_post);

        $post->delete();

        return response()->json(['message' => 'Post successfully deleted!'], 200);

    }
}
