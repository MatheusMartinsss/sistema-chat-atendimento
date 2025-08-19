@extends('layout')

@section('title','Login')

@section('content')
<div class="row justify-content-center">
  <div class="col-md-5 col-lg-4">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h4 class="mb-4 text-center">Acessar Painel</h4>

        @if ($errors->any())
          <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ url('/login') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label" for="email">E-mail</label>
            <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
          </div>
          <div class="mb-3">
            <label class="form-label" for="password">Senha</label>
            <input class="form-control" id="password" type="password" name="password" required>
          </div>
          <button class="btn btn-primary w-100" type="submit">Entrar</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection