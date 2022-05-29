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
use App\Http\Requests\UserImportRequest as ImportValidation;
use App\Exports\UserExport;
use App\Exports\UserImportTemplate;
use App\Imports\UserImport;
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

    /**
     * Show list resource.
     *
     * @param Request $request
     */
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
     */
    public function create()
    {
        return view($this->type . '.create', [
            'type' => $this->type,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreValidation  $request
     */
    public function store(StoreValidation $request)
    {
        try {
            DB::beginTransaction();
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

                DB::commit();

                return response()->json(['success' => 'save success']);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  DataDb  $data
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
     */
    public function update(UpdateValidation $request, DataDb $data)
    {
        try {
            DB::beginTransaction();
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

                DB::commit();

                return response()->json(['success' => 'update success']);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DataDb  $data
     */
    public function destroy(DataDb $data)
    {
        // delete book
        $data->delete();

        return response()->json(['success' => 'delete success']);
    }

    /**
     * export data.
     */
    public function export()
    {
        return new UserExport($this->type);
    }

    /**
     * import Template excel data.
     */
    public function importTemplate()
    {
        return new UserImportTemplate($this->type);
    }

    /**
     * import form.
     */
    public function importForm()
    {
        return view($this->type . '.import', [
            'type' => $this->type,
        ]);
    }

    /**
     * store import data.
     */
    public function importStore(ImportValidation $request)
    {
        try {
            if ($request->ajax()) {
                $request->validated();

                Excel::import(new UserImport(), $request->file('file'));

                return response()->json(['success' => 'update success']);
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
