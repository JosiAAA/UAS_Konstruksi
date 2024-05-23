<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function toggleFollow(Request $request)
    {
        $currentUser = Auth::user();
        $targetedUser = User::where('name', $request->targeted_user)->first();

        if (!$targetedUser) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $follow = Follow::where('current_user_id', $currentUser->id)
                        ->where('targeted_user_id', $targetedUser->id)
                        ->first();

        if ($follow) {
            // Unfollow
            $follow->delete();
            $targetedUser->saldo -= 1;
            $targetedUser->save();
            return redirect()->back()->with('success', 'Unfollowed successfully.');
        } else {
            // Follow
            Follow::create([
                'current_user_id' => $currentUser->id,
                'targeted_user_id' => $targetedUser->id,
            ]);
            $targetedUser->saldo += 1;
            $targetedUser->save();
            return redirect()->back()->with('success', 'Followed successfully.');
        }
    }
}
