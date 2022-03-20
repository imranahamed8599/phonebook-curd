@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div>
                @if(Session::has('message-success'))
                    <p class="bg-success text-center text-white p-2">{{Session::get('message-success')}}</p>
                @elseif(Session::has('message-danger'))
                    <p class="bg-success text-center text-white p-2">{{Session::get('message-danger')}}</p>
                @endif
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col align-self-start">
                            <h4>Contacts</h4>
                        </div>
                        <div class="col d-flex justify-content-end">
                            <a href="{{route('phonebook.create')}}" class="btn btn-primary btn-sm">Add New</a>
                        </div>
                    </div>    
                </div>

                <div class="card-body">
                    <!-- @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif -->

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Location</th>
                                <th scope="col">Photo</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $n = 0; @endphp
                            @foreach ($contacts as $contact)
                                <tr>
                                    <th scope="row">{{++$n}}</th>
                                    <td>{{$contact->name}}</td>
                                    <td>{{$contact->phone}}</td>
                                    <td>{{$contact->email}}</td>
                                    <td>{{$contact->location}}</td>
                                    <td>
                                        <img src="{{asset($contact->photo)}}" class="rounded float-right" width="50" height="50" alt="Image">
                                    </td>
                                    <td>
                                        <table>
                                            <tr>
                                                <td>
                                                    <a href="{{route('phonebook.edit'  , $contact->id)}}" class="btn btn-primary btn-sm">Edit</a>
                                                </td>
                                                <td>
                                                    <form action="{{ route('phonebook.destroy', $contact->id) }}" method="POST">
                                                        @method('DELETE')
                                                        @csrf
                                                        <button class="btn btn-danger btn-sm">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
