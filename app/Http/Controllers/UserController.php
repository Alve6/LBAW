<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Timeout;
use App\Models\Moderator;
use App\Models\Admin;
use App\Models\ModeratorAction;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Carrega contagens no SQL (não puxa listas)
        $user->loadCount(['followers', 'following']);

        $auth = Auth::user();
        $isFollowing = false;

        if ($auth && $auth->id !== $user->id) {
            $isFollowing = $auth->isFollowing($user);
        }

        return view('pages.user.profile', [
            'user'           => $user,
            'isFollowing'    => $isFollowing,
            'followersCount' => $user->followers_count,
            'followingCount' => $user->following_count,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        Gate::authorize('update', $user);
        return view('pages.user.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        Gate::authorize('update', $user);
        $validator = Validator::make($request->all(), [
            'current_password' => [
                'required', function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('Current Password doesn\'t match');
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        $validated = $request->validate([
            'username' => 'nullable|string|max:250|unique:users',
            'name' => 'nullable|string|max:250',
            'email' => 'nullable|email|max:250|unique:users',
            'description' => 'nullable|string|max:1000',
            'file' => 'nullable|image|mimes:jpeg,jpg,png,gif'
        ]);

        if($request->hasFile('file')) {
            $file = FileController::upload($request, $user->id);
            $user->profile_image = $file;
        }

        // Create the new user.
        $user->update([
            'username'=> $request->username ?? $user->username,
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'description' => $request->description ?? $user->description,
        ]);

        if(!is_null($request->new_password))
        {
            $validated = $request->validate([
                'new_password' => 'required|min:8|confirmed'
            ]);
            $user->password = Hash::make($request->new_password);
        }

        $user->save();
        // Attempt login for updated user.
        $credentials = ['username' => $user->username, 'password' => $user->password];
        Auth::attempt($credentials);

        // Regenerate session for security (protection against session fixation).
        $request->session()->regenerate();

        // Redirect to cards page with a success message.
        return redirect()->route('user.show', ['user' => $user])
            ->withSuccess('Your profile has been successfully updated!');
    }

    public function deleteConfirmation(User $user)
    {
        Gate::authorize('delete', $user);
        return view('pages.user.delete', ['user' => $user]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);
        $user->delete();
        if(Auth::user()->id == $user->id){
            Auth::logout();
        }
        return redirect()->route('allNews')
            ->withSuccess('You have logged out successfully!');
    }

    public function showRecoveryForm() {
        return view('auth.recovery');
    }

    public function recoverPassword(Request $request) {
        $user = User::find(session('user'));
        $code = session('recovery_code');
        $request->validate([
            'password'=> 'required|min:8|confirmed',
        ]);

        $validator = Validator::make($request->all(), [
            'code' => [
                'required', function ($attribute, $value, $fail) use ($code) {
                    if ($value != $code) {
                        $fail('Recovery Code doesn\'t match');
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        session(['recovery_code' => null]);
        $user->password = bcrypt($request->password);
        $user->save();
        return redirect()->route('login');
    }

    public function recoverPasswordForm(User $user) {
        return view('auth.recover_password', ['user' => $user]);
    }

    public function applyTimeout(Request $request, User $user){
        try{
            if (!Moderator::where('user_id', auth()->id())->exists()){
                return response()->json(['success'=>false, 'error'=>'Unauthorized'], 403);
            }

            $request->validate([
                'duration_hours' => 'required|integer|min:1|max:168',
                'reason' => 'required|string|max:500'
            ]);

            $moderatorAction = ModeratorAction::create([
                'moderator_id'=>auth()->id(),
                'date'=>now()
            ]);
            
            Timeout::create([
                'moderator_action_id'=>$moderatorAction->id,
                'user_id'=>$user->id,
                'start_time'=>now(),
                'end_time'=>now()->addHours($request->duration_hours),
                'reason'=>$request->reason
            ]);

            return response()->json(['success'=>true, 'message'=>'Timeout applied successfully']);

        } catch (\Exception $e){
            return response()->json(['success'=>false, 'error'=>$e->getMessage()], 500);
        }
    }

    public function showTimeoutForm(User $user){
        return view('pages.user.timeout', ['user' => $user]);
    }

    public function showPromoteToModeratorForm(User $user){
        Gate::authorize('promote', $user);
        return view('pages.admin.promotetomoderator', ['user'=>$user]);
    }

    public function showPromoteToAdminForm(User $user){
        Gate::authorize('promote', $user);
        return view('pages.admin.promotetoadmin', ['user'=>$user]);
    }

    public function promoteToModerator(User $user){
        Gate::authorize('promote', $user);
        Moderator::create(['user_id'=>$user->id]);
        return redirect()->route('user.show', ['user' => $user])->with('success', 'User promoted to Moderator successfully.');
    }

    public function promoteToAdmin(User $user){
        Gate::authorize('promote', $user);
        Admin::create(['user_id'=>$user->id]);
        return redirect()->route('user.show', ['user' => $user])->with('success', 'User promoted to Admin successfully.');
    }
}
