<?php
namespace App\Http\Controllers;

use App\Models\list_donasi;
use App\Models\komentar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function store(Request $request)
    {
        DB::table('list_donasi')->insert([
            'judul'=>$request->judul,
            'pemilik' => $request->pemilik,
            'role_pemilik'=> $request->role_pemilik,
            'link_gambar'=>$request->link_gambar,
            'tanggal' => $request->tanggal,
            'jam'=> $request->jam,
            'deskripsi'=> $request->deskripsi,
            'wilayah'=> $request->wilayah
        ]);
    
        return redirect('/posting');
    }

    public function donate(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user
        $donationId = $request->input('donation_id');
        $nominal = $request->input('nominal');
        $currentUser = $request->input('current_user'); // Ambil current_user dari input form
        $targetedPost = $request->input('targeted_post'); // Ambil targeted_post dari input form

        // Logika untuk Like
        // Cek apakah user sudah melakukan like pada targeted_post
        $like = DB::table('like_tabel')
            ->where('current_user', $currentUser)
            ->where('targeted_post', $targetedPost)
            ->first();

        if ($like) {
            // Jika user sudah melakukan like, tidak lakukan apa-apa
            return redirect()->back()->with('error', 'Anda sudah melakukan like pada postingan ini.');
        } else {
            // Jika user belum melakukan like, tambahkan data ke database
            DB::table('like_tabel')->insert([
                'current_user' => $currentUser,
                'targeted_post' => $targetedPost,
                'limit' => 1 // Set limit menjadi 1 karena user sudah melakukan like
            ]);

            // Check if the user has enough balance
            if ($user->saldo >= $nominal) {
                // Deduct the amount from the user's balance
                $user->saldo -= $nominal;
                $user->save();

                // Update the total_terkumpul in the list_donasi table
                $donation = list_donasi::find($donationId);
                $donation->total_terkumpul += $nominal;
                $donation->jumlah_pendonasi += 1;
                $donation->save();

                return redirect()->back()->with('success', 'Donasi dan like berhasil dilakukan');
            } else {
                return redirect()->back()->with('error', 'Saldo tidak mencukupi');
            }
        }
    }
    public function destroy(string $id)
    {
        DB::table("list_donasi")->where("id",$id)->delete();
        return redirect()->back()->with('success', 'Donasi berhasil dilakukan');
    }

   
}
