@extends('layouts.main')

@section('title', 'Cadastro de Preventivas')
@section('breadcrumb-item', 'Preventivas')
@section('breadcrumb-item-active', 'Cadastro de Preventivas')

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
            <form name="form" method="POST" action="{{ route('preventivas.store') }}">

              @isset($preventiva->id)
                <input type="hidden" name="id" value="{{ $preventiva->id }}">                                              
              @endisset
              @csrf                 

              <div class="form-group">
                <label class="form-label" >Cliente</label>
                <select class="form-control select2 @error('cliente_id') is-invalid @enderror" id="cliente_id" name="cliente_id" required>
                  <option value="" {{ is_null($preventiva->cliente_id) ? 'selected' : '' }}>Selecione um cliente</option>
                  @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}" {{ $preventiva->cliente_id == $cliente->id ? 'selected' : '' }}>{{ $cliente->razao }}</option>
                  @endforeach
                </select>
                @error('cliente_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror                          
              </div>                      

              <div class="form-group">
                <label class="form-label" >Funcionário</label>
                <select class="form-control select2 @error('funcionario_id') is-invalid @enderror" id="funcionario_id" name="funcionario_id">
                  <option value="" {{ is_null($preventiva->funcionario_id) ? 'selected' : '' }}>Selecione um funcionário</option>
                  @foreach($funcionarios as $funcionario)
                    <option value="{{ $funcionario->id }}" {{ $preventiva->funcionario_id == $funcionario->id ? 'selected' : '' }}>{{ $funcionario->nome }}</option>
                  @endforeach
                </select>
                @error('funcionario_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror                          
              </div>

              <div class="form-group">
                <label class="form-label" >Prazo (dias)</label>
                <input type="number" min="1" class="form-control @error('prazo') is-invalid @enderror" id="prazo" name="prazo" value="{{ $preventiva->prazo ?? '' }}" required>
                @error('prazo')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror                          
              </div>

              <div class="form-group">
                <label class="form-label" >Primeira/Próxima Data</label>
                <input type="date" class="form-control @error('data_execucao') is-invalid @enderror" id="data_execucao" name="data_execucao" value="{{ $preventiva->data_execucao ?? '' }}" required>
                @error('data_execucao')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror                          
              </div>

              <div class="form-group">
                <label class="form-label" >Descrição</label>
                <textarea class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao" required>{{ $preventiva->descricao }}</textarea>
                @error('descricao')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror                          
              </div>

              <button type="submit" class="btn btn-primary mb-4">Salvar</button>
            </form>

          <script>
            // Example starter JavaScript for disabling form submissions if there are invalid fields
            (function () {
            'use strict';
            window.addEventListener(
              'load',
              function () {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function (form) {
                form.addEventListener(
                  'submit',
                  function (event) {
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
<script src="{{ asset('assets/preventivas/preventivas.js') }}"></script>
@endsection