@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Create Transaction
    </div>

    <div class="card-body">
        <form action="{{ route('admin.transactions.store') }}" onSubmit="return confirm('Please verify before submit.')" method="POST">
            @csrf
            <div class="form-group {{ $errors->has('user_id') ? 'has-error' : '' }}">
                <label for="name">Name<span style="color: red;">*</span></label>
                <select name="user_id" id="status" class="form-control select2" required>
                    <option value="">Please Select</option>
                    @foreach($users as $user)
                    <option value="<?php echo $user->user_id.'_'.$user->id;?>">{{$user->store_name}} ({{$user->city_name}})</option>
                    @endforeach
                </select>
                @if($errors->has('name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('transaction_number') ? 'has-error' : '' }}">
                <label for="mobile">Transaction Number<span style="color: red;">*</span></label>
                <input type="text" id="transaction_number" name="transaction_number" class="form-control" value="" required>
                @if($errors->has('transaction_number'))
                    <em class="invalid-feedback">
                        {{ $errors->first('transaction_number') }}
                    </em>
                @endif
            </div>
            <div class="form-group {{ $errors->has('transaction_type') ? 'has-error' : '' }}">
                <label for="transaction_type">Transaction Method<span style="color: red;">*</span></label>
                <input type="text" id="transaction_type" name="transaction_type" class="form-control" value="" required>
                @if($errors->has('transaction_type'))
                    <em class="invalid-feedback">
                        {{ $errors->first('transaction_type') }}
                    </em>
                @endif
            </div>
            <div class="form-group">
                <label for="total_amount">Type<span style="color: red;">*</span></label>
                <select name="type" id="type" class="form-control select2" required>
                    <option value="">Select Type</option>
                    <option value="debit">Debit</option>
                    <option value="credit">Credit</option>
                </select>
            </div>
            <div class="form-group {{ $errors->has('amount') ? 'has-error' : '' }}">
                <label for="amount">Amount<span style="color: red;">*</span></label>
                <input type="text" name="amount" class="form-control" required>
                @if($errors->has('amount'))
                    <em class="invalid-feedback">
                        {{ $errors->first('amount') }}
                    </em>
                @endif
            </div>
            <!-- <div class="form-group {{ $errors->has('total_amount') ? 'has-error' : '' }}">
                <label for="total_amount">Outstanding<span style="color: red;">*</span></label>
                <input type="text" name="total_amount" class="form-control" required>
                @if($errors->has('total_amount'))
                    <em class="invalid-feedback">
                        {{ $errors->first('total_amount') }}
                    </em>
                @endif
            </div> -->
            <!-- <div class="form-group {{ $errors->has('received_amount') ? 'has-error' : '' }}">
                <label for="received_amount">Received Amount<span style="color: red;">*</span></label>
                <input type="text" id="received_amount" name="received_amount" class="form-control" value="" required>
                @if($errors->has('received_amount'))
                    <em class="invalid-feedback">
                        {{ $errors->first('received_amount') }}
                    </em>
                @endif
            </div> -->
            <div class="form-group {{ $errors->has('remark') ? 'has-error' : '' }}">
                <label for="remark">Remark<span style="color: red;">*</span></label>
                <input type="text" name="remark" class="form-control" required>
                @if($errors->has('remark'))
                    <em class="invalid-feedback">
                        {{ $errors->first('remark') }}
                    </em>
                @endif
            </div>
            <a class="btn btn-default my-2" href="{{ route('admin.tickets.index') }}">
            Back
            </a>
            <div>
                <input class="btn btn-danger" type="submit" value="Save">
            </div>
        </form>


    </div>
</div>
@endsection
