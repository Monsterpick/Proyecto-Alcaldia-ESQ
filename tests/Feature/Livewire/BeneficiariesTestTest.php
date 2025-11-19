<?php

namespace Tests\Feature\Livewire;

use App\Livewire\BeneficiariesTest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class BeneficiariesTestTest extends TestCase
{
    public function test_renders_successfully()
    {
        Livewire::test(BeneficiariesTest::class)
            ->assertStatus(200);
    }
}
