<?php

namespace App\Http\Controllers;

use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServicesController extends Controller
{

    public function search_service(Request $request)
    {
        // Search for a service in the database by name.
        $service = Service::where('name', 'LIKE', "%{$request->search}%")->get();

        // Return a collection of services.
        return ServiceResource::collection($service)->response();
    }

    public function all(Request $request)
    {
        $service = Service::all();
        return ServiceResource::collection($service)->response();
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $service = new Service();
            $service->service_code = Str::upper(Str::random(12));
            $service->name = $request->name;
            $service->save();

            if($service)
            {
                DB::commit();
                return (new ServiceResource($service))->response();
            }

            throw new \Exception('Service not created');

        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
