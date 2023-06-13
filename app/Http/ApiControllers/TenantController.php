<?php

namespace App\Http\ApiControllers;

use App\Http\Resources\TenantResource;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TenantController extends Controller
{
    public function index()
    {
        $collection = Tenant::filter()->collective();

        return TenantResource::collection($collection);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "id" => "required|string",
        ]);

        $row = $request->only(['id']);

        $record = Tenant::create($row);

        $message = "Tenant [$record->name] has been created";

        return (new TenantResource($record))
            ->additional(["message" => $message]);
    }

    public function show($id)
    {
        $record = Tenant::findOrFail($id);

        return (new TenantResource($record));
    }

    public function setUser($id, Request $request)
    {
        $this->validate($request, [
            "user_id" => "required|exists:users,id",
        ]);

        $record = Tenant::findOrFail($id);

        $user = User::find($request->user_id);

        DB::beginTransaction();

        $record->users()->save($user);

        $message = "User [$user->name] set to access Tenant [$record->name]";

        DB::commit();

        return (new TenantResource($record))
            ->additional(["message" => $message]);
    }

    public function destroy($id)
    {
        $record = Tenant::findOrFail($id);

        $record->delete();

        $message = "Tenant [$record->name] has been deleted";

        return response()->json(["message" => $message]);
    }
}
