<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::all();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');
        $states = getAllState();
        $cities = getAllCities();
        // dd($cities);
        return view('admin.users.create', compact('roles','states','cities'));
    }

    public function store(StoreUserRequest $request)
    {
        $inputs = $request->all();
        if(!empty($request->state_id))
        {
            $inputs['state_id'] = implode(',',$request->state_id);
        }
        if(!empty($request->city_id))
        {
            $inputs['city_id']  = implode(',',$request->city_id);
        }
        $user = User::create($inputs);
        $user->roles()->sync($request->input('roles', []));

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');

        $user->load('roles');

        $states = getAllState();
        $cities = getAllCities();
        // dd($user);

        return view('admin.users.edit', compact('roles', 'user','states','cities'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $inputs = $request->all();
        if(!empty($request->state_id))
        {
            $inputs['state_id'] = implode(',',$request->state_id);
        }else
        {
            $inputs['state_id'] = null;
        }
        if(!empty($request->city_id))
        {
            $inputs['city_id']  = implode(',',$request->city_id);
        }else{
            $inputs['city_id'] = null;
        }
        $user->update($inputs);
        $user->roles()->sync($request->input('roles', []));

        return redirect()->route('admin.users.index');
    }

    public function show(User $user)
    {
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles');

        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();

        return back();
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        User::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
