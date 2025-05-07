<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\WarehouseStock;
use Illuminate\Http\Request;
use App\Models\Warehouse;
use Auth;

class WarehouseController extends Controller
{

    public function index()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.warehouses.index');


        $warehouses = Warehouse::with('admins')->orderBy('name')->withTrashed()->get();
        return view('admin.warehousing.warehouses.all',compact('warehouses'));

    }


    public function create()
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.warehouses.create');

        $admins = Admin::where('status',Status::Active)->orderBy('fullname','desc')->get();

        return view('admin.warehousing.warehouses.create',compact('admins'));
    }


    public function store(Request $request)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.warehouses.create');

        $request->validate(
            [
                'name' => ['required','max:255','unique:warehouses'],
                'description' => ['nullable','max:255'],
                "admins"=>['required'],
            ],
            [
                'name.required' => 'نام انبار الزامی است.',
                'name.unique' => 'نام انبار تکراری است.',
                'name.max' => 'حداکثر  طول مجاز نام انبار 255 کارکتر می باشد.',
                'description.max' => 'حداکثر  طول مجاز توضیحات انبار 255 کارکتر می باشد.',
                'admins.required' => 'انتخاب مسئولین انبار الزامی است.',
            ]);

        if(in_array($request->status,[Status::Active,Status::Deactive])){
           $warehouse = Warehouse::create([
                            'name'=>$request->name,
                            'description'=>$request->description,
                            'status'=>$request->status]);

           $warehouse->admins()->sync($request->admins);

            toast('انبار جدید افزوده شد.','success')->position('bottom-end');
        }
        return redirect(route('admin.warehousing.warehouses.index'));
    }


    public function edit(Warehouse $warehouse)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.warehouses.edit');

        $admins = Admin::where('status',Status::Active)->orderBy('fullname','desc')->get();

        return view('admin.warehousing.warehouses.edit',compact('warehouse','admins'));
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

            if($warehouse->id != 1){
                $warehouse->name = $request->name;
                $warehouse->status = $request->status;
            }

            $warehouse->description = $request->description;
            $warehouse->save();

            $warehouse->admins()->sync($request->admins);

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

    public function stocks(Warehouse $warehouse)
    {
        //اجازه دسترسی
        config(['auth.defaults.guard' => 'admin']);
        $this->authorize('warehousing.warehouses.stocks');

        if (!in_array(Auth::guard('admin')->id(),$warehouse->adminsArrayId())){
            abort(403);
        }

        $stocks = WarehouseStock::where('warehouse_id',$warehouse->id)
                                    ->orderBy('created_at')
                                    ->paginate(10)
                                    ->withQueryString();

        return view('admin.warehousing.warehouses.stock',compact('stocks','warehouse'));
    }

}
