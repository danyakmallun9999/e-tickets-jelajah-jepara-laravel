<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            [
                'title' => 'Digitalisasi Pariwisata: Dinas Pariwisata Jepara Gandeng Mahasiswa UNISNU Luncurkan Platform "Jelajah Jepara"',
                'title_en' => 'Tourism Digitalization: Jepara Tourism Office Collaborates with UNISNU Students to Launch "Jelajah Jepara" Platform',
                'content' => self::toEditorJs([
                    ['type' => 'paragraph', 'text' => '<b>JEPARA</b> – Sektor pariwisata di Kabupaten Jepara kini memasuki babak baru dalam transformasi digital. Sebagai upaya meningkatkan aksesibilitas informasi dan layanan bagi wisatawan, sebuah platform inovatif bernama <b>"Jelajah Jepara"</b> resmi dikembangkan.'],
                    ['type' => 'paragraph', 'text' => 'Menariknya, aplikasi ini merupakan buah karya kolaborasi strategis dengan talenta muda lokal dari <b>Universitas Islam Nahdlatul Ulama (UNISNU) Jepara</b>. Tim pengembang yang terdiri dari empat mahasiswa kreatif—<b>Dany Akmallun Niam, Indonesiana Prima, Zaini Leon Mustofa Kamal, dan Muhammad Adimas Satria</b>—berhasil menerjemahkan visi Dinas Pariwisata ke dalam sebuah sistem informasi yang modern dan user-friendly.'],
                    ['type' => 'header', 'text' => 'Solusi Satu Pintu untuk Wisatawan', 'level' => 3],
                    ['type' => 'paragraph', 'text' => 'Website Jelajah Jepara hadir sebagai solusi <i>one-stop-service</i> bagi siapa saja yang ingin mengeksplorasi keindahan "Kota Ukir". Melalui platform ini, pengunjung dapat dengan mudah mengakses berbagai fitur unggulan, antara lain:'],
                    ['type' => 'list', 'style' => 'ordered', 'items' => [
                        '<b>Informasi Destinasi Terintegrasi:</b> Mulai dari wisata pantai yang eksotis, situs sejarah, hingga destinasi budaya yang tersebar di seluruh pelosok Jepara.',
                        '<b>Sistem E-Ticketing:</b> Memudahkan wisatawan memesan tiket masuk destinasi favorit secara daring, mengurangi antrean fisik, dan mendukung sistem pembayaran non-tunai.',
                        '<b>Kalender Acara (Event):</b> Memastikan wisatawan tidak ketinggalan berbagai festival budaya dan agenda rutin pariwisata yang diselenggarakan sepanjang tahun.',
                        '<b>Peta Eksplorasi Digital:</b> Fitur pemetaan yang membantu pengguna menemukan lokasi wisata terdekat dengan navigasi yang akurat.',
                    ]],
                    ['type' => 'header', 'text' => 'Sinergi Akademisi dan Pemerintah', 'level' => 3],
                    ['type' => 'paragraph', 'text' => 'Dany Akmallun Niam, mewakili tim pengembang, menyampaikan bahwa proyek ini bukan sekadar tugas akhir atau praktikum, melainkan wujud nyata pengabdian mahasiswa untuk tanah kelahiran. "Kami membangun Jelajah Jepara dengan teknologi <i>web development</i> terkini agar performanya cepat dan aman. Kami berharap aplikasi ini bisa menjadi katalisator bagi kebangkitan ekonomi kreatif di Jepara," ujarnya.'],
                    ['type' => 'paragraph', 'text' => 'Pihak Dinas Pariwisata mengapresiasi tinggi dedikasi para mahasiswa UNISNU ini. Kehadiran website ini diharapkan mampu memperluas jangkauan promosi wisata Jepara hingga ke kancah mancanegara, sekaligus memberikan data statistik kunjungan yang lebih akurat untuk evaluasi kebijakan pariwisata ke depan.'],
                    ['type' => 'header', 'text' => 'Menuju Jepara Unggul', 'level' => 3],
                    ['type' => 'paragraph', 'text' => 'Dengan peluncuran Jelajah Jepara, diharapkan tidak ada lagi jarak antara wisatawan dan keindahan tersembunyi yang dimiliki Jepara. Inovasi ini membuktikan bahwa sinergi antara pemerintah daerah dan institusi pendidikan mampu menghasilkan produk teknologi yang berdampak luas bagi masyarakat.'],
                    ['type' => 'paragraph', 'text' => 'Ayo, mulai petualanganmu dan jelajahi pesona Jepara sekarang di platform digital kami!'],
                ]),
                'content_en' => self::toEditorJs([
                    ['type' => 'paragraph', 'text' => '<b>JEPARA</b> – The tourism sector in Jepara Regency has officially entered a new chapter of digital transformation. In an effort to enhance the accessibility of information and services for travelers, an innovative platform named <b>"Jelajah Jepara"</b> has been officially developed.'],
                    ['type' => 'paragraph', 'text' => 'In a remarkable move, this application is the result of a strategic collaboration with local young talents from the <b>Islamic Nahdlatul Ulama University (UNISNU) Jepara</b>. The development team, consisting of four creative students—<b>Dany Akmallun Niam, Indonesiana Prima, Zaini Leon Mustofa Kamal, and Muhammad Adimas Satria</b>—successfully translated the Tourism Office\'s vision into a modern and user-friendly information system.'],
                    ['type' => 'header', 'text' => 'A One-Stop Solution for Tourists', 'level' => 3],
                    ['type' => 'paragraph', 'text' => 'The Jelajah Jepara website serves as a <i>one-stop-service</i> solution for anyone looking to explore the beauty of the "Carving City." Through this platform, visitors can easily access various premium features, including:'],
                    ['type' => 'list', 'style' => 'ordered', 'items' => [
                        '<b>Integrated Destination Information:</b> Ranging from exotic beach tours and historical sites to cultural destinations spread across all corners of Jepara.',
                        '<b>E-Ticketing System:</b> Allows tourists to book entry tickets for their favorite destinations online, reducing physical queues and supporting non-cash payment systems.',
                        '<b>Events Calendar:</b> Ensures tourists don\'t miss out on various cultural festivals and routine tourism agendas held throughout the year.',
                        '<b>Digital Exploration Map:</b> A mapping feature that helps users find nearby tourist locations with accurate navigation.',
                    ]],
                    ['type' => 'header', 'text' => 'Synergy Between Academia and Government', 'level' => 3],
                    ['type' => 'paragraph', 'text' => 'Dany Akmallun Niam, representing the development team, stated that this project is not merely a final assignment or a practical course project, but a tangible manifestation of students\' dedication to their hometown. "We built Jelajah Jepara using the latest <i>web development</i> technologies to ensure fast and secure performance. We hope this application can become a catalyst for the revival of the creative economy in Jepara," he said.'],
                    ['type' => 'paragraph', 'text' => 'The Tourism Office highly appreciates the dedication of these UNISNU students. The presence of this website is expected to expand the promotional reach of Jepara tourism to the international stage, while providing more accurate visitor statistics for future tourism policy evaluations.'],
                    ['type' => 'header', 'text' => 'Towards a Prosperous Jepara', 'level' => 3],
                    ['type' => 'paragraph', 'text' => 'With the launch of Jelajah Jepara, it is hoped that there will no longer be a gap between tourists and the hidden gems that Jepara possesses. This innovation proves that synergy between local government and educational institutions can produce technological products with a wide impact on society.'],
                    ['type' => 'paragraph', 'text' => 'Come, start your adventure and explore the charms of Jepara now on our digital platform!'],
                ]),
                'content_format' => 'editorjs',
                'type' => 'news',
                'author' => 'Admin Jelajah Jepara',
                'image_path' => '/images/posts/mahasiswa-unisnu.png',
                'published_at' => now()->subDays(1),
                'is_published' => true,
            ],
        ];

        foreach ($posts as $post) {
            $post['slug'] = Str::slug($post['title']) . '-' . time();
            Post::create($post);
        }
    }

    /**
     * Convert a simple block array to Editor.js JSON format.
     */
    private static function toEditorJs(array $blocks): string
    {
        $editorBlocks = [];
        foreach ($blocks as $block) {
            $editorBlock = [
                'id' => Str::random(10),
                'type' => $block['type'],
                'data' => [],
            ];

            switch ($block['type']) {
                case 'paragraph':
                    $editorBlock['data']['text'] = $block['text'];
                    break;
                case 'header':
                    $editorBlock['data']['text'] = $block['text'];
                    $editorBlock['data']['level'] = $block['level'] ?? 2;
                    break;
                case 'list':
                    $editorBlock['data']['style'] = $block['style'] ?? 'unordered';
                    $editorBlock['data']['items'] = $block['items'];
                    break;
            }

            $editorBlocks[] = $editorBlock;
        }

        return json_encode([
            'time' => now()->timestamp * 1000,
            'blocks' => $editorBlocks,
            'version' => '2.31.3',
        ], JSON_UNESCAPED_UNICODE);
    }
}
