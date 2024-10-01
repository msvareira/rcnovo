@extends('layouts.main')

@section('title', 'Cadastro de Contatos')
@section('breadcrumb-item', 'Contatos')

@section('breadcrumb-item-active', 'Cadastro de Contatos')

@section('css')
@endsection

@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Form controls</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <form name="form" method="POST" action="{{ route('contatos.store') }}">

                                @isset($contato->id)
                                    <input type="hidden" name="id" value="{{ $contato->id }}">
                                @endisset
                                @csrf

                                <div class="form-group">
                                    <label class="form-label">Nome</label>
                                    <input type="text" class="form-control @error('nome') is-invalid @enderror"
                                        id="nome" name="nome" required value="{{ $contato->nome }}">

                                    @error('nome')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                </div>

                                <div class="form-group">
                                    <label class="form-label">Telefone 1</label>
                                    <input type="text" class="form-control fone @error('fone1') is-invalid @enderror"
                                        id="fone1" name="fone1" required value="{{ $contato->fone1 }}">
                                    @error('fone1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Telefone 2</label>
                                    <input type="text" class="form-control fone @error('fone2') is-invalid @enderror"
                                        id="fone2" name="fone2" value="{{ $contato->fone2 }}">
                                    @error('fone2')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" required value="{{ $contato->email }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Cliente</label>
                                    <select class="form-control @error('cliente_id') is-invalid @enderror" id="cliente_id"
                                        name="cliente_id" required>
                                        @foreach ($clientes as $cliente)
                                            <option value="{{ $cliente->id }}"
                                                {{ $contato->cliente_id == $cliente->id ? 'selected' : '' }}>
                                                {{ $cliente->razao }}</option>
                                        @endforeach
                                    </select>
                                    @error('cliente_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary mb-4">Salvar</button>
                            </form>

                            <script>
                                // Example starter JavaScript for disabling form submissions if there are invalid fields
                                (function() {
                                    'use strict';
                                    window.addEventListener(
                                        'load',
                                        function() {
                                            // Fetch all the forms we want to apply custom Bootstrap validation styles to
                                            var forms = document.getElementsByClassName('needs-validation');
                                            // Loop over them and prevent submission
                                            var validation = Array.prototype.filter.call(forms, function(form) {
                                                form.addEventListener(
                                                    'submit',
                                                    function(event) {
                                                        if (form.checkValidity() === false) {
                                                            event.preventDefault();
                                                            event.stopPropagation();
                                                        }
                                                        form.classList.add('was-validated');
                                                    },
                                                    false
                                                );
                                            });
                                        },
                                        false
                                    );
                                })();
                            </script>
                        </div>
                    </div>
                </div>
            </div>

        @endsection

        @section('scripts')

            <script src="{{ asset('assets/contatos/contatos.js') }}"></script>

        @endsection
