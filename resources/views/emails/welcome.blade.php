@component('mail::message')
# Welcome to Quiz App!

Hello {{ $userDetails['username'] }},

Thank you for registering with us. Here are your details:

- **Username (Full Name):** {{ $userDetails['username'] }}
- **Email:** {{ $userDetails['email'] }}
- **City:** {{ $userDetails['city'] }}
- **Mobile Number:** {{ $userDetails['mobile_number'] }}
- **Your OTP:** {{ $userDetails['otp'] }} (This is valid for 5 minutes. Use it to verify your account.)

Please enter this OTP on the verification screen to start your exam.

If you didn't register, please ignore this email.

Thanks,<br>
Quiz App Team
@endcomponent