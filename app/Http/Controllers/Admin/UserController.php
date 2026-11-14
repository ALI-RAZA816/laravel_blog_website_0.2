<?php

namespace App\Http\Controllers\Admin;

use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewUserRequest;
use App\Http\Requests\UpdateNewUserPhotoRequest;
use App\Http\Requests\UpdateNewUserRequest;
use App\Models\User;
use App\Support\ImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function fetchUser(Request $request)
    {
        $search = $request->query('query');
        $role   = $request->query('role');
        $status = $request->query('status');

        $users = User::select('id', 'name', 'email', 'role', 'status', 'image', 'join_date')
            ->when($search && $search !== 'all', fn ($q) => $q->where(fn ($q) =>
                $q->where('name', 'LIKE', "%{$search}%")->orWhere('email', 'LIKE', "%{$search}%")))
            ->when($role && $role !== 'all', fn ($q) => $q->where('role', $role))
            ->when($status && $status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()->paginate(10);

        return response()->json([
            'status'     => true,
            'loggedUser' => $request->user()->only('id', 'name', 'email', 'role', 'join_date', 'status', 'image'),
            'users'      => $users,
            'counts'     => [
                'total'   => User::count(),
                'editor'  => User::where('role', 'editor')->count(),
                'blocked' => User::where('status', 'blocked')->count(),
            ],
            'this_week'  => User::where('created_at', '>=', now()->startOfWeek())->count(),
        ], 200);
    }

    public function addUser(StoreNewUserRequest $request)
    {
        $imageName = $request->hasFile('image')
            ? ImageUpload::save($request->file('image'), 'uploads') : null;

        $user = User::create([
            'name'      => $request->name,
            'username'  => $request->username,
            'role'      => $request->role,
            'status'    => $request->status ?? 'inactive',
            'bio'       => $request->bio,
            'email'     => $request->emailaddress,
            'password'  => Hash::make($request->password),
            'image'     => $imageName,
            'join_date' => date('M d, y'),
        ]);

        UserRegistered::dispatch($user, $request->password, $request->boolean('instruction'));
        return response()->json(['status' => 200, 'message' => 'New user added successfully']);
    }

    public function fetchEditUser(string $id)
    {
        Gate::authorize('manage-users');

        $user = User::select('id', 'name', 'username', 'email', 'bio', 'image', 'role', 'status')->find($id);

        if (!$user) {
            return response()->json(['status' => 404, 'user' => 'User not found'], 404);
        }

        return response()->json(['status' => 200, 'user' => $user], 200);
    }

    public function updateUser(UpdateNewUserRequest $request, int $id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'User not found'], 404);

       Gate::authorize('update-user', [$user, $request->role, $request->status]);

        $imageName = $user->image;
        if ($request->hasFile('image')) {
            ImageUpload::delete($user->image, 'uploads');
            $imageName = ImageUpload::save($request->file('image'), 'uploads');
        }

        $user->update([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'role'     => $request->role,
            'status'   => $request->status,
            'bio'      => $request->bio,
            'image'    => $imageName,
        ]);

        if ($request->status === 'blocked') $user->tokens()->delete();   // EnsureUserIsActive session band karta hai

        return response()->json(['status' => 200, 'message' => 'User updated successfully'], 200);
    }

    public function deleteUser(Request $request, string $id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'User not found'], 404);

        Gate::authorize('delete-user', $user);

        ImageUpload::delete($user->image, 'uploads');
        $user->delete();   // note: is user ki posts bhi cascade se delete hongi
        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    public function updatePhoto(UpdateNewUserPhotoRequest $request, string $id)
    {
        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'User not found'], 404);

        if ($request->hasFile('image')) {
            ImageUpload::delete($user->image, 'uploads');
            $user->update(['image' => ImageUpload::save($request->file('image'), 'uploads')]);
        }
        return response()->json(['message' => 'Image updated'], 200);
    }
}
