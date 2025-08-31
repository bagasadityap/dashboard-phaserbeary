<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;

class RaffleController extends Controller
{
    public function index(Request $request) {
        $page = 'Raffle';
        $response = Http::get('https://api.phaserbeary.xyz/api/raffle');
        $data = json_decode($response->body());
        $model = collect($data->data);

        if ($request->ajax()) {
            return DataTables::of($model)
                ->addColumn('_', function($model) {
                    $html = '';
                    $html .= '<button class="btn btn-outline-primary px-2 me-1 d-inline-flex align-items-center" onclick="view(\'' . $model->id . '\')"><i class="iconoir-eye fs-14"></i></button>';
                    $html .= '<button class="btn btn-outline-warning px-2 me-1 d-inline-flex align-items-center" onclick="edit(\'' . $model->id . '\')"><i class="iconoir-edit fs-14"></i></button>';
                    $html .= '<button class="btn btn-outline-danger px-2 d-inline-flex align-items-center" onclick="remove(\'' . $model->id . '\')"><i class="iconoir-trash fs-14"></i></button>';
                    return $html;
                })
                ->editColumn('description', function($model) {
                    $html = substr($model->description, 0, 20);
                    return $html;
                })
                ->editColumn('end_date', function($model) {
                    return Carbon::parse($model->end_date)->format('j F Y H:i');
                })
                ->editColumn('created_at', function($model) {
                    return Carbon::parse($model->created_at)->format('j F Y H:i');
                })
                ->editColumn('status', function($model) {
                    if ($model->status == 0) {
                        return '<span class="badge rounded-pill bg-secondary p-1">Draft</span>';
                    } elseif ($model->status == 1) {
                        return '<span class="badge rounded-pill bg-info p-1">Published</span>';
                    }  elseif ($model->status == 2) {
                        return '<span class="badge rounded-pill bg-primary p-1">Completed</span>';
                    } elseif ($model->status == 3) {
                        return '<span class="badge rounded-pill bg-success p-1">Closed</span>';
                    } elseif ($model->status == 4) {
                        return '<span class="badge rounded-pill bg-danger p-1">Cancelled</span>';
                    }

                    return '<span class="badge rounded-pill bg-dark p-1">Unknown</span>';

                })
                ->rawColumns(['_', 'description', 'end_date', 'created_at', 'status'])
                ->make(true);
        }

        return view('raffle.index', compact('page'));
    }

    public function create()
    {
        $page = 'Raffle';
        return view('raffle.create', compact('page'));
    }

    public function edit($id)
    {
        $page = 'Raffle';
        $response = Http::get('https://api.phaserbeary.xyz/api/raffle/get-raffle-by-id/' . $id);
        $data = json_decode($response->body());
        $model = collect($data->data);

        return view('raffle.update', compact('page', 'model'));
    }

    public function view($id)
    {
        $page = 'Raffle';
        $response = Http::get('https://api.phaserbeary.xyz/api/raffle/get-raffle-by-id/' . $id);
        $data = json_decode($response->body());
        $model = collect($data->data);

        return view('raffle.view', compact('page', 'model'));
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'end_date' => 'required|date',
        ]);

        $response = Http::attach(
            'image', file_get_contents($request->file('image')->getRealPath()), $request->file('image')->getClientOriginalName()
        )->post('https://api.phaserbeary.xyz/api/raffle/create', [
            'title' => $request->title,
            'description' => $request->description,
            'end_date' => Carbon::parse($request->end_date, 'Asia/Jakarta')
                          ->setTimezone('UTC')
        ]);

        if ($response->successful()) {
            return redirect()->route('raffle.index')
                            ->with('success', 'Raffle created successfully.');
        } else {
            return back()->withInput()
                        ->with('error', 'Failed to create raffle: ' . $response->body());
        }
    }

    public function update(Request $request, $id) {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'end_date' => 'nullable|date',
            'status' => 'nullable|integer',
        ]);

        $url = 'https://api.phaserbeary.xyz/api/raffle/update/' . $id;

        if ($request->hasFile('image')) {
            $response = Http::attach(
                'image',
                file_get_contents($request->file('image')->getRealPath()),
                $request->file('image')->getClientOriginalName()
            )->put($url, [
                'title' => $request->title,
                'description' => $request->description,
                'end_date' => $request->end_date,
                'status' => $request->status,
            ]);
        } else {
            $response = Http::put($url, [
                'title' => $request->title,
                'description' => $request->description,
                'end_date' => $request->end_date,
                'status' => $request->status,
            ]);
        }

        if ($response->successful()) {
            return redirect()->route('raffle.index')
                        ->with('success', 'Raffle updated successfully.');
        } else {
            return back()->withInput()
                        ->with('error', 'Failed to update raffle: ' . $response->body());
        }
    }

    public function delete($id)
    {
        $response = Http::delete('https://api.phaserbeary.xyz/api/raffle/delete/' . $id);

        if ($response->successful()) {
            return redirect()->route('raffle.index')
                            ->with('success', 'Raffle deleted successfully.');
        } else {
            return back()->with('error', 'Failed to delete raffle: ' . $response->body());
        }
    }

    public function randomPicker()
    {
        $page = 'Raffle';
        return view('raffle.random_picker', compact('page'));
    }
}
