<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Quotation App</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh; 
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
        }
        .login-card { 
            background: #fff; 
            border-radius: 12px; 
            box-shadow: 0 20px 60px rgba(0,0,0,0.3); 
            padding: 40px;
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo h1 { 
            font-size: 28px; 
            color: #333; 
            margin-bottom: 8px; 
        }
        .logo p {
            color: #666;
            font-size: 14px;
        }
        .form-group { 
            margin-bottom: 20px; 
        }
        .form-group label { 
            display: block; 
            font-size: 14px; 
            font-weight: 500; 
            color: #374151; 
            margin-bottom: 6px; 
        }
        .form-group input { 
            width: 100%; 
            padding: 12px 16px; 
            border: 2px solid #e5e7eb; 
            border-radius: 8px; 
            font-size: 14px; 
            transition: all 0.3s;
        }
        .form-group input:focus { 
            outline: none; 
            border-color: #667eea; 
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .form-group input.is-invalid {
            border-color: #ef4444;
        }
        .error-message {
            color: #ef4444;
            font-size: 12px;
            margin-top: 6px;
        }
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        .remember-me input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #667eea;
        }
        .remember-me span {
            font-size: 14px;
            color: #6b7280;
        }
        .btn-login { 
            width: 100%;
            padding: 14px 24px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff; 
            border: none;
            border-radius: 8px; 
            font-size: 16px; 
            font-weight: 600; 
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-login:hover { 
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        .btn-login:active {
            transform: translateY(0);
        }
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-danger {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo">
                <h1>Quotation App</h1>
                <p>Sign in to your account</p>
            </div>

            @if(session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                        placeholder="Enter your email"
                        required 
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                        placeholder="Enter your password"
                        required
                    >
                </div>

                <div class="remember-forgot">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Remember me</span>
                    </label>
                </div>

                <button type="submit" class="btn-login">Sign In</button>
            </form>
        </div>
    </div>
</body>
</html>
