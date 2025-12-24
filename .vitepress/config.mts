import { defineConfig } from 'vitepress'

export default defineConfig({
    title: "PHP OOP Course",
    description: "Kursus Pemrograman Berorientasi Objek dengan PHP - Dari Dasar hingga Mahir",
    lang: 'id-ID',
    base: '/php-oop-course/',
    
    head: [
        ['link', { rel: 'icon', type: 'image/svg+xml', href: '/php-oop-course/logo.svg' }],
    ],

    themeConfig: {
        logo: '/logo.svg',
        siteTitle: 'PHP OOP Course',

        nav: [
            { text: 'Home', link: '/' },
            { text: 'Mulai Belajar', link: '/pertemuan-01/' },
            { text: 'GitHub', link: 'https://github.com/mahendartea/php-oop-course' }
        ],

        sidebar: [
            {
                text: '📚 Modul 1: Fondasi OOP',
                collapsed: false,
                items: [
                    { text: '01. Pengenalan OOP', link: '/pertemuan-01/' },
                    { text: '02. Properti dan Method', link: '/pertemuan-02/' },
                    { text: '03. Constructor & Destructor', link: '/pertemuan-03/' },
                    { text: '04. Inheritance', link: '/pertemuan-04/' },
                ]
            },
            {
                text: '🔒 Modul 2: Encapsulation & Abstraction',
                collapsed: false,
                items: [
                    { text: '05. Visibility', link: '/pertemuan-05/' },
                    { text: '06. Abstract Class', link: '/pertemuan-06/' },
                    { text: '07. Interface', link: '/pertemuan-07/' },
                ]
            },
            {
                text: '🚀 Modul 3: Advanced OOP',
                collapsed: false,
                items: [
                    { text: '09. Static Properties & Methods', link: '/pertemuan-09/' },
                    { text: '10. Traits', link: '/pertemuan-10/' },
                    { text: '11. Namespaces & Autoloading', link: '/pertemuan-11/' },
                ]
            },
            {
                text: '🏗️ Modul 4: Design Patterns',
                collapsed: false,
                items: [
                    { text: '12. Error Handling', link: '/pertemuan-12/' },
                    { text: '13. Prinsip SOLID', link: '/pertemuan-13/' },
                    { text: '14. Design Patterns', link: '/pertemuan-14/' },
                    { text: '15. Studi Kasus CRUD', link: '/pertemuan-15/' },
                ]
            }
        ],

        socialLinks: [
            { icon: 'github', link: 'https://github.com/mahendartea' }
        ],

        footer: {
            message: 'Ditulis dan dikembangkan oleh Mahendar Dwi Payana.',
            copyright: 'Copyright © 2025 PHP OOP Course'
        },

        search: {
            provider: 'local'
        }
    }
})
