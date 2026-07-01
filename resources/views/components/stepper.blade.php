<div class="registro-stepper">
    <div class="stepper-item {{ $paso >= 1 ? 'active' : '' }} {{ $paso > 1 ? 'completed' : '' }}">
        <div class="stepper-circle">
            @if($paso > 1)
                ✓
            @else
                1
            @endif
        </div>
        <div class="stepper-label">Tipo de Publicación</div>
    </div>

    <div class="stepper-connector"></div>

    <div class="stepper-item {{ $paso >= 2 ? 'active' : '' }} {{ $paso > 2 ? 'completed' : '' }}">
        <div class="stepper-circle">
            @if($paso > 2)
                ✓
            @else
                2
            @endif
        </div>
        <div class="stepper-label">Información Empresa</div>
    </div>

    <div class="stepper-connector"></div>

    <div class="stepper-item {{ $paso >= 3 ? 'active' : '' }}">
        <div class="stepper-circle">
            3
        </div>
        <div class="stepper-label">Detalles Publicación</div>
    </div>
</div>