@extends('templates.app')

@section('content')
    <form method="POST" action="{{ route('login.auth') }}" class="w-75 d-block mx-auto my-5">
        {{-- csrf token kunci agar data form bisa di akases oleh akses/controller  --}}
        @csrf
        <div class="container pt-5">
            @if (Session::get('success'))
                <div class="alert alert-success">
                    {{ Session('success') }}
                </div>
            @endif

            @if (Session::get('error'))
                <div class="alert alert-danger">
                    {{ Session('error') }}
                </div>
            @endif

            <!-- Email input -->
            @error('email')
                <small class="text-danger">{{ $message }}</small>
            @enderror
            <div data-mdb-input-init class="form-outline mb-4">
                <input type="email" name="email" id="Email" class="form-control @error('email') is-invalid @enderror"
                    placeholder="Enter a valid email address" />
                <label class="form-label" for="Email">Email address</label>
            </div>

            <!-- Password input -->
            @error('password')
                <small class="text-danger">{{ $message }}</small>
            @enderror
            <div data-mdb-input-init class="form-outline mb-4">
                <input type="password" name="password" id="password"
                    class="form-control @error('password') is-invalid @enderror" placeholder="Enter password" />
                <label class="form-label" for="password">Password</label>
                <span class="position-absolute top-50 end-0 translate-middle-y me-3" style="cursor:pointer;"
                    onclick="togglePassword()">
                    <i id="togglePasswordIcon" class="bi bi-eye-slash"></i>
                </span>
            </div>

            <!-- 2 column grid layout for inline styling -->
            <div class="row mb-4">
                <div class="col d-flex justify-content-center">
                    <!-- Checkbox -->
                    <div class="form-check">
                        <input class="form-check-input " type="checkbox" value="" id="form2Example34" checked />
                        <label class="form-check-label" for="form2Example34"> Remember me </label>
                    </div>
                </div>

                <div class="col">
                    <!-- Simple link -->
                    <a href="#!">Forgot password?</a>
                </div>
            </div>

            <!-- Submit button -->
            <button data-mdb-ripple-init type="submit" class="btn btn-primary btn-block mb-4">Sign in</button>

            <!-- Register buttons -->
            <div class="text-center">
                <p>Not a member? <a href="#!">Register</a></p>
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
    </div>
@endsection

@push('script')
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        }
    </script>
@endpush
