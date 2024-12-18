<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\Backend\UserService;
use App\Http\Requests\Backend\UserManagement\UserRequest;
use App\Http\Requests\Backend\UserManagement\ChangePasswordRequest;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) 
    {
        $this->middleware(function ($request, $next) {
            if ($request->user()->isAdmin() && !in_array($request->routeIs('panel.user.index'), ['panel.user.index'])) {
                abort(403, 'Unauthorized action.');
            }

            if (!$request->user()->isOwner() && !$request->user()->isAdmin()) {
                abort(403, 'Unauthorized action.');
            }

            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('backend.user.index', [
            'users' => $this->userService->getUsers($request->search),
            'roles' => $this->userService->getUserRoles(),
            'stats' => $this->userService->getUserStats()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.user.create', [
            'roles' => $this->userService->getUserRoles()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            $this->userService->createUser($request->validated());
            return redirect()->route('panel.user.index')->with('success', 'User created successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create user: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($uuid)
    {
        return view('backend.user.edit', [
            'user' => $this->userService->getUserByUuid($uuid),
            'roles' => $this->userService->getUserRoles()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, $uuid)
    {
        try {
            $this->userService->updateUser($uuid, $request->validated());
            return redirect()->route('panel.user.index')->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update user: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($uuid)
    {
        try {
            $this->userService->deleteUser($uuid);
            
            return redirect()->route('panel.user.index')->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    public function changePassword(ChangePasswordRequest $request, $uuid)
    {
        try {
            $this->userService->changePassword($uuid, $request->password);
            return redirect()->route('panel.user.index')->with('success', 'Password changed successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to change password: ' . $e->getMessage());
        }
    }

    public function changeRole(Request $request, $uuid)
    {
        try {
            $this->userService->changeRole($uuid, $request->role);
            return redirect()->route('panel.user.index')->with('success', 'Role changed successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to change role: ' . $e->getMessage());
        }
    }
}
