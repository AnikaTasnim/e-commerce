@extends('layouts.storefront')

@section('title', 'Store admin sign in')

@section('content')
<main class="admin-login-page"><form class="admin-login-card" method="POST" action="{{ route('admin.login.store') }}">@csrf<span class="eyebrow">STORE MANAGEMENT</span><h1>Admin <em>sign in.</em></h1><p>Sign in to manage delivery options.</p>@if ($errors->any())<div class="form-error-summary">{{ $errors->first() }}</div>@endif<label for="email">Email address</label><input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus><label for="password">Password</label><input id="password" type="password" name="password" autocomplete="current-password" required><button class="button button-dark" type="submit">Sign in <span>↗</span></button></form></main>
@endsection
