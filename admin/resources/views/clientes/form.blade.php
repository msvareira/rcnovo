@extends('layouts.main')

@section('title', 'Cadastro de Clientes')
@section('breadcrumb-item', 'Clientes')

@section('breadcrumb-item-active', 'Cadastro de Clientes')

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
                  <form name="form" method="POST" action="{{ route('clientes.store') }}">

                    @isset($cliente->id)
                      <input type="hidden" name="id" value="{{ $cliente->id }}">                                              
                    @endisset
                    @csrf                 

                    <div class="form-group">
                      <label class="form-label" >Razão Social/Nome</label>
                      <input type="text" class="form-control @error('razao') is-invalid @enderror" id="razao" name="razao" required value="{{ $cliente->razao }}">
                        
                        @error('razao')
                          <div class="invalid-feedback">{{ $message }}</div>
                        @enderror                          
                      
                    </div>                      

                    <div class="form-group">
                      <label class="form-label" >CPF/CNPJ</label>
                      <input type="text" class="form-control cpf_cnpj  @error('razao') is-invalid @enderror" id="cpf_cnpj" name="cpf_cnpj" required value="{{ $cliente->cpf_cnpj }}">
                        
                      @error('cpf_cnpj')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror                          

                    </div>

                    <div class="form-group">
                      <label class="form-label" >RG/IE</label>
                      <input type="text" class="form-control @error('rg_ie') is-invalid @enderror" id="rg_ie" name="rg_ie" value="{{ $cliente->rg_ie }}" >                          
                      @error('rg_ie')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror                          
                    </div>

                    <div class="form-group">
                      <label class="form-label" >Email</label>
                      <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" required value="{{ $cliente->email }}">
                      @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror                          
                    </div>

                    <div class="form-group">
                      <label class="form-label" >CEP</label>
                      <input type="text" class="form-control cep  @error('cep') is-invalid @enderror" id="cep" name="cep" required value="{{ $cliente->cep }}">
                      @error('cep')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror                          
                    </div>

                    <div class="form-group">
                      <label class="form-label" >Estado</label>
                      <input type="text" class="form-control @error('estado') is-invalid @enderror" id="estado" name="estado" value="{{ $cliente->estado }}">
                      @error('estado')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror                          
                    </div>

                    <div class="form-group">
                      <label class="form-label" >Cidade</label>
                      <input type="text" class="form-control @error('cidade') is-invalid @enderror" id="cidade" name="cidade" value="{{ $cliente->cidade }}">
                      @error('cidade')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror                          
                    </div>

                    <div class="form-group">
                      <label class="form-label" >Bairro</label>
                      <input type="text" class="form-control @error('bairro') is-invalid @enderror" id="bairro" name="bairro" value="{{ $cliente->bairro }}">
                      @error('bairro')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror                          
                    </div>

                    <div class="form-group">
                      <label class="form-label" >Rua</label>
                      <input type="text" class="form-control @error('rua') is-invalid @enderror" id="rua" name="rua" value="{{ $cliente->rua }}">
                      @error('rua')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror                          
                    </div>

                    <div class="form-group">
                      <label class="form-label" >Número</label>
                      <input type="text" class="form-control @error('numero') is-invalid @enderror" id="numero" name="numero" value="{{ $cliente->numero }}">
                      @error('numero')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror                          
                    </div>

                    <div class="form-group">
                      <label class="form-label" >Complemento</label>
                      <input type="text" class="form-control @error('complemento') is-invalid @enderror" id="complemento" name="complemento" value="{{ $cliente->complemento }}">
                      @error('complemento')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror                          
                    </div>

                    <div class="form-group">
                      <label class="form-label" >Código IBGE</label>
                      <input type="text" class="form-control @error('cod_ibge') is-invalid @enderror" id="cod_ibge" name="cod_ibge" value="{{ $cliente->cod_ibge }}">
                      @error('cod_ibge')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror                          
                    </div>

                    <div class="form-group">
                      <label class="form-label" >Telefone 1</label>
                      <input type="text" class="form-control fone @error('fone') is-invalid @enderror" id="fone1" name="fone1" required value="{{ $cliente->fone1 }}">
                      @error('fone1')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror                          
                    </div>

                    <div class="form-group">
                      <label class="form-label" >Telefone 2</label>
                      <input type="text" class="form-control fone @error('fone2') is-invalid @enderror" id="fone2" name="fone2" value="{{ $cliente->fone2 }}">
                      @error('fone2')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror                          
                    </div>

                    <div class="form-group">
                      <label class="form-label" >Telefone 3</label>
                      <input type="text" class="form-control fone @error('fone3') is-invalid @enderror" id="fone3" name="fone3" value="{{ $cliente->fone3 }}">
                      @error('fone3')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror                          
                    </div>

                    <div class="form-group">
                      <label class="form-label" >Website</label>
                      <input type="text" class="form-control @error('website') is-invalid @enderror" id="website" name="website" value="{{ $cliente->website }}">
                      @error('website')
                        <div class="invalid-feedback">{{ $message }}</div>
                      @enderror                          
                    </div>

                    <div class="form-group">
                      <label class="form-label" >Observações</label>
                      <textarea class="form-control @error('obs') is-invalid @enderror" id="obs" name="obs" >{{ $cliente->obs }}</textarea>
                      @error('obs')
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

  <script src="{{ asset('assets/clientes/clientes.js') }}"></script>

@endsection