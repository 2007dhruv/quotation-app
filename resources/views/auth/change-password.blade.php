<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Quotation App</title>
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
        .container {
            width: 100%;
            max-width: 450px;
        }
        .card { 
            background: #fff; 
            border-radius: 12px; 
            box-shadow: 0 20px 60px rgba(0,0,0,0.3); 
            padding: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 { 
            font-size: 28px; 
            color: #333; 
            margin-bottom: 8px; 
        }
        .header p {
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
        .success-message {
            color: #10b981;
            font-size: 12px;
            margin-top: 6px;
            padding: 12px;
            background: #ecfdf5;
            border-radius: 6px;
            border-left: 4px solid #10b981;
        }
        .password-hint {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
        }
        .button-group {
            display: flex;
            gap: 12px;
            margin-top: 30px;
        }
        .btn { 
            flex: 1;
            padding: 14px 24px; 
            border: none;
            border-radius: 8px; 
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-primary { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        .btn-primary:active {
            transform: translateY(0);
        }
        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }
        .btn-secondary:hover {
            background: #d1d5db;
        }
        .divider {
            text-align: center;
            margin: 20px 0;
            color: #9ca3af;
        }
        .back-link {
            text-align: center;
            margin-top: 16px;
        }
        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
        }
        .back-link a:hover {
            color: #764ba2;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1>Change Password</h1>
                <p>Update your account password</p>
            </div>

            <form action="{{ route('change-password') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input 
                        type="password" 
                        id="current_password" 
                        name="current_password" 
                        required
                        placeholder="Enter your current password"
                        class="@error('current_password') is-invalid @enderror"
                    >
                    @error('current_password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input 
                        type="password" 
                        id="new_password" 
                        name="new_password" 
                        required
                        placeholder="Enter your new password"
                        class="@error('new_password') is-invalid @enderror"
                    >
                    <div class="password-hint">Minimum 8 characters</div>
                    @error('new_password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation">Confirm New Password</label>
                    <input 
                        type="password" 
                        id="new_password_confirmation" 
                        name="new_password_confirmation" 
                        required
                        placeholder="Confirm your new password"
                        class="@error('new_password_confirmation') is-invalid @enderror"
                    >
                    @error('new_password_confirmation')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>

            <div class="back-link">
                <a href="/">← Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>
