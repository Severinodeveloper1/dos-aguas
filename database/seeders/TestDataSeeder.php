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
                'name_en' => 'Pure Essence',
                'name_de' => 'Reine Essenz',
                'description' => 'Chocolates oscuros elaborados con porcentajes de cacao orgánico fino de aroma (70% y 100%).',
                'description_en' => 'Dark chocolates made with fine aroma organic cacao percentages (70% and 100%).',
                'description_de' => 'Dunkle Schokoladen aus feinem Bio-Edelkakao (70% und 100%).',
                'order' => 1,
                'is_active' => true,
                'meta_title' => 'Chocolates Esencia Pura | Dos Aguas',
                'meta_title_en' => 'Pure Essence Chocolates | Dos Aguas',
                'meta_title_de' => 'Reine Essenz Schokoladen | Dos Aguas',
                'meta_description' => 'Disfruta de nuestros chocolates de 70% y 100% cacao fino de aroma.',
                'meta_description_en' => 'Enjoy our 70% and 100% fine aroma cacao chocolates.',
                'meta_description_de' => 'Genießen Sie unsere 70% und 100% Edelkakao-Schokoladen.',
            ]
        );

        $cat2 = Category::firstOrCreate(
            ['slug' => 'infusiones-amazonicas'],
            [
                'name' => 'Infusiones Amazónicas',
                'name_en' => 'Amazonian Infusions',
                'name_de' => 'Amazonische Aufgüsse',
                'description' => 'Infusiones naturales de cascarilla de cacao combinadas con hierbas aromáticas de la selva y andes.',
                'description_en' => 'Natural cacao husk infusions combined with aromatic herbs from the jungle and Andes.',
                'description_de' => 'Natürliche Kakaoschalen-Aufgüsse kombiniert mit Kräutern aus Dschungel und Anden.',
                'order' => 2,
                'is_active' => true,
                'meta_title' => 'Infusiones Amazónicas de Cacao | Dos Aguas',
                'meta_title_en' => 'Amazonian Cacao Infusions | Dos Aguas',
                'meta_title_de' => 'Amazonische Kakao-Aufgüsse | Dos Aguas',
                'meta_description' => 'Infusiones saludables hechas con cascarilla de cacao y hierbas naturales.',
                'meta_description_en' => 'Healthy infusions made with cocoa husk and natural herbs.',
                'meta_description_de' => 'Gesunde Aufgüsse aus Kakaoschalen und natürlichen Kräutern.',
            ]
        );

        // 3. Seed Products & Variants
        // Product 1
        $prod1 = Product::firstOrCreate(
            ['slug' => 'chocolate-ucayali-70'],
            [
                'category_id' => $cat1->id,
                'name' => 'Chocolate Ucayali 70% Cacao',
                'name_en' => 'Ucayali 70% Cacao Chocolate',
                'name_de' => 'Ucayali 70% Kakao Schokolade',
                'description' => '<p>Barra de chocolate premium elaborada con cacao seleccionado del cruce de los ríos Aguaytía y San Alejandro en Ucayali. Sabor intenso con notas frutales y un toque cítrico.</p>',
                'description_en' => '<p>Premium chocolate bar crafted with selected cacao from the confluence of the Aguaytía and San Alejandro rivers in Ucayali. Intense flavor with fruity notes and a citrus touch.</p>',
                'description_de' => '<p>Premium-Schokoladentafel aus feinstem Kakao von der Mündung der Flüsse Aguaytía und San Alejandro in Ucayali. Intensiver Geschmack mit fruchtigen Noten und einer Zitrusnote.</p>',
                'tasting_notes' => '<p>Notas cítricas pronunciadas de Hierba Luisa y frutos amarillos con un final prolongado a cacao tostado.</p>',
                'tasting_notes_en' => '<p>Pronounced citrus notes of Lemon Verbena and yellow fruits with a prolonged roasted cocoa finish.</p>',
                'tasting_notes_de' => '<p>Ausgeprägte Zitrusnoten von Zitronenstrauch und gelben Früchten mit einem langanhaltenden Kakao-Abgang.</p>',
                'natural_benefits' => '<p>Rico en antioxidantes naturales, ayuda a mejorar el estado de ánimo y estimula la salud cardiovascular.</p>',
                'natural_benefits_en' => '<p>Rich in natural antioxidants, helps improve mood and stimulates cardiovascular health.</p>',
                'natural_benefits_de' => '<p>Reich an natürlichen Antioxidantien, hilft die Stimmung zu verbessern und fördert die Herzkreislauf-Gesundheit.</p>',
                'nutritional_values' => [
                    ['label' => 'Calorías', 'value' => '145 kcal'],
                    ['label' => 'Grasa Total', 'value' => '9g (12%)'],
                    ['label' => 'Carbohidratos', 'value' => '12g (4%)'],
                    ['label' => 'Azúcares', 'value' => '5g'],
                    ['label' => 'Proteínas', 'value' => '2g'],
                ],
                'nutritional_values_en' => [
                    ['label' => 'Calories', 'value' => '145 kcal'],
                    ['label' => 'Total Fat', 'value' => '9g (12%)'],
                    ['label' => 'Carbohydrates', 'value' => '12g (4%)'],
                    ['label' => 'Sugars', 'value' => '5g'],
                    ['label' => 'Protein', 'value' => '2g'],
                ],
                'nutritional_values_de' => [
                    ['label' => 'Kalorien', 'value' => '145 kcal'],
                    ['label' => 'Gesamtfett', 'value' => '9g (12%)'],
                    ['label' => 'Kohlenhydrate', 'value' => '12g (4%)'],
                    ['label' => 'Zucker', 'value' => '5g'],
                    ['label' => 'Eiweiß', 'value' => '2g'],
                ],
                'images' => [],
                'is_active' => true,
                'meta_title' => 'Chocolate de Ucayali 70% Cacao Fino | Dos Aguas',
                'meta_title_en' => 'Ucayali 70% Fine Cacao Chocolate | Dos Aguas',
                'meta_title_de' => 'Ucayali 70% Edelkakao Schokolade | Dos Aguas',
                'meta_description' => 'Exquisito chocolate peruano de origen con 70% de cacao seleccionado de la cuenca de Ucayali.',
                'meta_description_en' => 'Exquisite Peruvian single-origin chocolate with 70% selected cocoa from Ucayali.',
                'meta_description_de' => 'Exquisite peruanische Herkunftsschokolade mit 70% feinstem Kakao aus Ucayali.',
            ]
        );

        // Product 1 Variants
        ProductVariant::firstOrCreate(
            ['sku' => 'DA-CHO-UCA70-100G'],
            [
                'product_id' => $prod1->id,
                'name' => 'Barra Individual 100g',
                'name_en' => 'Single Bar 100g',
                'name_de' => 'Einzelne Tafel 100g',
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
                'name_en' => 'Medium Pack 250g',
                'name_de' => 'Mittlere Packung 250g',
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
                'name_en' => 'Cover Block 1kg',
                'name_de' => 'Kuvertüre Block 1kg',
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
                'name_en' => 'Maquila Cover Box 5kg',
                'name_de' => 'Kuvertüre Kiste 5kg',
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
                'name_en' => 'Cacao & Lemon Verbena Tea',
                'name_de' => 'Kakao & Zitronenstrauch Aufguss',
                'description' => '<p>Mezcla equilibrada de cascarilla de cacao orgánico tostado y hojas secas de hierba luisa silvestre, brindando una infusión refrescante y digestiva.</p>',
                'description_en' => '<p>Balanced blend of roasted organic cocoa husk and wild lemon verbena leaves, offering a refreshing and digestive herbal tea.</p>',
                'description_de' => '<p>Ausgewogene Mischung aus gerösteter Bio-Kakaoschale und wilden Zitronenstrauchblättern, die einen erfrischenden und verdauungsfördernden Tee ergibt.</p>',
                'tasting_notes' => '<p>Aroma cítrico herbal con notas sutiles de chocolate y madera dulce.</p>',
                'tasting_notes_en' => '<p>Herbal citrus aroma with subtle notes of chocolate and sweet wood.</p>',
                'tasting_notes_de' => '<p>Kräuter-Zitrus-Aroma mit subtilen Noten von Schokolade und süßem Holz.</p>',
                'natural_benefits' => '<p>Excelente digestivo natural, ayuda a relajar el sistema nervioso y alivia la congestión leve de vías respiratorias.</p>',
                'natural_benefits_en' => '<p>Excellent natural digestive aid, helps relax the nervous system and relieves mild congestion.</p>',
                'natural_benefits_de' => '<p>Hervorragendes natürliches Verdauungsmittel, hilft das Nervensystem zu entspannen und lindert leichte Beschwerden.</p>',
                'nutritional_values' => [
                    ['label' => 'Calorías', 'value' => '0 kcal'],
                    ['label' => 'Grasa Total', 'value' => '0g'],
                    ['label' => 'Azúcares', 'value' => '0g'],
                    ['label' => 'Sodio', 'value' => '0mg'],
                ],
                'nutritional_values_en' => [
                    ['label' => 'Calories', 'value' => '0 kcal'],
                    ['label' => 'Total Fat', 'value' => '0g'],
                    ['label' => 'Sugars', 'value' => '0g'],
                    ['label' => 'Sodium', 'value' => '0mg'],
                ],
                'nutritional_values_de' => [
                    ['label' => 'Kalorien', 'value' => '0 kcal'],
                    ['label' => 'Gesamtfett', 'value' => '0g'],
                    ['label' => 'Zucker', 'value' => '0g'],
                    ['label' => 'Natrium', 'value' => '0mg'],
                ],
                'images' => [],
                'is_active' => true,
                'meta_title' => 'Infusión Cacao y Hierba Luisa Digestiva | Dos Aguas',
                'meta_title_en' => 'Digestive Cacao & Lemon Verbena Tea | Dos Aguas',
                'meta_title_de' => 'Verdauungsfördernder Kakao & Zitronenstrauch Tee | Dos Aguas',
                'meta_description' => 'Combina las propiedades antioxidantes del cacao con la frescura digestiva de la hierba luisa.',
                'meta_description_en' => 'Combines the antioxidant properties of cacao with the digestive freshness of lemon verbena.',
                'meta_description_de' => 'Kombiniert die antioxidativen Eigenschaften von Kakao mit der Frische des Zitronenstrauchs.',
            ]
        );

        // Product 2 Variants
        ProductVariant::firstOrCreate(
            ['sku' => 'DA-INF-HLCA-20FILT'],
            [
                'product_id' => $prod2->id,
                'name' => 'Caja de 20 Filtrantes',
                'name_en' => 'Box of 20 Tea Bags',
                'name_de' => 'Schachtel mit 20 Filterbeuteln',
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
                'title_en' => 'Hacienda Dos Aguas',
                'title_de' => 'Hacienda Dos Aguas',
                'subtitle' => 'El cruce de los ríos Aguaytía y San Alejandro da vida al mejor chocolate artesanal.',
                'subtitle_en' => 'The confluence of the Aguaytía and San Alejandro rivers gives life to the finest artisanal chocolate.',
                'subtitle_de' => 'Die Mündung der Flüsse Aguaytía und San Alejandro erweckt die feinste handwerkliche Schokolade zum Leben.',
                'button_text' => 'Conocer Historia',
                'button_text_en' => 'Our Story',
                'button_text_de' => 'Unsere Geschichte',
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
                'title_en' => 'Biodiversity at Hacienda Dos Aguas: Agoutis and Sloths',
                'title_de' => 'Artenvielfalt auf der Hacienda Dos Aguas: Agutis und Faultiere',
                'excerpt' => 'Descubre la increíble variedad de fauna y flora que convive en nuestra hacienda y enriquece las tierras de nuestro cacao.',
                'excerpt_en' => 'Discover the incredible variety of fauna and flora that coexist in our hacienda and enrich our cocoa lands.',
                'excerpt_de' => 'Entdecken Sie die fantastische Vielfalt an Fauna und Flora, die auf unserer Hacienda zusammenlebt und unseren Kakao bereichert.',
                'content' => '<p>Nuestra hacienda familiar en la región de Ucayali no solo es el hogar de árboles de cacao vigorosos, sino también un santuario vivo de biodiversidad. Al recorrer los linderos sombreados, es común encontrarse con añujes recolectando frutos caídos y tímidos osos perezosos de tres dedos colgando de las copas de las cecropias.</p><p>Esta interacción ecológica es vital: la fauna local contribuye a la polinización y dispersión de semillas, manteniendo un suelo rico en materia orgánica que aporta notas minerales y frutales únicas a nuestras barras de chocolate Bean to Bar.</p>',
                'content_en' => '<p>Our family hacienda in the Ucayali region is not only home to vigorous cocoa trees, but also a living sanctuary of biodiversity. Walking through the shaded borders, it is common to find agoutis gathering fallen fruits and timid three-toed sloths hanging from the Cecropia canopies.</p><p>This ecological interaction is vital: local wildlife contributes to pollination and seed dispersal, keeping soil rich in organic matter that brings unique mineral and fruity notes to our Bean to Bar chocolate bars.</p>',
                'content_de' => '<p>Unsere Familien-Hacienda in der Region Ucayali ist nicht nur die Heimat kräftiger Kakaobäume, sondern auch ein lebendiges Schutzgebiet der Artenvielfalt. Beim Spaziergang durch die schattigen Ränder begegnet man häufig Agutis, die herabgefallene Früchte sammeln, und scheuen Dreizehen-Faultieren.</p><p>Diese ökologische Interaktion ist entscheidend: Die lokale Tierwelt trägt zur Bestäubung und Samenverbreitung bei und hält den Boden reich an organischer Substanz, die unseren Schokoladentafeln einzigartige mineralische und fruchtige Noten verleiht.</p>',
                'image_path' => 'posts/biodiversidad.webp',
                'published_at' => now(),
                'is_active' => true,
                'meta_title' => 'Biodiversidad y Vida Silvestre en Hacienda Dos Aguas',
                'meta_title_en' => 'Biodiversity and Wildlife at Hacienda Dos Aguas',
                'meta_title_de' => 'Artenvielfalt und Tierwelt auf der Hacienda Dos Aguas',
                'meta_description' => 'Conoce cómo los añujes, osos perezosos y el ecosistema de Ucayali influyen en la calidad del cacao artesanal de Dos Aguas.',
                'meta_description_en' => 'Learn how agoutis, sloths and the Ucayali ecosystem influence the quality of Dos Aguas artisanal cacao.',
                'meta_description_de' => 'Erfahren Sie, wie Agutis, Faultiere und das Ökosystem von Ucayali die Qualität des Kakaos von Dos Aguas beeinflussen.',
            ]
        );

        // 9. Seed Timeline Events
        \App\Models\TimelineEvent::firstOrCreate(
            ['year' => '2018'],
            [
                'title' => 'Fundación de Hacienda',
                'title_en' => 'Hacienda Founding',
                'title_de' => 'Gründung der Hacienda',
                'description' => 'Se adquieren los linderos en Ucayali flanqueados por los dos ríos, iniciando el cultivo agroecológico bajo sombra natural.',
                'description_en' => 'Lands in Ucayali flanked by two rivers are acquired, starting agroecological cultivation under natural shade.',
                'description_de' => 'Ländereien in Ucayali an zwei Flüssen werden erworben und der ökologische Anbau unter natürlichem Schatten beginnt.',
                'order' => 1,
                'is_active' => true,
            ]
        );

        \App\Models\TimelineEvent::firstOrCreate(
            ['year' => '2020'],
            [
                'title' => 'Primera Cosecha Selectiva',
                'title_en' => 'First Selective Harvest',
                'title_de' => 'Erste selektive Ernte',
                'description' => 'Tras años de cuidado artesanal de la tierra, cosechamos la primera producción selecta de cacao fino de aroma.',
                'description_en' => 'After years of artisanal care of the soil, we harvested the first select yield of fine aroma cacao.',
                'description_de' => 'Nach Jahren handwerklicher Bodenpflege ernteten wir die erste selektive Ausbeute an feinstem Edelkakao.',
                'order' => 2,
                'is_active' => true,
            ]
        );

        \App\Models\TimelineEvent::firstOrCreate(
            ['year' => '2023'],
            [
                'title' => 'Reconocimiento Internacional',
                'title_en' => 'International Recognition',
                'title_de' => 'Internationale Anerkennung',
                'description' => 'Nuestra barra Naranja 70% es galardonada con la Medalla de Oro en los International Chocolate Awards.',
                'description_en' => 'Our Naranja 70% bar is awarded the Gold Medal at the International Chocolate Awards.',
                'description_de' => 'Unsere Tafel Naranja 70% wird bei den International Chocolate Awards mit der Goldmedaille ausgezeichnet.',
                'order' => 3,
                'is_active' => true,
            ]
        );

        // 10. Seed Awards
        \App\Models\Award::firstOrCreate(
            ['title' => 'Medalla de Oro - Ucayali 70%'],
            [
                'description' => 'Mejor barra de chocolate oscuro de origen en los International Chocolate Awards.',
                'country' => 'Bélgica',
                'date' => '2023-11-12',
                'product_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBo0TfU1QE9Z3g6KSWzthQP8n3tJ1hrZprdYQTxSGqYo3njjVn7K0EKfw6840qBScOEn0HTTKh7RfwgbYhc3uuB1HNOvk-fnigdd0OG_HVwRALCiB15nolipjeuIOpQpuzA8Oa07jpbRIk6olmFouGsmhofVUpJnMZSE93cBk2khFLYCe96pSEjPIgvEwvZkU-MBVjZWAVH1i8XVY8C0xUNcB_PtOfwrj4UHOsI5v-n_r88KQibk4B5bqfALWtf5Ff2UIXiqelLVb4',
                'medal_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCq0lR2foQLeFO6xaWDpIcAAaaVVvZu20VMSzkcP2lLvgdDszeCrX25G4vAKstjPj7H4fq6wFAkSA2LMEy--Tkco2UV6USH96XUsrqzVP4GFdr0WXY8_4G_EApwGRbW_toWpQLkcp8t-omfVjNs5n83h3IERzAFtb6F6_Taik4iz0hoCDTPn1el_AUtCMtF_EvUyXSWAYhQDTg9m4Vd8GTr3x72I3edlu_AX-aBAsBT2wMyxAgtouqlzgvepLMQNdZNR7izO0sF2Ac',
                'certificate_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuCMjIjbQezVbdWabzkrGNdze2_zQ3umglIvC7b0TMaM1iSZ02hGTQBbifYtEpyexm1ZbshF9SFBd1rZcGqAPiBBdwLkNCtpAJIyyYuQDHPv-iPSe1kpEmcWO1di8i-RB6L1w0-3bgWo9CU8gzQ6pnSfeI3Ie8asUdcYvn5xRthpQy-bDcRNgpNRjj4MZ9msCnqG2GyGhTetslOby228jsTAJ3sV2lRFP_TOSPapFuhK8HQuhE4uoxk855njunPeTRkmJQLSjzgHf5Y',
            ]
        );

        // 11. Seed Policies (ES, EN, DE)
        \App\Models\Policy::firstOrCreate(
            ['slug' => 'politica-de-privacidad'],
            [
                'title' => 'Política de Privacidad y Protección de Datos',
                'title_en' => 'Privacy Policy & Data Protection',
                'title_de' => 'Datenschutz- und Datenverarbeitungsrichtlinie',
                'content' => '<p>En Hacienda Dos Aguas nos comprometemos a proteger la privacidad de nuestros clientes. Los datos personales recolectados a través de nuestro sitio web se utilizan exclusivamente para procesar pedidos, coordinar el envío de chocolates finos y brindar una experiencia de compra personalizada.</p><p>Garantizamos que su información no será vendida ni compartida con terceros con fines publicitarios.</p>',
                'content_en' => '<p>At Hacienda Dos Aguas we are committed to protecting our customers\' privacy. Personal data collected through our website is used exclusively to process orders, coordinate fine chocolate shipments, and offer a personalized shopping experience.</p><p>We guarantee your information will not be sold or shared with third parties for commercial purposes.</p>',
                'content_de' => '<p>Bei Hacienda Dos Aguas verpflichten wir uns zum Schutz der Privatsphäre unserer Kunden. Die über unsere Website gesammelten personenbezogenen Daten werden ausschließlich zur Bearbeitung von Bestellungen, zur Koordinierung von Schokoladenlieferungen und zur Bereitstellung eines personalisierten Einkaufserlebnisses verwendet.</p><p>Wir garantieren, dass Ihre Daten nicht an Dritte verkauft oder weitergegeben werden.</p>',
                'order' => 1,
                'is_active' => true,
            ]
        );

        \App\Models\Policy::firstOrCreate(
            ['slug' => 'politica-de-envios-y-devoluciones'],
            [
                'title' => 'Política de Envíos y Devoluciones',
                'title_en' => 'Shipping & Returns Policy',
                'title_de' => 'Versand- und Rückgabebelehrung',
                'content' => '<p>Realizamos envíos nacionales e internacionales cuidando el embalaje térmico para asegurar la frescura del chocolate. Si su pedido presenta algún inconveniente durante el traslado, por favor contáctenos dentro de las 48 horas posteriores a la recepción para coordinar el reemplazo o reembolso correspondiente.</p>',
                'content_en' => '<p>We offer national and international shipping using thermal packaging to maintain chocolate freshness. If your order experiences any issues during transit, please contact us within 48 hours of receipt to arrange a replacement or refund.</p>',
                'content_de' => '<p>Wir bieten nationalen und internationalen Versand in Thermoverpackung an, um die Frische der Schokolade zu gewährleisten. Sollte Ihre Bestellung beim Transport beschädigt werden, kontaktieren Sie uns bitte innerhalb von 48 Stunden nach Erhalt.</p>',
                'order' => 2,
                'is_active' => true,
            ]
        );

        \App\Models\Policy::firstOrCreate(
            ['slug' => 'politica-de-cookies'],
            [
                'title' => 'Política de Uso de Cookies',
                'title_en' => 'Cookie Usage Policy',
                'title_de' => 'Cookie-Richtlinie',
                'content' => '<p>Nuestro sitio web emplea cookies exclusivamente con fines técnicos esenciales: recordar los artículos añadidos al carrito de compras, mantener la seguridad de sus formularios mediante tokens CSRF y guardar su preferencia de idioma (Español, Inglés o Alemán). No utilizamos cookies publicitarias de terceros para rastreo o venta de información.</p>',
                'content_en' => '<p>Our website uses cookies exclusively for essential technical purposes: remembering items added to your shopping cart, maintaining form security via CSRF tokens, and saving your language preference (Spanish, English, or German). We do not use third-party advertising cookies for tracking or data sales.</p>',
                'content_de' => '<p>Unsere Website verwendet Cookies ausschließlich für notwendige technische Zwecke: Speichern von Artikeln im Warenkorb, Aufrechterhaltung der Formularsicherheit und Speichern Ihrer Sprachpräferenz. Wir verwenden keine Werbe-Cookies von Drittanbietern.</p>',
                'order' => 3,
                'is_active' => true,
            ]
        );
    }
}
