<div class="filter-card mb-4">
    <form id="dashboardFiltersForm" class="row gx-3 gy-3 align-items-end">
        <div class="col-md-4">
            <label class="filter-label">Empresa</label>
            <input type="text" name="empresa" value="{{ $filters['empresa'] ?? '' }}" class="form-control" placeholder="Buscar empresa" />
        </div>
        <div class="col-md-2">
            <label class="filter-label">Estado</label>
            <select name="estado" class="form-select">
                <option value="">Todos</option>
                <option value="activa" {{ isset($filters['estado']) && $filters['estado'] === 'activa' ? 'selected' : '' }}>Activas</option>
                <option value="finalizada" {{ isset($filters['estado']) && $filters['estado'] === 'finalizada' ? 'selected' : '' }}>Finalizadas</option>
                <option value="vencida" {{ isset($filters['estado']) && $filters['estado'] === 'vencida' ? 'selected' : '' }}>Vencidas</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="filter-label">Desde</label>
            <input type="date" name="desde" value="{{ $filters['desde'] ?? '' }}" class="form-control" />
        </div>
        <div class="col-md-2">
            <label class="filter-label">Hasta</label>
            <input type="date" name="hasta" value="{{ $filters['hasta'] ?? '' }}" class="form-control" />
        </div>
        <div class="col-md-2 text-end">
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            <button type="button" id="dashboardResetFilters" class="btn btn-outline-secondary w-100 mt-2">Limpiar</button>
        </div>
    </form>
</div>
