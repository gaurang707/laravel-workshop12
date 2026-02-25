<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(protected UserService $service)
    {
        // service injected by container
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $users = $this->service->list();
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): JsonResponse
    {
        $user = $this->service->create($request->validated());

        return response()->json(['data' => $user, 'message' => 'User created successfully.'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user): JsonResponse
    {
        $updated = $this->service->update($user, $request->validated());
        return response()->json(['data' => $updated, 'message' => 'User updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        $this->service->delete($user);
        return response()->json(null, 204);
    }
}
