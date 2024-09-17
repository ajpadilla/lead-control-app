<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class LeadControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_requires_authentication_to_create_lead()
    {
        // Simula una solicitud POST a la ruta 'leads' sin autenticación
        $response = $this->postJson('api/lead-control/v1/leads', [
            'name' => 'Test Lead',
            'source' => 'Test Source',
            'owner' => 1,
        ]);

        // Verifica que la respuesta sea un error 401 Unauthorized
        $response->assertStatus(401);
    }

    /** @test */
    public function it_creates_a_lead_with_valid_token()
    {
        // Crea un usuario manager
        $roleManager = Role::factory()->create(['name' => 'manager']);
        $permission = Permission::factory()->create(['name' => 'create-lead']);
        $roleManager->permissions()->attach($permission);

        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
        $user->roles()->attach($roleManager);

        // Genera un token JWT para el usuario
        $token = JWTAuth::fromUser($user);

        // Simula una solicitud POST a la ruta 'leads' con autenticación
        $response = $this->postJson('api/lead-control/v1/leads', [
            'name' => 'Test Lead',
            'source' => 'Test Source',
            'owner' => $user->id,
        ], [
            'Authorization' => "Bearer $token",
        ]);

        // Obtiene la respuesta JSON
        $responseData = $response->json();

        // Verifica que la respuesta sea un éxito 201 Created
        $response->assertStatus(201);

        // Verifica que la estructura de la respuesta sea la esperada
        $response->assertJson([
            'meta' => [
                'success' => true,
                'errors' => [],
            ],
            'data' => [
                'id' =>$responseData['data']['id'], // Verifica que el ID esté presente en la respuesta
                'name' => 'Test Lead',
                'source' => 'Test Source',
                'owner' => $user->id,
                'created_by' => $user->id, // Asegúrate de que el creador coincida con el ID del usuario
                'created_at' => $responseData['data']['created_at'], // Verifica que la fecha esté presente en la respuesta
            ],
        ]);
    }

    /** @test */
    public function it_denies_lead_creation_for_a_user_without_permission()
    {
        $roleAgent = Role::factory()->create(['name' => 'agent']);
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
        $user->roles()->attach($roleAgent);

        // Genera un token JWT para el usuario
        $token = JWTAuth::fromUser($user);

        // Simula una solicitud POST a la ruta 'leads' con autenticación y sin permiso
        $response = $this->postJson('api/lead-control/v1/leads', [
            'name' => 'Test Lead',
            'source' => 'Test Source',
            'owner' => $user->id,
        ], [
            'Authorization' => "Bearer $token",
        ]);

        // Verifica que la respuesta sea un acceso denegado 403 Forbidden
        $response->assertStatus(403);
        $response->assertJson([
            'meta' => [
                'success' => false,
                'errors' => [
                    'Unauthorized',
                ],
            ],
        ]);
    }


    /**
     * @test
     */
    public function it_retrieves_a_lead_by_id_with_valid_token()
    {
        // Crear un usuario de prueba
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        // Generar un token JWT para el usuario
        $token = JWTAuth::fromUser($user);

        // Crear un lead de prueba
        $lead = Lead::factory()->create([
            'created_by' => $user->id,
        ]);

        // Simula una solicitud GET a la ruta 'leads/{id}' con autenticación
        $response = $this->getJson("api/lead-control/v1/leads/{$lead->id}", [
            'Authorization' => "Bearer $token",
        ]);

        // Obtiene la respuesta JSON
        $responseData = $response->json();

        // Verifica que la respuesta sea un éxito 201 Created
        $response->assertStatus(201);

        // Verifica que la estructura de la respuesta sea la esperada
        $response->assertJson([
            'meta' => [
                'success' => true,
                'errors' => [],
            ],
            'data' => [
                'id' => (string) $lead->id, // Asegúrate de que el id esté en formato string
                'name' => $lead->name,
                'source' => $lead->source,
                'owner' => $lead->owner,
                'created_by' => $lead->created_by,
                'created_at' => $responseData['data']['created_at'], // Verifica que la fecha esté presente en la respuesta
            ],
        ]);
    }

    /** @test */
    public function it_returns_all_leads_for_a_manager_user()
    {
        // Limpia el caché antes de ejecutar el test
        Cache::flush();

        // Crea un usuario manager
        $roleManager = Role::factory()->create(['name' => 'manager']);
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
        $user->roles()->attach($roleManager);

        // Crea algunos leads
        $lead1 = Lead::factory()->create();
        $lead2 = Lead::factory()->create();

        // Genera un token JWT para el usuario
        $token = JWTAuth::fromUser($user);

        // Establece el cache key y el valor esperado
        $cacheKey = 'all_leads';
        $expectedData = [$lead1->toArray(), $lead2->toArray()];

        // Espía el cache
        Cache::shouldReceive('remember')
            ->once()
            ->with($cacheKey, 3600, \Closure::class)
            ->andReturn($expectedData);

        // Simula una solicitud GET a la ruta 'leads' con autenticación
        $response = $this->getJson('api/lead-control/v1/leads', [
            'Authorization' => "Bearer $token",
        ]);

       // dd($response->getContent());

        // Verifica que la respuesta sea un éxito 200 OK
        $response->assertStatus(200);
        $response->assertJson([
            'meta' => [
                'success' => true,
                'errors' => [],
            ],
            'data' => [
                [
                    'id' => $lead1->id,
                    'name' => $lead1->name,
                    'source' => $lead1->source,
                    'owner' => $lead1->owner,
                    'created_by' => $lead1->created_by,
                    'created_at' => $lead1->created_at->toDateTimeString(),
                ],
                [
                    'id' => $lead2->id,
                    'name' => $lead2->name,
                    'source' => $lead2->source,
                    'owner' => $lead2->owner,
                    'created_by' => $lead2->created_by,
                    'created_at' => $lead2->created_at->toDateTimeString(),
                ],
            ],
        ]);
    }

    /** @test */
    public function it_returns_only_assigned_leads_for_a_non_manager_user()
    {

        // Limpia el caché antes de ejecutar el test
        Cache::flush();

        // Crea un usuario agent
        $userAgent= User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $roleAgent = Role::factory()->create(['name' => 'agent']);
        $userAgent->roles()->attach($roleAgent);


        // Crea algunos leads, asignando uno al usuario agent
        $assignedLead = Lead::factory()->create([
            'owner' => $userAgent->id,
        ]);

        $otherLead = Lead::factory()->create([
            'owner' => $userAgent->id,
        ]);

        // Crea algunos leads
        $lead1 = Lead::factory()->create([
            'owner' => $userAgent->id,
        ]);

        // Crea un usuario manager
        $roleManager = Role::factory()->create(['name' => 'manager']);
        $userManager = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
        $userManager->roles()->attach($roleManager);

        $lead2 = Lead::factory()->create([
            'owner' => $userManager->id,
        ]);

        // Genera un token JWT para el usuario
        $token = JWTAuth::fromUser($userAgent);

        // Prepara los datos esperados
        $expectedData = collect([
            $assignedLead,
            $otherLead,
            $lead1,
        ]);

        // Espía el cache
        Cache::shouldReceive('remember')
            ->once()
            ->with('user_leads_' . $userAgent->id, 3600, \Closure::class)
            ->andReturn($expectedData);

        // Simula una solicitud GET a la ruta 'leads' con autenticación
        $response = $this->getJson('api/lead-control/v1/leads', [
            'Authorization' => "Bearer $token",
        ]);


        // Verifica que la respuesta sea un éxito 200 OK
        $response->assertStatus(200);
        $response->assertJson([
            'meta' => [
                'success' => true,
                'errors' => [],
            ],
            'data' => [
                [
                    'id' => $assignedLead->id,
                    'name' => $assignedLead->name,
                    'source' => $assignedLead->source,
                    'owner' => $assignedLead->owner,
                    'created_by' => $assignedLead->created_by,
                    'created_at' => $assignedLead->created_at->toDateTimeString(),
                ],
                [
                    'id' => $otherLead->id,
                    'name' => $otherLead->name,
                    'source' => $otherLead->source,
                    'owner' => $otherLead->owner,
                    'created_by' => $otherLead->created_by,
                    'created_at' => $otherLead->created_at->toDateTimeString(),
                ],
                [
                    'id' => $lead1->id,
                    'name' => $lead1->name,
                    'source' => $lead1->source,
                    'owner' => $lead1->owner,
                    'created_by' => $lead1->created_by,
                    'created_at' => $lead1->created_at->toDateTimeString(),
                ],
            ],
        ]);
    }

}
