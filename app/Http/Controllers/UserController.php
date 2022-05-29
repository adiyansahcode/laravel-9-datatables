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
use App\Exports\UserExport;
use Maatwebsite\Excel\Facades\Excel;

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
                    'display' => $row->created_at->isoFormat('DD MMMM Y HH:mm:ss'),
                    'timestamp' => $row->created_at->timestamp,
                ];
            })
            ->editColumn('updated_at', function ($row) {
                return [
                    'display' => $row->updated_at->isoFormat('DD MMMM Y HH:mm:ss'),
                    'timestamp' => $row->updated_at->timestamp,
                ];
            })
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

    public function export()
    {
        return new UserExport($this->type);
    }
}
