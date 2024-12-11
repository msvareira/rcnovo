@extends('layouts.main')

@section('title', 'Cadastro de Veículos')
@section('breadcrumb-item', 'Veículos')

@section('breadcrumb-item-active', 'Cadastro de Veículos')

@section('css')
@endsection

@section('content')
  <!-- [ Main Content ] start -->
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h5>Formulário de Veículos</h5>
        </div>
        <div class="card-body">
          <form name="form" method="POST" action="{{ isset($veiculo) ? route('veiculos.update', $veiculo->id) : route('veiculos.store') }}">
            @csrf
            
            <div class="form-group col-md-3">
              <label class="form-label" for="placa">Placa</label>
              <input type="text" class="form-control @error('placa') is-invalid @enderror" id="placa" name="placa" required value="{{ old('placa', $veiculo->placa ?? '') }}">
              @error('placa')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-group col-md-3">
              <label class="form-label" for="modelo">Modelo</label>
              <input type="text" class="form-control @error('modelo') is-invalid @enderror" id="modelo" name="modelo" required value="{{ old('modelo', $veiculo->modelo ?? '') }}">
              @error('modelo')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-group col-md-3">
              <label class="form-label" for="ano">Ano</label>
              <input type="number" class="form-control @error('ano') is-invalid @enderror" id="ano" name="ano" required value="{{ old('ano', $veiculo->ano ?? '') }}">
              @error('ano')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-group col-md-3">
              <label class="form-label" for="quilometragem_inicial">Quilometragem Inicial</label>
              <input type="number" class="form-control @error('quilometragem_inicial') is-invalid @enderror" id="quilometragem_inicial" name="quilometragem_inicial" required value="{{ old('quilometragem_inicial', $veiculo->quilometragem_inicial ?? '') }}">
              @error('quilometragem_inicial')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-group col-md-3">
              <label class="form-label" for="data_seguro">Data do Seguro</label>
              <input type="date" class="form-control @error('data_seguro') is-invalid @enderror" id="data_seguro" name="data_seguro" value="{{ old('data_seguro', $veiculo->data_seguro ?? '') }}">
              @error('data_seguro')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-group col-md-3">
              <label class="form-label" for="data_inspecao">Data da Inspeção</label>
              <input type="date" class="form-control @error('data_inspecao') is-invalid @enderror" id="data_inspecao" name="data_inspecao" value="{{ old('data_inspecao', $veiculo->data_inspecao ?? '') }}">
              @error('data_inspecao')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="form-group col-md-3">
              <label class="form-label" for="status">Status</label>
              <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
              <option value="">Selecione</option>
              <option value="Disponível" {{ old('status', $veiculo->status ?? '') == 'Disponível' ? 'selected' : '' }}>Disponível</option>
              <option value="Em uso" {{ old('status', $veiculo->status ?? '') == 'Em uso' ? 'selected' : '' }}>Em uso</option>
              <option value="Em manutenção" {{ old('status', $veiculo->status ?? '') == 'Em manutenção' ? 'selected' : '' }}>Em manutenção</option>
              <option value="Aguardando inspeção" {{ old('status', $veiculo->status ?? '') == 'Aguardando inspeção' ? 'selected' : '' }}>Aguardando inspeção</option>
              </select>
              @error('status')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <button type="submit" class="btn btn-primary mb-4">Salvar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
@endsection