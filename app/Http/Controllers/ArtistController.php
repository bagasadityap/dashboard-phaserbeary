<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Gedung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class ArtistController extends Controller
{
    public function index(Request $request) {
        $page = 'Art Gallery \ Artist';
        $response = Http::get('https://api.phaserbeary.xyz/api/users');
        $model = json_decode($response->body());

        if ($request->ajax()) {
            return DataTables::of($model)
                ->editColumn('profile_picture', function($model) {
                    if ($model->profile_picture) {
                        $url = "https://api.phaserbeary.xyz/storage/" . $model->profile_picture;
                        return '<img src="'.$url.'" alt="user-profile" class="img-thumbnail rounded-circle" width="50">';
                    }
                    return '<i class="iconoir-profile-circle" style="font-size: 32px; color: #888;"></i>';
                })
                ->editColumn('twitter_account', function($model) {
                    if ($model->twitter_account) {
                        $url = "https://x.com/" . $model->twitter_account;
                        return '<a href="'.$url.'">@'. $model->twitter_account .'</a>';
                    }
                    return '';
                })
                ->editColumn('instagram', function($model) {
                    if ($model->instagram) {
                        $url = "https://www.instagram.com/" . $model->instagram;
                        return '<a href="'.$url.'">@'. $model->instagram .'</a>';
                    }
                    return '';
                })
                ->editColumn('description', function($model) {
                    $desc = $model->description ?? '-';
                    $desc = preg_replace("/\r|\n/", " ", $desc);
                    $desc = mb_convert_encoding($desc, 'UTF-8', 'UTF-8');

                    return substr($desc, 0, 120) . ' .....';
                })
                ->rawColumns(['_', 'profile_picture', 'twitter_account', 'instagram', 'description'])
                ->make(true);
        }

        return view('art_gallery.artist.index', compact('page'));
    }

    public function view($id) {
        $page = 'Gedung \ Detail';
        $model = Gedung::findOrFail($id);

        return view('gedung.view', compact('page', 'model'));
    }

    public function delete($id) {
        $model = Gedung::where('id', $id)->firstOrFail();

        if ($model->pesanan()->exists()) {
            session()->flash('error', 'Gedung tidak dapat dihapus karena tedapat pesanan yang terkait.');
            return response()->json([
                'success' => false,
                'message' => 'Gedung tidak dapat dihapus karena tedapat pesanan yang terkait.'
            ]);
        }

        if ($model->gambar && Storage::disk('public')->exists($model->gambar)) {
            Storage::disk('public')->delete($model->gambar);
        }
        if ($model->gambarVR && Storage::disk('public')->exists($model->gambarVR)) {
            Storage::disk('public')->delete($model->gambarVR);
        }

        $model->delete();
        session()->flash('success', 'Data berhasil dihapus.');
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
