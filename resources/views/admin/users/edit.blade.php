@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.users.update", [$user->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">{{ trans('cruds.user.fields.name') }}<span style="color: red;">*</span></label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($user) ? $user->name : '') }}" required>
                @if($errors->has('name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                <label for="email">{{ trans('cruds.user.fields.email') }}<span style="color: red;">*</span></label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', isset($user) ? $user->email : '') }}" required>
                @if($errors->has('email'))
                    <em class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.email_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('mobile') ? 'has-error' : '' }}">
                <label for="mobile">Mobile<span style="color: red;">*</span></label>
                <input type="tel" id="mobile" name="mobile" maxlength="10" class="form-control" value="{{ old('mobile', isset($user) ? $user->mobile : '') }}" required>
                @if($errors->has('mobile'))
                    <em class="invalid-feedback">
                        {{ $errors->first('mobile') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.mobile_helper') }}
                </p>
            </div>
            <?php  
                $stateIds = explode(',',$user->state_id);
                $cityIds  = explode(',',$user->city_id);
                // $stateIds = implode(',',$stateIds)
            ?>
            <div class="form-group {{ $errors->has('state_id') ? 'has-error' : '' }}">
                <label for="permissions">State*
                <span class="btn btn-info btn-xs select-all">Select All</span>
                <span class="btn btn-info btn-xs deselect-all">Desellect All</span></label>
                <select name="state_id[]" id="permissions" class="form-control select2" multiple="multiple">
                    @foreach($states as $id => $state)
                        <option value="{{ $state->id }}" {{ (in_array($state->id,$stateIds)) ? 'selected' : '' }}>{{ $state->name }}</option>
                    @endforeach
                </select>
                @if($errors->has('state_id'))
                    <em class="invalid-feedback">
                        {{ $errors->first('state_id') }}
                    </em>
                @endif
            </div>
            
            <div class="form-group {{ $errors->has('city_id') ? 'has-error' : '' }}">
                <label for="permissions">City*
                <span class="btn btn-info btn-xs select-all">Select All</span>
                <span class="btn btn-info btn-xs deselect-all">Desellect All</span></label>
                <select name="city_id[]" id="city_id" class="form-control select2" multiple="multiple">
                    @foreach($cities as $id => $city)
                        <option value="{{ $city->id }}" {{ (in_array($city->id, $cityIds)) ? 'selected' : '' }}>{{ $city->city }} ({{$city->code}})</option>
                    @endforeach
                </select>
                @if($errors->has('city_id'))
                    <em class="invalid-feedback">
                        {{ $errors->first('city_id') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                <label for="status" style="margin-top: 12px;">Status<span style="color: red;">*</span></label>
                <select name="status" id="status" class="form-control select2" required>
                    <option value="active" @if(@$user->status == "active") selected @endif>Active</option>
                    <option value="inactive" @if(@$user->status == "inactive") selected @endif>Inactive</option>
                </select>
                @if($errors->has('status'))
                    <em class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                <label for="password">{{ trans('cruds.user.fields.password') }}</label>
                <input type="password" id="password" name="password" class="form-control">
                @if($errors->has('password'))
                    <em class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.password_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                <label for="roles">{{ trans('cruds.user.fields.roles') }}<span style="color: red;">*</span>
                    <span class="btn btn-info btn-xs select-all">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all">{{ trans('global.deselect_all') }}</span></label>
                <select name="roles[]" id="roles" class="form-control select2" multiple="multiple" required>
                    @foreach($roles as $id => $roles)
                        <option value="{{ $id }}" {{ (in_array($id, old('roles', [])) || isset($user) && $user->roles->contains($id)) ? 'selected' : '' }}>{{ $roles }}</option>
                    @endforeach
                </select>
                @if($errors->has('roles'))
                    <em class="invalid-feedback">
                        {{ $errors->first('roles') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.user.fields.roles_helper') }}
                </p>
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div>
@endsection
