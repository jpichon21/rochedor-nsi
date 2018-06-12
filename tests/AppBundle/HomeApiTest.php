<?php
namespace Tests\AppBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use AppBundle\Entity\Page;

class HomeApiTest extends WebTestCase
{
    public function testGetOneHome()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/home/fr');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testReturnedJsonGetOneHome()
    {
        $client = self::createClient();
        $crawler =$client->request('GET', '/api/home/en');
        $response = $client->getResponse()->getContent();
        $arrayResponse = json_decode($response, true);
        
        $homeTest = json_decode('{ 
        "id":7, 
        "title":"Homepage", 
        "sub_title":"My Homepage", 
        "description":"The homepage description", 
        "content":{ 
        "intro":"A page to check the CMS module", 
        "sections":[ 
        { 
        "title":"The Community", 
        "body":"<h2>Un titre 2<\\/h2>\\n<h3>Un titre 3<\\/h3>
        \\n<h4>Un titre 4<\\/h4>
        \\n<h5>un titre 5<\\/h5>
        \\n<h6>un titre 6<\\/h6>
        \\n<blockquote>Une citation<\\/blockquote>
        \\n<ul>\\n<li>Liste a puce 1<\\/li>
        \\n<li>Liste a puce 2<\\/li>
        \\n<li>Liste a puce 3<\\/li>
        \\n<\\/ul>\\n<p><strong>Texte en gras<\\/strong>
        <\\/p>\\n<p><em>Texte en Italique<\\/em>
        <\\/p>\\n<p>Texte Normal<\\/p>\\n", 
        "sub_title":"And His House" 
        }, 
        { 
        "title":"The", 
        "body":"<h2>Un titre 2<\\/h2>
        \\n<h3>Un titre 3<\\/h3>
        \\n<h4>Un titre 4<\\/h4>
        \\n<h5>un titre 5<\\/h5>
        \\n<h6>un titre 6<\\/h6>\\
        n<blockquote><em>
        Une citation<\\/em>
        <\\/blockquote>\\n<ul>
        \\n<li>Liste a puce 1<\\/li>
        \\n<li>Liste a puce 2<\\/li>
        \\n<li>Liste a puce 3<\\/li
        \\n<\\/ul>\\n<p><strong>Texte en gras<\\/strong>
        <\\/p>\\n<p><em>Texte en Italique<\\/em><\\/p>\\n<p>Texte Normal<\\/p>\\n", 
        "sub_title":"Pensions" 
        }, 
        { 
        "title":"The", 
        "body":"<h2>Un titre 2<\\/h2>\\n<h3>Un titre 3
        <\\/h3>\\n<h4>Un titre 4<\\/h4>\\n<h5>un titre 5
        <\\/h5>\\n<h6>un titre 6<\\/h6>\\n<blockquote>
        <em>Une citation<\\/em><\\/blockquote>\\n<ul>
        \\n<li>Liste a puce 1<\\/li>\\n<li>Liste a puce 2
        <\\/li>\\n<li>Liste a puce 3<\\/li>\\n<\\/ul>\\n<p>
        <strong>Texte en gras<\\/strong><\\/p>\\n<p><em>Texte en Italique<\\/em><\\/p>\\n<p>Texte Normal<\\/p>\\n", 
        "sub_title":"Calendar" 
        }, 
        { 
        "title":"We", 
        "body":"<h2>Un titre 2<\\/h2>
        \\n<h3>Un titre 3<\\/h3>\\n<h4>
        Un titre 4<\\/h4>\\n<h5>un titre 5<\\/h5>\\n<h6>un titre 6
        <\\/h6>\\n<blockquote><em>Une citation<\\/em><\\/blockquote>
        \\n<ul>\\n<li>Liste a puce 1<\\/li>\\n<li>Liste a puce 2<\\/li>
        \\n<li>Liste a puce 3<\\/li>\\n<\\/ul>\\n<p><strong>Texte en gras
        <\\/strong><\\/p>\\n<p><em>Texte en Italique<\\/em><\\/p>\\n<p>
        Texte Normal<\\/p>\\n", 
        "sub_title":"Support" 
        }, 
        { 
        "title":"The Editions", 
        "body":"<h2>Un titre 2<\\/h2>
        \\n<h3>Un titre 3<\\/h3>\\n
        <h4>Un titre 4<\\/h4>\\n<h5>un titre 5<\\/h5>\\n<h6>
        un titre 6<\\/h6>\\n<blockquote><em>Une citation<\\/em><\\/blockquote>\\
        n<ul>\\n<li>Liste a puce 1<\\/li>\\n<li>Liste a puce 2<\\/li>\\n<li>
        Liste a puce 3<\\/li>\\n<\\/ul>\\n<p><strong>Texte en gras<\\/strong><\\/p>\\n<p>
        <em>Texte en Italique<\\/em><\\/p>\\n<p>Texte Normal<\\/p>\\n", 
        "sub_title":"Of The Roche D\'or" 
        }, 
        { 
        "title":"Information", 
        "body":"<h2>Un titre 2<\\/h2>\\n<h3>
        Un titre 3<\\/h3>\\n<h4>Un titre 4
        <\\/h4>\\n<h5>un titre 5<\\/h5>\\n
        <h6>un titre 6<\\/h6>\\n<blockquote>
        <em>Une citation<\\/em><\\/blockquote>
        \\n<ul>\\n<li>Liste a puce 1<\\/li>\\n
        <li>Liste a puce 2<\\/li>\\n<li>Liste a puce 3
        <\\/li>\\n<\\/ul>\\n<p><strong>Texte en gras
        <\\/strong><\\/p>\\n<p><em>Texte en Italique<\\/em><\\/p>\\n<p>Texte Normal<\\/p>\\n", 
        "sub_title":"And Contact" 
        } 
        ] 
        }, 
        "background":null, 
        "locale":"en", 
        "parent":{ 
        "id":2, 
        "title":"Page d\'acceuil", 
        "sub_title":"Ma Page d\'acceuil", 
        "description":"Une Meta-Description", 
        "content":{ 
        "intro":"Une page pour v\\u00e9rifier le module CMS", 
        "sections":[ 
        { 
        "title":"Un titre", 
        "body":"<h2>Un titre 2<\\/h2>\\n<h3>Un titre 3<\\/h3>\\n<h4>Un titre 4
        <\\/h4>\\n<h5>un titre 5<\\/h5>\\n<h6>un titre 6<\\/h6>\\n<blockquote>
        Une citation<\\/blockquote>\\n<ul>\\n<li>Liste a puce 1<\\/li>\\n<li>
        Liste a puce 2<\\/li>\\n<li>Liste a puce 3<\\/li>\\n<\\/ul>\\n<p><strong>
        Texte en gras<\\/strong><\\/p>\\n<p><em>Texte en Italique<\\/em><\\/p>\\
        n<p>Texte Normal<\\/p>\\n", 
        "slides":[ 
        { 
        "layout":"1-1-2", 
        "images":[ 
        { 
        "type":"", 
        "url":"\\/uploads\\/images (3)1528367140.jpeg", 
        "alt":"", 
        "video":"https:\\/\\/www.youtube.com\\/watch?v=UnA0QPwiKZo" 
        }, 
        { 
        "type":"", 
        "url":"\\/uploads\\/images (2)1528367143.jpeg", 
        "alt":"", 
        "video":"" 
        }, 
        { 
        "type":"", 
        "url":"\\/uploads\\/images (1)1528367146.jpeg", 
        "alt":"", 
        "video":"" 
        }, 
        { 
        "type":"", 
        "url":"", 
        "alt":"", 
        "video":"" 
        } 
        ] 
        } 
        ] 
        }, 
        { 
        "title":"Un 2eme titre2", 
        "body":"<h2>Un titre 2<\\/h2>\\n<h3>Un titre 3<\\/h3>\\n<h4
        >Un titre 4<\\/h4>\\n<h5>un titre 5<\\/h5>\\n<h6>un titre 6<\\/h6>
        \\n<blockquote><em>Une citation<\\/em><\\/blockquote>\\n<ul>\\n<li>
        Liste a puce 1<\\/li>\\n<li>Liste a puce 2<\\/li>\\n<li>Liste a puce 3
        <\\/li>\\n<\\/ul>\\n<p><strong>Texte en gras<\\/strong><\\/p>\\n<p><em>
        Texte en Italique<\\/em><\\/p>\\n<p>Texte Normal<\\/p>\\n", 
        "slides":[ 
        { 
        "layout":"1-1-2", 
        "images":[ 
        { 
        "type":"", 
        "url":"\\/uploads\\/images1528367279.jpeg", 
        "alt":"", 
        "video":"" 
        }, 
        { 
        "type":"", 
        "url":"\\/uploads\\/9-michel-free.jpg1528367282.jpeg", 
        "alt":"", 
        "video":"" 
        }, 
        { 
        "type":"", 
        "url":"\\/uploads\\/220px-Paris_(75),_Sainte-Chapelle,_chapelle_haute_3.jpg1528367286.jpeg", 
        "alt":"", 
        "video":"" 
        }, 
        { 
        "type":"", 
        "url":"", 
        "alt":"", 
        "video":"" 
        } 
        ] 
        } 
        ] 
        } 
        ] 
        }, 
        "background":null, 
        "locale":"fr", 
        "parent":null, 
        "children":[ 
        { 
        "id":4, 
        "title":"P\\u00e1gina de inicio", 
        "sub_title":"Mi p\\u00e1gina de inicio", 
        "description":"Una meta descripci\\u00f3n", 
        "content":{ 
        "intro":"Una p\\u00e1gina para verificar el m\\u00f3dulo CMS", 
        "sections":[ 
        { 
        "title":"La comunidad", 
        "body":"<h2>Un titre 2<\\/h2>\\n<h3>Un titre 3<\\/h3>
        \\n<h4>Un titre 4<\\/h4>\\n<h5>un titre 5<\\/h5>\\n<h6>
        un titre 6<\\/h6>\\n<blockquote>Une citation<\\/blockquote>
        \\n<ul>\\n<li>Liste a puce 1<\\/li>\\n<li>Liste a puce 2
        <\\/li>\\n<li>Liste a puce 3<\\/li>\\n<\\/ul>\\n<p><strong>
        Texte en gras<\\/strong><\\/p>\\n<p><em>Texte en Italique<\\/em>
        <\\/p>\\n<p>Texte Normal<\\/p>\\n", 
        "sub_title":"Y sus casas" 
        }, 
        { 
        "title":"La", 
        "body":"<h2>Un titre 2<\\/h2>\\n<h3>Un titre 3
        <\\/h3>\\n<h4>Un titre 4<\\/h4>\\n<h5>un titre 5
        <\\/h5>\\n<h6>un titre 6<\\/h6>\\n<blockquote>
        <em>Une citation<\\/em><\\/blockquote>\\n<ul>\\n
        <li>Liste a puce 1<\\/li>\\n<li>Liste a puce 2<\\/li>
        \\n<li>Liste a puce 3<\\/li>\\n<\\/ul>\\n<p><strong>Texte en gras
        <\\/strong><\\/p>\\n<p><em>Texte en Italique<\\/em><\\/p>\\n<p>Texte Normal<\\/p>\\n", 
        "sub_title":"Pensiones" 
        }, 
        { 
        "title":"La", 
        "body":"<h2>Un titre 2<\\/h2>\\n<h3>Un titre 3<\\/h3>\\n<h4>
        Un titre 4<\\/h4>\\n<h5>un titre 5<\\/h5>\\n<h6>un titre 6
        <\\/h6>\\n<blockquote><em>Une citation<\\/em><\\/blockquote>
        \\n<ul>\\n<li>Liste a puce 1<\\/li>\\n<li>Liste a puce 2<\\/li>
        \\n<li>Liste a puce 3<\\/li>\\n<\\/ul>\\n<p><strong>Texte en gras
        <\\/strong><\\/p>\\n<p><em>Texte en Italique<\\/em><\\/p>\\n<p>
        Texte Normal<\\/p>\\n", 
        "sub_title":"Calendario" 
        }, 
        { 
        "title":"APOYANOS", 
        "body":"<h2>Un titre 2<\\/h2>\\n<h3>Un titre 3<\\/h3>
        \\n<h4>Un titre 4<\\/h4>\\n<h5>un titre 5<\\/h5>\\n<h6>
        un titre 6<\\/h6>\\n<blockquote><em>Une citation<\\/em>
        <\\/blockquote>\\n<ul>\\n<li>Liste a puce 1<\\/li>\\n
        <li>Liste a puce 2<\\/li>\\n<li>Liste a puce 3<\\/li>
        \\n<\\/ul>\\n<p><strong>Texte en gras<\\/strong><\\/p>
        \\n<p><em>Texte en Italique<\\/em><\\/p>\\n<p>Texte Normal<\\/p>\\n", 
        "sub_title":"" 
        }, 
        { 
        "title":"LAS EDICIONES", 
        "body":"<h2>Un titre 2<\\/h2>\\n<h3>Un titre 3<\\/h3>
        \\n<h4>Un titre 4<\\/h4>\\n<h5>un titre 5<\\/h5>\\n
        <h6>un titre 6<\\/h6>\\n<blockquote><em>Une citation
        <\\/em><\\/blockquote>\\n<ul>\\n<li>Liste a puce 1
        <\\/li>\\n<li>Liste a puce 2<\\/li>\\n<li>Liste a puce 3
        <\\/li>\\n<\\/ul>\\n<p><strong>Texte en gras<\\/strong><\\/p>
        \\n<p><em>Texte en Italique<\\/em><\\/p>\\n<p>Texte Normal<\\/p>\\n", 
        "sub_title":"De la Roche D\'or" 
        }, 
        { 
        "title":"Informacion", 
        "body":"<h2>Un titre 2<\\/h2>\\n<h3>Un titre 3<\\/h3>\\n<h4>
        Un titre 4<\\/h4>\\n<h5>un titre 5<\\/h5>\\n<h6>un titre 6
        <\\/h6>\\n<blockquote><em>Une citation<\\/em><\\/blockquote>
        \\n<ul>\\n<li>Liste a puce 1<\\/li>\\n<li>Liste a puce 2<\\/li>
        \\n<li>Liste a puce 3<\\/li>\\n<\\/ul>\\n<p><strong>Texte en gras
        <\\/strong><\\/p>\\n<p><em>Texte en Italique<\\/em><\\/p>\\n<p>
        Texte Normal<\\/p>\\n", 
        "sub_title":"y contactos" 
        } 
        ] 
        }, 
        "background":null, 
        "locale":"es", 
        "parent":null, 
        "children":[ 

        ], 
        "routes":[ 
        { 
        "path":"\\/", 
        "host":"", 
        "schemes":[ 

        ], 
        "methods":[ 

        ], 
        "defaults":{ 
        "_content_id":"AppBundle\\\\Entity\\\\Page:4" 
        }, 
        "requirements":[ 

        ], 
        "options":[ 

        ], 
        "condition":"", 
        "compiled":null, 
        "id":9, 
        "content":null, 
        "static_prefix":"\\/es", 
        "variable_pattern":null, 
        "need_recompile":false, 
        "name":"es", 
        "position":0 
        } 
        ], 
        "updated":"2018-06-08T08:16:16+08:00", 
        "url":null, 
        "parent_id":null 
        }, 
        { 
        "id":5, 
        "title":"Startseite", 
        "sub_title":"Meine Startseite", 
        "description":"Eine Meta-Beschreibung", 
        "content":{ 
        "intro":"Eine Seite f\\u00fcr das Modul CMS", 
        "sections":[ 
        { 
        "title":"Die Gemeinschaft", 
        "body":"<h2>Un titre 2<\\/h2>\\n<h3>Un titre 3
        <\\/h3>\\n<h4>Un titre 4<\\/h4>\\n<h5>un titre 5
        <\\/h5>\\n<h6>un titre 6<\\/h6>\\n<blockquote>
        Une citation<\\/blockquote>\\n<ul>\\n<li>Liste a puce 1
        <\\/li>\\n<li>Liste a puce 2<\\/li>\\n<li>Liste a puce 3
        <\\/li>\\n<\\/ul>\\n<p><strong>Texte en gras<\\/strong>
        <\\/p>\\n<p><em>Texte en Italique<\\/em><\\/p>\\n<p>Texte Normal<\\/p>\\n", 
        "sub_title":"UND SEINE H\\u00c4USER" 
        }, 
        { 
        "title":"Das", 
        "body":"<h2>Un titre 2<\\/h2>\\n<h3>Un titre 3<\\/h3>
        \\n<h4>Un titre 4<\\/h4>\\n<h5>un titre 5<\\/h5>\\n
        <h6>un titre 6<\\/h6>\\n<blockquote><em>Une citation
        <\\/em><\\/blockquote>\\n<ul>\\n<li>Liste a puce 1<\\/li>
        \\n<li>Liste a puce 2<\\/li>\\n<li>Liste a puce 3<\\/li>
        \\n<\\/ul>\\n<p><strong>Texte en gras<\\/strong><\\/p>\\
        n<p><em>Texte en Italique<\\/em><\\/p>\\n<p>Texte Normal<\\/p>\\n", 
        "sub_title":"Pensionen" 
        }, 
        { 
        "title":"Das", 
        "body":"<h2>Un titre 2<\\/h2>\\n<h3>Un titre 3<\\/h3>\\n
        <h4>Un titre 4<\\/h4>\\n<h5>un titre 5<\\/h5>\\n<h6>un titre 6
        <\\/h6>\\n<blockquote><em>Une citation<\\/em><\\/blockquote>\\n
        <ul>\\n<li>Liste a puce 1<\\/li>\\n<li>Liste a puce 2<\\/li>\\n
        <li>Liste a puce 3<\\/li>\\n<\\/ul>\\n<p><strong>Texte en gras
        <\\/strong><\\/p>\\n<p><em>Texte en Italique<\\/em><\\/p>\\n<p>Texte Normal<\\/p>\\n", 
        "sub_title":"Kalender" 
        }, 
        { 
        "title":"Unterst\\u00fctzen", 
        "body":"<h2>Un titre 2<\\/h2>\\n<h3>Un titre 3<\\/h3>\\n<h4>
        Un titre 4<\\/h4>\\n<h5>un titre 5<\\/h5>\\n<h6>un titre 6
        <\\/h6>\\n<blockquote><em>Une citation<\\/em><\\/blockquote>
        \\n<ul>\\n<li>Liste a puce 1<\\/li>\\n<li>Liste a puce 2
        <\\/li>\\n<li>Liste a puce 3<\\/li>\\n<\\/ul>\\n<p>
        <strong>Texte en gras<\\/strong><\\/p>\\n<p><em>Texte en Italique<\\/em><\\/p>\\n<p>Texte Normal<\\/p>\\n", 
        "sub_title":"Uns" 
        }, 
        { 
        "title":"Die Ausgaben", 
        "body":"<h2>Un titre 2<\\/h2>\\n<h3>Un titre 3<\\/h3>
        \\n<h4>Un titre 4<\\/h4>\\n<h5>un titre 5<\\/h5>\\n
        <h6>un titre 6<\\/h6>\\n<blockquote><em>Une citation
        <\\/em><\\/blockquote>\\n<ul>\\n<li>Liste a puce 1
        <\\/li>\\n<li>Liste a puce 2<\\/li>\\n<li>Liste a puce 3
        <\\/li>\\n<\\/ul>\\n<p><strong>Texte en gras<\\/strong>
        <\\/p>\\n<p><em>Texte en Italique<\\/em><\\/p>\\n<p>Texte Normal<\\/p>\\n", 
        "sub_title":"Der Roche D\'or" 
        }, 
        { 
        "title":"Information", 
        "body":"<h2>Un titre 2<\\/h2>\\n<h3>Un titre 3
        <\\/h3>\\n<h4>Un titre 4<\\/h4>\\n<h5>un titre 5
        <\\/h5>\\n<h6>un titre 6<\\/h6>\\n<blockquote>
        <em>Une citation<\\/em><\\/blockquote>\\n<ul>\\n
        <li>Liste a puce 1<\\/li>\\n<li>Liste a puce 2
        <\\/li>\\n<li>Liste a puce 3<\\/li>\\n<\\/ul>\\
        n<p><strong>Texte en gras<\\/strong><\\/p>
        \\n<p><em>Texte en Italique<\\/em><\\/p>\\
        n<p>Texte Normal<\\/p>\\n", 
        "sub_title":"UND KONTAKTE" 
        } 
        ] 
        }, 
        "background":null, 
        "locale":"de", 
        "parent":null, 
        "children":[ 

        ], 
        "routes":[ 
        { 
        "path":"\\/", 
        "host":"", 
        "schemes":[ 

        ], 
        "methods":[ 

        ], 
        "defaults":{ 
        "_content_id":"AppBundle\\\\Entity\\\\Page:5" 
        }, 
        "requirements":[ 

        ], 
        "options":[ 

        ], 
        "condition":"", 
        "compiled":null, 
        "id":10, 
        "content":null, 
        "static_prefix":"\\/de", 
        "variable_pattern":null, 
        "need_recompile":false, 
        "name":"de", 
        "position":0 
        } 
        ], 
        "updated":"2018-06-08T08:20:06+08:00", 
        "url":null, 
        "parent_id":null 
        }, 
        { 
        "id":6, 
        "title":"Pagina iniziale", 
        "sub_title":"a mia home page", 
        "description":"Una meta-descrizione", 
        "content":{ 
        "intro":"Una pagina per controllare il modulo CMS", 
        "sections":[ 
        { 
        "title":"La Communit\\u00e0", 
        "body":"<h2>Un titre 2<\\/h2>\\n<h3>
        Un titre 3<\\/h3>\\n<h4>Un titre 4
        <\\/h4>\\n<h5>un titre 5<\\/h5>\\n
        <h6>un titre 6<\\/h6>\\n<blockquote>
        Une citation<\\/blockquote>\\n<ul>
        \\n<li>Liste a puce 1<\\/li>\\n<li>
        Liste a puce 2<\\/li>\\n<li>Liste a puce 3
        <\\/li>\\n<\\/ul>\\n<p><strong>Texte en gras
        <\\/strong><\\/p>\\n<p><em>Texte en Italique
        <\\/em><\\/p>\\n<p>Texte Normal<\\/p>\\n", 
        "sub_title":"E le sue case" 
        }, 
        { 
        "title":"Le", 
        "body":"<h2>Un titre 2<\\/h2>\\n<h3>Un titre 3
        <\\/h3>\\n<h4>Un titre 4<\\/h4>\\n<h5>un titre 5
        <\\/h5>\\n<h6>un titre 6<\\/h6>\\n<blockquote>
        <em>Une citation<\\/em><\\/blockquote>\\n<ul>\\n
        <li>Liste a puce 1<\\/li>\\n<li>Liste a puce 2<\\/li>
        \\n<li>Liste a puce 3<\\/li>\\n<\\/ul>\\n<p><strong>
        Texte en gras<\\/strong><\\/p>\\n<p><em>Texte en Italique
        <\\/em><\\/p>\\n<p>Texte Normal<\\/p>\\n", 
        "sub_title":"Ritiri" 
        }, 
        { 
        "title":"Il", 
        "body":"<h2>Un titre 2<\\/h2>\\n<h3>Un titre 3<\\/h3>
        \\n<h4>Un titre 4<\\/h4>\\n<h5>un titre 5<\\/h5>\\n<h6
        un titre 6<\\/h6>\\n<blockquote><em>Une citation<\\/em>
        <\\/blockquote>\\n<ul>\\n<li>Liste a puce 1<\\/li>\\n
        <li>Liste a puce 2<\\/li>\\n<li>Liste a puce 3<\\/li>
        \\n<\\/ul>\\n<p><strong>Texte en gras<\\/strong><\\/p>
        \\n<p><em>Texte en Italique<\\/em><\\/p>\\n<p>Texte Normal<\\/p>\\n", 
        "sub_title":"Calendario" 
        }, 
        { 
        "title":"No", 
        "body":"<h2>Un titre 2<\\/h2>\\n<h3>Un titre 3
        <\\/h3>\\n<h4>Un titre 4<\\/h4>\\n<h5>un titre 5
        <\\/h5>\\n<h6>un titre 6<\\/h6>\\n<blockquote>
        <em>Une citation<\\/em><\\/blockquote>\\n<ul>\\n
        <li>Liste a puce 1<\\/li>\\n<li>Liste a puce 2<\\/li>
        \\n<li>Liste a puce 3<\\/li>\\n<\\/ul>\\n<p><strong>
        Texte en gras<\\/strong><\\/p>\\n<p><em>Texte en Italique<\\/em>
        <\\/p>\\n<p>Texte Normal<\\/p>\\n", 
        "sub_title":"Sostenerci" 
        }, 
        { 
        "title":"Edizione", 
        "body":"<h2>Un titre 2<\\/h2>\\n<h3>Un titre 3
        <\\/h3>\\n<h4>Un titre 4<\\/h4>\\n<h5>un titre 5
        <\\/h5>\\n<h6>un titre 6<\\/h6>\\n<blockquote>
        <em>Une citation<\\/em><\\/blockquote>\\n<ul>\\
        n<li>Liste a puce 1<\\/li>\\n<li>Liste a puce 2
        <\\/li>\\n<li>Liste a puce 3<\\/li>\\n<\\/ul>\\n
        <p><strong>Texte en gras<\\/strong><\\/p>\\n<p>
        <em>Texte en Italique<\\/em><\\/p>\\n<p>Texte Normal<\\/p>\\n", 
        "sub_title":"Del Roche d\'Or" 
        }, 
        { 
        "title":"Informazioni", 
        "body":"<h2>Un titre 2<\\/h2>\\n<h3>Un titre 3
        <\\/h3>\\n<h4>Un titre 4<\\/h4>\\n<h5>un titre 5
        <\\/h5>\\n<h6>un titre 6<\\/h6>\\n<blockquote>
        <em>Une citation<\\/em><\\/blockquote>\\n
        <ul>\\n<li>Liste a puce 1<\\/li>\\n<li>Liste a puce 2
        <\\/li>\\n<li>Liste a puce 3<\\/li>\\n<\\/ul>\\n<p>
        <strong>Texte en gras<\\/strong><\\/p>\\n<p>
        <em>Texte en Italique<\\/em><\\/p>\\n<p>Texte Normal<\\/p>\\n", 
        "sub_title":"E contatti" 
        } 
        ] 
        }, 
        "background":null, 
        "locale":"it", 
        "parent":null, 
        "children":[ 

        ], 
        "routes":[ 
        { 
        "path":"\\/", 
        "host":"", 
        "schemes":[ 

        ], 
        "methods":[ 

        ], 
        "defaults":{ 
        "_content_id":"AppBundle\\\\Entity\\\\Page:6" 
        }, 
        "requirements":[ 

        ], 
        "options":[ 

        ], 
        "condition":"", 
        "compiled":null, 
        "id":11, 
        "content":null, 
        "static_prefix":"\\/it", 
        "variable_pattern":null, 
        "need_recompile":false, 
        "name":"it", 
        "position":0 
        } 
        ], 
        "updated":"2018-06-08T08:25:07+08:00", 
        "url":null, 
        "parent_id":null 
        }, 
        null 
        ], 
        "routes":[ 
        { 
        "path":"\\/", 
        "host":"", 
        "schemes":[ 

        ], 
        "methods":[ 

        ], 
        "defaults":{ 
        "_content_id":"AppBundle\\\\Entity\\\\Page:2" 
        }, 
        "requirements":[ 

        ], 
        "options":[ 

        ], 
        "condition":"", 
        "compiled":null, 
        "id":8, 
        "content":null, 
        "static_prefix":"\\/fr", 
        "variable_pattern":null, 
        "need_recompile":false, 
        "name":"fr", 
        "position":0 
        } 
        ], 
        "updated":"2018-06-08T18:18:01+08:00", 
        "url":null, 
        "parent_id":null 
        }, 
        "children":[ 

        ], 
        "routes":[ 
        { 
        "path":"\\/", 
        "host":"", 
        "schemes":[ 

        ], 
        "methods":[ 

        ], 
        "defaults":{ 
        "_content_id":"AppBundle\\\\Entity\\\\Page:7" 
        }, 
        "requirements":[ 

        ], 
        "options":[ 

        ], 
        "condition":"", 
        "compiled":null, 
        "id":12, 
        "content":null, 
        "static_prefix":"\\/en", 
        "variable_pattern":null, 
        "need_recompile":false, 
        "name":"en", 
        "position":0 
        } 
        ], 
        "updated":"2018-06-08T08:06:19+08:00", 
        "url":"en", 
        "parent_id":null 
        }', true);
        $this->assertTrue(
            $arrayResponse === $homeTest
        );
    }

