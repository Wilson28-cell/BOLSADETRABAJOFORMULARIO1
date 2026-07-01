<?php

namespace Tests\Feature;

use App\Http\Requests\StoreBolsaTrabajoRequest;
use Tests\TestCase;

class SalaryFieldTest extends TestCase
{
    public function test_store_bolsa_trabajo_request_uses_single_salary_field(): void
    {
        $request = new StoreBolsaTrabajoRequest();
        $rules = $request->rules();

        $this->assertArrayHasKey('salario', $rules);
        $this->assertArrayNotHasKey('salario_minimo', $rules);
        $this->assertArrayNotHasKey('salario_maximo', $rules);
        $this->assertSame('required|numeric', $rules['salario']);
    }
}
