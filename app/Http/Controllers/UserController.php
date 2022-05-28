<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\User as DataDb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Requests\UserStoreRequest as StoreValidation;
use App\Http\Requests\UserUpdateRequest as UpdateValidation;

class UserController extends Controller
{
    /**
     * The url of resources.
     *
     * @var string
     */
    private $type;

    /**
     * __construct function.
     */
    public function __construct()
    {
        $this->type = 'user';
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables(DataDb::query())
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
                $button .= '<a href="' . route($this->type . '.show', $data->uuid) . '" class="dropdown-item" type="button" name="view" id="' . $data->uuid . '">';
                $button .= '<i class="fa-solid fa-eye m-1"></i> VIEW';
                $button .= '</a>';
                $button .= '</li>';

                $button .= '<li><div class="dropdown-divider"></div></li>';
                $button .= '<li>';
                $button .= '<a href="' . route($this->type . '.edit', $data->uuid) . '" class="dropdown-item" type="button" name="edit" id="' . $data->uuid . '">';
                $button .= '<i class="fa-solid fa-pen-to-square m-1"></i> EDIT';
                $button .= '</a>';
                $button .= '</li>';

                $button .= '<li><div class="dropdown-divider"></div></li>';
                $button .= '<li>';
                $button .= '<button class="dropdown-item delete-btn" type="button" name="delete" data-id="' . $data->uuid . '" id="' . $data->uuid . '">';
                $button .= '<i class="fa-solid fa-trash-can m-1"></i> DELETE';
                $button .= '</button>';
                $button .= '</li>';

                $button .= '</ul>';
                $button .= '</div>';

                return $button;
            })
            ->rawColumns(['action','status'])
            ->toJson();
        }

        return view($this->type . '.index', [
            'type' => $this->type,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->type . '.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreValidation  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreValidation $request)
    {
        if ($request->ajax()) {
            $request->validated();

            $data = new DataDb();
            $data->uuid = (string) Str::uuid();
            $data->name = $request->name;
            $data->username = $request->username;
            $data->phone = $request->phone;
            $data->email = $request->email;
            $data->password = $request->password;

            if ($request->has('status')) {
                $data->is_active = '1';
                $data->activated_at = now()->toDateTimeString();
            } else {
                $data->is_active = '0';
                $data->deactivated_at = now()->toDateTimeString();
            }

            $data->save();

            return response()->json(['success' => 'save success']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  DataDb  $data
     * @return \Illuminate\Http\Response
     */
    public function show(DataDb $data)
    {
        return view($this->type . '.show', [
            'type' => $this->type,
            'data' => $data,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  DataDb  $data
     * @return \Illuminate\Http\Response
     */
    public function edit(DataDb $data)
    {
        return view($this->type . '.edit', [
            'type' => $this->type,
            'data' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateValidation  $request
     * @param  DataDb  $data
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateValidation $request, DataDb $data)
    {
        if ($request->ajax()) {
            $request->validated();

            $data->name = $request->name;
            $data->username = $request->username;
            $data->phone = $request->phone;
            $data->email = $request->email;

            if ($request->has('password')) {
                $data->password = $request->password;
            }

            if ($request->has('status')) {
                $data->is_active = '1';
                $data->activated_at = now()->toDateTimeString();
            } else {
                $data->is_active = '0';
                $data->deactivated_at = now()->toDateTimeString();
            }

            $data->save();

            return response()->json(['success' => 'update success']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DataDb  $data
     * @return \Illuminate\Http\Response
     */
    public function destroy(DataDb $data)
    {
        // delete book
        $data->delete();

        return response()->json(['success' => 'delete success']);
    }
}
