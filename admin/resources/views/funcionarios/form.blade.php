@extends('layouts.main')

@section('title', 'Cadastro de Funcionários')
@section('breadcrumb-item', 'Funcionários')

@section('breadcrumb-item-active', 'Cadastro de Funcionários')

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
                  <form name="form" method="POST" action="{{ route('funcionarios.store') }}">

                    @isset($funcionario->id)
                      <input type="hidden" name="id" value="{{ $funcionario->id }}">                                              
                    @endisset
                    @csrf                 

                    <div class="form-group">
                      <label class="form-label" for="nome">Nome</label>
                      <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" required value="{{ $funcionario->nome }}">
                      @error('nome')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror 

                    </div>
                    
                    <div class="form-group">
                      <label class="form-label" for="rg">RG</label>
                      <input type="text" class="form-control @error('rg') is-invalid @enderror" id="rg" name="rg" value="{{ $funcionario->rg }}">
                      @error('rg')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror 
                    </div>

                    <div class="form-group">
                      <label class="form-label" for="cpf">CPF</label>
                      <input type="text" class="form-control cpf @error('cpf') is-invalid @enderror" id="cpf" name="cpf" data-mask="000.000.000-00" value="{{ $funcionario->cpf }}">
                      @error('cpf')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror 
                    </div>

                    <div class="form-group">
                      <label class="form-label" for="carta_motorista">Carta Motorista</label>
                      <input type="text" class="form-control @error('carta_motorista') is-invalid @enderror" id="carta_motorista" name="carta_motorista" value="{{ $funcionario->carta_motorista }}">
                      @error('carta_motorista')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror 
                    </div>

                    <div class="form-group">
                      <label class="form-label" for="carga_horaria_dia">Carga horária</label>
                      <input type="number" class="form-control @error('carga_horaria_dia') is-invalid @enderror" id="carga_horaria_dia" name="carga_horaria_dia" value="{{ $funcionario->carga_horaria_dia }}">
                      @error('carga_horaria_dia')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror 
                    </div>

                    <div class="form-group">
                      <label class="form-label" for="valor_hora_extra">Valor Hora Extra</label>
                      <input type="text" class="form-control currency @error('valor_hora_extra') is-invalid @enderror" min="0" id="valor_hora_extra" required name="valor_hora_extra" value="{{  \App\Http\Helpers\Utils::formatarnumero($funcionario->valor_hora_extra) }}">
                      @error('valor_hora_extra')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror 
                    </div>

                    <div class="form-group">
                      <label class="form-label" for="salario">Salário</label>
                      <input type="text" class="form-control currency @error('salario') is-invalid @enderror" id="salario" min="0" required name="salario" value="{{ \App\Http\Helpers\Utils::formatarnumero($funcionario->salario) }}">
                      @error('salario')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror 
                    </div>

                    <div class="form-group">
                      <label class="form-label" for="cod_cracha">Cracha</label>
                      <input type="text" class="form-control @error('cod_cracha') is-invalid @enderror" id="cod_cracha" name="cod_cracha" value="{{ $funcionario->cod_cracha }}">
                      @error('cod_cracha')
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
  <script src="{{ asset('assets/funcionarios/funcionarios.js') }}"></script>
@endsection