    public function testReturnedJsonGetOneVersionHome()
    {
        $client = self::createClient();
        $crawler =$client->request('GET', '/api/home/fr/1');
        $response = $client->getResponse()->getContent();
        $arrayResponse = json_decode($response, true);
        
        $homeTest = json_decode('{ 
        "id": 2, 
        "title": "Page de Test", 
        "sub_title": "Une page pour tester", 
        "description": "meta", 
        "content": { 
        "intro": "Une page pour vérifier le module CMS", 
        "sections": [ 
        { 
        "title": "", 
        "body": "", 
        "slides": [ 
        { 
        "layout": "1-1-2", 
        "images": [ 
        { 
        "type": "", 
        "url": "", 
        "alt": "", 
        "video": "" 
        }, 
        { 
        "type": "", 
        "url": "", 
        "alt": "", 
        "video": "" 
        }, 
        { 
        "type": "", 
        "url": "", 
        "alt": "", 
        "video": "" 
        }, 
        { 
        "type": "", 
        "url": "", 
        "alt": "", 
        "video": "" 
        } 
        ] 
        } 
        ] 
        } 
        ] 
        }, 
        "background": null, 
        "locale": "fr", 
        "parent": null, 
        "children": [ 
        { 
        "id": 4, 
        "title": "Página de inicio", 
        "sub_title": "Mi página de inicio", 
        "description": "Una meta descripción", 
        "content": { 
        "intro": "Una página para verificar el módulo CMS", 
        "sections": [ 
        { 
        "title": "La comunidad", 
        "body": "<h2>Un titre 2</h2>\\n<h3>Un titre 3
        </h3>\\n<h4>Un titre 4</h4>\\n<h5>un titre 5
        </h5>\\n<h6>un titre 6</h6>\\n<blockquote>
        Une citation</blockquote>\\n<ul>\\n<li>
        Liste a puce 1</li>\\n<li>Liste a puce 2
        </li>\\n<li>Liste a puce 3</li>\\n</ul>\\
        n<p><strong>Texte en gras</strong></p>\\n
        <p><em>Texte en Italique</em></p>\\n<p>Texte Normal</p>\\n", 
        "sub_title": "Y sus casas" 
        }, 
        { 
        "title": "La", 
        "body": "<h2>Un titre 2</h2>\\n<h3>Un titre 3
        </h3>\\n<h4>Un titre 4</h4>\\n<h5>un titre 5
        </h5>\\n<h6>un titre 6</h6>\\n<blockquote>
        <em>Une citation</em></blockquote>\\n<ul>\\n
        <li>Liste a puce 1</li>\\n<li>Liste a puce 2
        </li>\\n<li>Liste a puce 3</li>\\n</ul>\\n<p>
        <strong>Texte en gras</strong></p>\\n<p><em>Texte en Italique</em></p>\\n<p>Texte Normal</p>\\n", 
        "sub_title": "Pensiones" 
        }, 
        { 
        "title": "La", 
        "body": "<h2>Un titre 2</h2>\\n<h3>Un titre 3
        </h3>\\n<h4>Un titre 4</h4>\\n<h5>un titre 5
        </h5>\\n<h6>un titre 6</h6>\\n<blockquote>
        <em>Une citation</em></blockquote>\\n<ul>\\n
        <li>Liste a puce 1</li>\\n<li>Liste a puce 2
        </li>\\n<li>Liste a puce 3</li>\\n</ul>\\n<p>
        <strong>Texte en gras</strong></p>\\n<p><em>
        Texte en Italique</em></p>\\n<p>Texte Normal</p>\\n", 
        "sub_title": "Calendario" 
        }, 
        { 
        "title": "APOYANOS", 
        "body": "<h2>Un titre 2</h2>\\n<h3>Un titre 3
        </h3>\\n<h4>Un titre 4</h4>\\n<h5>un titre 5
        </h5>\\n<h6>un titre 6</h6>\\n<blockquote><em>
        Une citation</em></blockquote>\\n<ul>\\n
        <li>Liste a puce 1</li>\\n<li>Liste a puce 2
        </li>\\n<li>Liste a puce 3</li>\\n</ul>\\n
        <p><strong>Texte en gras</strong></p>\\n<p>
        <em>Texte en Italique</em></p>\\n<p>Texte Normal</p>\\n", 
        "sub_title": "" 
        }, 
        { 
        "title": "LAS EDICIONES", 
        "body": "<h2>Un titre 2</h2>\\n<h3>Un titre 3
        </h3>\\n<h4>Un titre 4</h4>\\n<h5>un titre 5
        </h5>\\n<h6>un titre 6</h6>\\n<blockquote>
        <em>Une citation</em></blockquote>\\n<ul>\\n
        <li>Liste a puce 1</li>\\n<li>Liste a puce 2
        </li>\\n<li>Liste a puce 3</li>\\n</ul>\\n
        <p><strong>Texte en gras</strong></p>\\n<p>
        <em>Texte en Italique</em></p>\\n<p>Texte Normal</p>\\n", 
        "sub_title": "De la Roche D\'or" 
        }, 
        { 
        "title": "Informacion", 
        "body": "<h2>Un titre 2</h2>\\n<h3>Un titre 3
        </h3>\\n<h4>Un titre 4</h4>\\n<h5>un titre 5
        </h5>\\n<h6>un titre 6</h6>\\n<blockquote>
        <em>Une citation</em></blockquote>\\n<ul>\\
        n<li>Liste a puce 1</li>\\n<li>Liste a puce 2
        </li>\\n<li>Liste a puce 3</li>\\n</ul>\\n<p>
        <strong>Texte en gras</strong></p>\\n<p><em>
        Texte en Italique</em></p>\\n<p>Texte Normal</p>\\n", 
        "sub_title": "y contactos" 
        } 
        ] 
        }, 
        "background": null, 
        "locale": "es", 
        "parent": null, 
        "children": [], 
        "routes": [ 
        { 
        "path": "/", 
        "host": "", 
        "schemes": [], 
        "methods": [], 
        "defaults": { 
        "_content_id": "AppBundle\\\\Entity\\\\Page:4" 
        }, 
        "requirements": [], 
        "options": [], 
        "condition": "", 
        "compiled": null, 
        "id": 9, 
        "content": null, 
        "static_prefix": "/es", 
        "variable_pattern": null, 
        "need_recompile": false, 
        "name": "es", 
        "position": 0 
        } 
        ], 
        "updated": "2018-06-08T08:16:16+08:00", 
        "url": null, 
        "parent_id": null 
        }, 
        { 
        "id": 5, 
        "title": "Startseite", 
        "sub_title": "Meine Startseite", 
        "description": "Eine Meta-Beschreibung", 
        "content": { 
        "intro": "Eine Seite für das Modul CMS", 
        "sections": [ 
        { 
        "title": "Die Gemeinschaft", 
        "body": "<h2>Un titre 2</h2>\\n<h3>Un titre 3
        </h3>\\n<h4>Un titre 4</h4>\\n<h5>un titre 5
        </h5>\\n<h6>un titre 6</h6>\\n<blockquote>
        Une citation</blockquote>\\n<ul>\\n<li>
        Liste a puce 1</li>\\n<li>Liste a puce 2
        </li>\\n<li>Liste a puce 3</li>\\n</ul>\\
        n<p><strong>Texte en gras</strong></p>\\
        n<p><em>Texte en Italique</em></p>\\n<p>Texte Normal</p>\\n", 
        "sub_title": "UND SEINE HÄUSER" 
        }, 
        { 
        "title": "Das", 
        "body": "<h2>Un titre 2</h2>\\n<h3>Un titre 3
        </h3>\\n<h4>Un titre 4</h4>\\n<h5>un titre 5
        </h5>\\n<h6>un titre 6</h6>\\n<blockquote>
        <em>Une citation</em></blockquote>\\n<ul>
        \\n<li>Liste a puce 1</li>\\n<li>
        Liste a puce 2</li>\\n<li>Liste a puce 3
        </li>\\n</ul>\\n<p><strong>Texte en gras
        </strong></p>\\n<p><em>Texte en Italique
        </em></p>\\n<p>Texte Normal</p>\\n", 
        "sub_title": "Pensionen" 
        }, 
        { 
        "title": "Das", 
        "body": "<h2>Un titre 2</h2>\\n
        <h3>Un titre 3</h3>\\n<h4>
        Un titre 4</h4>\\n<h5>un titre 5
        </h5>\\n<h6>un titre 6</h6>\\n
        <blockquote><em>Une citation
        </em></blockquote>\\n<ul>\\n
        <li>Liste a puce 1</li>\\n<li>
        Liste a puce 2</li>\\n
        <li>Liste a puce 3</li>
        \\n</ul>\\n<p><strong>
        Texte en gras</strong></p>\\n<p><em>Texte en Italique</em></p>\\n<p>Texte Normal</p>\\n", 
        "sub_title": "Kalender" 
        }, 
        { 
        "title": "Unterstützen", 
        "body": "<h2>Un titre 2</h2>\\n
        <h3>Un titre 3</h3>\\n<h4>
        Un titre 4</h4>\\n<h5>un titre 5
        </h5>\\n<h6>un titre 6</h6>\\n
        <blockquote><em>Une citation
        </em></blockquote>\\n<ul>\\n
        <li>Liste a puce 1</li>\\n<li>
        Liste a puce 2</li>\\n<li>
        Liste a puce 3</li>\\n</ul>\\
        n<p><strong>Texte en gras</strong>
        </p>\\n<p><em>Texte en Italique
        </em></p>\\n<p>Texte Normal</p>\\n", 
        "sub_title": "Uns" 
        }, 
        { 
        "title": "Die Ausgaben", 
        "body": "<h2>Un titre 2</h2>\\n<h3>Un titre 3
        </h3>\\n<h4>Un titre 4</h4>\\n<h5>un titre 5
        </h5>\\n<h6>un titre 6</h6>\\n<blockquote>
        <em>Une citation</em></blockquote>\\n<ul>\\
        n<li>Liste a puce 1</li>\\n<li>Liste a puce 2
        </li>\\n<li>Liste a puce 3</li>\\n</ul>\\n<p>
        <strong>Texte en gras</strong></p>\\n<p><em>
        Texte en Italique</em></p>\\n<p>Texte Normal</p>\\n", 
        "sub_title": "Der Roche D\'or" 
        }, 
        { 
        "title": "Information", 
        "body": "<h2>Un titre 2</h2>\\n<h3>Un titre 3</h3>
        \\n<h4>Un titre 4</h4>\\n<h5>un titre 5</h5>\\n
        <h6>un titre 6</h6>\\n<blockquote><em>Une citation
        </em></blockquote>\\n<ul>\\n<li>Liste a puce 1</li>
        \\n<li>Liste a puce 2</li>\\n<li>Liste a puce 3</li>
        \\n</ul>\\n<p><strong>Texte en gras</strong></p>\\
        n<p><em>Texte en Italique</em></p>\\n<p>Texte Normal</p>\\n", 
        "sub_title": "UND KONTAKTE" 
        } 
        ] 
        }, 
        "background": null, 
        "locale": "de", 
        "parent": null, 
        "children": [], 
        "routes": [ 
        { 
        "path": "/", 
        "host": "", 
        "schemes": [], 
        "methods": [], 
        "defaults": { 
        "_content_id": "AppBundle\\\\Entity\\\\Page:5" 
        }, 
        "requirements": [], 
        "options": [], 
        "condition": "", 
        "compiled": null, 
        "id": 10, 
        "content": null, 
        "static_prefix": "/de", 
        "variable_pattern": null, 
        "need_recompile": false, 
        "name": "de", 
        "position": 0 
        } 
        ], 
        "updated": "2018-06-08T08:20:06+08:00", 
        "url": null, 
        "parent_id": null 
        }, 
        { 
        "id": 6, 
        "title": "Pagina iniziale", 
        "sub_title": "a mia home page", 
        "description": "Una meta-descrizione", 
        "content": { 
        "intro": "Una pagina per controllare il modulo CMS", 
        "sections": [ 
        { 
        "title": "La Communità", 
        "body": "<h2>Un titre 2</h2>\\n<h3>Un titre 3</h3>
        \\n<h4>Un titre 4</h4>\\n<h5>un titre 5</h5>\\n
        <h6>un titre 6</h6>\\n<blockquote>Une citation
        </blockquote>\\n<ul>\\n<li>Liste a puce 1</li>
        \\n<li>Liste a puce 2</li>\\n<li>Liste a puce 3
        </li>\\n</ul>\\n<p><strong>Texte en gras</strong>
        </p>\\n<p><em>Texte en Italique</em></p>\\n<p>Texte Normal</p>\\n", 
        "sub_title": "E le sue case" 
        }, 
        { 
        "title": "Le", 
        "body": "<h2>Un titre 2</h2>\\n<h3>Un titre 3</h3>
        \\n<h4>Un titre 4</h4>\\n<h5>un titre 5</h5>\\n
        <h6>un titre 6</h6>\\n<blockquote><em>Une citation
        </em></blockquote>\\n<ul>\\n<li>Liste a puce 1
        </li>\\n<li>Liste a puce 2</li>\\n<li>Liste a puce 3
        </li>\\n</ul>\\n<p><strong>Texte en gras</strong>
        </p>\\n<p><em>Texte en Italique</em></p>\\n<p>Texte Normal</p>\\n", 
        "sub_title": "Ritiri" 
        }, 
        { 
        "title": "Il", 
        "body": "<h2>Un titre 2</h2>\\n<h3>Un titre 3
        </h3>\\n<h4>Un titre 4</h4>\\n<h5>un titre 5
        </h5>\\n<h6>un titre 6</h6>\\n<blockquote>
        <em>Une citation</em></blockquote>\\n
        <ul>\\n<li>Liste a puce 1</li>\\n<li>
        Liste a puce 2</li>\\n<li>Liste a puce 3
        </li>\\n</ul>\\n<p><strong>Texte en gras
        </strong></p>\\n<p><em>Texte en Italique
        </em></p>\\n<p>Texte Normal</p>\\n", 
        "sub_title": "Calendario" 
        }, 
        { 
        "title": "No", 
        "body": "<h2>Un titre 2</h2>\\n<h3>Un titre 3
        </h3>\\n<h4>Un titre 4</h4>\\n<h5>un titre 5
        </h5>\\n<h6>un titre 6</h6>\\n<blockquote>
        <em>Une citation</em></blockquote>\\n<ul>\\n
        <li>Liste a puce 1</li>\\n<li>Liste a puce 2
        </li>\\n<li>Liste a puce 3</li>\\n</ul>\\n
        <p><strong>Texte en gras</strong></p>\\n<p>
        <em>Texte en Italique</em></p>\\n<p>Texte Normal</p>\\n", 
        "sub_title": "Sostenerci" 
        }, 
        { 
        "title": "Edizione", 
        "body": "<h2>Un titre 2</h2>\\n<h3>
        Un titre 3</h3>\\n<h4>Un titre 4
        </h4>\\n<h5>un titre 5</h5>\\n
        <h6>un titre 6</h6>\\n<blockquote>
        <em>Une citation</em></blockquote>\\n<ul>
        \\n<li>Liste a puce 1</li>\\n<li>Liste a puce 2
        </li>\\n<li>Liste a puce 3</li>\\n</ul>\\n<p>
        <strong>Texte en gras</strong></p>\\n<p><em>
        Texte en Italique</em></p>\\n<p>Texte Normal</p>\\n", 
        "sub_title": "Del Roche d\'Or" 
        }, 
        { 
        "title": "Informazioni", 
        "body": "<h2>Un titre 2</h2>\\n<h3>Un titre 3</h3>
        \\n<h4>Un titre 4</h4>\\n<h5>un titre 5</h5>\\n
        <h6>un titre 6</h6>\\n<blockquote><em>Une citation
        </em></blockquote>\\n<ul>\\n<li>Liste a puce 1
        </li>\\n<li>Liste a puce 2</li>\\n<li>Liste a puce 3
        </li>\\n</ul>\\n<p><strong>Texte en gras</strong>
        </p>\\n<p><em>Texte en Italique</em></p>\\n<p>Texte Normal</p>\\n", 
        "sub_title": "E contatti" 
        } 
        ] 
        }, 
        "background": null, 
        "locale": "it", 
        "parent": null, 
        "children": [], 
        "routes": [ 
        { 
        "path": "/", 
        "host": "", 
        "schemes": [], 
        "methods": [], 
        "defaults": { 
        "_content_id": "AppBundle\\\\Entity\\\\Page:6" 
        }, 
        "requirements": [], 
        "options": [], 
        "condition": "", 
        "compiled": null, 
        "id": 11, 
        "content": null, 
        "static_prefix": "/it", 
        "variable_pattern": null, 
        "need_recompile": false, 
        "name": "it", 
        "position": 0 
        } 
        ], 
        "updated": "2018-06-08T08:25:07+08:00", 
        "url": null, 
        "parent_id": null 
        }, 
        { 
        "id": 7, 
        "title": "Homepage", 
        "sub_title": "My Homepage", 
        "description": "The homepage description", 
        "content": { 
        "intro": "A page to check the CMS module", 
        "sections": [ 
        { 
        "title": "The Community", 
        "body": "<h2>Un titre 2</h2>\\n<h3>Un titre 3
        </h3>\\n<h4>Un titre 4</h4>\\n<h5>un titre 5
        </h5>\\n<h6>un titre 6</h6>\\n<blockquote>
        Une citation</blockquote>\\n<ul>\\n<li>
        Liste a puce 1</li>\\n<li>Liste a puce 2
        </li>\\n<li>Liste a puce 3</li>\\n</ul>\\n
        <p><strong>Texte en gras</strong></p>\\n<p>
        <em>Texte en Italique</em></p>\\n<p>Texte Normal</p>\\n", 
        "sub_title": "And His House" 
        }, 
        { 
        "title": "The", 
        "body": "<h2>Un titre 2</h2>\\n<h3>Un titre 3
        </h3>\\n<h4>Un titre 4</h4>\\n<h5>un titre 5
        </h5>\\n<h6>un titre 6</h6>\\n<blockquote>
        <em>Une citation</em></blockquote>\\n<ul>\\n
        <li>Liste a puce 1</li>\\n<li>Liste a puce 2
        </li>\\n<li>Liste a puce 3</li>\\n</ul>\\n<p>
        <strong>Texte en gras</strong></p>\\n<p><em>
        Texte en Italique</em></p>\\n<p>Texte Normal</p>\\n", 
        "sub_title": "Pensions" 
        }, 
        { 
        "title": "The", 
        "body": "<h2>Un titre 2</h2>\\n<h3>Un titre 3
        </h3>\\n<h4>Un titre 4</h4>\\n<h5>un titre 5
        </h5>\\n<h6>un titre 6</h6>\\n<blockquote>
        <em>Une citation</em></blockquote>\\n<ul>\\n
        <li>Liste a puce 1</li>\\n<li>Liste a puce 2
        </li>\\n<li>Liste a puce 3</li>\\n</ul>\\n<p>
        <strong>Texte en gras</strong></p>\\n<p><em>
        Texte en Italique</em></p>\\n<p>Texte Normal</p>\\n", 
        "sub_title": "Calendar" 
        }, 
        { 
        "title": "We", 
        "body": "<h2>Un titre 2</h2>\\n<h3>Un titre 3
        </h3>\\n<h4>Un titre 4</h4>\\n<h5>un titre 5
        </h5>\\n<h6>un titre 6</h6>\\n<blockquote>
        <em>Une citation</em></blockquote>\\n<ul>\\n
        <li>Liste a puce 1</li>\\n<li>Liste a puce 2
        </li>\\n<li>Liste a puce 3</li>\\n</ul>\\n<p>
        <strong>Texte en gras</strong></p>\\n<p><em>
        Texte en Italique</em></p>\\n<p>Texte Normal</p>\\n", 
        "sub_title": "Support" 
        }, 
        { 
        "title": "The Editions", 
        "body": "<h2>Un titre 2</h2>\\n<h3>Un titre 3
        </h3>\\n<h4>Un titre 4</h4>\\n<h5>un titre 5
        </h5>\\n<h6>un titre 6</h6>\\n<blockquote>
        <em>Une citation</em></blockquote>\\n<ul>\\
        n<li>Liste a puce 1</li>\\n<li>Liste a puce 2
        </li>\\n<li>Liste a puce 3</li>\\n</ul>\\n<p>
        <strong>Texte en gras</strong></p>\\n<p><em>
        Texte en Italique</em></p>\\n<p>Texte Normal</p>\\n", 
        "sub_title": "Of The Roche D\'or" 
        }, 
        { 
        "title": "Information", 
        "body": "<h2>Un titre 2</h2>\\n<h3>Un titre 3</h3>
        \\n<h4>Un titre 4</h4>\\n<h5>un titre 5</h5>\\n
        <h6>un titre 6</h6>\\n<blockquote><em>Une citation
        </em></blockquote>\\n<ul>\\n<li>Liste a puce 1</li>
        \\n<li>Liste a puce 2</li>\\n<li>Liste a puce 3</li>
        \\n</ul>\\n<p><strong>Texte en gras</strong></p>\\n
        <p><em>Texte en Italique</em></p>\\n<p>Texte Normal</p>\\n", 
        "sub_title": "And Contact" 
        } 
        ] 
        }, 
        "background": null, 
        "locale": "en", 
        "parent": null, 
        "children": [], 
        "routes": [ 
        { 
        "path": "/", 
        "host": "", 
        "schemes": [], 
        "methods": [], 
        "defaults": { 
        "_content_id": "AppBundle\\\\Entity\\\\Page:7" 
        }, 
        "requirements": [], 
        "options": [], 
        "condition": "", 
        "compiled": null, 
        "id": 12, 
        "content": null, 
        "static_prefix": "/en", 
        "variable_pattern": null, 
        "need_recompile": false, 
        "name": "en", 
        "position": 0 
        } 
        ], 
        "updated": "2018-06-08T08:06:19+08:00", 
        "url": null, 
        "parent_id": null 
        } 
        ], 
        "routes": [ 
        { 
        "path": "/", 
        "host": "", 
        "schemes": [], 
        "methods": [], 
        "defaults": { 
        "_content_id": "AppBundle\\\\Entity\\\\Page:2" 
        }, 
        "requirements": [], 
        "options": [], 
        "condition": "", 
        "compiled": null, 
        "id": 8, 
        "content": null, 
        "static_prefix": "/fr", 
        "variable_pattern": null, 
        "need_recompile": false, 
        "name": "fr", 
        "position": 0 
        } 
        ], 
        "updated": "2018-06-08T18:18:01+08:00", 
        "url": "fr", 
        "parent_id": null 
        }', true);
        $this->assertTrue(
            $arrayResponse === $homeTest
        );
    }

