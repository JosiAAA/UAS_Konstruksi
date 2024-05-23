<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{
    public function show($id)
    {
        $targetedUser = User::findOrFail($id);
        $currentUser = Auth::user();
        $isFollowing = $currentUser->following()->where('targeted_user_id', $targetedUser->id)->exists();

        return view('explore', [
            'targetedUser' => $targetedUser,
            'isFollowing' => $isFollowing,
            
        ]);
    }
}
