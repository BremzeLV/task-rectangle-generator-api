
<h1>
    Live ip: http://18.184.62.150    
</h1>
<h3>Routes</h3>
<a href="http://18.184.62.150/generation-status?id={id}">GET http://18.184.62.150/generation-status?id={id}</a><br />
<a href="http://18.184.62.150/generate-rectangles">POST http://18.184.62.150/generate-rectangles</a><br />
<pre><code style="display: block;
  white-space: pre-wrap  ">
    {
    "width": 1024,
    "height": 1024,
    "color": "#fff",
    "rectangles": [{
    "id": "my-id3",
    "x": 10,
    "y": 10,
    "height": 100,
    "width": 200,
    "color": "#000"
    },
    {
    "id": "my-id4",
    "x": 100,
    "y": 100,
    "height": 100,
    "width": 200,
    "color": "#000"
    }]}
</code></pre>
<a href="http://18.184.62.150/generate-rectangles">GET http://18.184.62.150/generate-rectangles</a>


<hr />
<h2>
 Task description:
</h2>
<code>
    Izveidot php API, kas spēj ģenerēt PNG bildes ar aizpildītiem taisnstūriem
Detalizēta specifikācija

1) API Atbild uz HTTP pieprasījumu, reģistrē PNG bildes ģenerēšanas pieteikumu un atgriež tā unikālo identifikātoru.
Ja pieprasījuma kvadrāti pārklājas, robežojas ar citiem kvadrātiem vai bildes malām, kā arī, ja pieprasījumā tiek nosūtīti dati, kas kaut kādā veidā nav izpildāmi (negatīvas, nepareizas vērtības, nekorekta json struktūra utml.), tad jāatgriež kļūdu paziņojumi ar informāciju par datu nesakritību.
Pieprasījuma piemērs

curl -X POST http://your-server/generate-rectangles -d '
{
    width: 1024, // jebkāds pozitīvs skaitlis robežās [640, 1920]
    height: 1024, // jebkāds pozitīvs skaitlis robežās [480, 1080]
    color: '#fff', // jebkāds HEX krāsu kods
    rectangles: [
        { 
            id: 'my-id' //jebkāds teksts vai skaitlis, kas nepārsniedz 255 simbolus un ir unikāls visa rectangles masīva ietvaros
            x: 10, // jebkāds pozitīvs skaitlis
            y: 10, // jebkāds pozitīvs skaitlis
            height: 100, // jebkāds pozitīvs skaitlis
            width: 200, // jebkāds pozitīvs skaitlis
            color: '#000' // jebkāds HEX krāsu kods
        },
        ...
    ]
}'

Veiksmīgas apstrādes atbildes piemērs:
{
    success: true,
    id: auto-generated-identifier
}
Neveiksmīgas atbildes piemērs (kļūdu paziņojumu struktūra ir atvērta interpretācijai):
{
    success: false,
    errors: {
        'rectangles_overlap': ['rectangle_id', ...],
        'rectangles_out_of_bounds': ['rectangle_id'],
        'image_doesnt_fit_constraints': ['width'],
        'malformatted_json': []
    }
}

2) Pēc manuāla faila izsaukuma (aka CRON) uzģenerē vecāko bildes ģenerēšanas pieteikumu
3) Pēc pieprasījuma curl -X GET http://your-server/generation-status?id=auto-generated-identifier
atgriež šobrīdējo bildes ģenerēšanas stāvokli (pending, failed, in_progress, done)
Piemērs pending: 
{
    status: 'pending'
    queue_length: 5 // bilžu skaits, kas ir priekšā
}
Piemērs failed:  
{
    status: 'failed'
    reason: 'item_not_found' //notikusi kāda kļūme
}
Piemērs in_progress:  
{
    status: 'in_progress'
}
Piemērs done:  
{
    status: 'done'
    url: 'http://your-server/generated-image-location //bildes url, kurā to var saņemt kā (Content-Type: image/png)
}
    </code>

<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[British Software Development](https://www.britishsoftware.co)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- [UserInsights](https://userinsights.com)
- [Fragrantica](https://www.fragrantica.com)
- [SOFTonSOFA](https://softonsofa.com/)
- [User10](https://user10.com)
- [Soumettre.fr](https://soumettre.fr/)
- [CodeBrisk](https://codebrisk.com)
- [1Forge](https://1forge.com)
- [TECPRESSO](https://tecpresso.co.jp/)
- [Runtime Converter](http://runtimeconverter.com/)
- [WebL'Agence](https://weblagence.com/)
- [Invoice Ninja](https://www.invoiceninja.com)
- [iMi digital](https://www.imi-digital.de/)
- [Earthlink](https://www.earthlink.ro/)
- [Steadfast Collective](https://steadfastcollective.com/)
- [We Are The Robots Inc.](https://watr.mx/)
- [Understand.io](https://www.understand.io/)
- [Abdel Elrafa](https://abdelelrafa.com)
- [Hyper Host](https://hyper.host)
- [Appoly](https://www.appoly.co.uk)
- [OP.GG](https://op.gg)

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
