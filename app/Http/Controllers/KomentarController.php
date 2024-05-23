<?php

namespace App\Http\Controllers;

use App\Models\list_donasi;
use App\Models\komentar;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KomentarController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->route('id');
        $result = DB::table('list_donasi')->where('id', $id)->get();
        $komentar = komentar::where('target', $id)->get();
        $jumlahKomentar = komentar::where('target', $id)->count();

        $targetedUser = User::where('name', $result[0]->pemilik)->first();
        $currentUser = Auth::user();
        $isFollowing = Follow::where('current_user_id', $currentUser->id)
                            ->where('targeted_user_id', $targetedUser->id)
                            ->exists();

        return view('explore', [
            'result' => $result,
            'komentar' => $komentar,
            'jumlahKomentar' => $jumlahKomentar,
            'targetedUser' => $targetedUser,
            'isFollowing' => $isFollowing,
        ]);
    }

    public function store(Request $request)
    {
        DB::table('komentar')->insert([
            'pemilik' => $request->pemilik,
            'komen' => $request->komen,
            'role_pemilik' => $request->role_pemilik,
            'target' => $request->target,
            'tanggal' => $request->tanggal,
            'jam' => $request->jam,
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan');
    }
}
