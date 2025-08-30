<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Inertia\Inertia;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    public function index(){
        $packages = DB::table('services')->paginate(10);
        return Inertia::render('Admin/Packages/Index', compact(['packages']));
    }

    public function create(){
        return Inertia::render('Admin/Packages/Create');
    }

    public function edit(Service $service){
        return Inertia::render('Admin/Packages/Create', compact(['service']));
    }

    public function store(Request $request){
        try{
            DB::beginTransaction();
            $validatedData = $request->validate([
                'name' => 'required|string',
                'description' => 'sometimes',
                'price' => 'required|numeric',
            ]);
            $service = Service::create($validatedData);
            DB::commit();
            return redirect()->route('admin.package.index');
        }catch(Exception $e){
            DB::rollBack();
            logger($e->getMessage());
            return back()->with('error', 'Error in creating package, please try again letter');
        }
    }

    public function update(Request $request, Service $service)
    {
        try {
            DB::beginTransaction();

           $validatedData = $request->validate([
                'name' => 'required|string',
                'description' => 'sometimes',
                'price' => 'required|numeric',
            ]);

            $service->update($validatedData);

            DB::commit();

            return redirect()->route('admin.package.index')
                            ->with('success', 'Service updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e->getMessage());
            return back()->with('error', 'Error in updating package, please try again later');
        }
    }


    public function destroy(Service $service){
        try{
            $service->delete();
            return back()->with('success', 'Service deleted successfully');
        }catch(Exception $e){
            return back()->with(['error', 'Service was not deleted'], 500);
        }
    }
}
