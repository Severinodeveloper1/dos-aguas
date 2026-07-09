<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Location;
use App\Models\Post;
use App\Models\CompanyInfo;
use App\Models\Banner;
use App\Models\PaymentSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Roles & Users
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $editorRole = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@dosaguas.com'],
            [
                'name' => 'Super Admin Dos Aguas',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole($superAdminRole);

        $editor = User::firstOrCreate(
            ['email' => 'editor@dosaguas.com'],
            [
                'name' => 'Editor Dos Aguas',
                'password' => Hash::make('editor123'),
                'email_verified_at' => now(),
            ]
        );
        $editor->assignRole($editorRole);

        // Sync permissions dynamically
        $allPermissions = Permission::all();
        $superAdminRole->syncPermissions($allPermissions);

        $editorPermissions = Permission::where(function ($query) {
            $query->where('name', 'like', '%:Banner')
                ->orWhere('name', 'like', '%:Post')
                ->orWhere('name', 'like', '%:Category')
                ->orWhere('name', 'like', '%:Product')
                ->orWhere('name', 'like', '%:Location')
                ->orWhere('name', 'View:ManageCompanyInfo')
                ->orWhere('name', 'View:StatsOverview');
        })->orWhere(function ($query) {
            $query->where('name', 'like', '%:Order')
                ->where('name', 'not like', 'Delete%')
                ->where('name', 'not like', 'ForceDelete%');
        })->get();
        $editorRole->syncPermissions($editorPermissions);


        // 2. Seed Categories
        $cat1 = Category::firstOrCreate(
            ['slug' => 'esencia-pura'],
            [
                'name' => 'Esencia Pura',
                'description' => 'Chocolates oscuros elaborados con porcentajes de cacao orgánico fino de aroma (70% y 100%).',
                'order' => 1,
                'is_active' => true,
                'meta_title' => 'Chocolates Esencia Pura | Dos Aguas',
                'meta_description' => 'Disfruta de nuestros chocolates de 70% y 100% cacao fino de aroma.',
            ]
        );

        $cat2 = Category::firstOrCreate(
            ['slug' => 'infusiones-amazonicas'],
            [
                'name' => 'Infusiones Amazónicas',
                'description' => 'Infusiones naturales de cascarilla de cacao combinadas con hierbas aromáticas de la selva y andes.',
                'order' => 2,
                'is_active' => true,
                'meta_title' => 'Infusiones Amazónicas de Cacao | Dos Aguas',
                'meta_description' => 'Infusiones saludables hechas con cascarilla de cacao y hierbas naturales.',
            ]
        );

        // 3. Seed Products & Variants
        // Product 1
        $prod1 = Product::firstOrCreate(
            ['slug' => 'chocolate-ucayali-70'],
            [
                'category_id' => $cat1->id,
                'name' => 'Chocolate Ucayali 70% Cacao',
                'description' => '<p>Barra de chocolate premium elaborada con cacao seleccionado del cruce de los ríos Aguaytía y San Alejandro en Ucayali. Sabor intenso con notas frutales y un toque cítrico.</p>',
                'tasting_notes' => '<p>Notas cítricas pronunciadas de Hierba Luisa y frutos amarillos con un final prolongado a cacao tostado.</p>',
                'natural_benefits' => '<p>Rico en antioxidantes naturales, ayuda a mejorar el estado de ánimo y estimula la salud cardiovascular.</p>',
                'nutritional_values' => [
                    ['label' => 'Calorías', 'value' => '145 kcal'],
                    ['label' => 'Grasa Total', 'value' => '9g (12%)'],
                    ['label' => 'Carbohidratos', 'value' => '12g (4%)'],
                    ['label' => 'Azúcares', 'value' => '5g'],
                    ['label' => 'Proteínas', 'value' => '2g'],
                ],
                'images' => [],
                'is_active' => true,
                'meta_title' => 'Chocolate de Ucayali 70% Cacao Fino | Dos Aguas',
                'meta_description' => 'Exquisito chocolate peruano de origen con 70% de cacao seleccionado de la cuenca de Ucayali.',
            ]
        );

        // Product 1 Variants
        ProductVariant::firstOrCreate(
            ['sku' => 'DA-CHO-UCA70-100G'],
            [
                'product_id' => $prod1->id,
                'name' => 'Barra Individual 100g',
                'weight' => 100.00,
                'price' => 12.00,
                'stock' => 150,
                'is_active' => true,
            ]
        );

        ProductVariant::firstOrCreate(
            ['sku' => 'DA-CHO-UCA70-250G'],
            [
                'product_id' => $prod1->id,
                'name' => 'Presentación Mediana 250g',
                'weight' => 250.00,
                'price' => 28.00,
                'stock' => 80,
                'is_active' => true,
            ]
        );

        ProductVariant::firstOrCreate(
            ['sku' => 'DA-CHO-UCA70-1KG'],
            [
                'product_id' => $prod1->id,
                'name' => 'Bloque de Cobertura 1kg',
                'weight' => 1000.00,
                'price' => 95.00,
                'stock' => 20,
                'is_active' => true,
            ]
        );

        ProductVariant::firstOrCreate(
            ['sku' => 'DA-CHO-UCA70-5KG'],
            [
                'product_id' => $prod1->id,
                'name' => 'Caja de Cobertura Maquila 5kg',
                'weight' => 5000.00,
                'price' => 420.00,
                'stock' => 10,
                'is_active' => true,
            ]
        );

        // Product 2
        $prod2 = Product::firstOrCreate(
            ['slug' => 'infusion-hierba-luisa-cacao'],
            [
                'category_id' => $cat2->id,
                'name' => 'Infusión Cacao & Hierba Luisa',
                'description' => '<p>Mezcla equilibrada de cascarilla de cacao orgánico tostado y hojas secas de hierba luisa silvestre, brindando una infusión refrescante y digestiva.</p>',
                'tasting_notes' => '<p>Aroma cítrico herbal con notas sutiles de chocolate y madera dulce.</p>',
                'natural_benefits' => '<p>Excelente digestivo natural, ayuda a relajar el sistema nervioso y alivia la congestión leve de vías respiratorias.</p>',
                'nutritional_values' => [
                    ['label' => 'Calorías', 'value' => '0 kcal'],
                    ['label' => 'Grasa Total', 'value' => '0g'],
                    ['label' => 'Azúcares', 'value' => '0g'],
                    ['label' => 'Sodio', 'value' => '0mg'],
                ],
                'images' => [],
                'is_active' => true,
                'meta_title' => 'Infusión Cacao y Hierba Luisa Digestiva | Dos Aguas',
                'meta_description' => 'Combina las propiedades antioxidantes del cacao con la frescura digestiva de la hierba luisa.',
            ]
        );

        // Product 2 Variants
        ProductVariant::firstOrCreate(
            ['sku' => 'DA-INF-HLCA-20FILT'],
            [
                'product_id' => $prod2->id,
                'name' => 'Caja de 20 Filtrantes',
                'weight' => 40.00,
                'price' => 16.00,
                'stock' => 120,
                'is_active' => true,
            ]
        );

        // 4. Seed Company Info
        CompanyInfo::firstOrCreate(
            ['id' => 1],
            [
                'name' => 'Dos Aguas',
                'phone' => '+51 961 889 112',
                'email' => 'contacto@dosaguas.com',
                'address' => 'Av. Javier Prado Este 1234, San Isidro, Lima, Perú',
                'mission' => '<p>Producir y comercializar chocolate artesanal de la más alta calidad bajo el modelo Bean to Bar, impulsando la biodiversidad de Ucayali y garantizando un comercio justo con las comunidades de agricultores locales.</p>',
                'vision' => '<p>Ser reconocidos a nivel internacional como la marca líder en chocolates finos de aroma de origen amazónico peruano, preservando el legado familiar y respetando el ecosistema.</p>',
                'short_history' => '<p>Dos Aguas nace en la Hacienda familiar de Ucayali, inspirados en la labor y el legado de Doña Felícitas. El ritual familiar de cuidar cada planta de cacao, cosechar con esmero y secar los granos bajo el cálido sol de la selva se convirtió en el cimiento de nuestra chocolatería fina.</p>',
                'facebook_url' => 'https://facebook.com/chocolatesdosaguas',
                'instagram_url' => 'https://instagram.com/chocolatesdosaguas',
                'tiktok_url' => 'https://tiktok.com/@chocolatesdosaguas',
                'youtube_url' => 'https://youtube.com/c/chocolatesdosaguas',
                'whatsapp_phone' => '51961889112',
            ]
        );

        // 5. Seed Locations
        Location::firstOrCreate(
            ['name' => 'Planta de Procesamiento Chorrillos'],
            [
                'type' => 'planta',
                'address' => 'Av. Defensores del Morro 456, Chorrillos, Lima',
                'map_frames' => [
                    ['iframe_code' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15600.354146749321!2d-77.02796014458008!3d-12.174127099999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9105b828770c63ff%3A0x6bfa5b77ea8c6a0b!2sChorrillos!5e0!3m2!1ses!2spe!4v1700000000000!5m2!1ses!2spe" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>']
                ],
                'phone' => '+51 1 251-4455',
                'hours' => 'Lun - Sab: 8:00 AM - 5:00 PM',
                'is_active' => true,
            ]
        );

        Location::firstOrCreate(
            ['name' => 'Sede de Acopio Ucayali'],
            [
                'type' => 'acopio',
                'address' => 'Cruce de los ríos Aguaytía y San Alejandro, Padre Abad, Ucayali',
                'map_frames' => [
                    ['iframe_code' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3945.7483651121045!2d-75.18738410000001!3d-9.0125439!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x91a78bb123456789%3A0x123456789abcde!2sRio%20Aguaytia!5e0!3m2!1ses!2spe!4v1700000000000!5m2!1ses!2spe" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>']
                ],
                'phone' => '+51 961-889-112',
                'hours' => 'Lun - Vie: 7:00 AM - 4:00 PM',
                'is_active' => true,
            ]
        );

        // 6. Seed Payment Settings
        PaymentSetting::firstOrCreate(
            ['id' => 1],
            [
                'bank_transfer_enabled' => true,
                'bank_transfer_details' => '<p><strong>Banco de Crédito del Perú (BCP):</strong><br>Cuenta Corriente Soles: 191-9876543-0-12<br>CCI (Interbancaria): 002-19100987654301250<br><br><strong>Yape / Plin:</strong> 961 889 112</p>',
                'cod_enabled' => true,
                'gateway_enabled' => false,
                'gateway_provider' => 'mercadopago',
                'gateway_public_key' => 'TEST-public-key',
                'gateway_private_key' => 'TEST-private-key',
                'gateway_sandbox_mode' => true,
            ]
        );

        // 7. Seed Banners
        Banner::firstOrCreate(
            ['title' => 'Hacienda Dos Aguas'],
            [
                'subtitle' => 'El cruce de los ríos Aguaytía y San Alejandro da vida al mejor chocolate artesanal.',
                'button_text' => 'Conocer Historia',
                'button_url' => '/historia',
                'media_type' => 'image',
                'media_path' => 'banners/hacienda-banner.webp',
                'order' => 1,
                'is_active' => true,
            ]
        );

        // 8. Seed Blog Post
        Post::firstOrCreate(
            ['slug' => 'biodiversidad-en-la-hacienda-dos-aguas'],
            [
                'author_id' => $admin->id,
                'title' => 'Biodiversidad en la Hacienda Dos Aguas: Añujes y Osos Perezosos',
                'excerpt' => 'Descubre la increíble variedad de fauna y flora que convive en nuestra hacienda y enriquece las tierras de nuestro cacao.',
                'content' => '<p>Nuestra hacienda familiar en la región de Ucayali no solo es el hogar de árboles de cacao vigorosos, sino también un santuario vivo de biodiversidad. Al recorrer los linderos sombreados, es común encontrarse con añujes recolectando frutos caídos y tímidos osos perezosos de tres dedos colgando de las copas de las cecropias.</p><p>Esta interacción ecológica es vital: la fauna local contribuye a la polinización y dispersión de semillas, manteniendo un suelo rico en materia orgánica que aporta notas minerales y frutales únicas a nuestras barras de chocolate Bean to Bar.</p>',
                'image_path' => 'posts/biodiversidad.webp',
                'published_at' => now(),
                'is_active' => true,
                'meta_title' => 'Biodiversidad y Vida Silvestre en Hacienda Dos Aguas',
                'meta_description' => 'Conoce cómo los añujes, osos perezosos y el ecosistema de Ucayali influyen en la calidad del cacao artesanal de Dos Aguas.',
            ]
        );

        // 9. Seed Timeline Events
        \App\Models\TimelineEvent::firstOrCreate(
            ['year' => '2018'],
            [
                'title' => 'Fundación de Hacienda',
                'title_en' => 'Hacienda Founding',
                'description' => 'Se adquieren los linderos en Ucayali flanqueados por los dos ríos, iniciando el cultivo agroecológico bajo sombra natural.',
                'description_en' => 'Lands in Ucayali flanked by two rivers are acquired, starting agroecological cultivation under natural shade.',
                'order' => 1,
                'is_active' => true,
            ]
        );

        \App\Models\TimelineEvent::firstOrCreate(
            ['year' => '2020'],
            [
                'title' => 'Primera Cosecha Selectiva',
                'title_en' => 'First Selective Harvest',
                'description' => 'Tras años de cuidado artesanal de la tierra, cosechamos la primera producción selecta de cacao fino de aroma.',
                'description_en' => 'After years of artisanal care of the soil, we harvested the first select yield of fine aroma cacao.',
                'order' => 2,
                'is_active' => true,
            ]
        );

        \App\Models\TimelineEvent::firstOrCreate(
            ['year' => '2023'],
            [
                'title' => 'Reconocimiento Internacional',
                'title_en' => 'International Recognition',
                'description' => 'Nuestra barra Naranja 70% es galardonada con la Medalla de Oro en los International Chocolate Awards.',
                'description_en' => 'Our Naranja 70% bar is awarded the Gold Medal at the International Chocolate Awards.',
                'order' => 3,
                'is_active' => true,
            ]
        );
    }
}