    public function testGetOneVersionHome()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/home/fr/1');
        $response = $client->getResponse();
        $arrayResponse = json_decode($response, true);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetVersionHome()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/home/2/versions');
        $response = $client->getResponse();
        $arrayResponse = json_decode($response, true);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testPutHome()
    {
            $client = self::createClient();
            $crawler = $client->request(
                'PUT',
                '/api/home/2',
                array(),
                array(),
                array('CONTENT_TYPE' => 'application/json'),
                '{ 
                        "title": "Page de Test", 
                        "sub_title": "Une page pour tester", 
                        "description": "meta", 
                        "content": { 
                        "intro": "Une page pour vérifier le module CMS", 
                        "sections": [ 
                        { 
                        "title": "", 
                        "body": "", 
                        "slides": [ 
                        { 
                        "layout": "1-1-2", 
                        "images": [ 
                        { 
                        "type": "", 
                        "url": "", 
                        "alt": "", 
                        "video": "" 
                        }, 
                        { 
                        "type": "", 
                        "url": "", 
                        "alt": "", 
                        "video": "" 
                        }, 
                        { 
                        "type": "", 
                        "url": "", 
                        "alt": "", 
                        "video": "" 
                        }, 
                        { 
                        "type": "", 
                        "url": "", 
                        "alt": "", 
                        "video": "" 
                        } 
                        ] 
                        } 
                        ] 
                        } 
                        ] 
                        }, 
                        "background": null, 
                        "locale": "fr", 
                        "parent": null, 
                        "children": [], 
                        "routes": [ 
                        { 
                        "path": "/", 
                        "host": "", 
                        "schemes": [], 
                        "methods": [], 
                        "defaults": { 
                        "_content_id": "AppBundle\\\\Entity\\\\Page:2" 
                        }, 
                        "requirements": [], 
                        "options": [], 
                        "condition": "", 
                        "compiled": null, 
                        "id": 8, 
                        "content": null, 
                        "static_prefix": "/fr", 
                        "variable_pattern": null, 
                        "need_recompile": false, 
                        "name": "fr", 
                        "position": 0 
                        } 
                        ], 
                        "updated": "2018-06-08T18:18:01+08:00", 
                        "url": "fr", 
                        "parent_id": null 
                        }'
            );

            $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    
    public function testBadPutHome()
    {
            $client = self::createClient();
            $crawler = $client->request(
                'PUT',
                '/api/home/4000000',
                array(),
                array(),
                array('CONTENT_TYPE' => 'application/json'),
                '{
                "title": "Mon testqsfdv",
                "sub_title": "",
                "description": "",
                "content": [],
                "background": null,
                "locale": "de",
                "parent": null,
                "children": []
            }'
            );
            $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testReturnedJsonVersionLogHome()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/home/2/versions');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $response = $client->getResponse();
        $response = $response->getContent();
        $arrayResponse = json_decode($response, true);
        if (empty($arrayResponse)) {
            $empty = true;
        } else {
            $empty = false;
        }
        $this->assertTrue(
            $empty === false
        );
    }

    public function testBadGetOneHome()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/home/ch');
        $response = $client->getResponse();
        $arrayResponse = json_decode($response, true);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testBadGetVersionHome()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/api/home/200000/versions');
        $response = $client->getResponse();
        $arrayResponse = json_decode($response, true);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
