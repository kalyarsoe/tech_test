<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Exceptions\UserLikeOwnPostException;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\PostToggleReactionRequest;
use App\Exceptions\UserAlreadyLikedPostException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PostController extends Controller
{
    public function list()
    {
        $posts = Post::withCount('likes')->with('tags')->paginate();  
        $prepareResource = PostResource::collection($posts);
        return  $posts->setCollection(collect($prepareResource));
    }

    public function toggleReaction(PostToggleReactionRequest $request)
    {  
        try {   
            $post = Post::with(['likes' => function ($query) {
                $query->where('user_id', Auth::guard('ctj-api')->id());
            }])->findOrFail($request->post_id);
            
            // user tries to like his own post
            throw_if(Gate::denies('like-post', $post), UserLikeOwnPostException::class);  
          
            # search by login user id 
            $alreadyLikeChk = $post->likes()->where('user_id', Auth::guard('ctj-api')->id())->first();
          
            # If user already liked the post
            if ($alreadyLikeChk) {
                // reaction is like the post
                throw_if($request->boolean('like'), UserAlreadyLikedPostException::class);
                
                $alreadyLikeChk->delete();
                
                return response()->json([
                    'status'  => Response::HTTP_OK,
                    'message' => 'You unlike this post successfully',
                ]);
            }

            $post->likes()->create([
                'user_id' => Auth::guard('ctj-api')->id(),
            ]);

            return response()->json([
                'status'  => Response::HTTP_OK,
                'message' => 'You like this post successfully',
            ]);
        } catch (UserLikeOwnPostException $e) {
            return response()->json([
                'status'  => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'You cannot like your post',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (UserAlreadyLikedPostException $e) {
            return response()->json([
                'status'  => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => 'You already liked this post',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'  => Response::HTTP_NOT_FOUND,
                'message' => 'model not found',
            ], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }   
}
