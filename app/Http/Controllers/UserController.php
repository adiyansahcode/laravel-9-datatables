<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::latest();

            return datatables()
            ->of($data)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return [
                    'display' => date("Y-m-d H:i:s", strtotime($row->created_at)),
                    'timestamp' => strtotime($row->created_at),
                ];
            })
            ->editColumn('updated_at', function ($row) {
                return [
                    'display' => date("Y-m-d H:i:s", strtotime($row->updated_at)),
                    'timestamp' => strtotime($row->updated_at),
                ];
            })
            ->addColumn('status', function ($data) {
                $icon = null;
                if ($data->is_active) {
                    $icon = '<i class="fa-solid fa-circle-check text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Active"></i>';
                } else {
                    $icon = '<i class="fa-solid fa-lock text-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Non Active"></i>';
                }

                return $icon;
            })
            ->addColumn('action', function ($data) {
                $button = null;
                $button .= '<div class="btn-group">';
                $button .= '<button type="button" class="btn btn-default btn-sm dropdown-toggle" id="action-' . $data->uuid . '" data-bs-toggle="dropdown" aria-expanded="false">';
                $button .= '<i class="fa-solid fa-ellipsis-vertical"></i>';
                $button .= '</button>';

                $button .= '<ul class="dropdown-menu dropdown-menu-end" id="action-' . $data->uuid . '-menu" aria-labelledby="action-' . $data->uuid . '">';

                $button .= '<li>';
                $button .= '<a href="' . route('user.show', $data->uuid) . '" class="dropdown-item" type="button" name="view" id="' . $data->uuid . '"> <i class="fa-solid fa-eye m-1"></i> VIEW </a>';
                $button .= '</li>';

                $button .= '<li><div class="dropdown-divider"></div></li>';
                $button .= '<li>';
                $button .= '<a href="' . route('user.edit', $data->uuid) . '" class="dropdown-item" type="button" name="edit" id="' . $data->uuid . '"> <i class="fa-solid fa-pen-to-square m-1"></i> EDIT </a>';
                $button .= '</li>';

                $button .= '<li><div class="dropdown-divider"></div></li>';
                $button .= '<li>';
                $button .= '<button class="dropdown-item delete-btn" type="button" name="delete" data-id="' . $data->uuid . '" id="' . $data->uuid . '"> <i class="fa-solid fa-trash-can m-1"></i> DELETE </button>';
                $button .= '</li>';

                $button .= '</ul>';
                $button .= '</div>';

                return $button;
            })
            ->rawColumns(['action','status'])
            ->make(true);
        }

        return view('user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\UserStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request)
    {
        if ($request->ajax()) {
            $request->validated();

            $user = new User();
            $user->uuid = (string) Str::uuid();
            $user->name = $request->name;
            $user->username = $request->username;
            $user->phone = $request->phone;
            $user->email = $request->email;
            $user->password = $request->password;

            if ($request->has('status')) {
                $user->is_active = '1';
                $user->activated_at = now()->toDateTimeString();
            } else {
                $user->is_active = '0';
                $user->deactivated_at = now()->toDateTimeString();
            }

            $user->save();

            return response()->json(['success' => 'save success']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('user.show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('user.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UserUpdateRequest  $request
     * @param  \App\Models\User  $book
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        if ($request->ajax()) {
            $request->validated();

            $user->name = $request->name;
            $user->username = $request->username;
            $user->phone = $request->phone;
            $user->email = $request->email;

            if ($request->has('password')) {
                $user->password = $request->password;
            }

            if ($request->has('status')) {
                $user->is_active = '1';
                $user->activated_at = now()->toDateTimeString();
            } else {
                $user->is_active = '0';
                $user->deactivated_at = now()->toDateTimeString();
            }

            $user->save();

            return response()->json(['success' => 'update success']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // delete book
        $user->delete();

        return response()->json(['success' => 'delete success']);
    }
}
