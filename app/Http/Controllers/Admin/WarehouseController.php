<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warehouse;

class WarehouseController extends Controller
{

    public function index()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.warehouses.index');

        $warehouses = Warehouse::orderBy('name')->withTrashed()->get();
        return view('admin.warehousing.warehouses.all',compact('warehouses'));

    }


    public function create()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.warehouses.create');

        return view('admin.warehousing.warehouses.create');
    }


    public function store(Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.warehouses.create');

        $request->validate(
            [
                'name' => ['required','max:255','unique:warehouses'],
                'description' => ['nullable','max:255']
            ],
            [
                'name.required' => 'نام انبار الزامی است.',
                'name.unique' => 'نام انبار تکراری است.',
                'name.max' => 'حداکثر  طول مجاز نام انبار 255 کارکتر می باشد.',
                'description.max' => 'حداکثر  طول مجاز توضیحات انبار 255 کارکتر می باشد.',
            ]);

        if(in_array($request->status,[Status::Active,Status::Deactive])){
            Warehouse::create([
                'name'=>$request->name,
                'description'=>$request->description,
                'status'=>$request->status,
            ]);

            toast('انبار جدید افزوده شد.','success')->position('bottom-end');
        }
        return redirect(route('admin.warehousing.warehouses.index'));
    }


    public function edit(Warehouse $warehouse)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.warehouses.edit');

        return view('admin.warehousing.warehouses.edit',compact('warehouse'));
    }


    public function update(Request $request, Warehouse $warehouse)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.warehouses.edit');

        $request->validate(
            [
                'name' => ['required','max:255','unique:warehouses,name,'.$warehouse->id],
                'description' => ['nullable','max:255']
            ],
            [
                'name.required' => 'نام انبار الزامی است.',
                'name.unique' => 'نام انبار تکراری است.',
                'name.max' => 'حداکثر  طول مجاز نام انبار 255 کارکتر می باشد.',
                'description.max' => 'حداکثر  طول مجاز توضیحات انبار 255 کارکتر می باشد.',
            ]);


        if(in_array($request->status,[Status::Active,Status::Deactive])){
            $warehouse->update(['name'=>$request->name,
                                'description'=>$request->description,
                                 'status'=>$request->status,]);

            toast('بروزرسانی انجام شد.','success')->position('bottom-end');
        }
        return redirect(route('admin.warehousing.warehouses.index'));

    }

    public function destroy( Warehouse $warehouse)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.warehouses.destroy');
        $warehouse->delete();
        toast('انبار مورد نظر حذف شد.','error')->position('bottom-end');
        return back();
    }

    public function recycle($id)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.warehouses.recycle');
        $warehouse = Warehouse::withTrashed()->find($id);
        $warehouse->restore();
        toast('انبار مورد نظر بازیابی شد.','error')->position('bottom-end');
        return back();
    }
}
