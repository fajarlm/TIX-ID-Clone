@extends('templates.app')
@section('content')
    {{-- <div class="container pt-5"> --}}
    <form class="d-block mx-auto my-5 w-75 " method="POST" action="{{ route('signup.store') }}">
        {{-- csrf token kunci agar data form bisa di akases oleh akses/controller  --}}
        @csrf

        <!-- 2 column grid layout with text inputs for the first and last names -->
        <div class="row mb-4">
            <div class="col">
                <div data-mdb-input-init class="form-outline">
                    <input type="text" name="first_name" value="{{ old('first_name') }}" id="input-first-name"
                    class="form-control @error('email') is-invalid @enderror" />
                    <label class="form-label" for="input-first-name">First name</label>
                </div>
                @error('first_name')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                @enderror
            </div>

            <div class="col">
                <div data-mdb-input-init class="form-outline">
                    <input type="text" value="{{ old('last_name') }}" name="last_name" id="input-last-name"
                    class="form-control @error('last_name') is-invalid @enderror" />
                    <label class="form-label" for="input-last-name">Last name</label>
                </div>
                @error('last_name')
                    <small class="text-danger">
                        {{ $message }}
                    </small>
                @enderror
            </div>

        </div>

        <!-- Email input -->
        <div data-mdb-input-init class="form-outline mb-4">
            <input type="email" value="{{ old('email') }}" name="email" id="input-email"
            class="form-control @error('email')is-invalid @enderror" />
            <label class="form-label" for="input-email">Email address</label>
            @error('email')
                <small class="text-danger">
                    {{ $message }}
                </small>
            @enderror
        </div>

        <!-- Password input -->
        <div data-mdb-input-init class="form-outline mb-4">
            <input type="password" name="password" value="{{ old('password') }}" id="input-password" class="form-control  @error('password')is-invalid @enderror" />
            <label class="form-label" for="input-password">Password</label> 
            @error('password')
                <small class="text-danger">
                    {{ $message }}
                </small>
            @enderror
        </div>

        <!-- Checkbox -->
        <div class="form-check d-flex justify-content-center mb-4">
            <input class="form-check-input me-2" type="checkbox" value="" id="form2Example33" checked />
            <label class="form-check-label" for="form2Example33">
                Subscribe to our newsletter
            </label>
        </div>

        <!-- Submit button -->
        <button data-mdb-ripple-init type="submit" class="btn btn-primary btn-block mb-4">Sign up</button>

        <!-- Register buttons -->
        <div class="text-center">
            <p>or sign up with:</p>
            <button data-mdb-ripple-init type="button" class="btn btn-secondary btn-floating mx-1">
                <i class="fab fa-facebook-f"></i>
            </button>

            <button data-mdb-ripple-init type="button" class="btn btn-secondary btn-floating mx-1">
                <i class="fab fa-google"></i>
            </button>

            <button data-mdb-ripple-init type="button" class="btn btn-secondary btn-floating mx-1">
                <i class="fab fa-twitter"></i>
            </button>

            <button data-mdb-ripple-init type="button" class="btn btn-secondary btn-floating mx-1">
                <i class="fab fa-github"></i>
            </button>
        </div>
    </form>
    {{-- </div> --}}
@endsection
