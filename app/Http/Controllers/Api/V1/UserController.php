<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UserUpdateRequest;
use App\Http\Requests\Api\User\UserStoreRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return UserResource::collection(User::with('files')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return UserResource
     */
    public function store(UserStoreRequest $request)
    {
        $validated = $request->validated();

        $created_user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return new UserResource($created_user);
    }

    /**
     * Display the specified resource.
     *
     * @return UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     * @param User $user
     * @param UserUpdateRequest $request
     * @return UserResource
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $user->update($request->validated());

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user)
    {
        if (Storage::disk('public')->has('files/' . $user->id)) {
            Storage::disk('public')->deleteDirectory('files/' . $user->id);
        }

        $user->delete();

        return response()->json([
            'success' => 'true',
            'message' => 'User has been deleted'
        ]);
    }
}
