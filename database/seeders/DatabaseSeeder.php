<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Una sola clave para todas las cuentas iniciales; en producción define SEED_PASSWORD.
        $password = Hash::make(env('SEED_PASSWORD') ?: 'password123');

        // Administrador
        $admin = User::create([
            'name' => 'Admin InmoPlus',
            'email' => 'admin@inmoplus.com',
            'phone' => '555-0001',
            'profile_photo' => null,
            'role' => 'admin',
            'password' => $password,
            'email_verified_at' => now(),
        ]);

        // Agentes
        $agent1 = User::create([
            'name' => 'Agente Uno',
            'email' => 'agente1@inmoplus.com',
            'phone' => '555-0002',
            'profile_photo' => null,
            'role' => 'agent',
            'password' => $password,
            'email_verified_at' => now(),
        ]);

        $agent2 = User::create([
            'name' => 'Agente Dos',
            'email' => 'agente2@inmoplus.com',
            'phone' => '555-0003',
            'profile_photo' => null,
            'role' => 'agent',
            'password' => $password,
            'email_verified_at' => now(),
        ]);

        // Tipos de propiedad
        $propertyTypes = [
            ['name' => 'Casa', 'description' => 'Propiedad residencial unifamiliar', 'status' => 'active'],
            ['name' => 'Departamento', 'description' => 'Unidad habitacional en edificio', 'status' => 'active'],
            ['name' => 'Terreno', 'description' => 'Lote o parcela de terreno', 'status' => 'active'],
            ['name' => 'Local Comercial', 'description' => 'Espacio para uso comercial', 'status' => 'active'],
            ['name' => 'Oficina', 'description' => 'Espacio para uso profesional', 'status' => 'active'],
            ['name' => 'Bodega', 'description' => 'Espacio para almacenamiento', 'status' => 'active'],
        ];

        foreach ($propertyTypes as $type) {
            PropertyType::create($type);
        }

        // Propiedades de prueba
        $properties = [
            [
                'property_type_id' => 1, // Casa
                'user_id' => $agent1->id,
                'title' => 'Casa en zona residencial norte',
                'description' => 'Hermosa casa de 3 habitaciones con jardín amplio y garaje para 2 autos. Cercana a escuelas y parques.',
                'address' => 'Av. Principal 123',
                'city' => 'Bogotá',
                'neighborhood' => 'Residencial Norte',
                'price' => 425000000.00,
                'area' => 180.50,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'operation_type' => 'venta',
                'status' => 'disponible',
                'main_image' => null,
            ],
            [
                'property_type_id' => 1, // Casa
                'user_id' => $agent2->id,
                'title' => 'Casa moderna en zona sur',
                'description' => 'Casa de 4 habitaciones con acabados de lujo, piscina y terraza. En privada con seguridad 24/7.',
                'address' => 'Calle del Bosque 456',
                'city' => 'Medellín',
                'neighborhood' => 'Zona Sur',
                'price' => 715000000.00,
                'area' => 280.00,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'operation_type' => 'venta',
                'status' => 'disponible',
                'main_image' => null,
            ],
            [
                'property_type_id' => 2, // Departamento
                'user_id' => $agent1->id,
                'title' => 'Departamento céntrico con vista',
                'description' => 'Departamento de 2 habitaciones en piso 15 con vista panorámica. Amenidades: gym, salón de eventos, terraza común.',
                'address' => 'Carrera 7 789, Piso 15',
                'city' => 'Bogotá',
                'neighborhood' => 'Centro',
                'price' => 306000000.00,
                'area' => 95.00,
                'bedrooms' => 2,
                'bathrooms' => 2,
                'operation_type' => 'venta',
                'status' => 'disponible',
                'main_image' => null,
            ],
            [
                'property_type_id' => 2, // Departamento
                'user_id' => $agent2->id,
                'title' => 'Departamento en renta zona ejecutiva',
                'description' => 'Departamento amueblado de 1 recámara, ideal para ejecutivos. Incluye internet y limpieza semanal.',
                'address' => 'Torre Ejecutiva, Carrera 15 321',
                'city' => 'Cali',
                'neighborhood' => 'Zona Comercial',
                'price' => 4250000.00,
                'area' => 65.00,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'operation_type' => 'alquiler',
                'status' => 'disponible',
                'main_image' => null,
            ],
            [
                'property_type_id' => 3, // Terreno
                'user_id' => $agent1->id,
                'title' => 'Terreno residencial 500m2',
                'description' => 'Terreno plano de 500m2 en privada residencial con todos los servicios. Ideal para construir tu casa.',
                'address' => 'Lote 12, Privada Los Pinos',
                'city' => 'Bucaramanga',
                'neighborhood' => 'Residencial',
                'price' => 145000000.00,
                'area' => 500.00,
                'bedrooms' => 0,
                'bathrooms' => 0,
                'operation_type' => 'venta',
                'status' => 'disponible',
                'main_image' => null,
            ],
            [
                'property_type_id' => 3, // Terreno
                'user_id' => $agent2->id,
                'title' => 'Terreno comercial esquina',
                'description' => 'Terreno de 1000m2 en esquina de alta visibilidad. Uso de suelo comercial, ideal para plaza o edificio.',
                'address' => 'Avenida Principal esquina Calle 5',
                'city' => 'Cartagena',
                'neighborhood' => 'Comercial',
                'price' => 596000000.00,
                'area' => 1000.00,
                'bedrooms' => 0,
                'bathrooms' => 0,
                'operation_type' => 'venta',
                'status' => 'reservado',
                'main_image' => null,
            ],
            [
                'property_type_id' => 4, // Local Comercial
                'user_id' => $agent1->id,
                'title' => 'Local comercial en plaza central',
                'description' => 'Local de 120m2 en planta baja de plaza comercial con alto flujo peatonal. Baño propio y bodega.',
                'address' => 'Plaza Central, Local 15',
                'city' => 'Pereira',
                'neighborhood' => 'Centro',
                'price' => 7650000.00,
                'area' => 120.00,
                'bedrooms' => 0,
                'bathrooms' => 1,
                'operation_type' => 'alquiler',
                'status' => 'disponible',
                'main_image' => null,
            ],
            [
                'property_type_id' => 4, // Local Comercial
                'user_id' => $agent2->id,
                'title' => 'Local comercial esquina venta',
                'description' => 'Local en esquina de 200m2, doble frente, ideal para restaurante o tienda departamental. Estacionamiento propio.',
                'address' => 'Av. Comercial 555',
                'city' => 'Barranquilla',
                'neighborhood' => 'Comercial',
                'price' => 1157000000.00,
                'area' => 200.00,
                'bedrooms' => 0,
                'bathrooms' => 2,
                'operation_type' => 'venta',
                'status' => 'disponible',
                'main_image' => null,
            ],
            [
                'property_type_id' => 5, // Oficina
                'user_id' => $agent1->id,
                'title' => 'Oficina en torre corporativa',
                'description' => 'Oficina de 80m2 en torre corporativa clase A. Recepción, sala de juntas, 3 privados. Estacionamiento incluido.',
                'address' => 'Torre Corporativa, Piso 10, Oficina 101',
                'city' => 'Bogotá',
                'neighborhood' => 'Polanco',
                'price' => 5950000.00,
                'area' => 80.00,
                'bedrooms' => 0,
                'bathrooms' => 1,
                'operation_type' => 'alquiler',
                'status' => 'disponible',
                'main_image' => null,
            ],
            [
                'property_type_id' => 6, // Bodega
                'user_id' => $agent2->id,
                'title' => 'Bodega industrial 1000m2',
                'description' => 'Nave industrial de 1000m2 con altura de 10m, muelle de carga, oficinas administrativas y patio de maniobras.',
                'address' => 'Parque Industrial Norte, Nave 7',
                'city' => 'Manizales',
                'neighborhood' => 'Industrial',
                'price' => 20400000.00,
                'area' => 1000.00,
                'bedrooms' => 0,
                'bathrooms' => 2,
                'operation_type' => 'alquiler',
                'status' => 'disponible',
                'main_image' => null,
            ],
            [
                'property_type_id' => 1, // Casa - extra para completar 11 propiedades
                'user_id' => $admin->id,
                'title' => 'Casa de lujo en zona exclusiva',
                'description' => 'Mansión de 5 habitaciones con home theater, gimnasio, bodega de vinos y jardín japonés. Seguridad perimetral.',
                'address' => 'Club de Golf, Lote 1',
                'city' => 'Bogotá',
                'neighborhood' => 'Interlomas',
                'price' => 2553000000.00,
                'area' => 650.00,
                'bedrooms' => 5,
                'bathrooms' => 5,
                'operation_type' => 'venta',
                'status' => 'vendido',
                'main_image' => null,
            ],
        ];

        foreach ($properties as $index => $data) {
            $property = Property::create($data);
            $this->attachPhotos($property, $index + 1);
        }

        // Clientes de prueba
        $clients = [
            [
                'name' => 'María Fernanda López',
                'email' => 'maria.lopez@example.com',
                'phone' => '55-1234-5678',
                'identification' => 'A-1001-23456-001',
                'address' => 'Av. Universidad 1020, barrio El Nogal',
                'city' => 'Bogotá',
                'status' => 'active',
            ],
            [
                'name' => 'José Luis Ramírez',
                'email' => 'jose.ramirez@example.com',
                'phone' => '33-9876-5432',
                'identification' => 'A-1002-34567-002',
                'address' => 'Calle Chapultepec 450, barrio Laureles',
                'city' => 'Medellín',
                'status' => 'active',
            ],
            [
                'name' => 'Ana Sofía García',
                'email' => 'ana.garcia@example.com',
                'phone' => '81-2222-3344',
                'identification' => 'A-1003-45678-003',
                'address' => 'Avenida Díaz Ordaz 800, barrio Granada',
                'city' => 'Cali',
                'status' => 'active',
            ],
            [
                'name' => 'Carlos Eduardo Hernández',
                'email' => 'carlos.hernandez@example.com',
                'phone' => '51-5555-6677',
                'identification' => 'A-1004-56789-004',
                'address' => 'Av. Juárez 12, barrio Centro',
                'city' => 'Cartagena',
                'status' => 'active',
            ],
            [
                'name' => 'Laura Patricia Morales',
                'email' => 'laura.morales@example.com',
                'phone' => '44-2220-9988',
                'identification' => 'A-1005-67890-005',
                'address' => 'Prol. Corregidora Norte 231',
                'city' => 'Bucaramanga',
                'status' => 'inactive',
            ],
            [
                'name' => 'Miguel Ángel Torres',
                'email' => 'miguel.torres@example.com',
                'phone' => '47-7111-2233',
                'identification' => 'A-1006-78901-006',
                'address' => 'Avenida Adolfo López Mateos 345',
                'city' => 'Pereira',
                'status' => 'active',
            ],
            [
                'name' => 'Isabel Cristina Vega',
                'email' => 'isabel.vega@example.com',
                'phone' => '66-4321-9876',
                'identification' => 'A-1007-89012-007',
                'address' => 'Av. Revolución 1234, Zona Centro',
                'city' => 'Barranquilla',
                'status' => 'active',
            ],
            [
                'name' => 'Ricardo Antonio Fuentes',
                'email' => 'ricardo.fuentes@example.com',
                'phone' => '84-7777-8899',
                'identification' => 'A-1008-90123-008',
                'address' => 'Avenida Venustiano Carranza 567',
                'city' => 'Manizales',
                'status' => 'active',
            ],
            [
                'name' => 'Gabriela Alejandra Ríos',
                'email' => 'gabriela.rios@example.com',
                'phone' => '55-3456-7890',
                'identification' => 'A-1009-01234-009',
                'address' => 'Calle de la Paz 87, Col. Roma',
                'city' => 'Bogotá',
                'status' => 'active',
            ],
            [
                'name' => 'Jorge Alberto Mendoza',
                'email' => 'jorge.mendoza@example.com',
                'phone' => '33-2345-6789',
                'identification' => 'A-1010-12345-010',
                'address' => 'Av. Vallarta 678, Col. Providencia',
                'city' => 'Medellín',
                'status' => 'inactive',
            ],
            [
                'name' => 'Alejandra Nicole Paredes',
                'email' => 'alejandra.paredes@example.com',
                'phone' => '81-9090-1212',
                'identification' => 'A-1011-23456-011',
                'address' => 'Av. Constitución 901, barrio Centro',
                'city' => 'Cali',
                'status' => 'active',
            ],
            [
                'name' => 'Fernando David Salazar',
                'email' => 'fernando.salazar@example.com',
                'phone' => '44-8765-4321',
                'identification' => 'A-1012-34567-012',
                'address' => 'Paseo de la Reforma 210, Col. Juriquilla',
                'city' => 'Bucaramanga',
                'status' => 'active',
            ],
        ];

        foreach ($clients as $client) {
            $user = User::create([
                'name' => $client['name'],
                'email' => $client['email'],
                'phone' => $client['phone'],
                'profile_photo' => null,
                'role' => 'client',
                'password' => $password,
                'email_verified_at' => now(),
            ]);

            Client::create([...$client, 'user_id' => $user->id]);
        }

        // Intereses de prueba
        $availableIds = Property::where('status', 'disponible')
            ->orderBy('id')
            ->take(3)
            ->pluck('id')
            ->all();

        foreach ([
            'maria.lopez@example.com' => array_slice($availableIds, 0, 2), // 2 intereses
            'jose.ramirez@example.com' => array_slice($availableIds, 0, 1), // 1 interés
        ] as $email => $ids) {
            $client = Client::where('email', $email)->first();

            if ($client && $ids !== []) {
                $client->properties()->syncWithoutDetaching($ids);
            }
        }

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('Admin: admin@inmoplus.com / clave de SEED_PASSWORD (por defecto password123)');
        $this->command->info('Agente 1: agente1@inmoplus.com');
        $this->command->info('Agente 2: agente2@inmoplus.com');
        $this->command->info('Clientes de prueba: 12 creados (login = email, rol client)');
        $this->command->info('Intereses iniciales: María (2) y José (1) — solo propiedades disponibles');
    }

    /** Copia las fotos de database/seeders/images/{n} al disco público y las enlaza a la propiedad. */
    private function attachPhotos(Property $property, int $number): void
    {
        $folder = database_path("seeders/images/{$number}");

        if (! File::isDirectory($folder)) {
            return;
        }

        $files = collect(File::files($folder))
            ->filter(fn ($file) => in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp']))
            ->sortBy(fn ($file) => $file->getFilename())
            ->values();

        foreach ($files as $i => $file) {
            $path = 'properties/seed-'.$property->id.'-'.($i + 1).'.'.strtolower($file->getExtension());

            Storage::disk('public')->put($path, File::get($file->getPathname()));

            if ($i === 0) {
                $property->update(['main_image' => $path]);
            } else {
                $property->galleryImages()->create(['path' => $path]);
            }
        }
    }
}
